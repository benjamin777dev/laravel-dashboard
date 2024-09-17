<?php

namespace App\Http\Controllers;

use ZipArchive;
use Exception;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Services\DatabaseService;
use App\Services\ZohoCRM;
use App\Services\SendGridService;
use App\Services\Helper;
use App\Models\SuppressionGroup;
use App\Models\User;
use League\Csv\Writer;
use Illuminate\Support\Facades\Storage;

class EmailController extends Controller
{
    private $videoEmailSupGroupId;
    private $regularEmailSupGroupId;
    private $sendgrid;

    public function __construct()
    {
        $sendgrid = new SendGridService();
        $this->sendgrid = $sendgrid;
    }
    protected function guard()
    {
        return Auth::guard();
    }
    public function index(Request $request)
    {
        $db = new DatabaseService();
        $user = $this->user();
        if (!$user) {
            return redirect('/login');
        }
        $accessToken = $user->getAccessToken();
        $contactId = $request->query('contactId');
        $contacts = $db->retreiveContactsHavingEmail($user, $accessToken);
        return view('emails.email-inbox', compact('contacts', 'contactId'));
    }

    public function emailList(Request $request)
    {
        $db = new DatabaseService();
        $user = $this->user();
        $accessToken = $user->getAccessToken();
        $filter = $request->query('filter');
        $toEmail = $request->query('contactId');
        $emails = $db->getEmails($user, $filter, $toEmail);
        $contacts = $db->retreiveContactsHavingEmail($user, $accessToken);
        return view('emails.email-list', compact('contacts', 'emails'))->render();
    }

    private function preProcessEmail($inputData)
    {
        $this->videoEmailSupGroupId = $this->sendgrid->getIdSuppGroup("Video Email Suppression");
        $this->regularEmailSupGroupId = $this->sendgrid->getIdSuppGroup("Regular Email Suppression");
        $groupId = $inputData['emailType'] == "video" ? $this->videoEmailSupGroupId : $this->regularEmailSupGroupId;
        $condition = function($value) use ($inputData) {
            return !SuppressionGroup::isSuppressed($value, $inputData['emailType']);
        };

        $inputData['to'] = array_filter($inputData['to'], $condition);
        $inputData['cc'] = array_filter($inputData['cc'], $condition);
        $inputData['bcc'] = array_filter($inputData['bcc'], $condition);
        $inputData['groupId'] = $groupId;

        return $inputData;
    }

