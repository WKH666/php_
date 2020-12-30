<?php if(!defined('HOST'))die('not access');?>
<script >


var projectapplylist = '';
var a = '';
$(document).ready(function(){
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
	js.ajax(js.getajaxurl('getanniu','project_comment','main'),{},function(ds){
		anniu=ds.data;
		if(anniu!=''){
			$('.btn_.btn_search').parent().parent().append(anniu);
		}
	},'post,json');
},'post,json');

	{params}
	var atype=params.atype,zt=params.zt;
	if(!zt)zt='';
	a = $('#view_{rand}').bootstable({
		url:js.getajaxurl('await','project_comment','main'),params:{},
		fanye:true,modename:'专家网评列表',
		celleditor:true,storeafteraction:'awaitafter',
		columns:[{
			text:'项目名称',dataIndex:'project_name'
		},{
			text:'项目类别',dataIndex:'project_select'
		},{
			text:'申报单位',dataIndex:'deptname'
		},{
			text:'项目负责人',dataIndex:'project_head'
		},{
			text:'申报时间',dataIndex:'project_apply_time',sortable:true
		},
		{
			text:'库状态',dataIndex:'project_ku',renderer: function(v, d){
				return '预备库';
			}
		},{
			text:'操作',dataIndex:'caoz',	width:'180px'
		}],

		load:function(){
			projectapplylist = a;
		},
		beforeload:function(){},
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
			//check_project(d.id,d.project_name);
			openxiang('project_apply',d.id);
		},
		search:function(){
			var time_frame = '';//时间范围
			if(get('dt1_{rand}').value != "" && get('dt2_{rand}').value != ""){
				time_frame = get('dt1_{rand}').value+','+get('dt2_{rand}').value
			}
			a.setparams({
				//modeid:get('shengbao_{rand}').value,
				xmmc:get('project_name_{rand}').value,
				sbdw:get('dept_{rand}').value,
				xmbh:get('project_number_{rand}').value,
				//xmfl:get('project_select_{rand}').value,
//				zt:get('state_{rand}').value,
				time_frame: time_frame,
			},true);
		},
//		daochu:function(){
//			a.exceldown(nowtabs.name);
//		},
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
 * 编辑项目
 */
function edit_project(num,mid, project_name){
	projectapplylist = a;
	addtabs({num:'edlt_project_apply_{rand}'+mid,url:getRootPath()+'/index.php?a=lu&m=input&d=flow&mid='+mid+'&num='+num,icons:'icon-bookmark-empty',name:'['+project_name+']编辑'});
}

/**
 * 删除项目
 */
function del(el){
	$(el).parent('td').parent('tr').trigger("click");
	a.del();
}

function delc(id){
	/**
	 * 删除
	 */
	js.ajax(js.getajaxurl('delete','project_apply','main'),{id:id},function(ds){
		layer.msg(ds.msg);
		a.reload();
	},'post,json');
}


/**
 * 处理项目
 */
function chuli_project(num,mid, project_name){
	projectapplylist = a;
	addtabs({num:'chuli_project_apply_{rand}'+mid,url:getRootPath()+'/task.php?a=p&num='+num+'&mid='+mid+'&callback=',icons:'icon-bookmark-empty',name:'['+project_name+']处理'});
}

/*点击事件*/
function processClc(project_ku,mid,mtype){
	$("#layerhtml").html('');
	var layerload = layer.load(0, {shade: false}); //0代表加载的风格，支持0-2
	js.ajax(js.getajaxurl('getprojectflowinfo','project_apply','main'),{'mid':mid,'mtype':mtype},function(da){
		data = js.decode(da);
		context = '<div>'+data.flowcoursestr+'</div>';
		context += '<a class="callbackone" onclick="kutable(\''+project_ku+'\','+mid+',\''+mtype+'\',true)">返回上一级</a>';
		$("#layerhtml").html(context);
		layer.close(layerload);
	});
}

/*点击显示库状态和进程状态的二级表格*/
function kutable(project_ku,mid,mtype,lx){
	var layerload = layer.load(0, {shade: false}); //0代表加载的风格，支持0-2
	js.ajax(js.getajaxurl('getflowdetail','project_apply','main'),{'mid':mid},function(da){
		var html = '';//html内容
		data = js.decode(da);
		var k1 = data.length;
		html +='<div id="layerhtml">';
		$.each(data, function(k,v) {
			if(v.name == project_ku){
				html+='<div class="processDe" onclick="processClc(\''+project_ku+'\','+mid+',\''+mtype+'\')">'+v.name+'</div>';
				html+='<div class="processImg" onclick="processClc(\''+project_ku+'\','+mid+',\''+mtype+'\')"><img src="images/rigSign.png"/></div>';
			}else if(v.name == project_ku && ~~(k+1) == k1){
				html+='<div class="processDe">'+v.name+'</div>';
			}else{
				if(~~(k+1) == k1){
					html+='<div class="processDe bgG">'+v.name+'</div>';
				}else{
					html+='<div class="processDe bgG">'+v.name+'</div>';
					html+='<div class="processImg"><img src="images/rigSign.png"/></div>';
				}
				
			}
		});
		html += '</div>';
		if(!lx){
			//页面层
			layer.open({
				type: 1,
				skin: 'layui-layer-rim', //加上边框
				area: ['60%', '300px'], //宽高
				title:'详细流程',
				content: html
			});
		}else{
			$(".layui-layer-content").html(html);
		}
		layer.close(layerload);
	});
}


function dowList(lx){
	url=getRootPath()+'/?d=main&m=project_comment&a=getExcel&lx='+lx;
	js.open(url,800,500);
}

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
	<div class="searchAc1">
		<ul>
			<li ><span class="reviewContent stateContent">项目名称</span></li>
			<li><input type="text" id="project_name_{rand}" name="project_name" class="form-control txtPanel"></li>
		</ul>
	</div>
	<div class="searchAc1">
		<ul>
			<li><span class="reviewContent stateContent" style="letter-spacing: 2em;margin-right: -2em;">单位</span></li>
			<li>
				<select id="dept_{rand}" name="deptaname" class="selSearch selAuditState">
					<option value="">请选择</option>
				</select>
				<!--<select id="dept_1497507637677_6482" name="deptaname" class="selSearch selAuditState">
					<option value="">请选择</option>
				<option value="校项目办公室">校项目办公室</option><option value="外国语学院">外国语学院</option><option value="计算机工程技术学院">计算机工程技术学院</option><option value="人文社会科学学院">人文社会科学学院</option><option value="党政办公室">党政办公室</option><option value="组织人事处">组织人事处</option><option value="审计处">审计处</option><option value="教务处">教务处</option><option value="党委学生工作部（处）">党委学生工作部（处）</option><option value="科技处">科技处</option><option value="交流合作处">交流合作处</option><option value="财务处">财务处</option><option value="资产管理处">资产管理处</option><option value="保卫处">保卫处</option><option value="后勤处">后勤处</option><option value="经济管理学院">经济管理学院</option><option value="机械与电子工程学院">机械与电子工程学院</option><option value="建筑工程学院">建筑工程学院</option><option value="广州学院">广州学院</option><option value="艺术设计学院">艺术设计学院</option><option value="财会与金融学院">财会与金融学院</option><option value="体育系">体育系</option><option value="思想政治理论课教学部">思想政治理论课教学部</option><option value="创新创业学院">创新创业学院</option><option value="继续教育学院">继续教育学院</option><option value="督导室">督导室</option><option value="图书馆">图书馆</option><option value="实训中心">实训中心</option><option value="网络教育技术中心">网络教育技术中心</option><option value="工会">工会</option><option value="团委">团委</option><option value="资产经营公司">资产经营公司</option><option value="创新强校办公室">创新强校办公室</option><option value="广州校区管理委员会">广州校区管理委员会</option><option value="“互联网+”创新创业中心">“互联网+”创新创业中心</option><option value="业务主管部门">业务主管部门</option><option value="纪检监察处">纪检监察处</option><option value="校外专家">校外专家</option></select>-->
			</li>
		</ul>
	</div>
	<div class="searchAc1">
		<ul>
			<li><span class="reviewContent stateContent">项目编号</span></li>
			<li><input type="text" id="project_number_{rand}" name="project_number" class="form-control txtPanel"></li>
		</ul>
	</div>
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
		</ul>
	</div>
	</div>
</section>
<!--<div class="btnPanel" style="text-align: right;margin-bottom: 1%;">
	<button class="btn_ btn_search" click="daochu,1" type="button">导出</button>
</div>	-->

<!--<div class="blank10"></div>-->
<div id="view_{rand}"></div>
