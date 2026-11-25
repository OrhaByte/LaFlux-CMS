<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('cms_content_versions')) {
            Schema::create('cms_content_versions', function (Blueprint $table) {
                $table->id();
                $table->string('content_type', 20); // page or post
                $table->unsignedBigInteger('content_id');
                $table->unsignedInteger('version_number')->default(1);
                $table->string('title', 255);
                $table->text('excerpt')->nullable();
                $table->longText('content')->nullable();
                $table->string('featured_image', 500)->nullable();
                $table->string('meta_title', 255)->nullable();
                $table->string('meta_keywords', 500)->nullable();
                $table->text('meta_description')->nullable();
                $table->unsignedBigInteger('created_by')->nullable();
                $table->timestamps();

                $table->index(['content_type', 'content_id', 'version_number'], 'cms_versions_lookup_idx');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('cms_content_versions');
    }
};

