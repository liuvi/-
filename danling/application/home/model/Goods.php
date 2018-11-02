<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/19 0019
 * Time: 13:40
 */
namespace app\home\model;
use think\exception\PDOException;
use think\exception\ErrorException;
use think\Model;
class Goods extends Model{
    protected $resultSetType = 'collection';

    public function getPrice($where){
        $dataList=$this->where($where)->order('create_time desc')->select();
        return empty($dataList) ? [] : $dataList->toArray();
    }
}