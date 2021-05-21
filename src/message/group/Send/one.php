<?php
namespace App\Plugins\zero\src\message\group\Send;

use App\Models\BotCore;


class one {
    /**
     * 接收到的数据
     *
     * @var object
     */
    public $data;

    /**
     * 指令
     *
     * @var array
     */
    public $order;

    /**
     * 指令数量
     *
     * @var integer
     */
    public $orderCount;

    /**
     * 插件信息
     *
     * @var array
     */
    public $PluginData;

    public function __construct($data, $order, $orderCount, $PluginData)
    {
        $this->data = $data;
        $this->order = $order;
        $this->orderCount = $orderCount;
        $this->PluginData = $PluginData;
    }
    public function 全群禁言(){
        $quanxian = false;
        if($this->data->sender->role=="admin"){
            $quanxian = true;
        }
        if($this->data->sender->role=="owner"){
            $quanxian = true;
        }
        if(BotCore::where(['type' => 'qq','value' => $this->data->user_id])->count()){
            $quanxian = true;
        }
        if($quanxian){
            sendData([
                'group_id' => $this->data->group_id,
                "enable" => true
            ],'set_group_whole_ban');
            sendMsg([
                'group_id' => $this->data->group_id,
                'message' => "[CQ:reply,id=" . $this->data->message_id . "]搞定"
            ], "send_group_msg");
        }else{
            sendMsg([
                'group_id' => $this->data->group_id,
                'message' => "[CQ:reply,id=" . $this->data->message_id . "]无权限"
            ], "send_group_msg");
        }
    }
    public function 全群解禁(){
        $quanxian = false;
        if($this->data->sender->role=="admin"){
            $quanxian = true;
        }
        if($this->data->sender->role=="owner"){
            $quanxian = true;
        }
        if(BotCore::where(['type' => 'qq','value' => $this->data->user_id])->count()){
            $quanxian = true;
        }
        if($quanxian){
            sendData([
                'group_id' => $this->data->group_id,
                "enable" => false
            ],'set_group_whole_ban');
            sendMsg([
                'group_id' => $this->data->group_id,
                'message' => "[CQ:reply,id=" . $this->data->message_id . "]搞定"
            ], "send_group_msg");
        }else{
            sendMsg([
                'group_id' => $this->data->group_id,
                'message' => "[CQ:reply,id=" . $this->data->message_id . "]无权限"
            ], "send_group_msg");
        }
    }
}