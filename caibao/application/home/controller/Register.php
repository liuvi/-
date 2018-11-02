<?php
namespace app\home\controller;
use app\home\model\Topic;
use think\Db;
use app\home\model\School;
use app\home\model\RegisterModel;
class Register extends Common{

    public function _initialize()
    {
        if(session('MemberId')){
            $this->redirect('Activity/index');
        }
    }
    /*
     * 注册页面
     * */
    public function index(){
        $school=new School();
        $schoolList=$school->getSchoolList();
        $this->assign('SchoolList',$schoolList);
        return $this->fetch();
    }

    /*
     *根据学校获取班级
     * */
    public function getClass(){
        if(request()->isAjax()){
            $sid=input('param.sid');
            if(!$sid){
                return json(['code'=>0,'msg'=>'参数错误']);
            }
            $dataList=Db::name('SchoolClass')->where('sid',$sid)->order('sort desc')->select();
            return json(['code'=>1,'dataList'=>$dataList]);
        }
    }


    public function doReg(){
        if(IS_POST){
            $sid        =   input('post.sid');
            $cid        =   input('post.cid');
            $username   =   input('post.username');
            $tel        =   input('post.tel');
            $code       =   input('post.code');
            $c=session('codeSMS');
            //检测验证码
            if ($c['code_set'] != sha1($code) || !$code) {
                $status['status']=0;
                $status['msg']='验证码有误！';
                return json($status);
            }
            $data['username']=$username;
            $data['sid'] =$sid;
            $data['cid'] =$cid;
            $data['tel'] =$tel;
            $data['create_time']=time();
            $member=new RegisterModel();
            //检测是否注册
            $user = Db::name('Member')->where(array('tel'=>$tel))->find();
            if ($user) {
                $data['id']=$user['id'];
                $msg=$member->editReg($data);
                return json($msg);
            }else{
                $msg=$member->Reg($data);
                return json($msg);
            }
        }
    }

    public function getCode(){
        if(IS_POST){
            $tel    =input('tel');
            // 注册使用
            $times = session('getCodeTime');
            if ($times + 60 > time()) {
                $status['status']=0;
                $status['msg']   ="请求过于频繁，请稍后再试！！";
                return json($status);
            }
            $code=createCode(6);
            $content="您正在登录成都采宝，验证码为:{$code}，";
            $flag=getCodeNumber($tel,$code,30,$content);
            if($flag['status']==1){
                session('getCodeTime', time());
                $status['status']=1;
                $status['msg']=$flag['info'];
                return json($status);
            }else{
                $status['status']=0;
                $status['msg']=$flag['info'];
                return json($status);
            }

        }

    }
}