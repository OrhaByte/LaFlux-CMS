<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('cms_languages')) {
            Schema::create('cms_languages', function (Blueprint $table) {
                $table->id();
                $table->string('name', 100);
                $table->string('code', 10)->unique();
                $table->string('direction', 5)->default('ltr'); // ltr or rtl
                $table->boolean('is_default')->default(false);
                $table->smallInteger('status')->default(1);
                $table->timestamps();
                $table->softDeletes();
            });
        }

        // Seed default English language immediately if table is empty
        if (DB::table('cms_languages')->count() === 0) {
            DB::table('cms_languages')->insert([
                'name' => 'English',
                'code' => 'en',
                'direction' => 'ltr',
                'is_default' => true,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $tables = ['cms_categories', 'cms_tags', 'cms_pages', 'cms_posts', 'cms_menus'];

        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName) && !Schema::hasColumn($tableName, 'language_code')) {
                Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                    $table->string('language_code', 10)->default('en')->index()->after('id');

                    // Drop old single-column unique index on slug and replace with composite (slug, language_code)
                    try {
                        $table->dropUnique($tableName . '_slug_unique');
                    } catch (\Throwable $e) {
                        // Ignore if index name differs
                    }
                    $table->unique(['slug', 'language_code'], $tableName . '_slug_lang_unique');
                });
            }
        }
    }

    public function down(): void
    {
        $tables = ['cms_categories', 'cms_tags', 'cms_pages', 'cms_posts', 'cms_menus'];

        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName) && Schema::hasColumn($tableName, 'language_code')) {
                Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                    try {
                        $table->dropUnique($tableName . '_slug_lang_unique');
                    } catch (\Throwable $e) {
                    }
                    $table->dropColumn('language_code');
                });
            }
        }

        Schema::dropIfExists('cms_languages');
    }
};

