<?php
if (!defined('HOST'))
	die('not access');
?>
<script >$(document).ready(function() {
	{params}
	var atype = params.atype,
		zt = params.zt,
		bdt = params.bdt;
	if(!zt) zt = '';
	
	
	/**
	 * 获取查询条件
	 */
	js.ajax(js.getajaxurl('getsreachcondition','project_manage','main'),{},function(ds){
		//项目分类
		$.each(ds.xmflarr, function(k,v) {
			$("#project_select_{rand}").append("<option value='"+v.name+"'>"+v.name+"</option>");
		});
		//申报单位
		$.each(ds.sbdwarr, function(k,v) {
			$("#dept_{rand}").append("<option value='"+v.name+"'>"+v.name+"</option>");
		});
		//项目性质
		$.each(ds.kxzarr, function(k,v) {
			$("#project_xingzhi_{rand}").append("<option value='"+v.name+"'>"+v.name+"</option>");
		});
		//所在库
		$.each(ds.szkarr, function(k,v) {
			$("#porject_ku_{rand}").append("<option rdata='"+v.id+"' value='"+v.name+"'>"+v.name+"</option>");
		});
	},'post,json');
	
//	/**
//	 * 所在库选择改变值的事件
//	 */
//	$("#porject_ku_{rand}").change(function(){
//		var project_ku_id = $(this).find("option:selected").attr("rdata");
//		//console.log(project_ku_id);
//		if(project_ku_id=="all"){
//			$("#process_state_{rand}").addClass('selSearchBgeee');
//			$("#process_state_{rand}").attr("disabled","true");
//		}else{
//			//根据所在库获取进程状态
//			js.ajax(js.getajaxurl('getprocessstate','project_manage','main'),{library_state_id:project_ku_id},function(ds){
//				//所在库
//				var html = '<option value="all">全部</option>';
//				$.each(ds, function(k,v) {
//					html += "<option value='"+v.name+"'>"+v.name+"</option>";
//				});
//				$("#process_state_{rand}").html(html);
//				$("#process_state_{rand}").removeClass('selSearchBgeee');
//				$("#process_state_{rand}").removeAttr("disabled");
//			},'post,json');
//		}
//	});
	
	var a = $('#view_{rand}').bootstable({
		url:js.getajaxurl('getcensus','statistics','main',{'atype': atype,'zt': zt,'bdt': bdt}),fanye:true,modename:'查询统计',
//		tablename:'statistics',params:{'atype': atype,'zt': zt,'bdt': bdt},
//		fanye:true,modenum:'statistics',modename:'查询统计',
//		celleditor:true,modedir:'{mode}:{dir}',
		columns: [{
			text: '项目类别',
			dataIndex: 'project_select',
		}, {
			text: '申报单位',
			dataIndex: 'deptname',
			sortable: true
		}, {
			text: '项目年度',
			dataIndex: 'project_year',
			sortable: true
		}, {
			text: '申报时间',
			dataIndex: 'project_apply_time',
			sortable: true
		}, {
			text: '项目性质',
			dataIndex: 'project_xingzhi',
			sortable: true
		}, {
			text: '库状态',
			dataIndex: 'project_ku',
			sortable: true
		},
//		 {
//			text: '进程状态',
//			dataIndex: 'process_state',
//			sortable: true
//		},
		 {
			text: '绩效评价',
			dataIndex: 'evaluation',
			sortable: true
		}, {
			text: '项目数',
			dataIndex: 'project_count',
			sortable: true
		}
		, {
			text: '项目总预算(万元)',
			dataIndex: 'project_yushuan',
			sortable: true,
			renderer: function(v, d){
				return (v/10000)==0 ? 0 : v/10000;
			}
		}],
		//		itemdblclick:function(){
		//			c.view();
		//		},

	});
	
	//子表
	
	//单位表
	var a_deptname = $('#deptname_view_{rand}').bootstable({
		url:js.getajaxurl('getcensus','statistics','main',{'atype': atype,'zt': zt,'bdt': bdt,'childgroupwhere':1}),isshownumber:false,
		columns: [{
			text: '申报单位',
			dataIndex: 'deptname',
		},{
			text: '项目数',
			dataIndex: 'project_count'
		},{
			text: '项目总预算(万元)',
			dataIndex: 'project_yushuan',
			sortable: true,
			renderer: function(v, d){
				return (v/10000)==0 ? 0 : v/10000;
			}
		}],
	});
//	//进程状态表
//	var a_process_state = $('#process_state_view_{rand}').bootstable({
//		url:js.getajaxurl('getcensus','statistics','main',{'atype': atype,'zt': zt,'bdt': bdt,'childgroupwhere':2}),isshownumber:false,
//		columns: [{
//			text: '进程状态',
//			dataIndex: 'process_state',
//		},{
//			text: '项目数',
//			dataIndex: 'project_count'
//		}],
//	});
	//项目性质表
	var a_project_xingzhi = $('#project_xingzhi_view_{rand}').bootstable({
		url:js.getajaxurl('getcensus','statistics','main',{'atype': atype,'zt': zt,'bdt': bdt,'childgroupwhere':3}),isshownumber:false,
		columns: [{
			text: '项目性质',
			dataIndex: 'project_xingzhi',
		},{
			text: '项目数',
			dataIndex: 'project_count'
		},{
			text: '项目总预算(万元)',
			dataIndex: 'project_yushuan',
			sortable: true,
			renderer: function(v, d){
				return (v/10000)==0 ? 0 : v/10000;
			}
		}],
	});

	var c = {
		del: function() {
			a.del();
		},
		reload: function() {
			a.reload();
		},
		search: function() {
			var time_frame = '';//时间范围
			if(get('dt1_{rand}').value != "" && get('dt2_{rand}').value != ""){
				time_frame = get('dt1_{rand}').value+','+get('dt2_{rand}').value
			}
			a.setparams({
				time_frame: time_frame,
				dept_name: get('dept_{rand}').value,
				project_select: get('project_select_{rand}').value,
				project_year: get('project_year_{rand}').value=='' ? '' : get('project_year_{rand}').value,
				project_ku: get('porject_ku_{rand}').value=='all' ? '' : get('porject_ku_{rand}').value,
				project_xingzhi: get('project_xingzhi_{rand}').value,
//				process_state: get('process_state_{rand}').value=='all' ? '' : get('process_state_{rand}').value,
				achievements: get('achievements_{rand}').value
			}, true);
			a_deptname.setparams({
				time_frame: time_frame,
				dept_name: get('dept_{rand}').value,
				project_select: get('project_select_{rand}').value,
				project_year: get('project_year_{rand}').value=='' ? '' : get('project_year_{rand}').value,
				project_ku: get('porject_ku_{rand}').value=='all' ? '' : get('porject_ku_{rand}').value,
				project_xingzhi: get('project_xingzhi_{rand}').value,
//				process_state: get('process_state_{rand}').value=='all' ? '' : get('process_state_{rand}').value,
				achievements: get('achievements_{rand}').value
			}, true);
//			a_process_state.setparams({
//				time_frame: time_frame,
//				dept_name: get('dept_{rand}').value,
//				project_select: get('project_select_{rand}').value,
//				project_year: get('project_year_{rand}').value=='' ? '' : get('project_year_{rand}').value,
//				project_ku: get('porject_ku_{rand}').value=='all' ? '' : get('porject_ku_{rand}').value,
//				project_xingzhi: get('project_xingzhi_{rand}').value,
//				process_state: get('process_state_{rand}').value=='all' ? '' : get('process_state_{rand}').value,
//				achievements: get('achievements_{rand}').value
//			}, true);
			a_project_xingzhi.setparams({
				time_frame: time_frame,
				dept_name: get('dept_{rand}').value,
				project_select: get('project_select_{rand}').value,
				project_year: get('project_year_{rand}').value=='' ? '' : get('project_year_{rand}').value,
				project_ku: get('porject_ku_{rand}').value=='all' ? '' : get('porject_ku_{rand}').value,
				project_xingzhi: get('project_xingzhi_{rand}').value,
//				process_state: get('process_state_{rand}').value=='all' ? '' : get('process_state_{rand}').value,
				achievements: get('achievements_{rand}').value
			}, true);
		},
		daochu: function() {
			a.exceldown(nowtabs.name);
		},
		clickdt: function(o1, lx) {
			if(~~(lx)==3){
				$(o1).rockdatepicker({
					initshow: true,
					view: 'year',
					inputid: 'project_year_{rand}'
				});
			}else{
				$(o1).rockdatepicker({
					initshow: true,
					view: 'date',
					inputid: 'dt' + lx + '_{rand}'
				});
			}
		},
		betweendt: function(o1, lx) {
			a.setparams({bdt: lx});
			a_deptname.setparams({bdt: lx});
//			a_process_state.setparams({bdt: lx});
			a_project_xingzhi.setparams({bdt: lx});
			resetAllInputSel(0);
			this.search();
		}
	};
	js.initbtn(c);

	if(atype == 'wwc') {
		$('#wense_{rand}').remove();
		$('#btngroup{rand}').hide();
	}
	if(atype != 'my' && atype != 'wcj') $('#wense_{rand}').remove();
	
});

