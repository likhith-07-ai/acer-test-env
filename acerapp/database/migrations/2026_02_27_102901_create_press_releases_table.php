<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('press_releases', function (Blueprint $table) {
            $table->id();

            // Header Information
            $table->string('city')->nullable();
            $table->date('date')->nullable();
            $table->string('company_name')->nullable();
            $table->string('headline')->nullable();

            // Sections
            $table->json('rating_action_table')->nullable();
            $table->longText('analytical_approach')->nullable();
            $table->longText('brief_summary')->nullable();
            $table->longText('strengths')->nullable();
            $table->longText('weaknesses')->nullable();
            $table->longText('liquidity')->nullable();
            $table->longText('positive_sensitivities')->nullable();
            $table->longText('negative_sensitivities')->nullable();
            $table->longText('about_company_body')->nullable();
            $table->json('company_segments_table')->nullable();

            // Financials
            $table->string('financials_basis')->nullable();
            $table->json('fy_columns')->nullable();
            $table->string('financials_source')->nullable();

            // Status & Other
            $table->longText('non_cooperation_status')->nullable();
            $table->longText('other_information')->nullable();

            // Annexures
            $table->json('ann1_rating_history')->nullable();
            $table->json('ann1_1_complexity_levels')->nullable();
            $table->json('ann2_instrument_details')->nullable();
            $table->json('ann3_lender_details')->nullable();
            $table->longText('ann4_covenants')->nullable();
            $table->longText('ann5_fsr_list')->nullable();
            $table->json('ann6_entities_consolidated')->nullable();

            $table->json('applicable_criteria')->nullable();
            $table->json('analytical_contacts')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('press_releases');
    }
};