    public function sendEmail(Request $request)
    {
        try {
            $db = new DatabaseService();
            $zoho = new ZohoCRM();
            $user = $this->user();
            $accessToken = $user->getAccessToken();
            $sendgrid = $this->sendgrid;
            $helper = new Helper();

            if (!$user) {
                return redirect('/login');
            }

            $accessToken = $user->getAccessToken();
            $inputData = $request->json()->all();
            $inputData = $this->preProcessEmail($inputData);

            $userVerified = $sendgrid->verifySender($user['verified_sender_email'] ?? $user['email']);
            Log::info('User Verification', ['userVerified' => $userVerified]);

            // Initialize contact arrays
            $contactTypes = ['to', 'cc', 'bcc'];
            $contactData = [];

            foreach ($contactTypes as $type) {
                $emails = [];
                $ids = [];
                if (isset($inputData[$type])) {
                    list($emails, $ids) = $helper->filterEmailsAndNumbers($inputData[$type]);
                }

                // Fetch existing contacts by IDs
                if (!empty($ids)) {
                    $existingContacts = $db->getContactsByMultipleId($ids);
                    Log::info("Combined emails", [$existingContacts]);
                    $contactData[$type]['existing'] = $existingContacts->keyBy('id');
                } else {
                    $contactData[$type]['existing'] = collect([]);
                }

                // Create new contacts if emails are provided
                if (!empty($emails)) {
                    $createContacts = function ($emails) use ($user, $zoho, $db, &$inputData, $contactData, $type) {
                        // Call Zoho API to create contacts
                        $response = $zoho->createMultipleContact($user, $emails);

                        // Create contacts in the database if they don't exist, then filter them
                        $newContacts = $db->createContactIfNotExists($user, $response);

                        // Loop through the new contacts and add their IDs to the input data
                        foreach ($newContacts as $contact) {
                            Log::info("Input Type", [$inputData[$type]]);
                            $emailKey = array_search($contact['email'], $inputData[$type]);
                            if ($emailKey !== false) {
                                $inputData[$type][$emailKey] = (string) $contact['id'];
                            }
                        }

                        // Log the updated inputData
                        Log::info("Updated inputData: ", $inputData);

                        return $newContacts;
                    };


                    $contactData[$type]['new'] = $createContacts($emails);
                } else {
                    $contactData[$type]['new'] = collect([]);
                }

                // Combine existing and new contacts
                $combined = $contactData[$type]['existing']
                    ->merge($contactData[$type]['new']->keyBy('id'))
                    ->keyBy('id')
                    ->values()
                    ->toArray();
                $contactData[$type]['combined'] = $combined;
                Log::info("Combined emails", [$contactData[$type]]);
            }

            // Prepare data for SendGrid and Zoho
            $prepareEmailData = function ($details) {
                return array_map(function ($contact) {
                    // Check if the email field is missing or if contact is an array
                    if (is_array($contact)) {
                        $contact = (object) $contact; // Convert array to object if necessary
                    }
                    if (empty($contact->email)) {
                        throw new Exception("Email is not available for {$contact->name}");
                    }
                    return [
                        'id' => $contact->id,
                        'user_name' => $contact->name,
                        'email' => $contact->email,
                    ];
                }, $details);
            };


            $inputData['toData'] = $prepareEmailData($contactData['to']['combined']);
            $inputData['ccData'] = $prepareEmailData($contactData['cc']['combined']);
            $inputData['bccData'] = $prepareEmailData($contactData['bcc']['combined']);

            $zohoInput = [
                "data" => [
                    [
                        'to' => $inputData['toData'],
                        'cc' => $inputData['ccData'],
                        'bcc' => $inputData['bccData'],
                        'from' => [
                            "user_name" => $user['name'],
                            'email' => $user['email'],
                        ],
                        'subject' => $inputData['subject'],
                        'content' => $inputData['content'],
                        "consent_email" => false,
                    ]
                ]
            ];

            $contact = $db->retrieveContactByEmail($user, $accessToken, $user['email']);

            if ($inputData['isEmailSent'] === true) {
                if ($userVerified) {
                    $sendGridInput = [
                        'personalizations' => [
                            [
                                'to' => $inputData['toData'],
                                'subject' => $inputData['subject']
                            ]
                        ],
                        'from' => [
                            "name" => $user['name'],
                            'email' => $user['email'],
                        ],
                        'content' => [
                            [
                                'type' => 'text/html',
                                'value' => $inputData['content']
                            ]
                        ]
                    ];

                    if (!empty($inputData['ccData'])) {
                        $sendGridInput['personalizations'][0]['cc'] = $inputData['ccData'];
                    }
                    if (!empty($inputData['bccData'])) {
                        $sendGridInput['personalizations'][0]['bcc'] = $inputData['bccData'];
                    }

                    $sendEmail = $sendgrid->sendSendGridEmail($sendGridInput, $inputData['groupId']);

                    $associateZohoInput = [
                        "Emails" => [
                            [
                                'to' => $inputData['toData'],
                                'cc' => $inputData['ccData'],
                                'bcc' => $inputData['bccData'],
                                'from' => [
                                    "user_name" => $user['name'],
                                    'email' => $user['email'],
                                ],
                                'subject' => $inputData['subject'],
                                'content' => $inputData['content'],
                                "consent_email" => false,
                            ]
                        ]
                    ];

                    $associateZohoInput['Emails'][0] += [
                        'sent' => true,
                        'date_time' => now(),
                        'original_message_id' => Str::uuid(),
                    ];

                    $associateEmail = $zoho->associateEmail($associateZohoInput, $contact['zoho_contact_id']);
                    if ($associateEmail === "AUTHENTICATION_FAILURE") {
                        $this->guard()->logout();
                        return response()->json([
                            'status' => 'process',
                            'message' => 'AUTHENTICATION_FAILURE, Please Re-signup in ZOHO',
                            'redirect_url' => route('login')
                        ]);
                    }

                    $inputData['sendEmailFrom'] = "SendGrid";
                    $inputData['message_id'] = $associateEmail['Emails'][0]['details']['message_id'];
                } else {
                    $sendEmail = $zoho->sendZohoEmail($zohoInput, $contact['zoho_contact_id']);
                    if ($sendEmail === "AUTHENTICATION_FAILURE") {
                        $this->guard()->logout();
                        return response()->json([
                            'status' => 'process',
                            'message' => 'AUTHENTICATION_FAILURE, Please Re-signup in ZOHO',
                            'redirect_url' => route('login')
                        ]);
                    }
                    $inputData['sendEmailFrom'] = "Zoho";
                    $inputData['message_id'] = $sendEmail['data'][0]['details']['message_id'];
                }
            }
            $response = $db->saveEmail($user, $accessToken, $inputData);
            return response()->json($response);
        } catch (\Throwable $th) {
            Log::error('Error sending email', ['error' => $th->getMessage()]);
            throw $th;
        }
    }

