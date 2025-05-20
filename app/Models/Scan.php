<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Scan extends Model
{
    use HasFactory;

    protected $fillable = ['url', 'permalink', 'started_at', 'finished_at', 'summary_json'];

    protected $casts = [
        'summary_json' => 'array',
        'started_at'   => 'datetime',
        'finished_at'  => 'datetime',
    ];

    public function pages(): HasMany
    {
        return $this->hasMany(Page::class);
    }

    public function permalinkUrl(): string
    {
        return route('report.web', $this->permalink);
    }
}
