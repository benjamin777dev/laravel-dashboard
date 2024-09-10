<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Services\DatabaseService;
use App\Services\ZohoCRM;
use App\Services\SendGrid;
use App\Services\Helper;
use App\Models\Contact;
use App\Models\User;
use App\Jobs\ConvertWebmToMp4;
use League\Csv\Writer;
use ZipArchive;
use Illuminate\Support\Facades\Storage;

// use App\Models\ContactGroups;
// use App\Models\Groups;
// use DataTables;
// use Illuminate\Support\Facades\Validator;
// use App\Rules\ValidMobile;

class EmailController extends Controller
{
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

    public function interSendMail(Request $request) {

        $user = $this->user();
        if (!$user) {
            return redirect('/login');
        }
        $toArray = explode(',', $request['to']);
        $ccArray = explode(',', $request['cc']);
        $bccArray = explode(',', $request['bcc']);
        $isEmailSent = $request['isEmailSent'] == "true" ? true : false;

        $inputData = [
            "to" => $toArray,
            "cc" => $ccArray,
            "bcc" => $bccArray,
            "subject" => $request['subject'],
            "content" => $request['content'],
            "isEmailSent" => $isEmailSent,
            "emailType" => $request['emailType']
        ];

        if($request->hasFile('recordedVideo')) {
            $filePath['videoPath'] = Storage::put('recordData', $request['recordedVideo']);
            $filePath['imgPath'] = Storage::put('recordData', $request['fbImage']);
            ConvertWebmToMp4::dispatch($inputData, $filePath);
        } else {
            if($request['emailType'] == "multiple") {
                $this->sendMultipleEmail($inputData);
            } else {
                $this->sendEmail($inputData);
            }
        }
    }

    public function sendEmail($inputData)
    {
        try {
            $db = new DatabaseService();
            $zoho = new ZohoCRM();
            $user = $this->user();
            $accessToken = $user->getAccessToken();
            $sendgrid = new SendGrid();
            $helper = new Helper();

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
                        throw new \Exception("Email is not available for {$contact->name}");
                    }
                    return [
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

                    $sendEmail = $sendgrid->sendSendGridEmail($sendGridInput);

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

    public function sendMultipleEmail($inputData)
    {
        try {
            $db = new DatabaseService();
            $zoho = new ZohoCRM();
            $user = $this->user();
            $sendgrid = new SendGrid();
            $helper = new Helper();

            $accessToken = $user->getAccessToken();

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
                        throw new \Exception("Email is not available for {$contact->name}");
                    }
                    return [
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

                    $sendEmail = $sendgrid->sendSendGridEmail($sendGridInput);

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

            // Create a temporary file for the CSV
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

    // public function getEmailCreateModal(Request $request)
    // {
    //     $user = $this->user();
    //     if (!$user) {
    //         return redirect('/login');
    //     }

    //     // Retrieve input data from JSON request
    //     $contacts = $request->input('contacts');
    //     $emailType = $request->input('emailType');
    //     $selectedContacts = $request->input('selectedContacts');

    //     // Return the rendered view as a response
    //     return view('emails.email-create', compact('contacts', 'selectedContacts', 'emailType'))->render();
    // }
    public function getSignedUrl($identifier, $filename)
    {
        // Assuming the file path in the S3 bucket is constructed as "{identifier}/{filename}"
        $s3FilePath = "{$identifier}/{$filename}";

        // Check if the file exists in the database or just validate its existence
        $file = File::where('generated_identifier', $identifier)->first();
        
        if (!$file) {
            return response()->json(['error' => 'File not found'], 404);
        }

        // Generate a temporary signed URL from S3 valid for 1 hour
        $expiresAt = now()->addHour();
        $signedUrl = Storage::disk('s3')->temporaryUrl($s3FilePath, $expiresAt);

        // Redirect the user to the signed S3 URL
        return redirect($signedUrl);
    }

}
