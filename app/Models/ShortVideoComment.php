<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Database\Factories\ShortVideoCommentFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ShortVideoComment extends Model
{
    /** @use HasFactory<ShortVideoCommentFactory> */
    use HasFactory;

    /** @var list<string> */
    protected $fillable = [
        'short_video_id',
        'user_id',
        'comment',
    ];

    /** @return BelongsTo<ShortVideo, $this> */
    public function shortVideo(): BelongsTo
    {
        return $this->belongsTo(ShortVideo::class);
    }

    /** @return BelongsTo<User, $this> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
