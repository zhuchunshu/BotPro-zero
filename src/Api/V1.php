<?php
namespace App\Plugins\zero\src\Api;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;

class V1{
    /**
     * 备案查询
     * 必选参数: uk, domain
     * @return void
     */
    public static function Beian($domain){

        $domain = parse_url($domain);
        if(empty(@$domain['scheme']) && empty(@$domain['host']) && @$domain['path']){
            // 如果path不为空,host,scheme为空
            $domain = preg_replace('/[^0-9a-zA-Z.]/','',@$domain['path']);
        }else{
            // 如果host,scheme,path都存在
            $domain=$domain['host'];
        }
        if(!isDomain($domain)){
            return '域名格式有误';
        }

        # 判断备案号是否在数据库中存在

        $response = Http::withHeaders([
            'CLIENT-IP' => '127.0.0.1',
            'X-FORWARDED-FOR' => '127.0.0.1',
            'referer' => '',
            'user-agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36'
        ])->get('https://icp.aizhan.com/'.$domain.'/');
        $af=Str::after($response->body(), 'class="red">');
        $bf=Str::before($af, '</span>');
        //return $response->body();
        if(Str::is('未找到*',$bf)){
            return '域名格式有误';
        }else{
            // 查到备案号
            $af=Str::after($response->body(), '<td class="thead">主办单位名称</td>');
            $af=Str::after($af, '<td>');
            $name=Str::before($af, '</td>'); //主办单位名称
            $af=Str::after($response->body(), '<td class="thead">主办单位性质</td>');
            $af=Str::after($af, '<td>');
            $xingzhi=Str::before($af, '</td>'); //主办单位性质
            $af=Str::after($response->body(), '<td class="thead">网站备案/许可证号</td>');
            $af=Str::after($af, '<td><span>');
            $icp=Str::before($af, '</span></td>'); //icp备案号
            $af=Str::after($response->body(), '<td class="thead">网站名称</td>');
            $af=Str::after($af, '<td>');
            $web_name=Str::before($af, '</td>'); //网站名称
            $af=Str::after($response->body(), '<td class="thead">审核时间</td>');
            $af=Str::after($af, '<td><span>');
            $time=Str::before($af, '</span></td>'); //审核时间
            //return $time;
            //开始入库
            return [
                'domain' => $domain,
                'icp' => $icp,
                'name' => $name,
                'web_name' => $web_name,
                'nature' => $xingzhi,
                'Audit_time' => $time,
            ];
        }
    }
}