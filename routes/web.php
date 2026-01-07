<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
})->name('home');

/*
|--------------------------------------------------------------------------
| Guest Routes (Giriş yapmamış kullanıcılar)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    // Kayıt
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');

    // Giriş
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
});

/*
|--------------------------------------------------------------------------
| Public Routes (Herkes erişebilir)
|--------------------------------------------------------------------------
*/
// Paketler - Herkese açık
Route::get('/packages', [PackageController::class, 'index'])->name('packages.index');

/*
|--------------------------------------------------------------------------
| Auth Routes (Giriş yapmış kullanıcılar - Normal User)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    // Çıkış
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Kullanıcı Profil
    Route::get('/profile', [UserController::class, 'profile'])->name('user.profile');
    Route::get('/profile/edit', [UserController::class, 'edit'])->name('user.edit');
    Route::put('/profile', [UserController::class, 'update'])->name('user.update');

    // Token Geçmişi
    Route::get('/token-history', [UserController::class, 'tokenHistory'])->name('user.token-history');

    // Sepet
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::put('/cart/{cartItem}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{cartItem}', [CartController::class, 'remove'])->name('cart.remove');
    Route::delete('/cart', [CartController::class, 'clear'])->name('cart.clear');

    // Kupon
    Route::post('/cart/coupon/apply', [CartController::class, 'applyCoupon'])->name('cart.coupon.apply');
    Route::delete('/cart/coupon/remove', [CartController::class, 'removeCoupon'])->name('cart.coupon.remove');

    // Ödeme
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::get('/checkout/success/{orderId}', [CheckoutController::class, 'success'])->name('checkout.success');
    Route::get('/checkout/fail', [CheckoutController::class, 'fail'])->name('checkout.fail');

    // Siparişler
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{orderId}', [OrderController::class, 'show'])->name('orders.show');

    // TODO: Film Talebi Oluşturma (RequestController oluşturulduğunda eklenecek)
    // Route::get('/request/create', [RequestController::class, 'create'])->name('request.create');
    // Route::post('/request', [RequestController::class, 'store'])->name('request.store');
});

/*
|--------------------------------------------------------------------------
| Admin Routes (Sadece Admin Kullanıcılar)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'is_admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    // TODO: AdminController oluşturulduğunda eklenecek
    // Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Talepler Yönetimi
    // TODO: AdminRequestController oluşturulduğunda eklenecek
    // Route::get('/requests', [AdminRequestController::class, 'index'])->name('requests.index');
    // Route::get('/requests/{request}', [AdminRequestController::class, 'show'])->name('requests.show');
    // Route::put('/requests/{request}', [AdminRequestController::class, 'update'])->name('requests.update');

    // Kullanıcı Yönetimi
    // TODO: AdminUserController oluşturulduğunda eklenecek
    // Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    // Route::get('/users/{user}', [AdminUserController::class, 'show'])->name('users.show');
    // Route::put('/users/{user}', [AdminUserController::class, 'update'])->name('users.update');

    // Admin Kullanıcı Yönetimi
    // TODO: AdminUserController oluşturulduğunda eklenecek
    // Route::get('/admins', [AdminUserController::class, 'admins'])->name('admins.index');
    // Route::get('/admins/create', [AdminUserController::class, 'createAdmin'])->name('admins.create');
    // Route::post('/admins', [AdminUserController::class, 'storeAdmin'])->name('admins.store');
    // Route::delete('/admins/{user}', [AdminUserController::class, 'destroyAdmin'])->name('admins.destroy');

    // Paket Yönetimi
    // TODO: AdminPackageController oluşturulduğunda eklenecek
    // Route::resource('packages', AdminPackageController::class);

    // Kupon Yönetimi
    // TODO: AdminCouponController oluşturulduğunda eklenecek
    // Route::resource('coupons', AdminCouponController::class);

    // Sipariş Yönetimi
    // TODO: AdminOrderController oluşturulduğunda eklenecek
    // Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    // Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
});
