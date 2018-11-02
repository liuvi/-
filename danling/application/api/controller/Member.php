<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/12 0012
 * Time: 16:16
 */
namespace app\api\controller;
use think\Db;
use app\api\model\Address;
use app\api\model\Member as User;
use app\api\model\Money;
use app\api\model\Contact;
use think\Request;
class Member extends Common{

    public function _initialize()
    {
        parent::_initialize();
    }
    //获取授权用户的唯一标识
    public function getOpenIds(){
        if(request()->isPost()){
            $config=Db::name("Config")->column('name,value');
            $code       = input('post.code','');
            if(!$code){
                return json(['code'=>0,'msg'=>'参数错误']);
            }
            $appid      =  $config['appid'];//小程序唯一标识   (在微信小程序管理后台获取)
            $appsecret  =  $config['app_secret'];//小程序的 app secret (在微信小程序管理后台获取)
            $grant_type =  config('WX_AUTHORCODE'); //授权（必填）

            $params = "appid=".$appid."&secret=".$appsecret."&js_code=".$code."&grant_type=".$grant_type;
            $url = config('WX_URL').$params;
            $result = json_decode(httpGet($url),true);
            $data['openid']         = $result['openid'];
            $data['create_time']    = time();
            if($result['openid']){
                $member=new User();
                $msg=$member->addReg($data);
                return json($msg);
            }else{
                return json(['code'=>0,'msg'=>'授权失败']);
            }
        }else{
            return $this->dlError();
        }
    }


    //微信授权以获取用户头像,性别，微信名 并更新用户数据
    public function referUserInfo(){
        if (request()->isPost()){
            $userId = input('post.uid',0);
            if(!$userId){
                return json(['code'=>0,'msg'=>'登陆出错！']);
            }
            $data['headimg']    =   input('post.avatarUrl',''); //微信头像
            $data['username']   =   input('post.wxname',''); //微信昵称
            $data['sex']        =   input('post.gender',0);//性别  0 未知 1 男 2 女
            $data['id']         =   $userId;

            $member=new User();
            $msg=$member->saveUser($data);
            return json($msg);
        }else{
            return $this->dlError();
        }
    }

    /*
     *
     * 添加修改地址
     *
     * */
    public function addAddr(){
        if(request()->isPost()){
            $address=new Address();
            $param['uid']       =input('post.uid',0);
            $param['username']  =input('post.username','');
            $param['call']      =input('post.call','');
            $param['tel']       =input('post.tel','');
            $param['address']   =input('post.address','');
            $param['is_default']=input('post.is_default',0);
            $param['id']        =input('post.id',0);
            $param['town_id']   =input('post.town_id',0);
            if($param['town_id']==0){
                return json(['code'=>0,'msg'=>'未选择所属镇']);
            }
            if($param['uid']==0){
                return json(['code'=>0,'msg'=>'无效登录请求']);
            }
            if($param['id']===0){
                $msg=$address->addAddr($param);
            }else{
                $msg=$address->editAddr($param);
            }
            return json($msg);
        }else{
            return $this->dlError();
        }
    }

    /*
     * 获取地址详细信息
     * */
    public function getAddr(){
        if(request()->isPost()){
            $id=input('post.id',0);
            if(!$id){
                return json(['code'=>0,'msg'=>'参数错误']);
            }
            $address=new Address();
            $dataInfo=$address->getAdd($id);
            return json(['code'=>1,'msg'=>'获取成功','dataInfo'=>$dataInfo]);
        }else{
            return $this->dlError();
        }
    }

    /*
     * 获取地址列表
     * */

    public function getAddrList(){
        if(request()->isPost()){
            $uid=input('post.uid',0);
            if(!$uid){
                return json(['code'=>0,'msg'=>'未登录']);
            }
            $address=new Address();
            $msg=$address->getAddList($uid);
            return json($msg);
        }else{
            return $this->dlError();
        }
    }

    /*
     * 删除地址
     * */
    public function delAddr(){
        if(request()->isPost()){
            $id=input('post.id',0);
            if(!$id){
                return json(['code'=>0,'msg'=>'参数错误']);
            }
            $address=new Address();
            $msg=$address->delAdd($id);
            return json($msg);
        }else{
            return $this->dlError();
        }
    }

    /*
     * 设置默认地址
     * */
    public function setDefault(){
        if(request()->isPost()){
            $uid=input('post.uid',0);
            $id=input('post.id',0);
            if(!$id){
                return json(['code'=>0,'msg'=>'参数错误']);
            }
            if(!$uid){
                return json(['code'=>0,'msg'=>'用户信息错误']);
            }
            $address=new Address();
            $msg=$address->setDefault($uid,$id);
            return json($msg);
        }else{
            return $this->dlError();
        }
    }

