<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:61:"E:\WWW\caibao\public/../application/admin\view\user\edit.html";i:1536138572;s:55:"E:\WWW\caibao\application\admin\view\public\header.html";i:1535333950;s:55:"E:\WWW\caibao\application\admin\view\public\footer.html";i:1535333950;}*/ ?>
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

<link rel="stylesheet" type="text/css" href="../../static/admin/webupload/webuploader.css">
<link rel="stylesheet" type="text/css" href="../../static/admin/webupload/style.css">
<div class="row">
    <div class="col-sm-12">
        <div class="ibox float-e-margins">
            <div class="ibox-content">
                <form class="form-horizontal" method="post" id="signupForm" action="<?php echo url('User/edit'); ?>">
                    <input type="hidden" name="id" value="<?php echo $info['id']; ?>"/>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">用户名称</label>
                        <div class="col-sm-10">
                            <input type="text" name="username" id="username" class="form-control" value="<?php echo $info['username']; ?>">
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">用户密码</label>
                        <div class="col-sm-10">
                            <input type="password" name="password" id="password" class="form-control" value="">
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">所属角色</label>
                        <div class="col-sm-10">
                            <select class="form-control m-b" name="groupid" id="groupid">
                                <option value="">==请选择==
                                    <?php if(is_array($roleList) || $roleList instanceof \think\Collection || $roleList instanceof \think\Paginator): $i = 0; $__LIST__ = $roleList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                                <option value="<?php echo $vo['id']; ?>" <?php if($vo['id'] == $info['groupid']): ?>selected<?php endif; ?>><?php echo $vo['title']; ?></option>
                                <?php endforeach; endif; else: echo "" ;endif; ?>
                            </select>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">头像</label>
                        <div class="col-sm-10">
                            <input type="hidden" id="data_photo" name="portrait" value="<?php echo $info['portrait']; ?>">
                            <div id="fileList" class="uploader-list" style="float:right"></div>
                            <div id="imgPicker" style="float:left">选择头像</div>
                            <img id="img_data" class="img-circle" height="80px" width="80px" style="float:left;margin-left: 50px;margin-top: -10px;" src="../../uploads/images/<?php echo $info['portrait']; ?>" onerror="this.src='../../static/admin/img/head_default.gif'"/>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">真实姓名</label>
                        <div class="col-sm-10">
                            <input type="text" name="real_name" class="form-control" value="<?php echo $info['real_name']; ?>">
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">状态</label>
                        <div class="col-sm-10">
                            <div class="radio i-checks">
                                <input type="radio" name='status' value="1" <?php if($info['status'] == 1): ?>checked="checked"<?php endif; ?>/>开启&nbsp;&nbsp;
                                <input type="radio" name='status' value="0" <?php if($info['status'] == 0): ?>checked="checked"<?php endif; ?>/>关闭
                            </div>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <div class="col-sm-4 col-sm-offset-2">
                            <button class="btn btn-primary" type="submit">保存内容</button>
                            <a href="<?php echo url('User/index'); ?>" style="color: inherit;background: #fff;border: 1px solid #e7eaec;margin-bottom: 5px;border-radius: 3px;display: inline-block;padding: 6px 12px;font-size: 14px;font-weight: 400;line-height: 1.42857143;text-align: center;white-space: nowrap;vertical-align: middle;touch-action: manipulation;cursor: pointer;">取消</a>
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
<script type="text/javascript" src="../../static/admin/webupload/webuploader.min.js"></script>
<script type="text/javascript">
    var $list = $('#fileList');
    //上传图片,初始化WebUploader
    var uploader = WebUploader.create({

        auto: true,// 选完文件后，是否自动上传。
        swf: '../../static/admin/webupload/Uploader.swf',// swf文件路径
        server: "<?php echo url('Upload/uploadAjax'); ?>",// 文件接收服务端。
        duplicate :true,// 重复上传图片，true为可重复false为不可重复
        pick: '#imgPicker',// 选择文件的按钮。可选。

        accept: {
            title: 'Images',
            extensions: 'gif,jpg,jpeg,bmp,png',
            mimeTypes: 'image/jpg,image/jpeg,image/png'
        },

        'onUploadSuccess': function(file, data, response) {
            $("#data_photo").val(data._raw);
            $("#img_data").attr('src', '../../uploads/images/' + data._raw).show();
        }
    });

    uploader.on( 'fileQueued', function( file ) {
        $list.html( '<div id="' + file.id + '" class="item">' +
            '<h4 class="info">' + file.name + '</h4>' +
            '<p class="state">正在上传...</p>' +
            '</div>' );
    });

    // 文件上传成功
    uploader.on( 'uploadSuccess', function( file ) {
        $( '#'+file.id ).find('p.state').text('上传成功！');
    });

    // 文件上传失败，显示上传出错。
    uploader.on( 'uploadError', function( file ) {
        $( '#'+file.id ).find('p.state').text('上传出错!');
    });

    //提交
    $(function(){
        $('#signupForm').ajaxForm({
            beforeSubmit: checkForm,
            success: userSubmit,
            dataType: 'json'
        });
        function userSubmit(data){
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
        function checkForm(){
            if( '' == $.trim($('#username').val())){
                layer.msg('请输入用户名',{icon:2,time:1500,shade: 0.1}, function(index){
                    layer.close(index);
                });
                return false;
            }

            if( '' == $.trim($('#groupid').val())){
                layer.msg('请选择用户角色',{icon:2,time:1500,shade: 0.1}, function(index){
                    layer.close(index);
                });
                return false;
            }

            if( '' == $.trim($('#password').val())){
                layer.msg('请输入登录密码',{icon:2,time:1500,shade: 0.1}, function(index){
                    layer.close(index);
                });
                return false;
            }

        }

    });
</script>
</body>

</html>