</script>


<!--
<style type="text/css">.queryReview {
	display: block;
}


/*按钮 start*/

.review .btnPanel {
	margin: 1.5% 0% 2% 0%;
}

.review .btnPanel .panelSearch {
	text-align: right;
}

.review .btnPanel .panelOutput {
	text-align: center;
}


/*按钮 end*/


/*查询条件 start*/

.review {}

.review .reviewInput {
	text-align: left;
	padding-left: 8%;
	margin: 3% 0%;
}


/*选择文字*/

.review .reviewInput .stateContent {
	display: inline-block;
	width: 95px;
	text-align: right;
}


/*选择框*/

.review .reviewInput .dropPanel {
	display: inline-block;
}

.review .reviewInput .dropPanel button {
	width: 170px;
}

.retimeker {
	margin-top: 2%;
	margin-right: 2%;
	float: left;
}

.mybtn {
	text-align: center;
}


/*查询条件 end */</style>
<div class="selCondition">
	<section class="queryReview">

		<div class="container review">
			<div class="row">
				<table width="91.5%" style="margin:1% auto; ">
					<tr>
						<td width="90px"><span>按时间段统计</span></td>
						<td width="40%">
							<div class="timepicker">
								<div style="display: inline-block;">

									<div  class="input-group txtPanel">
										<input placeholder="开始" readonly class="form-control" id="dt1_{rand}" >
										<span class="input-group-btn">
										<button class="btn btn-default" click="clickdt,1" type="button">
										<i class="icon-calendar"></i>
										</button> </span>
									</div>
								</div>
								<div style="display: inline-block;">
									<div  class="input-group txtPanel">
										<input placeholder="结束" readonly class="form-control" id="dt2_{rand}" >
										<span class="input-group-btn">
										<button class="btn btn-default" click="clickdt,2" type="button">
										<i class="icon-calendar"></i>
										</button> </span>
									</div>
								</div>

						</td>

						<td  style="padding-left:10px;">
							<a click="betweendt,1" style="padding-left:10px;">
								最近一个月
							</a>
							<a click="betweendt,3" style="padding-left:10px;">
								最近三个月
							</a>
							<a click="betweendt,6" style="padding-left:10px;">
								最近半年
							</a>
							<a click="betweendt,12" style="padding-left:10px;">
								最近一年
							</a>
						</td>
					</tr>
				</table>
				<div class="col-lg-4 col-md-4 col-sm-4">
					<div class="reportingUnit reviewInput">
						<span class="reviewContent stateContent">申报单位</span>
						<div class="dropdown dropPanel">
							<select id="dept_{rand}" name="deptaname" class="selSearch selAuditState">
								<option value="">全部</option>
							</select>
						</div>
					</div>
				</div>
				<div class="col-lg-4 col-md-4 col-sm-4">
					<div class="reviewCategory reviewInput">
						<span class="reviewContent stateContent">项目类别</span>
						<div class="dropdown dropPanel">
							<select id="project_select_{rand}" name="project_select" class="selSearch selAuditState">
								<option value="">全部</option>
							</select>
						</div>
					</div>
				</div>
				<div class="col-lg-4 col-md-4 col-sm-4">
					<div class="reviewAuditStatus reviewInput">
						<span class="reviewContent stateContent">项目性质</span>
						<div class="dropdown dropPanel">
							<select id="project_xingzhi_{rand}" name="project_xingzhi" class="selSearch selAuditStatus">
								<option value="">全部</option>
							</select>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-4 col-md-4 col-sm-4">
					<div class="emergencyDegree reviewInput">
						<span class="reviewContent stateContent">项目年度</span>
						<div class="dropdown dropPanel">
							<span class="divinput"><input onclick="js.datechange(this,'year')" value="" class="inputs datesss" inputtype="year" readonly="" id="project_year_{rand}"></span>
						</div>
					</div>
				</div>
				<div class="col-lg-4 col-md-4 col-sm-4">
					<div class="reviewAuditStatus reviewInput">
						<span class="reviewContent stateContent">所在库</span>
						<div class="dropdown dropPanel">
							<select id="porject_ku_{rand}" name="porject_ku" class="selSearch selAuditStatus">
								<option rdata="all" value="all">全部</option>
							</select>
						</div>
					</div>
				</div>
				<div class="col-lg-4 col-md-4 col-sm-4">
					<div class="emergencyDegree reviewInput">
						<span class="reviewContent stateContent">进程状态</span>
						<div class="dropdown dropPanel">
							<select disabled="true"  id="process_state_{rand}" name="process_state" class=" selSearch selSearchBgeee selEmergencyDegree">
								<option value="">全部</option>
							</select>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-4 col-md-4 col-sm-4">
					<div class="emergencyDegree reviewInput">
						<span class="reviewContent stateContent">绩效评价</span>
						<div class="dropdown dropPanel">
							<select id="achievements_{rand}" name="achievements" class="selSearch selEmergencyDegree">
								<option value="">全部</option>
								<option value="1">已评价</option>
								<option value="0">未评价</option>
							</select>
						</div>
					</div>
				</div>
			</div>

			<div class="row btnPanel">
				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 mybtn">
					<button class="btn_ btn_search" click="search">
					统计数据
					</button>
				</div>
				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 mybtn">
					<button class="btn_ btn_reset">
					重置
					</button>
				</div>
				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 mybtn">
					<button click="exceldown" class="btn_ btn_output">
					导出
					</button>
				</div>
			</div>
		</div>
	</section>