    public function sendMultipleEmail(Request $request)
    {
        try {
            $db = new DatabaseService();
            $zoho = new ZohoCRM();
            $user = $this->user();
            $sendgrid = $this->sendgrid;
            $helper = new Helper();

            if (!$user) {
                return redirect('/login');
            }

            $accessToken = $user->getAccessToken();
            $inputData = $request->json()->all();
            $inputData = $this->preProcessEmail($inputData);

            $userVerified = $sendgrid->verifySender($user['verified_sender_email'] ?? $user['email']);
            Log::info('User Verification', ['userVerified' => $userVerified]);

            // Initialize contact arrays
            $contactTypes = ['to', 'cc', 'bcc'];
            $contactData = [];

            foreach ($contactTypes as $type) {
                $emails = [];
                $ids = [];
                if (isset($inputData[$type])) {
                    list($emails, $ids) = $helper->filterEmailsAndNumbers($inputData[$type]);
                }

                // Fetch existing contacts by IDs
                if (!empty($ids)) {
                    $existingContacts = $db->getContactsByMultipleId($ids);
                    Log::info("Combined emails", [$existingContacts]);
                    $contactData[$type]['existing'] = $existingContacts->keyBy('id');
                } else {
                    $contactData[$type]['existing'] = collect([]);
                }

                // Create new contacts if emails are provided
                if (!empty($emails)) {
                    $createContacts = function ($emails) use ($user, $zoho, $db, &$inputData, $contactData, $type) {
                        // Call Zoho API to create contacts
                        $response = $zoho->createMultipleContact($user, $emails);
                        // Create contacts in the database if they don't exist, then filter them
                        $newContacts = $db->createContactIfNotExists($user, $response);

                        // Loop through the new contacts and add their IDs to the input data
                        foreach ($newContacts as $contact) {
                            $emailKey = array_search($contact['email'], $inputData[$type]);
                            if ($emailKey !== false) {
                                $inputData[$type] = (string) $contact['id'];
                            }
                        }

                        // Log the updated inputData
                        Log::info("Updated inputData: ", $inputData);

                        return $newContacts;
                    };


                    $contactData[$type]['new'] = $createContacts($emails);
                } else {
                    $contactData[$type]['new'] = collect([]);
                }

                // Combine existing and new contacts
                $combined = $contactData[$type]['existing']
                    ->merge($contactData[$type]['new']->keyBy('id'))
                    ->keyBy('id')
                    ->values()
                    ->toArray();
                $contactData[$type]['combined'] = $combined;
                Log::info("Combined emails", [$contactData[$type]]);
            }

            // Prepare data for SendGrid and Zoho
            $prepareEmailData = function ($details) {
                return array_map(function ($contact) {
                    // Check if the email field is missing or if contact is an array
                    if (is_array($contact)) {
                        $contact = (object) $contact; // Convert array to object if necessary
                    }
                    if (empty($contact->email)) {
                        throw new Exception("Email is not available for {$contact->name}");
                    }
                    return [
                        'id' => $contact->id,
                        'user_name' => $contact->name,
                        'email' => $contact->email,
                    ];
                }, $details);
            };


            $inputData['toData'] = $prepareEmailData($contactData['to']['combined']);
            $inputData['ccData'] = $prepareEmailData($contactData['cc']['combined']);
            $inputData['bccData'] = $prepareEmailData($contactData['bcc']['combined']);

            $zohoInput = [
                "data" => []
            ];

            $DBInput = [];
            foreach ($inputData['to'] as $toData) {
                $DBInput[] = [
                    'to' => [$toData],
                    'cc' => $inputData['ccData'],
                    'bcc' => $inputData['bccData'],
                    'from' => [
                        "user_name" => $user['name'],
                        'email' => $user['email'],
                    ],
                    'subject' => $inputData['subject'],
                    'content' => $inputData['content'],
                    "isEmailSent" => false,
                ];

                $zohoInput['data'][] = [
                    'to' => [$toData],
                    'cc' => $inputData['ccData'],
                    'bcc' => $inputData['bccData'],
                    'from' => [
                        "user_name" => $user['name'],
                        'email' => $user['email'],
                    ],
                    'subject' => $inputData['subject'],
                    'content' => $inputData['content'],
                    "consent_email" => false,
                ];
            }

            $contact = $db->retrieveContactByEmail($user, $accessToken, $user['email']);

            if ($inputData['isEmailSent'] === true) {
                if ($userVerified) {
                    Log::info("RECIEPNTS TOTAL", [$inputData['toData']]);

                    $sendGridInput = [
                        'personalizations' => [],
                        'from' => [
                            "name" => $user['name'],
                            'email' => $user['email'],
                        ],
                        'content' => [
                            [
                                'type' => 'text/html',
                                'value' => $inputData['content']
                            ]
                        ]
                    ];
                    foreach ($inputData['toData'] as $toData) {
                        $sendGridInput['personalizations'][] = [
                            "to" => [$toData],
                            'subject' => $inputData['subject']
                        ];
                    }
                    if (!empty($inputData['ccData'])) {
                        $sendGridInput['personalizations'][0]['cc'] = $inputData['ccData'];
                    }
                    if (!empty($inputData['bccData'])) {
                        $sendGridInput['personalizations'][0]['bcc'] = $inputData['bccData'];
                    }

                    $sendEmail = $sendgrid->sendSendGridEmail($sendGridInput, $inputData['groupId']);

                    $associateZohoInput = [
                        "Emails" => []
                    ];

                    foreach ($inputData['toData'] as $index => $toData) {
                        $associateZohoInput['Emails'][0] = [
                            'to' => [$toData],
                            'cc' => $inputData['ccData'],
                            'bcc' => $inputData['bccData'],
                            'from' => [
                                "user_name" => $user['name'],
                                'email' => $user['email'],
                            ],
                            'subject' => $inputData['subject'],
                            'content' => $inputData['content'],
                            "consent_email" => false,
                            'sent' => true,
                            'date_time' => now(),
                            'original_message_id' => Str::uuid(),
                        ];
                        $associateEmail = $zoho->associateEmail($associateZohoInput, $contact['zoho_contact_id']);
                        if ($associateEmail === "AUTHENTICATION_FAILURE") {
                            $this->guard()->logout();
                            return response()->json([
                                'status' => 'process',
                                'message' => 'AUTHENTICATION_FAILURE, Please Re-signup in ZOHO',
                                'redirect_url' => route('login')
                            ]);
                        }
                        if (isset($associateEmail['Emails'][0]['details']['message_id'])) {
                            $DBInput[$index]['message_id'] = $associateEmail['Emails'][0]['details']['message_id'];
                            $DBInput[$index]['sendEmailFrom'] = 'SendGrid';
                        }
                    }
                } else {
                    $sendEmail = $zoho->sendMultipleZohoEmail($zohoInput, $contact['zoho_contact_id']);
                    if ($sendEmail === "AUTHENTICATION_FAILURE") {
                        $this->guard()->logout();
                        return response()->json([
                            'status' => 'process',
                            'message' => 'AUTHENTICATION_FAILURE, Please Re-signup in ZOHO',
                            'redirect_url' => route('login')
                        ]);
                    }
                    $inputData['sendEmailFrom'] = "Zoho";
                    $inputData['message_id'] = $sendEmail['data'][0]['details']['message_id'];
                }
            }

            $response = $db->saveMultipleEmail($user, $accessToken, $DBInput);
            return response()->json($response);
        } catch (\Throwable $th) {
            Log::error('Error sending email', ['error' => $th->getMessage()]);
            throw $th;
        }
    }

