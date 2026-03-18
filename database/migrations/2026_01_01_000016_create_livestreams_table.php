<?php

declare(strict_types=1);

use App\Enums\LivestreamStatus;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('livestreams', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('vendor_profile_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('room_id')->unique();
            $table->string('egress_id')->nullable();
            $table->json('egress_metadata')->nullable();
            $table->string('status')->default(LivestreamStatus::Scheduled->value);
            $table->unsignedInteger('viewer_count')->default(0);
            $table->dateTime('scheduled_at')->nullable();
            $table->dateTime('started_at')->nullable();
            $table->dateTime('ended_at')->nullable();
            $table->unsignedInteger('total_duration')->nullable();
            $table->timestamps();
        });

        Schema::create('livestream_products', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('livestream_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('livestream_comments', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('livestream_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->text('comment');
            $table->timestamps();
        });

        Schema::create('livestream_likes', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('livestream_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['livestream_id', 'user_id']);
        });

        Schema::create('livestream_saves', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('livestream_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['livestream_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('livestream_saves');
        Schema::dropIfExists('livestream_likes');
        Schema::dropIfExists('livestream_comments');
        Schema::dropIfExists('livestream_products');
        Schema::dropIfExists('livestreams');
    }
};
