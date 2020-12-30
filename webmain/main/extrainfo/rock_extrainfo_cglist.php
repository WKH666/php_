<?php if(!defined('HOST'))die('not access');?>
<script >
var lx = '';//当前项目id
var a = '';//列表
$(document).ready(function(){
	{params}
	lx=params.mid;
	if(!lx)lx='';
	a = $('#view_{rand}').bootstable({
		url:js.getajaxurl('purchase','extrainfo','main',{}),fanye:true,celleditor:true,storeafteraction:'extrainfoafter',storebeforeaction:'dataauthbefore',
		columns:[{
			text:'项目名称',dataIndex:'project_name'
		},
		{
			text:'采购时间',dataIndex:'purchase_time',renderer:function(v,d){
				if(d.is_purchase == 0)return '暂未采购';
				else return v;
			}
		},
		{
			text:'总计金额/元',dataIndex:'total_cost',renderer:function(v,d){
				if(d.is_purchase == 0)return 0;
				else return v;
			}
		},
		{
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
				project_name:get('project_name_{rand}').value,
				total_cost:get('total_cost_{rand}').value,
			},true);
		},
		clickdt:function(o1, lx){
			$(o1).rockdatepicker({initshow:true,view:'date',inputid:'dt'+lx+'_{rand}'});
		}
	};
	js.initbtn(c);
});


//录入采购信息
function purchase_add(lx,mtype,project_name){
	purchaselist = a;
	addtabs({num:'purchase_add'+lx,url:'main,extrainfo,cgedit,mid='+lx+',mtype='+mtype+',project_name='+project_name,icons:'icon-bookmark-empty',name:'录入['+project_name+']采购信息'});
}

//查看采购信息
function purchase_check(mid,id,project_name){
	var tab_id = 'purchase-'+id;//这个请求不能直接用1,2,3这样的方式传值，要表名_id
	addtabs({num:'purchase_file_'+id,url:'main,archives,files,tab_id='+tab_id+',mid='+mid,icons:'icon-bookmark-empty',name:'['+project_name+']采购信息文件'});
}


//编辑采购信息
function purchase_edit(lx,mtype,id,project_name){
	purchaselist = a;
	addtabs({num:'purchase_edit'+lx,url:'main,extrainfo,cgedit,id='+id+',mtype='+mtype+',mid='+lx+',project_name='+project_name,icons:'icon-bookmark-empty',name:'编辑['+project_name+']采购信息'});
}

//删除采购信息
function purchase_delete(lx,mtype){
	layer.confirm('确认删除该采购信息？', {
		btn: ['确定', '取消'], //按钮
		shade: 0,
		skin: 'layui-layer-molv',
		closeBtn:0
	}, function() {
		js.ajax(js.getajaxurl('publicdel','extrainfo','main'),{'mid':lx,table:jm.base64encode('purchase'),mtype:mtype},function(da){
			var data =js.decode(da);
			layer.msg(data.msg);
			a.reload();
		});
	}, function() {
		layer.msg('已取消');
	});
}

//预览
function yulan(){
	var url = js.getajaxurl('@lu','input','flow',{num:'purchasefile'});
	js.open(url, 700,450);
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
    /*padding-right: 10px;*/
	}
	.searchAc1  .stateContent{
		display: inline-block;
  		/*width: 90px;*/
  		/*text-indent: 20%;*/
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
				<li ><span class="reviewContent stateContent">项目名称</span></li>
				<li><input class="form-control txtPanel"  id="project_name_{rand}" placeholder="请输入项目名称"></li>
			</ul>
		</div>
		<div class="searchAc1">
			<ul>
				<li><span class="reviewContent stateContent">采购金额</span></li>
				<li><input class="form-control txtPanel"  id="total_cost_{rand}" placeholder="请输入采购金额"></li>
			</ul>
		</div>
		<div class="searchAc1" style="/*width: 33%;*/min-width: 440px;">
			<ul>
				<li><span class="reviewContent stateContent">采购日期</span></li>
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
				<li><button class="btn_ btn_search" click="search" type="button" >查询</button></li>
				<!--<li><input class="btn_  marH1" type="button" click="search" value="查询" /></li>-->
				<!--<li><input class="btn_" type="button" onclick="resetAllInputSel(this)" value="重置" /></li>-->
			</ul>
		</div>
		
	
						
		
	</div>
</section>


<!--

<div class="selBackGround">
	<table width="100%">
	<tr>
		<td align="center" style="width: 33%;">项目名称<input class="form-control" style="width:200px" id="project_name_{rand}" placeholder="请输入项目名称"></td>
		<td align="center" style="width: 33%;">采购金额<input class="form-control" style="width:200px" id="total_cost_{rand}" placeholder="请输入采购金额"></td>
		<td align="center" style="width: 33%;">
			<div style="width:200px"  class="input-group">
				<input placeholder="采购日期" readonly class="form-control" id="purchase_time_{rand}" >
				<span class="input-group-btn">
					<button class="btn btn-default" click="clickdt" type="button"><i class="icon-calendar"></i></button>
				</span>
			</div>
		</td>
	</tr>
	<tr align="center" class="">
		<td colspan="3"><div class="btnPanel1"><input class="btn_  marH1" type="button" click="search" value="查询" /><input class="btn_" type="button" onclick="downloadt()" value="重置" /></div></td></tr>
		<td><input class="btn btn-success" type="button" click="purchase_add,0" value="录入采购信息" /></td>
	</table>
	
</div>-->
<!--<div class="blank10"></div>-->
<div id="view_{rand}"></div>
