<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:64:"E:\WWW\caibao\public/../application/admin\view\member\index.html";i:1535856839;s:55:"E:\WWW\caibao\application\admin\view\public\header.html";i:1535333950;s:55:"E:\WWW\caibao\application\admin\view\public\footer.html";i:1535333950;}*/ ?>
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

<style>
    select.input-sm{
        height: 35px;
        line-height: 35px;
    }
</style>
<body class="gray-bg">
<div class="wrapper wrapper-content animated fadeInRight">
    <!-- Panel Other -->
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h5>用户列表</h5>
        </div>
        <div class="ibox-content">
            <!--搜索框开始-->

            <form class="form-inline" method="post" action="<?php echo url('index'); ?>">

                <div class="form-group">
                    <label class="font-noraml">姓名：</label>
                    <input type="text" class="form-control" id="name" name="username" value="<?php echo $parameter['username']; ?>" placeholder="输入需查询的姓名" />

                    <label class="font-noraml">手机号：</label>
                    <input type="text" class="form-control" name="tel" id="tel" value="<?php echo $parameter['tel']; ?>" placeholder="输入需查询手机号" />

                    <label class="font-noraml">学校：</label>
                    <div class="input-daterange input-group">
                        <select id="sid" class="chosen-select input-sm form-control input-s-sm inline" name="sid">
                            <option value="0">全部</option>
                            <?php if(is_array($schoolList) || $schoolList instanceof \think\Collection || $schoolList instanceof \think\Paginator): $i = 0; $__LIST__ = $schoolList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                            <option value="<?php echo $vo['id']; ?>" <?php if($parameter['sid'] == $vo['id']): ?>selected<?php endif; ?>><?php echo $vo['name']; ?></option>
                            <?php endforeach; endif; else: echo "" ;endif; ?>
                        </select>
                    </div>
                    <label class="font-noraml">分数：</label>
                    <div class="input-daterange input-group">
                        <select id="fenshu" class="chosen-select input-sm form-control input-s-sm inline" name="fenshu">
                            <option value="0">全部</option>
                            <option value="1" <?php if($parameter['fenshu'] == 1): ?>selected<?php endif; ?>>升序</option>
                            <option value="2" <?php if($parameter['fenshu'] == 2): ?>selected<?php endif; ?>>降序</option>
                        </select>
                    </div>
                    <div class="input-group">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> 搜索</button>&nbsp;&nbsp;&nbsp;&nbsp;
                        <button type="submit" id="export" class="btn btn-primary"> 导出</button>
                    </div>
                </div>
                <input type="hidden" name="daochu" id="daochu" value="0">
            </form>

            <!--搜索框结束-->
            <div class="hr-line-dashed"></div>

            <div class="example-wrap">
                <div class="example">
                    <table class="table table-bordered table-hover">
                        <thead>
                        <tr class="long-tr">
                            <th>id</th>
                            <th>姓名</th>
                            <th>学校</th>
                            <th>班级</th>
                            <th>电话</th>
                            <th>注册时间</th>
                            <th>分数</th>
                            <th>奖品</th>
                        </tr>
                        </thead>
                        <script id="list-template" type="text/html">
                            {{# for(var i=0;i<d.length;i++){ }}
                            <tr class="long-td">
                                <td>{{d[i].id}}</td>
                                <td>{{d[i].username}}</td>
                                <td>{{d[i].name}}</td>
                                <td>{{d[i].title}}</td>
                                <td>{{d[i].tel}}</td>
                                <td>{{d[i].create_time}}</td>
                                <td>{{d[i].fract}}</td>
                                <td id="lq{{d[i].id}}">
                                    {{# if(d[i].prize==0){ }}
                                        无
                                    {{# }else if(d[i].prize==1){ }}
                                        已领取
                                    {{# }else if(d[i].prize==2){　}}
                                        已发放
                                    {{# }}}
                                </td>
                                {{# if(d[i].prize==1){ }}
                                <td>
                                    <a id="status{{d[i].id}}" onclick="setTopStatus({{d[i].id}},'<?php echo url('Member/setTopStatus'); ?>');">
                                    <span class="label label-primary">设为已发放</span>
                                    </a>
                                </td>
                                {{#}}}
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
    $(function () {
        $('#export').click(function(){
            $("#daochu").val(1);

        });
    });

    function setTopStatus(id,url) {
        var data=ajax_post(url,{id:id},'get','json');
        if(data['code']==1){
            layer.msg(data.msg,{icon:6,time:1000},function (index) {
                layer.close(index);
                $('#status'+id).parents('td').remove();
                $("#lq"+id).text('已发放')
            });
        }
    }
    //laypage分页
    var name=$("#name").val();
    var tel=$("#tel").val();
    var sid=$("#sid").val();
    var fenshu=$("#fenshu").val();
    var searchObj={username:name,tel:tel,sid:sid,fenshu:fenshu};
    Ajaxpage(1,'<?php echo url("Member/index"); ?>',"<?php echo $allpage; ?>",searchObj);
</script>
</body>
</html>