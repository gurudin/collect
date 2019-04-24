<?php
return [
    'pageSize' => 20,
    'nav' => [
        [
            'icon' => 'fas fa-sitemap',
            'label' => 'Site',
            'href'  => '#',
            'badge' => '',
            'open'  => false,
            'child' => [
                ['label' => 'Recomment', 'href' => '/admin.cms/recomment/list'],
            ]
        ],
    ]
];
