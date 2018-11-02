<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/12 0012
 * Time: 11:26
 */
namespace app\api\model;
use think\exception\PDOException;
use think\exception\ErrorException;
use think\Model;
use think\Db;
class Goods extends Model{
    protected $resultSetType = 'collection';
    /*
     * 根据分类获取
     * */

    public function getGoodsList($where=array()){
        $dataList=$this->where($where)->field('id,name,price,unit')->order('sort desc,create_time desc')->select();
        return empty($dataList) ? [] : $dataList->toArray();
    }

}