<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PressRelease extends Model
{
    protected $fillable = [
        'city',
        'date',
        'company_name',
        'city',
        'headline',
        'rating_action_table',
        'analytical_approach',
        'brief_summary',
        'strengths',
        'weaknesses',
        'liquidity',
        'positive_sensitivities',
        'negative_sensitivities',
        'about_company_body',
        'company_segments_table',
        'financials_basis',
        'fy_columns',
        'financials_source',
        'non_cooperation_status',
        'other_information',
        'annexure_1_rating_history',
        'annexure_1_1_complexity',
        'annexure_2_instruments',
        'annexure_3_lenders',
        'liquidity_body',
        'ann4_covenants',
        'ann5_fsr_list',
        'ann6_entities_consolidated',
        'applicable_criteria',
        'analytical_contacts',
        'unsupported_rating',
        'pdf_file',
        'format'
    ];

    protected $casts = [
        'date' => 'date',
        'rating_action_table' => 'array',
        'company_segments_table' => 'array',
        'fy_columns' => 'array',
        'strengths' => 'array',
        'weaknesses' => 'array',
        'positive_sensitivities' => 'array',
        'negative_sensitivities' => 'array',
        'annexure_1_rating_history' => 'array',
        'annexure_1_1_complexity' => 'array',
        'annexure_2_instruments' => 'array',
        'annexure_3_lenders' => 'array',
        'ann6_entities_consolidated' => 'array',
        'applicable_criteria' => 'array',
        'analytical_contacts' => 'array',
    ];

    public function getInstrumentsAttribute()
    {
        if (empty($this->rating_action_table) || !is_array($this->rating_action_table))
            return 'N/A';
        return collect($this->rating_action_table)->pluck('instrument_name')->filter()->implode('<br>') ?: 'N/A';
    }

    public function getSizeAttribute()
    {
        if (empty($this->rating_action_table) || !is_array($this->rating_action_table))
            return 'N/A';
        return collect($this->rating_action_table)->pluck('amount_inr')->filter()->implode('<br>') ?: 'N/A';
    }

    public function getRatingAttribute()
    {
        if (empty($this->rating_action_table) || !is_array($this->rating_action_table))
            return 'N/A';
        return collect($this->rating_action_table)->pluck('current_rating')->filter()->implode('<br>') ?: 'N/A';
    }
}