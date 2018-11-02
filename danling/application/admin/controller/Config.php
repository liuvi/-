<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/5 0005
 * Time: 17:42
 */
namespace app\admin\controller;
use app\admin\model\ConfigModel;
use think\Db;
class Config extends Common{
    public function _initialize()
    {
        parent::_initialize();
    }
    //配置管理
    public function index(){
        $configModel = new ConfigModel();
        $list = $configModel->getAllConfig();
        $config = [];
        foreach ($list as $k => $v) {
            $config[trim($v['name'])] = $v['value'];
        }
        $this->assign('config',$config);
        return $this->fetch('index');
    }
    //保存配置信息
    public function save($config){
        if (request()->isAjax()) {
            $configModel = new ConfigModel();
            if ($config && is_array($config)) {
                foreach ($config as $name => $value) {
                    $map = array('name' => $name);
                    $configModel->SaveConfig($map, $value);
                }
            }
            return json(['code' => 1, 'msg' => '保存成功']);
            // $this->success('保存成功！');
        }
    }
}