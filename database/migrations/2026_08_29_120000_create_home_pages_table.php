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
        Schema::create('home_pages', function (Blueprint $table): void {
            $table->id();
            $table->string('key')->default('home')->unique();

            $table->json('hero_slides')->nullable();
            $table->json('hero_trust_items')->nullable();

            $table->string('about_eyebrow')->default('About the clinic');
            $table->string('about_heading');
            $table->string('about_heading_accent')->nullable();
            $table->text('about_body');
            $table->string('about_cta_label')->default('See what we do');
            $table->string('about_cta_href')->default('#treatments');
            $table->json('about_stats')->nullable();

            $table->string('stories_eyebrow')->default('Patient stories');
            $table->string('stories_heading');
            $table->string('stories_heading_accent')->nullable();
            $table->json('stories_items')->nullable();

            $table->string('contact_eyebrow')->default('Get in touch');
            $table->string('contact_heading');
            $table->string('contact_heading_accent')->nullable();
            $table->string('contact_map_title')->nullable();
            $table->text('contact_map_src')->nullable();

            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->text('seo_canonical_url')->nullable();
            $table->string('seo_focus_keyword', 120)->nullable();
            $table->text('seo_secondary_keywords')->nullable();
            $table->boolean('seo_robots_index')->default(true);
            $table->boolean('seo_robots_follow')->default(true);
            $table->string('seo_og_title', 180)->nullable();
            $table->text('seo_og_description')->nullable();
            $table->text('seo_og_image')->nullable();
            $table->string('seo_og_image_alt')->nullable();
            $table->string('seo_twitter_card', 40)->default('summary_large_image');
            $table->string('seo_twitter_title', 180)->nullable();
            $table->text('seo_twitter_description')->nullable();
            $table->text('seo_twitter_image')->nullable();
            $table->boolean('seo_enable_schema')->default(true);
            $table->string('seo_schema_type', 80)->default('Dentist');
            $table->string('seo_schema_name', 180)->nullable();
            $table->text('seo_schema_description')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('home_pages');
    }
};
