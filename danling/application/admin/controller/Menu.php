<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/6 0006
 * Time: 11:24
 */
namespace app\admin\controller;
use think\Db;
use app\admin\model\MenuModel;
class Menu extends Common{
    public $model;
    public function _initialize()
    {
        parent::_initialize();
        $this->model=new MenuModel();
    }

    public function index(){
        $this->CgetMenu();
        return $this->fetch();
    }

    //添加菜单
    public function add_menu(){
        if(request()->isAjax() && request()->isPost()){
            $param=input('post.');
            $msg= $this->model->insertMenu($param);
            return json(['code' => $msg['code'], 'data' => $msg['data'], 'msg' => $msg['msg'],'url'=>$msg['url']]);
        }else{
            $this->CgetMenu();
            //ajax请求这样返回 html不会被转义
            return view();
        }

    }

    //修改菜单
    public function edit_menu(){
        if(request()->isAjax() && request()->isPost()){
            $param=input('post.');
            $msg=$this->model->editMenu($param);
            return json(['code'=>$msg['code'],'data'=>'','msg'=>$msg['msg'],'url'=>$msg['url']]);
        }else{
            $this->CgetMenu();
            $id=input('param.id');
            $info=$this->model->getOneMenu($id);
            $this->assign('info',$info);
            return view();
        }

    }

    // 获取菜单
    public function CgetMenu(){
        $lists=getTree($this->model->getMenu());
        $this->assign('lists',$lists);
    }

    //删除菜单
    public function del_menu(){
        if(request()->isGet()){
            $id=input('param.id');
            $pid= $this->model->where(['pid'=>$id])->field('pid')->find();
            if($pid){
                return json(['code'=>0,'msg'=>'该菜单有子菜单不能删除']);
            }
            $msg=$this->model->delMenu($id);
            return json(['code'=>$msg['code'],'msg'=>$msg['msg'],'url'=>url('Menu/index')]);
        }
    }

    //设置状态
    public function setStatus(){
        if(request()->isGet()){
            $id     =input('param.id');
            $status = Db::name('auth_rule')->where('id',$id)->value('status');//得到字段值判断当前状态
            if($status==1)
            {
                $msg = Db::name('auth_rule')->where('id',$id)->setField(['status'=>0]);
                return json(['code' => 1, 'msg' => '已禁止']);
            }
            else
            {
                $msg = Db::name('auth_rule')->where('id',$id)->setField(['status'=>1]);
                return json(['code' => 0, 'msg' => '已开启']);
            }
        }
    }

    //更新排序
    public function menuOrder(){
        if (request()->isAjax()){
            $param = input('post.');
            $auth_rule = Db::name('auth_rule');
            foreach ($param as $id => $sort){
                $auth_rule->where(array('id' => $id ))->setField('sort' , $sort);
            }
            return json(['code' => 1, 'msg' => '排序更新成功','url'=>url('Menu/index')]);
        }
    }
}