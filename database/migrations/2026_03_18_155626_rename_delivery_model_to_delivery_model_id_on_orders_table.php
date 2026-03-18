<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table): void {
            $table->dropColumn('delivery_model');
        });

        Schema::table('orders', function (Blueprint $table): void {
            $table->unsignedBigInteger('delivery_model_id')->nullable()->after('total_sellers');
            $table->foreign('delivery_model_id')->references('id')->on('delivery_models')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table): void {
            $table->dropForeign(['delivery_model_id']);
            $table->dropColumn('delivery_model_id');
        });

        Schema::table('orders', function (Blueprint $table): void {
            $table->string('delivery_model')->nullable()->after('total_sellers');
        });
    }
};
