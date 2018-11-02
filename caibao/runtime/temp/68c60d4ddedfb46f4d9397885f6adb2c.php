<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:65:"E:\WWW\caibao\public/../application/home\view\register\index.html";i:1535965136;s:54:"E:\WWW\caibao\application\home\view\public\header.html";i:1535620662;}*/ ?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>采宝</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0" />
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <link rel="stylesheet" type="text/css" href="../../static/home/css/common.css" />
    <link rel="stylesheet" type="text/css" href="../../static/home/css/style.css" />
    <script src="../../static/home/js/jquery-1.11.1.min.js"></script>
    <script src="../../static/home/js/layer/layer.js"></script>
</head>
<script src="../../static/home/js/plugins.js"></script>
<body>
<div class="manback" style="background: url('../../static/home/images/backimg.png') center;">
    <div class="activity">
        <img src="../../static/home/images/zhishidabipin.png"/>
    </div>
    <div class="enroll">

            <div class="confang">
                <label class="label">
                    <span class=""><em>*</em>学&nbsp;&nbsp;校</span>
                    <select name="sid" class="school" id="school">
                        <option value="">请选择所在学校</option>
                        <?php if(is_array($SchoolList) || $SchoolList instanceof \think\Collection || $SchoolList instanceof \think\Paginator): $i = 0; $__LIST__ = $SchoolList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                        <option value="<?php echo $vo['id']; ?>"><?php echo $vo['name']; ?></option>
                        <?php endforeach; endif; else: echo "" ;endif; ?>
                    </select>
                    <i class="iconfont">&#xe64a;</i>
                </label>
            </div>
            <div class="confang">
                <label class="label">
                    <span class="grade"><em>*</em>班&nbsp;&nbsp;级</span>
                    <select name="cid" class="grade" id="schoolclass">
                        <option value="0">请选择所在班级</option>
                    </select>
                    <i class="iconfont">&#xe64a;</i>
                </label>
            </div>
            <div class="confang">
                <label class="label">
                    <span class=""><em>*</em>姓&nbsp;&nbsp;名</span>
                    <input type="text" placeholder="请输入姓名" class="name" name="" id="" value="" />
                </label>
            </div>
            <div class="confang">
                <label class="label">
                    <span class=""><em>*</em>手机号</span>
                    <input type="tel"  placeholder="请输入手机号" name="phone" maxlength="11" class="phone" id="" value="" />
                </label>
            </div>
            <div class="confang">
                <div class="label">
                    <span class=""><em>*</em>验证码</span>
                    <input type="number" placeholder="验证码" class="yzm"/>
                    <cite class="send_code">
                        <label class="obtain">获取验证码</label>
                        <label class="repeat">重新发送<span class="count_down">60</span></label>
                    </cite>
                </div>
            </div>
            <div class="btnc">
                <input type="submit" class="submit" value="完  成">
            </div>

        <p class="reminder">
            <em>*</em>本次知识比赛参与对象为成都市民，请务必填写详细信息，否则将会导致无法获得奖品和后续获得的说明。
        </p>
    </div>
</div>
<script type="text/javascript">
    $(function(){
        $("#school").change(function(){
            var sid=$(this).val();
            var data=ajax_post("<?php echo url('Register/getClass'); ?>",{sid:sid},'post');
            var str='<option value="0">请选择所在班级</option>';
            if(data['code']==1){
                $.each(data['dataList'],function (n,v) {
                    str+='<option value="'+v['id']+'">'+v['title']+'</option>'
                });
                $("#schoolclass").html(str);
            }else{
                $("#schoolclass").html(str);
            }
        });
        var $Phonezz= /^13[0-9]{9}|17[0-9]{9}|14[0-9]{9}|15[0-9]{9}|16[0-9]{9}|19[0-9]{9}|18[0-9]{9}$/;
        $(".enroll .send_code .obtain").click(function(){
            var phone=$(".enroll .confang .label .phone").val();
            if(!$Phonezz.test(phone)){
                layer.msg('电话号码有误');
            }else{
                //倒计时
                var data=ajax_post("<?php echo url('Register/getCode'); ?>",{tel:phone},'post');
                if(data['status']==1){
                    layer.msg("验证码已下发到你的手机");
                }else{
                    layer.msg(data.msg);
                    return false
                }
                //倒计时
                $(this).hide();
                $(".enroll .send_code .repeat").css("display","block");
                var time=60;
                var time_f;
                time_f=setInterval(function(){
                    if(time==1){
                        clearInterval(time_f);
                        $(".enroll .send_code .repeat").hide();
                        $(".enroll .send_code .obtain").show().html("重新获取");
                        $(".enroll .send_code .repeat .count_down").html(60);
                    }else{
                        time--;
                        $(".enroll .send_code .repeat .count_down").html(time);
                    }
                },1000);
            }
        });

        $(".enroll .submit").click(function(){
            var phone=$(".enroll .confang .label .phone").val();
            var yzm=$(".enroll .confang .label .yzm").val();
            var school=$(".enroll .confang .label .school").val();
            var grade=$("#schoolclass").val();
            var name=$(".enroll .confang .label .name").val();
            if(school==""){
                layer.msg('请选择学校');
                return false;
            }

            if(grade==0){
                layer.msg('请选择班级');
                return false;
            }
            if(name==""){
                layer.msg('请输入姓名');
                return false;
            }
            if(phone==""){
                layer.msg('请输入电话号码');
                return false;
            }
            if(!$Phonezz.test(phone)){
                layer.msg('电话号码有误');
                return false;
            }
//            if(yzm.length<6){
//                layer.msg('验证码有误');
//                return false;
//            }
           var data=ajax_post("<?php echo url('Register/doReg'); ?>",{tel:phone,code:yzm,sid:school,cid:grade,username:name},'post');
            if(data['code']==1){
                layer.msg(data.msg);
                window.location.href=data.url;
            }else{
                layer.msg(data.msg);
                return false;
            }
        });
    })
</script>
</body>
</html>