<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\BukuController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| REDIRECT AWAL
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    if (Auth::check()) {
        $user = Auth::user();
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        if ($user->role === 'anggota') {
            return redirect()->route('anggota.dashboard');
        }
    }
    return redirect('/login');
});

/*
|--------------------------------------------------------------------------
| GUEST AREA (Login)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

/*
|--------------------------------------------------------------------------
| AUTH AREA (Harus Login)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::middleware(['check.status'])->group(function () {

        /* |------------------------------------------------------------------
        | 🔥 ADMIN AREA 
        |------------------------------------------------------------------
        */
        Route::middleware(['checkRole:admin'])->prefix('admin')->group(function () {
            
            Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
            
            // Manajemen Buku
            Route::controller(BukuController::class)->group(function () {
                Route::get('/buku', 'index')->name('admin.buku');
                Route::post('/buku/store', 'store')->name('admin.buku.store');
                Route::get('/buku/edit/{id}', 'edit')->name('admin.buku.edit');
                Route::put('/buku/update/{id}', 'update')->name('admin.buku.update');
                Route::delete('/buku/delete/{id}', 'destroy')->name('admin.buku.delete');
            });

            // Manajemen Anggota
            Route::controller(AnggotaController::class)->group(function () {
                Route::get('/anggota', 'index')->name('admin.anggota');
                Route::post('/anggota/store', 'store')->name('admin.anggota.store');
                Route::get('/anggota/edit/{id}', 'edit')->name('admin.anggota.edit');
                Route::put('/anggota/update/{id}', 'update')->name('admin.anggota.update');
                Route::delete('/anggota/delete/{id}', 'destroy')->name('admin.anggota.destroy');
            });

            // Manajemen Transaksi & ACC
            Route::controller(AdminController::class)->group(function () {
                Route::get('/transaksi', 'transaksi')->name('admin.transaksi');
                // ROUTE BARU: Untuk Admin Klik Setujui/ACC Pinjaman
                Route::post('/transaksi/acc/{id}', 'accPinjaman')->name('admin.acc');
            });
        });

        /* |------------------------------------------------------------------
        | 🔥 ANGGOTA AREA 
        |------------------------------------------------------------------
        */
        Route::middleware(['checkRole:anggota'])->prefix('anggota')->group(function () {
            
            Route::get('/dashboard', [BukuController::class, 'anggota'])->name('anggota.dashboard');
            Route::get('/buku', [BukuController::class, 'anggota'])->name('anggota.buku');
            Route::get('/profil', [AnggotaController::class, 'index'])->name('anggota.index');

            // Proses Pengajuan Pinjam (Status Menunggu)
            Route::post('/pinjam', [AdminController::class, 'pinjamStore'])->name('pinjam.store');
            Route::post('/admin/transaksi/kembali/{id}', [AdminController::class, 'kembaliBuku'])->name('admin.kembali');
            Route::get('pengembalian', [AnggotaController::class, 'pengembalian'])
            ->name('anggota.pengembalian');
        });
    });
});