<?php if(!defined('HOST'))die('not access');?>
<script >
$(document).ready(function(){
	{params};
	var id = params.id; 
	if(!id)id = 0;
	var submitfields = 'mtype,mid,project_zhijingly,term_attribute,warehousing_property';
	if(adminid=='1')submitfields+=',type';
	var h = $.bootsform({
		window:false,rand:'{rand}',tablename:params.mtype,
		url:js.getajaxurl('updatemsg','project_manage','main'),
		editrecord:'true',modenum:params.mtype,
		submitfields:submitfields,
		success:function(){
			//if(id==0)js.msg('success','成功更新：'+h.form.user.value+'');
			closenowtabs();
			projectmanage.reload();
		}
	});
	h.forminit();
	
	var c = {
		clickdt:function(o1, lx){
			$(o1).rockdatepicker({initshow:true,view:'year',inputid:'dt'+lx+'_{rand}'});
		},
		upload: function() {
			js.upload('', { showid: 'fileidview_{rand}' });
		}
	};
	js.initbtn(c);
	$('#mid_{rand}').val(params.mid);
	$('#mtype_{rand}').val(params.mtype);
});

</script>

<style>
	.year_dis{
		display: none;
	}
</style>
<div align="center">
<div style="padding:10px;width:720px;">
	
	<form name="form_{rand}">
		<input id="mid_{rand}" name="mid" value="0" type="hidden" />
		<input id="mtype_{rand}" name="mtype" value="" type="hidden" />
		
		<div class="headerTitle" style="width: 100%;">
			<div>
				<span class="titleContent f20" >项目库信息管理</span>
			</div>
		</div>
		<table cellspacing="0" border="0" width="100%" align="center" cellpadding="0">
		<tr>
			<td align="left" width="20%">项目资金主体：</td>
			<td class="tdinput">
				<select id="project_zhijingly_{rand}" name="project_zhijingly" class="form-control">
					<option value="" style="display: none;">-请选择-</option>
					<option value="央财">央财</option>
					<option value="省财">省财</option>
					<option value="市财">市财</option>
					<option value="学校自筹">学校自筹</option>
					<option value="其它">其它</option>
				</select>
			</td>
		</tr>
		<tr>
			<td align="left" width="20%">项目期限属性：</td>
			<td class="tdinput">
				<select id="project_zhijingly_{rand}" name="project_zhijingly" class="form-control">
					<option value="" style="display: none;">-请选择-</option>
					<option value="跨年度">跨年度</option>
					<option value="一次性">一次性</option>
					<option value="期限性">经常性</option>
				</select>
			</td>
		</tr>
		<tr>
			<td align="left" width="20%">项目入库属性：</td>
			<td class="tdinput">
				<select id="project_zhijingly_{rand}" name="project_zhijingly" class="form-control">
					<option value="" style="display: none;">-请选择-</option>
					<option value="延续项目">延续项目</option>
					<option value="结转备选项目">结转备选项目</option>
					<option value="新增项目">新增项目</option>
				</select>
			</td>
		</tr>
		<tr>
			<td style="padding:15px 0px;text-align: center;padding-top: 5%;" colspan="3" align="left"><button class="btn_" id="save_{rand}" type="button"><i class="icon-save"></i>&nbsp;保存</button>&nbsp; <span id="msgview_{rand}"></span>
		</td>
		</tr>
		</table>
	</form>
</div>
</div>