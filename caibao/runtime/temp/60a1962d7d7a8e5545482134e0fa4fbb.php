<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:65:"E:\WWW\caibao\public/../application/home\view\activity\index.html";i:1535807494;s:54:"E:\WWW\caibao\application\home\view\public\header.html";i:1535620662;}*/ ?>
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
<body>
<div class=" manback" style="background: url('../../static/home/images/bing.png') no-repeat center;">
    <div class="activity">
        <img src="../../static/home/images/zhishidabipin.png"/>
    </div>
    <div class="btnc">
        <a href="<?php echo url('Activity/topic'); ?>" class="a btnred">立即答题</a>
    </div>
    <div class="btnc">
        <a href="<?php echo url('Activity/ranking'); ?>" class="a btngray">区域排名</a>
    </div>
    <div class="a">
        <a href="<?php echo url('Activity/explain',array('pid'=>1)); ?>">活动说明></a>
    </div>
</div>
</body>
</html>