<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/9 0009
 * Time: 14:21
 */
namespace app\admin\controller;
use think\File;
use think\Request;
class Upload extends Common{
    public function uploadAjax(){
        $file = request()->file('file');
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads/images');
        if($info){
            echo $info->getSaveName();
        }else{
            echo $file->getError();
        }
    }
}