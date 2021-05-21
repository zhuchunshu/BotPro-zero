<?php

use App\Models\Option;
use App\Plugins\zero\src\Server\Func;
use App\Plugins\zero\src\Server\TencentCloudApi;

function tcq(){
    return new TencentCloudApi();
}
function BOOT_func(){
    return new Func();
}
function isDomain($domain)
{
    return !empty($domain) && strpos($domain, '--') === false &&
    preg_match('/^([a-z0-9]+([a-z0-9-]*(?:[a-z0-9]+))?\.)?[a-z0-9]+([a-z0-9-]*(?:[a-z0-9]+))?(\.us|\.tv|\.org\.cn|\.org|\.net\.cn|\.net|\.mobi|\.me|\.la|\.info|\.hk|\.gov\.cn|\.edu|\.com\.cn|\.com|\.co\.jp|\.co|\.cn|\.cc|\.biz|\.top|\.work|\.ltd|\.ink|\.xyz)$/i', $domain) ? true : false;
}
function zero_setting_value($name){
    if(get_options_count($name)){
        return Option::where('name',$name)->first()->value;
    }else{
        return "无";
    }
}