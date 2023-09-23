<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\VisitorController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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


Route::prefix('admin')->group(function(){

    Route::post('/Register',[AdminController::class,'Register']);
    Route::post('/Login',[AdminController::class,'LoginAdmin']);

    Route::middleware('auth:admin-api')->group(function(){

        Route::get('/TrashedVisitor',[AdminController::class,'TrashedVisitor']);
        Route::post('/Retrieve',[AdminController::class,'RetrieveVisitor']);
        Route::get('/logout',[AdminController::class,'AdminLogout']);
        Route::post('/Delete',[AdminController::class,'DeleteVisitor']);
        Route::post('/Remove',[AdminController::class,'removeVisitor']);

    });  
});

Route::prefix('visitor')->group(function(){

    Route::post('/Register',[VisitorController::class,'Register']);
    Route::post('/Login',[VisitorController::class,'LoginVisitor']);

    Route::middleware('auth:visitor-api')->group(function(){

        Route::get('/logout',[VisitorController::class,'VisitorLogout']);
        Route::get('/visitor',[VisitorController::class,'VisitorData']);

    });  
});