<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('cms_theme_settings')) {
            Schema::create('cms_theme_settings', function (Blueprint $table) {
                $table->id();
                $table->string('theme_slug', 100)->default('default');
                $table->string('language_code', 10)->default('en');
                $table->string('section_key', 100);
                $table->longText('settings')->nullable();
                $table->timestamps();

                $table->unique(['theme_slug', 'language_code', 'section_key'], 'cms_theme_settings_unique');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('cms_theme_settings');
    }
};

