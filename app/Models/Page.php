<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Page extends Model
{
    use HasFactory;

    protected $fillable = [
        'scan_id',
        'url',
        'status_code',
        'title',
        'passed',
        'issues_total',
    ];

    protected $casts = [
        'passed' => 'boolean',
    ];

    public function scan(): BelongsTo
    {
        return $this->belongsTo(Scan::class);
    }

    public function issues(): HasMany
    {
        return $this->hasMany(Issue::class);
    }
}
