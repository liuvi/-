<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/29 0029
 * Time: 09:56
 */
namespace app\home\model;
use think\exception\ErrorException;
use think\exception\PDOException;
use think\Model;
use think\Db;
class RegisterModel extends Model
{
    protected $name='member';
    /*
     * 注册用户
     * */
    public function Reg($param)
    {
        try {
            $param['create_time'] = time();
            $result = $this->save($param);
            if (false === $result) {
                return ['code' => 0, 'data' => '', 'msg' => $this->getError(), 'url' => ''];
            } else {
                session('MemberId',$this->id);
                return ['code' => 1, 'data' => '', 'msg' => '提交成功', 'url' => url('Activity/index')];
            }
        } catch (PDOException $e) {
            return ['code' => 0, 'data' => '', 'msg' => $e->getMessage(), 'url' => ''];
        }
    }

    /*
     * 修改
     * */

    public function editReg($param)
    {
        try {
            $result = $this->save($param, ['id' => $param['id']]);
            if (false === $result) {
                return ['code' => 0, 'data' => '', 'msg' => $this->getError(), 'url' => ''];
            } else {
                session('MemberId',$param['id']);
                return ['code' => 1, 'data' => '', 'msg' => '提交成功', 'url' => url('Activity/index')];
            }
        } catch (PDOException $e) {
            return ['code' => 0, 'data' => '', 'msg' => $e->getMessage(), 'url' => ''];
        }
    }

}