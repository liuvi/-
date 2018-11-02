<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/29 0029
 * Time: 09:00
 */
namespace app\admin\model;
use think\exception\ErrorException;
use think\exception\PDOException;
use think\Model;
use think\Db;
class SchoolModel extends Model{
    protected $name='School';
    //开启时间戳自动写入
    /*
     * 学校列表
     * */
    public function getSchoolList($where,$Nowpage,$limit){
        return $this->where($where)->order('sort desc')->page($Nowpage,$limit)->select();
    }

    /*
     * 添加学校
     * */
    public function addSchool($param){
        try{
            $param['create_time']=time();
            $result = $this->validate('SchoolValidate')->save($param);
            if(false === $result){
                return ['code' => 0, 'data' => '', 'msg' => $this->getError(),'url'=>''];
            }else{
                return ['code' => 1, 'data' => '', 'msg' => '添加成功','url'=>url('School/index')];
            }
        }catch (PDOException $e){
            return ['code' => 2, 'data' => '', 'msg' => $e->getMessage(),'url'=>''];
        }
    }

    //修改学校
    public function editSchool($param){
        try{
            $result=$this->validate('SchoolValidate')->save($param,['id'=>$param['id']]);
            if(false===$result){
                return ['code'=>0,'data'=>'','msg'=>$this->getError(),'url'=>''];
            }else{
                return ['code'=>1,'data'=>'','msg'=>'修改成功','url'=>url('School/index')];
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
 * 删除学校
 * */
    public function delSchool($id){
        try{
            if($this->where(['id'=>$id])->delete()){
                return ['code'=>1,'msg'=>'删除成功','url'=>url('School/index')];
            }else{
                return ['code'=>0,'msg'=>'删除失败'];
            }
        }catch (PDOException $e){
            return ['code'=>2,'msg'=>$e->getMessage()];
        }

    }

    /*
     * 获取全部学校
     * */
    public function getAllSchoolList(){
        return $this->order('sort desc')->select();
    }
}