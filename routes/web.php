<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\GuestAuthController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PublicBookingController;
use App\Http\Controllers\UserRoleController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OccupancyForecastController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\AdminBookingDeletionController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\StayController;
use App\Http\Middleware\StaffOnly;
use App\Http\Middleware\GuestOnly;

/*
|--------------------------------------------------------------------------
| Homepage and staff login
|--------------------------------------------------------------------------
*/

Route::get('/', [PublicBookingController::class, 'welcome'])
    ->name('welcome');

Route::get('/login', [LoginController::class, 'show'])
    ->name('login');

Route::post('/login', [LoginController::class, 'login'])
    ->name('login.attempt');

Route::post('/logout', [LoginController::class, 'logout'])
    ->name('logout');

/*
|--------------------------------------------------------------------------
| Guest accounts
|--------------------------------------------------------------------------
*/

Route::get(
    '/guest/register',
    [GuestAuthController::class, 'showRegister']
)->name('guest.register');

Route::post(
    '/guest/register',
    [GuestAuthController::class, 'register']
)
    ->middleware('throttle:5,1')
    ->name('guest.register.store');

Route::get(
    '/guest/login',
    [GuestAuthController::class, 'showLogin']
)->name('guest.login');

Route::post(
    '/guest/login',
    [GuestAuthController::class, 'login']
)
    ->middleware('throttle:5,1')
    ->name('guest.login.attempt');

Route::middleware([GuestOnly::class])->group(function () {
    Route::get(
        '/guest/dashboard',
        [GuestAuthController::class, 'dashboard']
    )->name('guest.dashboard');

    Route::post(
        '/guest/logout',
        [GuestAuthController::class, 'logout']
    )->name('guest.logout');
});

/*
|--------------------------------------------------------------------------
| Public bookings
|--------------------------------------------------------------------------
*/

Route::get('/book', [PublicBookingController::class, 'create'])
    ->name('public.book');

Route::post('/book', [PublicBookingController::class, 'store'])
    ->name('public.book.store');

Route::get(
    '/book/{booking}/payment',
    [PublicBookingController::class, 'paymentPage']
)->name('public.book.payment');

Route::post(
    '/book/{booking}/payment',
    [PublicBookingController::class, 'submitPayment']
)->name('public.book.payment.submit');

/*
|--------------------------------------------------------------------------
| Staff pages
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', StaffOnly::class])->group(function () {
    Route::get(
        '/dashboard',
        [DashboardController::class, 'index']
    )->name('dashboard');

    Route::post(
        '/dashboard/forecast/refresh',
        [OccupancyForecastController::class, 'refresh']
    )
        ->middleware('throttle:2,1')
        ->name('dashboard.forecast.refresh');

    Route::resource('rooms', RoomController::class);
    Route::resource('guests', GuestController::class);

    /*
    |--------------------------------------------------------------------------
    | Maintenance
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/maintenance',
        [MaintenanceController::class, 'index']
    )->name('maintenance.index');

    Route::post(
        '/maintenance',
        [MaintenanceController::class, 'store']
    )->name('maintenance.store');

    Route::put(
        '/maintenance/{issue}',
        [MaintenanceController::class, 'update']
    )->name('maintenance.update');

    /*
    |--------------------------------------------------------------------------
    | Stay extensions, additional payments and departure
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/stays',
        [StayController::class, 'index']
    )->name('stays.index');

    Route::post(
        '/stays/{booking}',
        [StayController::class, 'update']
    )->name('stays.update');

    /*
    |--------------------------------------------------------------------------
    | Bookings
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/bookings',
        [BookingController::class, 'index']
    )->name('bookings.index');

    Route::get(
        '/bookings/create',
        [BookingController::class, 'create']
    )->name('bookings.create');

    Route::post(
        '/bookings',
        [BookingController::class, 'store']
    )->name('bookings.store');

    Route::get(
        '/bookings/{booking}',
        [BookingController::class, 'show']
    )->name('bookings.show');

    Route::get(
        '/bookings/{booking}/edit',
        [BookingController::class, 'edit']
    )->name('bookings.edit');

    Route::put(
        '/bookings/{booking}',
        [BookingController::class, 'update']
    )->name('bookings.update');

    Route::post(
        '/bookings/{booking}/verify-payment',
        [BookingController::class, 'verifyPayment']
    )->name('bookings.verifyPayment');

    Route::post(
        '/bookings/{booking}/reject-payment',
        [BookingController::class, 'rejectPayment']
    )->name('bookings.rejectPayment');

    Route::get(
        '/bookings/{booking}/manual-payment',
        [BookingController::class, 'manualPaymentPage']
    )->name('bookings.manual.payment');

    Route::post(
        '/bookings/{booking}/manual-payment',
        [BookingController::class, 'manualPaymentSubmit']
    )->name('bookings.manual.payment.submit');

    Route::post(
        '/bookings/{booking}/confirm',
        [BookingController::class, 'confirm']
    )->name('bookings.confirm');

    Route::post(
        '/bookings/{booking}/checkin',
        [BookingController::class, 'checkIn']
    )->name('bookings.checkin');

    Route::post(
        '/bookings/{booking}/checkout',
        [StayController::class, 'checkout']
    )->name('bookings.checkout');

    Route::post(
        '/bookings/{booking}/cancel',
        [BookingController::class, 'cancel']
    )->name('bookings.cancel');

    /*
    |--------------------------------------------------------------------------
    | Finance
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/finance',
        [FinanceController::class, 'index']
    )->name('finance.index');

    Route::resource('expenses', ExpenseController::class);
});

/*
|--------------------------------------------------------------------------
| Admin-only pages
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth',
    StaffOnly::class,
    'admin',
])->group(function () {
    Route::delete(
        '/bookings/{booking}',
        [AdminBookingDeletionController::class, 'destroy']
    )->name('bookings.destroy');

    Route::get(
        '/users/create',
        [UserController::class, 'create']
    )->name('users.create');

    Route::post(
        '/users',
        [UserController::class, 'store']
    )->name('users.store');

    Route::get(
        '/users/roles',
        [UserRoleController::class, 'index']
    )->name('users.roles');

    Route::post(
        '/users/{user}/role',
        [UserRoleController::class, 'update']
    )->name('users.role.update');

    Route::post(
        '/bookings/{booking}/send-receipt',
        [BookingController::class, 'sendReceipt']
    )->name('bookings.sendReceipt');

    Route::get(
        '/calendar',
        [BookingController::class, 'calendar']
    )->name('bookings.calendar');

    Route::get(
        '/bookings/{booking}/invoice',
        [BookingController::class, 'invoice']
    )->name('bookings.invoice');
});