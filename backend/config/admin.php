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
                ['label' => 'Card Group', 'href' => '/admin.cms/card-group'],
                ['label' => 'Cards', 'href' => '/admin.cms/card'],
            ]
        ],
    ]
];
