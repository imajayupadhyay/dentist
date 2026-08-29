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
        Schema::create('treatments', function (Blueprint $table): void {
            $table->id();
            $table->unsignedInteger('sort_order')->default(0)->index();
            $table->boolean('is_active')->default(true)->index();
            $table->string('slug')->unique();
            $table->string('tone', 32)->default('crimson');

            $table->string('home_title');
            $table->string('home_subtitle');
            $table->text('home_description');
            $table->string('home_image');
            $table->string('home_image_alt')->nullable();
            $table->text('home_icon_svg');

            $table->string('category');
            $table->string('title');
            $table->string('title_accent')->nullable();
            $table->string('tagline');
            $table->text('summary');
            $table->string('hero_image');
            $table->string('hero_image_alt')->nullable();
            $table->json('facts')->nullable();

            $table->string('overview_eyebrow')->default('What it is');
            $table->string('overview_heading');
            $table->string('overview_heading_accent')->nullable();
            $table->text('overview_lede');
            $table->longText('overview_body');
            $table->string('overview_image');
            $table->string('overview_image_alt')->nullable();
            $table->string('overview_caption')->nullable();

            $table->string('suitability_eyebrow')->default('Suitability');
            $table->string('suitability_heading')->default('Who this works for.');
            $table->string('suitability_heading_accent')->nullable();
            $table->text('suitability_lede')->nullable();
            $table->json('suitable_for')->nullable();
            $table->json('not_suitable')->nullable();

            $table->string('process_eyebrow')->default('Step by step');
            $table->string('process_heading')->default('From first visit to finished care.');
            $table->string('process_heading_accent')->nullable();
            $table->text('process_lede')->nullable();
            $table->json('steps')->nullable();

            $table->string('faq_eyebrow')->default('Questions');
            $table->string('faq_heading')->default('The things people actually ask.');
            $table->string('faq_heading_accent')->nullable();
            $table->text('faq_lede')->nullable();
            $table->json('faqs')->nullable();

            $table->string('cta_heading')->default('Find out if it is right for you.');
            $table->string('cta_heading_accent')->nullable();
            $table->text('cta_body')->nullable();

            $table->string('whatsapp_number')->default('919820000000');
            $table->text('whatsapp_message')->nullable();
            $table->string('phone')->default('+912226000000');
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('treatments');
    }
};
