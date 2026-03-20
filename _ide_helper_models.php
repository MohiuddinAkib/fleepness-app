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
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\AddressFactory factory($count = null, $state = [])
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
 * @property int $id
 * @property int $user_id
 * @property int $product_id
 * @property int|null $product_variant_id
 * @property int $quantity
 * @property bool $is_selected
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \App\Models\Product|null $product
 * @property-read \App\Models\User $user
 * @property-read \App\Models\ProductVariant|null $variant
 * @method static \Database\Factories\CartItemFactory factory($count = null, $state = [])
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
 * @property int $id
 * @property int|null $parent_id
 * @property string $name
 * @property string $slug
 * @property string|null $description
 * @property string|null $store_title
 * @property \App\Enums\CategoryStatus $status
 * @property int $sort_order
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read bool|null $activities_exists
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Category> $children
 * @property-read int|null $children_count
 * @property-read bool|null $children_exists
 * @property-read bool $is_active
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read bool|null $media_exists
 * @property-read \App\Models\Category|null $parent
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Product> $products
 * @property-read int|null $products_count
 * @property-read bool|null $products_exists
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Category active()
 * @method static \Database\Factories\CategoryFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Category newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Category newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Category query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Category whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Category whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Category whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Category whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Category whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Category whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Category whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Category whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Category whereStoreTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Category whereUpdatedAt($value)
 */
	class Category extends \Eloquent implements \Spatie\MediaLibrary\HasMedia {}
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
 * @property string $name
 * @property string|null $description
 * @property int $estimated_minutes
 * @property numeric $fee
 * @property bool $is_active
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read bool|null $activities_exists
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\DeliveryOption active()
 * @method static \Database\Factories\DeliveryOptionFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\DeliveryOption newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\DeliveryOption newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\DeliveryOption query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\DeliveryOption whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\DeliveryOption whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\DeliveryOption whereEstimatedMinutes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\DeliveryOption whereFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\DeliveryOption whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\DeliveryOption whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\DeliveryOption whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\DeliveryOption whereUpdatedAt($value)
 */
	class DeliveryOption extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property string $token
 * @property string|null $platform
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\DeviceTokenFactory factory($count = null, $state = [])
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
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read bool|null $activities_exists
 * @method static \Database\Factories\FeeFactory factory($count = null, $state = [])
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
 * @property int $vendor_profile_id
 * @property string $title
 * @property string|null $description
 * @property string $room_id
 * @property string|null $egress_id
 * @property array<array-key, mixed>|null $egress_metadata
 * @property \App\Enums\LivestreamStatus $status
 * @property int $viewer_count
 * @property \Carbon\CarbonImmutable|null $scheduled_at
 * @property \Carbon\CarbonImmutable|null $started_at
 * @property \Carbon\CarbonImmutable|null $ended_at
 * @property int|null $total_duration
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\LivestreamComment> $comments
 * @property-read int|null $comments_count
 * @property-read bool|null $comments_exists
 * @property-read bool $is_finished
 * @property-read bool $is_scheduled
 * @property-read bool $is_started
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\LivestreamLike> $likes
 * @property-read int|null $likes_count
 * @property-read bool|null $likes_exists
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read bool|null $media_exists
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read bool|null $notifications_exists
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Product> $products
 * @property-read int|null $products_count
 * @property-read bool|null $products_exists
 * @property-read string $recording_output_path
 * @property-read array $recordings
 * @property-read string $room_name
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\LivestreamSave> $saves
 * @property-read int|null $saves_count
 * @property-read bool|null $saves_exists
 * @property-read array $thumbnails
 * @property-read \App\Models\VendorProfile $vendorProfile
 * @method static \Database\Factories\LivestreamFactory factory($count = null, $state = [])
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
	class Livestream extends \Eloquent implements \Spatie\MediaLibrary\HasMedia {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $livestream_id
 * @property int $user_id
 * @property string $comment
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \App\Models\Livestream $livestream
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\LivestreamCommentFactory factory($count = null, $state = [])
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
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \App\Models\Livestream $livestream
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\LivestreamLikeFactory factory($count = null, $state = [])
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
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \App\Models\Livestream $livestream
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\LivestreamSaveFactory factory($count = null, $state = [])
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
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
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
 * @property int $id
 * @property int $user_id
 * @property int|null $delivery_option_id
 * @property string $order_number
 * @property bool $is_multi_vendor
 * @property int $vendor_count
 * @property numeric $product_total
 * @property numeric $delivery_fee
 * @property numeric $platform_fee
 * @property numeric $vat
 * @property numeric $commission
 * @property numeric $grand_total
 * @property numeric $balance
 * @property bool $is_completed
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read bool|null $activities_exists
 * @property-read \App\Models\DeliveryOption|null $deliveryOption
 * @property-read \App\Models\User $user
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\VendorOrder> $vendorOrders
 * @property-read int|null $vendor_orders_count
 * @property-read bool|null $vendor_orders_exists
 * @method static \Database\Factories\OrderFactory factory($count = null, $state = [])
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
 * @property bool $is_active
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read bool|null $activities_exists
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\UserPaymentAccount> $userPaymentAccounts
 * @property-read int|null $user_payment_accounts_count
 * @property-read bool|null $user_payment_accounts_exists
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\PaymentMethod active()
 * @method static \Database\Factories\PaymentMethodFactory factory($count = null, $state = [])
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
 * @property int $id
 * @property int $vendor_profile_id
 * @property int|null $category_id
 * @property int|null $size_template_id
 * @property string $name
 * @property string $slug
 * @property string|null $sku
 * @property int $quantity
 * @property int $order_count
 * @property numeric $selling_price
 * @property numeric|null $discount_price
 * @property string|null $short_description
 * @property string|null $description
 * @property \App\Enums\ProductStatus $status
 * @property bool $is_approved
 * @property \Carbon\CarbonImmutable|null $deleted_at
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read bool|null $activities_exists
 * @property-read \App\Models\Category|null $category
 * @property-read bool $is_active
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read bool|null $media_exists
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ProductReview> $reviews
 * @property-read int|null $reviews_count
 * @property-read bool|null $reviews_exists
 * @property-read \App\Models\SizeTemplate|null $sizeTemplate
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Tag> $tags
 * @property-read int|null $tags_count
 * @property-read bool|null $tags_exists
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ProductVariant> $variants
 * @property-read int|null $variants_count
 * @property-read bool|null $variants_exists
 * @property-read \App\Models\VendorProfile $vendorProfile
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Product active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Product approved()
 * @method static \Database\Factories\ProductFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Product newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Product newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Product onlyTrashed()
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Product withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Product withoutTrashed()
 */
	class Product extends \Eloquent implements \Spatie\MediaLibrary\HasMedia {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property int $product_id
 * @property int $rating
 * @property string|null $review
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \App\Models\Product|null $product
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\ProductReviewFactory factory($count = null, $state = [])
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
 * @property int $id
 * @property int $product_id
 * @property string $name
 * @property numeric $price
 * @property int $stock
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \App\Models\Product|null $product
 * @method static \Database\Factories\ProductVariantFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ProductVariant newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ProductVariant newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ProductVariant query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ProductVariant whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ProductVariant whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ProductVariant whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ProductVariant wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ProductVariant whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ProductVariant whereStock($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ProductVariant whereUpdatedAt($value)
 */
	class ProductVariant extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int|null $category_id
 * @property string $name
 * @property string|null $title
 * @property \App\Enums\SectionType $type
 * @property string|null $description
 * @property string|null $placement_type
 * @property int $sort_order
 * @property int $category_sort_order
 * @property bool $is_visible
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read bool|null $activities_exists
 * @property-read \App\Models\Category|null $category
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SectionItem> $items
 * @property-read int|null $items_count
 * @property-read bool|null $items_exists
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read bool|null $media_exists
 * @method static \Database\Factories\SectionFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Section newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Section newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Section query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Section visible()
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
	class Section extends \Eloquent implements \Spatie\MediaLibrary\HasMedia {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $section_id
 * @property int|null $tag_id
 * @property string|null $title
 * @property string|null $description
 * @property int $sort_order
 * @property bool $is_visible
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read bool|null $media_exists
 * @property-read \App\Models\Section $section
 * @property-read \App\Models\Tag|null $tag
 * @method static \Database\Factories\SectionItemFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\SectionItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\SectionItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\SectionItem query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\SectionItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\SectionItem whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\SectionItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\SectionItem whereIsVisible($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\SectionItem whereSectionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\SectionItem whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\SectionItem whereTagId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\SectionItem whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\SectionItem whereUpdatedAt($value)
 */
	class SectionItem extends \Eloquent implements \Spatie\MediaLibrary\HasMedia {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string|null $description
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read bool|null $activities_exists
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\VendorProfile> $vendorProfiles
 * @property-read int|null $vendor_profiles_count
 * @property-read bool|null $vendor_profiles_exists
 * @method static \Database\Factories\ShopCategoryFactory factory($count = null, $state = [])
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
 * @property-read int|null $likes_count
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ShortVideoComment> $comments
 * @property-read int|null $comments_count
 * @property-read bool|null $comments_exists
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ShortVideoLike> $likes
 * @property-read bool|null $likes_exists
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read bool|null $media_exists
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Product> $products
 * @property-read int|null $products_count
 * @property-read bool|null $products_exists
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ShortVideoSave> $saves
 * @property-read int|null $saves_count
 * @property-read bool|null $saves_exists
 * @property-read \App\Models\VendorProfile $vendorProfile
 * @method static \Database\Factories\ShortVideoFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ShortVideo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ShortVideo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ShortVideo query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ShortVideo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ShortVideo whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ShortVideo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ShortVideo whereLikesCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ShortVideo whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ShortVideo whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ShortVideo whereVendorProfileId($value)
 */
	class ShortVideo extends \Eloquent implements \Spatie\MediaLibrary\HasMedia {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $short_video_id
 * @property int $user_id
 * @property string $comment
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \App\Models\ShortVideo $shortVideo
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\ShortVideoCommentFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ShortVideoComment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ShortVideoComment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ShortVideoComment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ShortVideoComment whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ShortVideoComment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ShortVideoComment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ShortVideoComment whereShortVideoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ShortVideoComment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ShortVideoComment whereUserId($value)
 */
	class ShortVideoComment extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $short_video_id
 * @property int $user_id
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \App\Models\ShortVideo $shortVideo
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\ShortVideoLikeFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ShortVideoLike newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ShortVideoLike newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ShortVideoLike query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ShortVideoLike whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ShortVideoLike whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ShortVideoLike whereShortVideoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ShortVideoLike whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ShortVideoLike whereUserId($value)
 */
	class ShortVideoLike extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $short_video_id
 * @property int $user_id
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \App\Models\ShortVideo $shortVideo
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\ShortVideoSaveFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ShortVideoSave newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ShortVideoSave newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ShortVideoSave query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ShortVideoSave whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ShortVideoSave whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ShortVideoSave whereShortVideoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ShortVideoSave whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\ShortVideoSave whereUserId($value)
 */
	class ShortVideoSave extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $vendor_profile_id
 * @property string $name
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SizeTemplateItem> $items
 * @property-read int|null $items_count
 * @property-read bool|null $items_exists
 * @property-read \App\Models\VendorProfile $vendorProfile
 * @method static \Database\Factories\SizeTemplateFactory factory($count = null, $state = [])
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
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \App\Models\SizeTemplate $sizeTemplate
 * @method static \Database\Factories\SizeTemplateItemFactory factory($count = null, $state = [])
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
 * @property string|null $url
 * @property bool $is_active
 * @property int $sort_order
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read bool|null $activities_exists
 * @property-read \App\Models\Category|null $category
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read bool|null $media_exists
 * @property-read \App\Models\Tag|null $tag
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Slider active()
 * @method static \Database\Factories\SliderFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Slider newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Slider newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Slider query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Slider whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Slider whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Slider whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Slider whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Slider whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Slider whereTagId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Slider whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Slider whereUrl($value)
 */
	class Slider extends \Eloquent implements \Spatie\MediaLibrary\HasMedia {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Product> $products
 * @property-read int|null $products_count
 * @property-read bool|null $products_exists
 * @method static \Database\Factories\TagFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Tag newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Tag newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Tag query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Tag whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Tag whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Tag whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Tag whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Tag whereUpdatedAt($value)
 */
	class Tag extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property int|null $payment_method_id
 * @property string $reference
 * @property numeric $amount
 * @property \App\Enums\TransactionType $type
 * @property \App\Enums\TransactionStatus $status
 * @property string|null $note
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read bool|null $activities_exists
 * @property-read bool $is_approved
 * @property-read bool $is_pending
 * @property-read bool $is_withdrawal
 * @property-read \App\Models\PaymentMethod|null $paymentMethod
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\TransactionFactory factory($count = null, $state = [])
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
 * @property string|null $phone_number
 * @property string|null $password
 * @property string|null $provider
 * @property string|null $provider_id
 * @property \Carbon\CarbonImmutable|null $email_verified_at
 * @property string|null $remember_token
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read bool|null $activities_exists
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Address> $addresses
 * @property-read int|null $addresses_count
 * @property-read bool|null $addresses_exists
 * @property-read \App\Models\Address|null $defaultAddress
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DeviceToken> $deviceTokens
 * @property-read int|null $device_tokens_count
 * @property-read bool|null $device_tokens_exists
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\VendorProfile> $following
 * @property-read int|null $following_count
 * @property-read bool|null $following_exists
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read bool|null $media_exists
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read bool|null $notifications_exists
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Order> $orders
 * @property-read int|null $orders_count
 * @property-read bool|null $orders_exists
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\UserPaymentAccount> $paymentAccounts
 * @property-read int|null $payment_accounts_count
 * @property-read bool|null $payment_accounts_exists
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read bool|null $permissions_exists
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Role> $roles
 * @property-read int|null $roles_count
 * @property-read bool|null $roles_exists
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @property-read bool|null $tokens_exists
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Transaction> $transactions
 * @property-read int|null $transactions_count
 * @property-read bool|null $transactions_exists
 * @property-read \App\Models\VendorProfile|null $vendorProfile
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\User permission($permissions, bool $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\User role($roles, ?string $guard = null, bool $without = false)
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\User withoutPermission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\User withoutRole($roles, ?string $guard = null)
 */
	class User extends \Eloquent implements \App\Support\Notification\Contracts\FcmBroadcastNotifiableByDevice, \App\Support\Notification\Contracts\FcmNotifiableByDevice, \Filament\Models\Contracts\FilamentUser, \Spatie\MediaLibrary\HasMedia {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property int $payment_method_id
 * @property string $account_number
 * @property bool $is_primary
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \App\Models\PaymentMethod $paymentMethod
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\UserPaymentAccountFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\UserPaymentAccount newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\UserPaymentAccount newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\UserPaymentAccount query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\UserPaymentAccount whereAccountNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\UserPaymentAccount whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\UserPaymentAccount whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\UserPaymentAccount whereIsPrimary($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\UserPaymentAccount wherePaymentMethodId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\UserPaymentAccount whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\UserPaymentAccount whereUserId($value)
 */
	class UserPaymentAccount extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property int $vendor_profile_id
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \App\Models\User $user
 * @property-read \App\Models\VendorProfile $vendorProfile
 * @method static \Database\Factories\VendorFollowerFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\VendorFollower newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\VendorFollower newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\VendorFollower query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\VendorFollower whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\VendorFollower whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\VendorFollower whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\VendorFollower whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\VendorFollower whereVendorProfileId($value)
 */
	class VendorFollower extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $order_id
 * @property int $vendor_profile_id
 * @property int $customer_id
 * @property string $order_number
 * @property \App\Enums\VendorOrderStatus $status
 * @property string|null $status_note
 * @property numeric $product_total
 * @property numeric $commission
 * @property numeric $vat
 * @property numeric $delivery_fee
 * @property numeric $balance
 * @property bool $is_rider_assigned
 * @property bool $is_delayed
 * @property \Carbon\CarbonImmutable|null $packaging_started_at
 * @property \Carbon\CarbonImmutable|null $expected_delivery_at
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read bool|null $activities_exists
 * @property-read \App\Models\User $customer
 * @property-read bool $is_pending
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\VendorOrderItem> $items
 * @property-read int|null $items_count
 * @property-read bool|null $items_exists
 * @property-read \App\Models\Order $order
 * @property-read \App\Models\VendorProfile $vendorProfile
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\VendorOrder delayed()
 * @method static \Database\Factories\VendorOrderFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\VendorOrder newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\VendorOrder newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\VendorOrder query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\VendorOrder whereBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\VendorOrder whereCommission($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\VendorOrder whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\VendorOrder whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\VendorOrder whereDeliveryFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\VendorOrder whereExpectedDeliveryAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\VendorOrder whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\VendorOrder whereIsDelayed($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\VendorOrder whereIsRiderAssigned($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\VendorOrder whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\VendorOrder whereOrderNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\VendorOrder wherePackagingStartedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\VendorOrder whereProductTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\VendorOrder whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\VendorOrder whereStatusNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\VendorOrder whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\VendorOrder whereVat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\VendorOrder whereVendorProfileId($value)
 */
	class VendorOrder extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $vendor_order_id
 * @property int $product_id
 * @property int|null $product_variant_id
 * @property int $quantity
 * @property numeric $unit_price
 * @property numeric $total_price
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \App\Models\Product|null $product
 * @property-read \App\Models\ProductVariant|null $variant
 * @property-read \App\Models\VendorOrder $vendorOrder
 * @method static \Database\Factories\VendorOrderItemFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\VendorOrderItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\VendorOrderItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\VendorOrderItem query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\VendorOrderItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\VendorOrderItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\VendorOrderItem whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\VendorOrderItem whereProductVariantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\VendorOrderItem whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\VendorOrderItem whereTotalPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\VendorOrderItem whereUnitPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\VendorOrderItem whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\VendorOrderItem whereVendorOrderId($value)
 */
	class VendorOrderItem extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property int|null $shop_category_id
 * @property string $shop_name
 * @property string|null $description
 * @property string|null $pickup_location
 * @property numeric $balance
 * @property numeric $total_sales
 * @property numeric $withdrawn_amount
 * @property int $order_count
 * @property \App\Enums\VendorStatus $status
 * @property string|null $status_note
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read bool|null $activities_exists
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\VendorFollower> $followers
 * @property-read int|null $followers_count
 * @property-read bool|null $followers_exists
 * @property-read bool $is_approved
 * @property-read bool $is_pending
 * @property-read bool $is_rejected
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read bool|null $media_exists
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Product> $products
 * @property-read int|null $products_count
 * @property-read bool|null $products_exists
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\VendorReview> $reviews
 * @property-read int|null $reviews_count
 * @property-read bool|null $reviews_exists
 * @property-read \App\Models\ShopCategory|null $shopCategory
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ShortVideo> $shortVideos
 * @property-read int|null $short_videos_count
 * @property-read bool|null $short_videos_exists
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\VendorProfile approved()
 * @method static \Database\Factories\VendorProfileFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\VendorProfile newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\VendorProfile newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\VendorProfile query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\VendorProfile whereBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\VendorProfile whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\VendorProfile whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\VendorProfile whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\VendorProfile whereOrderCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\VendorProfile wherePickupLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\VendorProfile whereShopCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\VendorProfile whereShopName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\VendorProfile whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\VendorProfile whereStatusNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\VendorProfile whereTotalSales($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\VendorProfile whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\VendorProfile whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\VendorProfile whereWithdrawnAmount($value)
 */
	class VendorProfile extends \Eloquent implements \Spatie\MediaLibrary\HasMedia {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $vendor_profile_id
 * @property int $user_id
 * @property int $rating
 * @property string|null $comment
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \App\Models\User $user
 * @property-read \App\Models\VendorProfile $vendorProfile
 * @method static \Database\Factories\VendorReviewFactory factory($count = null, $state = [])
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
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
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

