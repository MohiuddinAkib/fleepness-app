<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('seller_orders', function (Blueprint $table): void {
            $table->dateTime('delivery_start_time')->nullable()->change();
            $table->dateTime('delivery_end_time')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('seller_orders', function (Blueprint $table): void {
            $table->time('delivery_start_time')->nullable()->change();
            $table->time('delivery_end_time')->nullable()->change();
        });
    }
};
