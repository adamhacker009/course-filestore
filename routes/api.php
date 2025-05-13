<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\ThreadController;
use App\Http\Controllers\UserController;
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

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/logout', [UserController::class, 'logout']);
    Route::post('/change-password', [UserController::class, 'changePassword']);
    Route::post("/delete-account", [UserController::class, 'deleteAccount']);
    Route::post("/upload-file", [FileController::class, 'upload']);
    Route::get('/files/public', [FileController::class, 'indexPublic']);
    Route::get('/files/private', [FileController::class, 'indexUser']);
    Route::get('/files/{file}', [FileController::class, 'download']);
    Route::delete('/files/{file}', [FileController::class, 'delete']);
    Route::post('/files/{file}/thread', [ThreadController::class, 'createThread']);
    Route::get('/files/{file}/threads', [ThreadController::class, 'newThreads']);
    Route::delete('/thread/{id}', [ThreadController::class, 'deleteThread']);
    Route::post('/comments', [CommentController::class, 'publish']);
    Route::put('/comments/{id}', [CommentController::class, 'update']);
    Route::delete('/comments/{id}', [CommentController::class, 'delete']);
    Route::get('/comments/{id}', [CommentController::class, 'getComments']);
});

Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);
