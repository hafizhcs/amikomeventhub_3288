<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\EventController as AdminEventController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\PartnerController;
use App\Http\Controllers\Admin\CouponController; // <-- 1. PERBAIKAN: Mengarah ke namespace Admin
use App\Http\Controllers\RatingController;
use App\Http\Controllers\TicketPriceController;
use App\Http\Controllers\OrganizerController;
use App\Http\Controllers\Admin\OrganizationController;



// =============================================
// PUBLIC ROUTES
// =============================================
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/api/events/filter', [HomeController::class, 'getFilteredEvents'])->name('api.events.filter');

Route::middleware('auth')->group(function () {
    Route::get('/profil', [HomeController::class, 'profil'])->name('profil');
});

Route::get('/katalog', [HomeController::class, 'katalog'])->name('katalog');
Route::get('/tentang', [HomeController::class, 'tentang'])->name('tentang');
Route::get('/contact', [HomeController::class, 'kontak'])->name('kontak');

Route::get('/event/{event}', [EventController::class, 'show'])->name('events.show');

// =============================================
// CHECKOUT & TICKET ROUTES (AUTH REQUIRED)
// =============================================
Route::middleware('auth')->group(function () {
    Route::get('/checkout/{event}', [CheckoutController::class, 'create'])->name('checkout.create');
    Route::post('/checkout/{event}', [CheckoutController::class, 'store'])->name('checkout.store');
    
    // <-- 2. PERBAIKAN: Rute AJAX untuk verifikasi dan penerapan kupon belanja
    Route::post('/checkout/apply-coupons/{event}', [CheckoutController::class, 'applyCoupon'])->name('checkout.applyCoupon');
    
    Route::get('/my-ticket', [EventController::class, 'ticket'])->name('ticket');
    Route::get('/my-ticket/{transaction}', [EventController::class, 'showTicket'])->name('eticket.show');
});

// Pembayaran & Success (Bisa diakses setelah redirect Midtrans)
Route::get('/payment/{order_id}', [CheckoutController::class, 'payment'])->name('checkout.payment');
Route::get('/success/{order_id}', [CheckoutController::class, 'success'])->name('checkout.success');


// =============================================
// AUTHENTICATION ROUTES (LOGIN, REGISTER, GOOGLE)
// =============================================
Route::get('/login', function (Request $request) {
    $redirectTo = $request->query('next') ?: url()->previous() ?: route('home');
    return redirect()->route('auth.google.redirect', ['next' => $redirectTo]);
})->name('login');

Route::get('/register', [RegisterController::class, 'create'])->name('register');
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

Route::match(['get','post'], '/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('home');
})->name('logout');

Route::post('/switch-account', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('login', ['next' => url()->previous() ?: route('home')] );
})->name('switch.account');

// Google OAuth
Route::get('auth/google', [GoogleController::class, 'redirect'])->name('auth.google.redirect');
Route::get('auth/google/callback', [GoogleController::class, 'callback'])->name('auth.google.callback');


// =============================================
// ORGANIZER (KEPANITIAAN / HIMA) - MULTI TENANT
// =============================================

// Pendaftaran organisasi baru: siapa saja yang sudah login boleh mengajukan
Route::middleware('auth')->group(function () {
    Route::get('/organisasi/daftar', [OrganizerController::class, 'registerForm'])->name('organizer.register');
    Route::post('/organisasi/daftar', [OrganizerController::class, 'registerStore'])->name('organizer.register.store');
});

// Area kerja organizer: khusus role=organizer yang organisasinya sudah terverifikasi
Route::prefix('organizer')->name('organizer.')->middleware(['auth', 'organizer'])->group(function () {
    Route::get('/dashboard', [OrganizerController::class, 'dashboard'])->name('dashboard');

    Route::get('/events', [OrganizerController::class, 'eventsIndex'])->name('events.index');
    Route::get('/events/create', [OrganizerController::class, 'eventsCreate'])->name('events.create');
    Route::post('/events', [OrganizerController::class, 'eventsStore'])->name('events.store');
    Route::get('/events/{event}/edit', [OrganizerController::class, 'eventsEdit'])->name('events.edit');
    Route::put('/events/{event}', [OrganizerController::class, 'eventsUpdate'])->name('events.update');
});

// =============================================
// RATING SYSTEM
// =============================================
Route::middleware('auth')->group(function () {
    Route::post('/rating', [RatingController::class, 'store'])->name('rating.store');
});


// =============================================
// ADMIN AREA ROUTES (PREFIX & NAMING GROUP)
// =============================================
Route::prefix('admin')->name('admin.')->group(function () {
    // Rute Login Admin (Bebas Akses)
    Route::get('login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login.post');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    // Rute Admin Terproteksi (Hanya Admin)
    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');

        // CRUD Resources Otomatis
        Route::resource('events', AdminEventController::class);
        Route::resource('categories', CategoryController::class);
        Route::resource('partners', PartnerController::class);
        Route::resource('ticket-prices', TicketPriceController::class);
        Route::resource('coupons', CouponController::class);

        // Pengawasan Multi-Tenant: verifikasi organisasi & review event organizer
        Route::get('/organizations', [OrganizationController::class, 'index'])->name('organizations.index');
        Route::post('/organizations/{organization}/approve', [OrganizationController::class, 'approve'])->name('organizations.approve');
        Route::post('/organizations/{organization}/suspend', [OrganizationController::class, 'suspend'])->name('organizations.suspend');

        Route::get('/events-review', [AdminEventController::class, 'pendingReview'])->name('events.pending');
        Route::post('/events/{event}/approve', [AdminEventController::class, 'approve'])->name('events.approve');
        Route::post('/events/{event}/reject', [AdminEventController::class, 'reject'])->name('events.reject');
    });


    // Webhook Payment Gateway (Midtrans)
    Route::post('/midtrans/callback', [\App\Http\Controllers\MidtransWebhookController::class, 'handle']);
});
Route::get('/organizer/{id}', [OrganizerController::class, 'show'])->name('organizer.show');