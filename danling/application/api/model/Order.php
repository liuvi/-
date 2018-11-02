<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/13 0013
 * Time: 17:00
 */
namespace app\api\model;
use think\exception\PDOException;
use think\exception\ErrorException;
use app\api\model\Address;
use app\api\model\Goods;
use think\Db;
use think\Model;

class Order extends Model{
    protected  $resultSetType='collection';
    /*
     * 创建订单
     * */
    public function addOrder($param){
        try{
            // 启动事务
            Db::startTrans();

            $param['order_sn']=self::createOrderSN();
            $address = new Address();
            $addInfo = $address->getAdd($param['address_id']);
            $param['town_id'] = $addInfo['town_id'];
            $param['address_info'] = json_encode($addInfo);

            $goods=new Goods();
            $where['id']=array('in',$param['cat_id']);
            $GoodsList=$goods->getGoodsList($where);//根据分类id获取名称价格数量
            $totalmoney=0;
            foreach ($GoodsList as &$value){
                $value['goods_num']=1;
                $value['totalmoney']=$value['goods_num']*$value['price'];
                $totalmoney+=$value['price'];
            }
            $param['totalmoney']=$totalmoney;
            $param['goods_attrs']=json_encode($GoodsList);//获取到的数据存入数据库
            $param['create_time']=time();
            $result=$this->allowField(true)->validate('OrderValidate')->save($param);
            if(false===$result){
                // 回滚事务
                Db::rollback();
                return ['code'=>0,'msg'=>$this->getError()];
            }else{
                // 提交事务
                 Db::commit();
                 self::orderLog($this->id,'您提交订单成功！','提交订单成功',1,1);
                 return ['code'=>1,'msg'=>'提交成功','order_sn'=>$param['order_sn']];
            }

        }catch (PDOException $e){
            return ['code'=>0,'msg'=>$e->getMessage()];
        }
    }

    /*
     * 获取订单信息
     * */

    public function getOrder($order_sn){
        try{
            $dataInfo=$this->where(['order_sn'=>$order_sn])->field('id,order_sn,bar_code,goods_attrs,address_info,create_time,status')->find();
            $dataInfo['goods_attrs']    =json_decode($dataInfo['goods_attrs'],true);
            $dataInfo['address_info']   =json_decode($dataInfo['address_info'],true);
            return ['code'=>1,'msg'=>'获取成功','dataInfo'=>$dataInfo];
        }catch (PDOException $e){
            return ['code'=>0,'msg'=>$e->getMessage()];
        }
    }

    /*
     * 获取订单列表
     * */

    public function getOrderList($where,$Nowpage,$limit){
        try{
            $count=$this->where($where)->count();
            $allpage = intval(ceil($count / $limit));
            $field="id,order_sn,goods_attrs,address_info,totalmoney,status,create_time";

            $data=$this->where($where)->field($field)->order('create_time desc')->page($Nowpage,$limit)->select();
            $dataList=empty($data) ? [] : $data->toArray();
            foreach ($dataList as &$value){
                $value['goods_attrs']   =json_decode($value['goods_attrs']);
                $value['address_info']  =json_decode($value['address_info']);
                $value['create_time']   =strtotime($value['create_time']);
                $value['create_time']   = date('Y-m-d',$value['create_time']).'       '.date('H:i',$value['create_time']);
            }
            return ['code'=>1,'msg'=>'获取成功','dataInfo'=>['dataList'=>$dataList,'allpage'=>$allpage]];
        }catch (PDOException $e){
            return ['code'=>0,'msg'=>$e->getMessage()];
        }
    }

    /*
     * 修改条形码
     *
     * */

    public function saveBar($param){
        try{
            $result=$this->allowField(true)->save($param,$param['id']);
            if(false===$result){
                return ['code'=>0,'msg'=>$this->getError()];
            }else{
               // self::orderLog($param['id'],'绑定条形码！','绑定条形码成功',1,1);
                return ['code'=>1,'msg'=>'修改成功'];
            }
        }catch (PDOException $e){
            return ['code'=>0,'msg'=>$e->getMessage()];
        }
    }

    /*
 * 打包带回
 * */
    public function packGo($param){
        try{
            $result=$this->save($param,$param['id']);
            if(false===$result){
                return ['code'=>0,'msg'=>$this->getError()];
            }else{
                return ['code'=>1,'msg'=>'打包成功'];
            }
        }catch (PDOException $e){
            return ['code'=>0,'msg'=>$e->getMessage()];
        }
    }


    /*
     * 订单操作记录
     */
    public function orderLog($order_id,$action,$note='',$status,$pay_status){
        $data['order_id'] = $order_id;
        $data['action_user'] = 0;
        $data['action_note'] = $note;
        $data['order_status'] = $status;
        $data['pay_status'] = $pay_status;
        $data['log_time'] = time();
        $data['status_desc'] = $action;
        Db::name('OrderLog')->insert($data);//订单操作记录
    }

    /*
     * 取消订单
     * */

    public function cancelOrder($param){
        try{
            $result=$this->save($param,$param['id']);
            if(false===$result){
                return ['code'=>0,'msg'=>$this->getError()];
            }else{
                return ['code'=>1,'msg'=>'取消成功'];
            }
        }catch (PDOException $e){
            return ['code'=>0,'msg'=>$e->getMessage()];
        }
    }



    /*
     *
     * */
    public function getStatusOrder($where){
        $id=$this->where($where)->value('id');
        return $id;
    }


























    /**
     * 创建新订单编号
     *
     * @return string
     */
    public function createOrderSN( $field = 'order_sn' ) {
        // 订单编号
        $SN = createRandomCode() . time();
        $ordersn=$this->where('order_sn',$SN)->value($field);
        // 判断是否存在
        if ($ordersn) {
            $this->createOrderSN( $field );
        } else {
            return $SN;
        }
    }
}