<?php if(!defined('HOST'))die('not access');?>
<script >
var lx = '';//当前项目id
var a = '';//表单
$(document).ready(function(){
	{params}
	lx=params.mid;
	if(!lx)lx='';
	a = $('#view_{rand}').bootstable({
		url:js.getajaxurl('appropriation','extrainfo','main',{mid:lx}),fanye:true,storeafteraction:'extrainfoafter',storebeforeaction:'dataauthbefore',
		columns:[{
			text:'项目编号',dataIndex:'project_number'
		},
		{
			text:'项目名称',dataIndex:'project_name'
		},
		{
			text:'项目类别',dataIndex:'project_select'
		},
		{
			text:'申报单位',dataIndex:'deptname'
		},
		{
			text:'项目负责人',dataIndex:'optname'
		},
		{
			text:'拨付时间',dataIndex:'appropriation_time',sortable:true,renderer:function(v,d){
				if(d.is_appropriation==0)return '暂未拨付';
				else return v;
			}
		},{
			text:'操作',dataIndex:'caoz'
		}]
//		隐藏双击事件
//		,itemdblclick:function(){
//			c.view();
//		}
	});

	var c = {
		del:function(){
			a.del();
		},
		reload:function(){
			a.reload();
		},
		search:function(){
			var time_frame = '';//时间范围
			if(get('dt1_{rand}').value != "" && get('dt2_{rand}').value != ""){
				time_frame = get('dt1_{rand}').value+','+get('dt2_{rand}').value
			}
			a.setparams({
				time_frame: time_frame,
				project_select:get('project_select_{rand}').value,
				deptname:get('dept_{rand}').value,
				project_name:get('project_name_{rand}').value,
				project_number:get('project_number_{rand}').value,
				project_head:get('project_head_{rand}').value,
			},true);
		},
		clickdt:function(o1, lx){
			$(o1).rockdatepicker({initshow:true,view:'date',inputid:'dt'+lx+'_{rand}'});
		}
	};
	js.initbtn(c);
	
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
	},'post,json');
});


//录入拨付信息
function appropriation_add(lx,mtype,project_name){
	appropriationlist = a;
	addtabs({num:'appropriation_add'+lx,url:'main,extrainfo,zjbfedit,mid='+lx+',mtype='+mtype+',project_name='+project_name,icons:'icon-bookmark-empty',name:'录入['+project_name+']验收信息'});
}

//查看拨付信息
function appropriation_check(mid,id,project_name){
	var tab_id = 'appropriation-'+id;//这个请求不能直接用1,2,3这样的方式传值，要表名_id
	addtabs({num:'appropriation_file_'+lx,url:'main,archives,files,tab_id='+tab_id+',mid='+mid,icons:'icon-bookmark-empty',name:'['+project_name+']验收信息文件'});
}

//编辑拨付信息
function appropriation_edit(lx,mtype,id,project_name){
	appropriationlist = a;
	addtabs({num:'appropriation_edit'+lx,url:'main,extrainfo,zjbfedit,id='+id+',mtype='+mtype+',mid='+lx+',project_name='+project_name,icons:'icon-bookmark-empty',name:'编辑['+project_name+']验收信息'});
}


</script>
<!--
<div>
	<table width="100%">
	<tr>
		<td align="center" style="width: 33%;">项目类别
			<select id="project_select_{rand}" click="selfl" name="project_select" class="selSearch selAuditState">
				<option value="">-请选择-</option>
			</select>
		</td>
		<td align="center" style="width: 33%;">申报单位
			<select id="dept_{rand}" name="deptaname" class="selSearch selAuditState">
				<option value="">-请选择-</option>
			</select>
		</td>
		<td align="center" style="width: 33%;">
			<div style="width:200px"  class="input-group">
				<input placeholder="拨付时间" readonly class="form-control" id="dt1_{rand}" >
				<span class="input-group-btn">
					<button class="btn btn-default" click="clickdt,1" type="button"><i class="icon-calendar"></i></button>
				</span>
			</div>
		</td>
	</tr>
	<tr>
		<td align="center" style="width: 33%;">项目名称<input class="form-control" style="width:200px" id="project_name_{rand}" placeholder="请输入项目名称"></td>
		<td align="center" style="width: 33%;">项目编号<input class="form-control" style="width:200px" id="project_head_{rand}" placeholder="请输入项目编辑"></td>
		<td align="center" style="width: 33%;">负责人<input class="form-control" style="width:200px" id="project_head_{rand}" placeholder="请输入项目负责人名称"></td>
	</tr>
		<tr align="center" class="">
		<td colspan="3"><div class="btnPanel1"><input class="btn_  marH1" type="button" click="search" value="查询" /><input class="btn_" type="button" onclick="resetAllInputSel(this)" value="重置" /></div></td></tr>
	</table>
	
</div>
-->





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
				<li ><span class="reviewContent stateContent">项目类别</span></li>
				<li>
					<select id="project_select_{rand}" click="selfl" name="project_select" class="selSearch selAuditState">
						<option value="">-请选择-</option>
					</select>
				</li>
			</ul>
		</div>
		<div class="searchAc1">
			<ul>
				<li><span class="reviewContent stateContent">申报单位</span></li>
				<li>
					<select id="dept_{rand}" name="deptaname" class="selSearch selAuditState">
						<option value="">-请选择-</option>
					</select>
				</li>
			</ul>
		</div>
		<div class="searchAc1">
			<ul>
				<li><span class="reviewContent stateContent">项目名称</span></li>
				<li>
					<input class="form-control" style="width:170px" id="project_name_{rand}" placeholder="请输入项目名称">
				</li>
			</ul>
		</div>
		<div class="searchAc1">
			<ul>
				<li><span class="reviewContent stateContent">项目编号</span></li>
				<li>
					<input class="form-control" style="width:170px" id="project_number_{rand}" placeholder="请输入项目编辑">
				</li>
			</ul>
		</div>
		<div class="searchAc1">
			<ul>
				<li><span class="reviewContent stateContent" style="letter-spacing: 0.5em;margin-right: -0.5em;">负责人</span></li>
				<li>
					<input class="form-control" style="width:170px" id="project_head_{rand}" placeholder="请输入项目负责人名称">
				</li>
			</ul>
		</div>
		<div class="searchAc1" style="/*width: 33%;*/min-width: 440px;">
			<ul>
				<li><span class="reviewContent stateContent">拨付日期</span></li>
				<li>
					<div class="timepicker" style="margin-bottom: 0.7%;">
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
					</div>
				</li>
			</ul>
		</div>
		<div class="searchAc1">
			<ul>
				<li><button class="btn_ btn_search" click="search" type="button" >查询</button>
					<!--<input class="btn_  marH1" type="button" click="search" value="查询" />--></li>
				<!--<li><input class="btn_" type="button" onclick="resetAllInputSel(this)" value="重置" /></li>-->
			</ul>
		</div>
		
	
						
		
	</div>
</section>







<!--<div class="blank10"></div>-->
<div id="view_{rand}"></div>
