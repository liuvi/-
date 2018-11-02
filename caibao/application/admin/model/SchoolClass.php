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
class SchoolClass extends Model{
    protected $resultSetType = 'collection';
    /*
     * 添加班级
     * */

    public function addClass($param){
        try{
            $param['create_time']=time();
            $result = $this->validate('SchoolClassValidate')->save($param);
            if(false === $result){
                return ['code' => 0, 'data' => '', 'msg' => $this->getError(),'url'=>''];
            }else{
                return ['code' => 1, 'data' => '', 'msg' => '添加成功','url'=>url('School/show_class').'?sid='.$param['sid']];
            }
        }catch (PDOException $e){
            return ['code' => 2, 'data' => '', 'msg' => $e->getMessage(),'url'=>''];
        }
    }

    /*
     * 修改班级
     * */

    public function editClass($param){
        try{
            $result=$this->validate('SchoolClassValidate')->save($param,['id'=>$param['id']]);
            if(false===$result){
                return ['code'=>0,'data'=>'','msg'=>$this->getError(),'url'=>''];
            }else{
                return ['code'=>1,'data'=>'','msg'=>'修改成功','url'=>url('School/show_class').'?sid='.$param['sid']];
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
 * 删除班级
 * */
    public function delSchoolClass($id){
        try{
            if($this->where(['id'=>$id])->delete()){
                return ['code'=>1,'msg'=>'删除成功','url'=>url('School/show_class').'?sid='.$_GET['sid']];
            }else{
                return ['code'=>0,'msg'=>'删除失败'];
            }
        }catch (PDOException $e){
            return ['code'=>2,'msg'=>$e->getMessage()];
        }
    }

    /*
     * 获取学校下面班级
     * */
    public function showClass($where,$Nowpage,$limit){
        $dataList= $this->where($where)->page($Nowpage,$limit)->order('sort desc')->select();
        return empty($dataList) ? [] : $dataList->toArray();
    }


}