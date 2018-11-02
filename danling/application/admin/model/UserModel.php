<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/9 0009
 * Time: 13:28
 */
namespace app\admin\model;
use think\Model;
use think\exception\PDOException;

class UserModel extends Model{
    protected $name='admin';
  //  protected $autoWriteTimestamp=true;
    //添加用户
    public function insertUser($param){
        try{
            $result=$this->save($param);
            if(false===$result){
                return ['code'=>0,'msg'=>$this->getError()];
            }else{
                return ['code'=>1,'msg'=>'添加成功'];
            }
        }catch(PDOException $e){
            return ['code'=>0,'msg'=>$e->getMessage()];
        }
    }

    //用户列表
    public function getUserList($map, $Nowpage, $limits){
        return $this->alias('a')->field('a.*,title')->join(config('prefixion').'auth_group b', 'a.groupid = b.id')
            ->where($map)->page($Nowpage, $limits)->order('id desc')->select();
    }

    public function getOne($id){
        return $this->where(['id'=>$id])->find();
    }
    //修改用户
    public function updateUser($param){
        try{
            $result=$this->save($param,$param['id']);
            if(false===$result){
                return ['code'=>0,'msg'=>$this->getError()];
            }else{
                return ['code'=>1,'msg'=>'修改成功'];
            }
        }catch(PDOException $e){
            return ['code'=>0,'msg'=>$e->getMessage()];
        }
    }
//单个删除
    public function delUser($id){
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
    //批量删除
    public function batchDelete($ids){
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
