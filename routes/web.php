<?php

use Spatie\PdfToText\Pdf;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ScrapperController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [RegisterController::class, 'create'])->name('register');
Route::get('/logout', [AuthController::class, 'logout']);

Route::get('/dashboard', [ScrapperController::class, 'index'])->middleware('auth');
Route::get('/export', [ScrapperController::class, 'export_excel'])->name('export');
Route::post('/upload', [ScrapperController::class, 'upload'])->name('upload');
Route::get('/delete', [ScrapperController::class, 'delete'])->name('delete');
// Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
// Route::post('/register', [AuthController::class, 'register']);

Route::get('/register', function () {
    return view('auth/register');
});
Route::post('/register/proses', [RegisterController::class, 'store'])->name('proses');
