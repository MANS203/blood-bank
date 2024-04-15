<?php

use App\Http\Controllers\Api\v1\AuthController;
use App\Http\Controllers\Api\v1\MainController;
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
    return $request->user();
});

Route::prefix('v1')->group(function () {
    Route::get('/governorates', [MainController::class, 'governorates']);
    Route::get('/cities', [MainController::class, 'cities']);
    Route::get('/logs', [MainController::class, 'logs']);
    Route::get('/bloodTypes', [MainController::class, 'BloodTypes']);
    Route::post('/testNotify', [MainController::class, 'testNotification']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/forgetPassword', [AuthController::class, 'ForgetPassword']);
    Route::post('/newPassword', [AuthController::class, 'NewPassword']);
    Route::get('/settings', [MainController::class, 'settings']);
    Route::get('/logs', [MainController::class, 'logs']);



    Route::group(['middleware'=>'auth:api'],function(){
        Route::post('/donationRequest/create', [MainController::class, 'donationRequestCreate']);
        Route::post('/notificationSettings', [MainController::class, 'notificationSettings']);
        Route::get('/donationRequests', [MainController::class, 'donationRequests']);
        Route::post('/donationRequest', [MainController::class, 'donationRequest']);
        Route::get('/posts',[MainController::class,'posts']);
        Route::get('/post',[MainController::class,'post']);
        Route::post('/favourite',[MainController::class,'postFavourite']);
        Route::get('/myPosts',[MainController::class,'myPosts']);
        Route::get('/categories',[MainController::class,'categories']);
        Route::post('/profile',[AuthController::class,'profile']);
        Route::post('/contacts', [MainController::class, 'contacts']);
        Route::post('/registerToken', [AuthController::class, 'registerToken']);
        Route::delete('/removeToken', [AuthController::class, 'removeToken']);
        Route::get('/notificationsCount', [MainController::class, 'notificationsCount']);
        Route::get('/notifications', [MainController::class, 'notifications']);
    });
});
