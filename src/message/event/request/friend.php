<?php
namespace App\Plugins\zero\src\message\event\request;

use App\Jobs\BotPro\SendMsg;

class friend {

     /**
     * 接收到的数据
     *
     * @var object
     */
    public $data;

    /**
     * 插件信息
     *
     * @var array
     */
    public $value;

    /**
     * 注册方法
     *
     * @param object 接收到的数据 $data
     * @param array 插件信息 $value
     * @return void
     */
    public function register($data,$value){
        $this->data = $data;
        $this->value = $value;
        $this->boot();
    }

    public function boot(){
        if(get_options("zero_switch_event_friend_nopass")){
            SendMsg([
                "flag" => $this->data->flag,
                "approve" => false
            ],"set_friend_add_request");
        }else{
            if(get_options("zero_switch_event_friend")){
                SendMsg([
                    "flag" => $this->data->flag,
                    "approve" => true
                ],"set_friend_add_request");
            }
        }
    }

}