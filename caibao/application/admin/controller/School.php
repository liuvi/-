<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/29 0029
 * Time: 08:54
 */
namespace app\admin\controller;
use think\Db;
use app\admin\model\SchoolModel;
use app\admin\model\SchoolClass;
class School extends Common{

    /*
     * 学校列表
     * */

    public function index(){
        $where=[];
        $this->parameter['name']=input('name');
        if($this->parameter['name']){
            $where['name']=['like','%'.$this->parameter['name'].'%'];
        }
        $Nowpage = input('get.page') ? input('get.page'):1;
        $limits = config('pagelimit');// 获取总条数
        $count = Db::name('School')->where($where)->count();//计算总页面
        $allpage = intval(ceil($count / $limits));
        $school=new SchoolModel();
        $dataList=$school->getSchoolList($where,$Nowpage,$limits);
        $this->listAssign['Nowpage']=$Nowpage; //当前页
        $this->listAssign['allpage']=$allpage; //总页数
        $this->listAssign['parameter']=$this->parameter;
        $this->assign($this->listAssign);
        if(input('get.page'))
        {
            return json($dataList);
        }
        return $this->fetch();
    }

    /*
     * 添加学校
     * */
    public function add(){
        if(request()->isPost() && request()->isAjax()){
            $param=input('post.');
            $school=new SchoolModel();
            $msg=$school->addSchool($param);
            return json($msg);
        }else{
            return view();
        }
    }

    /*
     * 修改学校
     * */

    public function edit(){
        $school=new SchoolModel();
        if(request()->isPost() && request()->isAjax()){
            $param=input('post.');
            $msg=$school->editSchool($param);
            return json($msg);
        }else{
            $id=input('get.id');
            if(!$id){
                return json(['code'=>0,'msg'=>'参数错误']);
            }
            $dataInfo=$school->getOne($id);
            $this->assign('dataInfo',$dataInfo);
            return view();
        }
    }

    /*
     * 删除学校
     * */
    public function del(){
        if(request()->isGet()){
            $id=input('param.id');
            if(!$id){
                return json(['code'=>0,'msg'=>'参数错误']);
            }
            $user=Db::name('Member')->where('sid',$id)->value('id');
            if($user){
                return json(['code'=>0,'msg'=>'该学校下有学生不能删除']);
            }
            $school=new SchoolModel();
            $msg=$school->delSchool($id);
            return json($msg);
        }
    }

    /*
     * 添加班级
     * */

    public function add_class(){
        if(request()->isPost() && request()->isAjax()){
            $param=input('post.');
            $school=new SchoolClass();
            $msg=$school->addClass($param);
            return json($msg);
        }else{
            $sid=input('param.id');
            $this->assign('sid',$sid);
            $this->getSchool();
            return view();
        }
    }

    /*
     * 获取学校
     * */
    public function getSchool(){
        $school=new SchoolModel();
        $dataList=$school->getAllSchoolList();
        $this->assign('dataList',$dataList);
    }

    /*
     * 查看班级
     * */
    public function show_class(){
        $where=[];
        $this->parameter['name']=input('name');
        if($this->parameter['name']){
            $where['title']=['like','%'.$this->parameter['name'].'%'];
        }
        $this->parameter['sid']=input('param.sid',0);
        $where['sid']=$this->parameter['sid'];
        $Nowpage = input('get.page') ? input('get.page'):1;
        $limits = config('pagelimit');// 获取总条数
        $count = Db::name('SchoolClass')->where($where)->count();//计算总页面
        $allpage = intval(ceil($count / $limits));
        $school=new SchoolClass();
        $dataList=$school->showClass($where,$Nowpage,$limits);
        $this->listAssign['Nowpage']=$Nowpage; //当前页
        $this->listAssign['allpage']=$allpage; //总页数
        $this->listAssign['parameter']=$this->parameter;
        $this->assign($this->listAssign);
        if(input('get.page'))
        {
            return json($dataList);
        }
        return $this->fetch();
    }

    /*
     * 修改班级
     * */
    public function edit_class(){
        $school=new SchoolClass();
        if(request()->isPost() && request()->isAjax()){
            $param=input('post.');
            $msg=$school->editClass($param);
            return json($msg);
        }else{
            $id=input('param.id');
            $dataInfo=$school->getOne($id);
            $this->assign('dataInfo',$dataInfo);
            $this->getSchool();
            return view();
        }
    }

    /*
     * 删除班级
     * */
    public function delclass(){
        if(request()->isGet()){
            $id=input('param.id');
            if(!$id){
                return json(['code'=>0,'msg'=>'参数错误']);
            }
            $user=Db::name('Member')->where('cid',$id)->value('id');
            if($user){
                return json(['code'=>0,'msg'=>'该班级下有学生不能删除']);
            }
            $school=new SchoolClass();
            $msg=$school->delSchoolClass($id);
            return json($msg);
        }
    }
}