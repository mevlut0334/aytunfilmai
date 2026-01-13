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

// Yasal Sayfalar - Herkese açık
Route::get('/terms', function () {
    return view('legal.terms');
})->name('legal.terms');

Route::get('/copyright', function () {
    return view('legal.copyright');
})->name('legal.copyright');

Route::get('/kvkk', function () {
    return view('legal.kvkk');
})->name('legal.kvkk');

Route::get('/personal-data', function () {
    return view('legal.personal-data');
})->name('legal.personal-data');

/*
|--------------------------------------------------------------------------
| İyzico Callback Route (Auth dışında, CSRF bootstrap/app.php'de muaf)
| Token tabanlı güvenlik ile korunuyor
|--------------------------------------------------------------------------
*/
Route::post('/checkout/callback', [CheckoutController::class, 'callback'])
    ->name('checkout.callback');

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

    // Ödeme (callback hariç - yukarıda tanımlandı)
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
    Route::get('/dashboard', [\App\Http\Controllers\Admin\AdminController::class, 'dashboard'])->name('dashboard');

    // Ana Sayfa Yönetimi
    Route::get('/home', [\App\Http\Controllers\Admin\AdminHomeController::class, 'index'])->name('home.index');

    // Slider Yönetimi
    Route::get('/home/sliders', [\App\Http\Controllers\Admin\AdminHomeController::class, 'sliders'])->name('home.sliders');
    Route::get('/home/sliders/create', [\App\Http\Controllers\Admin\AdminHomeController::class, 'createSlider'])->name('home.sliders.create');
    Route::post('/home/sliders', [\App\Http\Controllers\Admin\AdminHomeController::class, 'storeSlider'])->name('home.sliders.store');
    Route::get('/home/sliders/{id}/edit', [\App\Http\Controllers\Admin\AdminHomeController::class, 'editSlider'])->name('home.sliders.edit');
    Route::put('/home/sliders/{id}', [\App\Http\Controllers\Admin\AdminHomeController::class, 'updateSlider'])->name('home.sliders.update');
    Route::delete('/home/sliders/{id}', [\App\Http\Controllers\Admin\AdminHomeController::class, 'destroySlider'])->name('home.sliders.destroy');

    // Scrolling Images Yönetimi
    Route::get('/home/scrolling', [\App\Http\Controllers\Admin\AdminHomeController::class, 'scrollingImages'])->name('home.scrolling');
    Route::get('/home/scrolling/create', [\App\Http\Controllers\Admin\AdminHomeController::class, 'createScrollingImage'])->name('home.scrolling.create');
    Route::post('/home/scrolling', [\App\Http\Controllers\Admin\AdminHomeController::class, 'storeScrollingImage'])->name('home.scrolling.store');
    Route::get('/home/scrolling/{id}/edit', [\App\Http\Controllers\Admin\AdminHomeController::class, 'editScrollingImage'])->name('home.scrolling.edit');
    Route::put('/home/scrolling/{id}', [\App\Http\Controllers\Admin\AdminHomeController::class, 'updateScrollingImage'])->name('home.scrolling.update');
    Route::delete('/home/scrolling/{id}', [\App\Http\Controllers\Admin\AdminHomeController::class, 'destroyScrollingImage'])->name('home.scrolling.destroy');

    // FAQ Yönetimi
    Route::get('/home/faqs', [\App\Http\Controllers\Admin\AdminHomeController::class, 'faqs'])->name('home.faqs');
    Route::get('/home/faqs/create', [\App\Http\Controllers\Admin\AdminHomeController::class, 'createFaq'])->name('home.faqs.create');
    Route::post('/home/faqs', [\App\Http\Controllers\Admin\AdminHomeController::class, 'storeFaq'])->name('home.faqs.store');
    Route::get('/home/faqs/{id}/edit', [\App\Http\Controllers\Admin\AdminHomeController::class, 'editFaq'])->name('home.faqs.edit');
    Route::put('/home/faqs/{id}', [\App\Http\Controllers\Admin\AdminHomeController::class, 'updateFaq'])->name('home.faqs.update');
    Route::delete('/home/faqs/{id}', [\App\Http\Controllers\Admin\AdminHomeController::class, 'destroyFaq'])->name('home.faqs.destroy');

    // Site Ayarları
    Route::get('/home/settings', [\App\Http\Controllers\Admin\AdminHomeController::class, 'settings'])->name('home.settings');
    Route::put('/home/settings', [\App\Http\Controllers\Admin\AdminHomeController::class, 'updateSettings'])->name('home.settings.update');

    // Talepler Yönetimi
    Route::get('/requests', [\App\Http\Controllers\Admin\AdminRequestController::class, 'index'])->name('requests.index');
    Route::get('/requests/{requestId}', [\App\Http\Controllers\Admin\AdminRequestController::class, 'show'])->name('requests.show');
    Route::post('/requests/{requestId}/update-status', [\App\Http\Controllers\Admin\AdminRequestController::class, 'updateStatus'])->name('requests.update-status');

    // Kullanıcı Yönetimi
    Route::get('/users', [\App\Http\Controllers\Admin\AdminUserController::class, 'index'])->name('users.index');
    Route::get('/users/{userId}', [\App\Http\Controllers\Admin\AdminUserController::class, 'show'])->name('users.show');
    Route::get('/users', [\App\Http\Controllers\Admin\AdminUserController::class, 'index'])->name('users.index');
    Route::get('/users/{userId}', [\App\Http\Controllers\Admin\AdminUserController::class, 'show'])->name('users.show');
    Route::post('/users/{userId}/update-password', [\App\Http\Controllers\Admin\AdminUserController::class, 'updatePassword'])->name('users.update-password');


    // Admin Kullanıcı Yönetimi
    Route::get('/admins', [\App\Http\Controllers\Admin\AdminAdminController::class, 'index'])->name('admins.index');
    Route::get('/admins/create', [\App\Http\Controllers\Admin\AdminAdminController::class, 'create'])->name('admins.create');
    Route::post('/admins', [\App\Http\Controllers\Admin\AdminAdminController::class, 'store'])->name('admins.store');
    Route::delete('/admins/{userId}', [\App\Http\Controllers\Admin\AdminAdminController::class, 'destroy'])->name('admins.destroy');

    // Paket Yönetimi
    Route::get('/packages', [\App\Http\Controllers\Admin\AdminPackageController::class, 'index'])->name('packages.index');
    Route::get('/packages/create', [\App\Http\Controllers\Admin\AdminPackageController::class, 'create'])->name('packages.create');
    Route::post('/packages', [\App\Http\Controllers\Admin\AdminPackageController::class, 'store'])->name('packages.store');
    Route::get('/packages/{packageId}/edit', [\App\Http\Controllers\Admin\AdminPackageController::class, 'edit'])->name('packages.edit');
    Route::put('/packages/{packageId}', [\App\Http\Controllers\Admin\AdminPackageController::class, 'update'])->name('packages.update');
    Route::delete('/packages/{packageId}', [\App\Http\Controllers\Admin\AdminPackageController::class, 'destroy'])->name('packages.destroy');

    // Kupon Yönetimi
    Route::get('/coupons', [\App\Http\Controllers\Admin\AdminCouponController::class, 'index'])->name('coupons.index');
    Route::get('/coupons/create', [\App\Http\Controllers\Admin\AdminCouponController::class, 'create'])->name('coupons.create');
    Route::post('/coupons', [\App\Http\Controllers\Admin\AdminCouponController::class, 'store'])->name('coupons.store');
    Route::get('/coupons/{couponId}/edit', [\App\Http\Controllers\Admin\AdminCouponController::class, 'edit'])->name('coupons.edit');
    Route::put('/coupons/{couponId}', [\App\Http\Controllers\Admin\AdminCouponController::class, 'update'])->name('coupons.update');
    Route::delete('/coupons/{couponId}', [\App\Http\Controllers\Admin\AdminCouponController::class, 'destroy'])->name('coupons.destroy');

    // Sipariş Yönetimi
    Route::get('/orders', [\App\Http\Controllers\Admin\AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{orderId}', [\App\Http\Controllers\Admin\AdminOrderController::class, 'show'])->name('orders.show');

    // İstatistikler
    Route::get('/statistics', [\App\Http\Controllers\Admin\AdminStatisticsController::class, 'index'])->name('statistics.index');
});
