<?php
if (!defined('HOST')){
//	die('not access');
?>

	<meta charset="UTF-8">

<script type="text/javascript" src="../../../js/jquery.1.9.1.min.js"></script>


<?php }?>
<script>
$(document).ready(function() {
	var norm_id=false;
	try{
		{params}
		norm_id=params.norm_id;
	}catch(e){
		//TODO handle the exceptionalert(norm_id);
	}



	//var jsonstr = '{"name":"广东科学技术职业学院立项建设评审指标体系","num":"1","info":[{"option_msg":"基地类型","option_fenzhi":"15","option_range":"","sort":"1","info":[{"option_msg":"具有专业发展和技术先进性的新建专业项目","option_fenzhi":null,"minscore":"1","maxscore":"5","option_range":["1","5"],"sort":"0"},{"option_msg":"品牌专业等重大专业建设项目以及省级实训基地、省公共实训中心、重点实验室等重点建设项目","option_fenzhi":null,"minscore":"6","maxscore":"10","option_range":["6","10"],"sort":"0"}]},{"option_msg":"建设资金及预算","option_fenzhi":"20","option_range":"","sort":"2","info":[{"option_msg":"资金投入不够明确，预算安排不够合理。","option_fenzhi":null,"minscore":"1","maxscore":"4","option_range":["1","4"],"sort":"0"},{"option_msg":"已有项目专项建设资金；资金投入明确，有详细的计划和具体的实施办法，预算安排合理。","option_fenzhi":null,"minscore":"10","maxscore":"15","option_range":["10","15"],"sort":"0"}]},{"option_msg":"建设场地需求规划","option_fenzhi":"10","option_range":"","sort":"3","info":[{"option_msg":"未有建设场地或不明确。","option_fenzhi":null,"minscore":"1","maxscore":"2","option_range":["1","2"],"sort":"0"},{"option_msg":"已有建设场地，规划合理。","option_fenzhi":null,"minscore":"3","maxscore":"5","option_range":["3","5"],"sort":"0"}]},{"option_msg":"建设依据","option_fenzhi":"10","option_range":"","sort":"4","info":[{"option_msg":"不符合品牌专业等重点专业建设发展的要求，实践教学任务不明确。没有前瞻性，与区域产业发展需求和\\r\\n\\r\\n   \\r\\n\\r\\n行业发展趋势结合度不高。","option_fenzhi":null,"minscore":"1","maxscore":"4","option_range":["1","4"],"sort":"0"},{"option_msg":"符合品牌专业等重点专业建设发展的要求，符合专业教学计划于大纲的要求，实践教学任务明确。项目建\\r\\n\\r\\n   \\r\\n\\r\\n设有一定的前瞻性，能与区域产业发展需求和行业发展趋势结合。","option_fenzhi":null,"minscore":"1","maxscore":"2","option_range":["1","2"],"sort":"0"},{"option_msg":"符合品牌专业等重点专业建设发展的要求，符合专业教学计划于大纲的要求，实践教学任务明确。项目具\\r\\n\\r\\n   \\r\\n\\r\\n有前瞻性，紧密解饿区域产业发展需求和兴业发展趋势","option_fenzhi":null,"minscore":"1","maxscore":"2","option_range":["1","2"],"sort":"0"}]},{"option_msg":"建设目标","option_fenzhi":"10","option_range":"","sort":"5","info":[{"option_msg":"二级标题","option_fenzhi":null,"minscore":"0","maxscore":"2","option_range":["0","2"],"sort":"0"},{"option_msg":"实践教学目标不明确，专业共享性和整合度不好，预期使用率低，无岗位技能培养特色。","option_fenzhi":null,"minscore":"0","maxscore":"2","option_range":["0","2"],"sort":"0"},{"option_msg":"有明确的实践教学目标，有很好的专业共享性和整合度，预期使用率高，能较好满足岗位技能培养需求","option_fenzhi":null,"minscore":"0","maxscore":"2","option_range":["0","2"],"sort":"0"}]},{"option_msg":"建设思路与措施","option_fenzhi":"10","option_range":"","sort":"6","info":[{"option_msg":"建设思路不清晰，建设要求不明确，设备选型不恰当，技术路线不合理、不可行，建设思路不明确。","option_fenzhi":null,"minscore":"0","maxscore":"2","option_range":["0","2"],"sort":"0"},{"option_msg":"建设思路清晰，建设要求比较明确，设备选型基本恰当，技术路线合理、可行，建设思想明确","option_fenzhi":null,"minscore":"0","maxscore":"2","option_range":["0","2"],"sort":"0"},{"option_msg":"建设思路清晰，建设要求明确，设备选型恰当，技术路线先进，建设思路创新","option_fenzhi":null,"minscore":"0","maxscore":"2","option_range":["0","2"],"sort":"0"}]},{"option_msg":"项目实施的预期使用成效","option_fenzhi":"15","option_range":"","sort":"7","info":[{"option_msg":"设备和资源开放共享度低。","option_fenzhi":null,"minscore":"0","maxscore":"2","option_range":["0","2"],"sort":"0"},{"option_msg":"设备和资源开放共享度高。","option_fenzhi":null,"minscore":"0","maxscore":"2","option_range":["0","2"],"sort":"0"},{"option_msg":"项目常丹科研任务不明确，培养学生创新能力不强","option_fenzhi":null,"minscore":"0","maxscore":"2","option_range":["0","2"],"sort":"0"},{"option_msg":"项目能承担一定的科研任务，并且能培养学生一定的创新能力。","option_fenzhi":null,"minscore":"0","maxscore":"2","option_range":["0","2"],"sort":"0"},{"option_msg":"不能承担专业课程实践教学任务，设备利用率低，实验实训项目开出率较低。","option_fenzhi":null,"minscore":"0","maxscore":"2","option_range":["0","2"],"sort":"0"},{"option_msg":"能基本承担专业主干课程实践教学任务，能较好利用设备，使用率达到50%以上。","option_fenzhi":null,"minscore":"0","maxscore":"2","option_range":["0","2"],"sort":"0"},{"option_msg":"能完全承担专业主干课程实践教学任务，能承担学生综合性自主实践训练，设备利用充\\r\\n\\r\\n \\r\\n\\r\\n分，使用率  达到80%以上。","option_fenzhi":null,"minscore":"0","maxscore":"2","option_range":["0","2"],"sort":"0"}]},{"option_msg":"项目实施的预期使用成效","option_fenzhi":"10","option_range":"","sort":"8","info":[{"option_msg":"设备和资源开放共享项目可实施性不强，有一定的安全或环境影响隐患","option_fenzhi":null,"minscore":"0","maxscore":"2","option_range":["0","2"],"sort":"0"},{"option_msg":"项目可实施性强，没有安全或环境影响隐患。","option_fenzhi":null,"minscore":"0","maxscore":"6","option_range":["0","6"],"sort":"0"}]}]}';
	var jsonstr='';
	if(norm_id){
		js.ajax(js.getajaxurl('getnormdetail', 'project_comment', 'main'), {norm_id:norm_id}, function(ds) {
            jsonstr=ds.data;
			formForm(jsonstr);
            $('.score').css('display','none');
            $('#all_score').css('display','none');
		}, 'post,json');
		$('#option_title').css('display','none');
		$('#totalscore').css('display','none');
		$('.pinshen_tip').css('display','none');

	}else{
        if (getUrlParam('pinshentype')=='project_end' ){
            $('.projectEndDiv').css('display','block');
            if (getUrlParam('norm_id')){
                $.ajax({
                    url:getRootPath() + "/?d=main&m=project_comment&a=getnormdetail&ajaxbool=true",
                    type:'post',
                    data:{'norm_id':getUrlParam('norm_id')},
                    dataType:'json',
                    success:function (ds) {
                        var jsonstrs=ds.data;
                        formForm(jsonstrs);
                        $('.score').css('display','none');
                        $('#all_score').css('display','none');
                        $('#option_title').css('display','none');
                        $('#totalscore').css('display','none');
                        $('#reviewEnd_Suggest').attr('readonly','readonly')
                    }
                })
            }else{
                jsonstr = sessionStorage.getItem("normpreview");
                formForm(jsonstr);
                var review_opinion_end = sessionStorage.getItem('review_opinion_end');
                var level_suggest = sessionStorage.getItem('level_suggest');
                var publish_suggest = sessionStorage.getItem('publish_suggest');
                $('#reviewEnd_Suggest').val(review_opinion_end);
                $('#publish_suggest'+publish_suggest).attr('checked','checked');
                $('#level_suggest'+level_suggest).attr('checked','checked');
                sessionStorage.removeItem('review_opinion_end');
                sessionStorage.removeItem('level_suggest');
                sessionStorage.removeItem('publish_suggest');
            }
        }
        else if (getUrlParam('pinshentype')=='project_start'){
            $('.projectStartDiv').css('display','block');
            if (getUrlParam('norm_id')) {
                $.ajax({
                    url:getRootPath() + "/?d=main&m=project_comment&a=getnormdetail&ajaxbool=true",
                    type:'post',
                    data:{'norm_id':getUrlParam('norm_id')},
                    dataType:'json',
                    success:function (ds) {
                         var jsonstrs=ds.data;
                        formForm(jsonstrs);
                        $('.score').css('display','none');
                        $('#all_score').css('display','none');
                        $('#option_title').css('display','none');
                        $('#totalscore').css('display','none');
                        $('#review_opinion').attr('readonly','readonly')
                    }
                })
            }else{
                jsonstr = sessionStorage.getItem("normpreview");
                formForm(jsonstr);
                var review_opinion = sessionStorage.getItem("review_opinion");
                $('#review_opinion').val(review_opinion);
                sessionStorage.removeItem('review_opinion');
            }
        }
	}
/*	$('table').on('keyup','input',function(){
        var c=$(this);
        if(/[^\d]/.test(c.val())){//替换非数字字符
            var temp_amount=c.val().replace(/[^\d]/g,'');
            $(this).val(temp_amount);
        }
    });
	*/
    //是预览指标则不显示评审意见
    var type = getUrlParam('type');
    if (type){
        $('.pinshen_tip').css('display','none');
        $('#option_title').css('display','none');
        $('.total_score').css('display','none');
    }

	$('body').on('change','#mytable input[name="option"]',function() {
		var total = 0;
		$('#mytable input[name="option"]').each(function() {
			var c=$(this);
	        if(/[^\d]/.test(c.val())){//替换非数字字符
	            var temp_amount=c.val().replace(/[^\d]/g,'');
	            $(this).val(temp_amount);
	        }
			if(parseInt($(this).val()) > parseInt($(this).parent().parent().children().children('span').html()) || parseInt($(this).val()) < 0) {
				alert('数据有误！');
				$(this).val('');
			}
			if(parseInt($(this).val()) && $(this).val()!='') {
				total += parseInt($(this).val());
			}
			$('#totalscore').html(total);
		});
	});

	var tmp = '';
	$('body').on('focus','table input',function(){
		tmp = $(this).attr('placeholder');
		$(this).attr('placeholder','');
	}).on('blur','table input',function(){
		$(this).attr('placeholder',tmp);
	});
});




