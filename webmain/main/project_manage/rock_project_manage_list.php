<?php
if (!defined('HOST'))
	die('not access');
?>
<script >var lx = ''; //当前项目id
var a = '';//当前tab
$(document).ready(function(){
	{params}
	var atype=params.atype,xmfl=params.xmfl,sbdw=params.sbdw,jjcd=params.jjcd,dt1=params.dt1,xmbh=params.xmbh,xmmc=params.xmmc,fzr=params.fzr;
	if(!xmfl)xmfl='';if(!sbdw)sbdw='';if(!jjcd)jjcd='';if(!dt1)dt1='';
	if(!xmbh)xmbh='';if(!xmmc)xmmc='';if(!fzr)fzr='';
	a = $('#view_{rand}').bootstable({
		tablename:'project_apply',params:{'kzt':'xmk','atype':atype,'xmfl':xmfl,'sbdw':sbdw,'jjcd':jjcd,'dt1':dt1,'xmbh':xmbh,'xmmc':xmmc,'fzr':fzr},
		fanye:true,modenum:'project_apply',modename:'项目申报',
		celleditor:true,storeafteraction:'project_applyafter',modedir:'{mode}:{dir}',
		columns: [{
				text: '项目名称',dataIndex: 'project_name'
			},{
				text:'项目类别',dataIndex:'project_select'
			},{
				text:'申报单位',dataIndex:'deptname'
			},
			{
				text: '项目负责人',dataIndex: 'project_head'
			},
			{
				text: '申报时间',dataIndex: 'project_apply_time',sortable: true
			},
			{
				text: '项目性质',dataIndex: 'project_xingzhi'
			},
			{
				text: '库状态',dataIndex: 'project_ku',sortable: true
			},
//			{
//				text:'流程状态',dataIndex:'process_state'
//			},
			{
				text: '项目总预算(万元)',
				dataIndex: 'project_yushuan',
				sortable: true,
				renderer: function(v, d){
					return (v/10000)==0 ? 0 : v/10000;
				}
			},
			{
				text:'操作',dataIndex:'caoz',width:'180px'
			}],itemdblclick:function(){
				c.view();
			}
	});

	var c = {
		del:function(){
			a.del();
		},
		reload:function(){
			a.reload();
		},
		view:function(){
			var d=a.changedata;
			openxiang('project_apply',d.id);
		},
		exceldown:function(){
			//a.exceldown();
			var das = a._loaddata(1, true);
			//console.log(das);return;
			das.limit = 2000;
			das.execldown 	= 'true';
			das.exceltitle	= jm.encrypt('项目库列表');
			excelfields = ',project_name,project_yushuan,deptname,project_head,project_head_phone,project_z_bumeng,project_apply_time,project_year,project_z_bumeng,project_select,process_state,project_xingzhi,paiming,lunzhengtime,zhuanjiaxiaozu,lunzhengjielun,piwen,jingfeixiangmuchuchu,jingfeibianhao,chuku,beizhu';
			excelheader = ',项目名称,预算（万元）,申报单位,项目负责人,联系电话,主管部门,申报时间,实施年度,业务主管部门,分类,流程状态,项目性质,排名,论证时间,专家小组,论证结论,批文,经费项目出处,经费编号,出库,备注';
			das.excelfields = jm.encrypt(excelfields.substr(1));
			das.excelheader = jm.encrypt(excelheader.substr(1));
			$.ajax({
				url:'index.php?a=publicstore&m=project_manage&d=main&ajaxbool=true&rnd=0.08724030972968988',type:'POST',data:das,dataType:'json',
				success:function(a1){
					js.msg('success', '处理成功，共有记录'+a1.totalCount+'条/导出'+a1.downCount+'条，点我直接<a class="a" href="'+a1.url+'" target="_blank">[下载]</a>', 60);
				},
				error:function(e){
					js.msg('msg','err:'+e.responseText);
				}
			});
		},
		search:function(){
			var time_frame = '';//时间范围
			var yusuan = '';//项目预算(万元)
			if(get('yusuanone_{rand}').value != "" && get('yusuantwo_{rand}').value != ""){
				yusuan = get('yusuanone_{rand}').value+','+get('yusuantwo_{rand}').value
			}
			if(get('dt1_{rand}').value != "" && get('dt2_{rand}').value != ""){
				time_frame = get('dt1_{rand}').value+','+get('dt2_{rand}').value
			}
			a.setparams({
				modeid:get('shengbao_{rand}').value,
				time_frame: time_frame,
				xmfl:get('project_select_{rand}').value,
				sbdw:get('dept_{rand}').value,
//				zt:get('status_{rand}').value,
//				jjcd:get('exigence_status_{rand}').value,
				xmbh:get('project_number_{rand}').value,
				xmmc:get('project_name_{rand}').value,
				fzr:get('project_head_{rand}').value,
				xmys:yusuan,
			},true);
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
//		//紧急程度
//		$.each(ds.jjcdarr, function(k,v) {
//			$("#exigence_status_{rand}").append("<option value='"+v.name+"'>"+v.name+"</option>");
//		});
	},'post,json');
});

/**
 * 更改项目库状态
 */
function edit_status(mid,mtype,project_name){
	projectmanage=a;
	addtabs({num:'edit_status_'+mid,url:'main,project_manage,status_edit,mid='+mid+',mtype='+mtype,icons:'icon-bookmark-empty',name:'更改['+project_name+']状态'});
}


/**
 * 查看项目
 */
