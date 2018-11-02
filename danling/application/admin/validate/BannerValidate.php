<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/27 0027
 * Time: 14:20
 */
namespace app\admin\validate;
use think\validate;
class BannerValidate extends Validate
{
    protected $rule = [
        ['picture', 'require', '请上传图片'],
    ];
}