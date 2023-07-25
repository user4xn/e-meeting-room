<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UtilsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [AuthController::class, 'login']);
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::get('/not-authorized', [AuthController::class, 'notAuthorized'])->name('not-authorized');
Route::get('/emailVerification/{hash}', [AuthController::class, 'emailVerification'])->name('users.emailVerification');
Route::get('/berhasil-verifikasi', [AuthController::class, 'successVerification'])->name('success-verification');
Route::get('/cek-verifikasi/{hash}', [AuthController::class, 'checkVerification'])->name('check-verification');
Route::get('/kirim-ulang-verifikasi/{email}', [AuthController::class, 'resendVerification'])->name('resend-verification');
Route::post('/custom-login', [AuthController::class, 'customLogin'])->name('custom-login');
Route::get('/notify-password', [UtilsController::class, 'notifyPassword'])->name('notify-password');

Auth::routes();

Route::middleware(['single.session'])->group(function() {
    Route::post('/custom-logout', [AuthController::class, 'logOut'])->name('custom-logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::prefix('role')->middleware(['auth'])->group(function () { 
        Route::get('/', [RoleController::class, 'index'])->name('roles');
        Route::get('/datatable-roles', [RoleController::class, 'datatableRoles'])->name('datatable-roles');
        Route::get('/tambah', [RoleController::class, 'createRole'])->name('add-role');
        Route::get('/detail-role/{id}', [RoleController::class, 'detailRole'])->name('detail-role');
        Route::post('/store', [RoleController::class, 'storeRole'])->name('store-role');
        Route::get('/edit/{id}', [RoleController::class, 'edit'])->name('edit-role');
        Route::post('/update/{id}', [RoleController::class, 'update'])->name('update-role');
        Route::post('/delete/{id}', [RoleController::class, 'delete'])->name('delete-role');
    });
    
    Route::prefix('data-pengguna')->middleware(['auth'])->group(function () { 
        Route::get('/', [UserController::class, 'allUsers'])->name('users');
        Route::get('/datatable', [UserController::class, 'getData'])->name('get-users');
        Route::get('/detail/{id}', [UserController::class, 'detail'])->name('detail-user');
        Route::put('/update/{id}', [UserController::class, 'update'])->name('update-user');
        Route::post('/delete/{id}', [UserController::class, 'delete'])->name('delete-user');
        Route::get('/tambah', [UserController::class, 'addUser'])->name('add-user');
        Route::get('/edit/{id}', [UserController::class, 'edit'])->name('edit-user');
        Route::post('/store', [UserController::class, 'storeUser'])->name('store-user');
        Route::get('/pengaturan', [UserController::class, 'settingProfile'])->name('setting-profile');
        Route::put('/update', [UserController::class, 'updateProfile'])->name('update-profile');
        Route::put('/read-all-message', [UserController::class, 'readAllMessage'])->name('read-all-message');
        Route::put('/update-password', [UserController::class, 'updateUserPassword'])->name('update-password');
    });

    Route::prefix('room')->middleware(['auth'])->group(function () { 
        Route::get('/', [AppController::class, 'index'])->name('room');
    });
});
