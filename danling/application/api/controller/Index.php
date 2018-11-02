<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/5 0005
 * Time: 17:42
 */
namespace app\api\controller;
use think\Db;
use app\api\model\Category;
class Index extends Common{

    /*
     * 首页数据获取
     * */
    public function index(){
        $cate=new Category();
        $msg=$cate->getIndexCateList();
        return json($msg);
    }

}