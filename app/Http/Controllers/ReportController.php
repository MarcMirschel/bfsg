<?php

namespace App\Http\Controllers;

use App\Models\Scan;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function __invoke(string $permalink)
    {
        $scan = Scan::where('permalink', $permalink)
            ->with('pages.issues')
            ->firstOrFail();

        return view('report', compact('scan'));
    }
}
