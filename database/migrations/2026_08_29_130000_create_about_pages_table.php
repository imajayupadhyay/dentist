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
        Schema::create('about_pages', function (Blueprint $table): void {
            $table->id();
            $table->string('key')->default('about')->unique();

            $table->string('masthead_eyebrow')->default('About the clinic');
            $table->string('masthead_heading');
            $table->string('masthead_heading_accent')->nullable();
            $table->string('masthead_heading_suffix')->nullable();
            $table->text('masthead_lede');
            $table->json('masthead_meta')->nullable();
            $table->string('masthead_primary_label')->default('Book an appointment');
            $table->string('masthead_primary_href')->default('/#book');
            $table->string('masthead_secondary_label')->default('Meet the team');
            $table->string('masthead_secondary_href')->default('#team');
            $table->string('masthead_lead_image');
            $table->string('masthead_lead_image_alt')->nullable();
            $table->string('masthead_inset_image');
            $table->string('masthead_inset_image_alt')->nullable();
            $table->unsignedTinyInteger('masthead_proof_stars')->default(5);
            $table->string('masthead_proof_rating');
            $table->string('masthead_proof_text');

            $table->json('figures')->nullable();

            $table->string('note_eyebrow')->default("Founder's note");
            $table->string('note_image');
            $table->string('note_image_alt')->nullable();
            $table->text('note_quote');
            $table->text('note_body');
            $table->string('note_signature');
            $table->string('note_name');
            $table->string('note_role');

            $table->string('values_eyebrow')->default('How we work');
            $table->string('values_heading');
            $table->string('values_heading_accent')->nullable();
            $table->string('values_heading_suffix')->nullable();
            $table->text('values_lede');
            $table->json('values_items')->nullable();

            $table->string('team_eyebrow')->default('The team');
            $table->string('team_heading');
            $table->string('team_heading_accent')->nullable();
            $table->string('team_heading_suffix')->nullable();
            $table->text('team_lede');
            $table->string('team_image');
            $table->string('team_image_alt')->nullable();
            $table->text('team_caption')->nullable();
            $table->json('clinicians')->nullable();
            $table->json('team_chips')->nullable();

            $table->string('cta_heading');
            $table->string('cta_heading_accent')->nullable();
            $table->string('cta_heading_suffix')->nullable();
            $table->text('cta_body');
            $table->string('cta_primary_label')->default('Book an appointment');
            $table->string('cta_primary_href')->default('/#book');
            $table->string('cta_secondary_label')->default('Call the clinic');
            $table->string('cta_secondary_href')->default('tel:+912226000000');

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
        Schema::dropIfExists('about_pages');
    }
};
