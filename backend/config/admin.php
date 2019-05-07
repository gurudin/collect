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
                ['label' => '卡片组', 'href' => '/admin.cms/card-group'],
                ['label' => '卡片列表', 'href' => '/admin.cms/card'],
            ]
        ],
        [
            'icon'  => 'fas fa-users',
            'label' => '用户管理',
            'href'  => '#',
            'badge' => '',
            'open'  => false,
            'child' => [
                ['label' => '用户列表', 'href' => '/admin.cms/member'],
            ]
        ],
        [
            'icon' => 'fas fa-store',
            'label' => '交换管理',
            'href' => '#',
            'badge' => '',
            'open' => false,
            'child' => [
                ['label' => '交换列表', 'href' => '/admin.cms/store'],
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

    /**
     * 缓存key
     */
    'cache' => [
        'groups' => 'groups', // 卡片组
        'cards'  => 'cards', // 卡片
    ],
];
