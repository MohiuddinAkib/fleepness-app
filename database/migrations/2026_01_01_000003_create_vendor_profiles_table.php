<?php

declare(strict_types=1);

use App\Enums\VendorStatus;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vendor_profiles', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete()->unique();
            $table->foreignId('shop_category_id')->nullable()->constrained()->nullOnDelete();
            $table->string('shop_name');
            $table->text('description')->nullable();
            $table->string('pickup_location')->nullable();
            $table->decimal('balance', 12, 2)->default(0);
            $table->decimal('total_sales', 15, 2)->default(0);
            $table->decimal('withdrawn_amount', 15, 2)->default(0);
            $table->unsignedInteger('order_count')->default(0);
            $table->string('status')->default(VendorStatus::Pending->value);
            $table->text('status_note')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vendor_profiles');
    }
};
