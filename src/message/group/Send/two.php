<?php
namespace App\Plugins\zero\src\message\group\Send;

use Illuminate\Support\Facades\Http;
use App\Plugins\zero\src\message\group\Jobs\pingJob;

class two {
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
    public function 发语音(){
        sendMsg([
            'group_id' => $this->data->group_id,
            'message' => "[CQ:tts,text=".$this->order[1]."]"
        ],'send_group_msg');
    }
    public function 网抑音乐(){
        $response = Http::get('http://musicapi.leanapp.cn/search', [
            'keywords' => $this->order[1],
        ]);
        $arr = $response->json();
        if ($arr['result']['songCount'] >= 1) {
            sendMsg([
                'group_id' => $this->data->group_id,
                'message' => "[CQ:music,type=163,id=" . $arr['result']['songs'][0]['id'] . "]"
            ], "send_group_msg");
        } else {
            sendMsg([
                'group_id' => $this->data->group_id,
                'message' => "网易失败,找不到此歌曲" . $arr['result']['songCount']
            ], "send_group_msg");
        }
    }
    public function ping(){
        sendMsg([
            'group_id' => $this->data->group_id,
            'message' => "[CQ:reply,id=" . $this->data->message_id . "]脚本已执行,请耐心等待"
        ], "send_group_msg");
        dispatch(new pingJob($this->data, $this->order));
    }
    public function 翻译(){
        if (@$this->order[2]) {
            $target = BOOT_func()->language($this->order[2]);
        } else {
            $target = "en";
        }
        $text = tcq()->Translate($this->order[1], $target)->TargetText;
        SendMsg([
            'group_id' => $this->data->group_id,
            'message' => $text
        ], 'send_group_msg');
    }
}