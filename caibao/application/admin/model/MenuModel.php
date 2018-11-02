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
class MenuModel extends Model{
    protected $name="auth_rule";
    //开启时间戳自动写入
    protected $autoWriteTimestamp=true;

    //添加从菜单
    public function insertMenu($param){
        try{
            $result = $this->save($param);

            if(false === $result){
                return ['code' => 0, 'data' => '', 'msg' => $this->getError()];
            }else{
                return ['code' => 1, 'data' => '', 'msg' => '添加菜单成功','url'=>url('Menu/index')];
            }
        }catch (PDOException $e){
            return ['code' => 2, 'data' => '', 'msg' => $e->getMessage()];
        }
    }
    //修改菜单
    public function editMenu($param){
        try{
            $result=$this->save($param,['id'=>$param['id']]);
            if(false===$result){
                return ['code'=>0,'data'=>'','msg'=>$this->getError()];
            }else{
                return ['code'=>1,'data'=>'','msg'=>'修改菜单成功','url'=>url('Menu/index')];
            }
        }catch (PDOException $e){
            return ['code'=>2,'data'=>'','msg'=>$e->getMessage()];
        }
    }

    //获取菜单
    public function getMenu($where=array()){
        return $this->order('id asc')->where($where)->select();
    }
    //获取菜单一条数据
    public function getOneMenu($id){
        return $this->where(['id'=>$id])->find();
    }
    //删除菜单
    public function delMenu($id){
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


    /**
     * [getMenu 根据节点数据获取对应的菜单]
     */
    public function getHomeMenu($nodeStr = '')
    {
        //超级管理员没有节点数组
        $where = empty($nodeStr) ? 'status = 1' : 'status = 1 and id in('.$nodeStr.')';
        $result = Db::name('auth_rule')->where($where)->order('sort desc')->select();
        $menu = prepareMenu($result);
        return $menu;
    }

}