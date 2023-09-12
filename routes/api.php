<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\MasterRoomController;
use App\Http\Controllers\Api\RentController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\GuestController;
use App\Http\Controllers\Api\LandingpageController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\SettingController;
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
Route::group(['prefix' => 'v1/guest'], function ($router) {
    $router->post('/store', [GuestController::class, 'store'])->name('guest.store');
    $router->get('/list/room', [LandingpageController::class, 'listRoom'])->name('landingpage.list.room');
    $router->get('/list/current-meeting/{room_id}', [LandingpageController::class, 'listCurrentRent'])->name('landingpage.current.event');
});
Route::group(['prefix' => 'v1/room'], function ($router) {
    $router->get('/check-meeting/{room_id}', [RentController::class, 'checkMeeting'])->name('guest.checkMeeting');
    $router->get('/schedule/{room_id}', [RentController::class, 'scheduleMeeting'])->name('guest.schedule.events');
});
Route::group(['middleware' => 'auth:api'], function ($router) {
    $router->group(['prefix' => 'v1/dashboard'], function ($router) {
        $router->get('/', [DashboardController::class, 'dashboard']);
    });
    $router->group(['prefix' => 'v1/auth'], function ($router) {
        $router->get('/get-profile', [UserController::class, 'getProfileUser']);
        $router->post('/logout', [AuthController::class, 'logout']);
    });
    $router->group(['prefix' => 'v1/users'], function ($router) {
        $router->get('/list', [UserController::class, 'index']);
        $router->post('/store', [UserController::class, 'store']);
        $router->post('/update/{id}', [UserController::class, 'update']);
        $router->delete('/delete/{id}', [UserController::class, 'destroy']);
    });
    $router->group(['prefix' => 'v1/room'], function ($router) {
        $router->get('/', [MasterRoomController::class, 'index']);
        $router->post('/store', [MasterRoomController::class, 'store']);
        $router->get('/detail/{id}', [MasterRoomController::class, 'detail']);
        $router->post('/update/{id}', [MasterRoomController::class, 'update']);
        $router->delete('/delete/{id}', [MasterRoomController::class, 'destroy']);
        $router->get('/qrcode/{id}', [MasterRoomController::class, 'createQrcode']);
    });
    $router->group(['prefix' => 'v1/rent'], function ($router) {
        $router->get('/', [RentController::class, 'index']);
        $router->get('/calendar', [RentController::class, 'listCalendar']);
        $router->get('/list-person-responsible', [RentController::class, 'listPersonResponsible']);
        $router->get('/list-master-room', [RentController::class, 'selectOptionRoom']);
        $router->post('/store', [RentController::class, 'store']);
        $router->get('/detail/{id}', [RentController::class, 'detail']);
        $router->post('/update/{id}', [RentController::class, 'update']);
        $router->post('/update/status/{id}', [RentController::class, 'updateStatus']);
        $router->delete('/delete/{rent_id}', [RentController::class, 'delete']);
    });
    $router->group(['prefix' => 'v1/report'], function ($router) {
        $router->post('/store-bulk-image/{rent_id}', [ReportController::class, 'storeBulkImage']);
        $router->get('/list-rent', [ReportController::class, 'listRentReport']);
        $router->get('/detail-report/{rent_id}', [ReportController::class, 'detailReportRent']);
        $router->get('/list-guest/{rent_id}', [ReportController::class, 'listGuestByRent']);
        $router->get('/list-rent/pdf', [ReportController::class, 'listReportRentPdf']);
        $router->get('/detail/rent/{id}', [ReportController::class, 'detailReportRent']);
        $router->post('/rent/upload/{rent_id}', [ReportController::class, 'bulkReportAttachment']);
    });
    $router->group(['prefix' => 'v1/participant'], function ($router) {
        $router->get('/list-rent/ongoing', [ReportController::class, 'listParticipantRentOngoing']);
        $router->get('/list-rent/history', [ReportController::class, 'listParticipantRentHistory']);
        $router->get('/list-guest/{rent_id}', [ReportController::class, 'listGuestByRent']);
    });

    $router->group(['prefix' => 'v1/setting'], function ($router) {
        $router->post('/', [SettingController::class, 'setting']);
    });
});