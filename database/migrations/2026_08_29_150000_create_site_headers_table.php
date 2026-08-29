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
        Schema::create('site_headers', function (Blueprint $table): void {
            $table->id();
            $table->string('key')->default('main')->unique();
            $table->text('logo_path')->nullable();
            $table->string('logo_alt')->nullable();
            $table->string('logo_href')->default('/');
            $table->string('brand_name')->default('Pushpa Patel');
            $table->string('brand_subtitle')->nullable();
            $table->string('phone_label')->nullable();
            $table->string('phone_href')->nullable();
            $table->string('cta_label')->nullable();
            $table->string('cta_href')->nullable();
            $table->text('mobile_meta')->nullable();
            $table->json('nav_items')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_headers');
    }
};
