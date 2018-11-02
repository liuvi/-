<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/12 0012
 * Time: 16:18
 */
namespace app\api\model;
use think\exception\ErrorException;
use think\exception\PDOException;
use think\Model;
class Address extends Model{
    protected $resultSetType = 'collection';
    /*
     * 添加地址
     * */
    public function addAddr($param){
        try{
            if($param['is_default']==1){
                $where['is_default']=0;
                $this->where('uid',$param['uid'])->update($where);
            }
            $result=$this->allowField(true)->validate('AddressValidate')->save($param);
            if(false===$result){
                return ['code'=>0,'msg'=>$this->getError()];
            }else{
                return ['code'=>1,'msg'=>'添加成功'];
            }
        }catch (PDOException $e){
            return ['code'=>0,'msg'=>$e->getMessage()];
        }
    }

    /*
     * 修改地址
     * */
    public function editAddr($param){
        try{
            if($param['is_default']==1){
                $where['is_default']=0;
                $this->where('uid',$param['uid'])->update($where);
            }
            $result=$this->allowField(true)->validate('AddressValidate')->save($param,$param['id']);
            if(false===$result){
                return ['code'=>0,'msg'=>$this->getError()];
            }else{
                return ['code'=>1,'msg'=>'修改成功'];
            }
        }catch (PDOException $e){
            return ['code'=>0,'msg'=>$e->getMessage()];
        }
    }

    /*
     * 获取地址信息
     * */
    public function getAdd($id){
         $dataInfo=$this->where(['id'=>$id])->find();
         return empty($dataInfo) ? json([]) : $dataInfo->toArray();
    }

    /*
     * 根据用户id获取地址列表
     * */
    public function getAddList($uid){
        try{
            $where['uid']=$uid;
            $info=$this->where($where)->order('is_default desc')->select();
            $dataList=empty($info) ? json([]) : $info->toArray();
            return ['code'=>1,'msg'=>'获取成功','dataInfo'=>$dataList];
        }catch (PDOException $e){
            return ['code'=>0,'msg'=>$e->getMessage()];
        }
    }

    /*
     * 删除地址
     * */

    public function delAdd($id){
        try{
            if($this->where(['id'=>$id])->delete()){
                return ['code'=>1,'msg'=>'删除成功'];
            }else{
                return ['code'=>0,'msg'=>'删除失败'];
            }
        }catch (PDOException $e){
            return ['code'=>0,'msg'=>$e->getMessage()];
        }
    }

    /*
     * 设置默认地址
     * */

    public function setDefault($uid,$id){
        try{
            $where['is_default']=0;
            $this->where('uid',$uid)->update($where);

            $where['is_default']=1;
            $result=$this->where('id',$id)->update($where);
            if(false===$result){
                return ['code'=>0,'msg'=>$this->getError()];
            }else{
                return ['code'=>1,'msg'=>'设置成功'];
            }
        }catch (PDOException $e){
            return ['code'=>0,'msg'=>$e->getMessage()];
        }
    }

    /*
     * 获取默认地址
     * */

    public function getDefault($uid){
        try{
            $where['uid']=$uid;
            $where['is_default']=1;
            $field="id,area,address,tel,username";
            $info=$this->where($where)->field($field)->find();
            $dataList=empty($info) ? json([]) : $info->toArray();
            return ['code'=>1,'msg'=>'获取成功','dataInfo'=>$dataList];
        }catch (PDOException $e){
            return ['code'=>0,'msg'=>$e->getMessage()];
        }
    }

}