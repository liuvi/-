<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/14 0014
 * Time: 09:00
 */
namespace app\api\controller;
use app\api\model\BagOrder;
use wxpay\WeixinPay;
use think\Db;
use app\api\model\Member;
class Payment extends Common
{
    /*
      *微信支付 接口
      */
    public function WechatPay()
    {
        if(request()->isPost()){
            $config=Db::name("Config")->column('name,value');
            $uid        = (int)input("post.uid",0);
            $bagnum     = (int)input('post.bagnum',0);

            $order_sn   = 0;
            if($bagnum==0){
                return json(['code'=>0,'msg'=>'参数错误']);
            }
            //获取用户openid
            $openid=Db::name('Member')->where(['id'=>$uid])->value('openid');
            if(!$openid){
                return json(['code'=>0,'msg'=>'用户信息错误']);
            }

            $addbag['uid'] =$uid;
            $addbag['price']=$config['web_bag'];
            $addbag['bag_num']=$bagnum;
            $addbag['totalmoney']=$bagnum*$config['web_bag'];
            $bag=new BagOrder();
            $res=$bag->subOrder($addbag);
            if($res['code']==1){
                $order_sn=$res['data']['order_sn'];
            }else{
                return json($res);
            }

            $params = array(
                'appid' => $config['appid'],
                'secret' => $config['app_secret'],
                'mch_id' => $config['app_mch_id'],
                'key' => $config['app_key'],
                'grant_type' => 'authorization_code',
            );
            $appid = $params['appid'];
            $mch_id = $params['mch_id'];
            $key = $params['key'];


            if ($order_sn) {
                $out_trade_no = $order_sn; //订单号
                $total_fee  = ($bagnum*$config['web_bag'])*100;   //金额
                $body       = '购买垃圾袋';//$info['appointment_date'];
                $notify_url = config('SETURL').'/api/Payment/notify_url'; //异步通知
                $order_ip   =  request()->ip(); //IP地址
                $weixinpay  = new WeixinPay($appid, $openid, $mch_id, $key, $out_trade_no, $body, $total_fee, $notify_url, $order_ip);
                $return     = $weixinpay->pay();
                $dataaa['code'] = 1;
                $dataaa['msg'] = '调取支付成功';
                $dataaa['dataInfo'] = $return;
                return json($dataaa);
            } else {
                $dataaa['code'] = 0;
                $dataaa['msg'] = '订单错误,请重新操作';
                return json($dataaa);
            }
        }else{
            return $this->dlError();
        }
    }

    /*
     *
     * 余额支付
     *
     * */
    public function balancePay(){
        if(request()->isPost()){
            $config=Db::name("Config")->column('name,value');

            $uid        = (int)input("post.uid");
            $bagnum     = (int)input('post.bagnum',0);
            $order_sn   = 0;
            if(!$uid){
                return json(['code'=>0,'msg'=>'用户信息错误']);
            }

            if(!$bagnum){
                return json(['code'=>0,'msg'=>'请选择数量']);
            }
            $totalmoney=$bagnum*$config['web_bag'];
            $member=new Member();
            $user=$member->getDatas($uid);
            if($user['money']<$totalmoney){
                return json(['code'=>0,'msg'=>'余额不足']);
            }
            $addbag['uid'] =$uid;
            $addbag['price']=$config['web_bag'];
            $addbag['bag_num']=$bagnum;
            $addbag['totalmoney']=$totalmoney;
            $bag=new BagOrder();
            $res=$bag->subOrder($addbag);
            if($res['code']==1){
                $order_sn=$res['data']['order_sn'];
            }else{
                return json($res);
            }
            $result=Db::name('Member')->where(['id'=>$uid])->setDec('money',$totalmoney);
            if($result){
                $this->saveOrderStatus($order_sn,2);
                $data['uid']    =$uid;
                $data['type']   =2;
                $data['money']  =$totalmoney;
                $data['allmoney']=$user['money']-$totalmoney;
                $data['status']  =2;
                $data['create_time']=time();
                $money=new \app\api\model\Money();
                $msg=$money->subFor($data);
                return json(['code'=>1,'msg'=>'余额支付成功']);
            }else{
                return json(['code'=>0,'msg'=>'余额支付失败']);
            }

        }else{
            return $this->dlError();
        }
    }

    /*微信支付的 异步通知*/

    public function notify_url(){
        // 获取xml
        $xml=file_get_contents('php://input', 'r');
        // 转成php数组
        $attr=$this->toArray($xml);

        $total_fee = $attr['total_fee'];
        $open_id = $attr['openid'];
        $out_trade_no = $attr['out_trade_no'];
        $time = $attr['time_end'];
        $transaction_id = $attr['transaction_id'];
        //判断支付状态
        if ($attr['return_code']=='SUCCESS') {
            // 返回状态给微信服务器
            $this->saveOrderStatus($out_trade_no,1);
            echo "<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>";
        }

    }


    /*
     * 修改订单状态
     * */

    public function saveOrderStatus($order_sn,$type=0){
        $data['is_pay']=1;
        $data['pay_time']=time();
        $data['type']=$type;
        Db::name("BagOrder")->where(['order_sn'=>$order_sn])->update($data);
    }

    /**
     * 将xml转为array
     * @param  string $xml xml字符串
     * @return array       转换得到的数组
     */
    public function toArray($xml){
        //禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        $result= json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return $result;
    }
}