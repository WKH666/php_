<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title><?=$da['title']?></title>
<link rel="stylesheet" href="<?=$da['p']?>/css/css.css" />
<link rel="stylesheet" href="mode/kindeditor/themes/default/default.css" />
<link rel="stylesheet" type="text/css" href="mode/plugin/css/jquery-rockdatepicker.css"/>
<link rel="stylesheet" type="text/css" href="webmain/css/app.css">
<link rel="shortcut icon" href="favicon.ico" />
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/js.js"></script>
<script type="text/javascript" src="js/base64-min.js"></script>
<script type="text/javascript" src="mode/kindeditor/kindeditor-min.js"></script>
<script type="text/javascript" src="mode/plugin/jquery-rockdatepicker.js"></script>
<script type="text/javascript" src="<?=$da['p']?>/flow/input/inputjs/input.js"></script>
<script type="text/javascript" src="mode/layer/layer.js"></script>
<script type="text/javascript" src="web/res/js/jquery-changeuser.js"></script>
<script type="text/javascript">
var editor,arr=<?=$da['fieldsjson']?>,moders=<?=json_encode($da['moders'])?>,isedit=0,mid='<?=$da['mid']?>',data={};
</script>
<style>
.tdcont{padding:0px 0px;font-size:14px;}
.tdcont *{font-size:14px;}
.tdcont a{color:red;}
.ys0{border:1px #888888 solid}
.ys1{padding:5px 5px; border:1px #E5D7D7 solid;color:#555555;}
.ys2{padding:5px 5px; border:1px #E5D7D7 solid;}

.inputs{width:95%;font-size:14px;}
.cionsss{padding:4px; background-color:#dddddd}
.datesss{background:url(mode/icons/date.png) no-repeat right;cursor:pointer}

.status{position: absolute;right:5px;top:10px;display:none;width:80px;height:80px;overflow:hidden; border:3px red solid;border-radius:50%;font-size:20px;text-align:center;line-height:80px;color:red;transform:rotate(-45deg);-o-transform:rotate(-45deg);-ms-transform:rotate(-45deg);-webkit-transform:rotate(-45deg);filter:progid:DXImagetransform.Microsoft.Matrix(M11=0.707,M12=-0.707,M21=0.707,M22=0.707,SizingMethod='auto expand');}

.tablesub td{height:25px;text-align:center;border-bottom:1px #888888 solid;border-right:1px #888888 solid;}
.tablesub .inputs{border:none}

.course{padding:8px 10px; background-color:#bfddfb;border:1px #419af1 solid;margin-right:10px;text-align:center}
.coursejt{height:8px;overflow:hidden;width:30px;background-color:#bfddfb}
.coursejts{width:0px;  height:0px; overflow:hidden;border-width:8px;border-style:solid;border-color:transparent transparent transparent #bfddfb;}
.btn{color:#ffffff;opacity:0.8; background-color:#1389D3; padding:5px 10px; border:none; cursor:pointer;font-size:14px}
.btn-default{background-color:#999999;}
.btn:hover{opacity:1;color:#ffffff}
</style>
</head>
<body style="background:white">
<div align="center">
	<div class="blank10"></div>
	<div style="min-width:650px; position:relative;max-width:750px;margin:0px 10px">
		<div class="status"></div>
		<div style="padding-bottom:15px;"><span onclick="location.reload()" style="font-size:24px"><?=$da['title']?></span></div>
		<div class="tdcont" align="left">
			<form name="myform">
			<input name="id" type="hidden" value="<?=$da['mid']?>">

		<div class="mainTitle"><br /></div>
<p><br /></p>
<p class="tCenter pad1 f20 bgColor1 marBot3">一、项目申报单位情况</p>
<table width="100%" bordercolor="#000000" border="0" class="ke-zeroborder tbTxt">
	<tbody>
		<tr>
			<td width="15%" height="34" align="right" class="ys1">项目名称</td>
			<td colspan="3" class="ys2"><span id="div_project_name" class="divinput"><input class="inputs" type="text" value=""  name="project_name"></span></td>
		</tr>
		<tr>
			<td width="15%" height="34" align="right" class="ys1">项目分类</td>
			<td colspan="3" class="ys2"><span id="div_project_select" class="divinput"><select style="width:96%"  name="project_select" class="inputs"><option value="">-请选择-</option><option value="实训、教学基地项目" >实训、教学基地项目</option><option value="教改、专业、课程建设项目" >教改、专业、课程建设项目</option><option value="科技项目" >科技项目</option><option value="工程、基础设施项目" >工程、基础设施项目</option><option value="信息化项目" >信息化项目</option><option value="采购项目" >采购项目</option><option value="校园文化建设项目" >校园文化建设项目</option></select></span></td>
		</tr>
		<tr>
			<td height="34" align="right" class="ys1">项目编号</td>
			<td colspan="3" class="ys2"><span id="div_project_number" class="divinput"><input class="inputs" type="text" value=""  name="project_number"></span></td>
		</tr>
		<tr>
			<td height="34" align="right" class="ys1">项目实施年度</td>
			<td width="29%" class="ys2"><span id="div_project_year" class="divinput"><input class="inputs" type="text" value=""  name="project_year"></span></td>
			<td width="21%" align="right" class="ys1">项目申报时间</td>
			<td width="35%" class="ys2"><span id="div_project_apply_time" class="divinput"><input onclick="js.datechange(this,'date')" value=""  class="inputs datesss" inputtype="date" readonly name="project_apply_time"></span></td>
		</tr>
		<tr>
			<td height="34" align="right" class="ys1">项目申报单位</td>
			<td width="29%" class="ys2"><input class="inputs" style="border:none;background:none" name="base_deptname" value="系统管理员" readonly></td>
			<td width="21%" align="right" class="ys1">业务主管部门</td>
			<td width="35%" class="ys2"><br /><span id="div_project_z_bumeng" class="divinput"><table width="98%" cellpadding="0" border="0"><tr><td width="100%"><input  class="inputs" style="width:96%" id="changeproject_z_bumeng" value="" readonly type="text" name="project_z_bumeng"><input name="" value="" id="changeproject_z_bumeng_id" type="hidden"></td><td nowrap><a href="javascript:;" onclick="js.changeclear('changeproject_z_bumeng')" class="webbtn">×</a><a href="javascript:;" id="btnchange_project_z_bumeng" onclick="js.changeuser('changeproject_z_bumeng','changedept')" class="webbtn">选择</a></td></tr></table></span></td>
		</tr>
		<tr>
			<td height="34" align="right" class="ys1">项目负责人</td>
			<td width="29%" class="ys2"><span id="div_project_head" class="divinput"><input class="inputs" type="text" value=""  name="project_head"></span></td>
			<td width="21%" align="right" class="ys1">项目负责人联系电话</td>
			<td width="35%" class="ys2"><span id="div_project_head_phone" class="divinput"><input class="inputs" type="text" value=""  name="project_head_phone"></span></td>
		</tr>
		<tr>
			<td height="34" align="right" class="ys1">项目性质</td>
			<td width="29%" class="ys2">库项目<input value="库项目"  type="hidden" name="project_xingzhi"></td>
			<td width="21%" align="right" class="ys1"><br /></td>
			<td width="35%" class="ys2"><br /></td>
		</tr>
	</tbody>
</table><input value="申报中" type="hidden" name="project_ku"></span>
</span>
<p><br /></p>
<table width="100%" border="0" cellpadding="0" cellspacing="0" id="tablesub0" class="ke-zeroborder tbTxt tCenter">
	<tbody>
		<tr>
			<td width="10%" class="ys1 tdh40 ">序号</td>
			<td class="ys1 tdh40">姓名</td>
			<td class="ys1 tdh40">单位</td>
			<td class="ys1 tdh40">职务/职称</td>
			<td class="ys1 tdh40">工作分工</td>
			<td class="ys1 tdh40">联系方式</td>
			<td width="10%" class="ys1 tdh40">操作</td>
		</tr>
		<tr>
			<td class="ys1"><input class="inputs" style="text-align:center" readonly temp="xuhao" type="text" value="1" name="xuhao0_0"><input value="0" type="hidden" name="sid0_0"></td>
			<td class="ys1"><input class="inputs" type="text" value=""  name="project_team_name0_0"></td>
			<td class="ys1"><input class="inputs" type="text" value=""  name="project_team_dept0_0"></td>
			<td class="ys1"><input class="inputs" type="text" value=""  name="project_team_post0_0"></td>
			<td class="ys1"><input class="inputs" type="text" value=""  name="project_team_work0_0"></td>
			<td class="ys1"><input class="inputs" type="text" value=""  name="project_team_phome0_0"></td>
			<td class="ys1"><a href="javascript:;" onclick="c.delrow(this,0)" >删除</a></td>
		</tr>
		<tr>
			<td class="ys1"><input class="inputs" style="text-align:center" readonly temp="xuhao" type="text" value="1" name="xuhao0_1"><input value="0" type="hidden" name="sid0_1"></td>
			<td class="ys1"><input class="inputs" type="text" value=""  name="project_team_name0_1"></td>
			<td class="ys1"><input class="inputs" type="text" value=""  name="project_team_dept0_1"></td>
			<td class="ys1"><input class="inputs" type="text" value=""  name="project_team_post0_1"></td>
			<td class="ys1"><input class="inputs" type="text" value=""  name="project_team_work0_1"></td>
			<td class="ys1"><input class="inputs" type="text" value=""  name="project_team_phome0_2"></td>
			<td class="ys1"><a href="javascript:;" onclick="c.delrow(this,0)" >删除</a></td>
		</tr>
		<tr>
			<td class="ys1"><input class="inputs" style="text-align:center" readonly temp="xuhao" type="text" value="1" name="xuhao0_2"><input value="0" type="hidden" name="sid0_2"></td>
			<td class="ys1"><input class="inputs" type="text" value=""  name="project_team_name0_2"></td>
			<td class="ys1"><input class="inputs" type="text" value=""  name="project_team_dept0_2"></td>
			<td class="ys1"><input class="inputs" type="text" value=""  name="project_team_post0_2"></td>
			<td class="ys1"><input class="inputs" type="text" value=""  name="project_team_work0_2"></td>
			<td class="ys1"><input class="inputs" type="text" value=""  name="project_team_phome0_2"></td>
			<td class="ys1"><a href="javascript:;" onclick="c.delrow(this,0)" >删除</a></td>
		</tr>
		<tr>
			<td class="ys1"><input class="inputs" style="text-align:center" readonly temp="xuhao" type="text" value="1" name="xuhao0_3"><input value="0" type="hidden" name="sid0_3"></td>
			<td class="ys1"><input class="inputs" type="text" value=""  name="project_team_name0_3"></td>
			<td class="ys1"><input class="inputs" type="text" value=""  name="project_team_dept0_3"></td>
			<td class="ys1"><input class="inputs" type="text" value=""  name="project_team_post0_3"></td>
			<td class="ys1"><input class="inputs" type="text" value=""  name="project_team_work0_3"></td>
			<td class="ys1"><input class="inputs" type="text" value=""  name="project_team_phome0_3"></td>
			<td class="ys1"><a href="javascript:;" onclick="c.delrow(this,0)" >删除</a></td>
		</tr>
		<tr>
			<td class="ys1"><input class="inputs" style="text-align:center" readonly temp="xuhao" type="text" value="1" name="xuhao0_4"><input value="0" type="hidden" name="sid0_4"></td>
			<td class="ys1"><input class="inputs" type="text" value=""  name="project_team_name0_4"></td>
			<td class="ys1"><input class="inputs" type="text" value=""  name="project_team_dept0_4"></td>
			<td class="ys1"><input class="inputs" type="text" value=""  name="project_team_post0_4"></td>
			<td class="ys1"><input class="inputs" type="text" value=""  name="project_team_work0_4"></td>
			<td class="ys1"><input class="inputs" type="text" value=""  name="project_team_phome0_4"></td>
			<td class="ys1"><a href="javascript:;" onclick="c.delrow(this,0)" >删除</a></td>
		</tr>
	</tbody>
</table>
<div class="cbtn_  btnAdd tdcont1 "><a href="javascript:;" onclick="c.addrow(this,0)">＋新增</a></div>
<p><br /></p>
<p class="tCenter pad1 f20 bgColor1 ">二、项目基本情况</p>
<p><br /></p>
<p><span class="disB tCenter borC marRig3 pad2 f16">2-1项目申请理由</span> </p>
<!--（重点说明项目申报依据及必要性及紧迫性、规划时限等主要情况、主要经济技术指标、项目进度安排等）（不超过800字)-->
<p><span id="div_project_details_one" class="divinput"><textarea class="textarea"  style="height:300px"  placeholder="（重点说明项目申报依据及必要性及紧迫性、规划时限等主要情况、主要经济技术指标、项目进度安排等）限800字" name="project_details_one"></textarea></span></p>
<p><br /></p>
<p><span class="disB tCenter borC marRig3 pad2 f16">2-2项目具体建设目标</span> </p>
<!--要特别说明现有条件（人员、技术、设备、环境、用房）和要学校支持的条件。（1500字以内)-->
<p><span id="div_project_details_two" class="divinput"><textarea class="textarea"  style="height:300px" placeholder="项目具体建设目标、意义、范围、内容、可行性（环境、人员、技术等条件）、及相关技术材料。要特别说明现有条件（人员、技术、设备、环境、用房）和要学校支持的条件。限1500字" name="project_details_two"></textarea></span></p>
<p><br /></p>
<p><span class="disB tCenter borC marRig3 pad2 f16">2-3分项列出主要建设内容</span> </p>
<!--项目投资概预算及资金构成、资金筹措方案。（不超过1000字）-->
<p><span id="div_project_details_three" class="divinput"><textarea class="textarea"  style="height:300px" placeholder="分项列出主要建设内容，项目投资概预算及资金构成、资金筹措方案。限1000字" name="project_details_three"></textarea></span></p>
<p><br /></p>
<p>相关文件</p>
<p><input name="fileid" type="hidden" id="fileidview-inputEl"><div id="view_fileidview" style="width:98%;height:80px;border:1px #cccccc solid; background:white;overflow:auto"></div><div id="fileupaddbtn" class="cbtn_  tdcont1" style="display: inline-block;margin-top:2%;"><a href="javascript:;" class="blue" onclick="c.upload()" >＋添加文件</a></div></p>
<p><br /></p>
<!--<p class="tCenter padBot2 f30 ">流程</p>-->


			</form>
		</div>

		<div style="height:60px; overflow:hidden"></div>
	</div>
	<div id="ControlRow" align="center" style="background:#eeeeee;border-top:1px #aaaaaa solid;padding:10px 0px; position:fixed;width:100%;bottom:0px;left:0px">
		<span id="msgview"></span>&nbsp;
		<input id="AltS" style="display:none" type="button" onclick="return c.save()" value="保存(S)" class="webbtn">&nbsp; &nbsp;
	</div>
</div>
<script>

</script>
<script type="text/javascript" src="<?=$da['p']?>/flow/input/inputjs/mode_<?=$da['moders']['num']?>.js"></script>
<script type="text/javascript" src="web/res/js/jquery-rockupload.js"></script>
<script type="text/javascript" src="web/res/js/jquery-imgview.js"></script>
</body>
</html>
