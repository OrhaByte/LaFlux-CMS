<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        foreach (['cms_categories', 'cms_pages', 'cms_posts'] as $tableName) {
            if (Schema::hasTable($tableName) && !Schema::hasColumn($tableName, 'meta_keywords')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->string('meta_keywords', 500)->nullable()->after('meta_title');
                });
            }
        }
    }

    public function down(): void
    {
        foreach (['cms_categories', 'cms_pages', 'cms_posts'] as $tableName) {
            if (Schema::hasTable($tableName) && Schema::hasColumn($tableName, 'meta_keywords')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->dropColumn('meta_keywords');
                });
            }
        }
    }
};

