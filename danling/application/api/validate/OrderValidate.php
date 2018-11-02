<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/13 0013
 * Time: 17:24
 */
namespace app\api\validate;
use think\validate;
class OrderValidate extends Validate{

    protected $rule=[
          ['address_id','require','请选择收货地址'],
          ['uid','require','用户信息错误'],
          ['uid','gt:0','用户信息错误'],
          ['cat_id','require','请选择分类']
    ];
}