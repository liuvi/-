<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/14 0014
 * Time: 16:22
 */
namespace app\api\model;
use think\exception\PDOException;
use think\exception\ErrorException;
use think\Model;
use think\Db;
class Integral extends Model{
    protected $resultSetType = 'collection';
    /*
     * 获取积分明细
     * */
    public function getIntegral($where,$Nowpage,$limit){
        $dataList=$this->where($where)->order('create_time desc')->page($Nowpage,$limit)->select();
        return empty($dataList) ? json([]) : $dataList;
    }

    /*
     * 分享记录
     * */
    public function ShareRec($param){
        try{
            DB::startTrans();
            $result=$this->save($param);
            if(false===$result){
                Db::rollback();
                return ['code'=>0,'msg'=>$this->getError()];
            }else{
                Db::commit();
                return ['code'=>1,'msg'=>'分享成功'];
            }
        }catch (PDOException $e){
            return ['code'=>0,'msg'=>$e->getMessage()];
        }
    }
}