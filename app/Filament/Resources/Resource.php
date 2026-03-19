<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use Filament\Resources\Resource as BaseResource;
use Rmsramos\Activitylog\RelationManagers\ActivitylogRelationManager;

abstract class Resource extends BaseResource
{
    public static function getRelations(): array
    {
        return [
            ActivitylogRelationManager::class,
        ];
    }
}
