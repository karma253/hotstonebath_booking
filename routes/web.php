<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\AdminPortalController;
use App\Http\Controllers\Web\GuestPortalController;
use App\Http\Controllers\Web\OwnerPortalController;
use App\Http\Controllers\Web\PaymentPortalController;

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

Route::get('/', [GuestPortalController::class, 'home'])->name('home');
Route::get('/login', function () {
    return view('web.auth.login-selection');
})->name('login');
Route::get('/baths/{bath}', [GuestPortalController::class, 'showBath'])->name('baths.show');

Route::prefix('guest')->group(function () {
	Route::get('/login', [GuestPortalController::class, 'showLogin'])->name('guest.login');
	Route::post('/login', [GuestPortalController::class, 'login'])->name('guest.login.submit');
	Route::get('/register', [GuestPortalController::class, 'showRegister'])->name('guest.register');
	Route::post('/register', [GuestPortalController::class, 'register'])->name('guest.register.submit');

	Route::middleware('auth')->group(function () {
		Route::get('/dashboard', [GuestPortalController::class, 'dashboard'])->name('guest.dashboard');
		Route::get('/baths/{bath}/book', [GuestPortalController::class, 'createBookingForm'])->name('guest.booking.create');
		Route::post('/baths/{bath}/book', [GuestPortalController::class, 'storeBooking'])->name('guest.booking.store');
		Route::get('/bookings/{booking}/summary', [GuestPortalController::class, 'bookingSummary'])->name('guest.booking.summary');
		Route::get('/bookings/{booking}/payment', [PaymentPortalController::class, 'showPayment'])->name('guest.booking.payment');
		Route::get('/bookings/{booking}/confirmation', [PaymentPortalController::class, 'showConfirmation'])->name('guest.booking.confirmation');
		Route::post('/bookings/{booking}/cancel', [GuestPortalController::class, 'cancelBooking'])->name('guest.booking.cancel');
		Route::post('/logout', [GuestPortalController::class, 'logout'])->name('guest.logout');
	});
});

Route::prefix('owner')->group(function () {
	Route::get('/login', [OwnerPortalController::class, 'showLogin'])->name('owner.login');
	Route::post('/login', [OwnerPortalController::class, 'login'])->name('owner.login.submit');
	Route::get('/register', [OwnerPortalController::class, 'showRegister'])->name('owner.register');
	Route::post('/register', [OwnerPortalController::class, 'register'])->name('owner.register.submit');

	Route::middleware('auth')->group(function () {
		Route::get('/dashboard', [OwnerPortalController::class, 'dashboard'])->name('owner.dashboard');
		Route::get('/listing', [OwnerPortalController::class, 'showListingForm'])->name('owner.listing.form');
		Route::post('/listing', [OwnerPortalController::class, 'saveListing'])->name('owner.listing.save');
		Route::post('/bookings/{booking}/status', [OwnerPortalController::class, 'updateBookingStatus'])->name('owner.booking.status');
		Route::post('/logout', [OwnerPortalController::class, 'logout'])->name('owner.logout');
	});
});

Route::prefix('admin')->group(function () {
	Route::get('/login', [AdminPortalController::class, 'showLogin'])->name('admin.login');
	Route::post('/login', [AdminPortalController::class, 'login'])->name('admin.login.submit');

	Route::middleware('auth')->group(function () {
		Route::get('/dashboard', [AdminPortalController::class, 'dashboard'])->name('admin.dashboard');
		Route::get('/transactions/recent', [AdminPortalController::class, 'getRecentTransactionsJson'])->name('admin.transactions.recent');
		Route::post('/owners/{owner}/approve', [AdminPortalController::class, 'approveOwner'])->name('admin.owner.approve');
		Route::post('/owners/{owner}/reject', [AdminPortalController::class, 'rejectOwner'])->name('admin.owner.reject');
		Route::post('/listings/{bath}/status', [AdminPortalController::class, 'updateListingStatus'])->name('admin.listing.status');
		Route::post('/logout', [AdminPortalController::class, 'logout'])->name('admin.logout');
	});
});

