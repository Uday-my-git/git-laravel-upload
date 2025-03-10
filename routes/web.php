<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\AdminLoginController;
use App\Http\Controllers\admin\BarndController;
use App\Http\Controllers\admin\CategorieController;
use App\Http\Controllers\admin\HomeController;
use App\Http\Controllers\admin\SubCategoryController;
use App\Http\Controllers\admin\TempImagesController;
use App\Http\Controllers\admin\PageController;
use App\Http\Controllers\admin\ProductController;
use App\Http\Controllers\admin\ProductImageController;
use App\Http\Controllers\admin\ProductSubCategoryController;
use App\Http\Controllers\admin\ShippingController;
use App\Http\Controllers\admin\OrderController;
use App\Http\Controllers\admin\UserController;
use App\Http\Controllers\admin\DiscountCodeController;
use App\Http\Controllers\admin\SettingController;

// front end of prodcut listing
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\ShopController;

// front end of Add to Cart Prodcut
use App\Http\Controllers\CartController;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

// Route::get('/emailTest', function () {
//     orderEmail(132);
// });

/****************************** Add To Wishlist ******************************/ 

Route::get('/', [FrontController::class, 'index'])->name('front.home');
Route::post('/add-to-wishlist', [FrontController::class, 'addToWishlist'])->name('front.addToWishlist');
Route::get('/page/{slug}', [FrontController::class, 'page'])->name('front.page');
Route::post('/send-contact-us-email', [FrontController::class, 'sendContactUsEmail'])->name('front.sendContactUsEmail');

/***************************** Add ShopController Route ***********************/ 
Route::get('/shop/{categorySlug?}/{subCategorySlug?}', [ShopController::class, 'index'])->name('front.shop');
Route::get('/product/{slug}', [ShopController::class, 'product'])->name('front.product');
Route::post('/user-rating/{id}', [ShopController::class, 'userRating'])->name('front.userRating');

/**************************** Forgot Password Route *************************/
Route::get('/forgot-password', [AuthController::class, 'forgotPassword'])->name('front.forgotPassword');
Route::post('/process-forgot-password', [AuthController::class, 'processForgotPassword'])->name('front.processForgotPassword');
Route::get('/process-reset-password/{token}', [AuthController::class, 'resetPasswordAccount'])->name('front.resetPasswordAccount');
Route::post('/process-reset-password-account', [AuthController::class, 'processResetPasswordAccount'])->name('front.processResetPasswordAccount');

/**************************** Add to Cart Prodcut Front End Side **************/
Route::get('/cart', [CartController::class, 'cart'])->name('front.cart');
Route::post('/add-to-cart', [CartController::class, 'addToCart'])->name('front.addToCart');
Route::post('/update-cart', [CartController::class, 'updateCart'])->name('front.updateCart');
Route::post('/remove-cart-item', [CartController::class, 'removeCartItem'])->name('front.removeCartItem');
Route::get('/checkout', [CartController::class, 'checkout'])->name('front.checkout');
Route::post('/get-order-summery', [CartController::class, 'getOrderSummery'])->name('front.getOrderSummery');
Route::post('/process-checkout', [CartController::class, 'processCheckout'])->name('front.processCheckout');
Route::post('/apply-discount-coupon', [CartController::class, 'applyCouponCode'])->name('front.applyCouponCode');
Route::post('/remove-discount-coupon', [CartController::class, 'removeCouponCode'])->name('front.removeCouponCode');
Route::get('/thank-you-page/{orderId}', [CartController::class, 'thankyouPage'])->name('front.thankyouPage');

