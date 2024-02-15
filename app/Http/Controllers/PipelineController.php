<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Deal;

class PipelineController extends Controller
{
    public function index()
    {
        $deals = Deal::all();
        return view('pipeline.index', compact('deals'));
    }

    // Add methods for create, store, edit, update, and delete
}
