<?php
namespace App\Plugins\zero\src\message\event\request;

/**
 * 加群自动审批
 */
class group {

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
        $this->dengji();
    }

    // 通过等级审批
    public function dengji(){
        if(get_options("zero_switch_event_addGroup")){
            if(zero_setting_value("加群等级审批")){
                if(is_numeric(zero_setting_value("加群等级审批"))){
                    $dengji = zero_setting_value("加群等级审批");
                }else{
                    $dengji = 0;
                }
                if($this->data->sub_type=="add"){
                    $data = sendData([
                        "user_id" => $this->data->user_id
                    ],"_get_vip_info");
                    if($data['data']['level']>=$dengji){
                        sendMsg([
                            "flag" => $this->data->flag,
                            "sub_type" => $this->data->sub_type,
                            "approve" => true,
                        ],"set_group_add_request");
                    }else{
                        sendMsg([
                            "flag" => $this->data->flag,
                            "sub_type" => $this->data->sub_type,
                            "approve" => false,
                            "reason" => "等级低于".$dengji."级"
                        ],"set_group_add_request");
                    }
                }
            }
        }
    }

}