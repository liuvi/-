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
        $Nowpage = input('get.page') ? input('get.page'):1;
        $limits = config('pagelimit');// 获取总条数
        $where['a.id']=array('gt',1);
        $count = Db::name('admin')->alias('a')->where($where)->count();//计算总页面
        $allpage = intval(ceil($count / $limits));
        $lists=$this->model->getUserList($where,$Nowpage,$limits);
        $this->assign('Nowpage', $Nowpage); //当前页
        $this->assign('allpage', $allpage); //总页数
        $this->assign('lists',$lists);
        if(input('get.page'))
        {
            foreach ($lists as &$value){
                $value['last_login_time']=$value['last_login_time']?date('Y-m-d H:i:s',$value['last_login_time']):'还未登录';
            }
            return json($lists);
        }
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
            return $this->fetch();
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