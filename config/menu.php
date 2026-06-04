<?php

return [
    'admin-menu-sections' => [
        'main' => [
            'title' => 'Hệ thống',
            'role' => ['admin'],
            'items' => [
                [
                    'label' => 'Nội dung landing',
                    'route' => 'admin.lang-ui.index',
                    'active_route_prefix' => 'admin.lang-ui.',
                    'active_route_exclude_prefix' => 'admin.lang-ui.contribute',
                    'svg' => '<path d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418"/>',
                ],
                [
                    'label' => 'Chung sức',
                    'route' => 'admin.lang-ui.contribute.index',
                    'active_route_prefix' => 'admin.lang-ui.contribute',
                    'svg' => '<path d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z"/>',
                ],
            ],
        ],
        'account' => [
            'title' => 'Tài khoản',
            'role' => ['admin'],
            'items' => [
                [
                    'label' => 'Đăng xuất',
                    'route' => 'admin.logout',
                    'svg' => '<path d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75"/>',
                ],
            ],
        ],
    ],
];
