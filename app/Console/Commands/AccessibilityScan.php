<?php

namespace App\Console\Commands;

use App\Services\ScanService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

/**
 * Führt einen BFSG/WCAG‑Scan für eine URL durch.
 */
class AccessibilityScan extends Command
{
    protected $signature = 'accessibility:scan
                            {url : Start‑URL}
                            {--max-pages=50 : Maximale Anzahl gecrawlter Seiten}
                            {--depth=3 : Maximale Link‑Tiefe}
                            {--user-agent=Mozilla/5.0 (BFSG Scanner) : Custom UA}';

    protected $description = 'Crawler + Accessibility‑Audit gem. BFSG / WCAG 2.1 AA';

    public function handle(ScanService $scanService): int
    {
        $data = $this->arguments() + $this->options();

        $validator = Validator::make($data, [
            'url'        => 'required|url',
            'max-pages'  => 'integer|min:1|max:1000',
            'depth'      => 'integer|min:1|max:10',
            'user-agent' => 'string|max:255',
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }
            return self::FAILURE;
        }

        $scan = $scanService->run(
            $data['url'],
            (int) $data['max-pages'],
            (int) $data['depth'],
            $data['user-agent']
        );

        $this->info("Scan abgeschlossen! Bericht: {$scan->permalinkUrl()}");

        return self::SUCCESS;
    }
}
