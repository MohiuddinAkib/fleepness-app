<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('short_videos', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('vendor_profile_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('video_path');
            $table->string('thumbnail_path')->nullable();
            $table->unsignedInteger('likes_count')->default(0);
            $table->timestamps();
        });

        Schema::create('short_video_products', function (Blueprint $table): void {
            $table->foreignId('short_video_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->primary(['short_video_id', 'product_id']);
        });

        Schema::create('short_video_comments', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('short_video_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->text('comment');
            $table->timestamps();
        });

        Schema::create('short_video_likes', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('short_video_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['short_video_id', 'user_id']);
        });

        Schema::create('short_video_saves', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('short_video_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['short_video_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('short_video_saves');
        Schema::dropIfExists('short_video_likes');
        Schema::dropIfExists('short_video_comments');
        Schema::dropIfExists('short_video_products');
        Schema::dropIfExists('short_videos');
    }
};
