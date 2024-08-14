<?php

namespace App\Http\Controllers;

use App\Models\Customers;
use Illuminate\Http\Request;
use DataTables;

class UserController extends Controller
{
    public function index(Request $request) {
        
        return view('yajra-datatable', compact('data'));
    }
}
