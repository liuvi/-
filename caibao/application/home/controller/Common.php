<?php
namespace app\home\controller;
use think\Controller;
use think\Db;
class Common extends Controller
{
    public $uid;
    public function _initialize(){
        $this->uid=session('MemberId');
    }

    public function getUser(){
        $userInfo=Db::name('Member')->where('id',$this->uid)->find();
        return $userInfo;
    }

    public function checkLogin(){
        if(!$this->uid){
            $this->redirect('Register/index');
        }
    }
}