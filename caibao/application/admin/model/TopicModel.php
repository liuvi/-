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
class TopicModel extends Model{
    protected $resultSetType = 'collection';
    protected $name="topic";

    /*
     * 获取题库列表
     * */
    public function getTopicList($where,$Nowpage,$limit){
        return $this->where($where)->page($Nowpage,$limit)->order('id desc')->select();
    }
    /*
     * 添加题库
     * */

    public function addTopic($param){
        try{
            $param['create_time']=time();
            $result = $this->validate('TopicValidate')->save($param);
            if(false === $result){
                return ['code' => 0, 'data' => '', 'msg' => $this->getError(),'url'=>''];
            }else{
                return ['code' => 1, 'data' => '', 'msg' => '添加成功','url'=>url('Topic/index')];
            }
        }catch (PDOException $e){
            return ['code' => 2, 'data' => '', 'msg' => $e->getMessage(),'url'=>''];
        }
    }

    /*
     * 修改题库
     * */

    public function editTopic($param){
        try{
            $result=$this->validate('TopicValidate')->save($param,['id'=>$param['id']]);

            if(false===$result){
                return ['code'=>0,'data'=>'','msg'=>$this->getError(),'url'=>''];
            }else{
                return ['code'=>1,'data'=>'','msg'=>'修改成功','url'=>url('Topic/index')];
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
 * 删除题库
 * */
    public function delTopic($id){
        try{
            if($this->where(['id'=>$id])->delete()){
                return ['code'=>1,'msg'=>'删除成功','url'=>url('Topic/index')];
            }else{
                return ['code'=>0,'msg'=>'删除失败'];
            }
        }catch (PDOException $e){
            return ['code'=>2,'msg'=>$e->getMessage()];
        }
    }

    /*
 * 批量删除
 * */
    public function batchDelTopic($ids){
        try{
            if($this->destroy($ids)){
                return ['code'=>1,'msg'=>'删除成功'];
            }else{
                return ['code'=>0,'msg'=>'删除失败'];
            }
        }catch (PDOException $e){
            return ['code'=>2,'msg'=>$e->getMessage()];
        }
    }

}