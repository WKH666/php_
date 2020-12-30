<?php if(!defined('HOST'))die('not access');?>
<script >
$(document).ready(function(){
	{params};
	var id = params.id;
	if(!id)id = 0;
	var submitfields = 'mtype,mid,file_ids,total_cost,purchase_time';
	var h = $.bootsform({
		window:false,rand:'{rand}',tablename:'mf_purchase',
		url:js.getajaxurl('savepurchase','extrainfo','main'),
		modenum:'mf_purchase',
		submitfields:submitfields,
		success:function(){
			closenowtabs();
			try{purchaselist.reload();}catch(e){}
		}
	});
	h.forminit();
	if(id!=0){//如果是编辑，则获取相应的表单数据
		h.load(js.getajaxurl('loadform','extrainfo','main',{id:id,table:jm.base64encode('purchase')}));
		js.ajax(js.getajaxurl('getupfiles','extrainfo','main'),{id:id,table:jm.base64encode('purchase')},function(da){
			var data = js.decode(da);
			if(data !=''){
				js.downupshow(data,'fileidview_{rand}');
			}
		});
	}
	$('#mid_{rand}').val(params.mid);
	$('#mtype_{rand}').val(params.mtype);
	$('#id_{rand}').val(params.id);//如果有id则是编辑，添加id则是0
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
<div  style="padding:10px;width:720px;">
	
	<form name="form_{rand}">
		<input id="id_{rand}" name="purchase_id" value="0" type="hidden" />
		<input id="mid_{rand}" name="mid" value="0" type="hidden" />
		<input id="mtype_{rand}" name="mtype" value="" type="hidden" />
		
		<div class="headerTitle" style="width: 100%;">
			<div>
				<span class="titleContent f20" >录入采购信息</span>
			</div>
		</div>
		<table cellspacing="0" border="0" width="100%" align="center" cellpadding="0">
        
        <tr>
        	<td colspan="2" class="pad1">项目名称：<span class="proName" id="proName_{rand}"></span></td>
        </tr>
        
		<tr>
			<td colspan="2">
				<p><input name="file_ids" type="hidden" id="fileidview_{rand}-inputEl"></p>
				<span>采购资料</span>
				<div id="view_fileidview_{rand}" style="width:100%;height:80px;border:1px #cccccc solid; background:white;overflow:auto"></div>
				<div id="fileupaddbtn" style="margin-top: 2%;"><a href="javascript:;" class="cbtn_"  click="upload">＋添加文件</a></div>
			</td>
		</tr>
		<tr>
			<td align="left" class="pad2" style="padding-top: 5%;" width="4%">总金额：</td>
			<td class="tdinput pad2" width="35%" style="padding-top: 4%;"><input style="width: 33%;" name="total_cost" placeholder="采购总金额/元" maxlength="10" class="form-control"></td>
		</tr>
		<tr>
			<td align="left"  class="pad2"  width="4%">采购时间：</td>
			<td align="left"  class="pad2" style="width: 33%;">
			<div style="width:200px"  class="input-group">
				<input readonly class="form-control" name="purchase_time" id="dt1_{rand}" >
				<span class="input-group-btn">
					<button class="btn btn-default" click="clickdt,1" type="button"><i class="icon-calendar"></i></button>
				</span>
			</div>
		</td>
		</tr>
		
		
		<tr>
			<!--<td  align="right"></td>-->
			<td style="padding:15px 0px;text-align: center;padding-top: 5%;" colspan="3" align="left"><button class="btn_" id="save_{rand}" type="button"><i class="icon-save"></i>&nbsp;保存</button>&nbsp; <span id="msgview_{rand}"></span>
		</td>
		</tr>
		
		</table>
		</form>
	
</div>
</div>