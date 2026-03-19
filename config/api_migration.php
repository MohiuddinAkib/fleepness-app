<?php

declare(strict_types=1);

return [
    'legacy_endpoints' => [
        'me.role' => [
            'path' => '/api/me/role',
            'replacements' => ['/api/me', '/api/me/vendors'],
        ],
        'seller.status' => [
            'path' => '/api/seller/status',
            'replacements' => ['/api/vendor-applications/status', '/api/me/vendors'],
        ],
        'notifications.index' => [
            'path' => '/api/notifications',
            'replacements' => ['/api/me/notifications'],
        ],
        'notifications.mark-all-as-read' => [
            'path' => '/api/notifications/mark-as-read',
            'replacements' => ['/api/me/notifications/read'],
        ],
        'notifications.mark-as-read' => [
            'path' => '/api/notifications/{notification}/mark-as-read',
            'replacements' => ['/api/me/notifications/{notification}/read'],
        ],
        'addresses.default' => [
            'path' => '/api/addresses/default',
            'replacements' => ['/api/me/addresses'],
        ],
        'addresses.set-default' => [
            'path' => '/api/addresses/{address}/set-default',
            'replacements' => ['/api/me/addresses/{address}/default'],
        ],
        'following.index' => [
            'path' => '/api/following',
            'replacements' => ['/api/me/followings'],
        ],
        'followers.index' => [
            'path' => '/api/followers',
            'replacements' => ['/api/me/vendors/followers'],
        ],
        'user.balance-stats' => [
            'path' => '/api/user/balance-stats',
            'replacements' => ['/api/me/balances'],
        ],
        'shorts.saved' => [
            'path' => '/api/shorts/saved',
            'replacements' => ['/api/me/short-videos/saved'],
        ],
        'lives.liked' => [
            'path' => '/api/lives/liked',
            'replacements' => ['/api/me/livestreams/liked'],
        ],
        'lives.saved' => [
            'path' => '/api/lives/saved',
            'replacements' => ['/api/me/livestreams/saved'],
        ],
        'lives.likes-count' => [
            'path' => '/api/lives/{livestream}/likes-count',
            'replacements' => ['/api/livestreams/{livestream}'],
        ],
    ],
];
