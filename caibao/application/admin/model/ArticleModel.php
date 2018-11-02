<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/29 0029
 * Time: 09:56
 */
namespace app\admin\model;
use think\exception\ErrorException;
use think\exception\PDOException;
use think\Model;
use think\Db;
class ArticleModel extends Model{

    protected $name="article";

    /*
     * 获取文章列表
     * */
    public function getArticleList($where,$Nowpage,$limit){
        return $this->where($where)->page($Nowpage,$limit)->order('id desc')->select();
    }

    /*
     * 添加题库
     * */
    public function addArticle($param){
        try{
            $param['create_time']=time();
            $result = $this->save($param);
            if(false === $result){
                return ['code' => 0, 'data' => '', 'msg' => $this->getError(),'url'=>''];
            }else{
                return ['code' => 1, 'data' => '', 'msg' => '添加成功','url'=>url('Article/index')];
            }
        }catch (PDOException $e){
            return ['code' => 2, 'data' => '', 'msg' => $e->getMessage(),'url'=>''];
        }
    }

    /*
     * 修改文章
     * */

    public function editArticle($param){
        try{
            $result=$this->save($param,['id'=>$param['id']]);
            if(false===$result){
                return ['code'=>0,'data'=>'','msg'=>$this->getError(),'url'=>''];
            }else{
                return ['code'=>1,'data'=>'','msg'=>'修改成功','url'=>url('Article/index')];
            }
        }catch (PDOException $e){
            return ['code'=>2,'data'=>'','msg'=>$e->getMessage(),'url'=>''];
        }
    }

    /*
     * 获取一条数据
     * */

    public function getOne($id){
        return $this->where(['id'=>$id])->find();
    }

    /*
 * 删除文章
 * */
    public function delArt($id){
        try{
            if($this->where(['id'=>$id])->delete()){
                return ['code'=>1,'msg'=>'删除成功','url'=>url('Article/index')];
            }else{
                return ['code'=>0,'msg'=>'删除失败'];
            }
        }catch (PDOException $e){
            return ['code'=>2,'msg'=>$e->getMessage()];
        }
    }

}