//该函数接受一段json字符串为参数，并生成表格
function formForm(jsonstr) {
	var jsonobj = $.parseJSON(jsonstr);
	$('.xmk-panel-title').html(jsonobj.name);

	var tr = '';
	$('#totalscore').html(jsonobj.zongfen);//总得分
    /*	for(var i = 0; i < jsonobj.info.length; i++) { //循环最外层，一共有多少项总指标
		var td = '';
		var mainTarget = '';
		var itr = '';
		for(var j = 0; j < jsonobj.info[i].info.length; j++) { //连同里层一起循环，一次性把所有行都画出来
			var subTarget = []; //subTarget.length=0;
			var sortRange = []; //sortRange.length=0;
			var mainTarget = []; //mainTarget.length=0;
			var inputScore = []; //inputScore.length=0;
			mainTarget[0] = '<td rowspan="' + jsonobj.info[i].info.length + '">' + jsonobj.info[i].option_msg + '<br />(' + '<span>' + jsonobj.info[i].option_fenzhi + '</span>' + '分)</td>'; //主项标题，需要合并
			subTarget[j] = jsonobj.info[i].info[j].option_msg; //子项标题
			sortRange[j] = jsonobj.info[i].info[j].minscore + '-' + jsonobj.info[i].info[j].maxscore; //子项分值区间
			//inputScore[0] = '<td rowspan="' + jsonobj.info[i].info.length + '"><input name="option" type="text" placeholder="填写分数"></input></td>'; //主项分值填写，需要合并
			if(jsonobj.info[i].user_dafen){
					inputScore[0] = '<td rowspan="' + jsonobj.info[i].info.length + '">'+jsonobj.info[i].user_dafen+'</td>'; //主项分值填写，需要合并
			}else{
					inputScore[0] = '<td rowspan="' + jsonobj.info[i].info.length + '"></td>'; //主项分值填写，需要合并
			}

			itr += '<tr>' + mainTarget[j] + '<td style="text-align: left;">' + subTarget[j] + '</td><td>' + sortRange[j] + '</td>' + inputScore[j] + '</tr>';
		}
		tr += itr;

	}*/
    for(var i = 0; i < jsonobj.info.length; i++) { //循环最外层，一共有多少项总指标
        var td = '';
        var mainTarget = '';
        var itr = '';
        for(var j = 0; j < jsonobj.info[i].info.length; j++) { //连同里层一起循环，一次性把所有行都画出来
            var subTarget = []; //subTarget.length=0;
            var sortRange = []; //sortRange.length=0;
            var mainTarget = []; //mainTarget.length=0;
            var inputScore = []; //inputScore.length=0;
            var score_fenzhi = [];
            mainTarget[0] = '<td rowspan="' + jsonobj.info[i].info.length + '">' + jsonobj.info[i].option_msg + '<br /></td>'; //主项标题，需要合并,(' + '<span>' + jsonobj.info[i].option_fenzhi + '</span>' + '分)
            score_fenzhi[0] = '<td rowspan="'+ jsonobj.info[i].info.length +'">'+jsonobj.info[i].option_fenzhi+'</td>';
            subTarget[j] = jsonobj.info[i].info[j].option_msg; //子项标题
            sortRange[j] = jsonobj.info[i].info[j].minscore + '-' + jsonobj.info[i].info[j].maxscore; //子项分值区间
            if(jsonobj.info[i].user_dafen){
                inputScore[0] = '<td rowspan="' + jsonobj.info[i].info.length + '" class="score">'+jsonobj.info[i].user_dafen+'</td>'; //主项分值填写，需要合并
            }else{
                //没有打分
                //inputScore[0] = '<td rowspan="' + jsonobj.info[i].info.length + '" class="score"></td>'; //主项分值填写，需要合并
            }

            itr += '<tr>' + mainTarget[j] +score_fenzhi[j]+'<td style="text-align: center;">' + subTarget[j] + '</td><td>' + sortRange[j] + '</td>' + inputScore[j] + '</tr>';
        }
        tr += itr;

    }
	$('#mytable tbody').append(tr);
}

 function getUrlParam(name) {
            var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)"); //构造一个含有目标参数的正则表达式对象
            var r = window.location.search.substr(1).match(reg);  //匹配目标参数
            if (r != null) return unescape(r[2]); return null; //返回参数值
        }

 function getRootPath() {
    //获取当前网址，如： http://localhost:8083/uimcardprj/share/meun.jsp
    var curWwwPath = window.document.location.href;
    //获取主机地址之后的目录，如： uimcardprj/share/meun.jsp
    var pathName = window.document.location.pathname;
    var pos = curWwwPath.indexOf(pathName);
    //获取主机地址，如： http://localhost:8083
    var localhostPaht = curWwwPath.substring(0, pos);
    //获取带"/"的项目名，如：/uimcardprj
    var projectName = pathName.substring(0, pathName.substr(1).indexOf('/') + 1);
    return (localhostPaht + projectName);
}

