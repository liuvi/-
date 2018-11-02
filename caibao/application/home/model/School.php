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
class School extends Model{
    /*
     * 学校列表
     * */
    public function getSchoolList(){
        return $this->order('sort desc,id desc')->select();
    }
}