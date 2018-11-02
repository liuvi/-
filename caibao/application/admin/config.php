<?php

return [
    //模板参数替换
    'view_replace_str' => array(
        '__CSS__' => '../../static/admin/css',
        '__JS__'  => '../../static/admin/js',
        '__IMG__' => '../../static/admin/img',
        '__IMAGES__'=>'../../uploads/images',
        '__PLUGIN__'=>'../../static/admin/plugins',
    ),

    //默认错误跳转对应的模板文件
    'dispatch_error_tmpl' => APP_PATH.'admin/view/public/error.tpl',
    //默认成功跳转对应的模板文件
    'dispatch_success_tmpl' => APP_PATH.'admin/view/public/success.tpl',
    //每页显示数量
    'pagelimit'=>15,
];
