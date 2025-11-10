<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\PerformanceReviewController;
use App\Http\Controllers\DashboardController;
//
use Illuminate\Support\Facades\Artisan;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::resource('employees', EmployeeController::class);
Route::resource('departments', DepartmentController::class);
Route::resource('positions', PositionController::class);
Route::resource('attendances', AttendanceController::class);
Route::resource('performance-reviews', PerformanceReviewController::class);
//
Route::get('/migrate-and-seed', function () {
    // PERINGATAN: HANYA DIGUNAKAN UNTUK PERTAMA KALI DEPLOY
    Artisan::call('migrate:fresh', ['--seed' => true, '--force' => true]);
    return 'Migrasi dan Seeding Selesai! HAPUS ROUTE INI SEKARANG!';
});