<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/19 0019
 * Time: 10:57
 */
namespace app\home\controller;
use think\Controller;
use app\home\model\Member;
class Login extends Controller{

    public function _initialize()
    {
        $info=session('userInfo');
        if($info['id']){
            $this->redirect('Index/index');
        }
    }

    /*
     * 登录页面
     * */
    public function login(){

        $info=session('userInfo');
        return $this->fetch();
    }

    /*
     * 提交登录
     * */

    public function doLogin(){
        if(request()->isPost()){
            $param['username']=input('post.username','');
            $param['password']=input('post.password','');
            $param['rember']  =input('post.rember',0);
            $validate = new \app\home\validate\MemberValidate();
            if (!$validate->check($param)) {
                return json(['code'=>0,'msg'=>$validate->getError()]);
            }
            $member=new Member();
            $msg=$member->login($param);
            return json($msg);
        }else{
            return json(['code'=>0,'msg'=>'无效请求']);
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