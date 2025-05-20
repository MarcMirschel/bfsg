@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Accessibility‑Report für {{ $scan->url }}</h1>

    <div class="card my-4">
        <div class="card-body">
            <p><strong>Gesamtseiten:</strong> {{ $scan->summary_json['total_pages'] ?? '?' }}</p>
            <p><strong>Gesamt‑Issues:</strong> {{ $scan->summary_json['total_issues'] ?? '?' }}</p>
            <p><strong>Bestandene Seiten:</strong> {{ $scan->summary_json['passed_pages'] ?? '?' }}</p>
        </div>
    </div>

    <div class="accordion" id="pagesAccordion">
        @foreach ($scan->pages as $page)
            <div class="accordion-item">
                <h2 class="accordion-header" id="heading{{ $page->id }}">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapse{{ $page->id }}" aria-expanded="false"
                            aria-controls="collapse{{ $page->id }}">
                        {{ $page->title ?? $page->url }} ({{ $page->issues_total }} Issues)
                    </button>
                </h2>
                <div id="collapse{{ $page->id }}" class="accordion-collapse collapse"
                     aria-labelledby="heading{{ $page->id }}" data-bs-parent="#pagesAccordion">
                    <div class="accordion-body p-0">
                        <table class="table table-striped mb-0">
                            <thead>
                                <tr>
                                    <th>Kriterium</th>
                                    <th>Severity</th>
                                    <th>Nachricht</th>
                                    <th>Snippet</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($page->issues as $issue)
                                    <tr>
                                        <td>{{ $issue->criterion }}</td>
                                        <td><span class="badge bg-secondary">{{ $issue->severity }}</span></td>
                                        <td>{{ $issue->message }}</td>
                                        <td><code>{{ Str::limit($issue->snippet, 80) }}</code></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
