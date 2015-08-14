<?php
header('Content-type: text/html; charset=utf-8');
$appid = 'wxd3da8189b5ec1ba7';
$appsecret = 'b71109103ce5d7fd079636d23444081d';

if (isset($_GET['code'])){
    $code =  $_GET['code'];
    $token_url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=$appid&secret=$appsecret&code=$code&grant_type=authorization_code";
    $res = https_request($token_url);
    $res_array = json_decode($res,true);
    $access_token = $res_array['access_token'];
    $openid = $res_array['openid'];
    $refresh_token = $res_array['refresh_token'];
    setcookie("draw_openid",$openid);
    setcookie("refresh_token",$refresh_token);
    
    //获取用户信息并存入cookie
    $info_url = "https://api.weixin.qq.com/sns/userinfo?access_token=$access_token&openid=$openid";
    $res_info = https_request($info_url);
    $info_array = json_decode($res_info,true);
    $nickname = $info_array['nickname'];
    setcookie("draw_nickname",$nickname);
    $headimgurl = $info_array['headimgurl'];
    setcookie("draw_logo",$headimgurl);
    
    //echo $_COOKIE["draw_nickname"];
	//echo $_COOKIE["draw_logo"];
	//echo $_COOKIE["draw_openid"];
    
    echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />";
	echo "<script language='javascript'  type='text/javascript'>";
	echo "window.location.href='index.php' ; ";
	echo "</script>";
}else{
    echo "NO CODE";
}

function https_request($url, $data = null)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if (!empty($data)){
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }
?>