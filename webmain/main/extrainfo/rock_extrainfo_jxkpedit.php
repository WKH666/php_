<?php if(!defined('HOST'))die('not access');?>
<!--<script src="webmain/flow/input/inputjs/input.js"></script>
<script src="mode/plugin/jquery-rockbase.js"></script>
<script src="web/res/js/jquery-rockupload.js"></script>-->
<script >
$(document).ready(function(){
	{params};
	var id = params.id;
	if(!id)id = 0;
	var submitfields = 'mtype,mid,file_ids,evaluation_time';
	var h = $.bootsform({
		window:false,rand:'{rand}',tablename:'mf_evaluation',
		url:js.getajaxurl('saveevaluation','extrainfo','main'),
		modenum:'mf_evaluation',
		submitfields:submitfields,
		success:function(){
			closenowtabs();
			try{evaluationlist.reload();}catch(e){}
		}
	});
	h.forminit();
	if(id!=0){//如果是编辑，则获取相应的表单数据
		h.load(js.getajaxurl('loadform','extrainfo','main',{id:id,table:jm.base64encode('evaluation')}));
		js.ajax(js.getajaxurl('getupfiles','extrainfo','main'),{id:id,table:jm.base64encode('evaluation')},function(da){
			var data = js.decode(da);
			if(data!=''){
				js.downupshow(data,'fileidview_{rand}');
			}
		});
	}
	
	$('#id_{rand}').val(params.id);//如果有id则是编辑，添加id则是0
	$('#mid_{rand}').val(params.mid);
	$('#mtype_{rand}').val(params.mtype);
	$("#proName_{rand}").html(params.project_name);
	var c = {
		clickdt:function(o1, lx){
			$(o1).rockdatepicker({initshow:true,view:'date',inputid:'dt'+lx+'_{rand}'});
		},
		upload: function() {
			js.upload('', { showid: 'fileidview_{rand}' });
		}
	};
	js.initbtn(c);
});

</script>

<div align="center">
<div  style="padding:10px;width:720px">
	
	<form name="form_{rand}">
		<input id="id_{rand}" name="evaluation_id" value="0" type="hidden" />
		<input id="mid_{rand}" name="mid" value="0" type="hidden" />
		<input id="mtype_{rand}" name="mtype" value="" type="hidden" />
		
		<div class="headerTitle" style="width: 100%;">
			<div>
				<span class="titleContent f20" >录入考评信息</span>
			</div>
		</div>
		<table cellspacing="0" border="0" width="100%" align="center" cellpadding="0">
		<tr>
			<td colspan="2">
				<p><input name="file_ids" type="hidden" id="fileidview_{rand}-inputEl"></p>
				<span>考评资料</span>
				<div id="view_fileidview_{rand}" style="width:100%;height:80px;border:1px #cccccc solid; background:white;overflow:auto"></div>
				<div id="fileupaddbtn" class="marS2"><a href="javascript:;" class="cbtn_" click="upload">＋添加文件</a></div>
			</td>
		</tr>
		<tr>
			<td align="left" width="4%">考评时间：</td>
			<td align="left" style="width: 33%;">
			<div style="width:200px"  class="input-group">
				<input readonly class="form-control" name="evaluation_time" id="dt1_{rand}" >
				<span class="input-group-btn">
					<button class="btn btn-default" click="clickdt,1" type="button"><i class="icon-calendar"></i></button>
				</span>
			</div>
		</td>
		</tr>
		
		
		<tr>
			<!--<td  align="left"></td>-->
			<td style="padding:15px 0px;text-align: center;padding-top: 5%;" colspan="3" align="left"><button class="btn_" id="save_{rand}" type="button"><i class="icon-save"></i>&nbsp;保存</button>&nbsp; <span id="msgview_{rand}"></span>
		</td>
		</tr>
		
		</table>
		</form>
	
</div>
</div>