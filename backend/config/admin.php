<?php
return [
    /**
     * 默认分页
     */
    'pageSize' => 20,

    /**
     * 菜单
     */
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

    /**
     * 性别默认头像 0:未知 1:男 2:女
     */
    'avatar' => [
        '/storage/avatar/default.png',
        '/storage/avatar/male.png',
        '/storage/avatar/female.png',
    ],

    /**
     * 日志类型
     */
    'log_type' => [
        '1' => '抽取卡片',
        '2' => '分享',
        '3' => '广告',
        '4' => '分解卡片',
        '5' => '获取碎片',
        '6' => '物品交换',
        '7' => '整点登陆',
    ],
];
