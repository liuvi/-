<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:64:"E:\WWW\caibao\public/../application/admin\view\school\index.html";i:1535549639;s:55:"E:\WWW\caibao\application\admin\view\public\header.html";i:1535333950;s:55:"E:\WWW\caibao\application\admin\view\public\footer.html";i:1535333950;}*/ ?>
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

<body class="gray-bg">
<div class="wrapper wrapper-content animated fadeInRight">
    <!-- Panel Other -->
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h5>学校列表</h5>
        </div>
        <div class="ibox-content">
            <!--搜索框开始-->
            <div class="row">
                <div class="col-sm-12">
                    <form name="admin_list_sea" class="form-search" method="post" action="<?php echo url('index'); ?>">
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="text" id="name" class="form-control" name="name" value="<?php echo $parameter['name']; ?>" placeholder="输入需查询的学校名称" />
                                <span class="input-group-btn">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> 搜索</button>
                            </span>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!--搜索框结束-->
            <div class="hr-line-dashed"></div>
            <div class="">
                <a href="<?php echo url('School/add'); ?>" class="btn btn-primary add" title="添加学校">添加学校</a>
                <a href="<?php echo url('School/add_class'); ?>" class="btn btn-primary add" title="添加班级">添加班级</a>
            </div>
            <div class="example-wrap">
                <div class="example">
                    <table class="table table-bordered table-hover">
                        <thead>
                        <tr class="long-tr">
                            <th>id</th>
                            <th>学校名称</th>
                            <th>排序</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <script id="list-template" type="text/html">
                            {{# for(var i=0;i<d.length;i++){ }}
                            <tr class="long-td">
                                <td>{{d[i].id}}</td>
                                <td>{{d[i].name}}</td>
                                <td>{{d[i].create_time}}</td>
                                <td>
                                    <a title="添加班级" class="btn btn-info" rel="{{d[i].id}}" onclick="editDate(this,'<?php echo url('School/add_class'); ?>')">
                                        <i class="fa fa-paste"></i> 添加班级
                                    </a>
                                    <a title="查看班级" class="btn btn-info" rel="" href="<?php echo url('School/show_class'); ?>?sid={{d[i].id}}">
                                        <i class="fa fa-paste"></i> 查看班级
                                    </a>
                                    <a title="修改学校" class="btn btn-info" rel="{{d[i].id}}" onclick="editDate(this,'<?php echo url('School/edit'); ?>')">
                                        <i class="fa fa-paste"></i> 编辑
                                    </a>
                                    <a class="btn btn-danger" title="学校" onclick="delData(this,'<?php echo url('School/del'); ?>?id={{d[i].id}}')">
                                        <i class="fa fa-trash-o fa-lg"></i> 删除
                                    </a>
                                </td>
                            </tr>
                            {{# }}}
                        </script>
                        <tbody id="list-content"></tbody>
                    </table>
                    <div id="AjaxPage" style=" text-align: right;"></div>
                    <div id="allpage" style=" text-align: right;"></div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<!-- 加载动画 -->
<div class="spiner-example">
    <div class="sk-spinner sk-spinner-three-bounce">
        <div class="sk-bounce1"></div>
        <div class="sk-bounce2"></div>
        <div class="sk-bounce3"></div>
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
    //laypage分页
    var name=$("#name").val();
    var searchObj={name:name}
    Ajaxpage(1,'<?php echo url("School/index"); ?>',"<?php echo $allpage; ?>",searchObj);
</script>
</body>
</html>