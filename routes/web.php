<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DokumenController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\VisualisasiController;

Route::middleware(['guest', 'no-cache'])->group(function () {
    Route::get('/login', [LoginController::class, 'index'])->name('login');
    Route::post('/login', [LoginController::class, 'authenticate']);
});

Route::middleware(['auth'])->group(function () {
    Route::get('/logout', [LoginController::class, 'deauthenticate']);
    Route::get('/', [LandingController::class, 'index'])->name('dashboard')->middleware('no-cache');
    Route::get('/daftar-dokumen', [DokumenController::class, 'getDokumen']);

    Route::prefix('visualisasi')->group(function () {
    
        Route::view('/', 'visualisasi.index')->name('visualisasi.index');
        
        Route::prefix('mahasiswa')->group(function () {
            Route::get('{status}/{jenjang}', [VisualisasiController::class, 'show']);

            Route::view('mahasiswa-tugas-akhir', 'visualisasi.mahasiswa.mahasiswa-tugas-akhir');
            Route::view('mahasiswa-asing', 'visualisasi.mahasiswa.mahasiswa-asing');
        });
    
        Route::prefix('sdm')->group(function () {
            Route::prefix('dosen')->group(function () {
                Route::view('per-homebase', 'visualisasi.sdm.dosen.dosen-perHomebase');
                Route::view('per-jabatan', 'visualisasi.sdm.dosen.dosen-perJabatan');
                Route::view('per-pendidikan', 'visualisasi.sdm.dosen.dosen-perPendidikan');
                Route::view('per-sertifikasi', 'visualisasi.sdm.dosen.dosen-perSertifikasi');
                Route::view('per-tidak-tetap', 'visualisasi.sdm.dosen.dosen-perTidakTetap');
            });
            
            Route::view('tendik', 'visualisasi.sdm.tendik');
        });
    
        Route::view('akreditasi', 'visualisasi.akreditasi');
    });
    
});

Route::middleware(['auth', 'is-admin'])->group(function () {
    Route::view('/admin', 'admin.index');
    Route::resource('/admin/dokumen', DokumenController::class);
});