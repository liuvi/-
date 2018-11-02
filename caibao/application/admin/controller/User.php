<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/9 0009
 * Time: 13:23
 */
namespace app\admin\controller;
use app\admin\model\UserModel;
use app\admin\model\RoleModel;
use think\Db;
class User extends Common{
    public $model;
    public function _initialize()
    {
        parent::_initialize();
        $this->model=new UserModel();
    }

    //用户列表
    public function index(){
        $where=[];
        $where['cc_admin.id']=array('gt',1);
        $lists=$this->model->getUserList($where,0,10);
        $this->assign('lists',$lists);
        return $this->fetch();
    }

    public function add(){
        if(request()->isAjax() && request()->isPost()){
            $param=input('post.');
            unset($param['file']);
            $param['password']=md5($param['password']);
            $msg=$this->model->insertUser($param);
            $accdata = array(
                'uid'=> $this->model['id'],
                'group_id'=> $param['groupid'],
            );
            $group_access = Db::name('auth_group_access')->insert($accdata);
            return json(['code'=>$msg['code'],'msg'=>$msg['msg'],'url'=>url('User/index')]);
        }else{
            $role=new RoleModel();
            $roleList=$role->where(['status'=>1,'id'=>['<>',1]])->select();
            $this->assign('roleList',$roleList);
            return view();
        }

    }

    public function add_post(){
        if(request()->isAjax()){
            $param=input('post.');
            unset($param['file']);
            $param['password']=md5($param['password']);
            $msg=$this->model->insertUser($param);
            $accdata = array(
                'uid'=> $this->model['id'],
                'group_id'=> $param['groupid'],
            );
            $group_access = Db::name('auth_group_access')->insert($accdata);
            return json(['code'=>$msg['code'],'msg'=>$msg['msg'],'url'=>url('User/index')]);
        }
    }

    public function edit(){
        if(request()->isAjax() && request()->isPost()){
            $param=input('post.');
            unset($param['file']);
            $param['password']=md5($param['password']);
            $msg=$this->model->updateUser($param);

            $group_access = Db::name('auth_group_access')->where('uid', $this->model['id'])->update(['group_id' => $param['groupid']]);
            return json(['code'=>$msg['code'],'msg'=>$msg['msg'],'url'=>url('User/index')]);
        }else{
            $id=input('param.id');
            $info=$this->model->getOne($id);
            $this->assign('info',$info);
            $role=new RoleModel();
            $roleList=$role->where(['status'=>1,'id'=>['<>',1]])->select();
            $this->assign('roleList',$roleList);
            return view(); 
        }

    }

    public function edit_post(){
        if(request()->isAjax()){
            $param=input('post.');
            unset($param['file']);
            $param['password']=md5($param['password']);
            $msg=$this->model->updateUser($param);

            $group_access = Db::name('auth_group_access')->where('uid', $this->model['id'])->update(['group_id' => $param['groupid']]);
            return json(['code'=>$msg['code'],'msg'=>$msg['msg'],'url'=>url('User/index')]);
        }
    }

    public function del(){
        if(request()->isGet()){
            $id=input('param.id');
            $msg=$this->model->delUser($id);
            return json(['code'=>$msg['code'],'msg'=>$msg['msg'],'url'=>url('User/index')]);
        }
    }

    //设置状态
    public function setStatus(){
        if(request()->isGet()){
            $id     =input('param.id');
            $status = Db::name('admin')->where('id',$id)->value('status');//得到字段值判断当前状态
            if($status==1)
            {
                $msg = Db::name('admin')->where('id',$id)->setField(['status'=>0]);
                return json(['code' => 1, 'msg' => '已禁止']);
            }
            else
            {
                $msg = Db::name('admin')->where('id',$id)->setField(['status'=>1]);
                return json(['code' => 0, 'msg' => '已开启']);
            }
        }
    }

    //批量删除
    public function batchdel(){
        if(request()->isGet()){
            $ids=input('param.ids');
            $ids=rtrim($ids,',');
            $msg=$this->model->batchDelete($ids);
            return json(['code'=>$msg['code'],'msg'=>$msg['msg'],'url'=>url('User/index')]);
        }
    }
}