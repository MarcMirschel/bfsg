<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Issue extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'page_id',
        'criterion',
        'severity',
        'message',
        'xpath',
        'snippet',
    ];

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }
}
