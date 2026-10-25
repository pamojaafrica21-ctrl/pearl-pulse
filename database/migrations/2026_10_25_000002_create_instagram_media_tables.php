<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('instagram_media', function (Blueprint $table) {
            $table->id();
            $table->string('ig_id')->unique();
            $table->string('media_type', 32)->default('IMAGE');
            $table->string('media_product_type', 32)->nullable();
            $table->text('caption')->nullable();
            $table->string('permalink', 500)->nullable();
            $table->text('media_url')->nullable();
            $table->text('thumbnail_url')->nullable();
            $table->unsignedInteger('like_count')->default(0);
            $table->unsignedInteger('comments_count')->default(0);
            $table->timestamp('posted_at')->nullable();
            $table->json('raw')->nullable();
            $table->timestamps();
        });

        Schema::create('instagram_comments', function (Blueprint $table) {
            $table->id();
            $table->string('ig_id')->unique();
            $table->foreignId('instagram_media_id')->constrained('instagram_media')->cascadeOnDelete();
            $table->string('username')->nullable();
            $table->text('text')->nullable();
            $table->timestamp('posted_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('instagram_comments');
        Schema::dropIfExists('instagram_media');
    }
};
