<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DrugController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(['middleware'=>'api'],function($routes){
    Route::post('/register', [UserController::class, 'register']);
    Route::post('/login', [UserController::class, 'login']);
    Route::get('/refresh-token', [UserController::class, 'refreshToken']);
    Route::get('/logout', [UserController::class, 'logout']);
    Route::post('/drug/add', [DrugController::class, 'add']);
    Route::post('/drug/update', [DrugController::class, 'update']);
    Route::get('/drug/details/{id}', [DrugController::class, 'details']);
    Route::get('/drug/list', [DrugController::class, 'list']);
    Route::delete('/drug/delete/{id}', [DrugController::class, 'delete']);
});

