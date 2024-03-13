<?php

use App\Http\Controllers\UserProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\FollowerController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\PostCommentController;

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

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout']);

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResources([
        'posts' => PostController::class,
        'user' => UserProfileController::class,
        'posts/{post}/comment' => PostCommentController::class
    ]);

    Route::prefix('/user')->group(function () {
        Route::post('/{user}', [UserProfileController::class, 'updateUserProfile']);
        Route::put('/following/{user}', [FollowerController::class, 'follow']);
        Route::delete('/following/{user}', [FollowerController::class, 'unfollow']);
        Route::get('/followers', [FollowerController::class, 'followers']);
        Route::get('/following', [FollowerController::class, 'following']);
        Route::get('/following/{user}', [FollowerController::class, 'isFollowed']);
    });
});

 