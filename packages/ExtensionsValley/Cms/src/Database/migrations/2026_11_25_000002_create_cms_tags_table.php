<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('cms_tags')) {
            Schema::create('cms_tags', function (Blueprint $table) {
                $table->id();
                $table->string('name', 150);
                $table->string('slug', 180)->unique();
                $table->smallInteger('status')->default(1);
                $table->timestamps();
                $table->softDeletes();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('cms_tags');
    }
};

