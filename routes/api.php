<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Services\DB;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth')->get('/deals', function (Request $request) {
    $db= new DB();
    $user = auth()->user();
    $accessToken = $user->getAccessToken();
    $search = $request()->query('search');
    $deals = $db->retrieveDeals($user, $accessToken,$search);
    return response()->json($deals);;
});