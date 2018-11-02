<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:63:"E:\WWW\caibao\public/../application/admin\view\school\edit.html";i:1535507422;s:55:"E:\WWW\caibao\application\admin\view\public\header.html";i:1535333950;s:55:"E:\WWW\caibao\application\admin\view\public\footer.html";i:1535333950;}*/ ?>
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
                <form class="form-horizontal" method="post" id="signupForm" action="<?php echo url('School/edit'); ?>">
                    <input type="hidden" name="id" value="<?php echo $dataInfo['id']; ?>">

                    <div class="form-group">
                        <label class="col-sm-2 control-label">学校名称</label>
                        <div class="col-sm-10">
                            <input type="text" name="name" class="form-control" value="<?php echo $dataInfo['name']; ?>">
                        </div>
                    </div>

                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">排序</label>
                        <div class="col-sm-10">
                            <input type="text" name="sort" class="form-control" value="<?php echo $dataInfo['sort']; ?>">
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <div class="col-sm-4 col-sm-offset-2">
                            <button class="btn btn-primary" type="submit">保存内容</button>
                            <a href="<?php echo url('School/index'); ?>" style="color: inherit;background: #fff;border: 1px solid #e7eaec;margin-bottom: 5px;border-radius: 3px;display: inline-block;padding: 6px 12px;font-size: 14px;font-weight: 400;line-height: 1.42857143;text-align: center;white-space: nowrap;vertical-align: middle;touch-action: manipulation;cursor: pointer;">取消</a>
                        </div>
                    </div>
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
</body>

</html>