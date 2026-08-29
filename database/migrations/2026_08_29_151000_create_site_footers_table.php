<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('site_footers', function (Blueprint $table): void {
            $table->id();
            $table->string('key')->default('main')->unique();
            $table->boolean('cta_enabled')->default(true);
            $table->string('cta_icon', 40)->default('phone');
            $table->string('cta_title')->nullable();
            $table->text('cta_body')->nullable();
            $table->json('cta_actions')->nullable();
            $table->text('logo_path')->nullable();
            $table->string('logo_alt')->nullable();
            $table->string('brand_name')->nullable();
            $table->string('brand_subtitle')->nullable();
            $table->text('brand_blurb')->nullable();
            $table->json('social_links')->nullable();
            $table->json('link_groups')->nullable();
            $table->string('contact_title')->nullable();
            $table->json('contact_items')->nullable();
            $table->string('bottom_copyright')->nullable();
            $table->string('bottom_location')->nullable();
            $table->string('back_to_top_label')->nullable();
            $table->string('back_to_top_href')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_footers');
    }
};
