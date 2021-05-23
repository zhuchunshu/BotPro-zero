<?php
namespace App\Plugins\zero\src\Api;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redis;

class V1{
    /**
     * 获取网站收录
     *
     * @return void
     */
    public static function WebSiteShoulu($domain){
        if(!@$domain){
            return '请求参数不足,缺少:domain';
        }
        if(!isDomain($domain)){
            // 域名格式不正确
            return '域名格式不正确';
        }
        if(Redis::get('Api.V1.WebSiteShoulu.Domian.'.$domain)){
            return unserialize(Redis::get('Api.V1.WebSiteShoulu.Domian.'.$domain));
        }else{
            // 百度收录
            $response = Http::withHeaders([
                'CLIENT-IP' => '127.0.0.1',
                'X-FORWARDED-FOR' => '127.0.0.1',
                'referer' => '',
                'user-agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36'
            ])->get('https://www.baidu.com/s?ie=UTF-8&wd=site%3A'.$domain."/");
            $bddata = $response->body();
            $bddata=Str::after($bddata,'c-border c-row site_tip">');
            $bddata=Str::after($bddata,'<p>');
            $bddata=Str::after($bddata,'<b>');
            $bddata=Str::before($bddata,'</b>');
            $bdif=Str::before($bddata, '数约');
            if($bdif!="找到相关结果"){
                $bddata="未找到相关结果";
            }else{
                $bddata=Str::after($bddata,'数约');
                $bddata=Str::before($bddata,'个');
                $bddata=str_replace(",","",$bddata);
            }
            // 百度索引
            $response = Http::withHeaders([
                'CLIENT-IP' => '127.0.0.1',
                'X-FORWARDED-FOR' => '127.0.0.1',
                'referer' => '',
                'user-agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36'
            ])->get('https://www.baidu.com/s?ie=UTF-8&wd=site%3A'.$domain);
            $bdsuoyin = $response->body();
            $bdsuoyin = Str::after($bdsuoyin, 'c-border">');
            $bdsuoyin = Str::after($bdsuoyin, '<span>');
            $bdsuoyin = Str::before($bdsuoyin, '</span>');
            $bdif2 = Str::before($bdsuoyin, '有');
            $bdif2 = Str::after($bdif2, '该');
            if($bdif2!="网站共"){
                $bdsuoyin="未找到相关结果";
            }else{
                $bdsuoyin = Str::after($bdsuoyin, '>');
                $bdsuoyin = Str::before($bdsuoyin, '</b>');
                $bdsuoyin=str_replace(",","",$bdsuoyin);
            }
            //搜狗收录
            $response = Http::withHeaders([
                'CLIENT-IP' => '127.0.0.1',
                'X-FORWARDED-FOR' => '127.0.0.1',
                'referer' => '',
                'user-agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36'
            ])->get('https://www.sogou.com/web?query=site%3A'.$domain);
            $sgsl=$response->body();
            $sgsl = Str::after($sgsl, 'search-info">');
            $sgsl = Str::after($sgsl, 'num-tips">');
            $sgsl = Str::after($sgsl, '搜狗已为您找到约');
            $sgsl = Str::before($sgsl, '条相关结果');
            $sgsl=str_replace(",","",$sgsl);
            if($sgsl==0){
                $sgsl = "未找到相关结果";
            }
            // 360
            $response = Http::withHeaders([
                'CLIENT-IP' => '127.0.0.1',
                'X-FORWARDED-FOR' => '127.0.0.1',
                'referer' => '',
                'user-agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36'
            ])->get('https://www.so.com/s?q=site%3A'.$domain);
            $socom = $response->body();
            $socom = Str::after($socom, 'id="page">');
            $socom = Str::after($socom, '<span');
            $socom = Str::after($socom, '>');
            $socom = Str::before($socom, '</span>');
            $socomif = Str::before($socom, '果约');
            if($socomif!="找到相关结"){
                $socom = "未找到相关结果";
            }else{
                $socom = Str::after($socom, '找到相关结果约');
                $socom = Str::before($socom, '个');
                $socom=str_replace(",","",$socom);
            }
            // 神马
            $response = Http::withHeaders([
                'CLIENT-IP' => '127.0.0.1',
                'X-FORWARDED-FOR' => '127.0.0.1',
                'referer' => '',
                'user-agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36'
            ])->get('https://m.sm.cn/s?q=site%3A'.$domain);
            $smcn = $response->body();
            $smcn = Str::after($smcn, 'class="card site-info">');
            $smcn = Str::after($smcn, 'class="site-body">');
            $smcn = Str::after($smcn, '</h3>');
            $smcn = Str::after($smcn, '<p>');
            $smcn = Str::before($smcn, '</p>');
            $smcnif = Str::before($smcn, '站约');
            if($smcnif!="神马收录该网"){
                $smcn = "未找到相关结果";
            }else{
                $smcn = Str::after($smcn, '<i>');
                $smcn = Str::before($smcn, '</i>');
                $smcn=str_replace(",","",$smcn);
            }
            $data=[
                'Domian' => $domain,
                'Domain' => $domain,
                'BaiDu' => $bddata,
                'BaiDuIndex' => $bdsuoyin,
                'SoCom' => $socom,
                'Sogou' => $sgsl,
                'SmCn' => $smcn
            ];
            Redis::setex('Api.V1.WebSiteShoulu.Domian.'.$domain,86400,serialize($data));
            return unserialize(Redis::get('Api.V1.WebSiteShoulu.Domian.'.$domain));
        }
    }
    /**
     * 获取哔哩哔哩AV号
     *
     * @return void
     */
    public static function Bili_aid($bv){
        $response = Http::withHeaders([
            'CLIENT-IP' => '127.0.0.1',
            'X-FORWARDED-FOR' => '127.0.0.1',
            'referer' => '',
            'user-agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36'
        ])->get('https://api.bilibili.com/x/player/v2?cid=1&bvid='.$bv);
        $data=json_decode($response->body(),true);
        if($data['code']==0){
            // 成功结果
            return 'av'.$data['data']['aid'];
        }else{
            // 错误结果
            return $data['message'];
        }
    }
    /**
     * 获取哔哩哔哩BV号
     *
     * @return void
     */
    public static function Bili_bv($av){
        $av=Str::after($av, 'av');
        $response = Http::withHeaders([
            'CLIENT-IP' => '127.0.0.1',
            'X-FORWARDED-FOR' => '127.0.0.1',
            'referer' => '',
            'user-agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36'
        ])->get('https://api.bilibili.com/x/player/v2?cid=12&aid='.$av);
        $data=json_decode($response->body(),true);
        if($data['code']==0){
            // 成功结果
            return $data['data']['bvid'];
        }else{
            // 错误结果
            return $data['message'];
        }
    }
    // 藏头诗
    public static function Cangtoushi($text){
        $data = Http::get("http://www.guabu.com/cangtoushi/?type=guabu&key=".$text);
        $result = $data->body();
        $result = Str::after($result, 'class="other">');
        $result = Str::before($result, '</div>');
        $result = str_replace("<h3>","",$result);
        $result = str_replace("</h3>","\n",$result);
        return $result;
    }
    // 手机号价格评估
    public static function Shpujihaojiage($text){
        $data = Http::get("http://www.guabu.com/sjjg/?type=guabu&sjID=".$text);
        $result = $data->body();
        $result = Str::after($result, 'id="summary">');
        $result = Str::before($result, '</div>');
        $result = str_replace("<h3>","",$result);
        $result = str_replace("</h3>","",$result);
        $result = str_replace("<br>","\n",$result);
        $result = str_replace("<b>","",$result);
        $result = str_replace("</b>","\n",$result);
        return $result;
    }
    // 渣男语录
    public static function Zhanan(){
        $data = Http::get("https://api.lovelive.tools/api/SweetNothings/WebSite/1?type=M");
        $result = $data->json()[0]['content'];
        return $result;
    }
    // 绿茶语录
    public static function Lvcha(){
        $data = Http::get("https://api.lovelive.tools/api/SweetNothings/WebSite/1?type=F");
        $result = $data->json()[0]['content'];
        return $result;
    }
    // 舔狗语录
    public static function tiangou(){
        $data = Http::get("http://api.vience.cn/api/tiangou");
        $result = $data->body();
        return $result;
    }
    // 备案查询
    public static function chaicp($domain){
        $data = Http::get("https://api.vience.cn/api/icpbeian?domain=".$domain);
        $result = $data->json()['result'];
        return $result['content'];
    }
}