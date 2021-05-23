<?php
namespace App\Plugins\zero\src\message\group\Send;

use App\Models\BotCore;
use Illuminate\Support\Str;
use App\Plugins\zero\src\Api\V1;
use johnnymast\Morsecode\Morsecode;
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
        $response = Http::get('http://music.163.com/api/search/pc?total=true&s='.$this->order[1].'&type=1&limit=9');
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
        if(!get_options("zero_switch_txfanyi")){
            $text = "翻译功能未开启";
        }else{
            if(get_options("腾讯云SECRETID") && get_options("腾讯云SECRETKEY")){
                if (@$this->order[2]) {
                    $target = BOOT_func()->language($this->order[2]);
                } else {
                    $target = "en";
                }
                $text = tcq()->Translate($this->order[1], $target)->TargetText;
            }else{
                $text = "翻译接口未配置";
            }
        }
        SendMsg([
            'group_id' => $this->data->group_id,
            'message' => $text
        ], 'send_group_msg');
    }
    public function P站好图()
    {
        if (@$this->orderCount >= 2) {
            if (@$this->order[1] == "稳") {
                sendMsg([
                    'group_id' => $this->data->group_id,
                    'message' => "[CQ:image,file=https://open.pixivic.net/wallpaper/pc/random?size=original&domain=https://i.pixiv.cat&webp=0&detail=1&time=" . time() . "]"
                ], "send_group_msg");
            }else{
                sendMsg([
                    'group_id' => $this->data->group_id,
                    'message' => "[CQ:cardimage,file=https://open.pixivic.net/wallpaper/pc/random?size=original&domain=https://i.pixiv.cat&webp=0&detail=1&time=" . time() . "]"
                ], "send_group_msg");
            }
        } else {
            sendMsg([
                'group_id' => $this->data->group_id,
                'message' => "[CQ:cardimage,file=https://open.pixivic.net/wallpaper/pc/random?size=original&domain=https://i.pixiv.cat&webp=0&detail=1&time=" . time() . "]"
            ], "send_group_msg");
        }
    }
    public function 禁言()
    {
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
            if ($this->orderCount >= 4) {
                $duration = $this->order[2];
                if (is_numeric($duration)) {
                    switch ($this->order[3]) {
                        case '分钟':
                            $duration = $duration*60;
                            break;
                        case '小时':
                            $duration = $duration*60*60;
                            break;
                        case '天':
                            $duration = $duration*60*60*24;
                            break;
                        default:
                            $duration = $duration*1;
                            break;
                    }
                    sendMsg([
                        'group_id' => $this->data->group_id,
                        'user_id' => cq_at_qq($this->data->message),
                        'duration' => $duration
                    ], "set_group_ban");
                } else {
                    sendMsg([
                        'group_id' => $this->data->group_id,
                        'message' => "禁言时长必须是数字"
                    ], "send_group_msg");
                }
            } else {
                sendMsg([
                    'group_id' => $this->data->group_id,
                    'message' => "条件不满足，用法:
    禁言#@被禁言的人#时长#时间格式
    禁言#@张三#60#秒(分钟、小时、天)"
                ], "send_group_msg");
            }
        }else{
            sendMsg([
                'group_id' => $this->data->group_id,
                'message' => "[CQ:reply,id={$this->data->message_id}]无权操作"
            ], "send_group_msg");
        }
    }
    public function 踢(){
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
            if ($this->orderCount >= 2) {
                $lahei = false;
                if(@$this->order[2]=="拉黑"){
                    $lahei = true;
                }
                $qq = cq_at_qq($this->data->message);
                if(is_numeric($qq)){
                    sendMsg([
                        'group_id' => $this->data->group_id,
                        'user_id' => $qq,
                        'reject_add_request' => $lahei
                    ], "set_group_kick");
                    sendMsg([
                        'group_id' => $this->data->group_id,
                        'message' => "[CQ:reply,id={$this->data->message_id}]已将 {$qq} 移出本群"
                    ], "send_group_msg");
                }else{
                    sendMsg([
                        'group_id' => $this->data->group_id,
                        'message' => "条件不满足，用法:
        踢#@被踢的人#(拉黑)"
                    ], "send_group_msg");
                }
            } else {
                sendMsg([
                    'group_id' => $this->data->group_id,
                    'message' => "条件不满足，用法:
    踢#@被踢的人#(拉黑)"
                ], "send_group_msg");
            }
        }else{
            sendMsg([
                'group_id' => $this->data->group_id,
                'message' => "[CQ:reply,id={$this->data->message_id}]无权操作"
            ], "send_group_msg");
        }
    }
    public function 设置头衔(){
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
            if ($this->orderCount >= 4) {
                // 0 = 设置头衔
                // 1 = qq
                // 2 = 内容
                // 3 = 时长
                // 4 = (单位)
                $qq = cq_at_qq($this->data->message);
                if(is_numeric($this->order[3])){
                    // 是数字 
                    $duration = $this->order[3];
                    if($this->orderCount >= 5){
                        switch ($this->order[4]) {
                            case '分钟':
                                $duration = $duration*60;
                                break;
                            case '小时':
                                $duration = $duration*60*60;
                                break;
                            case '天':
                                $duration = $duration*60*60*24;
                                break;
                            default:
                                $duration = $duration*1;
                                break;
                        }
                    }else{
                        sendMsg([
                            'group_id' => $this->data->group_id,
                            'message' => "[CQ:reply,id={$this->data->message_id}]条件不满足,可能缺少时间单位,举个🌰: 设置头衔#@张三#头衔内容#1#小时(秒,分钟,小时,天) 或者: 设置头衔#@张三#头衔内容#永久"
                        ], "send_group_msg");
                    }
                }else{
                    // 不是数字 永久
                    $duration = -1;
                }
                sendData([
                    'group_id' => $this->data->group_id,
                    'user_id' => $qq,
                    "special_title" => $this->order[2],
                    "duration" => $duration
                ], "set_group_special_title");
                sendMsg([
                    'group_id' => $this->data->group_id,
                    'message' => "[CQ:reply,id={$this->data->message_id}]搞定(如果无效果,请确保机器人是群主)"
                ], "send_group_msg");
            }else{
                sendMsg([
                    'group_id' => $this->data->group_id,
                    'message' => "[CQ:reply,id={$this->data->message_id}]条件不满足,举个栗子: 设置头衔#@张三#头衔内容#1#小时(秒,分钟,小时,天) 或者: 设置头衔#@张三#头衔内容#永久"
                ], "send_group_msg");
            }
        }else{
            sendMsg([
                'group_id' => $this->data->group_id,
                'message' => "[CQ:reply,id={$this->data->message_id}]无权操作"
            ], "send_group_msg");
        }
    }

    public function 收录查询(){
        $result = V1::WebSiteShoulu($this->order[1]);
        if(is_array($result)){
            sendMsg([
                'group_id' => $this->data->group_id,
                'message' => "[CQ:reply,id={$this->data->message_id}]查询域名:{$result['Domain']}
百度收录:{$result['BaiDu']}
百度索引:{$result['BaiDuIndex']}
360收录:{$result['SoCom']}
搜狗收录:{$result['Sogou']}
神马收录:{$result['SmCn']}"
            ], "send_group_msg");
        }else{
            sendMsg([
                'group_id' => $this->data->group_id,
                'message' => "[CQ:reply,id={$this->data->message_id}]".$result
            ], "send_group_msg");
        }
    }
    public function md5(){
        sendMsg([
            'group_id' => $this->data->group_id,
            'message' => "[CQ:reply,id={$this->data->message_id}]".md5($this->order[1])
        ], "send_group_msg");
    }
    public function base64加密(){
        sendMsg([
            'group_id' => $this->data->group_id,
            'message' => "[CQ:reply,id={$this->data->message_id}]".base64_encode($this->order[1])
        ], "send_group_msg");
    }
    public function base64解密(){
        sendMsg([
            'group_id' => $this->data->group_id,
            'message' => "[CQ:reply,id={$this->data->message_id}]".base64_decode($this->order[1])
        ], "send_group_msg");
    }
    public function 藏头诗(){
        if (preg_match("/^[\x7f-\xff]+$/", $this->order[1])) {
            sendMsg([
                'group_id' => $this->data->group_id,
                'message' => "[CQ:reply,id={$this->data->message_id}]".V1::Cangtoushi($this->order[1])
            ], "send_group_msg");
        }else{
            sendMsg([
                'group_id' => $this->data->group_id,
                'message' => "[CQ:reply,id={$this->data->message_id}]藏头诗内容必须为中文"
            ], "send_group_msg");
        }
    }
    public function 手机号价格评估(){
        if(is_numeric($this->order[1])){
            if(Str::length($this->order[1])==11){
                sendMsg([
                    'group_id' => $this->data->group_id,
                    'message' => "[CQ:reply,id={$this->data->message_id}]".V1::Shpujihaojiage($this->order[1])
                ], "send_group_msg");
            }else{
                sendMsg([
                    'group_id' => $this->data->group_id,
                    'message' => "[CQ:reply,id={$this->data->message_id}]手机号格式不正确"
                ], "send_group_msg");
            }
        }else{
            sendMsg([
                'group_id' => $this->data->group_id,
                'message' => "[CQ:reply,id={$this->data->message_id}]手机号必须是数字"
            ], "send_group_msg");
        }
    }
    public function 哔哩哔哩av转bv(){
        sendMsg([
            'group_id' => $this->data->group_id,
            'message' => "[CQ:reply,id={$this->data->message_id}]".V1::Bili_bv($this->order[1])
        ], "send_group_msg");
    }
    public function 哔哩哔哩bv转av(){
        sendMsg([
            'group_id' => $this->data->group_id,
            'message' => "[CQ:reply,id={$this->data->message_id}]".V1::Bili_aid($this->order[1])
        ], "send_group_msg");
    }
    public function 摩斯电码(){
        $morse = new Morsecode();
        if (preg_match("/[\x7f-\xff]/", $this->order[1])) {
            sendMsg([
                'group_id' => $this->data->group_id,
                'message' => "[CQ:reply,id={$this->data->message_id}]加密内容不能包含中文"
            ], "send_group_msg");
        }else{
            sendMsg([
                'group_id' => $this->data->group_id,
                'message' => "[CQ:reply,id={$this->data->message_id}]".$morse->encode($this->order[1])
            ], "send_group_msg");
        }
    }
    public function 解摩斯电码(){
        $morse = new Morsecode();
        sendMsg([
            'group_id' => $this->data->group_id,
            'message' => "[CQ:reply,id={$this->data->message_id}]".$morse->decode($this->order[1])
        ], "send_group_msg");
    }
    public function 备案查询(){
        $result = V1::chaicp($this->order[1]);
        if($result){
            $data = $result[0];
            sendMsg([
                'group_id' => $this->data->group_id,
                'message' => "[CQ:reply,id={$this->data->message_id}]域名:{$data['domain']}
首页:{$data['homeUrl']}
备案号:{$data['mainLicence']}
备案主体:{$data['unitName']}
网站名:{$data['serviceName']}"
            ], "send_group_msg");
        }else{
            sendMsg([
                'group_id' => $this->data->group_id,
                'message' => "[CQ:reply,id={$this->data->message_id}]没查出来"
            ], "send_group_msg");
        }
    }
}