<?php

namespace App\Plugins\zero\src\message\group\Send;

use Illuminate\Support\Str;
use App\Plugins\zero\src\lib\aip\AipContentCensor;


class After
{

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

    public function 翻译()
    {
        // 检查是否开启此功能
        if (get_options("zero_switch_txfanyi")) {
            // 检查接口配置完整
            if (get_options("腾讯云SECRETID") && get_options("腾讯云SECRETKEY")) {
                $msg_id = Str::before($this->order[0], ']');
                $msg_id = Str::after($msg_id, 'id=');
                $text = sendData(['message_id' => $msg_id], 'get_msg')['data']['message'];
                // 检查翻译内容是否为字符串
                if (is_string($text)) {
                    if (@$this->order[2]) {
                        $target = BOOT_func()->language($this->order[2]);
                    } else {
                        $target = "zh";
                    }
                    $text = tcq()->Translate($text, $target)->TargetText;
                } else {
                    $text = "翻译内容不是有效的字符串";
                }
            } else {
                $text = "翻译接口未配置";
            }
        } else {
            $text = "翻译功能未开启";
        }
        sendMsg([
            'group_id' => $this->data->group_id,
            'message' => "[CQ:reply,id=" . $this->data->message_id . "]" . $text
        ], 'send_group_msg');
    }

    public function 举报()
    {
        // 检查是否开启此功能
        if(!get_options("zero_switch_baiducontent")){
            sendMsg([
                'group_id' => $this->data->group_id,
                'message' => "[CQ:reply,id=" . $this->data->message_id . "]举报功能未开启"
            ], 'send_group_msg');
        }else{
            // 检查配置是否完整
            if(get_options("百度内容审核APPID") && get_options("百度内容审核APIKEY") && get_options("百度内容审核SecretKey")){
                $client = new AipContentCensor(get_options("百度内容审核APPID"), get_options("百度内容审核APIKEY"), get_options("百度内容审核SecretKey"));
                $result = $client->textCensorUserDefined("测试文本");
                $msg_id = Str::before($this->order[0], ']');
                $msg_id = Str::after($msg_id, 'id=');
                $text = sendData(['message_id' => $msg_id], 'get_msg')['data']['message'];
                $sender_qq = sendData(['message_id' => $msg_id], 'get_msg')['data']['sender']['user_id'];
                // 检查举报内容是否为字符串
                if (is_string($text)) {
                    $result = $client->textCensorUserDefined($text);
                    if($result['conclusionType']==1){
                        sendMsg([
                            'group_id' => $this->data->group_id,
                            'message' => "[CQ:reply,id=" . $this->data->message_id . "]" . "经审核,举报内容合规故不作处罚"
                        ], 'send_group_msg');
                    }else{
                        $content = "共有 ".count($result['data'])." 个疑点
";
                        foreach ($result['data'] as $key => $value) {
                            $c = ($key+1).". ".$value['msg']."可疑内容包含:
";
                            if(@count($value['hits'][0]['words'])){
                                foreach ($value['hits'][0]['words'] as $values) {
                                    $c=$c.$values."    ";
                                }
                            }
                            $content = $content.$c."
";
                        }
                        $content = $content."一个疑点禁言10分钟,".count($result['data'])."个疑点奖励禁言:".(count($result['data'])*10)."分钟(确保机器人是管理员,不能处罚管理员和群主)\n\n我已将违规内容撤回";
                        sendData([
                            'group_id' => $this->data->group_id,
                            'user_id' => $sender_qq,
                            'duration' =>(count($result['data'])*10)*60
                        ], "set_group_ban");
                        sendData([
                            'message_id' => $msg_id,
                        ], "delete_msg");
                        sendMsg([
                            'group_id' => $this->data->group_id,
                            'message' => "[CQ:reply,id=" . $this->data->message_id . "]" . $content
                        ], 'send_group_msg');
                    }
                    
                } else {
                    sendMsg([
                        'group_id' => $this->data->group_id,
                        'message' => "[CQ:reply,id=" . $this->data->message_id . "]" . "举报内容不是有效的字符串"
                    ], 'send_group_msg');
                }
            }else{
                sendMsg([
                    'group_id' => $this->data->group_id,
                    'message' => "[CQ:reply,id=" . $this->data->message_id . "]" . "百度智能云内容审核接口配置不完整"
                ], 'send_group_msg');
            }
        }
    }
}
