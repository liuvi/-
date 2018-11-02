<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/5 0005
 * Time: 17:42
 */
namespace app\admin\controller;
use think\Db;
use app\admin\model\Category as Cate;
use app\admin\model\Goods;
class Category extends Common{
    public function _initialize()
    {
        parent::_initialize();
    }
    //分类列表
    public function index(){
        $where=[];
        $this->parameter['title']=input('title','');
        if( $this->parameter['title'] ){
            $where['title']=['like','%'.$this->parameter['title'].'%'];
        }

        $Nowpage = input('get.page') ? input('get.page'):1;
        $limits = config('pagelimit');// 获取总条数
        $count = Db::name('Category')->where($where)->count();//计算总页面
        $allpage = intval(ceil($count / $limits));
        $cate=new Cate();
        $lists=$cate->getCateList($where,$Nowpage,$limits);
        $this->listAssign['Nowpage']=$Nowpage; //当前页
        $this->listAssign['allpage']=$allpage; //总页数
        $this->listAssign['parameter']=$this->parameter;
        $this->assign($this->listAssign);
        if(input('get.page'))
        {
            return json($lists);
        }
        return $this->fetch();
    }

    /*
     * 添加分类
     * */
    public function add(){
        if(request()->isAjax() && request()->isPost()){
            $param=input('post.');
            $cate=new Cate();
            $msg=$cate->addCate($param);
            return json($msg);
        }else{
            return view();
        }
    }

    /*
     * 修改分类
     * */
    public function edit(){
        $cate=new Cate();
        if(request()->isAjax() && request()->isPost()){
            $param=input('post.');
            $msg=$cate->editCate($param);
            return json($msg);
        }else{
            $id=input('param.id');
            if(!$id){
                return json(['code'=>0,'msg'=>'参数错误']);
            }
            $dataInfo=$cate->getOne($id);
            $this->assign('dataInfo',$dataInfo);
            return view();
        }
    }


    //设置状态
    public function setStatus(){
        if(request()->isGet()){
            $id     =input('param.id');
            $status = Db::name('Category')->where('id',$id)->value('status');//得到字段值判断当前状态
            if($status==1)
            {
                $msg = Db::name('Category')->where('id',$id)->setField(['status'=>0]);
                return json(['code' => 1, 'msg' => '已禁止']);
            }
            else
            {
                $msg = Db::name('Category')->where('id',$id)->setField(['status'=>1]);
                return json(['code' => 0, 'msg' => '已开启']);
            }
        }
    }

    public function SetCateStatus(){
        if(request()->isGet()){
            $id     =input('param.id');
            $field  =input('param.field');
            $sta    =input('param.sta');
            $res=Db::name('Category')->where('id',$id)->update([$field=>$sta]);
            if($res){
                return json(['code' => 1, 'msg' => '设置成功']);
            }else{
                return json(['code' => 0, 'msg' => '设置失败']);
            }
        }
    }

    /*
     * 删除分类
     * */
    public function del(){
        if(request()->isGet()){
            $id=input('param.id');
            $cate=new Cate();
            $msg=$cate->delCate($id);
            return json(['code'=>$msg['code'],'msg'=>$msg['msg'],'url'=>url('Category/index')]);
        }
    }

    /*
     * 回收价格列表
     * */
    public function showrec(){
        $where=[];
        $this->parameter['name']=input('name','');
        $this->parameter['cid']=input('cid',0);
        if( $this->parameter['name'] ){
            $where['name']=['like','%'.$this->parameter['name'].'%'];
        }
        $where['cid']=$this->parameter['cid'];
        $Nowpage = input('get.page') ? input('get.page'):1;
        $limits = config('pagelimit');// 获取总条数
        $count = Db::name('Goods')->where($where)->count();//计算总页面
        $allpage = intval(ceil($count / $limits));
        $goods=new Goods();
        $lists=$goods->getGoodsList($where,$Nowpage,$limits);
        $this->listAssign['Nowpage']=$Nowpage; //当前页
        $this->listAssign['allpage']=$allpage; //总页数
        $this->listAssign['parameter']=$this->parameter;
        $this->assign($this->listAssign);
        if(input('get.page'))
        {
            return json($lists);
        }
        return $this->fetch();
    }

    /*
     * 添加回收价格
     * */
    public function addrec(){
        if(request()->isAjax() && request()->isPost()){
            $param=input('post.');
            $goods=new Goods();
            $msg=$goods->addGoods($param);
            return json($msg);
        }else{
            $cid=input('id');
            $this->getCateList();
            $this->assign('cid',$cid);
            return view();
        }
    }

    /*
     *  修改回收价格
     * */

    public function editrec(){
        $goods=new Goods();
        if(request()->isAjax() && request()->isPost()){
            $param=input('post.');
            $msg=$goods->editGoods($param);
            return json($msg);
        }else{
            $id=input('id');
            if(!$id){
                return json(['code'=>0,'msg'=>'参数错误']);
            }
           // echo strtotime(date('Y-m-d',time()).'-2 day');
            $dataInfo=$goods->getOne($id);
            $this->assign('dataInfo',$dataInfo);
            $this->getCateList();
            $this->getHistor($id);
            return view();
        }
    }

    /*
     * 获取历史价格
     * */
    public function getHistor($goods_id){
        $start_time = strtotime (date('Ymd', strtotime("-15 day")));
        $end_time = strtotime ( date('Ymd', strtotime('1 day')));
        $where['create_time'] = array('between',array($start_time,$end_time));
        $where['goods_id']=$goods_id;
        $his=Db::name('History')->where($where)->select();
        $price=array_column($his,'price');
        $times=array_column($his,'create_time');
        foreach ($times as &$value){
            $value=date('m月d',$value);
        }
        $pricelist=array('price'=>json_encode($price),'times'=>json_encode($times));
        $this->assign('priceList',$pricelist);
    }

    /*
     * 获取分类
     * */

    public function getCateList(){
        $cate=new Cate();
        $catelist=$cate->getCate();
        $this->assign('catelist',$catelist);
    }

}