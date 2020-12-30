<?php if(!defined('HOST'))die('not access');?>
<script >
var a = '';
$(document).ready(function(){
	{params}
	var atype=params.atype,zt=params.zt;
	if(!zt)zt='';
	a = $('#view_{rand}').bootstable({
		url:js.getajaxurl('normlist','project_comment','main',{}),
		fanye:true,modename:'指标列表',
		celleditor:true,storeafteraction:'normlistafter',
//		storebeforeaction:'dataauthbefore',
		columns:[{
			text:'指标体系名称',dataIndex:'dafen_model_name'
		},{
			text:'新增时间',dataIndex:'operating_time'
		},{
			text:'操作',dataIndex:'caoz',width:'180px'
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
				xmmc:get('project_name_{rand}').value,
				sbdw:get('dept_{rand}').value,
				xmbh:get('project_number_{rand}').value,
				xmfl:get('project_select_{rand}').value,
				time_frame: time_frame,
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
        importnorm:function(){
            project_norm=a;
            if(get('tabs_edit_norm')) closetabs('edit_norm');
            if(get('tabs_add_norm')) closetabs('add_norm');
            addtabs({num:'import_norm',url:'main,project_comment,norm_import',icons:'icon-bookmark-empty',name:'导入新增指标'});
            thechangetabs('import_norm');
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

//查看指标
function norm_look(norm_id){
	if(get('tabs_look_norm')) closetabs('look_norm');
	addtabs({num:'look_norm',url:'main,project_comment,norm_look,norm_id='+norm_id,icons:'icon-bookmark-empty',name:'查看指标'});
	thechangetabs('look_norm');
}

//编辑指标
function norm_edit(norm_id){
	project_norm=a;
	if(get('tabs_add_norm')) closetabs('add_norm');
	if(get('tabs_edit_norm')) closetabs('edit_norm');
	addtabs({num:'edit_norm',url:'main,project_comment,norm_edit,norm_id='+norm_id,icons:'icon-bookmark-empty',name:'编辑指标'});
	thechangetabs('edit_norm');
}

//删除指标
function norm_del(norm_id){
	layer.confirm('确认删除该指标？', {
		btn: ['确定', '取消'], //按钮
		shade: 0,
		skin: 'layui-layer-molv',
		closeBtn:0
	}, function() {
		js.ajax(js.getajaxurl('normdel','project_comment','main'),{'norm_id':norm_id},function(da){
			var data =js.decode(da);
			layer.msg(data.msg);
			a.reload();
		});
	}, function() {
		
	});
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
	<div class="searchAc1">
		<ul>
			<li><button class="btn_ btn_search" click="addnorm" type="button">新增指标</button></li>
			<li><button class="btn_ btn_search" click="importnorm" type="button">导入新增指标</button></li>
		</ul>
	</div>
</section>
<div id="view_{rand}"></div>