function check_project(num,mid, project_name){
	addtabs({num:'check_project_apply_'+mid,url:getRootPath()+'/task.php?a=p&num='+num+'&mid='+mid+'&btnstyle=1',icons:'icon-bookmark-empty',name:'['+project_name+']详情'});
}

/**
 * 编辑项目
 */
function edit_project(num,mid, project_name){
	projectapplylist = a;
	addtabs({num:'edit_project',url:getRootPath()+'/index.php?a=lu&m=input&d=flow&mid='+mid+'&num='+num,icons:'icon-bookmark-empty',name:'['+project_name+']编辑'});
}

/**
 * 管理项目库信息
 */
function manage(mtype,mid, project_name){
	projectapplylist = a;
	addtabs({num:'manage_project_'+mid,url:'main,project_manage,msg_edit,mid='+mid+',mtype='+mtype,icons:'icon-bookmark-empty',name:'['+project_name+']信息管理'});
}


/**
 * 录入非库项目
 */
function inputfk(){
	projectmanage=a;
	addtabs({num:'inputfk',url:'main,project_manage,fk',icons:'icon-bookmark-empty',name:'[录入非库项目'});
}

/**
 * 删除项目
 */
function del(el){
	$(el).parent('td').parent('tr').trigger("click");
	a.del();
}

/**
 * 文本框限制只能输入数字
 */
function keyPress() {    
    var keyCode = event.keyCode;    
    if ((keyCode >= 48 && keyCode <= 57))    {    
        event.returnValue = true;    
    }else{    
        event.returnValue = false;    
    }    
 } 
</script>
<div>

	
	

<style type="text/css">
	.selSearch{
		 padding: 0px 0; 
	}
	.serachPanel{
		display: block;
   		padding-left: 1%;
   		min-width: 700px;
	}
	.serachPanel .searchAc1{
		display: inline-block;
		/*width: 16%;
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
				<li ><span class="reviewContent stateContent">项目类别</span></li>

				<li>
					<select id="project_select_{rand}" name="project_select" class="selSearch selAuditState">
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
						<option value="">请选择</option>
					</select>
				</li>
			</ul>
		</div>
		<div class="searchAc1">
			<ul>
				<li><span class="reviewContent stateContent" style="letter-spacing: 0.5em;
margin-right: -0.5em">负责人</span></li>
				<li><input type="text" id="project_head_{rand}" name="project_head" class="form-control txtPanel"></li>
			</ul>
		</div>
		<div class="searchAc1" style="display: none;">
			<ul>
				<li><span class="reviewContent stateContent">业务主管部门</span></li>
				<li><select name="" class="selSearch selBusinessDepartment">
							<option value="">请选择</option>
							<option value="1">1</option>
							<option value="2">2</option>
							<option value="3">3</option>
						</select></li>
			</ul>
		</div>
		
		
		
		<!--<div class="searchAc1">
			<ul>
				<li><span class="reviewContent stateContent">审核状态</span></li>
				<li><select id="status_{rand}" name="status" class="selSearch selAuditStatus">
								<option value="">请选择</option>
								<option value="1">待审核</option>
								<option value="2">已完成</option>
							</select></li>
			</ul>
		</div>-->
		
		<!--<div class="searchAc1">
			<ul>
				<li><span class="reviewContent stateContent">紧急程度</span></li>
				<li><select id="exigence_status_{rand}" name="exigence_status" class="selSearch selEmergencyDegree">
								<option value="">请选择</option>
							</select></li>
			</ul>
		</div>-->
		<div class="searchAc1">
			<ul>
				<li><span class="reviewContent stateContent">项目编号</span></li>
				<li><input type="text" id="project_number_{rand}" name="project_number" class="form-control txtPanel"></li>
			</ul>
		</div>
		<div class="searchAc1">
			<ul>
				<li><span class="reviewContent stateContent">项目名称</span></li>
				<li><input type="text" id="project_name_{rand}" name="project_name" class="form-control txtPanel"></li>
			</ul>
		</div>
		<div class="searchAc1">
			<ul>
				<li><span class="reviewContent stateContent">项目总预算（万元）</span></li>
				<li>
					<div class="searchAc1">
						<ul>
							<li>
								<div class="input-group txtPanel">
								    <div class="input-group-addon">¥</div>
								    <input type="text" class="form-control" id="yusuanone_{rand}" onpaste="return false;"  onkeypress="keyPress()">
							    </div>
							</li>
							<li><span>-</span></li>
							<li>
								<div class="input-group txtPanel">
								    <div class="input-group-addon">¥</div>
								    <input type="text" class="form-control" id="yusuantwo_{rand}" onpaste="return false;"  onkeypress="keyPress()">
							    </div>
							</li>
						</ul>
					</div>
				</li>
			</ul>
		</div>
		<div class="searchAc1" style="/*width: 40%;*/min-width: 440px;">
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
		<div class="searchAc1" >
			<ul>
				<li><input class="btn_  marH1" type="button" click="search" value="查询" /></li>
				<!--<li><input class="btn_" type="button" onclick="resetAllInputSel(this)" value="重置" /></li>-->
				<li><button click="exceldown" class="btn_ btn_output bgGray marRig10">导出</button></li>
				
			</ul>
		</div>
		
	</div>
</section>
<!--<div class="btnPanel" style="text-align: right;margin-bottom: 1%;">
	<button click="exceldown" class="btn_ btn_output marRig10">
					导出
	</button><button class="btn_ btn_reset" onclick="inputfk()">
	录入非库项目
	</button>
</div>	-->
	
	<table width="100%">
		
	</table>
</div>
<!--<div class="blank10"></div>-->
<div id="view_{rand}"></div>
