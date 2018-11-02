<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/2 0002
 * Time: 09:50
 */
namespace app\api\model;
use think\exception\ErrorException;
use think\exception\PDOException;
use think\Model;
use think\Db;
class BagOrder extends Model{
   // protected $name="bag_order";
    /*
     * 提交订单
     * */

    public function subOrder($param){
        try{
            Db::startTrans();
            $param['order_sn']   =self::createOrderSN();
            $param['create_time']=time();
            $result=$this->save($param);
            if(false===$result){
                Db::rollback();
                return ['code'=>0,'msg'=>$this->getError()];
            }else{
                Db::commit();
                return ['code'=>1,'msg'=>'提交成功','data'=>['order_sn'=>$param['order_sn']]];
            }
        }catch (PDOException $e){
            return ['code'=>0,'msg'=>$e->getMessage()];
        }
    }

    /*
     * 修改订单状态
     * */





































    /**
     * 创建新订单编号
     *
     * @return string
     */
    public function createOrderSN( $field = 'order_sn' ) {
        // 订单编号
        $SN = createRandomCode() . time();
        $ordersn=$this->where('order_sn',$SN)->value($field);
        // 判断是否存在
        if ($ordersn) {
            $this->createOrderSN( $field );
        } else {
            return $SN;
        }
    }
}