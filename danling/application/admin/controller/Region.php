<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/5 0005
 * Time: 17:42
 */
namespace app\admin\controller;
use think\Db;
use app\admin\model\Region as Area;
class Region extends Common{
    /*
     * 广告列表
     * */

    public function index(){
        $where=[];
        $this->parameter['town']=input('town','');
        if( $this->parameter['town'] ){
            $where['a.town']=['like','%'.$this->parameter['town'].'%'];
        }

        $Nowpage = input('get.page') ? input('get.page'):1;
        $limits = config('pagelimit');// 获取总条数
        $count = Db::name('Region')->alias('a')->where($where)->count();//计算总页面
        $allpage = intval(ceil($count / $limits));
        $area=new Area();
        $lists=$area->getRegList($where,$Nowpage,$limits);
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
     * 添加地区
     * */
    public function add(){
        if(request()->isAjax() && request()->isPost()){
            $param=input('post.');
            $area=new Area();
            $msg=$area->addReg($param);
            return json($msg);
        }else{
            $this->getProvince();
            return $this->fetch();
        }
    }

    /*
     * 修改地区
     * */
    public function edit(){
        $area=new Area();
        if(request()->isAjax() && request()->isPost()){
            $param=input('post.');
            $msg=$area->editReg($param);
            return json($msg);
        }else{
            $id=input('param.id');
            if(!$id){
                return json(['code'=>0,'msg'=>'参数错误']);
            }
            $dataInfo=$area->getOne($id);
            $this->assign('dataInfo',$dataInfo);
            $this->getProvince();//省份
            $where['pid']=$dataInfo['province'];
            $city=Db::name("ChinaArea")->where($where)->select();
            $this->assign('city',$city);//城市
            $where['pid']=$dataInfo['city'];
            $area=Db::name("ChinaArea")->where($where)->select();
            $this->assign('area',$area);//县
            return view();
        }
    }

    /*
 * 删除地区
 * */
    public function del(){
        if(request()->isGet()){
            $id=input('param.id');
            $area=new Area();
            $msg=$area->delReg($id);
            return json(['code'=>$msg['code'],'msg'=>$msg['msg'],'url'=>url('Region/index')]);
        }
    }

    /*
     * 获取省
     * */
    public function getProvince(){
        $where['pid']=0;
        $lists=Db::name('ChinaArea')->where($where)->select();
        $this->assign('lists',$lists);
    }

    /*
     * 根据pid获取下级区域
     * */
    public function getCity(){
        $pid=input('get.pid',0);
        $where['pid']=$pid;
        $lists=Db::name('ChinaArea')->where($where)->select();
        return json(['code'=>1,'lists'=>$lists]);
    }

}