</div>-->




<style type="text/css">
	.serachPanel{
		display: block;
   		padding-left: 1%;
	}
	.serachPanel .searchAc1{
		display: inline-block;
		/*width: 17%;
		min-width: 271px;*/
		
	}
	.serachPanel .searchAc1 ul li{
	    float: left;
	    height: 40px;
	    line-height: 33px;
	    padding-right: 10px;
	}

	.serachPanel .searchAPanel{
		position: relative;
	}
	.searchAc{
		text-align: center;
		margin-top: 5px;
		margin-bottom: 10px;
		
	}
	.searchAc a{
		font-size: 12px;
		color: #555555;
	}
	.selSearch {
		height: 32px;
    	line-height: 32px;
	}
	.form-control{
		height: 32px;
    	line-height: 32px;
	}
	.btn-default{
		padding: 5px 12px;
	}
	.tTabc ul li{
		width: 100%;
		text-align: center;
		    border: 1px solid #eee;
    padding-bottom: 2%;
    margin-top: 2%;
	}
	.tTabc ul li span{
		display: block;	
		text-align: center;
		font-size: 20px;
		margin: 1% 0%;
	}	
	.searchAc1  .stateContent{
		display: inline-block;
  		/*width: 90px;
  		text-indent: 20%;*/
	}
	
	
