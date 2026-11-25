<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('cms_menu_items')) {
            Schema::create('cms_menu_items', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('menu_id')->index();
                $table->unsignedBigInteger('parent_id')->nullable()->index();
                $table->string('title', 200);
                $table->string('type', 30)->default('custom'); // custom, page, post, category
                $table->unsignedBigInteger('reference_id')->nullable();
                $table->string('url', 500)->nullable();
                $table->string('target', 20)->default('_self');
                $table->integer('ordering')->default(0);
                $table->smallInteger('status')->default(1);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('cms_menu_items');
    }
};

