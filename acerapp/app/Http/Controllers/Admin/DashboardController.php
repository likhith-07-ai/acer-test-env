<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Document;

class DashboardController extends Controller
{
    /**
     * Display the KPI dashboard.
     */
    public function index()
    {
        // KPI Group 1 - All Documents
        $totalDocuments = Document::count();
        $sebiDocuments = Document::sebi()->count();
        $rbiDocuments = Document::rbi()->count();
        $restrictedDocuments = Document::restricted()->count();

        // KPI Group 2 - SEBI
        $totalSebiDocuments = Document::sebi()->count();
        $publicSebiDocuments = Document::sebi()->public()->count();
        $restrictedSebiDocuments = Document::sebi()->restricted()->count();

        // KPI Group 3 - RBI
        $totalRbiDocuments = Document::rbi()->count();
        $publicRbiDocuments = Document::rbi()->public()->count();
        $restrictedRbiDocuments = Document::rbi()->restricted()->count();

        return view('admin.dashboard', compact(
            'totalDocuments',
            'sebiDocuments',
            'rbiDocuments',
            'restrictedDocuments',
            'totalSebiDocuments',
            'publicSebiDocuments',
            'restrictedSebiDocuments',
            'totalRbiDocuments',
            'publicRbiDocuments',
            'restrictedRbiDocuments'
        ));
    }
}
