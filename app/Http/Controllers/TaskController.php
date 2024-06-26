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
        $accessToken = $user->getAccessToken();

        // Fetch tasks for each category
        $upcomingTasks = $db->retreiveTasks($user, $accessToken, 'Upcoming');
        $inProgressTasks = $db->retreiveTasks($user, $accessToken, 'Due Today');
        $completedTasks = $db->retreiveTasks($user, $accessToken, 'Completed');
        $overdueTasks = $db->retreiveTasks($user, $accessToken, 'Overdue');

        $getdealsTransaction = $db->retrieveDeals($user, $accessToken);
        $retrieveModuleData = $db->retrieveModuleDataDB($user, $accessToken);

        return view('task.index', compact('upcomingTasks', 'inProgressTasks', 
            'completedTasks', 'getdealsTransaction', 'retrieveModuleData', 'overdueTasks'));
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
            return redirect('/pipeline');
        } 
        $tasks = $db->retreiveTasksForDeal($user, $accessToken, $tab, $deal->zoho_deal_id);
        $retrieveModuleData =  $db->retrieveModuleDataDB($user,$accessToken);

       return view('common.tasks',
            compact('tasks','deal','retrieveModuleData','tab'));
    }

}
