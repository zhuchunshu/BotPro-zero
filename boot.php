<?php
namespace App\Plugins\zero;

use Dcat\Admin\Admin;
use Illuminate\Support\Facades\Route;
use App\Plugins\zero\src\database\Zerouser;

class Boot{

    public function handle(){
        require_once plugin_path("zero/vendor/autoload.php");
        require_once plugin_path("zero/src/lib/functions.php");
        // 注册菜单
        $this->menu();
        // 注册路由
        $this->route();

        // 数据库迁移
        $this->DatabaseMigrate();

    }

    public function route(){
        Route::middleware(config('admin.route.middleware'))
        ->prefix(config('admin.route.prefix') . "/zero")
        ->name('admin.zero.')
        ->group(plugin_path("zero/src/lib/route.php"));
    }

    public function menu(){
        Admin::menu()->add(include __DIR__ . '/src/lib/menu.php', 0);
    }

    // 数据库迁移
    public function DatabaseMigrate(){
        // 创建用户表
        (new Zerouser())->up();
    }

}