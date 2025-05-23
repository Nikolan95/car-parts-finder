<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('user.login');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('user.register');

Route::group(['middleware' => 'auth'], function () {
    Route::get('/', [DashboardController::class, 'index'])->name('index');
    Route::get('/getCatalogInfo', [DashboardController::class, 'catalogInfo'])->name('catalogInfo');


    Route::post('/getVehicleInfo', [DashboardController::class, 'getVehicleInfo'])->name('vehicle.info');

    Route::post('/getGroups', [DashboardController::class, 'getGroups'])->name('vehicle.groups');

    Route::post('/getGroupsList', [\App\Http\Controllers\ListController::class, 'getListGroups'])->name('vehicle.list.groups');

    Route::post('/getGroupParts', [DashboardController::class, 'getGroupParts'])->name('vehicle.group.parts');

    Route::post('/getGroupPartsAll', [DashboardController::class, 'getGroupPartsAll'])->name('vehicle.group.parts.all');

    Route::post('/getUnitParts', [DashboardController::class, 'getUnitParts'])->name('vehicle.unit.parts');

    Route::post('/getCatalogInfo', [DashboardController::class, 'getCatalogInfo'])->name('vehicle.catalog');

    Route::get('/getAftermarketData', [DashboardController::class, 'getAftermarketData'])->name('aftermarket.data');
});
