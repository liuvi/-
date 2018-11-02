<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:62:"E:\WWW\caibao\public/../application/admin\view\menu\index.html";i:1535620736;s:55:"E:\WWW\caibao\application\admin\view\public\header.html";i:1535333950;s:55:"E:\WWW\caibao\application\admin\view\public\footer.html";i:1535333950;}*/ ?>
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
                    <a href="<?php echo url('Menu/add_menu'); ?>" class="btn btn-primary add" title="添加菜单">添加菜单</a>
                </div>
                <form method="post" action="<?php echo url('Menu/menuOrder'); ?>" id="btnOrder">
                <table class="table table-striped table-bordered table-hover " id="editable">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>权限名称</th>
                        <th>节点</th>
                        <th>菜单状态</th>
                        <th>添加时间</th>
                        <th>排序</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <?php if(is_array($lists) || $lists instanceof \think\Collection || $lists instanceof \think\Paginator): $i = 0; $__LIST__ = $lists;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                        <tr class="gradeA">
                            <td><?php echo $vo['id']; ?></td>
                            <td style='text-align:left;padding-left:<?php if($vo['leftpin'] != 0): ?><?php echo $vo['leftpin']; ?>px<?php endif; ?>'><?php echo $vo['lefthtml']; ?><?php echo $vo['title']; ?></td>
                            <td><?php echo $vo['name']; ?></td>
                            <td class="client-status">
                                <?php if($vo['status']): ?>
                                  <a id="status<?php echo $vo['id']; ?>" onclick="setStatus(<?php echo $vo['id']; ?>,'<?php echo url('Menu/setStatus'); ?>');">
                                      <span class="label label-primary">开启</span>
                                  </a>
                                    <?php else: ?>
                                   <a id="status<?php echo $vo['id']; ?>" onclick="setStatus(<?php echo $vo['id']; ?>,'<?php echo url('Menu/setStatus'); ?>');">
                                       <span class="label label-danger">禁用</span>
                                   </a>
                                <?php endif; ?>
                            </td>
                            <td><?php echo $vo['create_time']; ?></td>
                            <td>
                                <input type="text" name="<?php echo $vo['id']; ?>" value="<?php echo $vo['sort']; ?>" style="width: 50%;text-align: center;" class="form-control">
                            </td>
                            <td>
                                <a title="编辑菜单" class="btn btn-info edit" rel="<?php echo $vo['id']; ?>" href="<?php echo url('Menu/edit_menu'); ?>">
                                    <i class="fa fa-paste"></i> 编辑
                                </a>
                                <a class="btn btn-danger del" title="菜单" href="<?php echo url('Menu/del_menu',array('id'=>$vo['id'])); ?>">
                                    <i class="fa fa-trash-o fa-lg"></i> 删除
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                    <tr>
                        <td colspan="8" align="right">
                            <button type="submit" class="btn btn-w-m btn-primary">更新排序</button>
                        </td>
                    </tr>
                </table>
                </form>
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

<script>
    $(function () {
        //表单提交方法
        $('#btnOrder').ajaxForm({
            success: complete,
            dataType: 'json'
        });
        function complete(data){
            if(data.code==1){
                layer.msg(data.msg, {icon: 6,time:1000}, function(index){
                    layer.close(index);
                    //parent.location.reload(); // 父页面刷新
                    window.location.href=data.url;
                });
            }else{
                layer.msg(data.msg, {icon: 5,time:1000});
                return false;
            }
        }
    });
</script>
</body>

</html>