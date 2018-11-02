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
class Goods extends Model{
    /*
     * 分类列表
     * */
    public function getGoodsList($where,$Nowpage,$limit){
        return $this->where($where)->order('sort desc')->page($Nowpage,$limit)->select();
    }

    /*
     * 添加分类价格
     * */
    public function addGoods($param){
        try{
            $param['create_time']=time();
            $param['add_time']=date('Ymd');
            $result=$this->allowField(true)->validate('GoodsValidate')->save($param);
            if(false===$result){
                return ['code'=>0,'msg'=>$this->getError()];
            }else{
                return ['code'=>1,'msg'=>'添加成功','url'=>url('Category/showrec').'?cid='.$param['cid']];
            }
        }catch(PDOException $e){
            return ['code'=>0,'msg'=>$e->getMessage()];
        }
    }

    /*
     * 修改分类价格
     * */

    public function editGoods($param){
        try{
            $this->insertPrice($param['id']);//记录修改前价格
            $result=$this->allowField(true)->validate('GoodsValidate')->save($param,$param['id']);
            if(false===$result){
                return ['code'=>0,'msg'=>$this->getError()];
            }else{
                return ['code'=>1,'msg'=>'修改成功','url'=>url('Category/showrec').'?cid='.$param['cid']];
            }
        }catch(PDOException $e){
            return ['code'=>0,'msg'=>$e->getMessage()];
        }
    }

    /*
     *
     * 记录修改前价格
     * */
    public function insertPrice($id){
        $info=$this->getOne($id);
        $add['goods_id']=$id;
        $add['cid']     =$info['cid'];
        $add['price']   =$info['price'];
        $add['unit']    =$info['unit'];
        $add['create_time']=time();
        Db::name('History')->insert($add);
    }

    /*
     * 获取一条数据
     * */
    public function getOne($id){
        return $this->where(['id'=>$id])->find();
    }

    /*
     * 删除分类价格
     * */
    public function delGoods($id){
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

}