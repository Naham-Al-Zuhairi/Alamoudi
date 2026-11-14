<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', fn (Blueprint $table) => $table->boolean('is_admin')->default(false)->index());
        foreach (['hero_slides', 'page_sections', 'achievements', 'project_categories', 'projects', 'contact_locations', 'site_settings'] as $name) {
            Schema::create($name, function (Blueprint $table) use ($name) {
                $table->id();
                $table->string('slug')->unique();
                $table->string('title_ar')->nullable();
                $table->string('title_en')->nullable();
                $table->text('body_ar')->nullable();
                $table->text('body_en')->nullable();
                $table->string('image')->nullable();
                $table->unsignedInteger('position')->default(0);
                $table->boolean('is_published')->default(true);
                if ($name === 'projects') {
                    $table->foreignId('project_category_id')->nullable()->constrained()->nullOnDelete();
                }
                if ($name === 'contact_locations') {
                    $table->string('email')->nullable();
                    $table->string('phone')->nullable();
                    $table->string('fax')->nullable();
                }
                $table->timestamps();
                $table->index(['is_published', 'position']);
            });
        }
        Schema::create('project_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->string('path');
            $table->unsignedInteger('position')->default(0);
            $table->timestamps();
        });
        Schema::create('contact_messages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('subject');
            $table->text('message');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
            $table->index(['read_at', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_messages');
        Schema::dropIfExists('project_images');
        foreach (['projects', 'project_categories', 'site_settings', 'contact_locations', 'achievements', 'page_sections', 'hero_slides'] as $name) {
            Schema::dropIfExists($name);
        }
        Schema::table('users', fn (Blueprint $table) => $table->dropColumn('is_admin'));
    }
};