</script>
<style>

    .detailDiv{
        text-align: left;
        padding-left: 35px;
        margin-top: 25px;
    }

    .endHeader{
        width: 100%;
        height: 35px;
        text-align: left;
        line-height: 35px;
        background-color: #CDE3F1;
        font-size: 14px;
        border-radius: 5px;
        padding-left: 5px!important;
    }

    .reviewEnd_Suggest{
        width: 75%;
        height: 75px;
        border-radius: 3px;
        border-color: #f2f2f2;
        font-size: 12px;
        color:black;
    }

/*指标表个样式*/
.xmk-panel-title{
	background: #244d81;
    color: white;
    height: 50px;
    line-height: 50px;
    font-size: 20px;
    display: block;

    text-align: center;
}
.xmk-table{
	width: 100%;
	border: none;
	border-collapse: collapse;
	text-align: center;
}
.xmk-table{
	width: 100%;
	border: none;
	border-collapse: collapse;
	text-align: center;
}
.xmk-table a{
	text-decoration: none;
}
.xmk-table a:visited{
	color: #000;
}
.xmk-table td{
	border: solid #D6DED3 1px;
	height: 36px;
	padding: 0 10px;
}
.xmk-table td {
	border: solid #797979 1px;
	line-height: 30px;
	padding:3px 10px;
}
.xmk-table thead td {
	background-color: #fff;
	font-size: 18px;
	line-height: 60px;
}
.xmk-table thead{
	background-color: #F1F9EC;

}
.xmk-table tfoot td {
	background-color: #fff;
	font-size: 18px;
	line-height: 60px;
}
.xmk-table td input {
	border: none;
	width: 100%;
	height: 100%;
	text-align: center;
	outline:medium;
}
.xmk-table td input:focus {
	outline: none;
	box-shadow: none;
}

