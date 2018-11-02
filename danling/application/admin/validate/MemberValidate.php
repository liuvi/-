<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/27 0027
 * Time: 14:20
 */
namespace app\admin\validate;
use think\validate;
use app\admin\model\MemberModel;
class MemberValidate extends Validate
{
    protected $rule = [
        ['username', 'require', '用户名必须填写'],
        ['password', 'require', '密码必须填写'],
        ['newpassword', 'require', '确认密码必须填写'],
        ['newpassword', 'confirm:password', '两次密码不一致'],
        ['tel', 'require', '手机号必须填写'],
        ['tel','checkTel'],
        ['rid','require','所属镇必须填写']
    ];


    protected $scene = [
        'pass'     => ['password','newpassword'],
        'create'   => ['username','password','newpassword','tel','rid'],
        'edit'     => ['username','tel','rid']
    ];

    /*
     *
     * 验证手机是否存在
     *
     * */
    public function checkTel($value,$rule,$data){
        $res=new MemberModel();
        $tel=$res->checkTel($value,$data['id']);
        if($tel){
            return '手机号已存在';
        }
        return true;
    }
}