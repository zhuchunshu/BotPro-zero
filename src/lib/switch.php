<?php
return [
    [
        "id" => "zero_switch_txfanyi",
        "name" => "腾讯云翻译",
        'status' => get_options("zero_switch_txfanyi"),
    ],
    [
        "id" => "zero_switch_baiducontent",
        "name" => "百度智能云内容审核(群员对消息的举报)",
        'status' => get_options("zero_switch_baiducontent"),
    ],
    [
        "id" => "zero_switch_event_friend",
        "name" => "加好友自动通过",
        "status" => get_options("zero_switch_event_friend")
    ],
    [
        "id" => "zero_switch_event_friend_nopass",
        "name" => "加好友自动拒绝",
        "status" => get_options("zero_switch_event_friend_nopass")
    ],
    [
        "id" => "zero_switch_event_addGroup",
        "name" => "加群自动审批",
        "status" => get_options("zero_switch_event_addGroup")
    ],
    [
        "id" => "zero_switch_event_Group_shouyaoTy",
        "name" => "受邀自动同意",
        "status" => get_options("zero_switch_event_Group_shouyaoTy")
    ],
    [
        "id" => "zero_switch_event_Group_shouyaoJJ",
        "name" => "受邀自动拒绝",
        "status" => get_options("zero_switch_event_Group_shouyaoJJ")
    ],
    [
        "id" => "zero_switch_event_GroupAdmin_notice",
        "name" => "群管理员变动通知",
        "status" => get_options("zero_switch_event_GroupAdmin_notice")
    ],
    [
        "id" => "zero_switch_event_GroupUser_Snotice",
        "name" => "群成员减少通知",
        "status" => get_options("zero_switch_event_GroupUser_Snotice")
    ],
    [
        "id" => "zero_switch_event_GroupUser_Add_notice",
        "name" => "群成员增加通知",
        "status" => get_options("zero_switch_event_GroupUser_Add_notice")
    ],
];
