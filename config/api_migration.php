<?php

declare(strict_types=1);

return [
    'legacy_endpoints' => [
        'me.role' => [
            'path' => '/api/me/role',
            'replacements' => ['/api/v1/me/summaries', '/api/v1/me/vendors'],
        ],
        'seller.status' => [
            'path' => '/api/seller/status',
            'replacements' => ['/api/v1/me/summaries', '/api/v1/vendor-applications/status', '/api/v1/me/vendors'],
        ],
        'notifications.index' => [
            'path' => '/api/notifications',
            'replacements' => ['/api/v1/me/notifications'],
        ],
        'notifications.mark-all-as-read' => [
            'path' => '/api/notifications/mark-as-read',
            'replacements' => ['/api/v1/me/notifications/read'],
        ],
        'notifications.mark-as-read' => [
            'path' => '/api/notifications/{notification}/mark-as-read',
            'replacements' => ['/api/v1/me/notifications/{notification}/read'],
        ],
        'addresses.default' => [
            'path' => '/api/addresses/default',
            'replacements' => ['/api/v1/me/addresses'],
        ],
        'addresses.set-default' => [
            'path' => '/api/addresses/{address}/set-default',
            'replacements' => ['/api/v1/me/addresses/{address}/default'],
        ],
        'following.index' => [
            'path' => '/api/following',
            'replacements' => ['/api/v1/me/followings'],
        ],
        'followers.index' => [
            'path' => '/api/followers',
            'replacements' => ['/api/v1/me/vendors/followers'],
        ],
        'user.balance-stats' => [
            'path' => '/api/user/balance-stats',
            'replacements' => ['/api/v1/me/balances'],
        ],
        'shorts.saved' => [
            'path' => '/api/shorts/saved',
            'replacements' => ['/api/v1/me/short-videos/saved'],
        ],
        'lives.liked' => [
            'path' => '/api/lives/liked',
            'replacements' => ['/api/v1/me/livestreams/liked'],
        ],
        'lives.saved' => [
            'path' => '/api/lives/saved',
            'replacements' => ['/api/v1/me/livestreams/saved'],
        ],
        'lives.likes-count' => [
            'path' => '/api/lives/{livestream}/likes-count',
            'replacements' => ['/api/v1/livestreams/{livestream}'],
        ],
    ],
];
