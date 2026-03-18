<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Database\Factories\ShortVideoSaveFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ShortVideoSave extends Model
{
    /** @use HasFactory<ShortVideoSaveFactory> */
    use HasFactory;

    /** @var list<string> */
    protected $fillable = [
        'short_video_id',
        'user_id',
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
