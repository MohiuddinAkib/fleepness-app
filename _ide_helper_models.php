<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property string|null $label
 * @property string|null $formatted_address
 * @property string|null $address_line_1
 * @property string|null $address_line_2
 * @property string|null $area
 * @property string|null $city
 * @property string|null $postal_code
 * @property numeric|null $latitude
 * @property numeric|null $longitude
 * @property bool $is_default
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Address newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Address newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Address query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Address whereAddressLine1($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Address whereAddressLine2($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Address whereArea($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Address whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Address whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Address whereFormattedAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Address whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Address whereIsDefault($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Address whereLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Address whereLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Address whereLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Address wherePostalCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Address whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Address whereUserId($value)
 */
	class Address extends \Eloquent {}
}

namespace App\Models{
/**
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Bill newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Bill newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Bill query()
 */
	class Bill extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property int $product_id
 * @property int|null $product_variant_id
 * @property int $quantity
 * @property int $is_selected
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Product $product
 * @property-read \App\Models\ProductSize|null $size
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\CartItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\CartItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\CartItem query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\CartItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\CartItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\CartItem whereIsSelected($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\CartItem whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\CartItem whereProductVariantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\CartItem whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\CartItem whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\CartItem whereUserId($value)
 */
	class CartItem extends \Eloquent {}
}

namespace App\Models{
/**
 * @property-read string|null $profile_img
 * @property-read string|null $cover_img
 * @property int $id
 * @property int|null $parent_id
 * @property string $name
 * @property string $slug
 * @property string|null $description
 * @property string|null $store_title
 * @property string|null $profile_image_path
 * @property string|null $cover_image_path
 * @property string $status
 * @property int $sort_order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Category> $children
 * @property-read int|null $children_count
 * @property-read bool|null $children_exists
 * @property-read \App\Models\Category|null $grandParent
 * @property-read \App\Models\Category|null $parent
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Slider> $sliders
 * @property-read int|null $sliders_count
 * @property-read bool|null $sliders_exists
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Category newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Category newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Category query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Category whereCoverImagePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Category whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Category whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Category whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Category whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Category whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Category whereProfileImagePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Category whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Category whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Category whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Category whereStoreTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Category whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Category withGrandParentId()
 */
	class Category extends \Eloquent {}
}

namespace App\Models{
/**
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\DeliveryModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\DeliveryModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\DeliveryModel query()
 */
	class DeliveryModel extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property string $token
 * @property string|null $platform
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\DeviceToken newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\DeviceToken newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\DeviceToken query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\DeviceToken whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\DeviceToken whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\DeviceToken wherePlatform($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\DeviceToken whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\DeviceToken whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\DeviceToken whereUserId($value)
 */
	class DeviceToken extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property numeric $vat
 * @property numeric $platform_fee
 * @property numeric $commission
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Fee newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Fee newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Fee query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Fee whereCommission($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Fee whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Fee whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Fee wherePlatformFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Fee whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Fee whereVat($value)
 */
	class Fee extends \Eloquent {}
}

namespace App\Models{
/**
 * @property-read \App\Models\User|null $follower
 * @property-read \App\Models\User|null $vendor
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Follower newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Follower newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Follower query()
 */
	class Follower extends \Eloquent {}
}

namespace App\Models{
/**
 * @property-read string $room_name
 * @property-read list<array{filenamePrefix:string,imageCount:int,startedAt:int,endedAt:int}>|null $thumbnails
 * @property-read list<array{filename:string,startedAt:int,endedAt:int,duration:int,size:int,location:string}>|null $recordings
 * @property-read list<array{playlistName:string,livePlaylistName:string,duration:int,size:int,playlistLocation:string,livePlaylistLocation:string,segmentCount:int,startedAt:int,endedAt:int}>|null $short_videos
 * @property int $id
 * @property int $vendor_profile_id
 * @property string $title
 * @property string|null $description
 * @property string $room_id
 * @property string|null $egress_id
 * @property string|null $egress_metadata
 * @property \App\Constants\LivestreamStatuses $status
 * @property int $viewer_count
 * @property string|null $scheduled_at
 * @property \Illuminate\Support\Carbon|null $started_at
 * @property \Illuminate\Support\Carbon|null $ended_at
 * @property int|null $total_duration
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\LivestreamComment> $comments
 * @property-read int|null $comments_count
 * @property-read bool|null $comments_exists
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\LivestreamLike> $likes
 * @property-read int|null $likes_count
 * @property-read bool|null $likes_exists
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\LivestreamProduct> $livestreamProducts
 * @property-read int|null $livestream_products_count
 * @property-read bool|null $livestream_products_exists
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read bool|null $media_exists
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read bool|null $notifications_exists
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $participants
 * @property-read int|null $participants_count
 * @property-read bool|null $participants_exists
 * @property-read \App\Models\LivestreamProduct|null $pivot
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Product> $products
 * @property-read int|null $products_count
 * @property-read bool|null $products_exists
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\LivestreamSave> $saves
 * @property-read int|null $saves_count
 * @property-read bool|null $saves_exists
 * @property-read \App\Models\User|null $vendor
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Livestream newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Livestream newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Livestream query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Livestream whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Livestream whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Livestream whereEgressId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Livestream whereEgressMetadata($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Livestream whereEndedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Livestream whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Livestream whereRoomId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Livestream whereScheduledAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Livestream whereStartedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Livestream whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Livestream whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Livestream whereTotalDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Livestream whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Livestream whereVendorProfileId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Livestream whereViewerCount($value)
 */
	class Livestream extends \Eloquent implements \App\Support\Notification\Contracts\FcmNotifiableByTopic, \Spatie\MediaLibrary\HasMedia {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $livestream_id
 * @property int $user_id
 * @property string $comment
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Livestream $livestream
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\LivestreamComment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\LivestreamComment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\LivestreamComment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\LivestreamComment whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\LivestreamComment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\LivestreamComment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\LivestreamComment whereLivestreamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\LivestreamComment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\LivestreamComment whereUserId($value)
 */
	class LivestreamComment extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $livestream_id
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Livestream $livestream
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\LivestreamLike newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\LivestreamLike newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\LivestreamLike query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\LivestreamLike whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\LivestreamLike whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\LivestreamLike whereLivestreamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\LivestreamLike whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\LivestreamLike whereUserId($value)
 */
	class LivestreamLike extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\LivestreamProduct
 *
 * @property int $product_id
 * @property int $livestream_id
 * @method static \Illuminate\Database\Eloquent\Builder|LivestreamProduct newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LivestreamProduct newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LivestreamProduct query()
 * @method static \Illuminate\Database\Eloquent\Builder|LivestreamProduct whereLivestreamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LivestreamProduct whereProductId($value)
 * @mixin \Eloquent
 */
	class LivestreamProduct extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $livestream_id
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Livestream $livestream
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\LivestreamSave newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\LivestreamSave newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\LivestreamSave query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\LivestreamSave whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\LivestreamSave whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\LivestreamSave whereLivestreamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\LivestreamSave whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\LivestreamSave whereUserId($value)
 */
	class LivestreamSave extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $type
 * @property string $notifiable_type
 * @property int $notifiable_id
 * @property string $data
 * @property string|null $read_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Notification newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Notification newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Notification query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Notification whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Notification whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Notification whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Notification whereNotifiableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Notification whereNotifiableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Notification whereReadAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Notification whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Notification whereUpdatedAt($value)
 */
	class Notification extends \Eloquent {}
}

namespace App\Models{
/**
 * @property float|null $platform_fee
 * @property float|null $delivery_fee
 * @property int $id
 * @property int $user_id
 * @property int|null $delivery_option_id
 * @property string $order_number
 * @property int $is_multi_vendor
 * @property int $vendor_count
 * @property numeric $product_total
 * @property float $vat
 * @property float $commission
 * @property float $grand_total
 * @property float $balance
 * @property int $is_completed
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\DeliveryModel|null $deliveryModel
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SellerOrder> $sellerOrders
 * @property-read int|null $seller_orders_count
 * @property-read bool|null $seller_orders_exists
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Order newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Order newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Order query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Order whereBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Order whereCommission($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Order whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Order whereDeliveryFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Order whereDeliveryOptionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Order whereGrandTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Order whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Order whereIsCompleted($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Order whereIsMultiVendor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Order whereOrderNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Order wherePlatformFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Order whereProductTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Order whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Order whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Order whereVat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Order whereVendorCount($value)
 */
	class Order extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string|null $icon_path
 * @property int $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\PaymentMethod newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\PaymentMethod newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\PaymentMethod query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\PaymentMethod whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\PaymentMethod whereIconPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\PaymentMethod whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\PaymentMethod whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\PaymentMethod whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\PaymentMethod whereUpdatedAt($value)
 */
	class PaymentMethod extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $image
 * @property list<int> $tags
 * @property int $id
 * @property int $vendor_profile_id
 * @property int|null $category_id
 * @property int|null $size_template_id
 * @property string $name
 * @property string $slug
 * @property string|null $sku
 * @property int $quantity
 * @property int $order_count
 * @property float $selling_price
 * @property float|null $discount_price
 * @property string|null $short_description
 * @property string|null $description
 * @property string $status
 * @property int $is_approved
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Category|null $category
 * @property-read \App\Models\ProductImage|null $firstImage
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ProductImage> $images
 * @property-read int|null $images_count
 * @property-read bool|null $images_exists
 * @property-read \App\Models\ProductImage|null $imagesProduct
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Livestream> $livestreams
 * @property-read int|null $livestreams_count
 * @property-read bool|null $livestreams_exists
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ProductReview> $reviews
 * @property-read int|null $reviews_count
 * @property-read bool|null $reviews_exists
 * @property-read \App\Models\SizeTemplate|null $sizeTemplate
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ProductSize> $sizes
 * @property-read int|null $sizes_count
 * @property-read bool|null $sizes_exists
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Stock> $stocks
 * @property-read int|null $stocks_count
 * @property-read bool|null $stocks_exists
 * @property-read \App\Models\Category|null $tag
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Product newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Product newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Product query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Product whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Product whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Product whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Product whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Product whereDiscountPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Product whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Product whereIsApproved($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Product whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Product whereOrderCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Product whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Product whereSellingPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Product whereShortDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Product whereSizeTemplateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Product whereSku($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Product whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Product whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Product whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Product whereVendorProfileId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Product withTag()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Product withTagId()
 */
	class Product extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $product_id
 * @property string $path
 * @property int $sort_order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Product $product
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ProductImage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ProductImage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ProductImage query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ProductImage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ProductImage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ProductImage wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ProductImage whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ProductImage whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ProductImage whereUpdatedAt($value)
 */
	class ProductImage extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property int $product_id
 * @property int $rating
 * @property string|null $review
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Product $product
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ProductReview newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ProductReview newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ProductReview query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ProductReview whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ProductReview whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ProductReview whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ProductReview whereRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ProductReview whereReview($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ProductReview whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ProductReview whereUserId($value)
 */
	class ProductReview extends \Eloquent {}
}

namespace App\Models{
/**
 * @property-read \App\Models\Product|null $product
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ProductSize newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ProductSize newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ProductSize query()
 */
	class ProductSize extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int|null $category_id
 * @property string $name
 * @property string|null $title
 * @property string $type
 * @property string|null $description
 * @property string|null $placement_type
 * @property string|null $background_image_path
 * @property string|null $banner_image_path
 * @property int $sort_order
 * @property int $category_sort_order
 * @property int $is_visible
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $background_image
 * @property-read mixed $banner_image
 * @property-read \App\Models\Category|null $category
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SectionItem> $items
 * @property-read int|null $items_count
 * @property-read bool|null $items_exists
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Section newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Section newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Section query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Section whereBackgroundImagePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Section whereBannerImagePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Section whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Section whereCategorySortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Section whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Section whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Section whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Section whereIsVisible($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Section whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Section wherePlacementType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Section whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Section whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Section whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Section whereUpdatedAt($value)
 */
	class Section extends \Eloquent {}
}

namespace App\Models{
/**
 * @property-read ?string $image
 * @property int $id
 * @property int $section_id
 * @property int|null $tag_id
 * @property string|null $image_path
 * @property string|null $title
 * @property string|null $description
 * @property int $sort_order
 * @property int $is_visible
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Section $section
 * @property-read \App\Models\Category|null $tag
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\SectionItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\SectionItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\SectionItem query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\SectionItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\SectionItem whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\SectionItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\SectionItem whereImagePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\SectionItem whereIsVisible($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\SectionItem whereSectionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\SectionItem whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\SectionItem whereTagId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\SectionItem whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\SectionItem whereUpdatedAt($value)
 */
	class SectionItem extends \Eloquent {}
}

namespace App\Models{
/**
 * @property-read User $seller
 * @property \App\Enums\SellerOrderStatus $status
 * @property-read \App\Models\User|null $customer
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SellerOrderItem> $items
 * @property-read int|null $items_count
 * @property-read bool|null $items_exists
 * @property-read \App\Models\Order|null $order
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\SellerOrder newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\SellerOrder newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\SellerOrder query()
 */
	class SellerOrder extends \Eloquent {}
}

namespace App\Models{
/**
 * @property-read \App\Models\Product|null $product
 * @property-read \App\Models\SellerOrder|null $sellerOrder
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\SellerOrderItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\SellerOrderItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\SellerOrderItem query()
 */
	class SellerOrderItem extends \Eloquent {}
}

namespace App\Models{
/**
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\SellerTags newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\SellerTags newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\SellerTags query()
 */
	class SellerTags extends \Eloquent {}
}

namespace App\Models{
/**
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Setting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Setting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Setting query()
 */
	class Setting extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @property-read bool|null $users_exists
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ShopCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ShopCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ShopCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ShopCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ShopCategory whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ShopCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ShopCategory whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ShopCategory whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ShopCategory whereUpdatedAt($value)
 */
	class ShopCategory extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $vendor_profile_id
 * @property string $title
 * @property string|null $description
 * @property string $video_path
 * @property string|null $thumbnail_path
 * @property-read int|null $likes_count
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ShortsComment> $comments
 * @property-read int|null $comments_count
 * @property-read bool|null $comments_exists
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ShortsLike> $likes
 * @property-read bool|null $likes_exists
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Product> $products
 * @property-read int|null $products_count
 * @property-read bool|null $products_exists
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ShortsSave> $saves
 * @property-read int|null $saves_count
 * @property-read bool|null $saves_exists
 * @property-read mixed $video
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ShortVideo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ShortVideo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ShortVideo query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ShortVideo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ShortVideo whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ShortVideo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ShortVideo whereLikesCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ShortVideo whereThumbnailPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ShortVideo whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ShortVideo whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ShortVideo whereVendorProfileId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ShortVideo whereVideoPath($value)
 */
	class ShortVideo extends \Eloquent {}
}

namespace App\Models{
/**
 * @property-read \App\Models\ShortVideo|null $shortVideo
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ShortsComment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ShortsComment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ShortsComment query()
 */
	class ShortsComment extends \Eloquent {}
}

namespace App\Models{
/**
 * @property-read \App\Models\ShortVideo|null $shortVideo
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ShortsLike newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ShortsLike newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ShortsLike query()
 */
	class ShortsLike extends \Eloquent {}
}

namespace App\Models{
/**
 * @property-read \App\Models\Product|null $product
 * @property-read \App\Models\ShortVideo|null $shortVideo
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ShortsProduct newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ShortsProduct newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ShortsProduct query()
 */
	class ShortsProduct extends \Eloquent {}
}

namespace App\Models{
/**
 * @property-read \App\Models\ShortVideo|null $shortVideo
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ShortsSave newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ShortsSave newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ShortsSave query()
 */
	class ShortsSave extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $vendor_profile_id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SizeTemplateItem> $items
 * @property-read int|null $items_count
 * @property-read bool|null $items_exists
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\SizeTemplate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\SizeTemplate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\SizeTemplate query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\SizeTemplate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\SizeTemplate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\SizeTemplate whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\SizeTemplate whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\SizeTemplate whereVendorProfileId($value)
 */
	class SizeTemplate extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $size_template_id
 * @property string $label
 * @property string $value
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\SizeTemplate|null $template
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\SizeTemplateItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\SizeTemplateItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\SizeTemplateItem query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\SizeTemplateItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\SizeTemplateItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\SizeTemplateItem whereLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\SizeTemplateItem whereSizeTemplateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\SizeTemplateItem whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\SizeTemplateItem whereValue($value)
 */
	class SizeTemplateItem extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int|null $category_id
 * @property int|null $tag_id
 * @property string $image_path
 * @property string|null $url
 * @property int $is_active
 * @property int $sort_order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Category|null $category
 * @property-read mixed $photo
 * @property-read \App\Models\Category|null $tag
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Slider newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Slider newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Slider query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Slider whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Slider whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Slider whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Slider whereImagePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Slider whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Slider whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Slider whereTagId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Slider whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Slider whereUrl($value)
 */
	class Slider extends \Eloquent {}
}

namespace App\Models{
/**
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Stock newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Stock newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Stock query()
 */
	class Stock extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property int|null $payment_method_id
 * @property string $reference
 * @property numeric $amount
 * @property string $type
 * @property string $status
 * @property string|null $note
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\PaymentMethod|null $paymentMethod
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Transaction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Transaction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Transaction query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Transaction whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Transaction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Transaction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Transaction whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Transaction wherePaymentMethodId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Transaction whereReference($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Transaction whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Transaction whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Transaction whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Transaction whereUserId($value)
 */
	class Transaction extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string|null $email
 * @property string $phone_number
 * @property string|null $password
 * @property string|null $provider
 * @property string|null $provider_id
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \App\Enums\SellerStatus $status
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Address> $addresses
 * @property-read int|null $addresses_count
 * @property-read bool|null $addresses_exists
 * @property-read mixed $banner_image
 * @property-read mixed $cover_image
 * @property-read \App\Models\Address|null $defaultAddress
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DeviceToken> $deviceTokens
 * @property-read int|null $device_tokens_count
 * @property-read bool|null $device_tokens_exists
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\LivestreamLike> $likedLivestreams
 * @property-read int|null $liked_livestreams_count
 * @property-read bool|null $liked_livestreams_exists
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Livestream> $livestreams
 * @property-read int|null $livestreams_count
 * @property-read bool|null $livestreams_exists
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read bool|null $media_exists
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read bool|null $notifications_exists
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\UserPayment> $payments
 * @property-read int|null $payments_count
 * @property-read bool|null $payments_exists
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\VendorReview> $reviews
 * @property-read int|null $reviews_count
 * @property-read bool|null $reviews_exists
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\LivestreamSave> $savedLivestreams
 * @property-read int|null $saved_livestreams_count
 * @property-read bool|null $saved_livestreams_exists
 * @property-read \App\Models\ShopCategory|null $shopCategory
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @property-read bool|null $tokens_exists
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\User wherePhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\User whereProvider($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\User whereProviderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\User whereUpdatedAt($value)
 */
	class User extends \Eloquent implements \App\Support\Notification\Contracts\FcmBroadcastNotifiableByDevice, \App\Support\Notification\Contracts\FcmNotifiableByDevice {}
}

namespace App\Models{
/**
 * @property-read \App\Models\PaymentMethod|null $paymentMethod
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\UserPayment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\UserPayment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\UserPayment query()
 */
	class UserPayment extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $vendor_profile_id
 * @property int $user_id
 * @property int $rating
 * @property string|null $comment
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @property-read \App\Models\User|null $vendor
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\VendorReview newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\VendorReview newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\VendorReview query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\VendorReview whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\VendorReview whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\VendorReview whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\VendorReview whereRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\VendorReview whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\VendorReview whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\VendorReview whereVendorProfileId($value)
 */
	class VendorReview extends \Eloquent {}
}

namespace App\Webhooks\Livekit{
/**
 * App\Webhooks\Livekit\LivekitWebhookCall
 *
 * @method static \Illuminate\Database\Eloquent\Builder|LivekitWebhookCall newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LivekitWebhookCall newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LivekitWebhookCall query()
 * @mixin \Eloquent
 * @property int $id
 * @property string $name
 * @property string $url
 * @property array<array-key, mixed>|null $headers
 * @property array<array-key, mixed>|null $payload
 * @property array<array-key, mixed>|null $exception
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Webhooks\Livekit\LivekitWebhookCall whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Webhooks\Livekit\LivekitWebhookCall whereException($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Webhooks\Livekit\LivekitWebhookCall whereHeaders($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Webhooks\Livekit\LivekitWebhookCall whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Webhooks\Livekit\LivekitWebhookCall whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Webhooks\Livekit\LivekitWebhookCall wherePayload($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Webhooks\Livekit\LivekitWebhookCall whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Webhooks\Livekit\LivekitWebhookCall whereUrl($value)
 */
	class LivekitWebhookCall extends \Eloquent {}
}

