<?php
namespace app\home\controller;
use think\Controller;
use think\Db;
use app\home\model\Member;
class Common extends Controller{

    public $userInfo;

    public function _initialize()
    {
        Vendor("jssdk.jssdk");
        $jssdk = new \JSSDK("wxef5e4280bcb3e13e", "c69b5224e6272ce06d0faf9a959dfa58 ");
        $signPackage = $jssdk->GetSignPackage();
        $this->assign('signPackage',$signPackage);

        $this->setMember();
        $this->getLoginInfo();
    }

    /*
     * 无效请求
     * */
    public function dlError(){
        return json(['code'=>0,'msg'=>'无效请求']);
    }

    /*
     * 验证登录
     *
     * */

    public function checkLogin(){
        $info=session('userInfo');
        if(!$info['id']){
            $this->redirect('Login/login');
        }
        $this->userInfo=$info;
    }



    public function getLoginInfo(){
        $info=session('userInfo');
        if(!$info['id']){
            if ( request()->isAjax()  ) {
                $return['code']=0;
                $return['msg'] = '登录失效请刷新重试！';
                $return['url'] =url("Login/login");
                return json($return);
            }
            die("<script>location.href='".url("Login/login")."';</script>");
        }
    }

    /**
     * 获取用户信息
     */
    private function setMember() {
        $member=new Member();

        // 判断是否登录
        $theMember = $member->checkLogin();
        if ( $theMember ) {
            $this->userInfo = $theMember;
        } else {
            $this->userInfo = $this->autoLogin();

        }

        // 如果被删除，则退出登录状态
        if ( $this->userInfo && $this->userInfo[ 'status' ] == 0 ) {
            $member->logout();
        }

    }

    /**
     * 用户自动登录
     *
     * @return array
     */
    public function autoLogin() {
        $member=new Member();
        // 用户信息
        $theMember = [];
        // 获取cookie
        if (cookie('remember_password') && cookie('remember_username')) {
            $map = [];
            $map[ 'username' ] = cookie('remember_username');
            $map[ 'password' ] = cookie('remember_password');
            $map[ 'status' ] = 1;
            $theMember = $member->getMemberInfo($map);
            if ( $theMember ) {
                // 设置用户登录状态
                session('userInfo',$theMember);
            }
        }
        return $theMember;
    }

}