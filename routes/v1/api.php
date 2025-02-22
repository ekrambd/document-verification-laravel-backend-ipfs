<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\LogController;

//auth
 Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
   //logout
   Route::post('logout', [AuthController::class, 'logout']);

   Route::get('/user-details', [AuthController::class, 'userDetails']);

   //documents

   Route::apiResource('documents', DocumentController::class);

   Route::post('save-document-log', [LogController::class, 'saveDocumentLog']);

});