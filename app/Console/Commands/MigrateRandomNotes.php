<?php

namespace App\Console\Commands;

use App\Models\Contact;
use App\Models\Note;
use App\Models\User;
use App\Services\ZohoCRM;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class MigrateRandomNotes extends Command
{
    protected $signature = 'notes:migrate';

    protected $description = 'Migrate random notes from contacts to notes and sync to Zoho';

    private $zoho;

    public function __construct(ZohoCRM $zoho)
    {
        parent::__construct();
        $this->zoho = $zoho;

        $user = User::where('email', 'phillip@coloradohomerealty.com')->first();
        $accessToken = $user->getAccessToken();
        $this->zoho->access_token = $accessToken;


    }

    public function handle()
    {
        $contacts = Contact::whereNotNull('random_notes')
            ->where('random_notes', '!=', '')
            ->get();

        foreach ($contacts as $contact) {
            $note = null;
            Log::info("Looking up owner: " . $contact->contact_owner);
            $userOwner = User::where('root_user_id', $contact->contact_owner)->first();

            if ($userOwner) {
                try {
                    $note = Note::create([
                        'related_to' => $contact->id,
                        'related_to_type' => 'Contacts',
                        'related_to_module_id' => '5141697000000002179',
                        'related_to_parent_record_id' => $contact->zoho_contact_id,
                        'owner' => $userOwner->id,
                        'note_content' => $contact->random_notes,
                        'created_time' => Carbon::now(),
                        'mark_as_done' => '0',
                    ]);

                    Log::info("Note created for Contact ID: {$contact->id}", ['note' => $note]);

                    // Sync the note to Zoho
                    $this->syncToZoho($note, $contact);

                    $this->info("Migrated and synced note for Contact ID: {$contact->id}");

                } catch (\Exception $e) {
                    Log::error("Failed to process note for Contact ID: {$contact->id}. Error: {$e->getMessage()}");
                    $this->error("Failed to process note for Contact ID: {$contact->id}");
                }
            } else {
                Log::warning("No valid user owner found for Contact ID: {$contact->id}. Note creation skipped.");
            }
        }

        $this->info('Random notes migration completed successfully.');
    }

    private function syncToZoho(Note $note, $contact)
    {
        $inputJson = [
            'data' => [
                [
                    'Note_Title' => 'Migrated Note',
                    'Note_Content' => $note->note_content,
                    'Parent_Id' => $contact->zoho_contact_id,
                    'se_module' => 'Contacts',
                ],
            ],
        ];

        
        $response = $this->zoho->createNoteData($inputJson, $contact->zoho_contact_id, 'Notes');

        if (!$response->successful()) {
            Log::error("Failed to sync note ID: {$note->id} to Zoho.", ['response' => $response->json()]);
            throw new \Exception("Failed to sync note ID: {$note->id} to Zoho.");
        }

        $contact->random_notes = null;
        $contact->save();

        Log::info("Successfully synced note ID: {$note->id} to Zoho and cleared random notes.");
    }
}
