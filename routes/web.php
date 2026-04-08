<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\ArticleController;

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
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');

    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
});

/*
|--------------------------------------------------------------------------
| Public Routes (Herkes erişebilir)
|--------------------------------------------------------------------------
*/
Route::get('/packages', [PackageController::class, 'index'])->name('packages.index');

Route::get('/blog', [ArticleController::class, 'index'])->name('articles.index');
Route::get('/blog/{slug}', [ArticleController::class, 'show'])->name('articles.show');

Route::get('/terms', fn() => view('legal.terms'))->name('legal.terms');
Route::get('/copyright', fn() => view('legal.copyright'))->name('legal.copyright');
Route::get('/kvkk', fn() => view('legal.kvkk'))->name('legal.kvkk');
Route::get('/personal-data', fn() => view('legal.personal-data'))->name('legal.personal-data');
// Paddle Webhook (CSRF muaf, auth gerektirmez)
Route::post('/paddle/webhook', [\App\Http\Controllers\PaddleWebhookController::class, 'handle'])
    ->name('paddle.webhook');

/*
|--------------------------------------------------------------------------
| Auth Routes (Giriş yapmış kullanıcılar)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    // Çıkış
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Profil
    Route::get('/profile', [UserController::class, 'profile'])->name('user.profile');
    Route::get('/profile/edit', [UserController::class, 'edit'])->name('user.edit');
    Route::put('/profile', [UserController::class, 'update'])->name('user.update');

    // Token Geçmişi
    Route::get('/token-history', [UserController::class, 'tokenHistory'])->name('user.token-history');

    // Paddle Ödeme Sonuç Sayfaları
    Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');
    Route::get('/checkout/fail',    [CheckoutController::class, 'fail'])->name('checkout.fail');

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

    // Makale Kategorileri
    Route::get('/article-categories', [\App\Http\Controllers\Admin\AdminArticleCategoryController::class, 'index'])->name('article-categories.index');
    Route::get('/article-categories/create', [\App\Http\Controllers\Admin\AdminArticleCategoryController::class, 'create'])->name('article-categories.create');
    Route::post('/article-categories', [\App\Http\Controllers\Admin\AdminArticleCategoryController::class, 'store'])->name('article-categories.store');
    Route::get('/article-categories/{id}/edit', [\App\Http\Controllers\Admin\AdminArticleCategoryController::class, 'edit'])->name('article-categories.edit');
    Route::put('/article-categories/{id}', [\App\Http\Controllers\Admin\AdminArticleCategoryController::class, 'update'])->name('article-categories.update');
    Route::delete('/article-categories/{id}', [\App\Http\Controllers\Admin\AdminArticleCategoryController::class, 'destroy'])->name('article-categories.destroy');

    // Makale Yönetimi
    Route::get('/articles', [\App\Http\Controllers\Admin\AdminArticleController::class, 'index'])->name('articles.index');
    Route::get('/articles/create', [\App\Http\Controllers\Admin\AdminArticleController::class, 'create'])->name('articles.create');
    Route::post('/articles', [\App\Http\Controllers\Admin\AdminArticleController::class, 'store'])->name('articles.store');
    Route::get('/articles/{id}/edit', [\App\Http\Controllers\Admin\AdminArticleController::class, 'edit'])->name('articles.edit');
    Route::put('/articles/{id}', [\App\Http\Controllers\Admin\AdminArticleController::class, 'update'])->name('articles.update');
    Route::delete('/articles/{id}', [\App\Http\Controllers\Admin\AdminArticleController::class, 'destroy'])->name('articles.destroy');
    Route::post('/articles/update-order', [\App\Http\Controllers\Admin\AdminArticleController::class, 'updateOrder'])->name('articles.update-order');

    // Talepler Yönetimi
    Route::get('/requests', [\App\Http\Controllers\Admin\AdminRequestController::class, 'index'])->name('requests.index');
    Route::get('/requests/{requestId}', [\App\Http\Controllers\Admin\AdminRequestController::class, 'show'])->name('requests.show');
    Route::delete('/requests/{requestId}', [\App\Http\Controllers\Admin\AdminRequestController::class, 'destroy'])->name('requests.destroy');
    Route::post('/requests/{requestId}/update-status', [\App\Http\Controllers\Admin\AdminRequestController::class, 'updateStatus'])->name('requests.update-status');

    // Kullanıcı Yönetimi
    Route::get('/users', [\App\Http\Controllers\Admin\AdminUserController::class, 'index'])->name('users.index');
    Route::get('/users/{userId}', [\App\Http\Controllers\Admin\AdminUserController::class, 'show'])->name('users.show');
    Route::post('/users/{userId}/update-password', [\App\Http\Controllers\Admin\AdminUserController::class, 'updatePassword'])->name('users.update-password');
    Route::post('/users/{userId}/add-tokens', [\App\Http\Controllers\Admin\AdminUserController::class, 'addTokens'])->name('users.add-tokens');
    Route::delete('/users/{userId}', [\App\Http\Controllers\Admin\AdminUserController::class, 'destroy'])->name('users.destroy');

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
    Route::post('/orders/{orderId}/approve', [\App\Http\Controllers\Admin\AdminOrderController::class, 'approve'])->name('orders.approve');

    // İstatistikler
    Route::get('/statistics', [\App\Http\Controllers\Admin\AdminStatisticsController::class, 'index'])->name('statistics.index');
});
