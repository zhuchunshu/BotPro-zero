<?php

namespace App\Plugins\zero\src\Server;

use TencentCloud\Common\Credential;
use TencentCloud\Common\Profile\ClientProfile;
use TencentCloud\Common\Profile\HttpProfile;
use TencentCloud\Common\Exception\TencentCloudSDKException;
use TencentCloud\Tmt\V20180321\TmtClient;
use TencentCloud\Tmt\V20180321\Models\TextTranslateRequest;

class TencentCloudApi{
    public function Translate($SourceText,$Target,$Source='auto',$ProjectId=0,$SecretId=NULL,$SecretKey=NULL){
        
        if(get_options("腾讯云SECRETID") && get_options("腾讯云SECRETKEY")){
            $SecretId=get_options("腾讯云SECRETID");
            $SecretKey=get_options("腾讯云SECRETKEY");
            try {
                $cred = new Credential($SecretId, $SecretKey);
                $httpProfile = new HttpProfile();
                $httpProfile->setEndpoint("tmt.tencentcloudapi.com");
    
                $clientProfile = new ClientProfile();
                $clientProfile->setHttpProfile($httpProfile);
                $client = new TmtClient($cred, "ap-chengdu", $clientProfile);
    
                $req = new TextTranslateRequest();
    
                $params = array(
                    "ProjectId" => $ProjectId,
                    "SourceText" => $SourceText,
                    "Source" => $Source,
                    "Target" => $Target
                );
                $req->fromJsonString(json_encode($params));
    
                $resp = $client->TextTranslate($req);
    
                return $resp;
            }
            catch(TencentCloudSDKException $e) {
                return $e;
            }
        }else{
            return "腾讯云翻译接口未配置";
        }
    }
}