    /*
     * 获取镇
     * */
    public function getTown(){
        if(request()->isGet()){
            $area=Db::name('Region')->field('id,town')->order('sort desc')->select();
            return json(['code'=>1,'msg'=>'获取成功','dataInfo'=>$area]);
        }else{
            return $this->dlError();
        }
    }


    /*
     * 个人资料
     * */
    public function myself(){
        if(request()->isGet()){
            $uid    =input('get.uid',0);
            if(!$uid){
                return json(['code'=>0,'msg'=>'参数错误']);
            }
            $user=new User();
            $msg=$user->getUserInfo($uid);
            return json($msg);
        }else{
            return $this->dlError();
        }
    }

    /*
     * 分享增加积分
     * */
    public function shareintegral(){
        if(request()->isPost()){
            $uid    =   input('post.uid',0);
            if(!$uid){
                return json(['code'=>0,'msg'=>'参数错误']);
            }
            $user =new User();
            $msg=$user->shareintegral($uid);
            return json($msg);
        }else{
            return $this->dlError();
        }
    }

    /*
     * 积分明细
     * */
    public function getIntegralList(){
        if(request()->isGet()){
            $uid    =input('get.uid',0);
            $page   =input('get.page',1);
            $limit  =config('SETLIMIT');
            if(!$uid){
                return json(['code'=>0,'msg'=>'参数错误']);
            }
            $where['uid']   =$uid;
            $user =new User();
            $msg=$user->getIntergral($where,$page,$limit);
            return json($msg);
        }else{
            return $this->dlError();
        }
    }

    /*
     * 提现申请
     * */
    public function forward(){
        if(request()->isPost()){
            $param['uid']    =input('post.uid',0);
            $param['money']  =input('post.money',0);
            $param['weixin'] =input('post.weixin','');
            if(!$param['uid'] || !$param['money']){
                return json(['code'=>0,'msg'=>'参数错误']);
            }
            if($param['weixin']==''){
                return json(['code'=>0,'msg'=>'请填写要到帐的微信号']);
            }
            $user = new User();
            $msg=$user->subForward($param);
            return json($msg);
        }else{
            return $this->dlError();
        }
    }

    /*
     * 获取提现记录
     * */

    public function getMoneyList(){
        if(request()->isGet()){
            $uid    =   input('get.uid',0);
            $page   =   input('get.page',1);
            $limit  =   config('SETLIMIT');
            if(!$uid){
                return ['code'=>0,'msg'=>'登录出错'];
            }
            $where['uid']=$uid;
            $money=new Money();
            $count=Db::name('Money')->where($where)->count();
            $allpage = intval(ceil($count / $limit));
            $dataList=$money->getMoney($where,$page,$limit);
            return json(['code'=>1,'msg'=>'获取成功','dataInfo'=>['dataList'=>$dataList,'allpage'=>$allpage]]);
        }else{
            return $this->dlError();
        }
    }

    /*
     * 获取默认地址
     * */

    public function getDefault(){
        if(request()->isGet()){
            $uid=input('get.uid',0);
            if(!$uid){
                return json(['code'=>0,'msg'=>'用户信息错误']);
            }
            $address=new Address();
            $msg=$address->getDefault($uid);
            return json($msg);
        }else{
            return $this->dlError();
        }
    }

    /*
     * 获取联系人
     * */

    public function getContact(){
        if(request()->isGet()){
            $page   =   input('get.page',1);
            $limit  =   config('SETLIMIT');
            $contact=new Contact();
            $where['status']=1;
            $msg=$contact->getContactList($where,$page,$limit);
            return json($msg);
        }else{
            return $this->dlError();
        }
    }


    public function sedCode(){
        if(request()->isPost()){
            $mobile=I('post.tel');
            if(!$mobile){
                return json(['code'=>0,'msg'=>'请填写手机号']);
            }
            $times =  session('getCodeTime');
            if ($times + 60 > time()) {
                $status['code']=0;
                $status['msg']   ="请求过于频繁，请稍后再试！！";
                return json($status);
            }
            $code=createCode(6);
            $content="您正在添加地址，验证码为:{$code}，";
            $flag=getCodeNumber($mobile,$code,30,$content);
            if($flag['status']==1){
                session('getCodeTime', time());
                $status['code']=1;
                $status['msg']=$flag['info'];
                return json($status);
            }else{
                $status['code']=0;
                $status['msg']=$flag['info'];
                return json($status);
            }
        }else{
            return $this->dlError();
        }
    }
}