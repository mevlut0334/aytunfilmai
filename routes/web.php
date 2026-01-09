<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\RequestController;

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

    // Film Talepleri
    Route::get('/requests', [RequestController::class, 'index'])->name('requests.index');
    Route::get('/requests/create', [RequestController::class, 'create'])->name('requests.create');
    Route::post('/requests', [RequestController::class, 'store'])->name('requests.store');
    Route::get('/requests/{requestId}', [RequestController::class, 'show'])->name('requests.show');
    Route::delete('/requests/{requestId}', [RequestController::class, 'destroy'])->name('requests.destroy');
});

/*
|--------------------------------------------------------------------------
| Admin Routes (Sadece Admin Kullanıcılar)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'is_admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    // TODO: AdminController oluşturulduğunda eklenecek
    // Dashboard
       Route::get('/dashboard', [\App\Http\Controllers\Admin\AdminController::class, 'dashboard'])->name('dashboard');

    // Talepler Yönetimi
       Route::get('/requests', [\App\Http\Controllers\Admin\AdminRequestController::class, 'index'])->name('requests.index');
       Route::get('/requests/{requestId}', [\App\Http\Controllers\Admin\AdminRequestController::class, 'show'])->name('requests.show');
       Route::post('/requests/{requestId}/update-status', [\App\Http\Controllers\Admin\AdminRequestController::class, 'updateStatus'])->name('requests.update-status');

    // Kullanıcı Yönetimi
       Route::get('/users', [\App\Http\Controllers\Admin\AdminUserController::class, 'index'])->name('users.index');
       Route::get('/users/{userId}', [\App\Http\Controllers\Admin\AdminUserController::class, 'show'])->name('users.show');

    // Admin Kullanıcı Yönetimi
       Route::get('/admins', [\App\Http\Controllers\Admin\AdminAdminController::class, 'index'])->name('admins.index');
       Route::get('/admins/create', [\App\Http\Controllers\Admin\AdminAdminController::class, 'create'])->name('admins.create');
       Route::post('/admins', [\App\Http\Controllers\Admin\AdminAdminController::class, 'store'])->name('admins.store');
       Route::delete('/admins/{userId}', [\App\Http\Controllers\Admin\AdminAdminController::class, 'destroy'])->name('admins.destroy');

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
