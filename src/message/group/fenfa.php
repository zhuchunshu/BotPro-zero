<?php
namespace App\Plugins\zero\src\message\group;

use App\Plugins\zero\src\interfaces\message;
use App\Plugins\zero\src\message\group\Send\After;
use App\Plugins\zero\src\message\group\Send\two as SendTwo;
use App\Plugins\zero\src\Middleware\HasUser;
use App\Plugins\zero\src\Models\ZeroUsers;

/**
 * 发语音
 */
class fenfa implements message{

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
    
    public $order;

    public $orderCount;

    public $after_order;
    public $after_orderCount;

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
        $this->order = $order = GetZhiling($data,"#");
        $this->orderCount = count($order);
        $this->boot();
    }

    public function boot(){
        // 指令大于等于2
        if(@$this->orderCount>=2){
            $arr = [
                SendTwo::class
            ];
            $this->Run($arr);
        }

        // 后指令
        $after_order = GetZhiling($this->data," ");
        $after_orderCount = count($after_order);
        $this->after_order = $after_order;
        $this->after_orderCount = $after_orderCount;
        if ($after_orderCount >= 2) {
            $arr = [
                After::class
            ];
            $this->AfterRun($arr);
        }
    }

    public function Run(array $arr){
        foreach ($arr as $value) {
            if(method_exists(new $value($this->data,$this->order,$this->orderCount,$this->value),$this->order[0])){
                $method = $this->order[0];
                (new $value($this->data,$this->order,$this->orderCount,$this->value))->$method();
            }
        }
    }

    public function AfterRun(array $arr){
        foreach ($arr as $value) {
            if(method_exists(new $value($this->data,$this->after_order,$this->after_orderCount,$this->value),$this->after_order[1])){
                $method = $this->after_order[1];
                (new $value($this->data,$this->after_order,$this->after_orderCount,$this->value))->$method();
            }
        }
    }
}