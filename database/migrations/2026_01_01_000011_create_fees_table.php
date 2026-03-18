<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fees', function (Blueprint $table): void {
            $table->id();
            $table->decimal('vat', 5, 2);
            $table->decimal('platform_fee', 8, 2);
            $table->decimal('commission', 5, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fees');
    }
};
