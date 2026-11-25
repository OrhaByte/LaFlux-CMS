<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('cms_posts')) {
            Schema::create('cms_posts', function (Blueprint $table) {
                $table->id();
                $table->string('title', 255);
                $table->string('slug', 255)->unique();
                $table->text('excerpt')->nullable();
                $table->longText('content')->nullable();
                $table->unsignedBigInteger('category_id')->nullable()->index();
                $table->string('featured_image', 500)->nullable();
                $table->string('meta_title', 255)->nullable();
                $table->text('meta_description')->nullable();
                $table->smallInteger('status')->default(1);
                $table->timestamp('published_at')->nullable()->index();
                $table->unsignedBigInteger('author_id')->nullable()->index();
                $table->timestamps();
                $table->softDeletes();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('cms_posts');
    }
};

