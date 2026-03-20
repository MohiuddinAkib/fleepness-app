<?php

declare(strict_types=1);

arch('no debugging functions in application code')
    ->expect(['dd', 'dump', 'var_dump', 'ray'])
    ->not->toBeUsedIn([
        'App\Http\Controllers',
        'App\Models',
        'App\Data',
        'App\Services',
        'App\Notifications',
        'App\Jobs',
    ]);

arch('strict types declared everywhere')
    ->expect('App')
    ->toUseStrictTypes();

arch('models extend Eloquent Model')
    ->expect('App\Models')
    ->classes()
    ->toExtend('Illuminate\Database\Eloquent\Model')
    ->ignoring('App\Models\Concerns');

arch('models are not final')
    ->expect('App\Models')
    ->classes()
    ->not->toBeFinal()
    ->ignoring('App\Models\Concerns');

arch('enums are backed enums')
    ->expect('App\Enums')
    ->toBeStringBackedEnums();

arch('controllers have correct suffix')
    ->expect('App\Http\Controllers')
    ->toHaveSuffix('Controller');

arch('Data classes extend Spatie Data')
    ->expect('App\Data')
    ->toExtend('Spatie\LaravelData\Data');

arch('notifications extend Laravel Notification')
    ->expect('App\Notifications')
    ->toExtend('Illuminate\Notifications\Notification');

arch('services do not inject Request')
    ->expect('App\Services')
    ->not->toUse('Illuminate\Http\Request');

arch('jobs implement ShouldQueue')
    ->expect('App\Jobs')
    ->toImplement('Illuminate\Contracts\Queue\ShouldQueue');
