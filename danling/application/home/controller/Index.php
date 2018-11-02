<?php
namespace app\home\controller;
use app\home\model\Order;
use app\api\model\Order as Ord;
use app\api\model\Category;
use app\api\model\Goods;
use think\Cache;
use think\Db;
class Index extends Common
{
    /*
     * 订单列表
     * */
    public function index()
    {

        $page   =   input('get.page',1);
        $status =   input('get.status',1);
        $town_id=   $this->userInfo['rid'];
        $pagenum =config('pagelimit');//每页数量
        if (request()->isAjax() && request()->isGet()) {
            $tpl = 'ajaxindex';
        } else {
            $page = 0;
            $tpl = '';
        }
        $where['status']=$status;
        $where['is_delete']=0;
        $where[]=['exp',"FIND_IN_SET(town_id,'{$town_id}')"];
        $count = Db::name('Order')->where($where)->count();//计算总页面
        $allpage = intval(ceil($count / $pagenum));
        $order=new Order();
        $dataList=$order->getOrderList($where,$page,$pagenum);
        $this->assign('allpage',$allpage);
        $this->assign('dataList',$dataList);
        $this->assign('status',$status);
        return $this->fetch($tpl);
    }

    /*
     * 搜索页面
     * */
    public function search(){
        $page   =   input('get.page',1);
        $town_id=   $this->userInfo['rid'];
        $bar_code=  input('get.bar_code','');
        if($bar_code){
            $where['bar_code']=['like','%'.$bar_code.'%'];
        }
        $pagenum =config('pagelimit');//每页数量
        if (request()->isAjax() && request()->isGet()) {
            $tpl = 'ajaxsearch';
        } else {
            $page = 0;
            $tpl = '';
        }

        $where['is_delete']=0;
        $where[]=['exp',"FIND_IN_SET(town_id,'{$town_id}')"];
        $count = Db::name('Order')->where($where)->count();//计算总页面
        $allpage = intval(ceil($count / $pagenum));
        $order=new Order();
        $dataList=$order->getOrderList($where,$page,$pagenum);
        if(count($dataList)==0){
            return json(['code'=>0,'msg'=>'没有找到搜索的结果！']);
        }
        $this->assign('allpage',$allpage);
        $this->assign('dataList',$dataList);
        $this->assign('bar_code',$bar_code);
        return $this->fetch($tpl);
    }

    /*
     * 订单详情
     * */
    public function show(){
        $id=input('get.id',0);
        if(!$id){
            return json(['code'=>0,'msg'=>'参数错误']);
        }
        $order=new Order();
        $dataInfo=$order->getOne($id);
        $dataInfo['goods_attrs']=json_decode($dataInfo['goods_attrs'],true);
        $dataInfo['address_info']=json_decode($dataInfo['address_info'],true);
        $this->assign('dataInfo',$dataInfo);
        return $this->fetch();
    }

