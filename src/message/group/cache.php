<?php
namespace App\Plugins\zero\src\message\group;

use Hashids\Hashids;
use App\Plugins\zero\src\interfaces\message;
use App\Plugins\zero\src\message\group\Send\Cache\Fa;
use App\Plugins\zero\src\message\group\Send\Cache\Shou;
use Illuminate\Support\Facades\Cache as FacadesCache;
require_once plugin_path("zero/vendor/autoload.php");

class cache implements message{

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

    private $ban = [
        "__construct"
    ];

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

    public function boot()
    {
        if (FacadesCache::get($this->data->group_id . "_" . $this->data->user_id, null)) {
            if (!in_array(FacadesCache::get($this->data->group_id . "_" . $this->data->user_id, null), $this->ban) && method_exists($obj = new Fa($this->data), FacadesCache::get($this->data->group_id . "_" . $this->data->user_id, null))) {
                $data = FacadesCache::get($this->data->group_id . "_" . $this->data->user_id, null);
                FacadesCache::forget($this->data->group_id . "_" . $this->data->user_id);
                (new Fa($this->data))->$data();
            }
        } else {
            if (!in_array($this->order[0], $this->ban) && method_exists($obj = new Shou($this->data), $this->order[0])) {
                $data = $this->order[0];
                FacadesCache::add($this->data->group_id . "_" . $this->data->user_id, $this->order[0], 60);
                (new Shou($this->data))->$data();
            }
        }
    }

}