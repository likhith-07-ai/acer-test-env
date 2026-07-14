<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/test-press-release', [\App\Http\Controllers\Admin\PressReleaseController::class, 'store']);
