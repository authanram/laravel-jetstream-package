<?php

return [

    'paths' => [
        app_path('Actions') => app_path('Jetstream/Actions'),
        app_path('Events') => app_path('Jetstream/Events'),
        app_path('Http/Middleware/HandleInertiaRequests.php') => app_path('Jetstream/Http/Middleware/HandleInertiaRequests.php'),
        app_path('Models/Membership.php') => app_path('Jetstream/Models/Membership.php'),
        app_path('Models/Team.php') => app_path('Jetstream/Models/Team.php'),
        app_path('Models/TeamInvitation.php') => app_path('Jetstream/Models/TeamInvitation.php'),
        app_path('Policies') => app_path('Jetstream/Policies'),
        app_path('Providers/FortifyServiceProvider.php') => app_path('Jetstream/Providers/FortifyServiceProvider.php'),
        app_path('Providers/JetstreamServiceProvider.php') => app_path('Jetstream/Providers/JetstreamServiceProvider.php'),
        app_path('View/Components') => app_path('Jetstream/View/Components'),
        resource_path('views/api') => resource_path('views/jetstream/api'),
        resource_path('views/auth') => resource_path('views/jetstream/auth'),
        resource_path('views/layouts') => resource_path('views/jetstream/layouts'),
        resource_path('views/profile') => resource_path('views/jetstream/profile'),
        resource_path('views/teams') => resource_path('views/jetstream/teams'),
        resource_path('views/app.blade.php') => resource_path('views/jetstream/app.blade.php'),
        resource_path('views/dashboard.blade.php') => resource_path('views/jetstream/dashboard.blade.php'),
        resource_path('views/navigation-menu.blade.php') => resource_path('views/jetstream/navigation-menu.blade.php'),
        resource_path('views/policy.blade.php') => resource_path('views/jetstream/policy.blade.php'),
        resource_path('views/terms.blade.php') => resource_path('views/jetstream/terms.blade.php'),
    ],

    'replace' => [
        base_path('composer.json') => [
            [
                'search' => <<<'EOF'
    "autoload": {
        "psr-4": {
            "App\\": "app/",
            "Database\\Factories\\": "database/factories/",
            "Database\\Seeders\\": "database/seeders/"
        }
    },
EOF,
                'replace' => <<<'EOF'
    "autoload": {
        "psr-4": {
            "App\\": ["app/", "app/Jetstream/"],
            "Database\\Factories\\": "database/factories/",
            "Database\\Seeders\\": "database/seeders/"
        },
        "classmap": [
            "app/Jetstream"
        ]
    },
EOF,
            ],
        ],

        config_path('view.php') => [
            [
                'search' => <<<'EOF'
    'paths' => [
        resource_path('views'),
    ],
EOF,
                'replace' => <<<'EOF'
    'paths' => [
        resource_path('views'),
        resource_path('views/jetstream'),
    ],
EOF,
            ],
        ],
    ],

];
