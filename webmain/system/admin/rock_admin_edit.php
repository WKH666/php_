<?php if(!defined('HOST'))die('not access');?>
<script >
$(document).ready(function(){
	{params};
	var id = params.id;
	if(!id)id = 0;
	var submitfields = 'status,sort,user,name,pingyin,pass,superid,superman,deptname,mobile,num,sex,tel,deptid,ranking,email,workdate,weixinid,is_admin,unlocktime,school_name';
	if(adminid=='1')submitfields+=',type';
	var h = $.bootsform({
		window:false,rand:'{rand}',tablename:'admin',
		url:js.getajaxurl('publicsave','admin','system'),
		editrecord:'true',modenum:'user',
		params:{int_filestype:'status,type,sort',add_otherfields:'adddt={now}',md5_filestype:'pass'},
		submitfields:submitfields,
		requiredfields:'name,user,ranking,deptname',
		success:function(){
			if(id==0)js.msg('success','成功添加帐号：'+h.form.user.value+'');
			closenowtabs();
			try{adminusermanage.reload();}catch(e){}
		},
		load:function(a){
		}
	});
	h.forminit();
	h.load(js.getajaxurl('loadadmin','admin','system',{id:id}));
	//获取unlocktime解锁到期时间
	$.get(js.getajaxurl('loadadmin','admin','system',{id:id}),function(da){
		var data = js.decode(da);
		var unlocktime = data.unlocktime;
		if(unlocktime == null || unlocktime == undefined || unlocktime == ''){
			$('#unlockBtn').remove();
		}else{
			$('#unlockBtn').removeAttr('disabled');
		}
	});
	try{
		if(adminid!='1')h.form.type.disabled=true;
	}catch(e){}
	var c = {
		getdept:function(){
			js.getuser({
				nameobj:h.form.deptname,
				idobj:h.form.deptid,
				type:'dept',
				value:h.getValue('deptid'),
				title:'选择对应部门'
			});
		},
		getuser:function(){
			js.getuser({
				nameobj:h.form.superman,
				idobj:h.form.superid,
				type:'usercheck',
				value:h.getValue('superid'),
				title:'选择上级主管'
			});
		},
		removess:function(){
			h.form.superman.value='';
			h.form.superid.value='';
		},
		clickdt:function(o1, lx){
			$(o1).rockdatepicker({initshow:true,view:'date',inputid:'dt'+lx+'_{rand}'});
		}
	};

	js.initbtn(c);
	$('#dt1_{rand}').val(js.now());
});
//解锁方法
function unlock(){
	//使解锁到期时间为空，即可
	$('#unlocktime').val(null);
	$('#unlockBtn').attr('disabled',true);
	$('#save_{rand}').click();
}
</script>

<div align="center">
<div  style="padding:10px;width:700px">


	<form name="form_{rand}">
		<input name="id" value="0" type="hidden" />
		<table cellspacing="0" border="0" width="100%" align="center" cellpadding="0">


		<tr>
			<td align="right" width="15%"><font color="red">*</font> 姓名：</td>
			<td class="tdinput" width="35%"><input name="name" maxlength="10" class="form-control"></td>
			<td align="right" width="15%">编号：</td>
			<td class="tdinput" width="35%"><input name="num" placeholder="唯一编号/工号" onblur="js.replacecn(this)" maxlength="20" class="form-control"></td>
		</tr>


	<tr>
			<td align="right"><font color="red">*</font> 用户名：</td>
			<td class="tdinput"><input name="user" placeholder="" onblur="js.replacecn(this)" maxlength="20" class="form-control"></td>

			<td align="right"></td>
			<td class="tdinput"></td>
		</tr>

		<tr>
			<td align="right">密码：</td>
			<td class="tdinput"><input name="pass" value="123456" maxlength="20" class="form-control"></td>

			<td align="right">性别：</td>
            <td class="tdinput"><select name="sex" class="form-control"><option value="男">男</option><option value="女">女</option></select></select></td>

		</tr>


		<tr>
			<td  align="right"><font color="red">*</font> </i>部门：</td>
			<td  class="tdinput">
			<div class="input-group">
				<input readonly class="form-control" name="deptname" >
				<input type="hidden" name="deptid">
				<span class="input-group-btn">
					<button class="btn btn-default" click="getdept" type="button"><i class="icon-search"></i></button
>
				</span>
			</div>
			</td>

			<td align="right"><font color="red">*</font> 职位：</td>
			<td class="tdinput"><input name="ranking" maxlength="20" class="form-control"></td>
		</tr>
        <!--上级主管-->
		<tr>
			<td  align="right"></i>上级主管：</td>
			<td  class="tdinput">
			<div class="input-group">
				<input readonly class="form-control" name="superman" >
				<input type="hidden" name="superid">
				<span class="input-group-btn">
					<button class="btn btn-default" click="removess" type="button"><i class="icon-remove"></i></button>
					<button class="btn btn-default" click="getuser" type="button"><i class="icon-search"></i></button>
				</span>
			</div>
			</td>

			<td align="right" >入职日期：</td>
			<td class="tdinput">
				<div class="input-group">
					<input readonly class="form-control" id="dt1_{rand}" name="workdate" >
					<span class="input-group-btn">
						<button class="btn btn-default" click="clickdt,1" type="button"><i class="icon-calendar"></i></button>
					</span>
				</div>
			</td>
		</tr>




		<tr>
			<td align="right"></td>
			<td class="tdinput"><label><input name="status" value="1" checked type="checkbox">启用</label>&nbsp; &nbsp; <label>
<!--				<input name="type" value="1" type="checkbox">管理员-->
				<input name="is_admin" value="1" type="checkbox">管理员</label></td>
		</tr>

		<tr>
			<td align="right">邮箱：</td>
			<td class="tdinput"><input name="email"  onblur="js.replacecn(this)" class="form-control"></td>
			<td align="right">电话：</td>
			<td class="tdinput"><input name="tel"  onblur="js.replacecn(this)" class="form-control"></td>
		</tr>

		<tr>
			<td align="right">排序号：</td>
			<td class="tdinput"><input name="sort" onfocus="js.focusval=this.value" onblur="js.number(this)" type="number" value="0" class="form-control"></td>
			<td align="right">手机号：</td>
			<td class="tdinput"><input name="mobile"  maxlength="11"  onblur="js.replacecn(this)" class="form-control"></td>
		</tr>

	<tr>
			<td align="right">微信号：</td>
			<td class="tdinput"><input placeholder="手机号不能作为微信号"  onblur="js.replacecn(this)" name="weixinid" class="form-control"></td>
			<td align="right">姓名拼音：</td>
			<td class="tdinput"><input placeholder="拼音全拼(方便人员搜索)"  onblur="js.replacecn(this)" name="pingyin" class="form-control"></td>
		</tr>


		<tr>
            <td align="right">高校名称：</td>
            <td class="tdinput"><input placeholder="没关联高校则不用填" name="school_name" class="form-control"></td>
        </tr>


		<tr>
			<td  align="right"></td>
			<td style="padding:15px 0px" colspan="3" align="left">
				<button disabled class="btn btn-success" id="unlockBtn" onclick="unlock()" type="button"><i class="icon-save"></i>&nbsp;解锁</button>&nbsp;
				<button disabled class="btn btn-success" id="save_{rand}" type="button"><i class="icon-save"></i>&nbsp;保存</button>&nbsp; <span id="msgview_{rand}"></span>
			</td>
		</tr>

		</table>
		</form>

</div>
</div>
