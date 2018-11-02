<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:67:"E:\WWW\caibao\public/../application/home\view\activity\ranking.html";i:1535815162;s:54:"E:\WWW\caibao\application\home\view\public\header.html";i:1535620662;}*/ ?>
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
<div class="manback" style="background: url('../../static/home/images/backimg.png') center;">
    <div class="paiming">
        <?php if($dataList[1]['name']): ?>
        <div class="minkaung kaung">
            <div class="hider">
                <img src="../../static/home/images/dengj2.png"/>
                <span><?php echo $dataList[1]['name']; ?></span>
            </div>
            <div class="foter">
                <p>参与人数 <?php echo $dataList[1]['people']; ?></p>
                <h3><?php echo $dataList[1]['abs_fenshu']; ?></h3>
            </div>
        </div>
        <?php endif; if($dataList[0]['name']): ?>
        <div class="bigkaung kaung">
            <div class="hider">
                <img src="../../static/home/images/dengj1.png"/>
                <span><?php echo $dataList[0]['name']; ?></span>
            </div>
            <div class="foter">
                <p>参与人数 <?php echo $dataList[0]['people']; ?></p>
                <h3><?php echo $dataList[0]['abs_fenshu']; ?></h3>
            </div>
        </div>
        <?php endif; if($dataList[2]['name']): ?>
        <div class="minkaung kaung">
            <div class="hider">
                <img src="../../static/home/images/dengj3.png"/>
                <span><?php echo $dataList[2]['name']; ?></span>
            </div>
            <div class="foter">
                <p>参与人数 <?php echo $dataList[2]['people']; ?></p>
                <h3><?php echo $dataList[2]['abs_fenshu']; ?></h3>
            </div>
        </div>
        <?php endif; ?>
    </div>
    <div class="table">
        <table>
            <tr>
                <th>排名</th>
                <th>学校名称</th>
                <th>参与人数</th>
                <th>平均分</th>
            </tr>
            <?php if(is_array($dataList) || $dataList instanceof \think\Collection || $dataList instanceof \think\Paginator): $k = 0; $__LIST__ = $dataList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?>
            <tr>
                <td><?php echo $k; ?></td>
                <td><?php echo $vo['name']; ?></td>
                <td><?php echo $vo['people']; ?></td>
                <td><span><?php echo $vo['abs_fenshu']; ?></span></td>
            </tr>
            <?php endforeach; endif; else: echo "" ;endif; ?>
        </table>
    </div>
</div>
</body>
</html>