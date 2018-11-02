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
class Banner extends Model{
    protected $resultSetType = 'collection';

    /*
     * 添加广告
     * */
    public function addBanner($param){
        try{
            $param['create_time']=time();
            $result=$this->allowField(true)->validate('BannerValidate')->save($param);
            if(false===$result){
                return ['code'=>0,'msg'=>$this->getError()];
            }else{
                return ['code'=>1,'msg'=>'添加成功','url'=>url('Banner/index')];
            }
        }catch(PDOException $e){
            return ['code'=>0,'msg'=>$e->getMessage()];
        }
    }

    /*
     * 修改广告
     * */

    public function editBanner($param){
        try{
            $result=$this->allowField(true)->validate('BannerValidate')->save($param,$param['id']);
            if(false===$result){
                return ['code'=>0,'msg'=>$this->getError()];
            }else{
                return ['code'=>1,'msg'=>'修改成功','url'=>url('Banner/index')];
            }
        }catch(PDOException $e){
            return ['code'=>0,'msg'=>$e->getMessage()];
        }
    }

    /*
     * 获取一条数据
     * */
    public function getOne($id){
        return $this->where(['id'=>$id])->find();
    }

    /*
     * 删除广告
     * */
    public function delBanner($id){
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