<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/18 0018
 * Time: 10:21
 */
namespace app\admin\model;
use think\exception\PDOException;
use think\exception\ErrorException;
use think\Model;
use think\Db;
class Order extends Model{
    protected $resultSetType='collection';
    /*
     * 获取订单列表
     * */
    public function getOrderList($where,$Nowpage,$limit){
        $field="a.*,b.username,c.town";
        $dataList=$this->alias('a')->field($field)
            ->join('dl_member b','b.id=a.uid','LEFT')
            ->join('dl_region c','c.id=a.town_id','LEFT')
            ->where($where)
            ->order('create_time desc')
            ->page($Nowpage,$limit)
            ->select();
        return empty($dataList) ? [] : $dataList->toArray();
    }

    /*
     * 获取一条数据
     * */
    public function getOne($id){
        return $this->where(['id'=>$id])->find();
    }

    /*
 * 获取订单操作记录
 * */
    public function getOrderHandle($oder_id,$name){
        $where['a.order_id']=$oder_id;
        $lists=Db::name("OrderLog")->alias('a')->field('a.*')
            ->join("dl_order b",'b.id=a.order_id',"LEFT")
            ->where($where)
            ->select();
        $member=Db::name('Member');
        foreach ($lists as &$val){
            if($val['action_user']==0){
                $val['name']='用户：'.$name;
            }else{
                $val['name']='操作人：'.$member->where('id',$val['action_user'])->value('username');
            }
        }
        return $lists;
    }

}