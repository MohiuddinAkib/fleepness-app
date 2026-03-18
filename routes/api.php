<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SMSController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Broadcast;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\user\CartController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\Auth\OTPAuthController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SizeTemplateController;
use App\Http\Controllers\user\AddressController;
use App\Http\Controllers\DeliveryModelController;
use App\Http\Controllers\user\UserSearchController;
use App\Http\Controllers\user\UserVendorController;
use App\Http\Controllers\Auth\SocialLoginController;
use App\Http\Controllers\user\UserProductController;
use App\Http\Controllers\user\UserProfileController;
use App\Http\Controllers\Admin\AdminSliderController;
use App\Http\Controllers\ShortsInteractionController;
use App\Http\Controllers\Admin\ShopCategoryController;
use App\Http\Controllers\Vendor\VendorProductController;
use App\Http\Controllers\user\UserVendorFollowController;
use App\Http\Controllers\user\UserVendorReviewController;
use App\Http\Controllers\Vendor\VendorShortVideoController;
use App\Http\Controllers\LiveStreaming\LivestreamController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\LiveStreaming\LivestreamCommentController;
use App\Http\Controllers\LiveStreaming\LivestreamProductController;
use App\Http\Controllers\LiveStreaming\GetLivestreamPublisherTokenController;
use App\Http\Controllers\LiveStreaming\GetLivestreamSubscriberTokenController;

