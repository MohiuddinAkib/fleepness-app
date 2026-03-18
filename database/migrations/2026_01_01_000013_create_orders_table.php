<?php

declare(strict_types=1);

use App\Enums\VendorOrderStatus;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('delivery_option_id')->nullable()->constrained()->nullOnDelete();
            $table->string('order_number', 32)->unique();
            $table->boolean('is_multi_vendor')->default(false);
            $table->unsignedTinyInteger('vendor_count')->default(1);
            $table->decimal('product_total', 10, 2)->default(0);
            $table->decimal('delivery_fee', 10, 2)->default(0);
            $table->decimal('platform_fee', 10, 2)->default(0);
            $table->decimal('vat', 10, 2)->default(0);
            $table->decimal('commission', 10, 2)->default(0);
            $table->decimal('grand_total', 10, 2)->default(0);
            $table->decimal('balance', 12, 2)->default(0);
            $table->boolean('is_completed')->default(false);
            $table->timestamps();
        });

        Schema::create('vendor_orders', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('vendor_profile_id')->constrained()->cascadeOnDelete();
            $table->foreignId('customer_id')->constrained('users')->cascadeOnDelete();
            $table->string('order_number', 32)->unique();
            $table->string('status')->default(VendorOrderStatus::Pending->value);
            $table->text('status_note')->nullable();
            $table->decimal('product_total', 10, 2)->default(0);
            $table->decimal('commission', 10, 2)->default(0);
            $table->decimal('vat', 10, 2)->default(0);
            $table->decimal('delivery_fee', 10, 2)->default(0);
            $table->decimal('balance', 10, 2)->default(0);
            $table->boolean('is_rider_assigned')->default(false);
            $table->boolean('is_delayed')->default(false);
            $table->dateTime('packaging_started_at')->nullable();
            $table->dateTime('expected_delivery_at')->nullable();
            $table->timestamps();
        });

        Schema::create('vendor_order_items', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('vendor_order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_variant_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedSmallInteger('quantity');
            $table->decimal('unit_price', 10, 2);
            $table->decimal('total_price', 10, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vendor_order_items');
        Schema::dropIfExists('vendor_orders');
        Schema::dropIfExists('orders');
    }
};
