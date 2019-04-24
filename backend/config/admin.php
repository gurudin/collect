<?php
return [
    'pageSize' => 20,
    'nav' => [
        [
            'icon' => 'fas fa-credit-card',
            'label' => '卡片管理',
            'href'  => '#',
            'badge' => '',
            'open'  => false,
            'child' => [
                ['label' => 'Recomment', 'href' => '/admin.cms/recomment/list'],
            ]
        ],
    ]
];
