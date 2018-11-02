<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/18 0018
 * Time: 08:37
 */
namespace app\admin\model;
use think\exception\PDOException;
use think\exception\ErrorException;
use think\Model;
class Money extends Model{
    /*
     * 提现申请列表
     * */
    public function getMoneyList($where,$Nowpage,$limit){
        $field="a.*,b.username";
        return $this->alias('a')->join('dl_member b','b.id=a.uid','LEFT')->field($field)->where($where)->order('create_time desc')->page($Nowpage,$limit)->select();
    }

    /*
     * 获取一条数据
     * */
    public function getOne($id){
        return $this->where(['id'=>$id])->find();
    }

    /*
     * 修改数据
     * */
    public function editMoney($param){
        try{
            $result=$this->save($param,$param['id']);
            if(false===$result){
                return ['code'=>0,'msg'=>$this->getError()];
            }else{
                return ['code'=>1,'msg'=>'保存成功','url'=>url('Member/money')];
            }
        }catch (PDOException $e){
            return ['code'=>0,'msg'=>$e->getMessage()];
        }
    }
}