/**************************** Rront-end User Login & Signup ******************/ 
Route::group(['prefix' => 'account'], function () { 
    Route::group(['middleware' => 'guest'], function () {
        Route::get('/register', [AuthController::class, 'register'])->name('account.register');
        Route::get('/login', [AuthController::class, 'login'])->name('account.login');
        Route::post('/login-authenticate', [AuthController::class, 'authenticate'])->name('account.authenticate');
        Route::post('/save-register-form', [AuthController::class, 'saveRegisterForm'])->name('account.saveRegisterForm');
    });

    Route::group(['middleware' => 'auth'], function () {
        Route::get('/profile', [AuthController::class, 'profile'])->name('account.profile');
        Route::post('/update-profile', [AuthController::class, 'updateProfile'])->name('account.updateProfile');
        Route::post('/update-address', [AuthController::class, 'updateAddress'])->name('account.updateAddress');
        Route::get('/my-orders', [AuthController::class, 'orders'])->name('account.orders');
        Route::get('/order-detail/{orderId}', [AuthController::class, 'get_orderDetail'])->name('account.get_orderDetail');
        Route::get('/wishlist', [AuthController::class, 'wishlist'])->name('account.wishlist');
        Route::post('/remove-wishlist', [AuthController::class, 'removeWishlist'])->name('account.removeWishlist');
        Route::get('/logout', [AuthController::class, 'logout'])->name('account.logout');
        Route::get('/change-password-form', [AuthController::class, 'changePasswordForm'])->name('account.changePasswordForm');
        Route::post('/change-password', [AuthController::class, 'changePassword'])->name('account.changePassword');

    });
});

