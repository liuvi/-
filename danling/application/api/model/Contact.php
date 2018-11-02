<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/30 0030
 * Time: 09:03
 */
namespace app\api\model;
use think\Model;
use think\exception\PDOException;
use think\exception\ErrorException;
use think\Db;
class Contact extends Model{
    protected $resultSetType = 'collection';
    /*
     * 获取列表
     * */
    public function getContactList($where,$Nowpage,$limit){
        $count=$this->where($where)->count();
        $allpage = intval(ceil($count / $limit));
        $data=$this->where($where)->field('name,tel')->order('create_time desc')->page($Nowpage,$limit)->select();
        $dataList= empty($data) ? json([]) : $data->toArray();
        return ['code'=>1,'msg'=>'获取成功','dataInfo'=>['dataList'=>$dataList,'allpage'=>$allpage]];
    }
}