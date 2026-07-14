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
        Schema::table('press_releases', function (Blueprint $table) {
            $table->renameColumn('ann1_rating_history', 'annexure_1_rating_history');
            $table->renameColumn('ann1_1_complexity_levels', 'annexure_1_1_complexity');
            $table->renameColumn('ann2_instrument_details', 'annexure_2_instruments');
            $table->renameColumn('ann3_lender_details', 'annexure_3_lenders');

            // Missing fields
            $table->text('liquidity_body')->nullable();
            $table->string('outlook')->nullable();
            $table->text('outlook_body')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('press_releases', function (Blueprint $table) {
            $table->renameColumn('annexure_1_rating_history', 'ann1_rating_history');
            $table->renameColumn('annexure_1_1_complexity', 'ann1_1_complexity_levels');
            $table->renameColumn('annexure_2_instruments', 'ann2_instrument_details');
            $table->renameColumn('annexure_3_lenders', 'ann3_lender_details');

            $table->dropColumn([
                'liquidity_body', 'outlook', 'outlook_body'
            ]);
        });
    }
};
