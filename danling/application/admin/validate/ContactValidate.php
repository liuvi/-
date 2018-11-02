<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/27 0027
 * Time: 14:20
 */
namespace app\admin\validate;
use think\validate;
class ContactValidate extends Validate
{
    protected $rule = [
        ['name', 'require', '请填写名称'],
        ['tel', 'require', '请填写电话'],
    ];
}