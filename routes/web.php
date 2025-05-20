<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportController;

Route::get('/report/{permalink}', ReportController::class)
    ->name('report.web');