</style>
<section class="serachPanel selBackGround">
	<div class="searchAPanel">
		
	
		<div class="searchAc1">
			<ul>
				<li><span class="reviewContent stateContent">申报单位</span></li>
				<li><select id="dept_{rand}" name="deptaname" class="selSearch selAuditState">
								<option value="">全部</option>
							</select></li>
			</ul>
		</div>
		<div class="searchAc1">
			<ul>
				<li><span class="reviewContent stateContent">项目类别</span></li>
				<li><select id="project_select_{rand}" name="project_select" class="selSearch selAuditState">
								<option value="">全部</option>
							</select></li>
			</ul>
		</div>
		<div class="searchAc1">    
			<ul>
				<li><span class="reviewContent stateContent" style="letter-spacing: 0.5em;margin-right: -0.5em;">项目性质</span></li>
				<li><select id="project_xingzhi_{rand}" name="project_xingzhi" class="selSearch selAuditStatus">
								<option value="">全部</option>
							</select></li>
			</ul>
		</div>
		<div class="searchAc1">
			<ul>
				<li>
					<span class="reviewContent stateContent">项目年度</span>
				</li>
				<li>
					<div style="display: inline-block;">

						<div  class="input-group txtPanel">
							<input placeholder="" readonly class="form-control" id="project_year_{rand}" >
							<span class="input-group-btn">
							<button class="btn btn-default" click="clickdt,3" type="button">
							<i class="icon-calendar"></i>
							</button> </span>
						</div>
					</div>
				</li>
			</ul>

		</div>
		
		<div class="searchAc1">
			<ul>
				<li><span class="reviewContent stateContent" style="letter-spacing:0.5em;margin-right:-0.5em;">库状态</span></li>
				<li><select id="porject_ku_{rand}" name="porject_ku" class="selSearch selAuditStatus">
								<option rdata="all" value="all">全部</option>
							</select></li>
			</ul>
		</div>
		
		<!--<div class="searchAc1">
			<ul>
				<li><span class="reviewContent stateContent">进程状态</span></li>
				<li><select disabled="true"  id="process_state_{rand}" name="process_state" class=" selSearch selSearchBgeee selEmergencyDegree">
								<option value="">全部</option>
							</select></li>
			</ul>
		</div>-->
		
		
		<div class="searchAc1">
			<ul>
				<li><span class="reviewContent stateContent">绩效评价</span></li>
				<li><select id="achievements_{rand}" name="achievements" class="selSearch selEmergencyDegree">
								<option value="">全部</option>
								<option value="1">已评价</option>
								<option value="0">未评价</option>
							</select></li>
			</ul>
		</div>
	
		
		<div class="searchAc1" style="/*width: 33%*/;min-width: 440px; ">
			<ul>
				<li><span class="reviewContent stateContent">申报时间</span></li>
				<li>
					<div class="timepicker" style="margin-bottom: 0.7%;">
						<div style="display: inline-block;">
							<div class="input-group txtPanel">
								<input placeholder="开始" readonly class="form-control" id="dt1_{rand}" >
								<span class="input-group-btn">
								<button class="btn btn-default" click="clickdt,1" type="button">
								<i class="icon-calendar"></i>
								</button> </span>
							</div>
						</div>
						<div style="display: inline-block;">
							<div class="input-group txtPanel">
								<input placeholder="结束" readonly class="form-control" id="dt2_{rand}" >
								<span class="input-group-btn">
								<button class="btn btn-default" click="clickdt,2" type="button">
								<i class="icon-calendar"></i>
								</button> </span>
							</div>
						</div>
					</div>
				</li>
			</ul>
			
		</div>
			<div class="searchAc1">
			<ul>
				<li><input class="btn_  marH1" type="button" click="search" value="统计" /></li>
				<!--<li><input class="btn_" type="button" onclick="resetAllInputSel(this)" value="重置" /></li>-->
				<li><button click="daochu" class="btn_ btn_output bgGray">导出</button></li>
				
			</ul>
		</div>
		<div class="searchAc">
			<a click="betweendt,1" style="padding-left:10px;">
				最近一个月
			</a>
			<a click="betweendt,3" style="padding-left:10px;">
				最近三个月
			</a>
			<a click="betweendt,6" style="padding-left:10px;">
				最近半年
			</a>
			<a click="betweendt,12" style="padding-left:10px;">
				最近一年
			</a>
</div>
	
						
		
	</div>
</section>


<!--
<div class="btnPanel" style="text-align: right;margin-bottom: 1%;">
	<button click="exceldown" class="btn_ btn_output marRig10" clickadd="true">
					导出
	</button>
</div>-->


<div id="view_{rand}"></div>
<div class="blank10"></div>

<div class="tTabc">
	<ul>
		<li>
			<span>申报单位统计表</span>
			<div id="deptname_view_{rand}" style="width: 33%;display: inline-block;"></div></li>
		<!--<li>
			<span>进程状态统计表</span>
			<div id="process_state_view_{rand}" style="width: 33%;display: inline-block;"></div></li>-->
		<li>
			<span>项目性质统计表</span>
			<div id="project_xingzhi_view_{rand}" style="width: 33%;display: inline-block;"></div></li>
	</ul>
</div>


