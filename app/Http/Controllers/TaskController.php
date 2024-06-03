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

        $user = auth()->user();
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

}
