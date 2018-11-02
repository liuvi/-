<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/27 0027
 * Time: 14:20
 */
namespace app\admin\validate;
use think\validate;
class TopicValidate extends Validate{
    protected $rule = [
        ['name', 'require', '名称必须填写'],
        ['value','require','请填写正确答案'],
        ['items','checkItems'],
    ];

    protected function checkItems($type,$rule,$data){

        $items= json_decode($type,true);
        foreach ($items as $value){
            if($value['title']==''){
                return '答案不能为空';
            }
        }
        $arr=i_array_column($items,'title');
        $nuiques=array_unique($arr);

        if (count($arr) != count(array_unique($nuiques))) {
            return '答案不能相同';
        }
        return true;
    }
}