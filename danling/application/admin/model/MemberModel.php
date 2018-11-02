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
class MemberModel extends Model{
    protected $resultSetType = 'collection';
    protected $name='member';

    /*
     * 收货人列表
     * */

    public function getMemberList($where,$Nowpage,$limit){
        return $this->where($where)->order('create_time desc')->page($Nowpage,$limit)->select();
    }
    /*
     * 添加收货人
     * */
    public function addMember($param){
        try{
            $param['create_time']=time();
            $result=$this->allowField(true)->save($param);
            if(false===$result){
                return ['code'=>0,'msg'=>$this->getError()];
            }else{
                return ['code'=>1,'msg'=>'添加成功','url'=>url('Member/delivery')];
            }
        }catch(PDOException $e){
            return ['code'=>0,'msg'=>$e->getMessage()];
        }
    }

    /*
     * 修改收货人
     * */

    public function editMember($param){
        try{
            $result=$this->allowField(true)->validate('MemberValidate.edit')->save($param,$param['id']);
            if(false===$result){
                return ['code'=>0,'msg'=>$this->getError()];
            }else{
                return ['code'=>1,'msg'=>'修改成功','url'=>url('Member/delivery')];
            }
        }catch(PDOException $e){
            return ['code'=>0,'msg'=>$e->getMessage()];
        }
    }

    /*
     * 修改密码
     * */

    public function editPass($param){
        try{
            $result=$this->allowField(true)->save($param,$param['id']);
            if(false===$result){
                return ['code'=>0,'msg'=>$this->getError()];
            }else{
                return ['code'=>1,'msg'=>'修改成功','url'=>url('Member/delivery')];
            }
        }catch(PDOException $e){
            return ['code'=>0,'msg'=>$e->getMessage()];
        }
    }

    /*
     *
     * */
    public function savePass($data){
        return $this->where('id',$data['id'])->update(['password'=>md5($data['password'])]);
    }
    /*
     * 获取一条数据
     * */
    public function getOne($id){
        return $this->where(['id'=>$id])->find();
    }

    /*
     * 删除收货人
     * */
    public function delMember($id){
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

    /*
     * 验证手机是否存在
     * */
    public function checkTel($value,$id){
        $where['tel']=$value;
        $where['id']=array('NOT IN',$id);
        return $this->where($where)->value('id');
    }

}