<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/5 0005
 * Time: 17:42
 */
namespace app\api\controller;
use think\Db;
use app\api\model\Category as cate;
class Category extends Common{

    /*
     * 分类获取
     * */
    public function getcate(){
        $model=new cate();
        $msg=$model->getCateList();
        return json($msg);
    }

    /*
     * 根据分类获取对应分类价格
     * */
    public function getCatePrice(){
        $model=new cate();
        $msg=$model->getPrice();
        return json($msg);
    }
}