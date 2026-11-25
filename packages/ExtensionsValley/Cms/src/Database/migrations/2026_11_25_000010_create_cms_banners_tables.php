<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('cms_banners')) {
            Schema::create('cms_banners', function (Blueprint $table) {
                $table->id();
                $table->string('name', 200);
                $table->string('slug', 200)->unique();
                $table->smallInteger('status')->default(1);
                $table->timestamps();
                $table->softDeletes();
            });
        }

        if (!Schema::hasTable('cms_banner_slides')) {
            Schema::create('cms_banner_slides', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('banner_id')->index();
                $table->string('title', 255)->nullable();
                $table->text('description')->nullable();
                $table->string('image', 500);
                $table->string('button_text', 100)->nullable();
                $table->string('button_link', 500)->nullable();
                $table->integer('ordering')->default(0);
                $table->smallInteger('status')->default(1);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('cms_banner_slides');
        Schema::dropIfExists('cms_banners');
    }
};

