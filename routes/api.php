<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\MasterRoomController;
use App\Http\Controllers\Api\RentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'api'], function ($router) {
    Route::group(['prefix' => 'v1/auth'], function ($router) {
        $router->post('/login', [AuthController::class, 'login']);
        $router->get('/get-profile', [AuthController::class, 'getProfileUser']);
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
        $router->get('/list-person-responsible', [RentController::class, 'listPersonResponsible']);
        $router->post('/store', [RentController::class, 'store']);
        $router->get('/detail/{id}', [RentController::class, 'detail']);
        $router->post('/update/{id}', [RentController::class, 'update']);
        $router->delete('/delete/{id}', [RentController::class, 'destroy']);
    });
});