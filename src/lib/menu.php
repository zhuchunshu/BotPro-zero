<?php
return [
    [
        'id'            => 1, // 此id只要保证当前的数组中是唯一的即可
        'title'         => 'Zero插件',
        'icon'          => 'feather icon-book',
        'uri'           => 'zero',
        'parent_id'     => 0,
        'roles'         => 'administrator', // 与角色绑定
    ],
    [
        'id'            => 2, // 此id只要保证当前的数组中是唯一的即可
        'title'         => '插件信息',
        'icon'          => '',
        'uri'           => 'zero',
        'parent_id'     => 1,
        'roles'         => 'administrator', // 与角色绑定
    ],
    [
        'id'            => 3, // 此id只要保证当前的数组中是唯一的即可
        'title'         => '插件设置',
        'icon'          => '',
        'uri'           => 'zero/setting',
        'parent_id'     => 1,
        'roles'         => 'administrator', // 与角色绑定
    ],
];