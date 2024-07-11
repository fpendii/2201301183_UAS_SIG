<?php

use App\Http\Controllers\admin\kelola_pom_mini;
use App\Http\Controllers\admin\KelolaPomMiniController;
use App\Http\Controllers\Pengguna\LandingPageController;
use Illuminate\Support\Facades\Route;

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

Route::get('', function () {
    return view('welcome');
});

Route::prefix('')->group(function () {
    Route::get('landing-page', [LandingPageController::class, 'index']);
});

Route::prefix('admin')->group(function () {
    Route::get('kelola-pom-mini', [KelolaPomMiniController::class, 'index']);
    Route::get('pom-mini/tambah', [KelolaPomMiniController::class, 'tambah']);
    Route::post('pom-mini/simpan', [KelolaPomMiniController::class, 'simpan']);
});

