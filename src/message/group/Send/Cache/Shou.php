<?php
namespace App\Plugins\zero\src\message\group\Send\Cache;

class Shou{
    public $data;
    public $order;
    public $orderCount;
    private $ban = [
        'start'
    ];
    public function __construct($data)
    {
        $this->data = $data;
        $this->order = $order = GetZhiling($data,"#");
        $this->orderCount = count($order);
    }
    public function 秀图()
    {
        sendMsg([
            'group_id' => $this->data->group_id,
            'message' => "请发送图片"
        ], "send_group_msg");
    }
    public function 图片链接()
    {
        sendMsg([
            'group_id' => $this->data->group_id,
            'message' => "请发送图片"
        ], "send_group_msg");
    }
    public function 路由追踪(){
        sendMsg([
            'group_id' => $this->data->group_id,
            'message' => "请在60秒内发送域名或IP"
        ], "send_group_msg");
    }
}