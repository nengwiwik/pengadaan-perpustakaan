<?php

use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Penerbit\ArsipPengadaanController as PenerbitArsipPengadaanController;
use App\Http\Controllers\Penerbit\ArsipPengadaanDetailController as PenerbitArsipPengadaanDetailController;
use App\Http\Controllers\Penerbit\ImportBukuController;
use App\Http\Controllers\Penerbit\PengadaanAktifController as PenerbitPengadaanAktifController;
use App\Http\Controllers\Penerbit\PengadaanAktifDetailController as PenerbitPengadaanAktifDetailController;
use App\Http\Controllers\Penerbit\PengadaanBaruController as PenerbitPengadaanBaruController;
use App\Http\Controllers\Penerbit\PengadaanBaruDetailController as PenerbitPengadaanBaruDetailController;
use App\Http\Controllers\Penerbit\SimpanPengadaanController;
use App\Http\Controllers\Prodi\ArsipPengadaanController as ProdiArsipPengadaanController;
use App\Http\Controllers\Prodi\ArsipPengadaanDetailController as ProdiArsipPengadaanDetailController;
use App\Http\Controllers\Prodi\PengadaanAktifController as ProdiPengadaanAktifController;
use App\Http\Controllers\Prodi\PengadaanAktifDetailController as ProdiPengadaanAktifDetailController;
use App\Http\Controllers\Profile\UpdatePasswordController;
use App\Http\Controllers\Profile\UpdateProfileController;
use App\Http\Controllers\SuperAdmin\AdminPenerbitController;
use App\Http\Controllers\SuperAdmin\AdminProdiController;
use App\Http\Controllers\SuperAdmin\ArsipPengadaanController;
use App\Http\Controllers\SuperAdmin\ArsipPengadaanDetailController;
use App\Http\Controllers\SuperAdmin\JurusanController;
use App\Http\Controllers\SuperAdmin\KampusController;
use App\Http\Controllers\SuperAdmin\PenerbitController;
use App\Http\Controllers\SuperAdmin\PengadaanAktifController;
use App\Http\Controllers\SuperAdmin\PengadaanAktifDetailController;
use App\Http\Controllers\SuperAdmin\PengadaanBaruController;
use App\Http\Controllers\SuperAdmin\PengadaanBaruDetailController;
use App\Http\Controllers\SuperAdmin\PenggunaBelumAktifController;
use App\Http\Controllers\SuperAdmin\TerimaPengadaanController;
use App\Http\Controllers\SuperAdmin\TolakPengadaanController;
use App\Http\Controllers\SuperAdmin\VerifikasiPengadaanController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
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

Route::get('/', [HomeController::class, 'index'])->middleware('auth', 'verified')->name('homepage');

Route::get('/logout', function () {
    Auth::logout();
    return to_route('login');
})->middleware('auth');

Route::prefix('admin')->middleware(['role:Super Admin', 'auth', 'verified'])->group(function () {
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

    Route::get('/publisher', PenerbitController::class)->name('publisher');
    Route::post('/publisher', PenerbitController::class);

    Route::get('/pengadaan/baru', PengadaanBaruController::class)->name('procurements.new');
    Route::post('/pengadaan/baru', PengadaanBaruController::class);

    Route::get('/pengadaan/{procurement}/baru', PengadaanBaruDetailController::class)->name('procurements.books');
    Route::post('/pengadaan/{procurement}/baru', PengadaanBaruDetailController::class);

    Route::get('/pengadaan/aktif', PengadaanAktifController::class)->name('procurements.active');
    Route::post('/pengadaan/aktif', PengadaanAktifController::class);

    Route::get('/pengadaan/{procurement}/aktif', PengadaanAktifDetailController::class)->name('procurements.books.active');
    Route::post('/pengadaan/{procurement}/aktif', PengadaanAktifDetailController::class);

    Route::get('/pengadaan/arsip', ArsipPengadaanController::class)->name('procurements.archived');
    Route::post('/pengadaan/arsip', ArsipPengadaanController::class);

    Route::get('/pengadaan/{procurement}/arsip', ArsipPengadaanDetailController::class)->name('procurements.books.arsip');
    Route::post('/pengadaan/{procurement}/arsip', ArsipPengadaanDetailController::class);

    Route::get('/pengadaan/{id}/approve', TerimaPengadaanController::class)->name('procurement.approve');
    Route::get('/pengadaan/{id}/reject', TolakPengadaanController::class)->name('procurement.reject');
    Route::get('/pengadaan/{id}/verify', VerifikasiPengadaanController::class)->name('procurement.verify');
});

