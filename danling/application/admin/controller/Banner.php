<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/5 0005
 * Time: 17:42
 */
namespace app\admin\controller;
use think\Db;
use app\admin\model\Banner as adver;
class Banner extends Common{
    /*
     * 广告列表
     * */

    public function index(){
        $dataList=Db::name('Banner')->order('sort desc')->select();
        $this->assign('dataList',$dataList);
        return $this->fetch();
    }

    /*
     * 添加广告
     * */
    public function add(){
        if(request()->isAjax() && request()->isPost()){
            $param=input('post.');
            $banner=new Adver();
            $msg=$banner->addBanner($param);
            return json($msg);
        }else{
            return view();
        }
    }

    /*
     * 修改广告
     * */
    public function edit(){
        $banner=new Adver();
        if(request()->isAjax() && request()->isPost()){
            $param=input('post.');
            $msg=$banner->editBanner($param);
            return json($msg);
        }else{
            $id=input('param.id');
            if(!$id){
                return json(['code'=>0,'msg'=>'参数错误']);
            }
            $dataInfo=$banner->getOne($id);
            $this->assign('dataInfo',$dataInfo);
            return view();
        }
    }

    /*
 * 删除广告
 * */
    public function del(){
        if(request()->isGet()){
            $id=input('param.id');
            $banner=new Adver();
            $msg=$banner->delBanner($id);
            return json(['code'=>$msg['code'],'msg'=>$msg['msg'],'url'=>url('Banner/index')]);
        }
    }

}