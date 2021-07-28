<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CardsController;
use App\Http\Controllers\NotesController;
use App\Http\Controllers\AuthController;


Route::get('/token', function (Request $request) {

    return csrf_token();
});

Route::post('/register', [AuthController::class, 'register']);

Route::post('/login', [AuthController::class, 'login']);


Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::resource('/cards', CardsController::class);
    Route::resource('/notes', NotesController::class);

    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
});

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/