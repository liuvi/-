<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:68:"E:\WWW\caibao\public/../application/home\view\activity\sub_succ.html";i:1535965812;s:54:"E:\WWW\caibao\application\home\view\public\header.html";i:1535620662;}*/ ?>
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
<script type="text/javascript" src="../../static/home/js/ldxjquery.js"></script>
<script src="../../static/home/js/plugins.js"></script>
<body>
<div class=" manback" style="background: url('../../static/home/images/backimg.png') no-repeat center;">
    <div class="result">
        <div class="quanred">
            <h3><?php echo $dataInfo['fenshu']; ?> 分</h3>
            <p>你的得分</p>
        </div>
        <div class="quanred">
            <h3>NO.<?php echo $dataInfo['rank']; ?></h3>
            <p>你的排名</p>
        </div>
    </div>
    <div class="activity">
        <img src="../../static/home/images/text.png"/>
    </div>
    <div class="tishitext">
       <?php echo $dataInfo['shuomin']; ?>
    </div>
    <div id="again">
    <div class="btnc">
        <?php if($dataInfo['user']['prize'] == 0): ?>
        <a href="javaScript:void(0)" class="a btnred" id="draw">立即领取</a>
        <?php else: ?>
        <a href="javaScript:void(0)" class="a btngray" >立即领取</a>
        <?php endif; ?>
    </div>
    <div class="btnc">
        <?php if($dataInfo['user']['share_time'] != date('Ymd')): ?>
        <a href="javaScript:void(0)" class="a btnred" id="share">分享再来一次</a>
        <?php else: ?>
        <a href="javaScript:void(0)" class="a btngray" >分享再来一次</a>
        <?php endif; ?>
    </div>
    </div>
</div>
<div class="tanc">
    <div class="rem">
        <i class="iconfont">&#xe658;</i>
    </div>
    <div class="kap">
        <img src="../../static/home/images/OK.png"/>
        <h3>恭喜你，领取成功！</h3>
        <p>活动奖品将于线下统一发放</p>
    </div>
</div>
<div class="share">
    <img src="../../static/home/images/goweb.png" />
</div>
<script src="http://res.wx.qq.com/open/js/jweixin-1.2.0.js"></script>
<script>
    $(function () {
        $("#draw").click(function () {
            var data=ajax_post("<?php echo url('Activity/take'); ?>",{},'post');
            if(data['code']==1){
                $(".tanc").fadeToggle(300);
                $("#draw").unbind();
                $("#draw").addClass('btngray');
                return false;
            }else{
                layer.msg(data.msg);
                return false;
            }
        })
    });

    wx.config({
        debug: false, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
        appId: '<?php echo $signPackage["appId"];?>',
        timestamp: <?php echo $signPackage["timestamp"];?>,
        nonceStr: '<?php echo $signPackage["nonceStr"];?>',
        signature: '<?php echo $signPackage["signature"];?>',
        jsApiList: ['onMenuShareTimeline','onMenuShareAppMessage'] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
    });
    wx.ready(function () {
        wx.onMenuShareTimeline({
            title: '关注消费安全知识', // 分享标题
            desc: '我在进行消防知识答题，快来参与吧！', // 分享描述
            link: 'www.baidu.com', // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
            imgUrl: '', // 分享图标
            success: function () {
                callbackShare(1);
            },
            cancel: function () {
                // 用户取消分享后执行的回调函数
            }
        });
        wx.onMenuShareAppMessage({
            title:'关注消费安全知识', // 分享标题
            desc: '我在进行消防知识答题，快来参与吧！', // 分享描述
            link: 'www.baidu.com', // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
            imgUrl: '', // 分享图标
            type: '', // 分享类型,music、video或link，不填默认为link
            dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
            success: function () {
                callbackShare(2);
            },
            cancel: function () {
                // 用户取消分享后执行的回调函数
            }
        });



    });

    //分享回调时记录分享
    function callbackShare(status){
        var data=ajax_post("<?php echo url('Activity/share'); ?>",{status:status},'post');
        if(data['code']==1){
            layer.msg(data.msg);
            $(".tishitext").text('分享成功，你已获得再次答题的机会');
            $("#again").html('<div class="btnc"><a href="<?php echo url('Activity/topic'); ?>" class="a btnred">再次答题</a> </div>');
        }else{
            layer.msg(data.msg);
            return false;
        }
    }
</script>
</body>
</html>