<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\MasterRoomController;
use App\Http\Controllers\Api\RentController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1/auth'], function ($router) {
    $router->post('/login', [AuthController::class, 'login']);
    $router->post('/refresh/token', [AuthController::class, 'refresh']);
    $router->get('/verification/email', [UserController::class, 'emailVerification'])->name('users.emailVerification');
    $router->post('/resend/otp', [AuthController::class, 'resendOtpEmail']);
    $router->post('/resend/verification', [UserController::class, 'resendEmailVerification']);
    $router->post('/verification/otp', [AuthController::class, 'verificationEmailOtp']);
    $router->get('/unauthorized', [AuthController::class, 'unauthorized'])->name('unauthorized');
});
Route::group(['middleware' => 'api'], function ($router) {
    Route::group(['prefix' => 'v1/auth'], function ($router) {
        $router->get('/get-profile', [UserController::class, 'getProfileUser']);
        $router->post('/logout', [AuthController::class, 'logout']);
    });
    Route::group(['prefix' => 'v1/users'], function ($router) {
        $router->get('/list', [UserController::class, 'index']);
        $router->post('/store', [UserController::class, 'store']);
        $router->post('/update/{id}', [UserController::class, 'update']);
        $router->delete('/delete/{id}', [UserController::class, 'destroy']);
    });
    Route::group(['prefix' => 'v1/room'], function ($router) {
        $router->get('/', [MasterRoomController::class, 'index']);
        $router->post('/store', [MasterRoomController::class, 'store']);
        $router->get('/detail/{id}', [MasterRoomController::class, 'detail']);
        $router->post('/update/{id}', [MasterRoomController::class, 'update']);
        $router->delete('/delete/{id}', [MasterRoomController::class, 'destroy']);
    });
    Route::group(['prefix' => 'v1/rent'], function ($router) {
        $router->get('/', [RentController::class, 'index']);
        $router->get('/calendar', [RentController::class, 'listCalendar']);
        $router->get('/list-person-responsible', [RentController::class, 'listPersonResponsible']);
        $router->post('/store', [RentController::class, 'store']);
        $router->get('/detail/{id}', [RentController::class, 'detail']);
        $router->post('/update/{id}', [RentController::class, 'update']);
        $router->delete('/delete/{id}', [RentController::class, 'destroy']);
    });
});