<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('cms_categories')) {
            Schema::create('cms_categories', function (Blueprint $table) {
                $table->id();
                $table->string('name', 200);
                $table->string('slug', 220)->unique();
                $table->unsignedBigInteger('parent_id')->nullable()->index();
                $table->text('description')->nullable();
                $table->string('meta_title', 255)->nullable();
                $table->text('meta_description')->nullable();
                $table->smallInteger('status')->default(1);
                $table->unsignedBigInteger('created_by')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('cms_categories');
    }
};

