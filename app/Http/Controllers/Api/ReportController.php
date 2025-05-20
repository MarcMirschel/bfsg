<?php

namespace App\Http\Controllers\Api;

use App\Models\Scan;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ReportController extends Controller
{
    public function __invoke(string $permalink)
    {
        $scan = Scan::where('permalink', $permalink)
            ->with('pages.issues')
            ->firstOrFail();

        return response()->json($scan);
    }
}
