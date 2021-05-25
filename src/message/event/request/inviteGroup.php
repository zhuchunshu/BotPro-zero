<?php
namespace App\Plugins\zero\src\message\event\request;

/**
 * 受邀自动同意
 */
class inviteGroup {

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
        if(get_options("zero_switch_event_Group_shouyaoTy")){
            if($this->data->sub_type=="invite"){
                sendMsg([
                    "flag" => $this->data->flag,
                    "sub_type" => $this->data->sub_type,
                    "approve" => true,
                ],"set_group_add_request");
            }
        }
    }

}