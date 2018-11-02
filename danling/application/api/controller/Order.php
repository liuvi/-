<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/13 0013
 * Time: 16:27
 */
namespace app\api\controller;
use think\Db;
use app\api\model\Address;
use app\api\model\Goods;
use app\api\model\Order as Ord;
class Order extends Common{

    /*
     * 提交订单
     * */
    public function subOrder(){
        if(request()->isPost()){
            $param['address_id']=input('post.address_id',0);
            $param['cat_id']    =input('post.cat_id','');
            $param['uid']       =input('post.uid',0);
            $order=new Ord();
            $msg=$order->addOrder($param);
            return json($msg);
        }else{
            return $this->dlError();
        }
    }

    /*
     * 获取订单详细
     * */
    public function getOrderInfo(){
        if(request()->isGet()){
            $order_sn   =input('param.order_sn','');
            if(!$order_sn){
                return json(['code'=>0,'msg'=>'参数错误']);
            }
            $order = new Ord();
            $msg   = $order->getOrder($order_sn);
            return json($msg);
        }else{
            return $this->dlError();
        }
    }

    /*
     * 订单列表
     * */

    public function getOrderList(){
        if(request()->isGET()){
            $uid    =input('get.uid',0);
            $page   =input('get.page',1);
            if(!$uid){
                return json(['code'=>0,'msg'=>'参数错误']);
            }
            $limit=config('SETLIMIT');
            $order = new Ord();
            $where['uid']       =$uid;
            $where['is_delete'] =0;
            $msg=$order->getOrderList($where,$page,$limit);
            return json($msg);
        }else{
            return $this->dlError();
        }
    }

    /*
     * 修改条形码
     *
     * */
    public function saveBarCode(){
        if(request()->isPost()){
            $param['bar_code']  =input('post.bar_code','');
            $param['id']        =input('post.id',0);
            if(!$param['bar_code'] || !$param['id']){
                return json(['code'=>0,'msg'=>'参数错误']);
            }
            $order=new Ord();
            $where['id']=array('neq',$param['id']);
            $where['bar_code']=$param['bar_code'];
            $bar=$order->getStatusOrder($where);
            if($bar){
                return json(['code'=>0,'msg'=>'该条码已存在']);
            }
            $msg = $order->saveBar($param);
            return json($msg);
        }else{
            return $this->dlError();
        }
    }


    /*
     * 取消订单
     * */
    public function cancelOrder(){
        if(request()->isPost()){
            $id=input('post.id',0);
            if(!$id){
                return json(['code'=>0,'msg'=>'参数错误']);
            }
            $order=new Ord();
            $param['id']=$id;
            $param['status']=4;
            $msg=$order->cancelOrder($param);
            return json($msg);
        }else{
            return $this->dlError();
        }
    }


}