    public function associateMultipleEmail($associateInput, $contactId)
    {
        try {
            $zoho = new ZohoCRM();
            $user = $this->user();

            if (!$user) {
                return redirect('/login');
            }

            $csvResponse = $this->exportCsv($associateInput, $contactId);
            $zipFilename = $csvResponse->getData()->zip_filename;
            $zipPath = storage_path('app/' . $zipFilename);
            $FileId = $zoho->uploadZipFile($zipPath);
            Log::info("CSV FILE", [$FileId]);
            $fileId = $FileId['details']['file_id'];
            //Bulk Write
            $bulkJob = $zoho->associateEmailBulk($fileId, $contactId);
            $jobID = $bulkJob['details']['id'];
            return response()->json($csvResponse);
        } catch (\Throwable $th) {
            Log::error('Error sending email', ['error' => $th->getMessage()]);
            throw $th;
        }
    }

    public function exportCsv($associateInput, $contactId)
    {
        try {
            $csv = Writer::createFromString('');
            $csv->insertOne(['to_email', 'cc_email', 'bcc_email', 'from_email', 'subject', 'content', 'date_time', 'original_message_id']);

            // Process JSON data and write to CSV
            foreach ($associateInput as $item) {
                $toEmails = implode(', ', array_column($item['to'], 'email'));
                $ccEmails = implode(', ', $item['cc']);
                $bccEmails = implode(', ', $item['bcc']);
                $fromEmail = $item['from']['email'];
                $subject = $item['subject'];
                $content = strip_tags($item['content']); // Remove HTML tags for CSV
                $dateTime = $item['date_time'];
                $originalMessageId = $item['original_message_id'];

                $csv->insertOne([
                    $toEmails,
                    $ccEmails,
                    $bccEmails,
                    $fromEmail,
                    $subject,
                    $content,
                    $dateTime,
                    $originalMessageId
                ]);
            }

            // Prepare the response
            $csvContent = $csv->getContent();
            $csvFilename = 'emails_' . date('Y_m_d_H_i_s') . '.csv';

            $tempCsvPath = storage_path('app/' . $csvFilename);
            file_put_contents($tempCsvPath, $csvContent);

            // Create a zip archive
            $zip = new ZipArchive;
            $zipFilename = 'emails_' . date('Y_m_d_H_i_s') . '.zip';
            $zipPath = storage_path('app/' . $zipFilename);

            if ($zip->open($zipPath, ZipArchive::CREATE) === TRUE) {
                // Add the CSV file to the zip archive
                $zip->addFile($tempCsvPath, $csvFilename);
                $zip->close();
            }

            // Remove the temporary CSV file
            unlink($tempCsvPath);

            // Return the zip file name
            return response()->json(['zip_filename' => $zipFilename]);

        } catch (\Throwable $th) {
            Log::error('Error sending email', ['error' => $th->getMessage()]);
            throw $th;
        }
    }
    public function emailDetail(Request $request)
    {
        $db = new DatabaseService();
        $user = $this->user();
        if (!$user) {
            return redirect('/login');
        }
        $accessToken = $user->getAccessToken();

        $emailId = $request->route('emailId');
        $email = $db->getEmailDetail($emailId);
        $contacts = $db->retreiveContactsHavingEmail($user, $accessToken);
        // return response()->json($email);
        return view('emails.email-read', compact('contacts', 'email'))->render();
    }

