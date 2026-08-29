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
        Schema::table('treatments', function (Blueprint $table): void {
            $table->text('seo_canonical_url')->nullable()->after('seo_description');
            $table->string('seo_focus_keyword', 120)->nullable()->after('seo_canonical_url');
            $table->text('seo_secondary_keywords')->nullable()->after('seo_focus_keyword');
            $table->boolean('seo_robots_index')->default(true)->after('seo_secondary_keywords');
            $table->boolean('seo_robots_follow')->default(true)->after('seo_robots_index');
            $table->string('seo_breadcrumb_label', 120)->nullable()->after('seo_robots_follow');
            $table->string('seo_og_title', 180)->nullable()->after('seo_breadcrumb_label');
            $table->text('seo_og_description')->nullable()->after('seo_og_title');
            $table->text('seo_og_image')->nullable()->after('seo_og_description');
            $table->string('seo_og_image_alt')->nullable()->after('seo_og_image');
            $table->string('seo_twitter_card', 40)->default('summary_large_image')->after('seo_og_image_alt');
            $table->string('seo_twitter_title', 180)->nullable()->after('seo_twitter_card');
            $table->text('seo_twitter_description')->nullable()->after('seo_twitter_title');
            $table->text('seo_twitter_image')->nullable()->after('seo_twitter_description');
            $table->boolean('seo_enable_schema')->default(true)->after('seo_twitter_image');
            $table->string('seo_schema_type', 80)->default('MedicalProcedure')->after('seo_enable_schema');
            $table->string('seo_schema_name', 180)->nullable()->after('seo_schema_type');
            $table->text('seo_schema_description')->nullable()->after('seo_schema_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('treatments', function (Blueprint $table): void {
            $table->dropColumn([
                'seo_canonical_url',
                'seo_focus_keyword',
                'seo_secondary_keywords',
                'seo_robots_index',
                'seo_robots_follow',
                'seo_breadcrumb_label',
                'seo_og_title',
                'seo_og_description',
                'seo_og_image',
                'seo_og_image_alt',
                'seo_twitter_card',
                'seo_twitter_title',
                'seo_twitter_description',
                'seo_twitter_image',
                'seo_enable_schema',
                'seo_schema_type',
                'seo_schema_name',
                'seo_schema_description',
            ]);
        });
    }
};
