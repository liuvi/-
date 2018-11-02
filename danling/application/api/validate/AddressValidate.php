<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/12 0012
 * Time: 16:21
 */
namespace app\api\validate;
use think\validate;
class AddressValidate extends Validate
{
    protected $rule=[
        ['username','require','联系人必须填写'],
        ['tel','require','联系电话必须填写'],
        ['tel','istel'],
        ['address','require','地址必须填写'],
        ['town_id','require','未选择所属镇']
    ];

    //验证是否是正确的手机号
    protected function istel($value)
    {
        $rule = '/^13[0-9]{9}|17[0-9]{9}|14[0-9]{9}|15[0-9]{9}|16[0-9]{9}|19[0-9]{9}|18[0-9]{9}$/';
        $result = preg_match($rule, $value);
        if ($result) {
            return true;
        } else {
            return '手机号不正确';
        }
    }

}