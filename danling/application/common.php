<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------
error_reporting(E_ERROR | E_WARNING | E_PARSE);
// 应用公共文件
function getTree($cate , $lefthtml = '— — ' , $pid=0 , $lvl=0, $leftpin=0 ){
    $arr=array();
    foreach ($cate as $v){
        if($v['pid']==$pid){
            $v['lvl']=$lvl + 1;
            $v['leftpin']=$leftpin + 0;//左边距
            $v['lefthtml']=str_repeat($lefthtml,$lvl);
            $arr[]=$v;
            $arr= array_merge($arr,getTree($cate,$lefthtml,$v['id'],$lvl+1 , $leftpin+20));
        }
    }
    return $arr;
}

//后台菜单
function menuChild($menu,$name='child',$pid = 0){
    $arr = array();
    foreach($menu as $_v){
        if($_v['pid'] == $pid){
            $_v[$name] = menuChild($menu,$name,$_v['id']);
            $_v['href']=url($_v['name']);
            $arr[] = $_v;
        }
    }
    return $arr;
}


/**
 * 整理菜单树方法
 * @param $param
 * @return array
 */
function prepareMenu($param)
{
    $parent = []; //父类
    $child = [];  //子类

    foreach($param as $key=>$vo){

        if($vo['pid'] == 0){
            $vo['href'] = '#';
            $parent[] = $vo;
        }else{
            $vo['href'] = url($vo['name']); //跳转地址
            $child[] = $vo;
        }
    }

    foreach($parent as $key=>$vo){
        foreach($child as $k=>$v){

            if($v['pid'] == $vo['id']){
                $parent[$key]['child'][] = $v;
            }
        }
    }
    unset($child);
    return $parent;
}



//----------------发起请求
function httpGet($url) {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_TIMEOUT, 500);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_URL, $url);
    $res = curl_exec($curl);
    curl_close($curl);
    return $res;
}



/**
 * +-------------------------------------------------
 * + 创建多位随机码
 * +-------------------------------------------------
 * + @param number $length 长度 默认为6位
 * + @param string $codeSet 数据源 默认为(0123456789)
 * +-------------------------------------------------
 * + @return string
 * +-------------------------------------------------
 */
function createRandomCode( $length = 6, $codeSet = "0123456789" ) {
    $code = "";
    for ( $i = 0; $i < $length; $i ++ ) {
        $code .= $codeSet[ mt_rand( 0, strlen( $codeSet ) - 1 ) ];
    }
    return $code;
}


function getCodeNumber($mobile,$code,$time,$content=''){
    //企业ID $userid
    $userid = '';
    //用户账号 $account
    $account = 'aimeixiu';
    //用户密码 $password
    $password = 'aimeixiu123';
    //发送到的目标手机号码 $mobile

    //短信内容 $content
    $content .= "您的验证码将在" . $time . "分钟后失效！【丹棱】";
    // $c=urlencode($content);
    //发送短信（其他方法相同）
    $gateway = "http://sh2.ipyy.com/sms.aspx?action=send&userid={$userid}&account={$account}&password={$password}&mobile={$mobile}&content={$content}&sendTime=";
    $result = file_get_contents($gateway);
    $xml = simplexml_load_string($result);

    if ($xml->returnstatus == "Success") {
        $return['status'] = 1;
        $return['info'] = '短信发送成功！';
    } else {
        $jsonStr = json_encode($xml->message);
        $jsonArray = json_decode($jsonStr,true);
        $return['status'] = 0;
        $return['info'] = $jsonArray[0];
    }
    return $return;
}


function createCode($length = 6) {
    $codeSet = "1234567890";
    $code = "";
    for ($i = 0; $i < $length; $i ++) {
        $code .= $codeSet [mt_rand(0, strlen($codeSet) - 1)];
    }
    $tempArray['code_set'] = sha1($code);
    $tempArray['end_time'] = time() + 60 * 30; // 半个小时过期
    session('codeSMS', $tempArray);
    return $code;
}