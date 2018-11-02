<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/29 0029
 * Time: 09:56
 */
namespace app\home\model;
use think\exception\ErrorException;
use think\exception\PDOException;
use think\Model;
use think\Db;
class Topic extends Model{
    protected $resultSetType = 'collection';
    /*
     * 题库获取
     * */
    public function getTopicList($where,$Nowpage,$limit){
        $datalist= $this->where($where)->page($Nowpage,$limit)->order('RAND()')->select();
        return empty($datalist) ? [] : $datalist->toArray();
    }

    /*
     *
     * */

    public function getTopicRes($ids = [])
    {
        $data = $this->where('id','in',$ids)->field('id,value,fenshu')->select();
        return empty($data) ? [] : $data->toArray();
    }
}