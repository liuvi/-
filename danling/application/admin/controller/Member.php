<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/5 0005
 * Time: 17:42
 */
namespace app\admin\controller;
use think\Db;
use app\admin\model\MemberModel;
use think\Validate;
use think\Response;
use app\admin\model\Money;
class Member extends Common{
    /*
     * 普通用户列表
     * */

    public function index(){
        $where=[];

        $this->parameter['username']=input('username','');
        if( $this->parameter['username'] ){
            $where['username']=['like','%'.$this->parameter['username'].'%'];
        }
        $where['type']=1;
        $Nowpage = input('get.page') ? input('get.page'):1;
        $limits = config('pagelimit');// 获取总条数
        $count = Db::name('Member')->where($where)->count();//计算总页面
        $allpage = intval(ceil($count / $limits));
        $member=new MemberModel();
        $lists=$member->getMemberList($where,$Nowpage,$limits);
        $this->listAssign['Nowpage']=$Nowpage; //当前页
        $this->listAssign['allpage']=$allpage; //总页数
        $this->listAssign['parameter']=$this->parameter;
        $this->assign($this->listAssign);
        if(input('get.page'))
        {
            return json($lists);
        }
        return $this->fetch();
    }
    /*
     * 收货人列表
     * */
    public function delivery(){
        $where=[];

        $this->parameter['username']=input('username','');
        if( $this->parameter['username'] ){
            $where['username']=['like','%'.$this->parameter['username'].'%'];
        }

        $where['type']=2;
        $Nowpage = input('get.page') ? input('get.page'):1;
        $limits = config('pagelimit');// 获取总条数
        $count = Db::name('Member')->where($where)->count();//计算总页面
        $allpage = intval(ceil($count / $limits));
        $member=new MemberModel();
        $lists=$member->getMemberList($where,$Nowpage,$limits);
        $this->listAssign['Nowpage']=$Nowpage; //当前页
        $this->listAssign['allpage']=$allpage; //总页数
        $this->listAssign['parameter']=$this->parameter;
        $this->assign($this->listAssign);
        if(input('get.page'))
        {
            return json($lists);
        }
        return $this->fetch();
    }

    /*
     * 添加收货人
     * */
    public function add(){
        if(request()->isAjax() && request()->isPost()){
            $param=input('post.');
            $param['type']=2;
            if(array_key_exists('rid',$param)){
                $param['rid']=implode(',',$param['rid']);
            }
            $validate = new \app\admin\validate\MemberValidate();
            if (!$validate->scene('create')->check($param)) {
                return json(['code'=>0,'msg'=>$validate->getError()]);
            }
            $member=new MemberModel();
            $param['password']=md5($param['password']);
            $msg=$member->addMember($param);
            return json($msg);
        }else{
            $this->getArea();
            return view();
        }
    }

    /*
     * 修改收货人
     * */
    public function edit(){
        $member=new MemberModel();
        if(request()->isAjax() && request()->isPost()){
            $param=input('post.');
            if(array_key_exists('rid',$param)){
                $param['rid']=implode(',',$param['rid']);
            }
            $msg=$member->editMember($param);
            return json($msg);
        }else{
            $id=input('param.id');
            if(!$id){
                return json(['code'=>0,'msg'=>'参数错误']);
            }
            $dataInfo=$member->getOne($id);
            $this->assign('dataInfo',$dataInfo);
            $this->getSelectTown($dataInfo['rid']);
            $this->getArea();
            return view();
        }
    }
    /*
     * 修改密码
     * */
    public function edit_pass(){
        $member=new MemberModel();
        if(request()->isAjax() && request()->isPost()){
            $password=input('post.password');
            $newpassword=input('post.newpassword');
            $id=input('post.id');
            $param['password']=$password;
            $param['newpassword']=$newpassword;
            $param['id']=$id;
            $validate = new \app\admin\validate\MemberValidate();
            if (!$validate->scene('pass')->check($param)) {
                return json(['code'=>0,'msg'=>$validate->getError()]);
            }
            $param['password']=md5($password);
            $msg=$member->editPass($param);
            return json($msg);
        }else{
            $id=input('param.id');
            if(!$id){
                return json(['code'=>0,'msg'=>'参数错误']);
            }
            $dataInfo=$member->getOne($id);
            $this->assign('dataInfo',$dataInfo);
            return view();
        }
    }

