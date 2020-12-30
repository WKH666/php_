<?php if(!defined('HOST'))die('not access');?>
<script >
$(document).ready(function(){
	{params}
	var atype=params.atype,zt=params.zt;
	if(!zt)zt='';
	var a = $('#view_{rand}').bootstable({
		tablename:'project_apply',params:{'atype':atype,'zt':zt},fanye:true,modename:'项目申报',
		celleditor:true,storeafteraction:'project_applyafter',modedir:'{mode}:{dir}',
		columns:[{
			text:'分类',dataIndex:'project_name',align:'left'
		},{
			text:'申报单位',dataIndex:'dept'
		},{
			text:'项目名称',dataIndex:'project_name'
		},{
			text:'负责人',dataIndex:'project_head'
		},{
			text:'申报时间',dataIndex:'applydt',sortable:true
		},{
			text:'申报人',dataIndex:'optname'
		},{
			text:'状态',dataIndex:'statustext'
		},{
			text:'操作',dataIndex:'caozuo'
		}],itemdblclick:function(){
			c.view();
		},
		itemclick:function(){
			btn(false);
		},
		beforeload:function(){
			btn(true);
		}
	});

	function btn(bo){
		get('xiang_{rand}').disabled = bo;
	}
	var c = {
		del:function(){
			a.del();
		},
		reload:function(){
			a.reload();
		},
		view:function(){
			var d=a.changedata;
			openxiangs('项目申报','',d.id);
		},
		search:function(){
			a.setparams({
				key:get('key_{rand}').value,
				dt1:get('dt1_{rand}').value,
			
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
		},
		changlx:function(o1,lx){
			$("button[id^='state{rand}']").removeClass('active');
			$('#state{rand}_'+lx+'').addClass('active');
			a.setparams({zt:lx});
			this.search();
		}
	};
	js.initbtn(c);
	
	if(atype=='wwc'){
		$('#wense_{rand}').remove();
		$('#btngroup{rand}').hide();
	}
	if(atype!='my' && atype!='wcj')$('#wense_{rand}').remove();
});
</script>
<div>
	<table width="100%">
	<tr>
	<td id="wense_{rand}" style="padding-right:10px">
		<button class="btn btn-primary" click="clickwin,0" type="button"><i class="icon-plus"></i> 创建任务</button>
	</td>
	<td  style="padding-left:10px">
		<div style="width:140px"  class="input-group">
			<input placeholder="申请日期" readonly class="form-control" id="dt1_{rand}" >
			<span class="input-group-btn">
				<button class="btn btn-default" click="clickdt,1" type="button"><i class="icon-calendar"></i></button>
			</span>
		</div>
	</td>
	<td style="padding-left:10px">
		<input class="form-control" style="width:200px" id="key_{rand}"   placeholder="项目名称/项目编号/负责人">
	</td>
	<td style="padding-left:10px">
		<button class="btn btn-default" click="search" type="button">搜索</button> 
	</td>
	
	
	<td  width="90%" style="padding-left:10px">
		
		<div class="btn-group" id="btngroup{rand}">
		<button class="btn btn-default active" id="state{rand}_" click="changlx," type="button">全部任务</button>
		<button class="btn btn-default" id="state{rand}_0" click="changlx,0" type="button">已审核</button>
		<button class="btn btn-default" id="state{rand}_1" click="changlx,1" type="button">处理中</button>
		<button class="btn btn-default" id="state{rand}_2" click="changlx,2" type="button">未通过</button>

		</div>	
	</td>

	
	
	<td align="right" nowrap>
		<button class="btn btn-default" id="xiang_{rand}" click="view" disabled type="button">详情</button> &nbsp; 
		<button class="btn btn-default bgGray" click="daochu,1" type="button">导出</button> 
	</td>
	</tr>
	</table>
	
</div>
<div class="blank10"></div>
<div id="view_{rand}"></div>
