<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('cms_pages')) {
            Schema::table('cms_pages', function (Blueprint $table) {
                if (!Schema::hasColumn('cms_pages', 'template')) {
                    $table->string('template', 100)->default('default')->after('slug');
                }
                if (!Schema::hasColumn('cms_pages', 'template_data')) {
                    $table->longText('template_data')->nullable()->after('template');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('cms_pages')) {
            Schema::table('cms_pages', function (Blueprint $table) {
                if (Schema::hasColumn('cms_pages', 'template_data')) {
                    $table->dropColumn('template_data');
                }
                if (Schema::hasColumn('cms_pages', 'template')) {
                    $table->dropColumn('template');
                }
            });
        }
    }
};