/**************************** Admin Route Define ****************************/
Route::group(['prefix' => 'admin'], function() {
    Route::group(['middleware' => 'admin.guest'], function() {
        Route::get('/login', [AdminLoginController::class, 'index'])->name('admin.login');
        Route::post('/authenticate', [AdminLoginController::class, 'authenticate'])->name('admin.authenticate');
    });

    Route::group(['middleware' => 'admin.auth'], function() {
        Route::get('/dashboard', [HomeController::class, 'index'])->name('admin.dashboard');
        Route::get('/logout', [HomeController::class, 'logout'])->name('admin.logout');

        /**************************** Categories Route Define ****************************/
        Route::get('/categories', [CategorieController::class, 'index'])->name('categories.index');
        Route::get('/categories/create', [CategorieController::class, 'create'])->name('categories.create');
        Route::post('/categories', [CategorieController::class, 'store'])->name('categories.store');

        Route::get('/getSlug', [CategorieController::class, 'getSlug'])->name('categories.getSlug');
        Route::get('/categories/{id}/edit', [CategorieController::class, 'edit'])->name('categories.edit');
        Route::put('/categories/{id}', [CategorieController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{id}', [CategorieController::class, 'destroy'])->name('categories.delete');
        
        /****************************  Sub Categories Route Define ****************************/
        Route::get('/sub-categories', [SubCategoryController::class, 'index'])->name('sub-category.index');
        Route::get('/sub-categories/create', [SubCategoryController::class, 'create'])->name('sub_categories.create');
        Route::post('/sub-categories/store', [SubCategoryController::class, 'store'])->name('sub_categories.store');
        Route::get('/sub-categories/{id}/edit', [SubCategoryController::class, 'edit'])->name('sub_categories.edit');
        Route::put('/sub-categories/{id}', [SubCategoryController::class, 'update'])->name('sub_categories.update');
        Route::delete('/sub-categories/{id}', [SubCategoryController::class, 'destroy'])->name('sub_categories.delete');

        /**************************** Barnd Route Define ****************************/
        Route::get('/brands/list', [BarndController::class, 'index'])->name('brands.brandListing');
        Route::get('/brands/create', [BarndController::class, 'create'])->name('brands.create');
        Route::post('/brands/save', [BarndController::class, 'store'])->name('brands.save');
        Route::get('/brands/{id}/edit', [BarndController::class, 'edit'])->name('brands.edit');
        Route::put('/brands/{id}', [BarndController::class, 'update'])->name('brands.update');
        Route::delete('/brands/{id}', [BarndController::class, 'destroy'])->name('brands.delete');

        /**************************** Product Route Define ****************************/
        Route::get('/product/list', [ProductController::class, 'index'])->name('product.productListing');
        Route::get('/product/create', [ProductController::class, 'create'])->name('product.create');
        Route::post('/product/save', [ProductController::class, 'store'])->name('product.save');
        Route::get('/product/{id}/edit', [ProductController::class, 'edit'])->name('product.edit');
        Route::put('/product/{id}', [ProductController::class, 'update'])->name('product.update');
        Route::delete('/product/{id}', [ProductController::class, 'deleteProduct'])->name('product.delete');
        Route::get('/product/getProducts', [ProductController::class, 'getRelatedProducts'])->name('product.getRelatedProducts');
            
        /**************************** Product SubCategory Route Define ****************************/
        Route::get('/product-subCategory', [ProductSubCategoryController::class, 'index'])->name('product.subCategory');
        
        // Product image update route define
        Route::post('/product-image/update', [ProductImageController::class, 'update'])->name('product-image.update');
        Route::delete('/product-image/{id}', [ProductImageController::class, 'deleteProductImage'])->name('product-image.delete');

        /**************************** Shipping Route Define ****************************/
        Route::get('/shipping', [ShippingController::class, 'create'])->name('shipping.create');
        Route::post('/store', [ShippingController::class, 'store'])->name('shipping.store');
        Route::get('/edit/{id}', [ShippingController::class, 'edit'])->name('shipping.edit');
        Route::put('/update/{id}', [ShippingController::class, 'update'])->name('shipping.update');
        Route::delete('/delete/{id}', [ShippingController::class, 'destroy'])->name('shipping.destroy');

        /**************************** Dsicount coupon code route ****************************/
        Route::get('coupons/listing', [DiscountCodeController::class, 'index'])->name('coupons.list');
        Route::get('coupons/create', [DiscountCodeController::class, 'create'])->name('coupons.create');
        Route::post('coupons/store', [DiscountCodeController::class, 'store'])->name('coupons.store');
        Route::get('coupons/edit/{id}', [DiscountCodeController::class, 'edit'])->name('coupons.edit');
        Route::put('coupons/update/{id}', [DiscountCodeController::class, 'update'])->name('coupons.update');
        Route::delete('coupons/delete/{id}', [DiscountCodeController::class, 'destroy'])->name('coupons.destroy');

        /**************************** Order Routes ****************************/
        Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('orders-details/{orderId}', [OrderController::class, 'getOrderDetail'])->name('orders.getOrderDetail');
        Route::post('change-orderStatus/{statusId}', [OrderController::class, 'changeOrderStatus'])->name('orders.changeOrderStatus');
        Route::post('send-email-invoice/{orderId}', [OrderController::class, 'sendInvoiceEmail'])->name('orders.sendInvoiceEmail');
        
        /**************************** User Route Define  ****************************/
        Route::get('user-list', [UserController::class, 'index'])->name('user.index');
        Route::get('create', [UserController::class, 'userCreate'])->name('user.userCreate');
        Route::post('save', [UserController::class, 'save'])->name('user.save');
        Route::get('user-edit/{id}', [UserController::class, 'edit'])->name('user.userEdit');
        Route::put('user-update/{id}', [UserController::class, 'update'])->name('user.userupdate');
        Route::delete('user-delete/{id}', [UserController::class, 'remove'])->name('user.remove');

        /**************************** Pages Route Define  ****************************/
        Route::get('pages-list', [PageController::class, 'index'])->name('pages.listPage');
        Route::get('create-new', [PageController::class, 'create'])->name('pages.createPage');
        Route::post('pages-store', [PageController::class, 'store'])->name('pages.store');
        Route::get('pages-edit/{id}', [PageController::class, 'edit'])->name('pages.edit');
        Route::put('pages-update/{id}', [PageController::class, 'update'])->name('pages.update');
        Route::delete('pages-delete/{id}', [PageController::class, 'delete'])->name('pages.delete');

        /**************************** Pages Route Define  ****************************/
        Route::get('chage-password-form', [SettingController::class, 'chagePasswordForm'])->name('admin.chagePasswordForm');
        Route::post('chage-password', [SettingController::class, 'chagePassword'])->name('admin.chagePassword');

        /**************************** Temp-images Route Define ****************************/
        Route::post('/upload-temp-images', [TempImagesController::class, 'create'])->name('temp-images.create');

        Route::get('/getSlug', function(Request $request) {      // type 1 generate slug
            $slug = "";

            if (!empty($request->title)) {
                $slug = Str::slug($request->title);
            }
            return  response()->json(['status' => true, 'slug' => $slug]);
        })->name('getSlug');
    });
});