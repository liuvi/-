<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/5 0005
 * Time: 17:42
 */
namespace app\admin\controller;
use think\Db;
class Article extends Common{
    public function _initialize()
    {
        parent::_initialize();
    }
    //文章列表
    public function index(){
        return $this->fetch('index');
    }
}