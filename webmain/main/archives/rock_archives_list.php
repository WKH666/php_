<?php if(!defined('HOST'))die('not access');?>
<script >
$(document).ready(function(){
	{params}
	var atype=params.atype,zt=params.zt;
	if(!zt)zt='';
	var a = $('#view_{rand}').bootstable({
		tablename:'project_apply',params:{'atype':atype,'zt':zt},fanye:true,modenum:'project_apply',modename:'项目申报',
		celleditor:true,storeafteraction:'project_applyafter',modedir:'project_apply:main',
		columns:[{
			text:'项目名称',dataIndex:'project_name',width:'30%',
		},{
			text:'分类',dataIndex:'project_select',width:'28%',
		},{
			text:'申报时间',dataIndex:'project_apply_time',sortable:true,width:'25%',
		},{
			text:'操作',dataIndex:'id',renderer:function(v,d){
				return '<a project_name="'+d.project_name+'" onclick="lookfiles(this,'+v+',\''+d.mtype+'\')">查看</a>';
			},width:'200px'
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
//		view:function(){
//			var d=a.changedata;
//			openxiangs('项目申报','project_apply',d.id);
//		},
		search:function(){
			a.setparams({
				xmmc:get('project_name_{rand}').value,
			},true);
		}
	};
	js.initbtn(c);
	
	if(atype=='wwc'){
		$('#wense_{rand}').remove();
		$('#btngroup{rand}').hide();
	}
	if(atype!='my' && atype!='wcj')$('#wense_{rand}').remove();
	
});
function lookfiles(ol,lx,mtype){
	var project_name = $(ol).attr('project_name');
	addtabs({num:'archives_file_'+lx,url:'main,archives,files,mid='+lx+',htype=sc,mtype='+mtype,icons:'icon-bookmark-empty',name:'['+project_name+']项目文件'});
}


</script>
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
				<li><span class="reviewContent stateContent">项目名称</span></li>
				<li>
					<input class="form-control" style="width:170px" id="project_name_{rand}"   placeholder="项目名称">
				</li>
			</ul>
		</div>
		<div class="searchAc1">
			<ul>
				<li>	<button class="btn_ btn_search" click="search" type="button">查询</button></li>
			
			</ul>
		</div>
		
	
						
		
	</div>
</section>
<!--<div>
	<table width="100%">
	<tr>
	<td style="padding-left:10px" width="200">
		<input class="form-control" style="width:200px" id="key_{rand}"   placeholder="分类/项目名称">
	</td>
	<td style="padding-left:10px">
		<button class="btn btn-default" click="search" type="button">搜索</button> 
	</td>
	</tr>
	</table>
	
</div>
<div class="blank10"></div>-->
<div id="view_{rand}"></div>
