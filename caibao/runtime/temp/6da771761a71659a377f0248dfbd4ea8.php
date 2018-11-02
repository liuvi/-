<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:65:"E:\WWW\caibao\public/../application/home\view\activity\topic.html";i:1535801051;s:54:"E:\WWW\caibao\application\home\view\public\header.html";i:1535620662;}*/ ?>
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
<div class="manback dati" style="background: url('../../static/home/images/backimg.png') center;">
    <div class="topnumber">
        <div class="number">
            <span id="number"></span>/50
        </div>
    </div>
    <div class="area">
        <div class="many">
            <img src="../../static/home/images/l_place.png"/>
            <div class="texts" id="texts">二十一</div>
            <img src="../../static/home/images/r_place.png"/>
        </div>
        <div id="tishow" class="issue">
            <h3></h3>
            <ul>
            </ul>
        </div>
    </div>
    <div class="next">
        <div class="btnc">
            <a href="javaScript:void(0)" id="next" class="a btnred">下一题</a>
        </div>
    </div>
</div>
<script>
    $(function () {
    	var indexs=0;
        var data =ajax_post("<?php echo url('Activity/getList'); ?>",{},'post');
        var solution=[];
        if(data['code']==1){
		 	getlist(data['dataList'],indexs);
        }else{
            layer.msg(data.msg);
            window.location.href="<?php echo url('Activity/sub_succ'); ?>";
            return false;
        }
        $("#next").click(function(){
        	if(solution.length==data['dataList'].length-1){
        		$(this).html("提交答案")	
        	}
        	if(solution.length<data['dataList'].length){
	        	if(indexs==solution.length){
	            	layer.msg('请选择答案');
	            	return false;
	        	}else{
		 			getlist(data['dataList'],indexs+1);
	        	}
	        	indexs=indexs+1
        	}else{
        		//提交答案
                var res=ajax_post("<?php echo url('Activity/subAnswer'); ?>",{result:solution},'post');
                if(res['code']==1){
                    layer.msg(res.msg);
                    window.location.href=res.url;
                    return false;
                }else{
                    layer.msg(res.msg);
                    return false;
                }

        	}
        });
        //选择答案
        $("#tishow").find("ul").on("click","input",function(){
        	var newread={};
        	newread.id=data['dataList'][indexs].id;
        	//newread.ranking=indexs
        	newread.val=$(this).val();
        	solution[indexs]=newread
        	
        });
        function addresult(indexs){
        	var newread={}
        }
    });
    //给页面添值
	 function getlist(data, indexs){	
	 		$("#number").html(indexs+1);
	 		$("#texts").html(SectionToChinese(indexs+1));
			$("#tishow").find("h3").html(data[indexs].name);
    		$("#tishow").find("ul").html(getli(data[indexs],indexs));
    	
        }
	//给页面的答案设置值
	function getli(lis,indexs){		
		var list=""
		$.each(lis['items'],function (n,v) {
			list+="<li><label><input type='radio' value='"+v.value+"' name='correct'/>"+v.value+"、"+v.title+"</label></li>"
		});
		return list;
	}
	var chnNumChar = ["零","一","二","三","四","五","六","七","八","九"];
    var chnUnitSection = ["","万","亿","万亿","亿亿"];
    var chnUnitChar = ["","十","百","千"];
    function SectionToChinese(section){
        var strIns = '', chnStr = '';
        var unitPos = 0;
        var zero = true;
        while(section > 0){
            var v = section % 10;
            if(v === 0){
                if(!zero){
                    zero = true;
                    chnStr = chnNumChar[v] + chnStr;
                }
            }else{
                zero = false;
                strIns = chnNumChar[v];
                strIns += chnUnitChar[unitPos];
                chnStr = strIns + chnStr;
            }
            unitPos++;
            section = Math.floor(section / 10);
        }
        return chnStr;
    }

</script>
</body>
</html>