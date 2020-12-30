<?php
if (!defined('HOST'))
	die('not access');
?>
<script >var lx = ''; //当前项目id
$(document).ready(function(){
	{params}
	var atype=params.atype,zt=params.zt;
	if(!zt)zt='';
	

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
<style type="text/css">

</style>
	
	<div class="status_log">
		<ul>
			<li>当前状态：</li>
			<li>项目年份：</li>
			<li>状态更改记录</li>
			<li><div><span>更改时间：</span><span>更：</span></div></li>
			<li>当前状态：</li>
		</ul>
	</div>
</div>
<div class="blank10"></div>
<div id="view_{rand}"></div>
