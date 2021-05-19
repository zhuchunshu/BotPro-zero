<?php
namespace App\Plugins\zero\src\Middleware;

use App\Plugins\zero\src\Models\ZeroUsers;

class HasUser{
    public function handle($group,$qq){
        if(!ZeroUsers::where(['qq' => $qq,'group' => $group])->count()){
            $id = ZeroUsers::insertGetId([
                'qq' => $qq,
                'group' => $group,
                'jifen' => 10,
                'created_at' => date("Y-m-d H:i:s")
            ]);
            sendMsg([
                'group_id' => $group,
                'message' => "首次注册,奖励10积分\n你的ID为:{$id}"
            ],'send_group_msg');
        }
    }
}