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
 * @property numeric|null $latitude
 * @property numeric|null $longitude
 * @property string|null $formatted_address
 * @property string|null $city
 * @property string|null $label
 * @property string|null $postal_code
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $address_text
 * @property string|null $address_line_1
 * @property string|null $address_line_2
 * @property string|null $area
 * @property bool $is_default
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Address newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Address newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Address query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Address whereAddressLine1($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Address whereAddressLine2($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Address whereAddressText($value)
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
 * @property int $id
 * @property int $user_id
 * @property numeric $amount
 * @property string|null $transaction_id
 * @property string|null $payment_media
 * @property string|null $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Bill newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Bill newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Bill query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Bill whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Bill whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Bill whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Bill wherePaymentMedia($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Bill whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Bill whereTransactionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Bill whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Bill whereUserId($value)
 */
	class Bill extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property int $product_id
 * @property int $quantity
 * @property bool $selected
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $size_id
 * @property-read \App\Models\Product $product
 * @property-read \App\Models\ProductSize|null $size
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\CartItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\CartItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\CartItem query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\CartItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\CartItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\CartItem whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\CartItem whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\CartItem whereSelected($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\CartItem whereSizeId($value)
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
 * @property string $name
 * @property string|null $store_title
 * @property string $slug
 * @property string|null $description
 * @property string $status
 * @property int|null $order
 * @property string|null $image
 * @property string|null $mark
 * @property int|null $parent_id
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Category whereCoverImg($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Category whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Category whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Category whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Category whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Category whereMark($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Category whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Category whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Category whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Category whereProfileImg($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Category whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Category whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Category whereStoreTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Category whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Category withGrandParentId()
 */
	class Category extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property int $minutes
 * @property numeric $fee
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\DeliveryModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\DeliveryModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\DeliveryModel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\DeliveryModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\DeliveryModel whereFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\DeliveryModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\DeliveryModel whereMinutes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\DeliveryModel whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\DeliveryModel whereUpdatedAt($value)
 */
	class DeliveryModel extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property string $token
 * @property array<array-key, mixed>|null $meta
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\DeviceToken newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\DeviceToken newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\DeviceToken query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\DeviceToken whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\DeviceToken whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\DeviceToken whereMeta($value)
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
 * @property int $id
 * @property int $follower_id
 * @property int $vendor_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $follower
 * @property-read \App\Models\User $vendor
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Follower newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Follower newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Follower query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Follower whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Follower whereFollowerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Follower whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Follower whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Follower whereVendorId($value)
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
 * @property string $title
 * @property int $vendor_id
 * @property string|null $total_duration
 * @property \Illuminate\Support\Carbon|null $scheduled_time
 * @property \Illuminate\Support\Carbon|null $started_at
 * @property \Illuminate\Support\Carbon|null $ended_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $egress_id
 * @property \App\Constants\LivestreamStatuses $status
 * @property string|null $room_id
 * @property int $total_participants
 * @property array<array-key, mixed>|null $egress_data
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Livestream whereEgressData($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Livestream whereEgressId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Livestream whereEndedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Livestream whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Livestream whereRoomId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Livestream whereScheduledTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Livestream whereStartedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Livestream whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Livestream whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Livestream whereTotalDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Livestream whereTotalParticipants($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Livestream whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Livestream whereVendorId($value)
 */
	class Livestream extends \Eloquent implements \App\Support\Notification\Contracts\FcmNotifiableByTopic, \Spatie\MediaLibrary\HasMedia {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property int $livestream_id
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
 * @property int $user_id
 * @property int $livestream_id
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
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\LivestreamProduct whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\LivestreamProduct whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\LivestreamProduct whereUpdatedAt($value)
 */
	class LivestreamProduct extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property int $livestream_id
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
 * @property string $order_code
 * @property bool|null $is_multi_seller
 * @property int|null $total_sellers
 * @property int|null $delivery_model_id
 * @property float|null $product_cost
 * @property float|null $commission
 * @property float|null $vat
 * @property float|null $grand_total
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property bool $platform_fee_added
 * @property bool $completed_order
 * @property float $balance
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Order whereCompletedOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Order whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Order whereDeliveryFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Order whereDeliveryModelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Order whereGrandTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Order whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Order whereIsMultiSeller($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Order whereOrderCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Order wherePlatformFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Order wherePlatformFeeAdded($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Order whereProductCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Order whereTotalSellers($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Order whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Order whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Order whereVat($value)
 */
	class Order extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string|null $icon
 * @property int $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\PaymentMethod newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\PaymentMethod newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\PaymentMethod query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\PaymentMethod whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\PaymentMethod whereIcon($value)
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
 * @property int $user_id
 * @property int|null $category_id
 * @property int $size_template_id
 * @property string $name
 * @property string $slug
 * @property string $code
 * @property int|null $quantity
 * @property int|null $order_count
 * @property float|null $selling_price
 * @property float|null $discount_price
 * @property string|null $short_description
 * @property string|null $long_description
 * @property \Illuminate\Database\Eloquent\Collection<int, \App\Models\ProductReview> $reviews
 * @property string|null $time
 * @property numeric|null $discount
 * @property string|null $deleted_at
 * @property string|null $status
 * @property string $admin_approval
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
 * @property-read int|null $reviews_count
 * @property-read bool|null $reviews_exists
 * @property-read \App\Models\SizeTemplate $sizeTemplate
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Product whereAdminApproval($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Product whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Product whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Product whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Product whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Product whereDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Product whereDiscountPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Product whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Product whereLongDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Product whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Product whereOrderCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Product whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Product whereReviews($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Product whereSellingPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Product whereShortDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Product whereSizeTemplateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Product whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Product whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Product whereTags($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Product whereTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Product whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Product whereUserId($value)
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
 * @property string|null $alt_text
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Product $product
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ProductImage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ProductImage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ProductImage query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ProductImage whereAltText($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ProductImage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ProductImage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ProductImage wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ProductImage whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ProductImage whereUpdatedAt($value)
 */
	class ProductImage extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $product_id
 * @property int|null $user_id
 * @property int $rating
 * @property string|null $comment
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Product $product
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ProductReview newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ProductReview newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ProductReview query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ProductReview whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ProductReview whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ProductReview whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ProductReview whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ProductReview whereRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ProductReview whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ProductReview whereUserId($value)
 */
	class ProductReview extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $product_id
 * @property string $size_name
 * @property string $size_value
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Product|null $product
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ProductSize newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ProductSize newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ProductSize query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ProductSize whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ProductSize whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ProductSize whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ProductSize whereSizeName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ProductSize whereSizeValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ProductSize whereUpdatedAt($value)
 */
	class ProductSize extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string|null $section_name
 * @property string|null $section_type
 * @property string|null $section_title
 * @property int $category_id
 * @property int|null $index
 * @property int $visibility
 * @property string|null $background_image
 * @property string|null $banner_image
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $bio
 * @property string $placement_type
 * @property int|null $cat_index
 * @property-read \App\Models\Category $category
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SectionItem> $items
 * @property-read int|null $items_count
 * @property-read bool|null $items_exists
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Section newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Section newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Section query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Section whereBackgroundImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Section whereBannerImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Section whereBio($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Section whereCatIndex($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Section whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Section whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Section whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Section whereIndex($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Section wherePlacementType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Section whereSectionName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Section whereSectionTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Section whereSectionType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Section whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Section whereVisibility($value)
 */
	class Section extends \Eloquent {}
}

namespace App\Models{
/**
 * @property-read ?string $image
 * @property int $id
 * @property int $section_id
 * @property string|null $title
 * @property string|null $bio
 * @property string|null $tag_id
 * @property int|null $index
 * @property int $visibility
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Section $section
 * @property-read \App\Models\Category|null $tag
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\SectionItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\SectionItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\SectionItem query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\SectionItem whereBio($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\SectionItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\SectionItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\SectionItem whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\SectionItem whereIndex($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\SectionItem whereSectionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\SectionItem whereTagId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\SectionItem whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\SectionItem whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\SectionItem whereVisibility($value)
 */
	class SectionItem extends \Eloquent {}
}

namespace App\Models{
/**
 * @property-read User $seller
 * @property int $id
 * @property string $seller_order_code
 * @property int $order_id
 * @property int $seller_id
 * @property int|null $customer_id
 * @property \App\Enums\SellerOrderStatus|null $status
 * @property string|null $status_message
 * @property \Illuminate\Support\Carbon|null $delivery_start_time
 * @property \Illuminate\Support\Carbon|null $delivery_end_time
 * @property float|null $product_cost
 * @property float|null $commission
 * @property float|null $vat
 * @property float|null $delivery_fee
 * @property float|null $balance
 * @property bool|null $rider_assigned
 * @property int $is_delay
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $customer
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SellerOrderItem> $items
 * @property-read int|null $items_count
 * @property-read bool|null $items_exists
 * @property-read \App\Models\Order $order
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\SellerOrder newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\SellerOrder newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\SellerOrder query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\SellerOrder whereBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\SellerOrder whereCommission($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\SellerOrder whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\SellerOrder whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\SellerOrder whereDeliveryEndTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\SellerOrder whereDeliveryFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\SellerOrder whereDeliveryStartTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\SellerOrder whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\SellerOrder whereIsDelay($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\SellerOrder whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\SellerOrder whereProductCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\SellerOrder whereRiderAssigned($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\SellerOrder whereSellerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\SellerOrder whereSellerOrderCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\SellerOrder whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\SellerOrder whereStatusMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\SellerOrder whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\SellerOrder whereVat($value)
 */
	class SellerOrder extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $seller_order_id
 * @property int $product_id
 * @property string|null $size
 * @property int|null $quantity
 * @property float|null $total_cost
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Product $product
 * @property-read \App\Models\SellerOrder $sellerOrder
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\SellerOrderItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\SellerOrderItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\SellerOrderItem query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\SellerOrderItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\SellerOrderItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\SellerOrderItem whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\SellerOrderItem whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\SellerOrderItem whereSellerOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\SellerOrderItem whereSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\SellerOrderItem whereTotalCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\SellerOrderItem whereUpdatedAt($value)
 */
	class SellerOrderItem extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $vendor_id
 * @property array<array-key, mixed> $tags
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\SellerTags newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\SellerTags newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\SellerTags query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\SellerTags whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\SellerTags whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\SellerTags whereTags($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\SellerTags whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\SellerTags whereVendorId($value)
 */
	class SellerTags extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $title
 * @property string $logo
 * @property string $favicon
 * @property string $address
 * @property string $phone
 * @property string $email
 * @property string|null $meta_keyword
 * @property string|null $meta_description
 * @property string $footer_logo
 * @property string $footer_text
 * @property string $footer_copyright_by
 * @property string $footer_copyright_url
 * @property string $footer_bg_image
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $num_of_tag
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Setting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Setting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Setting query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Setting whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Setting whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Setting whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Setting whereFavicon($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Setting whereFooterBgImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Setting whereFooterCopyrightBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Setting whereFooterCopyrightUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Setting whereFooterLogo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Setting whereFooterText($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Setting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Setting whereLogo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Setting whereMetaDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Setting whereMetaKeyword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Setting whereNumOfTag($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Setting wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Setting whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Setting whereUpdatedAt($value)
 */
	class Setting extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string $description
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
 * @property int $user_id
 * @property string $title
 * @property string $video
 * @property string|null $alt_text
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ShortVideo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ShortVideo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ShortVideo query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ShortVideo whereAltText($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ShortVideo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ShortVideo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ShortVideo whereLikesCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ShortVideo whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ShortVideo whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ShortVideo whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ShortVideo whereVideo($value)
 */
	class ShortVideo extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property int $short_video_id
 * @property string $comment
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\ShortVideo $shortVideo
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ShortsComment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ShortsComment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ShortsComment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ShortsComment whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ShortsComment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ShortsComment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ShortsComment whereShortVideoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ShortsComment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ShortsComment whereUserId($value)
 */
	class ShortsComment extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property int $short_video_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\ShortVideo $shortVideo
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ShortsLike newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ShortsLike newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ShortsLike query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ShortsLike whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ShortsLike whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ShortsLike whereShortVideoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ShortsLike whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ShortsLike whereUserId($value)
 */
	class ShortsLike extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $short_video_id
 * @property int $product_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Product $product
 * @property-read \App\Models\ShortVideo $shortVideo
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ShortsProduct newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ShortsProduct newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ShortsProduct query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ShortsProduct whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ShortsProduct whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ShortsProduct whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ShortsProduct whereShortVideoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ShortsProduct whereUpdatedAt($value)
 */
	class ShortsProduct extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property int $short_video_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\ShortVideo $shortVideo
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ShortsSave newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ShortsSave newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ShortsSave query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ShortsSave whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ShortsSave whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ShortsSave whereShortVideoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ShortsSave whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ShortsSave whereUserId($value)
 */
	class ShortsSave extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $seller_id
 * @property string $template_name
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\SizeTemplate whereSellerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\SizeTemplate whereTemplateName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\SizeTemplate whereUpdatedAt($value)
 */
	class SizeTemplate extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $template_id
 * @property string $size_name
 * @property string $size_value
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\SizeTemplate|null $template
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\SizeTemplateItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\SizeTemplateItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\SizeTemplateItem query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\SizeTemplateItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\SizeTemplateItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\SizeTemplateItem whereSizeName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\SizeTemplateItem whereSizeValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\SizeTemplateItem whereTemplateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\SizeTemplateItem whereUpdatedAt($value)
 */
	class SizeTemplateItem extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $photo
 * @property string|null $photo_alt
 * @property string|null $title
 * @property int|null $category_id
 * @property int|null $tag_id
 * @property string|null $description
 * @property string|null $btn_name
 * @property string|null $btn_url
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Category|null $category
 * @property-read \App\Models\Category|null $tag
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Slider newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Slider newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Slider query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Slider whereBtnName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Slider whereBtnUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Slider whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Slider whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Slider whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Slider whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Slider wherePhoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Slider wherePhotoAlt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Slider whereTagId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Slider whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Slider whereUpdatedAt($value)
 */
	class Slider extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $product_id
 * @property string|null $size
 * @property int|null $quantity
 * @property int $order_qty
 * @property numeric|null $buying_price
 * @property numeric|null $selling_price
 * @property numeric|null $discount_price
 * @property string|null $photo
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Stock newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Stock newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Stock query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Stock whereBuyingPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Stock whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Stock whereDiscountPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Stock whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Stock whereOrderQty($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Stock wherePhoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Stock whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Stock whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Stock whereSellingPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Stock whereSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Stock whereUpdatedAt($value)
 */
	class Stock extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property int $payment_method_id
 * @property string|null $reference
 * @property numeric $amount
 * @property string|null $transaction_id
 * @property string|null $note
 * @property string $type
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\PaymentMethod $paymentMethod
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Transaction whereTransactionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Transaction whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Transaction whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Transaction whereUserId($value)
 */
	class Transaction extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string|null $name
 * @property string|null $shop_name
 * @property int|null $shop_category
 * @property string|null $email
 * @property string|null $phone_number
 * @property string|null $otp
 * @property string|null $otp_expires_at
 * @property string|null $banner_image
 * @property string|null $cover_image
 * @property string|null $pickup_location
 * @property string|null $description
 * @property string $role
 * @property \App\Enums\SellerStatus|null $status
 * @property int|null $order_count
 * @property numeric $total_sales
 * @property numeric $balance
 * @property numeric $withdrawn_amount
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string|null $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $provider
 * @property string|null $provider_id
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Address> $addresses
 * @property-read int|null $addresses_count
 * @property-read bool|null $addresses_exists
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\User whereBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\User whereBannerImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\User whereCoverImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\User whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\User whereOrderCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\User whereOtp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\User whereOtpExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\User wherePhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\User wherePickupLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\User whereProvider($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\User whereProviderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\User whereShopCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\User whereShopName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\User whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\User whereTotalSales($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\User whereWithdrawnAmount($value)
 */
	class User extends \Eloquent implements \App\Support\Notification\Contracts\FcmBroadcastNotifiableByDevice, \App\Support\Notification\Contracts\FcmNotifiableByDevice {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property int $payment_method_id
 * @property string $account_number
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\PaymentMethod $paymentMethod
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\UserPayment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\UserPayment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\UserPayment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\UserPayment whereAccountNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\UserPayment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\UserPayment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\UserPayment wherePaymentMethodId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\UserPayment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\UserPayment whereUserId($value)
 */
	class UserPayment extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int|null $user_id
 * @property int $vendor_id
 * @property int $rating
 * @property string|null $comment
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $user
 * @property-read \App\Models\User $vendor
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\VendorReview newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\VendorReview newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\VendorReview query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\VendorReview whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\VendorReview whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\VendorReview whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\VendorReview whereRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\VendorReview whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\VendorReview whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\VendorReview whereVendorId($value)
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

