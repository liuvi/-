<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/27 0027
 * Time: 14:20
 */
namespace app\home\validate;
use think\validate;
class MemberValidate extends Validate
{
    protected $rule = [
        ['username', 'require', '用户名必须填写'],
        ['password', 'require', '密码必须填写'],
    ];



}