<?php if(!defined('HOST'))die('not access');?>
<script >
var lx = '';//当前项目id
var a = '';//表单
$(document).ready(function(){
	{params}
	lx=params.mid;
	if(!lx)lx='';
	a = $('#view_{rand}').bootstable({
		url:js.getajaxurl('purchase','construction','main',{mid:lx}),data:{is_fk:true},fanye:true,storeafteraction:'constructionafter',storebeforeaction:'dataauthbefore',
		columns:[{
            text:'项目编号',dataIndex:'budget_code'
        }, {
            text:'项目名称',dataIndex:'budget_name'
        }, {
			text:'付款凭证号',dataIndex:'pay_code'
		}, {
			text:'付款时间',dataIndex:'pay_date'
		}, {
			text:'已付金额(元)',dataIndex:'a_pay_money'
		}, {
            text:'应付金额(元)',dataIndex:'s_pay_money'
        }, {
            text:'余额(元)',dataIndex:'pay_budget'
        }, {
            text:'操作',renderer:function(v,d){
                return '<a onclick="look('+d.id+',\''+d.budget_name+'\')">查看</a>';
            }
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
				is_fk:true,
				pay_date: time_frame,
				budget_name:get('project_name_{rand}').value,
				pay_code:get('pay_code_{rand}').value,
			},true);
		},
		clickdt:function(o1, lx){
			$(o1).rockdatepicker({initshow:true,view:'date',inputid:'dt'+lx+'_{rand}'});
		}
	};
	js.initbtn(c);
});


function look(id, project_name){
    addtabs({num:'accept_detail_'+id,url:'main,construction,yslook,id='+id,icons:'icon-bookmark-empty',name:'['+project_name+']验收信息'});
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
		min-width: 271px;
		*/
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
	.searchAc1  .stateContent{
		display: inline-block;
  		/*width: 90px;
  		text-indent: 20%;*/
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
				<li><input class="form-control txtPanel" style="" id="project_name_{rand}" placeholder="请输入项目名称"></li>
			</ul>
		</div>
		<div class="searchAc1">
			<ul>
				<li><span class="reviewContent stateContent">付款凭证号</span></li>
				<li><input class="form-control txtPanel" style="" id="pay_code_{rand}" placeholder="请输入付款凭证号"></li>
			</ul>
		</div>
		<div class="searchAc1" style="/*width: 33%;*/min-width: 440px;">
			<ul>
				<li><span class="reviewContent stateContent">付款时间</span></li>
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


<!--
<div>
	<table width="100%">
	<tr>
		<td align="center" style="width: 33%;">项目名称</td>
		<td align="center" style="width: 33%;">项目负责人<input class="form-control" style="width:200px" id="project_head_{rand}" placeholder="请输入项目负责人名称"></td>
		<td align="center" style="width: 33%;">
			<div style="width:200px"  class="input-group">
				<input placeholder="验收日期" readonly class="form-control" id="dt1_{rand}" >
				<span class="input-group-btn">
					<button class="btn btn-default" click="clickdt,1" type="button"><i class="icon-calendar"></i></button>
				</span>
			</div>
		</td>
	</tr>
		<tr align="center" class="">
		<td colspan="3"><div class="btnPanel1"><input class="btn_  marH1" type="button" click="search" value="查询" /><input class="btn_" type="button" onclick="downloadt()" value="重置" /></div></td></tr>
	<tr> 
		<td><input class="btn btn-success" type="button" click="search" value="查询" /></td>
		<td><input class="btn btn-success" type="button" onclick="downloadt()" value="重置" /></td>
		<td><input class="btn btn-success" type="button" onclick="downloadt()" value="录入库验收信息" /></td>
		<td><input class="btn btn-success" type="button" onclick="downloadt()" value="录入非库验收信息" /></td>
	</table>
	
</div>-->
<!--<div class="blank10"></div>-->
<div id="view_{rand}"></div>
