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

/**

 * @author ja颂
 * 把数字1-1亿换成汉字表述，如：123->一百二十三
 * @param [num] $num [数字]
 * @return [string] [string]
 */
function numToWord($num)
{
    $chiNum = array('零', '一', '二', '三', '四', '五', '六', '七', '八', '九');
    $chiUni = array('','十', '百', '千', '万', '亿', '十', '百', '千');

    $chiStr = '';

    $num_str = (string)$num;

    $count = strlen($num_str);
    $last_flag = true; //上一个 是否为0
    $zero_flag = true; //是否第一个
    $temp_num = null; //临时数字

    $chiStr = '';//拼接结果
    if ($count == 2) {//两位数
        $temp_num = $num_str[0];
        $chiStr = $temp_num == 1 ? $chiUni[1] : $chiNum[$temp_num].$chiUni[1];
        $temp_num = $num_str[1];
        $chiStr .= $temp_num == 0 ? '' : $chiNum[$temp_num];
    }else if($count > 2){
        $index = 0;
        for ($i=$count-1; $i >= 0 ; $i--) {
            $temp_num = $num_str[$i];
            if ($temp_num == 0) {
                if (!$zero_flag && !$last_flag ) {
                    $chiStr = $chiNum[$temp_num]. $chiStr;
                    $last_flag = true;
                }
            }else{
                $chiStr = $chiNum[$temp_num].$chiUni[$index%9] .$chiStr;

                $zero_flag = false;
                $last_flag = false;
            }
            $index ++;
        }
    }else{
        $chiStr = $chiNum[$num_str[0]];
    }
    return $chiStr;
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

function i_array_column($input, $columnKey, $indexKey=null){
    if(!function_exists('array_column')){
        $columnKeyIsNumber  = (is_numeric($columnKey))?true:false;
        $indexKeyIsNull            = (is_null($indexKey))?true :false;
        $indexKeyIsNumber     = (is_numeric($indexKey))?true:false;
        $result                         = array();
        foreach((array)$input as $key=>$row){
            if($columnKeyIsNumber){
                $tmp= array_slice($row, $columnKey, 1);
                $tmp= (is_array($tmp) && !empty($tmp))?current($tmp):null;
            }else{
                $tmp= isset($row[$columnKey])?$row[$columnKey]:null;
            }
            if(!$indexKeyIsNull){
                if($indexKeyIsNumber){
                    $key = array_slice($row, $indexKey, 1);
                    $key = (is_array($key) && !empty($key))?current($key):null;
                    $key = is_null($key)?0:$key;
                }else{
                    $key = isset($row[$indexKey])?$row[$indexKey]:0;
                }
            }
            $result[$key] = $tmp;
        }
        return $result;
    }else{
        return array_column($input, $columnKey, $indexKey);
    }
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
    $content .= "您的验证码将在" . $time . "分钟后失效！【成都采宝】";
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
