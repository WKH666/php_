<?php if(!defined('HOST'))die('not access');?>
<script >
var projectapplylist = '';
var a = '';
$(document).ready(function(){
	{params}
	var atype=params.atype,zt=params.zt;
	if(!zt)zt='';
	a = $('#view_{rand}').bootstable({
        url:js.getajaxurl('selectbaseproject','statistics','main',{'atype':atype,'zt':zt}),fanye:true,modename:'项目基本信息查询',
		//tablename:'project_apply',params:{'atype':atype,'zt':zt},fanye:true,modenum:'project_apply',modename:'项目信息',
		//celleditor:true,storeafteraction:'project_applyafter',modedir:'{mode}:{dir}',
		columns:[{
            text:'项目编号',dataIndex:'project_number'
        },{
			text:'项目名称',dataIndex:'project_name'
		},{
			text:'项目类别',dataIndex:'project_select'
		},{
			text:'申报单位',dataIndex:'deptname'
		},{
			text:'项目负责人',dataIndex:'project_head'
		},{
			text:'申报时间',dataIndex:'project_apply_time',sortable:true
		},{
			text:'库状态',dataIndex:'project_ku',renderer: function(v, d){
				if(d.isturn==0){
					return '草稿状态';
				}else{
				    return v;
				}
			}
		},{
			text:'操作',dataIndex:'caoz',width:'80px',renderer: function(v, d){
                return '<a onclick="check_project(\''+d.num+'\','+d.id+',\''+d.project_name+'\')">查看</a>';
            }
		}],
		/*双击事件*/
//		itemdblclick:function(){
//			c.view();
//		},
//		itemclick:function(){
//			btn(false);
//		},
//		beforeload:function(){
//			btn(true);
//		}
		load:function(){
			projectapplylist = a;
		}
	});
//详情专用
//	function btn(bo){
//		get('xiang_{rand}').disabled = bo;
//	}
	var c = {
		del:function(){
			a.del();
		},
		reload:function(){
			a.reload();
		},
		view:function(){
			var d=a.changedata;
			//check_project(d.id,d.project_name);
			openxiang('project_apply',d.id);
		},
		search:function(){
			var time_frame = '';//时间范围
			if(get('dt1_{rand}').value != "" && get('dt2_{rand}').value != ""){
				time_frame = get('dt1_{rand}').value+','+get('dt2_{rand}').value
			}
			a.setparams({
				modeid:get('shengbao_{rand}').value,
				xmmc:get('project_name_{rand}').value,
				sbdw:get('dept_{rand}').value,
				xmbh:get('project_number_{rand}').value,
				xmfl:get('project_select_{rand}').value,
//				zt:get('state_{rand}').value,
				time_frame: time_frame,
			},true);
		},
		daochu:function(){
			a.exceldown(nowtabs.name);
		},
		clickwin:function(o1,lx){
			var id=0;
			if(lx==1)id=a.changeid;
			openinput('项目申报', 'project_apply',id);
		},
		clickdt:function(o1, lx){
			$(o1).rockdatepicker({initshow:true,view:'date',inputid:'dt'+lx+'_{rand}'});
		}
	};
	js.initbtn(c);
	
	if(atype=='wwc'){
		$('#wense_{rand}').remove();
		$('#btngroup{rand}').hide();
	}
	if(atype!='my' && atype!='wcj')$('#wense_{rand}').remove();
});

/**
 * 查看项目
 */
function check_project(num,mid, project_name){
	addtabs({num:'check_'+num+'_{rand}'+mid,url:getRootPath()+'/task.php?a=p&num='+num+'&mid='+mid+'&btnstyle=1',icons:'icon-bookmark-empty',name:'['+project_name+']详情'});
}

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




</script>

