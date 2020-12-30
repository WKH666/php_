<?php if(!defined('HOST'))die('not access');?>
<script >
var a = '';
$(document).ready(function(){
	{params}
	var atype=params.atype,zt=params.zt;
	if(!zt)zt='';
	a = $('#view_{rand}').bootstable({
		url:js.getajaxurl('commentContrast','project_comment','main',{}),
		fanye:true,modename:'网评列表',
		celleditor:true,storeafteraction:'normlistafter',
//		storebeforeaction:'dataauthbefore',
		columns:[{
			text:'网评批次',dataIndex:'pici_name'
		},{
			text:'项目类型',dataIndex:'mtype',renderer:function(v,d){
				if(v == 'project_sx_apply')return '实训类项目';
				else return '非实训类项目';
			}
		},{
			text:'项目数量',dataIndex:'project_num'
		},{
			text:'网评状态',dataIndex:'com_status'
		},{
			text:'网评发起时间',dataIndex:'operating_time',sortable: true
		},{
			text:'操作',dataIndex:'caoz',width:'280px'
		}],
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
				//mtype:get('mtype_{rand}').value,
				launch_time:time_frame,
				pici_name:get('pici_name_{rand}').value,
			},true);
		},
		daochu:function(){
			a.exceldown(nowtabs.name);
		},
		addnorm:function(){
			project_norm=a;
			if(get('tabs_edit_norm')) closetabs('edit_norm');
			if(get('tabs_add_norm')) closetabs('add_norm');
			addtabs({num:'add_norm',url:'main,project_comment,norm_edit',icons:'icon-bookmark-empty',name:'新增指标'});
			thechangetabs('add_norm');
		},
		clickdt:function(o1, lx){
			$(o1).rockdatepicker({initshow:true,view:'date',inputid:'dt'+lx+'_{rand}'});
		},
		looknorm:function(){
			addtabs({num:'look_norm',url:'main,project_comment,norm_look',icons:'icon-bookmark-empty',name:'查看'});
		}
	};
	js.initbtn(c);
	
	if(atype=='wwc'){
		$('#wense_{rand}').remove();
		$('#btngroup{rand}').hide();
	}
	if(atype!='my' && atype!='wcj')$('#wense_{rand}').remove();
});

//查看汇总信息
function checkpici(id){
	if(get('tabs_checkpici')) closetabs('checkpici');
	addtabs({num:'checkpici',url:'main,project_comment,contrast_detail,pici_id='+id,icons:'icon-bookmark-empty',name:'查看汇总信息'});
	thechangetabs('checkpici');
}

//重写tabs改变事件
function thechangetabs(num){
	$("div[temp='content']").hide();
	$("[temp='tabs']").removeClass();
	var bo = false;
	if(get('content_'+num+'')){
		$('#content_'+num+'').show();
		$('#tabs_'+num+'').addClass('accive');
		nowtabs = tabsarr[num];
	}
	opentabs.push(num);
	_changhhhsv(num);
}

function dow(pici_id,lx){
	url=getRootPath()+'/?d=main&m=project_comment&a=getExcel&pici_id='+pici_id+'&lx='+lx;
	js.open(url,800,500);
}
</script>

<style type="text/css">
	.serachPanel{
		display: block;
   		padding-left: 1%;
   		/*min-width: 700px;*/
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
		<!--<div class="searchAc1">
			<ul>
				<li >
					<span class="reviewContent stateContent">项目类别</span>
				</li>
				<li>
					<select id="mtype_{rand}" name="mtype" class="selSearch selAuditState">
						<option value="">请选择</option>
						<option value="project_sx_apply">实训项目</option>
						<option value="project_apply">非实训项目</option>
					</select>
				</li>
			</ul>
		</div>-->
		<div class="searchAc1" style="/*width: 33%;*/min-width: 440px;">
			<ul>
				<li>
					<span class="reviewContent stateContent">网评发起时间</span>
				</li>
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
				<li>
					<span class="reviewContent stateContent">所属批次</span>
				</li>
				<li>
					<input type="text" id="pici_name_{rand}" name="pici_name" class="form-control txtPanel">
				</li>
			</ul>
		</div>
		<div class="searchAc1" >
			<ul>
				<li>
					<input class="btn_ marH1" type="button" click="search" value="查询" />
				</li>
			</ul>
		</div>
	</div>
</section>
<div id="view_{rand}"></div>
