<?php
namespace App\Plugins\zero\src\message\group\Send;

use Illuminate\Support\Str;


class After{

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

    public function 翻译(){
        $msg_id = Str::before($this->order[0], ']');
        $msg_id = Str::after($msg_id, 'id=');
        $text = sendData(['message_id'=>$msg_id], 'get_msg')['data']['message'];
        if(is_string($text)){
            if(@$this->order[2]){
                $target = BOOT_func()->language($this->order[2]);
            }else{
                $target = "zh";
            }
            $text = tcq()->Translate($text,$target)->TargetText;
            sendMsg([
                'group_id' => $this->data->group_id,
                'message' => "[CQ:reply,id=" . $this->data->message_id . "]".$text
            ],'send_group_msg');
        }else{
            sendMsg([
                'group_id' => $this->data->group_id,
                'message' => "被翻译内容为无效的字符串"
            ], "send_group_msg");
        }
    }

}