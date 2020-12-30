<?php if(!defined('HOST'))die('not access');?>
<script >
var lx = '';//当前项目id
$(document).ready(function(){
	{params} 
	lx=params.mid;tab_id=params.tab_id;mtype=params.mtype;
	if(!lx)lx='';if(!tab_id)tab_id='';
	if(params.htype=='sc'){
		$('#sc_{rand}').removeClass('yincang');
	}
	var a = $('#view_{rand}').bootstable({
		url:js.getajaxurl('getthisfiles','archives','main',{mid:lx,tab_id:tab_id,mtype:mtype}),fanye:true,celleditor:true,
		columns:[
//		{
//			text:'档案分类',dataIndex:'filetype',align:'left'
//		},
		{
			text:'文件名称',dataIndex:'filename'
		},
		{
			text:'上传者',dataIndex:'optname',sortable:true
		},
		{
			text:'上传时间',dataIndex:'adddt',sortable:true
		},
		{
			text:'<label><input type="checkbox" onclick="selall(this)">全选</label>',dataIndex:'id',renderer:function(v,d){
				return '<input type="checkbox" name="selfile_{rand}" value="'+v+'" />'; 
			}
		},
		{
			text:'操作',dataIndex:'id',renderer:function(v,d){
				return '<a onclick="downloadone('+v+')">下载</a>';
			}
		}]
//		隐藏双击事件
//		,itemdblclick:function(){
//			c.view();
//		}
	});
	
	_editfacech{rand}angback=function(a,mid,pars2,sid){
		c.savefile(mid, sid);
	};

	var c = {
		del:function(){
			a.del();
		},
		reload:function(){
			a.reload();
		},
//		view:function(){
//			var d=a.changedata;
//			openxiangs('项目申报','project_apply',d.id);
//		},
		search:function(){
			a.setparams({
				file_name:get('file_name_{rand}').value,
			},true);
		},
		uploadfile:function(){
			var na = a.changedata.name;
			js.upload('_editfacech{rand}angback',{'title':'上传文件','params1':params.mid});	
		},
		savefile:function(mid, sid){
			if(sid=='')return;
			js.ajax(js.getajaxurl('savefile','{mode}','{dir}'),{'mid':mid,'sid':sid},function(s){
				a.reload();
			},'get',false, '保存中...,保存成功');
		},
	};
	js.initbtn(c);
});

//全选方法
function selall(el){
	var cboxs = $("input[name='selfile_{rand}']");
	//判断全选按钮是否被选中
	if($(el).is(':checked')){
		$.each(cboxs,function(k,v){
			$(v).prop("checked", "checked");
		});
	}else{
		$.each(cboxs,function(k,v){
			$(v).removeAttr("checked");
		});
	}
}

//单文件下载
function downloadone(file_id){
	location.href = js.getajaxurl('downloadone','archives','main',{ajaxbool:true, file_id:file_id});
}

//多文件下载
function downloadt(){
	if(js.bool)return false;
	var file_id_arr = new Array();
	var cboxs = $("input[name='selfile_{rand}']:checked");
	$.each(cboxs,function(k,v){
		file_id_arr.push($(v).val());
	});
	var file_ids = file_id_arr.join(',');
	if(file_ids == ''){
		js.msg('msg','请选择需要下载的文件');
		return;
	}
	location.href = js.getajaxurl('packAllToZip','archives','main',{ajaxbool:true, mid:lx, file_ids:file_ids});
}


</script>

<style type="text/css">
	.yincang{
		display: none;
	}
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
				<li><span class="reviewContent stateContent">文件名称</span></li>
				<li>
					<input class="form-control" style="width:170px" id="file_name_{rand}" placeholder="文件名称">
				</li>
			</ul>
		</div>
		<div class="searchAc1">
			<ul>
				<li><button class="btn_ btn_search" click="search" type="button">查询</button></li>
				<li><button class="btn_  marH1 yincang" id="sc_{rand}" click="uploadfile" type="button">上传文件</button></li>
				<li><input class="btn_  marH1" type="button" onclick="downloadt()" value="批量下载" /></li>
			</ul>
		</div>
	</div>
</section>
<!--<div>
	<table width="100%">
	<tr>
	<td align="right" style="padding-right:10px;"><button class="btn btn-primary" click="uploadfile" type="button"><i class="icon-upload-alt"></i> 上传文件</button></td>
	<td align="right" style="padding-right:10px;"><input class="btn btn-primary" type="button" onclick="downloadt()" value="批量下载" /></td>
	</tr>
	</table>
</div>-->

<!--<div>
	<table width="100%">
		<tr>
			<td align="right" style="padding-left:10px;padding-bottom: 10px;">
				<button class="btn_  marH1 yincang" id="sc_{rand}" click="uploadfile" type="button">上传文件</button> 
				<input class="btn_  marH1" type="button" onclick="downloadt()" value="批量下载" />
			</td>
		</tr>
	</table>
	
</div>-->
<!--<div class="blank10"></div>-->
<div id="view_{rand}"></div>
