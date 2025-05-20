<?php

use App\Services\ScanService;
use Illuminate\Support\Facades\Http;

it('stores pages and issues', function () {
    Http::fake([
        'https://example.com' => Http::response('<html><title>Home</title></html>', 200),
    ]);

    $scan = app(ScanService::class)->run('https://example.com', 1, 1, 'TestBot/1.0');

    expect($scan->pages)->toHaveCount(1)
        ->and($scan->pages->first()->issues_total)->toBeGreaterThanOrEqual(0);
});
