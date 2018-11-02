<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/19 0019
 * Time: 11:07
 */
namespace app\home\model;
use think\Model;
class Member extends Model{
    protected $resultSetType = 'collection';

    /**
     * 用户登录
     *
     */
    public function login ($param)
    {
        $userInfo = self::getUsername($param['username']);

        if (empty($userInfo)) {
            return ['code'=>0, 'msg'=>'账户不存在'];
        }
        if ($userInfo['status']==0) {
            return ['code'=>0,'msg'=> '该账户已冻结请联系管理员'];
        }
        $password=md5($param['password']);
        if ($password!==$userInfo['password']) {
            return ['code'=>0,'msg'=>'登陆密码不正确'];
        }

        //记住账号密码
        if($param['rember']==1){
            cookie('remember_password',$password,3600*24*30);
            cookie('remember_username',$param['username'],3600*24*30);
        }
        session('userInfo', $userInfo);

        return ['code'=>1,'msg'=>'登陆成功','url'=>url('Index/index')];
    }

    /**
     * 根据账户获取信息
     *
     */
    public function getUsername ($username)
    {
        $dataInfo= $this->where(['username'=>$username])->find();
        return empty($dataInfo) ? [] : $dataInfo->toArray();
    }

    /*
     * 获取用户金额
     * */
    public function getUserMoney($uid){
        $money=$this->where(['id'=>$uid])->value('money');
        return $money;
    }

    /*
     * 设置金额数据
     * */
    public function setMoney($uid,$money){
        $result=$this->where(['id'=>$uid])->setInc('money',$money);
        return $result;
    }

    /*
     * 获取用户数据
     * */
    public function getMemberInfo($where){
        $dataInfo=$this->where($where)->find();
        return empty($dataInfo) ? [] : $dataInfo->toArray();
    }


    /**
     * 检测是否登录
     *
     */
    public function checkLogin() {
        $Member= session('userInfo');
        if (!$Member) {
            return false;
        } else {
            $where['id']=$Member['id'];
            $theMember = $this->getMemberInfo($where);
            if ( $theMember ) {
                return $theMember;
            } else {
                $this->logout();
                return false;
            }
        }
    }


    /*
     * 退出登录
     * */
    public function logOut(){
        session('userInfo',null);
        cookie('remember_password', null);
        cookie('remember_username', null);
    }

}