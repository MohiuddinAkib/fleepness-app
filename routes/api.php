<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Me\CartController;
use App\Http\Controllers\Me\OrderController;
use App\Http\Controllers\Me\FollowController;
use App\Http\Controllers\Me\AddressController;
use App\Http\Controllers\Me\ProfileController;
use App\Http\Controllers\Public\TagController;
use App\Http\Controllers\Auth\OTPAuthController;
use App\Http\Controllers\Auth\SessionController;
use App\Http\Controllers\Public\SearchController;
use App\Http\Controllers\Public\VendorController;
use App\Http\Controllers\Me\TransactionController;
use App\Http\Controllers\Me\VendorOrderController;
use App\Http\Controllers\Public\ProductController;
use App\Http\Controllers\Public\SectionController;
use App\Http\Controllers\Auth\SocialAuthController;
use App\Http\Controllers\Me\SizeTemplateController;
use App\Http\Controllers\Public\CategoryController;
use App\Http\Controllers\Auth\DeviceTokenController;
use App\Http\Controllers\Me\VendorProfileController;
use App\Http\Controllers\Me\PaymentAccountController;
use App\Http\Controllers\Public\LivestreamController;
use App\Http\Controllers\Public\ShortVideoController;
use App\Http\Controllers\Public\ShopCategoryController;
use App\Http\Controllers\Public\PaymentMethodController;
use App\Http\Controllers\Public\DeliveryOptionController;
use App\Http\Controllers\Me\ProductController as MeProductController;
use App\Http\Controllers\Me\LivestreamController as MeLivestreamController;
use App\Http\Controllers\Me\ShortVideoController as MeShortVideoController;

// ─── Auth ────────────────────────────────────────────────────────────────────
Route::prefix('auth')->group(function (): void {
    Route::post('register', [OTPAuthController::class, 'register']);
    Route::post('verify-otp', [OTPAuthController::class, 'verifyOtp']);
    Route::post('resend-otp', [OTPAuthController::class, 'resendOtp']);
    Route::post('login', [OTPAuthController::class, 'login']);

    Route::prefix('social')->group(function (): void {
        Route::get('{provider}', [SocialAuthController::class, 'redirect']);
        Route::get('{provider}/callback', [
            SocialAuthController::class,
            'callback',
        ]);
    });

    Route::middleware(['auth:sanctum'])->group(function (): void {
        Route::post('logout', [SessionController::class, 'destroy']);
        Route::post('device-tokens', [DeviceTokenController::class, 'store']);
        Route::delete('device-tokens/{deviceToken}', [
            DeviceTokenController::class,
            'destroy',
        ]);
    });
});

