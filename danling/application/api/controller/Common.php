<?php
namespace app\api\controller;
use think\Controller;
use think\Db;
class Common extends Controller{

    /*
     * 无效请求
     * */
    public function dlError(){
        return json(['code'=>0,'msg'=>'无效请求']);
    }
}