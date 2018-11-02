<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/6 0006
 * Time: 16:03
 */
namespace app\admin\model;
use think\exception\ErrorException;
use think\exception\PDOException;
use think\Model;
use think\Db;
class Contact extends Model{
    protected $resultSetType = 'collection';

    /*
     * 联系人列表
     * */
    public function getMsgList($where,$Nowpage,$limit){
        $dataList=$this->where($where)->order('create_time desc')->page($Nowpage,$limit)->select();
        return empty($dataList) ? [] : $dataList->toArray();
    }
    /*
     * 添加联系人
     * */
    public function addMsg($param){
        try{
            $param['create_time']=time();
            $result=$this->allowField(true)->validate('ContactValidate')->save($param);
            if(false===$result){
                return ['code'=>0,'msg'=>$this->getError()];
            }else{
                return ['code'=>1,'msg'=>'添加成功','url'=>url('Contact/index')];
            }
        }catch(PDOException $e){
            return ['code'=>0,'msg'=>$e->getMessage()];
        }
    }

    /*
     * 修改联系人
     * */

    public function editMsg($param){
        try{
            $result=$this->allowField(true)->validate('ContactValidate')->save($param,$param['id']);
            if(false===$result){
                return ['code'=>0,'msg'=>$this->getError()];
            }else{
                return ['code'=>1,'msg'=>'修改成功','url'=>url('Contact/index')];
            }
        }catch(PDOException $e){
            return ['code'=>0,'msg'=>$e->getMessage()];
        }
    }

    /*
     * 获取一条数据
     * */
    public function getOne($id){
        return $this->where(['id'=>$id])->find();
    }

    /*
     * 删除联系人
     * */
    public function delMsg($id){
        try{
            if($this->where(['id'=>$id])->delete()){
                return ['code'=>1,'msg'=>'删除成功'];
            }else{
                return ['code'=>0,'msg'=>'删除失败'];
            }
        }catch (PDOException $e){
            return ['code'=>2,'msg'=>$e->getMessage()];
        }
    }

}