Route::prefix('penerbit')->middleware(['role:Penerbit', 'auth', 'verified'])->group(function () {
    Route::get('/pengadaan/baru', PenerbitPengadaanBaruController::class)->name('penerbit.invoices');
    Route::post('/pengadaan/baru', PenerbitPengadaanBaruController::class);

    Route::get('/pengadaan/{invoice:code}/store', SimpanPengadaanController::class)->name('penerbit.invoices.store');

    Route::get('/pengadaan/{procurement}/baru', PenerbitPengadaanBaruDetailController::class)->name('penerbit.invoices.books');
    Route::post('/pengadaan/{procurement}/baru', PenerbitPengadaanBaruDetailController::class);

    Route::post('/pengadaan/{procurement}/import', ImportBukuController::class)->name('penerbit.invoices.books.import');

    Route::get('/pengadaan/aktif', PenerbitPengadaanAktifController::class)->name('penerbit.invoices.ongoing');
    Route::post('/pengadaan/aktif', PenerbitPengadaanAktifController::class);

    Route::get('/pengadaan/{procurement}/aktif', PenerbitPengadaanAktifDetailController::class)->name('penerbit.invoices.books.ongoing');
    Route::post('/pengadaan/{procurement}/aktif', PenerbitPengadaanAktifDetailController::class);

    Route::get('/pengadaan/arsip', PenerbitArsipPengadaanController::class)->name('penerbit.invoices.verified');
    Route::post('/pengadaan/arsip', PenerbitArsipPengadaanController::class);

    Route::get('/pengadaan/{procurement}/arsip', PenerbitArsipPengadaanDetailController::class)->name('penerbit.invoices.books.verified');
    Route::post('/pengadaan/{procurement}/arsip', PenerbitArsipPengadaanDetailController::class);
});

Route::prefix('prodi')->middleware(['role:Admin Prodi', 'auth', 'verified'])->group(function () {
    Route::get('/pengadaan/aktif', ProdiPengadaanAktifController::class)->name('prodi.procurements.active');
    Route::post('/pengadaan/aktif', ProdiPengadaanAktifController::class);

    Route::get('/pengadaan/{procurement}/aktif', ProdiPengadaanAktifDetailController::class)->name('prodi.procurements.books.active');
    Route::post('/pengadaan/{procurement}/aktif', ProdiPengadaanAktifDetailController::class);

    Route::get('/pengadaan/arsip', ProdiArsipPengadaanController::class)->name('prodi.procurements.archived');
    Route::post('/pengadaan/arsip', ProdiArsipPengadaanController::class);

    Route::get('/pengadaan/{procurement}/arsip', ProdiArsipPengadaanDetailController::class)->name('prodi.procurements.books.archived');
    Route::post('/pengadaan/{procurement}/arsip', ProdiArsipPengadaanDetailController::class);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', UpdateProfileController::class)->name('profil.index');
    Route::patch('/profile', UpdateProfileController::class)->name('profil.update');
    Route::get('/profile/password', UpdatePasswordController::class)->name('profil.password');
    Route::patch('/profile/password', UpdatePasswordController::class)->name('profil.update-password');
});

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    return redirect('/');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::get('/email/verify', function () {
    return view('auth.verify');
})->middleware('auth')->name('verification.notice');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.resend');

Auth::routes();

/**
 * socialite auth
 */
Route::get('/auth/{provider}', [SocialiteController::class, 'redirectToProvider'])->name('socialite.redirect');
Route::get('/auth/{provider}/callback', [SocialiteController::class, 'handleProvideCallback']);

Route::get('/tex', function () {
    $inv = App\Models\Procurement::find(4);
    $majors = $inv->books()->select('major_id')->get();
    $res = [];
    foreach($majors as $major) {
        $d = explode(",", $major->major_id);
        array_push($res, $d);
    }

    $result = [];
    foreach($res as $d) {
        foreach($d as $e) {
            array_push($result, $e);
        }
    }
    dump($result);
    $unik = array_unique($result);
});

Route::fallback(function () {
    return to_route('homepage')->with('error', 'Kamu tersesat?');
});