    public function emailDetailDraft(Request $request)
    {
        $db = new DatabaseService();
        $user = $this->user();
        if (!$user) {
            return redirect('/login');
        }
        $accessToken = $user->getAccessToken();

        $emailId = $request->route('emailId');
        $email = $db->getEmailDetail($emailId);    
        $contacts = $db->retreiveContactsHavingEmail($user, $accessToken);
        return view('emails.email-draft', compact('contacts', 'email'))->render();
    }

    public function emailTemplate(Request $request)
    {
        $db = new DatabaseService();
        $user = $this->user();
        if (!$user) {
            return redirect('/login');
        }
        $accessToken = $user->getAccessToken();

        $emailId = $request->route('emailId');
        $email = $db->getEmailDetail($emailId);    
        $contacts = $db->retreiveContactsHavingEmail($user, $accessToken);
        return view('emails.email-template')->render();
    }

    public function emailMoveToTrash(Request $request)
    {
        $db = new DatabaseService();
        $user = $this->user();
        if (!$user) {
            return redirect('/login');
        }
        $accessToken = $user->getAccessToken();

        $emailIds = $request->input('emailIds');
        $isDeleted = $request->input('isDeleted');
        $email = $db->moveToTrash($emailIds, $isDeleted);
        return response()->json(['success' => true]);
    }

