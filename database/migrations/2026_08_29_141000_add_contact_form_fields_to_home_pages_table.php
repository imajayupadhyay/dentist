<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('home_pages', function (Blueprint $table): void {
            $table->string('contact_form_heading')->default('Request an appointment');
            $table->text('contact_form_intro')->nullable();
            $table->json('contact_form_treatment_options')->nullable();
            $table->json('contact_form_time_options')->nullable();
            $table->string('contact_form_submit_label', 80)->default('Request a call back');
            $table->text('contact_form_privacy_note')->nullable();
            $table->string('contact_form_success_title', 160)->default("Thank you — that's with the front desk.");
            $table->text('contact_form_success_body')->nullable();
        });

        DB::table('home_pages')->update([
            'contact_form_intro' => 'Send this and the front desk will call you back the same working day. Nothing is confirmed until you have spoken to a person.',
            'contact_form_treatment_options' => json_encode([
                ['label' => 'General check-up'],
                ['label' => 'Pain or emergency'],
                ['label' => 'Dental implants'],
                ['label' => 'Invisible aligners'],
                ['label' => 'Smile design'],
                ['label' => 'Jaw joint (TMD)'],
                ['label' => "Kids' dentistry"],
            ], JSON_THROW_ON_ERROR),
            'contact_form_time_options' => json_encode([
                ['label' => 'Morning · 9:30 – 13:00'],
                ['label' => 'Afternoon · 13:00 – 17:00'],
                ['label' => 'Evening · 17:00 – 19:30'],
            ], JSON_THROW_ON_ERROR),
            'contact_form_privacy_note' => 'We reply the same working day. Your details are never shared.',
            'contact_form_success_body' => 'Someone will call you before the end of the day to confirm a time.',
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('home_pages', function (Blueprint $table): void {
            $table->dropColumn([
                'contact_form_heading',
                'contact_form_intro',
                'contact_form_treatment_options',
                'contact_form_time_options',
                'contact_form_submit_label',
                'contact_form_privacy_note',
                'contact_form_success_title',
                'contact_form_success_body',
            ]);
        });
    }
};
