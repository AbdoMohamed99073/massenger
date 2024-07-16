<?php

use App\Http\Controllers\ConversationController;
use App\Http\Controllers\MessagesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::middleware('auth:sanctum')->group(function(){
    //conversation routes
    Route::get('/conversations',[ConversationController::class,'index']);
    Route::get('/conversations/{conversation}',[ConversationController::class,'show']);
    Route::post('/conversations/{conversation}/participants',[ConversationController::class,'addParticipant']);
    Route::delete('/conversations/{conversation}/participants',[ConversationController::class,'removeParticipant']);
    
    
    //messages routes
    Route::get('/conversations/{id}/messages',[MessagesController::class,'index']);
    Route::post('/messages',[MessagesController::class,'store'])
        ->name('storemessage');
    Route::delete('/messages/{id}',[MessagesController::class,'destroy']);

});

