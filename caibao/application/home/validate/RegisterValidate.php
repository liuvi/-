<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/27 0027
 * Time: 14:20
 */
namespace app\home\validate;
use think\validate;
class RegisterValidate extends Validate{
    protected $rule = [
        ['sid', 'require', '请选择学校'],
        ['cid','require','请选择班级'],
        ['username','require','请填写姓名'],
        ['code','require','请填写验证码'],
        ['tel','require','请填写手机号'],
    ];
}