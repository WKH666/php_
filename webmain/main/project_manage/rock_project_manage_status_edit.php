<?php if(!defined('HOST'))die('not access');?>
<script >
$(document).ready(function(){
	{params};
	var id = params.id; 
	if(!id)id = 0;
	var submitfields = 'mtype,mid,upldate_status,remark,file_ids,carryover_yeas';
	if(adminid=='1')submitfields+=',type';
	var h = $.bootsform({
		window:false,rand:'{rand}',tablename:'mf_status_log',
		url:js.getajaxurl('save','project_manage','main'),
		editrecord:'true',modenum:'mf_status_log',
		params:{int_filestype:'status,type,sort',add_otherfields:'update_time={now}'},
		submitfields:submitfields,
		success:function(){
			//if(id==0)js.msg('success','成功更新：'+h.form.user.value+'');
			closenowtabs();
			projectmanage.reload();
		},
		load:function(a){
			
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
	/**
	 * 获取查询条件
	 */
	js.ajax(js.getajaxurl('getstatuscondition','project_manage','main'),{},function(ds){
		//项目状态
		$.each(ds, function(k,v) {
			$("#upldate_status_{rand}").append("<option value='"+v.name+"'>"+v.name+"</option>");
		});
	},'post,json');
	
	$("#upldate_status_{rand}").change(function(){
		if($(this).val() == '结转'){
			$("#year_tr").removeClass('year_dis');
		}else{
			$("#year_tr").addClass('year_dis');
		}
	});
});

</script>

<style>
	.year_dis{
		display: none;
	}
</style>
<!--
<div align="center">
<div  style="padding:10px;width:700px">
	
	<form name="form_{rand}">
		<input id="mid_{rand}" name="mid" value="0" type="hidden" />
		<table cellspacing="0" border="0" width="100%" align="center" cellpadding="0">
        
        <tr>
        	<td>项目库状态更改</td>
        </tr>
		<tr>
			<td align="right" width="15%">项目状态：</td>
			<td class="tdinput" width="35%"><input name="upldate_status" maxlength="10" class="form-control"></td>
			<td class="tdinput" width="35%">
				<select id="upldate_status_{rand}" name="upldate_status" class="form-control">
					<option value="">-请选择-</option>
				</select>
			</td>
		</tr>
		<tr id="year_tr" class="year_dis">
			<td align="right" width="15%">项目年份：</td>
			<td class="tdinput" width="35%"><input name="carryover_yeas" maxlength="10" class="form-control"></td>
			<td align="left" style="width: 33%;">
			<div style="width:200px"  class="input-group">
				<input readonly class="form-control" name="carryover_yeas" id="dt1_{rand}" >
				<span class="input-group-btn">
					<button class="btn btn-default" click="clickdt,1" type="button"><i class="icon-calendar"></i></button>
				</span>
			</div>
		</tr>
		<tr>
			<td align="right" width="15%">备注说明：</td>
			<td class="tdinput" width="35%"><textarea name="remark" class="form-control" rows="3"></textarea></td>
		</tr>
		<tr>
			<td align="right" width="15%">相关资料：</td>
			<td>
				<p><input name="file_ids" type="hidden" id="fileidview_{rand}-inputEl"></p>
				<div id="view_fileidview_{rand}" style="width:98%;height:200px;border:1px #cccccc solid; background:white;overflow:auto"></div>
				<div id="fileupaddbtn"><a href="javascript:;" click="upload"><u>＋添加文件</u></a></div>
			</td>
		</tr>
		
		<tr>
			<td  align="right"></td>
			<td style="padding:15px 0px" colspan="3" align="left"><button class="btn btn-success" id="save_{rand}" type="button"><i class="icon-save"></i>&nbsp;保存</button>&nbsp; <span id="msgview_{rand}"></span>
		</td>
		</tr>
		
		</table>
		</form>
	
</div>
</div>
-->



<div align="center">
<div  style="padding:10px;width:720px;">
	
	<form name="form_{rand}">
		<input id="mid_{rand}" name="mid" value="0" type="hidden" />
		<input id="mtype_{rand}" name="mtype" value="" type="hidden" />
		
		<div class="headerTitle" style="width: 100%;">
			<div>
				<span class="titleContent f20" >项目库状态更改</span>
			</div>
		</div>
		<table cellspacing="0" border="0" width="100%" align="center" cellpadding="0">
        
        
	
		<tr>
			<td align="left" class="pad2" style="padding-top: 5%;" width="4%">项目状态：</td>
			<td class="tdinput pad2" width="35%" style="padding-top: 4%;">
				<select style="width: 32.8%;" id="upldate_status_{rand}" name="upldate_status" class="form-control">
					<option value="">-请选择-</option>
				</select>
			</td>
		</tr>
		
		<tr id="year_tr" class="year_dis">
			<td align="left" class="pad2" style="padding-top: 5%;" width="4%">项目年份：</td>
			<td class="tdinput pad2" width="35%" style="padding-top: 4%;">
				<div style="width:200px"  class="input-group">
					<input readonly class="form-control" name="carryover_yeas" id="dt1_{rand}" >
					<span class="input-group-btn">
						<button class="btn btn-default" click="clickdt,1" type="button"><i class="icon-calendar"></i></button>
					</span>
				</div>
			</td>
		</tr>
		
		<tr>
			<td colspan="2">
				<p><input name="file_ids" type="hidden" id="fileidview_{rand}-inputEl"></p>
				<span>相关资料</span>
				<div id="view_fileidview_{rand}" style="width:100%;height:80px;border:1px #cccccc solid; background:white;overflow:auto"></div>
				<div id="fileupaddbtn" style="margin-top: 2%;"><a href="javascript:;" class="cbtn_"  click="upload">＋添加文件</a></div>
			</td>
		</tr>
		<tr>
			<td align="left"  class="pad2"  width="4%">备注说明：</td>
			<td align="left"  class="pad2" style="width: 33%;">
				<textarea name="remark" class="form-control" rows="3"></textarea>
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