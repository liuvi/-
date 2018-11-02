<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/8 0008
 * Time: 17:23
 */
namespace app\admin\controller;
use app\admin\model\RoleModel;
use think\Db;
class Role extends Common{
    public $model;
    public function _initialize()
    {
        parent::_initialize();
        $this->model=new RoleModel();
    }

    public function index(){
        $lists=$this->model->getRole();
        $this->assign('lists',$lists);
        return $this->fetch();
    }

    //添加角色
    public function add(){
        if(request()->isAjax() && request()->isPost()) {
            $param=input('post.');
            $msg=$this->model->insertRole($param);
            return json(['code'=>$msg['code'],'msg'=>$msg['msg'],'url'=>url('Role/index')]);
        }else{
            //ajax请求使用此方法返回html
            return view();
        }

    }

    //修改角色
    public function edit(){
        if(request()->isAjax() && request()->isPost()) {
            $param=input('post.');
            $msg=$this->model->UpdateData($param);
            return json(['code'=>$msg['code'],'msg'=>$msg['msg'],'url'=>url('Role/index')]);
        }else{
            $id=input('param.id');
            $info=$this->model->getOne($id);
            $this->assign('info',$info);
            return view();
        }

    }


    //设置状态
    public function setStatus(){
        if(request()->isGet()){
            $id     =input('param.id');
            $status = Db::name('auth_group')->where('id',$id)->value('status');//得到字段值判断当前状态
            if($status==1)
            {
                $msg = Db::name('auth_group')->where('id',$id)->setField(['status'=>0]);
                return json(['code' => 1, 'msg' => '已禁止']);
            }
            else
            {
                $msg = Db::name('auth_group')->where('id',$id)->setField(['status'=>1]);
                return json(['code' => 0, 'msg' => '已开启']);
            }
        }
    }

    //删除角色
    public function del(){
        if(request()->isGet()){
            $id=input('param.id');
            $msg=$this->model->delRole($id);
            return json(['code'=>$msg['code'],'msg'=>$msg['msg'],'url'=>url('Role/index')]);
        }
    }

    //设置权限
    public function setaccess(){
       if(request()->isAjax()){
           $rule=input('post.rule');
           $id=input('post.id');
           $param=array(
               'id'=>$id,
               'rules'=>$rule
           );
           $msg=$this->model->editRules($param);
           return json(['code'=>$msg['code'],'msg'=>$msg['msg'],'url'=>url('Role/index')]);
       }
    }

    //获取节点
    public function getRules(){
        if(request()->isAjax()){
            $id=input('param.id');
            $lists=$this->model->getNodeInfo($id);
            return json(['code'=>1,'data'=>$lists,'msg'=>'success']);
        }
    }
}