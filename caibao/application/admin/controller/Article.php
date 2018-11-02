<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/5 0005
 * Time: 17:42
 */
namespace app\admin\controller;
use think\Db;
use app\admin\model\ArticleModel;
class Article extends Common{
    protected $pagelist;
    public function _initialize()
    {
        parent::_initialize();
        $this->pagelist = array(
            array('id'=>1,'name'=>'活动说明'),
            array('id'=>2,'name'=>'奖品说明'),
        );
    }

    //文章列表
    public function index(){
        $where=[];
        $this->parameter['name']=input('name');
        if($this->parameter['name']){
            $where['title']=['like','%'.$this->parameter['name'].'%'];
        }
        $Nowpage = input('get.page') ? input('get.page'):1;
        $limits = config('pagelimit');// 获取总条数
        $count = Db::name('Article')->where($where)->count();//计算总页面
        $allpage = intval(ceil($count / $limits));
        $article=new ArticleModel();
        $dataList=$article->getArticleList($where,$Nowpage,$limits);
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
     * 添加文章
     * */
    public function add(){
        if(request()->isPost() && request()->isAjax()){
            $param=input('post.');
            $article=new ArticleModel();
            $msg=$article->addArticle($param);
            return json($msg);
        }else{
            $this->assign('pagelist',$this->pagelist);
            return $this->fetch();
        }

    }

    /*
 * 编辑文章
 * */
    public function edit(){
        $article=new ArticleModel();
        if(request()->isPost() && request()->isAjax()){
            $param=input('post.');

            $msg=$article->editArticle($param);
            return json($msg);
        }else{
            $id=input('param.id');
            $dataInfo=$article->getOne($id);
            $this->assign('dataInfo',$dataInfo);
            $this->assign('pagelist',$this->pagelist);
            return $this->fetch();
        }

    }

    /*
  * 删除文章
  * */
    public function del(){
        if(request()->isGet()){
            $id=input('param.id');
            if(!$id){
                return json(['code'=>0,'msg'=>'参数错误']);
            }
            $article=new ArticleModel();
            $msg=$article->delArt($id);
            return json($msg);
        }
    }
}