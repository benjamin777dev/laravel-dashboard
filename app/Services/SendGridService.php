<?php

namespace App\Services;

use Exception;
use SendGrid;
use SendGrid\Mail\Mail;
use SendGrid\Mail\Personalization;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\Client\RequestException;
use App\Services\DatabaseService;
use App\Models\SuppressionGroup;
use App\Models\Contact;

class SendGridService
{
    private $sendGridApi = 'https://api.sendgrid.com/v3/';
    protected $sendgrid;
    protected $sendgrid_api_key;

    public function __construct()
    {
        Log::info('Initializing SendGrid');

        $this->sendgrid_api_key = config('services.sendgrid.api_key');
        $this->sendgrid = new SendGrid($this->sendgrid_api_key);

        Log::info('SendGrid initialized');
    }

    public function sendgridSenderList()
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->sendgrid_api_key,
                'Content-Type' => 'application/json',
            ])->get($this->sendGridApi . "senders");

            $responseData = $response->json();

            if (!$response->successful()) {
                Log::error('Sender List Error Response', ['response' => $responseData]);
                throw new Exception('Failed to get sender list');
            }

            // Log::info('Get sender list response', ['response' => $responseData]);

            return $responseData;
        } catch (\Throwable $th) {
            Log::error('Error Get Sender List: ' . $th->getMessage());
            throw new Exception($th->getMessage());
        }
    }

    public function verifySender($senderEmail)
    {
        try {
            $sendGridUsers = $this->sendgridSenderList();
            $verified = false;
            // Log::info('Sender Users List', ['sendGridUsers' => $sendGridUsers]);
            foreach ($sendGridUsers as $sender) {
                if ($sender['from']['email'] == $senderEmail) {
                    $verified = $sender['verified']['status'];
                    break;
                }
            }
            return $verified;
        } catch (\Throwable $th) {
            Log::error('Error Sending Email: ' . $th->getMessage());
            throw new Exception($th->getMessage());
        }
    }

    private function buildPersonalizations($recipientsData, $groupId)
    {
        $personalizations = [];
        foreach ($recipientsData as $data) {
            $personalization = [];
            foreach (['to', 'cc', 'bcc'] as $recipientType) {
                if (isset($data[$recipientType])) {
                    foreach ($data[$recipientType] as $recipient) {
                        $unsubscribeUrl = route('email.unsubscribe', [
                            'userId' => $recipient['id'],
                            'groupId' => $groupId,
                            'hash' => hash('sha256', $recipient['email'])
                        ]);
                        $personalization[] = [
                            "to" => [
                                ["email" => $recipient['email']]
                            ],
                            'substitutions' => [
                                "-unsubscribe_url-" => $unsubscribeUrl
                            ]
                        ];
                    }
                }
            }
            $personalizations[] = $personalization;
        }
        return array_merge(...$personalizations);
    }

    public function sendSendGridEmail($inputEmail, $groupId)
    {
        try {

            $recipientsData = $inputEmail['personalizations'];
            $personalizations = [];
            $inputEmail['subject'] = $inputEmail['personalizations'][0]['subject'];

            foreach ($recipientsData as $data) {
                $personalization = [];
            
                foreach ($data['to'] as $toRecipient) {
                    $unsubscribeUrl = route('email.unsubscribe', [
                        'userId' => $toRecipient['id'],
                        'groupId' => $groupId,
                        'hash' => sha1($toRecipient['email'])
                    ]);
                    
                    $personalization[] = [
                        'to' => [
                            ["email" => $toRecipient['email']]
                            ],
                        'substitutions' => [
                            "-unsubscribe_url-" => $unsubscribeUrl
                            ]
                    ];
                }
            
                if(isset($data['cc'])) {
                    foreach ($data['cc'] as $ccRecipient) {
                        $unsubscribeUrl = route('email.unsubscribe', [
                            'userId' => $ccRecipient['id'],
                            'groupId' => $groupId,
                            'hash' => sha1($ccRecipient['email'])
                        ]);
                
                        $personalization[] = [
                            'to' => [
                                ["email" => $ccRecipient['email']]
                                ],
                            'substitutions' => [
                                "-unsubscribe_url-" => $unsubscribeUrl
                                ]
                        ];
                    }
                }
                if(isset($data['bcc'])) {
                    foreach ($data['bcc'] as $bccRecipient) {
                        $unsubscribeUrl = route('email.unsubscribe', [
                            'userId' => $bccRecipient['id'],
                            'groupId' => $groupId,
                            'hash' => sha1($bccRecipient['email'])
                        ]);
                
                        $personalization[] = [
                            'to' => [
                                ["email" => $bccRecipient['email']]
                                ],
                            'substitutions' => [
                                "-unsubscribe_url-" => $unsubscribeUrl
                                ]
                        ];
                    }
                }
            
                $personalizations[] = $personalization;
            }

            $inputEmail['personalizations'] = array_merge(...$personalizations);
            $inputEmail['content'][0]['value'] .= '<p>Click <a href="-unsubscribe_url-">here</a> to unsubscribe.</p>';
            
            $inputEmailJSON = json_encode($inputEmail);
            Log::info("Input Sendgrid Email" . $inputEmailJSON);

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->sendgrid_api_key,
                'Content-Type' => 'application/json',
            ])->post($this->sendGridApi . "mail/send", $inputEmail);
            Log::info('Raw Response', ['response' => $response]);

            $responseData = $response->json();
            if (!$response->successful()) {
                Log::error('Send Email Error Response', ['response' => $responseData]);
                throw new Exception('Failed to send Email');
            }
            Log::info('Send Email repsonse', ['response' => $responseData]);
            return $responseData;
        } catch (\Throwable $th) {
            Log::error('Error Sending Email: ' . $th->getMessage());
            throw new Exception('Failed to Send email');
        }
    }

    public function checkUserSuppressed($userId, $groupId, $emailType) {
        if(!SuppressionGroup::isSuppressed($userId, $emailType)) {
            try {
                $response = $this->sendgrid->client->asm()->suppressions()->get();

                $suppressions = json_decode($response->body(), true);
    
                foreach ($suppressions as $suppressedEmail) {
                    if ($suppressedEmail['email'] === $userId) {
                        foreach ($suppressedEmail['groups'] as $group) {
                            if ($group['id'] == $groupId) {
                                return true;
                            }
                        }
                    }
                }
            } catch (\Throwable $th) {
                Log::error('SendGrid suppression check failed: ' . $th->getMessage());
                throw new Exception('SendGrid suppression check failed');
            }
    
            return false;
        } else {
            return false;
        }
    }

    public function getIdSuppGroup($groupName) {
        $existingGroupId = $this->findSuppressionGroupByName($groupName);

        if ($existingGroupId) {
            return $existingGroupId;
        } else {
            return $this->createSuppressionGroup($groupName);
        }
    }

    private function findSuppressionGroupByName($groupName) {
        try {
            $response = $this->sendgrid->client->asm()->groups()->get();
            $groups = json_decode($response->body(), true);

            foreach ($groups as $group) {
                if ($group['name'] === $groupName) {
                    return $group['id'];
                }
            }

            return null;
        } catch (Exception $e) {
            Log::error('SendGrid API Error: ' . $e->getMessage());
            return null;
        }
    }

    private function createSuppressionGroup($name)
    {
        $requestData = [
            'name' => $name,
            'description' => "Description for " . $name . " for zPortal",
            'is_default' => false
        ];

        try {
            $response = $this->sendgrid->client->asm()->groups()->post($requestData);

            if ($response->statusCode() == 201) {
                $responseBody = json_decode($response->body(), true);
                return $responseBody['id'];
            } else {
                throw new Exception('Error creating suppression group: ' . $response->body());
            }
        } catch (\Throwable $th) {
            Log::error('SendGrid API Error: ' . $th->getMessage());
            return false;
        }
    }

    public function addUserToSuppressionGroup($groupId, $email) {
        try {
            $requestData = [
                'recipient_emails' => [$email]
            ];

            // $response = Http::withHeaders([
            //     'Authorization' => 'Bearer ' . $this->sendgrid_api_key,
            //     'Content-Type' => 'application/json',
            // ])->post($this->sendGridApi . "groups/" . $groupId ."send", $requestData);
            $response = $this->sendgrid->client->asm()->groups()->_($groupId)->suppressions()->post($requestData);

            if ($response->statusCode() == 201 || $response->statusCode() == 204) {
                Log::info($email . ' has added to suppression group' . $groupId);
                return true;
            } else {
                Log::info("Failed to add users to suppression group: " . $response->body());
                return false;
            }
        } catch (Exception $e) {
            Log::error('Add user failed: ' . $e->getMessage());
            return false;
        }
    }

}