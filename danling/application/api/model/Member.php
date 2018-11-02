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
use think\Db;
use app\api\model\Integral;
use app\api\model\Money;
class Member extends Model{
    protected $resultSetType = 'collection';
    /*
     * 注册用户（微信自动登录）
     * */
    public function addReg($param){
        try{
            $userId=$this->where(['openid'=>$param['openid']])->value('id');
            if(!$userId){
                $result=$this->allowField(true)->save($param);
                if(false===$result){
                    return ['code'=>0,'msg'=>$this->getError()];
                }else{
                    return ['code'=>1,'msg'=>'授权成功','dataInfo'=>['uid'=>$this->id]];
                }
            }else{
                return ['code'=>1,'msg'=>'授权成功','dataInfo'=>['uid'=>$userId]];
            }
        }catch (PDOException $e){
            return ['cdoe'=>0,'msg'=>$e->getMessage()];
        }
    }

    /*
     * 更新用户信息
     * */

    public function saveUser($param){
        try{
           // if($info !== $info){
                $result=$this->allowField(true)->save($param,$param['id']);
                if(false===$result){
                    return ['code'=>0,'msg'=>$this->getError()];
                }else{
                    return ['code'=>1,'msg'=>'更新成功'];
                }
//            }else{
//                return ['code'=>1,'msg'=>'更新成功'];
//            }
        }catch (PDOException $e){
            return ['code'=>0,'msg'=>$e->getMessage()];
        }
    }

    /*
     * 获取用户信息
     * */

    public function getUserInfo($id){
        try{
            $dataInfo=$this->where(['id'=>$id])->field('username,headimg,money,integral')->find();
            $dataList=empty($dataInfo) ? json([]) : $dataInfo->toArray();
            if(is_array($dataList)){
                $dataList['bagnum']=Db::name('BagOrder')->where(['uid'=>$id,'is_pay'=>1])->sum('bag_num');
            }
            $config=Db::name("Config")->column('name,value');
            $dataList['bagprice']= number_format($config['web_bag'],2);
            return ['code'=>1,'msg'=>'获取成功','dataInfo'=>$dataList];
        }catch (PDOException $e){
            return ['code'=>0,'msg'=>$e->getMessage()];
        }
    }

    /*
     * 分享后获取积分
     * */

    public function shareintegral($uid){
        try{
            $info=self::getDatas($uid);
            if($info['current_day'] == date('Ymd',time())){
                return ['code'=>1,'msg'=>'分享成功']; //今日已经分享直接返回不记录
            }
            Db::startTrans();
            $param['integral']=$info['integral']+5;//积分加5
            $param['current_day']=date('Ymd',time());
            $param['id']=$uid;
            $result=$this->save($param,$uid);
            if(false===$result){
                Db::rollback();
                return ['code'=>0,'msg'=>$this->getError()];
            }else{
                Db::commit();
                $data['uid']        =$uid;
                $data['integral']   =5;
                $data['allintegral']=$param['integral'];
                $data['create_time']=time();
                $integral=new Integral();
                $msg=$integral->ShareRec($data);
                return $msg;
            }
        }catch (PDOException $e){
            return ['code'=>0,'msg'=>$e->getMessage()];
        }
    }

    public function getDatas($uid){
        $dataInfo=$this->where(['id'=>$uid])->field('current_day,integral,money')->find();
        return empty($dataInfo) ? [] : $dataInfo->toArray();
    }

    /*
     * 积分明细
     * */

    public function getIntergral($where,$Nowpage,$limit){
        try{
            $count=Db::name('Integral')->where($where)->count();
            $allpage = intval(ceil($count / $limit));
            $integral=new Integral();
            $dataList=$integral->getIntegral($where,$Nowpage,$limit);
            return ['code'=>1,'msg'=>'获取成功','dataInfo'=>['dataList'=>$dataList,'allpage'=>$allpage]];
        }catch (PDOException $e){
            return ['code'=>0,'msg'=>$e->getMessage()];
        }
    }

    /*
     * 提交提现申请
     * */
    public function subForward($param){
        try{
            $userInfo=self::getDatas($param['uid']);
            if($userInfo['money']<$param['money']){
                return ['code'=>0,'msg'=>'当前余额不足'];
            }
            $allmoeny=$userInfo['money']-$param['money'];
            $result=$this->where(['id'=>$param['uid']])->setDec('money',$param['money']);
            if($result){
                $data['uid']    =$param['uid'];
                $data['type']   =2;
                $data['money']  =$param['money'];
                $data['weixin'] =$param['weixin'];
                $data['allmoney']=$allmoeny;
                $data['create_time']=time();
                $money=new Money();
                $msg=$money->subFor($data);
                return $msg;
            }else{
                return ['code'=>0,'msg'=>$this->getError()];
            }
        }catch (PDOException $e){
            return ['code'=>0,'msg'=>$e->getMessage()];
        }
    }

}