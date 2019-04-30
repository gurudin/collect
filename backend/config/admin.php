<?php
return [
    'pageSize' => 20,
    'nav' => [
        [
            'icon'  => 'fas fa-credit-card',
            'label' => '卡片管理',
            'href'  => '#',
            'badge' => '',
            'open'  => false,
            'child' => [
                ['label' => 'Card Group', 'href' => '/admin.cms/card-group'],
                ['label' => 'Cards', 'href' => '/admin.cms/card'],
            ]
        ],
        [
            'icon'  => 'fas fa-users',
            'label' => '用户管理',
            'href'  => '#',
            'badge' => '',
            'open'  => false,
            'child' => [
                ['label' => 'Users', 'href' => '/admin.cms/member'],
            ]
        ],
        [
            'icon' => 'fas fa-store',
            'label' => '交换管理',
            'href' => '#',
            'badge' => '',
            'open' => false,
            'child' => [
                ['label' => 'Store', 'href' => '/admin.cms/store'],
            ]
        ]
    ],
    'avatar' => [
        '/storage/avatar/default.png',
        '/storage/avatar/male.png',
        '/storage/avatar/female.png',
    ]
];