Route::middleware(['api', 'throttle:api'])->group(function (): void {

    // Auth
    Route::get('/auth/{provider}', [SocialLoginController::class, 'redirectToProvider']);
    Route::get('/auth/{provider}/callback', [SocialLoginController::class, 'handleProviderCallback']);
    Route::post('/register', [OTPAuthController::class, 'register']);
    Route::post('/send-sms', [SMSController::class, 'sendSMS']);
    Route::post('/send-login-otp', [AuthenticatedSessionController::class, 'apiSendOtp']);
    Route::post('/verify-otp', [OTPAuthController::class, 'verifyOtp']);
    Route::post('/resend-otp', [OTPAuthController::class, 'resendOtp']);
    Route::post('/seller/register', [UserController::class, 'application']);

    // Public data
    Route::get('/payment-methods', [UserProfileController::class, 'getPaymenMethods']);
    Route::get('/sliders', [AdminSliderController::class, 'getAllSliders']);
    Route::get('/delivery/models', [DeliveryModelController::class, 'userIndex']);
    Route::get('/sections', [SectionController::class, 'sections']);
    Route::get('/search-section', [SectionController::class, 'searchSection']);
    Route::get('/search/product', [UserProductController::class, 'search']);
    Route::get('/search', [UserSearchController::class, 'search']);
    Route::get('/get-categories', [CategoryController::class, 'getCategories'])->name('get.categories');
    Route::get('/get-categories-by-order', [CategoryController::class, 'getOrderBasisCategories'])->name('get.categories.by.order');
    Route::get('/categories', [CategoryController::class, 'getCategoriesOnly']);
    Route::get('/get-tags', [TagController::class, 'getTags'])->name('get.tags');
    Route::get('/get-random-tags', [TagController::class, 'getTagsRandom'])->name('get.random.tags');
    Route::get('/get-tag-info/{id}', [TagController::class, 'getTagInfo'])->name('get.tag.info');
    Route::get('/get-product-by-tag/{id}', [TagController::class, 'getProductByTag'])->name('get.product.by.tag');
    Route::get('/get-own-product-by-tag/{id}', [TagController::class, 'getOwnProductByTag'])->name('get.own.product.by.tag');
    Route::get('/product/{product}', [UserProductController::class, 'show']);
    Route::get('/product/{id}/similar', [UserProductController::class, 'getSimilarProducts']);
    Route::get('/seller/{id}/products', [UserProductController::class, 'getProductsByType']);
    Route::get('/user/{userId}/tags/most-used', [TagController::class, 'getMostUsedTags']);
    Route::get('/user/{userId}/tags/used', [TagController::class, 'getAllUsedTags']);

    // Shop categories
    Route::get('/shop-categories', [ShopCategoryController::class, 'index']);
    Route::get('/shop-categories/{shopCategory}', [ShopCategoryController::class, 'show']);

    // Vendors
    Route::prefix('vendors')->group(function (): void {
        Route::get('/', [UserVendorController::class, 'vendorlist']);
        Route::get('/{vendor}', [UserVendorController::class, 'vendorData']);
        Route::get('/{vendor}/similar', [UserVendorController::class, 'similarSellers']);
        Route::get('/{vendor}/products', [UserProductController::class, 'getAllProducts']);
        Route::get('/{vendor}/products/price-range', [UserProductController::class, 'getProductsByPriceRange']);
        Route::get('/{vendor}/products/price-category', [UserProductController::class, 'getProductsInPriceCategory']);
        Route::get('/{vendor}/short-videos', [UserVendorController::class, 'getShortVideos']);
        Route::get('/{vendor}/reviews', [UserVendorReviewController::class, 'index']);
    });

    // Short videos (public)
    Route::prefix('short-videos')->group(function (): void {
        Route::get('/', [ShortsInteractionController::class, 'allshorts']);
        Route::get('/{id}', [VendorShortVideoController::class, 'showApi']);
        Route::get('/{id}/products', [ShortsInteractionController::class, 'getShortProducts']);
        Route::get('/{id}/comments', [ShortsInteractionController::class, 'getComments']);
    });

    // Livestreams (public)
    Route::get('livestreams', [LivestreamController::class, 'index'])->name('livestreams.index');
    Route::get('livestreams/{ls}', [LivestreamController::class, 'show'])->name('livestreams.show');
    Route::get('livestreams/{livestream}/subscriber-token', GetLivestreamSubscriberTokenController::class)->name('livestreams.get-subscriber-token');
    Route::get('livestream/{livestream}/products', [LivestreamController::class, 'addedProducts']);

    Route::delete('broadcasting/auth', fn (Request $request) => Broadcast::driver('fcm')->unauth($request));

    // Authenticated routes
    Route::middleware('auth:sanctum')->group(function (): void {

        // Auth & profile
        Route::post('/logout', [AuthenticatedSessionController::class, 'destroyapi']);
        Route::post('/store-device-token', [AuthenticatedSessionController::class, 'storeDeviceToken'])->name('auth.store-device-token');
        Route::get('/user/profile', [UserProfileController::class, 'show']);
        Route::post('/seller/profile', [UserProfileController::class, 'updateSeller']);
        Route::post('/user/profile', [UserProfileController::class, 'updateUser']);
        Route::post('/my/payments', [UserProfileController::class, 'updatePaymentAccounts']);
        Route::get('/my/payments', [UserProfileController::class, 'getPaymentAccounts']);
        Route::get('/seller/status', [UserController::class, 'checkStatus']);
        Route::get('/me/role', [UserController::class, 'checkProfile']);
        Route::post('/seller/application', [UserController::class, 'applyForSeller']);
        Route::get('/user/balance-stats', [UserController::class, 'getBalanceStats']);

        // Notifications
        Route::get('/notifications', [NotificationController::class, 'index']);
        Route::post('/notifications/mark-as-read', [NotificationController::class, 'markAllAsRead']);
        Route::post('/notifications/{notification}/mark-as-read', [NotificationController::class, 'markAsRead']);

        // Admin only
        Route::middleware('role:admin')->group(function (): void {
            Route::post('/admin/seller-approve', [UserController::class, 'approveSeller']);
            Route::post('/admin/seller-reject', [UserController::class, 'rejectSeller']);
            Route::get('/admin/seller-requests', [UserController::class, 'sellerRequest']);
            Route::post('/shop-categories', [ShopCategoryController::class, 'store']);
            Route::put('/shop-categories/{shopCategory}', [ShopCategoryController::class, 'update']);
            Route::delete('/shop-categories/{shopCategory}', [ShopCategoryController::class, 'destroy']);
        });

        // Vendor actions
        Route::post('/vendors/{vendor}/reviews', [UserVendorReviewController::class, 'store']);
        Route::delete('/vendors/{vendor}/reviews/{review}', [UserVendorReviewController::class, 'destroy']);
        Route::post('/vendors/{vendor}/follow', [UserVendorFollowController::class, 'follow']);
        Route::delete('/vendors/{vendor}/follow', [UserVendorFollowController::class, 'unfollow']);
        Route::get('/following', [UserVendorFollowController::class, 'following']);
        Route::get('/followers', [UserVendorFollowController::class, 'followers']);

        // Cart
        Route::post('/cart', [CartController::class, 'addOrUpdate']);
        Route::get('/cart', [CartController::class, 'index']);
        Route::delete('/cart/{cartItem}', [CartController::class, 'destroy']);
        Route::get('/cart/summary', [CartController::class, 'summary']);

        // Orders
        Route::post('/orders', [OrderController::class, 'store']);
        Route::get('/my-orders', [OrderController::class, 'MyOrders']);
        Route::get('/my-orders/search', [OrderController::class, 'searchOrderById']);
        Route::get('/my-order/{order}', [OrderController::class, 'myOrderDetail']);
        Route::get('/my-store-orders', [OrderController::class, 'MyStoreOrders']);
        Route::get('/seller/orders', [OrderController::class, 'sellerOrders']);
        Route::get('/seller/orders/{order}', [OrderController::class, 'sellerOrderDetail']);
        Route::patch('/seller/orders/{order}/accept', [OrderController::class, 'acceptSellerOrder']);
        Route::patch('/seller/orders/{order}/reject', [OrderController::class, 'rejectSellerOrder']);

        // Addresses
        Route::get('/addresses', [AddressController::class, 'index']);
        Route::post('/addresses', [AddressController::class, 'store']);
        Route::put('/addresses/{address}', [AddressController::class, 'update']);
        Route::post('/addresses/{address}/set-default', [AddressController::class, 'setDefault']);
        Route::get('/addresses/default', [AddressController::class, 'getDefault']);

        // Size templates
        Route::get('/size-templates', [SizeTemplateController::class, 'index']);
        Route::post('/size-templates', [SizeTemplateController::class, 'store']);
        Route::delete('/size-templates/{sizeTemplate}', [SizeTemplateController::class, 'destroy']);
        Route::post('/size-templates/{sizeTemplate}/items', [SizeTemplateController::class, 'storeItem']);
        Route::put('/size-templates/{sizeTemplate}/items/{sizeTemplateItem}', [SizeTemplateController::class, 'updateItem'])->name('size-template.update-size');
        Route::delete('/size-templates/{sizeTemplate}/items/{sizeTemplateItem}', [SizeTemplateController::class, 'destroyItem']);

        // Short video interactions
        Route::post('/short-videos/{id}/comment', [ShortsInteractionController::class, 'comment']);
        Route::delete('/short-videos/comment/{id}', [ShortsInteractionController::class, 'deleteComment']);
        Route::post('/short-videos/{id}/like', [ShortsInteractionController::class, 'toggleLike']);
        Route::post('/short-videos/{id}/save', [ShortsInteractionController::class, 'toggleSave']);
        Route::get('/short-videos/saved', [ShortsInteractionController::class, 'getSavedShorts']);

        // Vendor-only routes
        Route::middleware('role:vendor')->group(function (): void {
            // Products
            Route::post('/my/products', [VendorProductController::class, 'store']);
            Route::get('/my/products', [VendorProductController::class, 'getAllMyProducts']);
            Route::get('/my/products/{id}', [VendorProductController::class, 'getSingleProduct']);
            Route::post('/my/products/{id}', [VendorProductController::class, 'update']);
            Route::post('/my/products/{id}/soft-delete', [VendorProductController::class, 'destroy']);
            Route::delete('/my/products/{id}/images/{img}', [VendorProductController::class, 'deleteImage']);
            Route::post('/my/products/{id}/inactive', [VendorProductController::class, 'inactive']);
            Route::post('/my/products/{id}/active', [VendorProductController::class, 'active']);
            Route::get('/search/my-product', [VendorProductController::class, 'search']);

            // Vendor short videos
            Route::prefix('my/short-videos')->group(function (): void {
                Route::get('/', [VendorShortVideoController::class, 'indexApi']);
                Route::post('/', [VendorShortVideoController::class, 'storeApi']);
                Route::post('/{id}', [VendorShortVideoController::class, 'updateApi']);
                Route::delete('/{id}', [VendorShortVideoController::class, 'destroyApi']);
            });

            Route::post('/withdraw', [TransactionController::class, 'withdraw']);
        });

        // Livestreams (authenticated)
        Route::post('livestreams', [LivestreamController::class, 'store'])->name('livestreams.store');
        Route::match(['put', 'patch'], 'livestreams/{livestream}', [LivestreamController::class, 'update'])->name('livestreams.update');
        Route::get('livestreams/{livestream}/publisher-token', GetLivestreamPublisherTokenController::class)->name('livestreams.get-publisher-token');
        Route::post('livestreams/{ls}/products', [LivestreamProductController::class, 'store'])->name('livestream-products.store');
        Route::delete('livestreams/{ls}/products', [LivestreamProductController::class, 'destroy'])->name('livestream-products.destroy');
        Route::get('my-livestreams', [LivestreamController::class, 'myLivestreams'])->name('livestreams.my');
        Route::post('livestreams/{id}/like', [LivestreamController::class, 'like']);
        Route::post('livestreams/{id}/save', [LivestreamController::class, 'save']);

        Route::prefix('lives')->group(function (): void {
            Route::get('liked', [LivestreamController::class, 'getLikedLivestreams']);
            Route::get('saved', [LivestreamController::class, 'getSavedLivestreams']);
            Route::get('{livestream}/likes-count', [LivestreamController::class, 'getLikesCount'])->name('livestreams.likes-count');
        });

        Route::prefix('livestreams/{livestreamId}/comments')->group(function (): void {
            Route::get('/', [LivestreamCommentController::class, 'index'])->withoutMiddleware('auth:sanctum');
            Route::post('/', [LivestreamCommentController::class, 'store']);
            Route::put('{commentId}', [LivestreamCommentController::class, 'update']);
            Route::delete('{commentId}', [LivestreamCommentController::class, 'destroy']);
        });
    });
});
