<?php
namespace App\Plugins\zero\src\message\group\Send\Cache;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use App\Plugins\zero\src\message\group\Jobs\traceJob;

class Fa{
    public $data;
    public $order;
    public $orderCount;
    private $ban = [
        'start'
    ];
    public function __construct($data)
    {
        $this->data = $data;
        $this->order = GetZhiling($data,"#");
        $this->orderCount = count($this->order);
    }
    public function 秀图()
    {
        $img = Str::before($this->order[0], ']');
        $img = Str::after($img, 'url=');
        sendMsg([
            'group_id' => $this->data->group_id,
            'message' => "[CQ:image,file=" . $img . ",type=show,id=40000]"
        ], "send_group_msg");
    }
    public function 图片链接()
    {
        $img_url = Str::before($this->order[0], ']');
        $img_url = Str::after($img_url, 'url=');
        sendMsg([
            'group_id' => $this->data->group_id,
            'message' => "[CQ:image,file=" . $img_url . "]图片链接为:" . $img_url
        ], "send_group_msg");
    }
    public function 路由追踪()
    {
        dispatch(new traceJob($this->data,$this->order));
        sendMsg([
            'group_id' => $this->data->group_id,
            'message' => "[CQ:reply,id=".$this->data->message_id."]脚本已执行,请耐心等待"
        ], "send_group_msg");
        Cache::forget($this->data->group_id . "_" . $this->data->user_id);
    }
}