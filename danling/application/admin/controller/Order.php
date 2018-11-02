<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/18 0018
 * Time: 10:20
 */
namespace app\admin\controller;
use think\Db;
use app\admin\model\Order as Ord;
class Order extends Common{

    /*
     * 订单列表
     * */
    public function index(){
        $where=[];

        $this->parameter['username']=input('username','');
        $this->parameter['order_sn']=input('order_sn','');
        $this->parameter['status']=input('status','');
        $this->parameter ['start_time']=input('start_time','');
        $this->parameter ['end_time'] =input('end_time','');
        $this->parameter ['bar_code'] =input('bar_code','');
        if( $this->parameter['username'] ){
            $where['b.username']=['like','%'.$this->parameter['username'].'%'];
        }
        if($this->parameter['order_sn']){
            $where['a.order_sn']=['like','%'.$this->parameter['order_sn'].'%'];
        }
        if($this->parameter['status']){
            $where['a.status']=['like','%'.$this->parameter['status'].'%'];
        }
        if( $this->parameter ['bar_code'] ){
            $where['a.bar_code']=['like','%'.$this->parameter['bar_code'].'%'];
        }
        //创建时间
        if ( ! empty ( $this->parameter ['start_time'] ) && ! empty ( $this->parameter ['end_time'] ) ) {
            $start_time = strtotime ( $this->parameter ['start_time'] );
            $end_time = strtotime ( $this->parameter ['end_time'] );
            $where['a.create_time'] = array('between',array($start_time,$end_time));
        } else if ( ! empty ( $this->parameter ['start_time'] ) ) {
            $start_time = strtotime ( $this->parameter ['start_time'] );
            $where['a.create_time'] = array('EGT',$start_time);
        } else if ( ! empty ( $this->parameter ['end_time'] ) ) {
            $end_time = strtotime ( $this->parameter ['end_time'] );
            $where['a.create_time'] = array('ELT',$end_time);
        }

        $Nowpage = input('get.page') ? input('get.page'):1;
        $limits = config('pagelimit');// 获取总条数
        $count = Db::name('Order')->alias('a')
                ->join('dl_member b','b.id=a.uid','LEFT')
                ->join('dl_region c','c.id=a.town_id','LEFT')->where($where)->count();//计算总页面
        $allpage = intval(ceil($count / $limits));
        $order=new Ord();
        $lists=$order->getOrderList($where,$Nowpage,$limits);
        $this->listAssign['Nowpage']=$Nowpage; //当前页
        $this->listAssign['allpage']=$allpage; //总页数
        $this->listAssign['parameter']=$this->parameter;
        $this->listAssign['count']=$count;

        $start = strtotime(date('Y-m-d 00:00:00'));
        $map['create_time']=array('EGT',$start);
        $day_order=$order->where($map)->count(); //今日订单量
        $map['status']=3;
        $day_order_succ=$order->where($map)->count();// 今日已完成订单
        $this->listAssign['day_order']=$day_order;
        $this->listAssign['day_order_succ']=$day_order_succ;
        $this->assign($this->listAssign);
        if(input('get.page'))
        {
            return json($lists);
        }
        return $this->fetch();
    }

    /*
     * 订单详情
     *
     * */
    public function show(){
        $id=input('get.id');
        if(!$id){
            return json(['code'=>0,'msg'=>'参数错误']);
        }
        $order=new Ord();
        $dataInfo=$order->getOne($id);
        $dataInfo['username']=Db::name('Member')->where(['id'=>$dataInfo['uid']])->value('username');
        $dataInfo['address']=json_decode($dataInfo['address_info'],true);
        $dataInfo['goods']=json_decode($dataInfo['goods_attrs'],true);
        //操作记录
        $dataInfo['handle']=$order->getOrderHandle($id, $dataInfo['username']);

        $this->assign('dataInfo',$dataInfo);

        $where[]=['exp',"FIND_IN_SET({$dataInfo['town_id']},rid)"];
        $to=Db::name("Member")->where($where)->field('username')->select();
        $this->assign('to',$to);
        return $this->fetch();
    }
}