</style>


<div align="center">
	<span class="xmk-panel-title">
		广东科学技术职业学院立项建设评审指标体系
	</span>
	<table class="xmk-table" id="mytable" border="1px">
		<thead>
			<tr>
				<!--<td style="width: 15%;min-width: 150px;" id="first_norm_title"> 评审内容 </td>
				<td style="width: 55%;min-width: 450px;" id="second_norm_title"> 内容分类及标准 </td>
				<td style="width: 15%;min-width: 100px;" id="natural_title"> 赋分范围 </td>
				<td style="width: 15%;min-width: 100px;" id="option_title"> 分项分值 </td>-->
				<td style="width: 15%;min-width: 150px;" id="first_norm_title">一级指标</td>
				<td style="width: 15%;min-width: 150px;" id="fenzhi_score">指标分数</td>
				<td style="width: 40%;min-width: 100px;" id="second_norm_title">二级指标内容</td>
				<td style="width: 15%;min-width: 100px;" id="natural_title">赋分范围</td>
				<td style="width: 15%;min-width: 100px;" id="option_title">分项分值</td>
			</tr>
		</thead>
		<tbody>
			<!-- <tr>
			<td rowspan="2">基地类型（10分）</td>
			<td>品牌专业等重大专业建设项目以及省级实训基地、省公共实训中心、重点实验室等重点建设项目</td>
			<td>6-10</td>
			<td rowspan="2"><input type="text" placeholder="填写分数"></input></td>
			</tr>
			<tr>
			<td>品牌专业等重大专业建设项目以及省级实训基地、省公共实训中心、重点实验室等重点建设项目</td>
			<td>6-10</td>
			</tr> -->
		</tbody>
		<tfoot>
			<tr class="total_score">
				<td colspan="4" id="all_score">总计得分</td>
				<!--<td colspan="2">100</td>-->
				<td id="totalscore"></td>
			</tr>
            <!--<tr class="pinshen_tip">
                <td colspan="5" align="left">评审意见:</td>
            </tr>
            <tr class="pinshen_tip">
                <td colspan="5"><textarea style="width: 100%" name="review_opinion" rows="5" placeholder="" readonly id="review_opinion"></textarea></td>
            </tr>-->
		</tfoot>
	</table>

    <div class="projectStartDiv" style="margin-bottom: 80px;display: none">
        <div class="endHeader">评审意见</div>
        <div class="detailDiv" style="display: flex;align-items: start">
            <span>评审意见：</span>
            <textarea  name="review_opinion" rows="5" placeholder="请填写你对本次评审的意见..." id="review_opinion" class="reviewEnd_Suggest"></textarea>
        </div>
    </div>

    <div class="projectEndDiv" style="margin-bottom: 80px;display: none">
        <div class="endHeader">评审意见</div>
        <div class="detailDiv">
            <span>等级建议：</span>
            <label style="margin-left: 15px;"><input type="radio" name="level_suggest" id="level_suggest1" value="1">优秀</label>
            <label style="margin-left: 30px;"><input type="radio" name="level_suggest" id="level_suggest2" value="2">良好</label>
            <label style="margin-left: 30px;"><input type="radio" name="level_suggest" id="level_suggest3" value="3">合格</label>
            <label style="margin-left: 30px;"><input type="radio" name="level_suggest" id="level_suggest4" value="4">不合格</label>
            <span style="margin-left: 32px;">*对成果质量的综合评价和总体意见</span>
        </div>
        <div class="detailDiv">
            <span>出版建议：</span>
            <label style="margin-left: 15px;"><input type="radio" name="publish_suggest" id="publish_suggest1" value="1" >值得出版</label>
            <label style="margin-left: 7px;"><input type="radio" name="publish_suggest" id="publish_suggest2" value="2">可出版也可不出版</label>
            <label style="margin-left: 36px;"><input type="radio" name="publish_suggest" id="publish_suggest3" value="3">不必出版</label>
            <span style="margin-left: 20px;">*对成果质量的综合评价和总体意见</span>
        </div>
        <div class="detailDiv" style="display: flex;align-items: start">
            <span>评审意见：</span>
            <textarea name="reviewEnd_Suggest" class="reviewEnd_Suggest" placeholder="请输入备注" id="reviewEnd_Suggest"></textarea>
        </div>
    </div>
</div>