// ─── Me (authenticated) ───────────────────────────────────────────────────────
Route::middleware(['auth:sanctum'])
    ->prefix('me')
    ->group(function (): void {
        // Profile
        Route::get('/', [ProfileController::class, 'show']);
        Route::patch('/', [ProfileController::class, 'update']);

        // Vendor profile
        Route::prefix('vendor')->group(function (): void {
            Route::get('/', [VendorProfileController::class, 'show']);
            Route::patch('/', [VendorProfileController::class, 'update']);
        });
        Route::get('balance', [VendorProfileController::class, 'balance']);

        // Addresses
        Route::prefix('addresses')->group(function (): void {
            Route::get('/', [AddressController::class, 'index']);
            Route::post('/', [AddressController::class, 'store']);
            Route::patch('{address}', [AddressController::class, 'update']);
            Route::delete('{address}', [AddressController::class, 'destroy']);
            Route::post('{address}/default', [
                AddressController::class,
                'setDefault',
            ]);
        });

        // Payment accounts
        Route::prefix('payment-accounts')->group(function (): void {
            Route::get('/', [PaymentAccountController::class, 'index']);
            Route::post('/', [PaymentAccountController::class, 'store']);
            Route::delete('{paymentAccount}', [
                PaymentAccountController::class,
                'destroy',
            ]);
        });

        // Products (vendor CRUD)
        Route::prefix('products')->group(function (): void {
            Route::get('/', [MeProductController::class, 'index']);
            Route::post('/', [MeProductController::class, 'store']);
            Route::get('{product}', [MeProductController::class, 'show']);
            Route::patch('{product}', [MeProductController::class, 'update']);
            Route::delete('{product}', [MeProductController::class, 'destroy']);
            Route::post('{product}/toggle-status', [
                MeProductController::class,
                'toggleStatus',
            ]);
            Route::delete('{product}/images/{mediaId}', [
                MeProductController::class,
                'destroyImage',
            ]);
        });

        // Size templates
        Route::prefix('size-templates')->group(function (): void {
            Route::get('/', [SizeTemplateController::class, 'index']);
            Route::post('/', [SizeTemplateController::class, 'store']);
            Route::delete('{sizeTemplate}', [
                SizeTemplateController::class,
                'destroy',
            ]);
            Route::post('{sizeTemplate}/items', [
                SizeTemplateController::class,
                'storeItem',
            ]);
            Route::patch('{sizeTemplate}/items/{sizeTemplateItem}', [
                SizeTemplateController::class,
                'updateItem',
            ]);
            Route::delete('{sizeTemplate}/items/{sizeTemplateItem}', [
                SizeTemplateController::class,
                'destroyItem',
            ]);
        });

        // Livestreams (vendor CRUD)
        Route::prefix('livestreams')->group(function (): void {
            Route::get('/', [MeLivestreamController::class, 'index']);
            Route::post('/', [MeLivestreamController::class, 'store']);
            Route::patch('{livestream}', [
                MeLivestreamController::class,
                'update',
            ]);
            Route::delete('{livestream}', [
                MeLivestreamController::class,
                'destroy',
            ]);
            Route::get('{livestream}/publisher-token', [
                MeLivestreamController::class,
                'publisherToken',
            ]);
            Route::post('{livestream}/products', [
                MeLivestreamController::class,
                'attachProduct',
            ]);
            Route::delete('{livestream}/products/{product}', [
                MeLivestreamController::class,
                'detachProduct',
            ]);
        });

        // Short videos (vendor CRUD)
        Route::prefix('short-videos')->group(function (): void {
            Route::get('/', [MeShortVideoController::class, 'index']);
            Route::post('/', [MeShortVideoController::class, 'store']);
            Route::patch('{shortVideo}', [
                MeShortVideoController::class,
                'update',
            ]);
            Route::delete('{shortVideo}', [
                MeShortVideoController::class,
                'destroy',
            ]);
        });

        // Orders (customer)
        Route::prefix('orders')->group(function (): void {
            Route::get('/', [OrderController::class, 'index']);
            Route::get('{order}', [OrderController::class, 'show']);
        });

        // Vendor orders
        Route::prefix('vendor-orders')->group(function (): void {
            Route::get('/', [VendorOrderController::class, 'index']);
            Route::get('{vendorOrder}', [VendorOrderController::class, 'show']);
            Route::patch('{vendorOrder}/accept', [
                VendorOrderController::class,
                'accept',
            ]);
            Route::patch('{vendorOrder}/reject', [
                VendorOrderController::class,
                'reject',
            ]);
        });

        // Transactions & withdrawals
        Route::get('transactions', [TransactionController::class, 'index']);
        Route::post('withdrawals', [TransactionController::class, 'store']);

        // Following
        Route::get('following', [FollowController::class, 'following']);
    });

// ─── Vendor application ───────────────────────────────────────────────────────
Route::middleware(['auth:sanctum'])
    ->prefix('vendor-application')
    ->group(function (): void {
        Route::post('/', [VendorProfileController::class, 'apply']);
        Route::get('status', [
            VendorProfileController::class,
            'applicationStatus',
        ]);
    });

// ─── Cart ─────────────────────────────────────────────────────────────────────
Route::middleware(['auth:sanctum'])
    ->prefix('cart')
    ->group(function (): void {
        Route::get('/', [CartController::class, 'index']);
        Route::get('summary', [CartController::class, 'summary']);
        Route::post('items', [CartController::class, 'store']);
        Route::patch('items/{cartItem}', [CartController::class, 'update']);
        Route::delete('items/{cartItem}', [CartController::class, 'destroy']);
    });

// ─── Orders ───────────────────────────────────────────────────────────────────
Route::middleware(['auth:sanctum'])->post('orders', [
    OrderController::class,
    'store',
]);

// ─── Public: Products ─────────────────────────────────────────────────────────
Route::prefix('products')->group(function (): void {
    Route::get('/', [ProductController::class, 'index']);
    Route::get('{product}', [ProductController::class, 'show']);
    Route::get('{product}/similar', [ProductController::class, 'similar']);
    Route::get('{product}/reviews', [ProductController::class, 'reviews']);
    Route::middleware(['auth:sanctum'])->group(function (): void {
        Route::post('{product}/reviews', [
            ProductController::class,
            'storeReview',
        ]);
        Route::delete('{product}/reviews/{review}', [
            ProductController::class,
            'destroyReview',
        ]);
    });
});

