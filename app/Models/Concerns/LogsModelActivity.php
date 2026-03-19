<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use Illuminate\Support\Str;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

trait LogsModelActivity
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName(Str::of(class_basename(static::class))->snake()->toString())
            ->logAll()
            ->logExcept($this->activityLogExcludedAttributes())
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(
                fn (string $eventName): string => Str::of(class_basename(static::class))
                    ->headline()
                    ->append(' ', $eventName)
                    ->lower()
                    ->toString()
            );
    }

    /** @return list<string> */
    protected function activityLogExcludedAttributes(): array
    {
        $attributes = [
            ...$this->getHidden(),
            $this->getCreatedAtColumn(),
            $this->getUpdatedAtColumn(),
        ];

        if (method_exists($this, 'getDeletedAtColumn')) {
            $attributes[] = $this->getDeletedAtColumn();
        }

        return array_values(array_unique(array_filter($attributes)));
    }
}
