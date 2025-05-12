<?php

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    route::get('/logout', [UserController::class, 'logout']);
    route::post('/change-password', [UserController::class, 'changePassword']);
    route::post("/delete-account", [UserController::class, 'deleteAccount']);
});

route::post('/register', [UserController::class, 'register']);
route::post('/login', [UserController::class, 'login']);