// ─── Public: Categories ───────────────────────────────────────────────────────
Route::prefix('categories')->group(function (): void {
    Route::get('/', [CategoryController::class, 'index']);
    Route::get('{category}', [CategoryController::class, 'show']);
    Route::get('{category}/products', [CategoryController::class, 'products']);
});

// ─── Public: Tags ─────────────────────────────────────────────────────────────
Route::prefix('tags')->group(function (): void {
    Route::get('/', [TagController::class, 'index']);
    Route::get('{tag}/products', [TagController::class, 'products']);
});

// ─── Public: Vendors ──────────────────────────────────────────────────────────
Route::prefix('vendors')->group(function (): void {
    Route::get('/', [VendorController::class, 'index']);
    Route::get('{vendorProfile}', [VendorController::class, 'show']);
    Route::get('{vendorProfile}/products', [
        VendorController::class,
        'products',
    ]);
    Route::get('{vendorProfile}/short-videos', [
        VendorController::class,
        'shortVideos',
    ]);
    Route::get('{vendorProfile}/reviews', [VendorController::class, 'reviews']);
    Route::middleware(['auth:sanctum'])->group(function (): void {
        Route::post('{vendorProfile}/follow', [
            VendorController::class,
            'follow',
        ]);
        Route::delete('{vendorProfile}/follow', [
            VendorController::class,
            'unfollow',
        ]);
        Route::post('{vendorProfile}/reviews', [
            VendorController::class,
            'storeReview',
        ]);
        Route::delete('{vendorProfile}/reviews/{review}', [
            VendorController::class,
            'destroyReview',
        ]);
    });
});

// ─── Public: Short videos ─────────────────────────────────────────────────────
Route::prefix('short-videos')->group(function (): void {
    Route::get('/', [ShortVideoController::class, 'index']);
    Route::get('{shortVideo}', [ShortVideoController::class, 'show']);
    Route::get('{shortVideo}/products', [
        ShortVideoController::class,
        'products',
    ]);
    Route::get('{shortVideo}/comments', [
        ShortVideoController::class,
        'comments',
    ]);
    Route::middleware(['auth:sanctum'])->group(function (): void {
        Route::post('{shortVideo}/comments', [
            ShortVideoController::class,
            'storeComment',
        ]);
        Route::delete('{shortVideo}/comments/{comment}', [
            ShortVideoController::class,
            'destroyComment',
        ]);
        Route::post('{shortVideo}/like', [ShortVideoController::class, 'like']);
        Route::post('{shortVideo}/save', [ShortVideoController::class, 'save']);
    });
});

// ─── Public: Livestreams ──────────────────────────────────────────────────────
Route::prefix('livestreams')->group(function (): void {
    Route::get('/', [LivestreamController::class, 'index']);
    Route::get('{livestream}', [LivestreamController::class, 'show']);
    Route::get('{livestream}/products', [
        LivestreamController::class,
        'products',
    ]);
    Route::get('{livestream}/comments', [
        LivestreamController::class,
        'comments',
    ]);
    Route::middleware(['auth:sanctum'])->group(function (): void {
        Route::post('{livestream}/comments', [
            LivestreamController::class,
            'storeComment',
        ]);
        Route::delete('{livestream}/comments/{comment}', [
            LivestreamController::class,
            'destroyComment',
        ]);
        Route::post('{livestream}/like', [LivestreamController::class, 'like']);
        Route::post('{livestream}/save', [LivestreamController::class, 'save']);
    });
    Route::get('{livestream}/subscriber-token', [
        LivestreamController::class,
        'subscriberToken',
    ]);
});

// ─── Public: Misc ─────────────────────────────────────────────────────────────
Route::get('sections', [SectionController::class, 'index']);
Route::get('sliders', [SectionController::class, 'sliders']);
Route::get('delivery-options', [DeliveryOptionController::class, 'index']);
Route::get('shop-categories', [ShopCategoryController::class, 'index']);
Route::get('payment-methods', [PaymentMethodController::class, 'index']);
Route::get('search', [SearchController::class, 'index']);
