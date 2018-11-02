<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/5 0005
 * Time: 17:42
 */
namespace app\admin\controller;
use think\Db;
use app\admin\model\Contact as msg;
class Contact extends Common{
    /*
     * 联系人
     * */

    public function index(){
        $where=[];
        $this->parameter['name']=input('name','');
        $this->parameter['tel']=input('tel','');
        if( $this->parameter['name'] ){
            $where['name']=['like','%'.$this->parameter['name'].'%'];
        }
        if($this->parameter['tel']){
            $where['tel']=['like','%'.$this->parameter['tel'].'%'];
        }

        $Nowpage = input('get.page') ? input('get.page'):1;
        $limits = config('pagelimit');// 获取总条数
        $count = Db::name('Contact')->where($where)->count();//计算总页面
        $allpage = intval(ceil($count / $limits));
        $msg=new Msg();
        $lists=$msg->getMsgList($where,$Nowpage,$limits);
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
     * 添加联系人
     * */
    public function add(){
        if(request()->isAjax() && request()->isPost()){
            $param=input('post.');
            $contact=new Msg();
            $msg=$contact->addMsg($param);
            return json($msg);
        }else{
            return view();
        }
    }

    /*
     * 修改联系人
     * */
    public function edit(){
        $contact=new Msg();
        if(request()->isAjax() && request()->isPost()){
            $param=input('post.');
            $msg=$contact->editMsg($param);
            return json($msg);
        }else{
            $id=input('param.id');
            if(!$id){
                return json(['code'=>0,'msg'=>'参数错误']);
            }
            $dataInfo=$contact->getOne($id);
            $this->assign('dataInfo',$dataInfo);
            return view();
        }
    }



    //设置状态
    public function setStatus(){
        if(request()->isGet()){
            $id     =input('param.id');
            $status = Db::name('Contact')->where('id',$id)->value('status');//得到字段值判断当前状态
            if($status==1)
            {
                $msg = Db::name('Contact')->where('id',$id)->setField(['status'=>0]);
                return json(['code' => 1, 'msg' => '已禁止']);
            }
            else
            {
                $msg = Db::name('Contact')->where('id',$id)->setField(['status'=>1]);
                return json(['code' => 0, 'msg' => '已开启']);
            }
        }
    }

    /*
 * 删除联系人
 * */
    public function del(){
        if(request()->isGet()){
            $id=input('param.id');
            $contact=new Msg();
            $msg=$contact->delMsg($id);
            return json(['code'=>$msg['code'],'msg'=>$msg['msg'],'url'=>url('Contact/index')]);
        }
    }

}