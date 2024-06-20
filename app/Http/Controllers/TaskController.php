<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Task;
use App\Services\DatabaseService;

class TaskController extends Controller
{
    public function index()
    {

        $user = $this->user();
        if (!$user) {
            return redirect('/login');
        }
        $db = new DatabaseService();
        $tab = request()->query('tab') ?? 'In Progress';
        $accessToken = $user->getAccessToken(); 
        $tasks = $db->retreiveTasks($user, $accessToken,$tab);
        $getdealsTransaction = $db->retrieveDeals($user,$accessToken);
        $retrieveModuleData =  $db->retrieveModuleDataDB($user,$accessToken);

       return view('task.index',
            compact('tasks','getdealsTransaction','retrieveModuleData','tab'));
    }

    public function taskForContact()
    {

        $user = $this->user();
        if (!$user) {
            return redirect('/login');
        }
        $db = new DatabaseService();
        $tab = request()->query('tab') ?? 'In Progress';
        $accessToken = $user->getAccessToken();
        $contactId = request()->route('contactId');
        $contact = $db->retrieveContactById($user, $accessToken, $contactId);
        if (!$contact) {
            return redirect('/contacts');
        } 
        $tasks = $db->retreiveTasksForContact($user, $accessToken, $tab, $contact->zoho_contact_id);
        $retrieveModuleData =  $db->retrieveModuleDataDB($user,$accessToken);

       return view('common.tasks',
            compact('tasks','contact','retrieveModuleData','tab'));
    }

    public function taskForPipeline()
    {

        $user = $this->user();
        if (!$user) {
            return redirect('/login');
        }
        $db = new DatabaseService();
        $tab = request()->query('tab') ?? 'In Progress';
        $accessToken = $user->getAccessToken();
        $dealId = request()->route('dealId');
        $deal = $db->retrieveDealById($user, $accessToken, $dealId);
        if (!$deal) {
            return redirect('/pipelines');
        } 
        $tasks = $db->retreiveTasksForDeal($user, $accessToken, $tab, $deal->zoho_deal_id);
        $retrieveModuleData =  $db->retrieveModuleDataDB($user,$accessToken);

       return view('common.tasks',
            compact('tasks','deal','retrieveModuleData','tab'));
    }

}
