<?php
use App\Http\Controllers\{AccountController, CartController, CheckoutController, HomeController, OrderController, PageController, ProductController, ProfileController, ShopController, WishlistController};
use App\Http\Controllers\Admin;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/shop', [ShopController::class, 'index'])->name('shop');
Route::get('/collections/offer-zone', [ShopController::class, 'offer'])->name('offer');
Route::get('/collections/{category}', [ShopController::class, 'collection'])->name('collection');
Route::get('/brand/{brand}', [ShopController::class, 'brand'])->name('brand');
Route::get('/all-brands', [ShopController::class, 'brands'])->name('brands');
Route::get('/offered/product', [ShopController::class, 'flash'])->name('flash');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('product.show');
Route::get('/load-more', [ProductController::class, 'loadMore'])->name('load.more');

Route::get('/cart/drawer', [CartController::class, 'drawer']);
Route::post('/cart/add', [CartController::class, 'add']);
Route::post('/cart/update', [CartController::class, 'update']);
Route::post('/cart/remove', [CartController::class, 'remove']);
Route::match(['get', 'post'], '/buy/now/{product}', [CartController::class, 'buyNow'])->name('buy.now');
Route::post('/coupon', [CartController::class, 'applyCoupon'])->name('coupon.apply');
Route::delete('/coupon', [CartController::class, 'removeCoupon'])->name('coupon.remove');

Route::get('/checkout', [CheckoutController::class, 'show'])->name('checkout');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/order/success/{orderNo}', [OrderController::class, 'success'])->name('order.success');
Route::get('/track/order', [OrderController::class, 'trackForm'])->name('track');
Route::post('/track/order', [OrderController::class, 'track'])->name('track.check');
Route::get('/page/{page}', [PageController::class, 'show'])->name('page');

Route::middleware('auth')->group(function () {
    Route::get('/my/orders', [AccountController::class, 'orders'])->name('account.orders');
    Route::get('/my/wishlists', [WishlistController::class, 'index'])->name('wishlist');
    Route::post('/wishlist/{product}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
    Route::get('/dashboard', fn () => redirect()->route('account.orders'))->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::get('orders', [Admin\OrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{order}', [Admin\OrderController::class, 'show'])->name('orders.show');
    Route::patch('orders/{order}', [Admin\OrderController::class, 'update'])->name('orders.update');
    Route::get('settings', [Admin\SettingController::class, 'edit'])->name('settings');
    Route::post('settings', [Admin\SettingController::class, 'update'])->name('settings.update');
    Route::post('products/{product}/variants', [Admin\VariantController::class, 'store'])->name('admin.variants.store');
Route::put('variants/{variant}', [Admin\VariantController::class, 'update'])->name('admin.variants.update');
Route::delete('variants/{variant}', [Admin\VariantController::class, 'destroy'])->name('admin.variants.destroy');

    Route::controller(Admin\CrudController::class)->prefix('{resource}')
        ->where(['resource' => 'products|categories|brands|banners|testimonials|pages|coupons'])
        ->group(function () {
            Route::get('/', 'index')->name('crud.index');
            Route::get('create', 'create')->name('crud.create');
            Route::post('/', 'store')->name('crud.store');
            Route::get('{id}/edit', 'edit')->name('crud.edit');
            Route::put('{id}', 'update')->name('crud.update');
            Route::delete('{id}', 'destroy')->name('crud.destroy');
        });
});