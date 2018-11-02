<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/19 0019
 * Time: 13:40
 */
namespace app\home\model;
use think\exception\PDOException;
use think\exception\ErrorException;
use app\api\model\Money;
use app\home\model\Member;
use app\api\model\Order as Ord;
use think\Model;
use think\Db;
use think\Cache;
class Order extends Model{
    protected $resultSetType = 'collection';
    /*
     * 订单列表
     * */

    public function getOrderList($where,$Nowpage,$limit){
        $dataList=$this->where($where)->order('create_time desc')->page($Nowpage,$limit)->select();
        if($dataList){
            foreach ($dataList as &$value){
                $value['address_info']=json_decode($value['address_info'],true);
            }
        }
        return empty($dataList) ? [] : $dataList->toArray();
    }

    /*
     * 订单详情
     * */

    public function getOne($id){
        $dataInfo=$this->where(['id'=>$id,'is_delete'=>0])->find();
        return empty($dataInfo) ? [] : $dataInfo->toArray();
    }

    /*
     * 删除订单
     * */
    public function del($id){
        try{
            $data['is_delete']=1;
            $data['id']=$id;
            $result=$this->save($data,$data['id']);
            if(false===$result){
                return ['code'=>0,'msg'=>$this->getError()];
            }else{
                return ['code'=>1,'msg'=>'删除成功'];
            }
        }catch (PDOException $e){
            return ['code'=>0,'msg'=>$e->getMessage()];
        }
    }


    /*
     * 修改订单信息
     * */
    public function editOrder($param){
        try{
            // 启动事务
            Db::startTrans();
            $result=$this->save($param,$param['id']);
            if(false===$result){
                // 回滚事务
                Db::rollback();
                return ['code'=>0,'msg'=>$this->getError()];
            }else{
                $uid=self::getUid($param['id']);
                $member=new Member();
                $usermoney=$member->getUserMoney($uid);
                $res=$member->setMoney($uid,$param['totalmoney']);
                if($res){
                    $data['uid']    =$uid;
                    $data['type']   =1;
                    $data['status'] =2;
                    $data['money']  =$param['totalmoney'];
                    $data['allmoney']=$param['totalmoney']+$usermoney;
                    $data['create_time']=time();
                    $money=new Money();
                    $msg=$money->subFor($data);
                    if($msg['code']==1){
                        // 提交事务
                        Db::commit();
                        $ord=new Ord();
                        $thisuser=session('userInfo');
                        Cache::rm('goods_attrs'.$thisuser['id']);
                        Cache::rm('totalmoney'.$thisuser['id']);//操作成功清除缓存内容
                        $ord->orderLog($thisuser['id'],'订单称重成功！','订单已称重',3,1);

                    }else{
                        // 回滚事务
                        Db::rollback();
                    }
                }else{
                    // 回滚事务
                    Db::rollback();
                }
                return ['code'=>1,'msg'=>'提交成功'];
            }
        }catch (PDOException $e){
            return ['code'=>0,'msg'=>$e->getMessage()];
        }
    }

    /*
     * 获取当前订单uid
     * */
    public function getUid($orderid){
        $uid=$this->where(['id'=>$orderid])->value('uid');
        return $uid;
    }

    /*
     * 根据条码获取内容
     * */
    public function getBarInfo($bar_code){
        $id=$this->where(['bar_code'=>$bar_code])->value('id');
        return $id;
    }


    /*
     * 修改订单商品
     * */
    public function saveGoods($param){
        try{
            $result=$this->save($param,$param['id']);
            if(false===$result){
                return ['code'=>0,'msg'=>$this->getError()];
            }else{
                return ['code'=>1,'msg'=>'添加成功'];
            }
        }catch (\PDOException $e){
            return ['code'=>0,'msg'=>$e->getMessage()];
        }
    }

    /*
     * 获取订单商品
     * */
    public function getOrderGoods($orderid){
        $dataInfo=$this->where(['id'=>$orderid])->value('goods_attrs');
        return json_decode($dataInfo,true);
    }

}
