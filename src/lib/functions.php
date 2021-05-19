<?php

use App\Plugins\zero\src\Server\Func;
use App\Plugins\zero\src\Server\TencentCloudApi;

function tcq(){
    return new TencentCloudApi();
}
function BOOT_func(){
    return new Func();
}