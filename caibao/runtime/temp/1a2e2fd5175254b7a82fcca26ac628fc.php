<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:63:"E:\WWW\caibao\public/../application/admin\view\login\login.html";i:1535951396;}*/ ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

    <title>后台管理系统--登录</title>
    <meta name="keywords" content="">
    <meta name="description" content="">
    <link href="../../static/admin/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../static/admin/css/font-awesome.min.css?v=4.4.0" rel="stylesheet">
    <link href="../../static/admin/css/animate.min.css" rel="stylesheet">
    <link href="../../static/admin/css/style.min.css" rel="stylesheet">
    <link href="../../static/admin/css/login.min.css" rel="stylesheet">
    <script src="../../static/admin/js/jquery.min.js?v=2.1.4"></script>
    <script src="../../static/admin/js/layer/layer.js"></script>
    <!--[if lt IE 8]>
    <meta http-equiv="refresh" content="0;ie.html" />
    <![endif]-->
    <script>
        if(window.top!==window.self){window.top.location=window.location};
    </script>
</head>

<body class="signin">
<div class="signinpanel">
    <div class="row">
        <div class="col-sm-7">
            <div class="signin-info">

                <div class="m-b"></div>
                <h4>欢迎使用 <strong>后台管理系统</strong></h4>

            </div>
        </div>
        <div class="col-sm-5">
            <form>
                <h4 class="no-margins">登录：</h4>
                <p class="m-t-md">登录后台管理系统</p>
                <input type="text" class="form-control uname" id="username" placeholder="用户名" />
                <input type="password" id="password" class="form-control pword m-b" placeholder="密码" />
                <input type="text" id="code" class="form-control" placeholder="验证码" style="color:black;width:140px;float:left;margin:0px 0px;"/>
                <img src="<?php echo url('checkVerify'); ?>" onclick="javascript:this.src='<?php echo url('checkVerify'); ?>?tm='+Math.random();" style="float:right;cursor: pointer"/>

                <a class="btn btn-success btn-block" id="btn" style="height: 35px;margin-top: 53px;">登录</a>
            </form>
        </div>
    </div>
    <div class="signup-footer">
        <div class="pull-left">

        </div>
    </div>
</div>
</body>

</html>
<script>
    document.onkeydown=function(event){
        e = event ? event :(window.event ? window.event : null);
        if(e.keyCode==13){

            $('#btn').click();
        }
    }
    $(function () {
       $('#btn').click(function(){
           var username=$("#username").val();
           var password=$("#password").val();
           var code    =$("#code").val();
           if(username==''){
               layer.msg('<a style="color:#6E6E6E;">用户名不能为空</a>',{icon:5,time:1000});
               return false;
           }
           if(password==''){
               layer.msg('<a style="color:#6E6E6E;">请输入密码</a>',{icon:5,time:1000});
               return false;
           }
           if(code==''){
               layer.msg('<a style="color:#6E6E6E;">请输入验证码!</a>',{icon:5,time:1000});
               return false;
           }
           $.ajax({
               type:"post",
               url:"<?php echo url('Login/doLogin'); ?>",
               data:{username:username,password:password,code:code},
               dataType:"json",
               success:function (data) {
                   if(data.code==1){
                       layer.msg('<a style="color:#6E6E6E;">'+data.msg+'</a>', {icon: 6,time:1000});
                       window.location.href=data.url;
                   }else{
                       layer.msg('<a style="color:#6E6E6E;">'+data.msg+'</a>', {icon: 5,time:1000});
                       return false;
                   }
               }
           });
       }) ;
    });
</script>