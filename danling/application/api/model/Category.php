<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/12 0012
 * Time: 09:32
 */
namespace app\api\model;
use think\exception\ErrorException;
use think\exception\PDOException;
use think\Model;
use think\Db;
use app\api\model\Goods;
class Category extends Model{
    protected $resultSetType = 'collection';
    /*
     * 获取首页分类
     * */
    public function getIndexCateList(){
        try{
            $where['status']=1;
            $where['is_rec']=1;
            $field = 'id,title,picture';
            $dataList = $this->where($where)->field($field)->order('sort desc')->select();
            if(empty($dataList)){

                return ['code'=>1,'msg'=>'暂无数据','dataInfo'=>json([])];

            }else{
                $map['status']=1;
                $banner=Db::name('Banner')->where($map)->field($field)->order('sort desc')->select();
                foreach ($banner as &$value){
                    $value['picture']=config('IMGURL').$value['picture'];
                }
                foreach ($dataList as &$value1){
                    $value1['picture']=config('IMGURL').$value1['picture'];
                }

                return ['code'=>1,'msg'=>'获取成功','dataInfo'=>['cateList'=>$dataList->toArray(),'banner'=>$banner]];
            }

        }catch (PDOException $e){

            return ['code'=>0,'msg'=>$e->getMessage()];

        }
    }

    /*
     *获取分类
     * */
    public function getCateList(){
        try{
            $where['status']=1;
            $field = 'id,title,picture';
            $dataList = $this->where($where)->field($field)->order('sort desc')->select();
            if(empty($dataList)){

                return ['code'=>1,'msg'=>'暂无数据'];

            }else{
                foreach ($dataList as &$value){
                    $value['picture']=config('IMGURL').$value['picture'];
                }
                return ['code'=>1,'msg'=>'获取成功','dataInfo'=>['cateList'=>$dataList->toArray()]];
            }

        }catch (PDOException $e){

            return ['code'=>0,'msg'=>$e->getMessage()];

        }
    }

    /*
     * 获取分类价格
     * */

    public function getPrice(){
        try{
            $goods=new Goods();
            $Lists=self::getList();
            $all=array(array('id'=>0,'title'=>'所有'));
            $dataList=array_merge($all,$Lists);
            foreach ($dataList as $key=>$value){
                if($value['id']==0){
                    $dataList[$key]['child']=$goods->getGoodsList();
                }else{
                    $dataList[$key]['intro_img']=config('IMGURL').$value['intro_img'];
                    $where['cid']=$value['id'];
                    $where['status']=1;
                    $dataList[$key]['child']=$goods->getGoodsList($where);
                }
            }
            return ['code'=>1,'msg'=>'获取成功','dataInfo'=>$dataList];
        }catch (PDOException $e){
            return ['code'=>0,'msg'=>$e->getMessage()];
        }
    }


    /*
     * 获取列表
     * */

    public function getList(){
        $where['status']=1;
        $dataList=$this->where($where)->field('id,title,intro_img')->order('sort desc')->select();
        return empty($dataList) ? [] : $dataList->toArray();
    }


}