    /*
     * 删除
     * */

    public function del(){
        if(request()->isGet()){
            $id=input('param.id');
            $member=new MemberModel();
            $msg=$member->delMember($id);
            return json(['code'=>$msg['code'],'msg'=>$msg['msg'],'url'=>url('Member/delivery')]);
        }
    }

    //设置状态
    public function setStatus(){
        if(request()->isGet()){
            $id     =input('param.id');
            $status = Db::name('Member')->where('id',$id)->value('status');//得到字段值判断当前状态
            if($status==1)
            {
                $msg = Db::name('Member')->where('id',$id)->setField(['status'=>0]);
                return json(['code' => 1, 'msg' => '已禁止']);
            }
            else
            {
                $msg = Db::name('Member')->where('id',$id)->setField(['status'=>1]);
                return json(['code' => 2, 'msg' => '已开启']);
            }
        }
    }

    /*
     * 根据选中的镇获取
     * */
    public function getSelectTown($ids){
       $town= Db::name('Region')->whereIn('id',$ids)->field('id,town,area')->select();
       $this->assign('town',$town);
    }

    /*
     * 获取添加镇的上级 县
     * */
    public function getArea(){
        $area=Db::name('Region')->alias('a')->join('dl_china_area b','b.id=a.area','LEFT')->field('b.name,a.area')->group('a.area')->select();
        $this->assign('area',$area);
    }

    /*
     * 获取县下所有镇
     * */

    public function getTown(){
        $rid=Db::name('Member')->where('type',2)->field('rid')->select();
        $ids=implode(',',array_column($rid,'rid'));
        $areaid=input('get.area',0);
        $where['area']=$areaid;
        $where['id']=array('NOT IN',$ids);
        $areaList=Db::name('Region')->where($where)->order('sort desc')->select();
        return json(['code'=>1,'areaList'=>$areaList]);
    }

    /*
     * 提现申请
     * */
    public function money(){
        $where=[];

        $this->parameter['username']=input('username','');
        if( $this->parameter['username'] ){
            $where['b.username']=['like','%'.$this->parameter['username'].'%'];
        }
        $where['a.type']=2;
        $Nowpage = input('get.page') ? input('get.page'):1;
        $limits = config('pagelimit');// 获取总条数
        $count = Db::name('Money')->alias('a')->join('dl_member b','b.id=a.uid','LEFT')->where($where)->count();//计算总页面
        $allpage = intval(ceil($count / $limits));
        $money=new Money();
        $lists=$money->getMoneyList($where,$Nowpage,$limits);
        $this->listAssign['Nowpage']=$Nowpage; //当前页
        $this->listAssign['allpage']=$allpage; //总页数
        $this->listAssign['parameter']=$this->parameter;
        $this->assign($this->listAssign);
        if(input('get.page'))
        {
            return json($lists);
        }
        return $this->fetch();
    }

    /*
     * 审核查看
     * */
    public function examine(){
        $id=input('id',0);
        $money=new Money();
        $dataInfo=$money->getOne($id);
        if(request()->isPost() && request()->isAjax()){
            $status=input('post.status',0);
            $intro=input('post.intro','');
            if($status==0){
                return json(['code'=>0,'msg'=>'请选择审核状态']);
            }
            if(!$id){
                return json(['code'=>0,'msg'=>'参数错误']);
            }
            if($status==3 && $intro==''){
                return json(['code'=>0,'msg'=>'请填写不打款原因']);
            }
            $param['status']=$status;
            $param['intro']=$intro;
            $param['id']=$id;
            $msg=$money->editMoney($param);
            if($msg['code']==1){
                if($status==3){
                    //不打款金额返回给用户
                    Db::name('Member')->where(['id'=>$dataInfo['uid']])->setInc('money',$dataInfo['money']);
                }
            }
            return json($msg);
        }else{
            if(!$id){
                return json(['code'=>0,'msg'=>'参数错误']);
            }
            $this->assign('dataInfo',$dataInfo);
            return view();
        }
    }
}