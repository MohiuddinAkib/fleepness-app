<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Database\Factories\SizeTemplateItemFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SizeTemplateItem extends Model
{
    /** @use HasFactory<SizeTemplateItemFactory> */
    use HasFactory;

    /** @var list<string> */
    protected $fillable = [
        'size_template_id',
        'label',
        'value',
    ];

    /** @return BelongsTo<SizeTemplate, $this> */
    public function sizeTemplate(): BelongsTo
    {
        return $this->belongsTo(SizeTemplate::class);
    }
}
