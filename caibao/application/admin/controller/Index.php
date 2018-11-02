<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/5 0005
 * Time: 17:05
 */
namespace app\admin\controller;
use think\Db;

class Index extends Common{
    public function _initialize()
    {
        parent::_initialize();
    }
    //后台首页
    public function index(){
        return $this->fetch('index');
    }

    public function indexhome(){
        return $this->fetch('indexhome');
    }
}