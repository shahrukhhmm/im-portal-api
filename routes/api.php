<?php

use App\Http\Controllers\ActionController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BackendController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\EntityController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\BrandLanguageController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::prefix('password')->group(function(){
        Route::post('forgot' , [PasswordResetController::class , 'forgot']);
        Route::post('reset' , [PasswordResetController::class , 'reset']);
    });
});

Route::group(['middleware' => 'auth:api'], function () {

    Route::prefix('auth')->group(function () {
        Route::get('/logout', [AuthController::class, 'logout']);
        Route::get('/getUserInfo', [AuthController::class, 'getUserInfo'])->name('getUserInfo');
        Route::get('/getAppInfo/{appId}', [AuthController::class, 'getAppInfo'])->name('getAppInfo');
    });

    Route::prefix('application')->group(function () {
        Route::get('/', [ApplicationController::class, 'index'])->name('application.index');
        Route::post('/', [ApplicationController::class, 'store'])->name('application.store');
        Route::get('/getApplicationBrands', [ApplicationController::class, 'getApplicationBrands'])->name('application.getApplicationBrands');
        Route::get('/{id}', [ApplicationController::class, 'show'])->name('application.show');
        Route::put('/{id}', [ApplicationController::class, 'update'])->name('application.update');
        Route::put('/setting/{id}', [ApplicationController::class, 'appSetting'])->name('application.appSetting');
        Route::get('/getSetting/{id}', [ApplicationController::class, 'getSetting'])->name('application.getSetting');
        Route::delete('/{id}', [ApplicationController::class, 'destroy'])->name('application.destroy');
        Route::put('/setApplicationBrand/{appId}/{brandId}/{status}', [ApplicationController::class, 'setApplicationBrand'])->name('application.setApplicationBrand');
        Route::put('/setApplicationUser/{appId}/{userId}/{roleId}/{status}', [ApplicationController::class, 'setApplicationUser'])->name('application.setApplicationUser');
        Route::get('/getApplicationBrand/{appId}', [ApplicationController::class, 'getApplicationBrand'])->name('application.getApplicationBrand');
        Route::get('/getApplicationUser/{appId}', [ApplicationController::class, 'getApplicationUser'])->name('application.getApplicationUser');
    });

    Route::prefix('brand')->group(function () {
        Route::get('/', [BrandController::class, 'index'])->name('brand.index');
        Route::post('/', [BrandController::class, 'store'])->name('brand.store');
        Route::get('/{id}', [BrandController::class, 'show'])->name('brand.show');
        Route::put('/{id}', [BrandController::class, 'update'])->name('brand.update');
        Route::post('/assignToUser', [BrandController::class, 'assignToUser'])->name('brand.assignToUser');
        Route::post('/attachUser', [BrandController::class, 'attachUser'])->name('brand.attachUser');
        Route::post('/detachUser', [BrandController::class, 'detachUser'])->name('brand.detachUser');
        Route::delete('/{id}', [BrandController::class, 'destroy'])->name('brand.destroy');
        Route::get('/getByUserId/{userId}', [BrandController::class, 'getByUserId'])->name('brand.getByUserId');
        Route::put('/setBrandUser/{brandId}/{userId}/{status}', [BrandController::class, 'setBrandUser'])->name('brand.setBrandUser');
        Route::get('/getBrandUser/{brandId}', [BrandController::class, 'getBrandUser'])->name('brand.getBrandUser');
        Route::prefix('languages')->controller(BrandLanguageController::class)->group(function () {
            Route::get('/options' , 'options');
            Route::get('/{brand}' , 'index');
            Route::post('/{brand}' , 'create');
            Route::delete('/{brandLanguage}' , 'delete');

        });
    });

    Route::prefix('user')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('user.index');
        Route::get('/getApplicationList', [UserController::class, 'getApplicationList'])->name('user.getApplicationList');
        Route::post('/register', [UserController::class, 'register'])->name('user.register');
        Route::put('/{id}', [UserController::class, 'update'])->name('user.update');
        Route::delete('/{id}', [UserController::class, 'destroy'])->name('user.destroy');
        Route::get('/{id}', [UserController::class, 'show'])->name('user.show');
        Route::get('/getProfile', [UserController::class, 'getProfile'])->name('user.getProfile');
        Route::put('/setUserBrand/{userId}/{brandId}/{status}', [UserController::class, 'setUserBrand'])->name('user.setUserBrand');
        Route::get('/getUserBrand/{id}', [UserController::class, 'getUserBrand'])->name('user.getUserBrand');
        Route::put('/setUserApplication/{userId}', [UserController::class, 'setUserApplication'])->name('user.setUserApplication');
        Route::put('/setUserApplications/{userId}', [UserController::class, 'setUserApplications'])->name('user.setUserApplications');
        Route::get('/getUserApplications/{userId}', [UserController::class, 'getUserApplications'])->name('user.getUserApplications');
        Route::get('/getBrandUsers/{brand}', [UserController::class, 'getBrandUsers'])->name('user.getBrandUsers');

    });

    Route::prefix('organization')->group(function () {
        Route::get('/', [OrganizationController::class, 'index'])->name('organization.index');
        Route::post('/', [OrganizationController::class, 'store'])->name('organization.store');
        Route::get('/{id}', [OrganizationController::class, 'show'])->name('organization.show');
        Route::put('/{id}', [OrganizationController::class, 'update'])->name('organization.update');
        Route::delete('/{id}', [OrganizationController::class, 'destroy'])->name('organization.destroy');
    });

    Route::prefix('role')->group(function () {
        Route::get('/', [RoleController::class, 'index'])->name('role.index');
        Route::post('/', [RoleController::class, 'store'])->name('role.store');
        Route::put('/setRoleAccess', [RoleController::class, 'setRoleAccess'])->name('role.setRoleAccess');
        Route::get('/{id}', [RoleController::class, 'show'])->name('role.show');
        Route::put('/{id}', [RoleController::class, 'update'])->name('role.update');
        Route::delete('/{id}', [RoleController::class, 'destroy'])->name('role.destroy');
        Route::get('/getRoleAccess/{appId}/{roleId}', [RoleController::class, 'getRoleAccess'])->name('role.getRoleAccess');
    });

    Route::prefix('log')->group(function () {
        Route::post('/', [LogController::class, 'store'])->name('log.store');
    });

    Route::prefix('entity')->group(function () {
        Route::get('/', [EntityController::class, 'index'])->name('entity.index');
        Route::get('/getEntities/{appId}', [EntityController::class, 'getEntities'])->name('entity.getEntities');
        Route::post('/', [EntityController::class, 'store'])->name('entity.store');
        Route::get('/{id}', [EntityController::class, 'show'])->name('entity.show');
        Route::put('/{id}', [EntityController::class, 'update'])->name('entity.update');
    });

    Route::prefix('action')->group(function () {
        Route::get('/', [ActionController::class, 'index'])->name('action.index');
    });

});

//login
Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::get('/inquiry/{publicKey}', [AuthController::class, 'userInquiry'])->name('userInquiry');
    Route::get('/appInquiry/{publicKey}', [AuthController::class, 'appInquiry'])->name('appInquiry');
    Route::post('/forgotPassword', [AuthController::class, 'forgotPassword'])->name('forgotPassword');
    Route::post('/resetPassword', [AuthController::class, 'resetPassword'])->name('resetPassword');
});

//Backend
Route::prefix('backend')->group(function () {
    Route::get('/generateToken', [BackendController::class, 'generateToken'])->name('generateToken');
    Route::get('/verifyToken', [BackendController::class, 'verifyToken'])->name('verifyToken');
    Route::get('/refreshToken', [BackendController::class, 'refreshToken'])->name('refreshToken');
});

