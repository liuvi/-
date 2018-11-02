<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/8 0008
 * Time: 17:49
 */

namespace app\admin\model;
use think\Model;
use think\exception\PDOException;
use think\exception\ErrorException;
use think\Db;

class RoleModel extends Model
{
    protected $name="auth_group";

    protected $autoWriteTimestamp=true;
    //添加角色
    public function insertRole($param){
        try{
            $result=$this->save($param);
            if(false===$result){
                return ['code'=>0,'msg'=>'添加失败'];
            }else{
                return ['code'=>1,'msg'=>'添加成功'];
            }
        }catch (PDOException $e){
            return ['code'=>0,'msg'=>$e->getMessage()];
        }
    }
    //修改角色
    public function UpdateData($param){
        try{
            $result=$this->save($param,$param['id']);
            if(false===$result){
                return ['code'=>0,'msg'=>'修改失败'];
            }else{
                return ['code'=>1,'msg'=>'修改成功'];
            }
        }catch (PDOException $e){
            return ['code'=>0,'msg'=>$e->getMessage()];
        }
    }
    //获取一条数据
    public function getOne($id){
        return $this->where(['id'=>$id])->find();
    }
    //获取一组数据

    public function getRole(){
        return $this->where(['id'=>['<>',1]])->order('id desc')->select();
    }

    //删除角色
    public function delRole($id){
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

    //获取节点数据
    public function getNodeInfo($id){
        $str='';
        //所有节点
        $node=new MenuModel();
        $result=$node->field('id,title,pid')->select();
        //当前已经选中
        $rules=$this->where('id',$id)->field('rules')->find();
        if(!empty($rules)){
            $rules=explode(',',$rules['rules']);
        }
        foreach($result as $key=>$vo){
            $str .= '{ "id": "' . $vo['id'] . '", "pId":"' . $vo['pid'] . '", "name":"' . $vo['title'].'"';

            if(!empty($rules) && in_array($vo['id'], $rules)){
                $str .= ' ,"checked":1';
            }

            $str .= '},';
        }
        return  "[" . substr($str, 0, -1) . "]";
    }

    //分配权限
    public function editRules($param){
        try{
            $resule=$this->save($param,$param['id']);
            if(false===$resule){
                return ['code'=>0,'msg'=>'分配失败'];
            }else{
                return ['code'=>1,'msg'=>'分配成功'];
            }
        }catch (PDOException $e){
            return ['code'=>0,'msg'=>$e->getMessage()];
        }
    }

    /**
     * [getRoleInfo 获取角色信息]
     */
    public function getRoleInfo($id){

        $result = Db::name('auth_group')->where('id', $id)->find();
        if(empty($result['rules'])){
            $where = '';
        }else{
            $where = 'id in('.$result['rules'].')';
        }
        $res = Db::name('auth_rule')->field('name')->where($where)->select();

        foreach($res as $key=>$vo){
            if('#' != $vo['name']){
                $result['name'][] = $vo['name'];
            }
        }
        return $result;
    }
}