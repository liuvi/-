<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:62:"E:\WWW\caibao\public/../application/admin\view\user\index.html";i:1535939034;s:55:"E:\WWW\caibao\application\admin\view\public\header.html";i:1535333950;s:55:"E:\WWW\caibao\application\admin\view\public\footer.html";i:1535333950;}*/ ?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="renderer" content="webkit">
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <title>后台管理中心</title>

    <meta name="keywords" content="">
    <meta name="description" content="">

    <!--[if lt IE 8]>
    <meta http-equiv="refresh" content="0;ie.html" />
    <![endif]-->
    <link href="../../static/admin/css/bootstrap.min.css?v=3.3.5" rel="stylesheet">
    <link href="../../static/admin/css/font-awesome.min.css?v=4.4.0" rel="stylesheet">
    <link href="../../static/admin/css/animate.min.css" rel="stylesheet">
    <link href="../../static/admin/css/style.min.css?v=4.0.0" rel="stylesheet">
    <link href="../../static/admin/css/plugins/iCheck/custom.css" rel="stylesheet">

</head>

<div class="row">
    <div class="col-sm-12">
        <div class="ibox float-e-margins">

            <div class="ibox-content">
                <div class="">
                    <a href="<?php echo url('User/add'); ?>" class="btn btn-primary add" title="添加用户">添加用户</a>
                    <a class="btn btn-danger" id="batchdel" href="<?php echo url('User/batchdel'); ?>">
                        <i class="fa fa-trash-o fa-lg"></i> 批量删除
                    </a>
                </div>
                <table class="table table-striped table-bordered table-hover " id="editable">
                    <thead>
                    <tr>
                        <td>
                            <input type="checkbox" class="" id="batchCheck">
                        </td>
                        <th>ID</th>
                        <th>用户名</th>
                        <th>头像</th>
                        <th>登录次数</th>
                        <th>上次登录时间</th>
                        <th>登录ip</th>
                        <th>真实姓名</th>
                        <th>状态</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <?php if(is_array($lists) || $lists instanceof \think\Collection || $lists instanceof \think\Paginator): $i = 0; $__LIST__ = $lists;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                    <tr class="gradeX">
                        <td>
                            <input type="checkbox" class="i-checks" value="<?php echo $vo['id']; ?>">
                        </td>
                        <td><?php echo $vo['id']; ?></td>
                        <td><?php echo $vo['username']; ?></td>
                        <td>

                            <img src="../../uploads/images/<?php echo $vo['portrait']; ?>" width="100"/>
                        </td>
                        <td><?php echo $vo['loginnum']; ?></td>
                        <td><?php echo date('Y-m-d H:i:s',$vo['last_login_time']); ?></td>
                        <td><?php echo $vo['last_login_ip']; ?></td>
                        <td><?php echo $vo['real_name']; ?></td>
                        <td>
                            <?php if($vo['status']): ?>
                            <a id="status<?php echo $vo['id']; ?>" onclick="setStatus(<?php echo $vo['id']; ?>,'<?php echo url('User/setStatus'); ?>');">
                                <span class="label label-primary">开启</span>
                            </a>
                            <?php else: ?>
                            <a id="status<?php echo $vo['id']; ?>" onclick="setStatus(<?php echo $vo['id']; ?>,'<?php echo url('User/setStatus'); ?>');">
                                <span class="label label-danger">禁用</span>
                            </a>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a title="编辑用户" class="btn btn-info edit" rel="<?php echo $vo['id']; ?>" href="<?php echo url('User/edit'); ?>">
                                <i class="fa fa-paste"></i> 编辑
                            </a>
                            <a class="btn btn-danger del" title="用户" href="<?php echo url('User/del',array('id'=>$vo['id'])); ?>">
                                <i class="fa fa-trash-o fa-lg"></i> 删除
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                </table>
            </div>
        </div>
    </div>
</div>
<script src="../../static/admin/js/jquery.min.js?v=2.1.4"></script>
<script src="../../static/admin/js/bootstrap.min.js?v=3.3.5"></script>
<script src="../../static/admin/js/plugins/metisMenu/jquery.metisMenu.js"></script>
<script src="../../static/admin/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>
<script src="../../static/admin/js/layer/layer.js"></script>
<script src="../../static/admin/js/hplus.min.js?v=4.0.0"></script>
<script type="text/javascript" src="../../static/admin/js/contabs.js"></script>
<script src="../../static/admin/js/plugins/pace/pace.min.js"></script>


<script src="../../static/admin/js/plugins/jeditable/jquery.jeditable.js"></script>

<script src="../../static/admin/js/content.min.js?v=1.0.0"></script>

<script src="../../static/admin/js/plugins/iCheck/icheck.min.js"></script>
<script src="../../static/admin/js/laypage/laypage.js"></script>
<script src="../../static/admin/js/laytpl/laytpl.js"></script>
<script src="../../static/admin/js/admin.js"></script>
<script src="../../static/admin/js/jquery.form.js"></script>

<script>
    $(document).ready(function(){$(".i-checks").iCheck({checkboxClass:"icheckbox_square-green",radioClass:"iradio_square-green",})});
</script>
