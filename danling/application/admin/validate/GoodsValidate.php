<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/27 0027
 * Time: 14:20
 */
namespace app\admin\validate;
use think\validate;
class GoodsValidate extends Validate
{
    protected $rule = [
        ['name', 'require', '名称必须填写'],
    ];
}