    /*
     * 取消订单
     *
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

    /*
     * 修改条形码
     * */
    public function saveBarCode(){
        if(request()->isPost()){
            $param['bar_code']  =input('post.bar_code','');
            $param['id']        =input('post.id',0);
            if(!$param['bar_code'] || !$param['id']){
                return json(['code'=>0,'msg'=>'条形码参数错误']);
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
     * 打包带回
     * */
    public function packGo(){
        if(request()->isPost()){
            $param['bar_code']  =input('post.bar_code','');
            $param['id']        =input('post.id',0);
            if(!$param['bar_code'] || !$param['id']){
                return json(['code'=>0,'msg'=>'条形码参数错误']);
            }
            $order=new Ord();
            $where['id']=array('neq',$param['id']);
            $where['bar_code']=$param['bar_code'];
            $bar=$order->getStatusOrder($where);
            if($bar){
                return json(['code'=>0,'msg'=>'该条码已存在']);
            }
            $msg = $order->saveBar($param);
            if($msg['code']==1){
                $data['id']=$param['id'];
                $data['status']=2;
                $msg=$order->packGo($data);
            }
            return json($msg);
        }else{
            return $this->dlError();
        }
    }

    /*
     * 删除订单
     * */
    public function delOrder(){
        if(request()->isPost()){
            $id =input('post.id',0);
            if(!$id){
                return json(['code'=>0,'msg'=>'参数错误']);
            }
            $order=new Order();
            $msg=$order->del($id);
            return json($msg);
        }else{
            return $this->dlError();
        }
    }

    /*
     * 开始称重页面
     * */
    public function startWeigh(){
        $id=input('get.id',0);
        if(!$id){
            return json(['code'=>0,'msg'=>'参数错误']);
        }
        $order=new Order();
        $dataInfo=$order->getOne($id);
        $dataInfo['goods_attrs']=json_decode($dataInfo['goods_attrs'],true);
        $dataInfo['address_info']=json_decode($dataInfo['address_info'],true);
//        $goods_attrs=Cache::get('goods_attrs'.$this->userInfo['id']);
//        if($goods_attrs){//如何缓存存在使用已添加缓存中的分类
//            $dataInfo['goods_attrs']=$goods_attrs;
//            $dataInfo['totalmoney']=Cache::get('totalmoney'.$this->userInfo['id']);
//        }

        $this->assign('dataInfo',$dataInfo);
        return $this->fetch();
    }

    /*
     * 添加回收页面
     * */
    public function addcate(){
        $order=new Order();
        if(request()->isPost() && request()->isAjax()){
            $arr        =input('post.arr/a',[]);
            $orderid    =input('post.id/d',0);
            if(empty($arr)){
                return json(['code'=>0,'msg'=>'数据错误']);
            }
            if(!$orderid){
                return json(['code'=>0,'msg'=>'订单信息错误']);
            }
            $checkid='';
            foreach ($arr as $value){
                $checkid.=$value['id'].',';
            }
            $cid=rtrim($checkid,',');
            $goods=new Goods();
            $where['id']=array('in',$cid);
            $GoodsList=$goods->getGoodsList($where);//根据分类id获取名称价格

            $totalmoney=0;
            foreach ($GoodsList as $key=>$value){
                $GoodsList[$key]['goods_num']=$arr[$key]['goods_num'];
                $GoodsList[$key]['totalmoney']=$arr[$key]['goods_num']*$value['price'];
                $totalmoney+=$GoodsList[$key]['totalmoney'];
            }
            $param['totalmoney']=$totalmoney;
            $param['goods_attrs']=json_encode($GoodsList);
            $param['id']=$orderid;
            $result=$order->saveGoods($param);
            if($result['code']==1){
                return json(['code'=>1]);
            }else{
                return json(['code'=>0,'msg'=>'记录失败']);
            }
        }
        $orderid=input('get.id',0);
        $category=new Category();
        $cateprice=$category->getPrice();
        $this->assign('orderid',$orderid);
        $this->assign('cateprice',$cateprice['dataInfo']);


        $dataInfo=$order->getOne($orderid);
        $dataInfo['goods_attrs']=json_decode($dataInfo['goods_attrs'],true);

        $checkid=[];
        foreach ($dataInfo['goods_attrs'] as $key=>$value){
            $checkid[]=$value['id'];
        }
        $this->assign('checkid',$checkid);
        $this->assign('goods_attrs', $dataInfo['goods_attrs']);
        $strcheckid=implode(',',$checkid);
        $this->assign('strcheckid',$strcheckid);
        return $this->fetch();
    }

    /*
     * 确认添加
     * */
    public function confirmadd(){
        if(request()->isPost()){
            $order=new Order();
            $catarr=input('post.catarr/a',[]);
            $orderid=input('post.orderid',0);
            if(!$catarr){
                return json(['code'=>0,'msg'=>'所选分类错误!']);
            }
            $catarr=implode(',',$catarr);
            $goods=new Goods();
            $where['id']=array('in',$catarr);
            $GoodsList=$goods->getGoodsList($where);//根据分类id获取名称价格数量
            //获取修改之前订单数据
            $history=$order->getOrderGoods($orderid);
            $totalmoney=0;
            $arr3 = array();
            foreach($history as $k2 => $v2)
            {
                $arr3[$v2['id']] = $v2;
            }
            foreach($GoodsList as $k => $v)
            {
                if($arr3[$v['id']] != null)
                {
                    $GoodsList[$k]['goods_num'] = $arr3[$v['id']]['goods_num'];
                }else{
                    $GoodsList[$k]['goods_num'] = 1;
                }
                $GoodsList[$k]['totalmoney']= $GoodsList[$k]['goods_num']*$v['price'];
                $totalmoney+= $GoodsList[$k]['totalmoney'];
            }
            $param['totalmoney']=$totalmoney;
            $param['goods_attrs']=json_encode($GoodsList);
            $param['id']=$orderid;
            $result=$order->saveGoods($param);
            return json($result);
        }else{
            return $this->dlError();
        }
    }


    /*
     * 提交统计
     * */

    public function subCount(){
        if(request()->isPost()){
            $arr        =input('post.arr/a',[]);
            $orderid    =input('post.id/d',0);
            if(empty($arr)){
                return json(['code'=>0,'msg'=>'数据错误']);
            }
            if(!$orderid){
                return json(['code'=>0,'msg'=>'订单信息错误']);
            }
            $checkid='';
            foreach ($arr as $value){
                $checkid.=$value['id'].',';
            }
            $cid=rtrim($checkid,',');
            $goods=new Goods();
            $where['id']=array('in',$cid);
            $GoodsList=$goods->getGoodsList($where);//根据分类id获取名称价格

            $totalmoney=0;
            foreach ($GoodsList as $key=>$value){
                if($arr[$key]['goods_num']==''){
                    return json(['code'=>0,'msg'=>'请输入数量']);
                }

                $GoodsList[$key]['goods_num']=$arr[$key]['goods_num'];
                $GoodsList[$key]['totalmoney']=$arr[$key]['goods_num']*$value['price'];
                $totalmoney+=$GoodsList[$key]['totalmoney'];
            }

            $param['id']        =$orderid;
            $param['status']    =3;
            $param['pay_time']  =time();
            $param['totalmoney']=$totalmoney;
            $param['goods_attrs']=json_encode($GoodsList);
            $order=new Order();
            $msg=$order->editOrder($param);
            return json($msg);
        }else{
            return $this->dlError();
        }
    }

    /*
     * 获取值
     * */
    public function getBarInfo(){
        if(request()->isGet()){
            $bar_code=input('get.bar_code','');
            if(!$bar_code){
                return json(['code'=>0,'msg'=>'条码信息错误']);
            }
            $town_id=   $this->userInfo['rid'];


            $order=new Order();

            $id=$order->getBarInfo($bar_code);
            if($id){
                $where[]=['exp',"FIND_IN_SET(town_id,'{$town_id}')"];
                $where['bar_code']=$bar_code;
                $t=$order->where($where)->value('id');
                if(!$t){
                    return json(['code'=>0,'msg'=>'您所在区域不能管理该订单！']);
                }
                return json(['code'=>1,'data'=>$id]);
            }else{
                return json(['code'=>0,'msg'=>'没有此条码']);
            }
        }else{
            return $this->dlError();
        }
    }

    /*
     * 退出登录
     * */
    public function logOut(){
        session('userInfo',null);
        cookie('remember_password', null);
        cookie('remember_username', null);
        $this->redirect('Login/login');
    }
}
