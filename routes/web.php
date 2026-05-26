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
Route::view('/how-to-book', 'web.how-to-book')->name('how.to.book');
Route::view('/about-us', 'web.about-us')->name('about.us');
Route::get('/login', function () {
    return view('web.auth.login-selection');
})->name('login');

// 🔐 HIDDEN ADMIN LOGIN ROUTES - Not linked anywhere, only accessible via direct URL
// Primary admin login endpoint
Route::get('/admin/login', [AdminPortalController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AdminPortalController::class, 'login'])->name('admin.login.submit');

// Alternative secret admin access URL (harder to discover through brute force)
Route::get('/secure-admin-portal-access', [AdminPortalController::class, 'showLogin'])->name('admin.secret.login');
Route::post('/secure-admin-portal-access', [AdminPortalController::class, 'login'])->withoutMiddleware(['web'])->middleware('web');

Route::get('/baths/{bath}', [GuestPortalController::class, 'showBath'])->name('baths.show');
Route::get('/baths/{bath}/inquiry', [GuestPortalController::class, 'showInquiryForm'])->name('guest.inquiry.show');
Route::post('/baths/{bath}/inquiry', [GuestPortalController::class, 'storeInquiry'])->name('guest.inquiry.store');

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
		Route::post('/bookings/{booking}/payment/digital', [PaymentPortalController::class, 'processDigitalPayment'])->name('guest.payment.digital');
		Route::post('/bookings/{booking}/payment/cash', [PaymentPortalController::class, 'processCashPayment'])->name('guest.payment.cash');
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
		Route::get('/service/add', [OwnerPortalController::class, 'showServiceForm'])->name('owner.service.form');
		Route::post('/service/add', [OwnerPortalController::class, 'saveService'])->name('owner.service.save');
		Route::get('/services/{service}/edit', [OwnerPortalController::class, 'showEditServiceForm'])->name('owner.service.edit');
		Route::post('/services/{service}/update', [OwnerPortalController::class, 'updateService'])->name('owner.service.update');
		Route::post('/services/{service}/delete', [OwnerPortalController::class, 'deleteService'])->name('owner.service.delete');
		Route::post('/services/{service}/flag', [OwnerPortalController::class, 'flagService'])->name('owner.service.flag');
		Route::post('/bookings/{booking}/status', [OwnerPortalController::class, 'updateBookingStatus'])->name('owner.booking.status');
		Route::post('/logout', [OwnerPortalController::class, 'logout'])->name('owner.logout');
	});
});

Route::prefix('admin')->group(function () {
	// Admin login routes - NOT protected by admin middleware (users need to login first)
	Route::get('/login', [AdminPortalController::class, 'showLogin'])->name('admin.login');
	Route::post('/login', [AdminPortalController::class, 'login'])->name('admin.login.submit');

	// All admin dashboard routes - PROTECTED by auth and admin middleware
	Route::middleware(['auth', 'admin'])->group(function () {
		Route::get('/dashboard', [AdminPortalController::class, 'dashboard'])->name('admin.dashboard');
		Route::get('/users', [AdminPortalController::class, 'showUsers'])->name('admin.users');
		Route::get('/bookings', [AdminPortalController::class, 'showBookings'])->name('admin.bookings');
		Route::get('/revenue', [AdminPortalController::class, 'showRevenue'])->name('admin.revenue');
		Route::get('/transactions/recent', [AdminPortalController::class, 'getRecentTransactionsJson'])->name('admin.transactions.recent');
		Route::post('/users/{user}/approve', [AdminPortalController::class, 'approveUser'])->name('admin.user.approve');
		Route::post('/users/{user}/reject', [AdminPortalController::class, 'rejectUser'])->name('admin.user.reject');
		Route::post('/owners/{owner}/approve', [AdminPortalController::class, 'approveOwner'])->name('admin.owner.approve');
		Route::post('/owners/{owner}/reject', [AdminPortalController::class, 'rejectOwner'])->name('admin.owner.reject');
		Route::post('/listings/{bath}/status', [AdminPortalController::class, 'updateListingStatus'])->name('admin.listing.status');
		Route::post('/services/{service}/approve', [AdminPortalController::class, 'approveService'])->name('admin.service.approve');
		Route::post('/services/{service}/reject', [AdminPortalController::class, 'rejectService'])->name('admin.service.reject');
		Route::post('/logout', [AdminPortalController::class, 'logout'])->name('admin.logout');
		
		// Messaging routes - SPECIFIC routes BEFORE generic parameter routes
		Route::get('/messages/unread/count', [\App\Http\Controllers\Web\MessageController::class, 'getUnreadCount'])->name('admin.messages.unread');
		Route::get('/messages/pending-requests', [\App\Http\Controllers\Web\MessageController::class, 'getPendingRequests'])->name('admin.messages.pending');
		Route::get('/messages/{booking}', [\App\Http\Controllers\Web\MessageController::class, 'showMessages'])->name('admin.messages.show');
		Route::post('/messages/{booking}', [\App\Http\Controllers\Web\MessageController::class, 'sendMessage'])->name('admin.messages.send');
		
		// ============================================================
		// NEW ADMIN SECTIONS: Bath, Commissions, Settings, Reviews
		// ============================================================
		
		// BATH MANAGEMENT
		Route::get('/baths', [AdminPortalController::class, 'manageBaths'])->name('admin.baths');
		Route::get('/baths/{bath}/edit', [AdminPortalController::class, 'editBath'])->name('admin.baths.edit');
		Route::post('/baths/{bath}/update', [AdminPortalController::class, 'updateBath'])->name('admin.baths.update');
		Route::post('/baths/{bath}/delete', [AdminPortalController::class, 'deleteBath'])->name('admin.baths.delete');
		
		// COMMISSION MANAGEMENT
		Route::get('/commissions', [AdminPortalController::class, 'manageCommissions'])->name('admin.commissions');
		Route::get('/commissions/report', [AdminPortalController::class, 'generateCommissionReport'])->name('admin.commission.report');
		
		// NOTIFICATIONS & MESSAGES
		Route::get('/notifications', function () {
			return view('web.admin.notifications');
		})->name('admin.notifications');
		Route::post('/notifications/send', [AdminPortalController::class, 'sendNotification'])->name('admin.notification.send');
		Route::get('/messages', [AdminPortalController::class, 'viewMessages'])->name('admin.all-messages');
		
		// SETTINGS
		Route::get('/settings', [AdminPortalController::class, 'showSettings'])->name('admin.settings');
		Route::post('/settings/profile', [AdminPortalController::class, 'updateAdminProfile'])->name('admin.settings.profile');
		Route::post('/settings/system', [AdminPortalController::class, 'updateSystemSettings'])->name('admin.settings.system');
		
		// REVIEWS & FEEDBACK
		Route::get('/reviews', [AdminPortalController::class, 'manageReviews'])->name('admin.reviews');
		Route::post('/reviews/{review}/approve', [AdminPortalController::class, 'approveReview'])->name('admin.review.approve');
		Route::post('/reviews/{review}/reject', [AdminPortalController::class, 'rejectReview'])->name('admin.review.reject');
	});
});

