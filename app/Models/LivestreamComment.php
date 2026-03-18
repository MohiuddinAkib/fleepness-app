<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Database\Factories\LivestreamCommentFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LivestreamComment extends Model
{
    /** @use HasFactory<LivestreamCommentFactory> */
    use HasFactory;

    /** @var list<string> */
    protected $fillable = [
        'livestream_id',
        'user_id',
        'comment',
    ];

    /** @return BelongsTo<Livestream, $this> */
    public function livestream(): BelongsTo
    {
        return $this->belongsTo(Livestream::class);
    }

    /** @return BelongsTo<User, $this> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
