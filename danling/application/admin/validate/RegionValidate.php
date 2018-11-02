<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/27 0027
 * Time: 14:20
 */
namespace app\admin\validate;
use think\validate;
class RegionValidate extends Validate
{
    protected $rule = [
        ['area', 'require', '县必须填写'],
        ['town', 'require', '镇必须填写'],
    ];
}