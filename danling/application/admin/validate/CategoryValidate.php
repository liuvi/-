<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/27 0027
 * Time: 14:20
 */
namespace app\admin\validate;
use think\validate;
class CategoryValidate extends Validate
{
    protected $rule = [
        ['title', 'require', '名称必须填写'],
    ];
}