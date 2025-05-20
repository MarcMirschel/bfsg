<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ReportController;

Route::get('/report/{permalink}', ReportController::class)
    ->name('report.api');
