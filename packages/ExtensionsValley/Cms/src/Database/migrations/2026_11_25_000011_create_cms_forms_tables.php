<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('cms_forms')) {
            Schema::create('cms_forms', function (Blueprint $table) {
                $table->id();
                $table->string('name', 200);
                $table->string('slug', 200)->unique();
                $table->string('submit_label', 100)->default('Submit');
                $table->string('success_message', 500)->default('Thank you! Your submission has been received.');
                $table->string('notify_email', 255)->nullable();
                $table->smallInteger('status')->default(1);
                $table->timestamps();
                $table->softDeletes();
            });
        }

        if (!Schema::hasTable('cms_form_fields')) {
            Schema::create('cms_form_fields', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('form_id')->index();
                $table->string('label', 200);
                $table->string('field_name', 120);
                $table->string('field_type', 40)->default('text'); // text, email, textarea, number, tel, date, select, radio, checkbox
                $table->string('placeholder', 255)->nullable();
                $table->text('options')->nullable(); // comma-separated for select/radio/checkbox
                $table->boolean('is_required')->default(false);
                $table->integer('ordering')->default(0);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('cms_form_submissions')) {
            Schema::create('cms_form_submissions', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('form_id')->index();
                $table->longText('payload');
                $table->string('ip_address', 64)->nullable();
                $table->string('user_agent', 500)->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('cms_form_submissions');
        Schema::dropIfExists('cms_form_fields');
        Schema::dropIfExists('cms_forms');
    }
};