<style type="text/css">
.serachPanel{
		display: block;
   		padding-left: 1%;
   		min-width: 700px;
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
	    /*padding: 0% 1%;*/
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
		/*margin: 1% 0%;*/
	}	
	.searchAc1  .stateContent{
		display: inline-block;
  		/*width: 90px;
  		text-indent: 20%;*/
	}
	
	

	/*流程*/
	.processDe{
	    background-color: #419af1;
	    color: #fff;
	    display: inline-block;
	    font-size: 20px;
	    padding: 3% 0%;
	    width: 12%;
	    text-align: center;
	    border-radius: 5px;
	    cursor: pointer;
	}
	.bgG{
		background-color: #848484 !important;
	}
	.processImg{
	    color: #fff;
	    display: inline-block;
	    font-size: 20px;
	    padding: 2% 4%;
	    width: 10%;
	    text-align: center;
	}
	.processImg img{
	    text-align: center;
		width: auto;
		max-width: 100%;
	}
	#layerhtml{
		font-size: 24px;
		display: table-cell;
		vertical-align: middle;
	}
	.layui-layer-content{
	    display: table;
	    width: 100%;
	    padding: 0% 5%;
	}
	a[name="shenbao"]{
	    color: #428bca !important;
	    cursor: pointer;
	}
	
	/*返回上一级a标签的样式*/
	.callbackone{
		position: absolute;
	    display: block;
	    right: 5.8%;
	    bottom: 5%;
	    font-size: 15px;
	}
</style>
<section class="serachPanel selBackGround">
	<div class="searchAPanel">
		<div class="searchAc1">
			<ul>
				<li ><span class="reviewContent stateContent">申报类型</span></li>
				<li>
					<select id="shengbao_{rand}" name="modeid" class="selSearch selAuditState">
						<option value="">请选择</option>
						<option value="1">普通类申报书</option>
						<option value="2">实训类申报书</option>
					</select>
				</li>
			</ul>
		</div>
		<div class="searchAc1">
			<ul>
				<li ><span class="reviewContent stateContent">项目类型</span></li>
				<li>
					<select id="project_select_{rand}" name="project_select" class="selSearch selAuditState">
						<option value="">请选择</option>
					</select>
				</li>
			</ul>
		</div>
		<div class="searchAc1">
			<ul>
				<li ><span class="reviewContent stateContent">项目名称</span></li>
				<li><input type="text" id="project_name_{rand}" name="project_name" class="form-control txtPanel"></li>
			</ul>
		</div>
		<div class="searchAc1">
			<ul>
				<li><span class="reviewContent stateContent">申报单位</span></li>
				<li>
					<select id="dept_{rand}" name="deptaname" class="selSearch selAuditState">
						<option value="">请选择</option>
					</select>
				</li>
			</ul>
		</div>
		<div class="searchAc1">
			<ul>
				<li><span class="reviewContent stateContent">项目编号</span></li>
				<li><input type="text" id="project_number_{rand}" name="project_number" class="form-control txtPanel"></li>
			</ul>
		</div>
		<!--<div class="searchAc1">
			<ul>
				<li><span class="reviewContent stateContent">审核状态</span></li>
				<li><select name="selChange" id="state_{rand}" change="selChange" class="selSearch selEmergencyDegree">
								<option  value="">	全部状态</option>
								<option  value="0">	待审核</option>
								<option  value="1">	已审核</option>
							    <option  value="2">  未通过</option>
							</select></li>
			</ul>
		</div>-->
		<div class="searchAc1" style="/*width: 33%;*/min-width: 440px;">
			<ul>
				<li><span class="reviewContent stateContent">申报时间</span></li>
				<li >
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
				<li><button class="btn_ btn_search" click="search" type="button">查询</button></li>
				<!--<li><input class="btn_" type="button" onclick="resetAllInputSel(this)" value="重置" /></li>-->
<!--				<li><button class="btn_ btn_search bgGray " click="daochu,1" type="button">导出</button></li>-->
				
			</ul>
		</div>
		
	
						
		
	</div>
</section>
<!--<div class="btnPanel" style="text-align: right;margin-bottom: 1%;">
	<button class="btn_ btn_search" click="daochu,1" type="button">导出</button>
</div>	-->

<!--<div class="blank10"></div>-->
<div id="view_{rand}"></div>
