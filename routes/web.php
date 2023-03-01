<?php

use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Penerbit\ImportBukuController;
use App\Http\Controllers\PenerbitController;
use App\Http\Controllers\ProdiController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\SuperAdmin\AdminPenerbitController;
use App\Http\Controllers\SuperAdmin\AdminProdiController;
use App\Http\Controllers\SuperAdmin\ArsipPengadaanController;
use App\Http\Controllers\SuperAdmin\ArsipPengadaanDetailController;
use App\Http\Controllers\SuperAdmin\JurusanController;
use App\Http\Controllers\SuperAdmin\KampusController;
use App\Http\Controllers\SuperAdmin\PenerbitController as SuperAdminPenerbitController;
use App\Http\Controllers\SuperAdmin\PengadaanAktifController;
use App\Http\Controllers\SuperAdmin\PengadaanAktifDetailController;
use App\Http\Controllers\SuperAdmin\PengadaanBaruController;
use App\Http\Controllers\SuperAdmin\PengadaanBaruDetailController;
use App\Http\Controllers\SuperAdmin\PenggunaBelumAktifController;
use App\Http\Controllers\SuperAdmin\TerimaPengadaanController;
use App\Http\Controllers\SuperAdmin\TolakPengadaanController;
use App\Http\Controllers\SuperAdmin\VerifikasiPengadaanController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

Route::get('/', [HomeController::class, 'index'])->middleware('auth')->name('homepage');

Route::get('/logout', function () {
    Auth::logout();
    return to_route('login');
})->middleware('auth');

Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::get('/users/prodi', AdminProdiController::class)->name('prodi_users');
    Route::post('/users/prodi', AdminProdiController::class);

    Route::get('/users/publisher', AdminPenerbitController::class)->name('publisher_users');
    Route::post('/users/publisher', AdminPenerbitController::class);

    Route::get('/users/inactive', PenggunaBelumAktifController::class)->name('inactive_users');
    Route::post('/users/inactive', PenggunaBelumAktifController::class);

    Route::get('/campuses', KampusController::class)->name('campuses');
    Route::post('/campuses', KampusController::class);

    Route::get('/majors', JurusanController::class)->name('majors');
    Route::post('/majors', JurusanController::class);

    Route::get('/publisher', SuperAdminPenerbitController::class)->name('publisher');
    Route::post('/publisher', SuperAdminPenerbitController::class);

    Route::get('/pengadaan/baru', PengadaanBaruController::class)->name('procurements.new');
    Route::post('/pengadaan/baru', PengadaanBaruController::class);

    Route::get('/pengadaan/{invoice}/baru', PengadaanBaruDetailController::class)->name('procurements.books');
    Route::post('/pengadaan/{invoice}/baru', PengadaanBaruDetailController::class);

    Route::get('/pengadaan/aktif', PengadaanAktifController::class)->name('procurements.active');
    Route::post('/pengadaan/aktif', PengadaanAktifController::class);

    Route::get('/pengadaan/{invoice}/aktif', PengadaanAktifDetailController::class)->name('procurements.books.active');
    Route::post('/pengadaan/{invoice}/aktif', PengadaanAktifDetailController::class);

    Route::get('/pengadaan/arsip', ArsipPengadaanController::class)->name('procurements.archived');
    Route::post('/pengadaan/arsip', ArsipPengadaanController::class);

    Route::get('/pengadaan/{invoice}/arsip', ArsipPengadaanDetailController::class)->name('procurements.books.arsip');
    Route::post('/pengadaan/{invoice}/arsip', ArsipPengadaanDetailController::class);

    Route::get('/pengadaan/{id}/approve', TerimaPengadaanController::class)->name('procurement.approve');
    Route::get('/pengadaan/{id}/reject', TolakPengadaanController::class)->name('procurement.reject');
    Route::get('/pengadaan/{id}/verify', VerifikasiPengadaanController::class)->name('procurement.verify');
});

Route::prefix('penerbit')->middleware(['role:Penerbit', 'auth'])->group(function () {
    Route::get('/pengadaan', [PenerbitController::class, 'invoices'])->name('penerbit.invoices');
    Route::post('/pengadaan', [PenerbitController::class, 'invoices']);

    Route::get('/pengadaan/{invoice}/books', [PenerbitController::class, 'books'])->name('penerbit.invoices.books');
    Route::post('/pengadaan/{invoice}/books', [PenerbitController::class, 'books']);

    Route::post('/pengadaan/{invoice}/import', ImportBukuController::class)->name('penerbit.invoices.books.import');

    Route::get('/pengadaan/aktif', [PenerbitController::class, 'ongoing_invoices'])->name('penerbit.invoices.ongoing');
    Route::post('/pengadaan/aktif', [PenerbitController::class, 'ongoing_invoices']);

    Route::get('/pengadaan/aktif/{invoice}/books', [PenerbitController::class, 'ongoing_books'])->name('penerbit.invoices.books.ongoing');
    Route::post('/pengadaan/aktif/{invoice}/books', [PenerbitController::class, 'ongoing_books']);

    Route::get('/pengadaan/arsip', [PenerbitController::class, 'verified_invoices'])->name('penerbit.invoices.verified');
    Route::post('/pengadaan/arsip', [PenerbitController::class, 'verified_invoices']);

    Route::get('/pengadaan/arsip/{invoice}/books', [PenerbitController::class, 'ongoing_books'])->name('penerbit.invoices.books.verified');
    Route::post('/pengadaan/arsip/{invoice}/books', [PenerbitController::class, 'ongoing_books']);
});

Route::prefix('prodi')->middleware(['role:Admin Prodi', 'auth'])->group(function () {
    Route::get('/pengadaan/aktif', [ProdiController::class, 'active_procurements'])->name('prodi.procurements.active');
    Route::post('/pengadaan/aktif', [ProdiController::class, 'active_procurements']);

    Route::get('/pengadaan/aktif/{invoice}/books', [ProdiController::class, 'procurement_books'])->name('prodi.procurements.books.active');
    Route::post('/pengadaan/aktif/{invoice}/books', [ProdiController::class, 'procurement_books']);

    Route::get('/pengadaan/arsip', [ProdiController::class, 'archived_procurements'])->name('prodi.procurements.archived');
    Route::post('/pengadaan/arsip', [ProdiController::class, 'archived_procurements']);

    Route::get('/pengadaan/arsip/{invoice}/books', [ProdiController::class, 'archived_procurement_books'])->name('prodi.procurements.books.archived');
    Route::post('/pengadaan/arsip/{invoice}/books', [ProdiController::class, 'archived_procurement_books']);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfilController::class, 'index'])->name('profil.index');
    Route::patch('/profile', [ProfilController::class, 'update'])->name('profil.update');
    Route::get('/profile/password', [ProfilController::class, 'password'])->name('profil.password');
    Route::patch('/profile/password', [ProfilController::class, 'password'])->name('profil.update-password');
});

Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

/**
 * socialite auth
 */
Route::get('/auth/{provider}', [SocialiteController::class, 'redirectToProvider'])->name('socialite.redirect');
Route::get('/auth/{provider}/callback', [SocialiteController::class, 'handleProvideCallback']);
