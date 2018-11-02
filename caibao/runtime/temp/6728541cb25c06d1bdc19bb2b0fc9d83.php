<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:62:"E:\WWW\caibao\public/../application/admin\view\role\index.html";i:1536137759;s:55:"E:\WWW\caibao\application\admin\view\public\header.html";i:1535333950;s:55:"E:\WWW\caibao\application\admin\view\public\footer.html";i:1535333950;}*/ ?>
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
                    <a href="<?php echo url('Role/add'); ?>" class="btn btn-primary add" title="添加角色">添加角色</a>
                </div>
                <table class="table table-striped table-bordered table-hover " id="editable">
                    <tr>
                        <th>ID</th>
                        <th>角色名称</th>
                        <th>状态</th>
                        <th>添加时间</th>
                        <th>操作</th>
                    </tr>
                    <?php if(is_array($lists) || $lists instanceof \think\Collection || $lists instanceof \think\Paginator): $i = 0; $__LIST__ = $lists;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                    <tr class="gradeX">
                        <td><?php echo $vo['id']; ?></td>
                        <td>
                            <?php echo $vo['title']; ?>
                        </td>
                        <td class="client-status">
                            <?php if($vo['id'] != 1): if($vo['status']): ?>
                            <a id="status<?php echo $vo['id']; ?>" onclick="setStatus(<?php echo $vo['id']; ?>,'<?php echo url('Role/setStatus'); ?>');">
                                <span class="label label-primary">开启</span>
                            </a>
                            <?php else: ?>
                            <a id="status<?php echo $vo['id']; ?>" onclick="setStatus(<?php echo $vo['id']; ?>,'<?php echo url('Role/setStatus'); ?>');">
                                <span class="label label-danger">禁用</span>
                            </a>
                            <?php endif; endif; ?>
                        </td>
                        <td><?php echo $vo['create_time']; ?></td>
                        <td class="center">
                            <?php if($vo['id'] != 1): ?>
                            <a title="设置权限" class="btn btn-info" onclick="getRules(<?php echo $vo['id']; ?>)">
                                <i class="fa fa-paste"></i> 分配权限
                            </a>
                            <a title="编辑角色" class="btn btn-info edit" rel="<?php echo $vo['id']; ?>" href="<?php echo url('Role/edit'); ?>">
                                <i class="fa fa-paste"></i> 编辑
                            </a>
                            <a class="btn btn-danger del" title="角色" href="<?php echo url('Role/del',array('id'=>$vo['id'])); ?>">
                                <i class="fa fa-trash-o fa-lg"></i> 删除
                            </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                </table>

            </div>
        </div>
    </div>
</div>
<div class="zTreeDemoBackground left" style="display: none" id="role">
    <input type="hidden" id="nodeid">
    <div class="form-group">
        <div class="col-sm-5 col-sm-offset-2">
            <ul id="treeType" class="ztree"></ul>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-4 col-sm-offset-4" style="margin-bottom: 15px">
            <input type="button" value="确认分配" class="btn btn-primary" id="setaccess"/>
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
<link rel="stylesheet" href="../../static/admin/js/zTree/zTreeStyle.css" type="text/css">
<script type="text/javascript" src="../../static/admin/js/zTree/jquery.ztree.core-3.5.js"></script>
<script type="text/javascript" src="../../static/admin/js/zTree/jquery.ztree.excheck-3.5.js"></script>
<script type="text/javascript" src="../../static/admin/js/zTree/jquery.ztree.exedit-3.5.js"></script>
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

        //确认分配权限
        $("#setaccess").click(function(){
            var zTree = $.fn.zTree.getZTreeObj("treeType");
            var nodes = zTree.getCheckedNodes(true);
            var NodeString = '';
            $.each(nodes, function (n, value) {
                if(n>0){
                    NodeString += ',';
                }
                NodeString += value.id;
            });
            var id = $("#nodeid").val();
            //写入库
           var data= ajax_post("<?php echo url('Role/setaccess'); ?>",{id:id,rule:NodeString},'post','json');
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

        });
    });

    function getRules(id) {
        $("#nodeid").val(id);
        var data=ajax_post("<?php echo url('Role/getRules'); ?>",{id:id},'get','json');
        if(data.code==1){
            zNodes = JSON.parse(data.data);  //将字符串转换成obj
            //页面层
            index = layer.open({
                type: 1,
                area:['500px', '80%'],
                title:'权限分配',
                skin: 'layui-layer-demo', //加上边框
                content: $('#role')
            });

            //设置zetree
            var setting = {
                check:{
                    enable:true
                },
                data: {
                    simpleData: {
                        enable: true
                    }
                }
            };
            $.fn.zTree.init($("#treeType"), setting, zNodes);
            var zTree = $.fn.zTree.getZTreeObj("treeType");
            zTree.expandAll(true);
        }else{
            layer.msg(data.msg, {icon: 5,time:1000});
            return false;
        }
    }
</script>
</body>

</html>