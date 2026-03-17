<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\OwnerAuthController;
use App\Http\Controllers\Auth\GuestAuthController;
use App\Http\Controllers\Admin\AdminVerificationController;
use App\Http\Controllers\Admin\AdminTransactionController;
use App\Http\Controllers\Provider\ProviderDashboardController;
use App\Http\Controllers\Provider\ProviderBookingController;
use App\Http\Controllers\Guest\SearchController;
use App\Http\Controllers\Guest\BookingController;
use App\Http\Controllers\Guest\ProfileController;
use App\Http\Controllers\Guest\PaymentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Public Auth Routes
Route::prefix('auth')->group(function () {
    // Owner/Provider Registration & Login
    Route::post('owner/register', [OwnerAuthController::class, 'registerOwner']);
    Route::post('owner/login', [OwnerAuthController::class, 'loginOwner']);

    // Guest Registration & Login
    Route::post('guest/register', [GuestAuthController::class, 'registerGuest']);
    Route::post('guest/login', [GuestAuthController::class, 'loginGuest']);

    // Logout (requires auth)
    Route::middleware('auth:sanctum')->post('logout', [GuestAuthController::class, 'logout']);
});

// Public Search Routes
Route::prefix('baths')->group(function () {
    Route::get('dzongkhags', [SearchController::class, 'getDzongkhags']);
    Route::get('search', [SearchController::class, 'searchBaths']);
    Route::get('{bathId}', [SearchController::class, 'getBathDetails']);
    Route::get('{bathId}/available-slots', [SearchController::class, 'getAvailableSlots']);
});

// Admin Routes (requires auth & admin role)
Route::middleware(['auth:sanctum', 'admin'])->prefix('admin')->group(function () {
    // Verification Management
    Route::get('pending-owners', [AdminVerificationController::class, 'getPendingOwners']);
    Route::get('owner/{userId}', [AdminVerificationController::class, 'getOwnerDetails']);
    Route::post('owner/{userId}/approve', [AdminVerificationController::class, 'approveOwner']);
    Route::post('owner/{userId}/reject', [AdminVerificationController::class, 'rejectOwner']);
    Route::post('document/{documentId}/verify', [AdminVerificationController::class, 'verifyDocument']);
    
    // Dashboard Stats
    Route::get('dashboard/stats', [AdminVerificationController::class, 'getDashboardStats']);

    // Transactions Management
    Route::get('transactions/recent', [AdminTransactionController::class, 'getRecentTransactions']);
});

// Provider Routes (requires auth & owner/manager role)
Route::middleware(['auth:sanctum', 'provider'])->prefix('provider')->group(function () {
    // Dashboard
    Route::get('dashboard', [ProviderDashboardController::class, 'getBathDetails']);
    Route::get('dashboard/stats', [ProviderDashboardController::class, 'getDashboardStats']);
    Route::put('dashboard', [ProviderDashboardController::class, 'updateBathDetails']);
    Route::post('dashboard/publish', [ProviderDashboardController::class, 'publishBath']);

    // Services Management
    Route::post('services', [ProviderDashboardController::class, 'addService']);
    Route::put('services/{serviceId}', [ProviderDashboardController::class, 'updateService']);
    Route::delete('services/{serviceId}', [ProviderDashboardController::class, 'deleteService']);

    // Facilities Management
    Route::post('facilities', [ProviderDashboardController::class, 'addFacility']);
    Route::put('facilities/{facilityId}', [ProviderDashboardController::class, 'updateFacility']);
    Route::delete('facilities/{facilityId}', [ProviderDashboardController::class, 'deleteFacility']);

    // Availability
    Route::post('availability', [ProviderDashboardController::class, 'setAvailability']);

    // Images
    Route::post('images', [ProviderDashboardController::class, 'uploadImages']);
    Route::delete('images/{imageId}', [ProviderDashboardController::class, 'deleteImage']);

    // Booking Management
    Route::get('bookings', [ProviderBookingController::class, 'getAllBookings']);
    Route::get('bookings/pending', [ProviderBookingController::class, 'getPendingBookings']);
    Route::post('bookings/{bookingId}/confirm', [ProviderBookingController::class, 'confirmBooking']);
    Route::post('bookings/{bookingId}/reject', [ProviderBookingController::class, 'rejectBooking']);
    Route::post('bookings/{bookingId}/complete', [ProviderBookingController::class, 'completeBooking']);
    Route::post('bookings/{bookingId}/no-show', [ProviderBookingController::class, 'markNoShow']);

    // Reports
    Route::get('reports', [ProviderBookingController::class, 'getReports']);
});

// Guest Routes (requires auth & guest role)
Route::middleware(['auth:sanctum', 'guest'])->group(function () {
    // Profile Management
    Route::get('profile', [ProfileController::class, 'getProfile']);
    Route::put('profile', [ProfileController::class, 'updateProfile']);

    // Bookings
    Route::prefix('bookings')->group(function () {
        Route::post('/', [BookingController::class, 'createBooking']);
        Route::get('/', [BookingController::class, 'getMyBookings']);
        Route::get('{bookingId}', [BookingController::class, 'getBookingDetails']);
        Route::post('{bookingId}/cancel', [BookingController::class, 'cancelBooking']);
        Route::post('{bookingId}/payment', [BookingController::class, 'processPayment']);
    });

    // Payments
    Route::prefix('payments')->group(function () {
        Route::get('methods/{bookingId}', [PaymentController::class, 'showPaymentMethods']);
        Route::get('banking-apps/{bookingId}', [PaymentController::class, 'showBankingApps']);
        Route::post('process/{bookingId}', [PaymentController::class, 'processPayment']);
        Route::post('cash/{bookingId}', [PaymentController::class, 'processCashPayment']);
        Route::post('retry/{bookingId}', [PaymentController::class, 'retryPayment']);
        Route::get('status/{bookingId}', [PaymentController::class, 'getPaymentStatus']);
    });

    // Reviews
    Route::prefix('reviews')->group(function () {
        Route::post('{bookingId}', [ProfileController::class, 'submitReview']);
        Route::get('/', [ProfileController::class, 'getMyReviews']);
    });
});