    public function emailDelete(Request $request)
    {
        $db = new DatabaseService();
        $user = $this->user();
        if (!$user) {
            return redirect('/login');
        }
        $accessToken = $user->getAccessToken();

        $emailIds = $request->input('emailIds');
        $email = $db->emailDelete($emailIds);    
        return response()->json(['success' => true]);
    }

    public function getEmailModal()
    {
        $user = $this->user();
        if (!$user) {
            return redirect('/login');
        }
        $db = new DatabaseService();
        $emailId = request()->route('emailId');
        $accessToken = $user->getAccessToken();
        $email = $db->getEmailDetail($emailId);    
        return view('emails.email-read-modal', compact('email'))->render();
    }

    public function getSignedUrl($identifier, $filename)
    {
        try {
            $s3FilePath = "{$identifier}/{$filename}";
            $expiresAt = now()->addHour();
            $signedUrl = Storage::disk('s3')->temporaryUrl($s3FilePath, $expiresAt);
    
            return redirect($signedUrl);
        } catch (Exception $e) {
            Log::error("Error generating signed URL: " . $e->getMessage());
    
            return redirect()->back()->with('error', 'There was an issue generating the signed URL. Please try again later.');
        }
    }

    public function unsubscribe(Request $request, $userId, $groupId, $hash) 
    {
        $user = Contact::find($userId);
        if (!$user || !hash_equals($hash, hash('sha256', $user->email))) {
            return redirect('/')->with('error', 'Invalid unsubscribe link.');
        }

        $suppression = SuppressionGroup::where("user_id", $userId)->first(); 
        if($suppression == null) {
            $suppression = new SuppressionGroup();
            $suppression->user_id = $user->id;
        }

        $result = $this->sendgrid->addUserToSuppressionGroup($groupId, $user->email);
        $this->videoEmailSupGroupId = $this->sendgrid->getIdSuppGroup("Video Email Suppression");
        if($result) {
            if ($groupId == $this->videoEmailSupGroupId) {
                $suppression['video_emails'] = true;
            } else {
                $suppression['regular_emails'] = true;
            }
            $suppression->save();
            return redirect('/')->with('success', 'You have been unsubscribed successfully.');
        } else {
            return redirect('/')->with('error', 'Suppression group add failed.');
        }
    }

}
