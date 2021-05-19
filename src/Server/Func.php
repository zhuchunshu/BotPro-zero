<?php
namespace App\Plugins\zero\src\Server;

class Func
{
    public function language($text){
        switch ($text) {
            case '英语':
                $target = "en";
                break;
            case '日语':
                $target = "ja";
                break;
            case '韩语':
                $target = "ko";
                break;
            case '法语':
                $target = "fr";
                break;
            case '西班牙语':
                $target = "es";
                break;
            case '意大利语':
                $target = "it";
                break;
            case '德语':
                $target = "de";
                break;
            case '土耳其语':
                $target = "tr";
                break;
            case '俄语':
                $target = "ru";
                break;
            case '泰语':
                $target = "th";
                break;
            case '中文':
                $target = 'zh';
                break;
            case '繁体中文':
                $target = 'zh-TW';
                break;
            default:
                $target = 'zh-TW';
                break;
        }
        return $target;
    }
}
