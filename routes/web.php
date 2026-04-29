<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PublicBookingController;
use App\Http\Controllers\UserRoleController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\ExpenseController;

Route::get('/', [PublicBookingController::class, 'welcome'])->name('welcome');

Route::get('/login', [LoginController::class, 'show'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.attempt');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Public booking + public payment
|--------------------------------------------------------------------------
*/
Route::get('/book', [PublicBookingController::class, 'create'])->name('public.book');
Route::post('/book', [PublicBookingController::class, 'store'])->name('public.book.store');

Route::get('/book/{booking}/payment', [PublicBookingController::class, 'paymentPage'])
    ->name('public.book.payment');

Route::post('/book/{booking}/payment', [PublicBookingController::class, 'submitPayment'])
    ->name('public.book.payment.submit');

/*
|--------------------------------------------------------------------------
| Authenticated dashboard
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('rooms', RoomController::class);
    Route::resource('guests', GuestController::class);

    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/create', [BookingController::class, 'create'])->name('bookings.create');
    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
    Route::get('/bookings/{booking}/edit', [BookingController::class, 'edit'])->name('bookings.edit');
    Route::put('/bookings/{booking}', [BookingController::class, 'update'])->name('bookings.update');
    Route::delete('/bookings/{booking}', [BookingController::class, 'destroy'])->name('bookings.destroy');

    Route::post('/bookings/{booking}/verify-payment', [BookingController::class, 'verifyPayment'])
        ->name('bookings.verifyPayment');

    Route::post('/bookings/{booking}/reject-payment', [BookingController::class, 'rejectPayment'])
        ->name('bookings.rejectPayment');

    Route::get('/bookings/{booking}/manual-payment', [BookingController::class, 'manualPaymentPage'])
        ->name('bookings.manual.payment');

    Route::post('/bookings/{booking}/manual-payment', [BookingController::class, 'manualPaymentSubmit'])
        ->name('bookings.manual.payment.submit');

    Route::post('/bookings/{booking}/confirm', [BookingController::class, 'confirm'])->name('bookings.confirm');
    Route::post('/bookings/{booking}/checkin', [BookingController::class, 'checkIn'])->name('bookings.checkin');
    Route::post('/bookings/{booking}/checkout', [BookingController::class, 'checkOut'])->name('bookings.checkout');
    Route::post('/bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');

    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');

    Route::get('/finance', [FinanceController::class, 'index'])->name('finance.index');
    Route::resource('expenses', ExpenseController::class);
});

/*
|--------------------------------------------------------------------------
| Admin-only
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/users/roles', [UserRoleController::class, 'index'])->name('users.roles');
    Route::post('/users/{user}/role', [UserRoleController::class, 'update'])->name('users.role.update');

    Route::post('/bookings/{booking}/send-receipt', [BookingController::class, 'sendReceipt'])
        ->name('bookings.sendReceipt');

    Route::get('/calendar', [BookingController::class, 'calendar'])
        ->name('bookings.calendar');

    Route::get('/bookings/{booking}/invoice', [BookingController::class, 'invoice'])
        ->name('bookings.invoice');
});