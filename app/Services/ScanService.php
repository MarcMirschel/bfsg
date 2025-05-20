<?php

namespace App\Services;

use App\Models\Scan;
use App\Models\Page;
use App\Models\Issue;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;
use Spatie\Crawler\Crawler;
use Spatie\LaravelWebCrawler\CrawlResults;
use Cidilabs\PhpAlly\PhpAlly;

class ScanService
{
    public function run(string $url, int $maxPages, int $depth, string $userAgent): Scan
    {
        return DB::transaction(function () use ($url, $maxPages, $depth, $userAgent) {
            $scan = Scan::create([
                'url'        => $url,
                'started_at' => Carbon::now(),
                'permalink'  => Uuid::uuid4()->toString(),
            ]);

            $results = Crawler::create()
                ->setUserAgent($userAgent)
                ->setParseableMimeTypes(['text/html'])
                ->ignoreRobots(false)
                ->maxDepth($depth)
                ->setMaximumCrawlCount($maxPages)
                ->doNotCrawlSubdomains()
                ->startCrawling($url);

            $phpAlly = new PhpAlly();

            $results->each(function (CrawlResults $result) use ($scan, $phpAlly) {
                $response = $result->response;

                if ($response === null || $response->getStatusCode() >= 300) {
                    return;
                }

                $page = Page::create([
                    'scan_id'     => $scan->id,
                    'url'         => (string) $result->url,
                    'status_code' => $response->getStatusCode(),
                    'title'       => $this->extractTitle((string) $response->getBody()),
                ]);

                $audit = $phpAlly->evaluateHtml((string) $response->getBody(), (string) $result->url);
                $issues = $this->mapAuditToIssues($audit, $page->id);

                $page->passed       = $issues->isEmpty();
                $page->issues_total = $issues->count();
                $page->save();
            });

            $scan->finished_at  = Carbon::now();
            $scan->summary_json = $this->buildSummary($scan);
            $scan->save();

            return $scan;
        });
    }

    // --- Hilfsfunktionen (nur Platzhalter) ---
    protected function extractTitle(string $html): string
    {
        if (preg_match('/<title>(.*?)<\/title>/i', $html, $m)) {
            return trim($m[1]);
        }
        return '';
    }

    protected function mapAuditToIssues(array $audit, int $pageId)
    {
        return collect($audit)->map(function ($issue) use ($pageId) {
            return Issue::create([
                'page_id'  => $pageId,
                'criterion'=> $issue['label'] ?? '',
                'severity' => $issue['type'] ?? 'notice',
                'message'  => $issue['message'] ?? '',
                'xpath'    => $issue['xpath'] ?? '',
                'snippet'  => $issue['html'] ?? '',
            ]);
        });
    }

    protected function buildSummary(Scan $scan): array
    {
        $totalPages   = $scan->pages()->count();
        $totalIssues  = Issue::whereIn('page_id', $scan->pages()->pluck('id'))->count();
        $passedPages  = $scan->pages()->where('passed', true)->count();

        return [
            'total_pages'  => $totalPages,
            'total_issues' => $totalIssues,
            'passed_pages' => $passedPages,
        ];
    }
}
