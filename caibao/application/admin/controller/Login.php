<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;
use app\admin\model\RoleModel;
class Login extends controller{
    public function login(){
        return $this->fetch('login');
    }

    public function doLogin(){
        $username = input("param.username");
        $password = input("param.password");
        $code     = input("param.code");
        if($username==''){
            return json(['code' =>0, 'msg' => '用户名不能为空']);
        }
        if($password==''){
            return json(['code' =>0, 'msg' => '密码不能为空']);
        }
        $verify = new \com\Verify();
        if (!$verify->check($code)) {
            return json(['code' => 0, 'msg' => '验证码错误']);
        }
        $hasUser = Db::name('admin')->where('username', $username)->find();
        if(empty($hasUser)){
            return json(['code' => 0, 'msg' => '管理员不存在']);
        }
        if(1 != $hasUser['status']){
            return json(['code' => 0, 'msg' => '该账号被禁用']);
        }
        if(md5($password) != $hasUser['password']){
            return json(['code' => 0,  'msg' => '账号或密码错误']);
        }

        //获取该管理员的角色信息
        $user = new RoleModel();
        $info = $user->getRoleInfo($hasUser['groupid']);
        session('uid', $hasUser['id']);         //用户ID
        session('username', $hasUser['username']);  //用户名
        session('portrait', $hasUser['portrait']); //用户头像
        session('rolename', $info['title']);    //角色名
        session('rule', $info['rules']);        //角色节点
        session('name', $info['name']);         //角色权限
        //更新管理员状态
        $param = [
            'loginnum' => $hasUser['loginnum'] + 1,
            'last_login_ip' => request()->ip(),
            'last_login_time' => time(),
        ];
        Db::name('admin')->where('id', $hasUser['id'])->update($param);
        return json(['code' => 1, 'url' => url('index/index'), 'msg' => '登录成功！']);
    }

    /**
     * 验证码
     * @return
     */
    public function checkVerify()
    {
        $verify = new \com\Verify();
        $verify->imageH = 34;
        $verify->imageW = 90;
        $verify->codeSet = '0123456789';
        $verify->length = 4;
        $verify->useNoise = false;
        $verify->fontSize = 14;
        return $verify->entry();
    }


    /**
     * 退出登录
     * @return
     */
    public function loginOut()
    {
        session(null);
        $this->redirect('Login/login');
    }
}