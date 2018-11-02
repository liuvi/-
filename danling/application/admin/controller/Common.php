<?php
namespace app\admin\controller;
use app\admin\model\MenuModel;
use think\Controller;
use think\Db;
 class Common extends Controller{

     /**
      * 批量数据注入
      *
      * @var array
      */
     protected $listAssign = array();
     /*
      * 参数
      * */
     protected $parameter;
     
     public function _initialize()
     {
        $this->loginAuth();

     }

     public function loginAuth(){
         if(!session('uid')||!session('username')){
             $this->redirect('Login/login');
         }

         $auth = new \com\Auth();
         $module     = strtolower(request()->module());
         $controller = strtolower(request()->controller());
         $action     = strtolower(request()->action());
         $url        = $module."/".$controller."/".$action;
         //跳过检测以及主页权限
         if(session('uid')!=1){
             if(!in_array($url, ['admin/index/index','admin/index/indexhome','admin/upload/uploadajax','admin/region/getcity','admin/member/gettown'])){
                 if(!$auth->check($url,session('uid'))){
                     $this->error('抱歉，您没有操作权限');
                 }
             }
         }
         $menu=new MenuModel();
         //$where['status']=1;
         $menuList=$menu->getHomeMenu();
         $this->assign('menuList',$menuList);
     }
 }