<?php

use App\Http\Controllers\Backend\AdminController;
use App\Http\Controllers\Backend\AdminVendorProfileController;
use App\Http\Controllers\Backend\BrandController;
use App\Http\Controllers\Backend\CategoryController;
use App\Http\Controllers\Backend\ChildCategoryController;
use App\Http\Controllers\Backend\CouponController;
use App\Http\Controllers\Backend\FlashSaleController;
use App\Http\Controllers\Backend\PaymentSettingController;
use App\Http\Controllers\Backend\PaypalSettingsController;
use App\Http\Controllers\Backend\ProductController;
use App\Http\Controllers\Backend\ProductImageGalleryController;
use App\Http\Controllers\Backend\ProductVariantController;
use App\Http\Controllers\Backend\ProductVariantItemController;
use App\Http\Controllers\Backend\ProfileController;
use App\Http\Controllers\Backend\RazorpaySettingController;
use App\Http\Controllers\Backend\SellerProductController;
use App\Http\Controllers\Backend\SettingController;
use App\Http\Controllers\Backend\ShippingRuleController;
use App\Http\Controllers\Backend\SliderController;
use App\Http\Controllers\Backend\StripeSettingController;
use App\Http\Controllers\Backend\SubCategoryController;
use Illuminate\Support\Facades\Route;

/*Admin Routes*/

Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

/*Profile Routes*/
Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
Route::post('/profile/update', [ProfileController::class, 'updateProfile'])->name('profile.update');
Route::post('/profile/update/password', [ProfileController::class, 'updatePassword'])->name('password.update');

/*Slider Route*/
Route::resource('/slider', SliderController::class);

/*Category Route*/
Route::put('/change-status', [CategoryController::class, 'changeStatus'])->name('category.change-status');
Route::resource('/category', CategoryController::class);

/*Sub Category Route*/
Route::put('/sub-category/change-status', [SubCategoryController::class, 'changeStatus'])->name('sub-category.change-status');
Route::resource('/sub-category', SubCategoryController::class);

/*Child Category Route*/
Route::put('/child-category/change-status', [ChildCategoryController::class, 'changeStatus'])->name('child-category.change-status');
Route::get('get-subcategories', [ChildCategoryController::class, 'getSubCategories'])->name('get-subcategories');
Route::resource('/child-category', ChildCategoryController::class);

/*Brand Route*/
Route::put('/brand/change-status', [BrandController::class, 'changeStatus'])->name('brand.change-status');
Route::resource('/brand', BrandController::class);

/*Vendor Profile routes*/
Route::resource('/vendor-profile', AdminVendorProfileController::class);

/*Product routes*/
Route::get('product/get-subcategories', [ProductController::class, 'getSubCategories'])->name('product.get-subcategories');
Route::get('product/get-child-categories', [ProductController::class, 'getChildCategories'])->name('product.get-child-categories');
Route::put('/products/change-status', [ProductController::class, 'changeStatus'])->name('product.change-status');
Route::resource('/products', ProductController::class);

/*Products image gallery route **/
Route::resource('/products-image-gallery', ProductImageGalleryController::class);

/*Product variant route*/
Route::put('/products-variant/change-status', [ProductVariantController::class, 'changeStatus'])->name('products-variant.change-status');
Route::resource('/products-variant', ProductVariantController::class);

/*Product variant item route **/
Route::get('/products-variant-item/{productId}/{variantItemId}', [ProductVariantItemController::class, 'index'])->name('products-variant-item.index');
Route::get('/products-variant-item/create/{productId}/{variantItemId}', [ProductVariantItemController::class, 'create'])->name('products-variant-item.create');
Route::post('/products-variant-item', [ProductVariantItemController::class, 'store'])->name('products-variant-item.store');
Route::get('/products-variant-item-edit/{variantItemId}', [ProductVariantItemController::class, 'edit'])->name('products-variant-item.edit');
Route::put('/products-variant-item-update/{variantItemId}', [ProductVariantItemController::class, 'update'])->name('products-variant-item.update');
Route::delete('/products-variant-item/{variantItemId}', [ProductVariantItemController::class, 'destroy'])->name('products-variant-item.destroy');
Route::put('/products-variant-item-status', [ProductVariantItemController::class, 'changeStatus'])->name('products-variant-item.change-status');

/** Seller product routes **/
Route::get('/seller-products', [SellerProductController::class, 'index'])->name('seller-products.index');
Route::get('/seller-pending-products', [SellerProductController::class, 'pendingProducts'])->name('seller-pending-products.index');
Route::put('/change-approved-status', [SellerProductController::class, 'changeApprovedStatus'])->name('change-approved-status');

/** Flash Sale Routes */
Route::get('/flash-sale', [FlashSaleController::class, 'index'])->name('flash-sale.index');
Route::put('/flash-sale', [FlashSaleController::class, 'update'])->name('flash-sale.update');
Route::post('/flash-sale/add-product', [FlashSaleController::class, 'addProduct'])->name('flash-sale.add-product');
Route::put('/flash-sale/show_at_home/status-change', [FlashSaleController::class, 'changeShowAtHomeStatus'])->name('flash-sale.show_at_home.change-status');
Route::put('/flash-sale-status', [FlashSaleController::class, 'changeStatus'])->name('flash-sale-status');
Route::delete('/flash-sale/{id}', [FlashSaleController::class, 'destroy'])->name('flash-sale.destroy');

/** Coupon Routes */
Route::put('/coupons/change-status', [CouponController::class, 'changeStatus'])->name('coupons.change-status');
Route::resource('/coupons', CouponController::class);

/** Shipping Rule Routes */
Route::put('/shipping-rule/change-status', [ShippingRuleController::class, 'changeStatus'])->name('shipping-rule.change-status');
Route::resource('/shipping-rule', ShippingRuleController::class);

/** Settings routes */
Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
Route::put('/general-setting-update', [SettingController::class, 'generalSettingUpdate'])->name('general-setting-update');

/** Payment settings routes */
Route::get('/payment-settings', [PaymentSettingController::class, 'index'])->name('payment-settings.index');
Route::resource('/paypal-setting', PaypalSettingsController::class);
Route::put('/stripe-setting/{id}', [StripeSettingController::class, 'update'])->name('stripe-setting.update');
Route::put('/razorpay-setting/{id}', [RazorpaySettingController::class, 'update'])->name('razorpay-setting.update');
