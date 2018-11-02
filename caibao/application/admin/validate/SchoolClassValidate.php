<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/27 0027
 * Time: 14:20
 */
namespace app\admin\validate;
use think\validate;
class SchoolClassValidate extends Validate{
    protected $rule = [
        ['title', 'require', '名称必须填写'],
        ['sid', 'require', '请选择班级'],
       // ['title','unique:schoolclass','学校名称已存在'],
    ];
}