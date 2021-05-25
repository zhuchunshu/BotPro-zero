<?php
namespace App\Plugins\zero\src\message\event\notice;

/**
 * 群成员减少通知
 */
class group_decrease {
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
        if(get_options("zero_switch_event_GroupUser_Snotice")){
            if($this->data->sub_type=="leave"){
                sendMsg([
                    "group_id" => $this->data->group_id,
                    "message" => "QQ:".$this->data->user_id."退出了本群,我祝他全家身体健康,祝它在网络的道路上一路长虹,平平安安"
                ],"send_group_msg");
            }
            if($this->data->sub_type=="kick"){
                sendMsg([
                    "group_id" => $this->data->group_id,
                    "message" => "QQ:".$this->data->user_id."被[CQ:at,qq=".$this->data->operator_id."]踢退出了本群"
                ],"send_group_msg");
            }
        }
    }
}