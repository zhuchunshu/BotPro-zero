<?php
namespace App\Plugins\zero\src\message\event\notice;

/**
 * 管理员变动通知
 */
class group_admin {
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
        // 设置管理
        if(get_options("zero_switch_event_GroupAdmin_notice")){
            if($this->data->sub_type=="set"){
                sendMsg([
                    "group_id" => $this->data->group_id,
                    "message" => $this->data->user_id."升为了管理\n\nps:当你领悟到屁眼不只是用来拉屎的时候你也可以成为管理"
                ],"send_group_msg");
            }
            // 取消管理
            if($this->data->sub_type=="unset"){
                sendMsg([
                    "group_id" => $this->data->group_id,
                    "message" => $this->data->user_id."被下了管理\n\nps:服务差管理就没啦"
                ],"send_group_msg");
            }
        }
    }
}