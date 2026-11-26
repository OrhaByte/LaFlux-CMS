<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('cms_redirects')) {
            Schema::create('cms_redirects', function (Blueprint $table) {
                $table->id();
                $table->string('source_url', 500)->unique();
                $table->string('target_url', 500);
                $table->smallInteger('status_code')->default(301); // 301, 302, 307, 308
                $table->unsignedBigInteger('hits')->default(0);
                $table->smallInteger('status')->default(1);
                $table->timestamps();
                $table->softDeletes();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('cms_redirects');
    }
};

