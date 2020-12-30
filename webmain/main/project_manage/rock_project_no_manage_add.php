
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>项目申报</title>
<link rel="stylesheet" href="webmain/css/css.css" />
<link rel="stylesheet" href="mode/kindeditor/themes/default/default.css" />
<link rel="stylesheet" type="text/css" href="mode/plugin/css/jquery-rockdatepicker.css"/>
<link rel="stylesheet" type="text/css" href="webmain/css/app.css">
<link rel="shortcut icon" href="favicon.ico" />
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/js.js"></script>
<script type="text/javascript" src="js/base64-min.js"></script>
<script type="text/javascript" src="mode/kindeditor/kindeditor-min.js"></script>
<script type="text/javascript" src="mode/plugin/jquery-rockdatepicker.js"></script>
<script type="text/javascript" src="webmain/flow/input/inputjs/input.js"></script>
<script type="text/javascript" src="mode/layer/layer.js"></script>
<script type="text/javascript" src="web/res/js/jquery-changeuser.js"></script>
<script type="text/javascript">
var editor,arr=[{"fields":"project_name","fieldstype":"text","name":"\u9879\u76ee\u540d\u79f0","dev":null,"data":null,"isbt":"1","islu":"1","attr":null,"iszb":"0"},{"fields":"project_x_time","fieldstype":"date","name":"\u6821\u529e\u516c\u4f1a\u5ba1\u6279\u65f6\u95f4","dev":null,"data":null,"isbt":"0","islu":"0","attr":null,"iszb":"0"},{"fields":"project_x_cishu","fieldstype":"text","name":"\u6821\u529e\u516c\u4f1a\u7b2c\u51e0\u6b21\u5ba1\u6279","dev":null,"data":null,"isbt":"0","islu":"0","attr":null,"iszb":"0"},{"fields":"project_z_score","fieldstype":"text","name":"\u9879\u76ee\u5206\u6570","dev":null,"data":null,"isbt":"0","islu":"0","attr":null,"iszb":"0"},{"fields":"project_z_bumeng","fieldstype":"changedept","name":"\u4e1a\u52a1\u4e3b\u7ba1\u90e8\u95e8","dev":null,"data":null,"isbt":"0","islu":"0","attr":null,"iszb":"0"},{"fields":"project_select","fieldstype":"rockcombo","name":"\u9879\u76ee\u5206\u7c7b","dev":null,"data":"xiangmufl","isbt":"1","islu":"1","attr":null,"iszb":"0"},{"fields":"project_xingzhi","fieldstype":"fixed","name":"\u9879\u76ee\u6027\u8d28","dev":"\u5e93\u9879\u76ee","data":null,"isbt":"0","islu":"1","attr":null,"iszb":"0"},{"fields":"project_ku","fieldstype":"rockcombo","name":"\u6240\u5728\u5e93","dev":null,"data":"kumingcheng","isbt":"0","islu":"1","attr":null,"iszb":"0"},{"fields":"project_zhineng_time","fieldstype":"date","name":"\u804c\u80fd\u90e8\u95e8\u5ba1\u6838\u65f6\u95f4","dev":null,"data":null,"isbt":"0","islu":"0","attr":null,"iszb":"0"},{"fields":"project_z_paixu","fieldstype":"text","name":"\u9879\u76ee\u6392\u5e8f","dev":null,"data":null,"isbt":"0","islu":"0","attr":null,"iszb":"0"},{"fields":"project_z_sum","fieldstype":"text","name":"\u6559\u5b66\u7c7b\u9879\u76ee\/\u975e\u6559\u5b66\u7c7b\u9879\u76ee","dev":null,"data":null,"isbt":"0","islu":"0","attr":null,"iszb":"0"},{"fields":"project_z_count","fieldstype":"text","name":"\u672c\u7c7b\u9879\u76ee\u603b\u6570","dev":null,"data":null,"isbt":"0","islu":"0","attr":null,"iszb":"0"},{"fields":"project_number","fieldstype":"text","name":"\u9879\u76ee\u7f16\u53f7","dev":null,"data":null,"isbt":"1","islu":"1","attr":null,"iszb":"0"},{"fields":"project_year","fieldstype":"text","name":"\u9879\u76ee\u5b9e\u65bd\u5e74\u5ea6","dev":null,"data":null,"isbt":"1","islu":"1","attr":null,"iszb":"0"},{"fields":"project_apply_time","fieldstype":"date","name":"\u9879\u76ee\u7533\u62a5\u65f6\u95f4","dev":null,"data":null,"isbt":"1","islu":"1","attr":null,"iszb":"0"},{"fields":"project_head","fieldstype":"text","name":"\u9879\u76ee\u8d1f\u8d23\u4eba","dev":null,"data":null,"isbt":"1","islu":"1","attr":null,"iszb":"0"},{"fields":"project_head_phone","fieldstype":"text","name":"\u9879\u76ee\u8d1f\u8d23\u4eba\u8054\u7cfb\u7535\u8bdd","dev":null,"data":null,"isbt":"1","islu":"1","attr":null,"iszb":"0"},{"fields":"project_details_one","fieldstype":"textarea","name":"\u9879\u76ee\u8be6\u60c5\u4e00","dev":null,"data":null,"isbt":"1","islu":"1","attr":"style=\"height:300px\"  placeholder=\"\uff08\u91cd\u70b9\u8bf4\u660e\u9879\u76ee\u7533\u62a5\u4f9d\u636e\u53ca\u5fc5\u8981\u6027\u53ca\u7d27\u8feb\u6027\u3001\u89c4\u5212\u65f6\u9650\u7b49\u4e3b\u8981\u60c5\u51b5\u3001\u4e3b\u8981\u7ecf\u6d4e\u6280\u672f\u6307\u6807\u3001\u9879\u76ee\u8fdb\u5ea6\u5b89\u6392\u7b49\uff09\u9650800\u5b57\"","iszb":"0"},{"fields":"project_details_two","fieldstype":"textarea","name":"\u9879\u76ee\u8be6\u60c5\u4e8c","dev":null,"data":null,"isbt":"1","islu":"1","attr":"style=\"height:300px\" placeholder=\"\u9879\u76ee\u5177\u4f53\u5efa\u8bbe\u76ee\u6807\u3001\u610f\u4e49\u3001\u8303\u56f4\u3001\u5185\u5bb9\u3001\u53ef\u884c\u6027\uff08\u73af\u5883\u3001\u4eba\u5458\u3001\u6280\u672f\u7b49\u6761\u4ef6\uff09\u3001\u53ca\u76f8\u5173\u6280\u672f\u6750\u6599\u3002\u8981\u7279\u522b\u8bf4\u660e\u73b0\u6709\u6761\u4ef6\uff08\u4eba\u5458\u3001\u6280\u672f\u3001\u8bbe\u5907\u3001\u73af\u5883\u3001\u7528\u623f\uff09\u548c\u8981\u5b66\u6821\u652f\u6301\u7684\u6761\u4ef6\u3002\u96501500\u5b57\"","iszb":"0"},{"fields":"project_details_three","fieldstype":"textarea","name":"\u9879\u76ee\u8be6\u60c5\u4e09","dev":null,"data":null,"isbt":"1","islu":"1","attr":"style=\"height:300px\" placeholder=\"\u5206\u9879\u5217\u51fa\u4e3b\u8981\u5efa\u8bbe\u5185\u5bb9\uff0c\u9879\u76ee\u6295\u8d44\u6982\u9884\u7b97\u53ca\u8d44\u91d1\u6784\u6210\u3001\u8d44\u91d1\u7b79\u63aa\u65b9\u6848\u3002\u96501000\u5b57\"","iszb":"0"},{"fields":"exigence_status","fieldstype":"rockcombo","name":"\u7d27\u6025\u7a0b\u5ea6","dev":null,"data":"jinjichengdu","isbt":"0","islu":"0","attr":null,"iszb":"0"},{"fields":"fxuankuang","fieldstype":"checkboxall","name":"\u9879\u76ee\u529e\u516c\u4f1a\u6d41\u7a0b\u9009\u62e9","dev":null,"data":"\u4e1a\u52a1\u4e3b\u7ba1\u90e8\u95e8,\u4e13\u5bb6\u7ec4\u8ba8\u8bba,\u6821\u957f\u7b7e\u5b57","isbt":"0","islu":"0","attr":null,"iszb":"0"}],moders={"num":"project_apply","id":"54","name":"\u9879\u76ee\u7533\u62a5","names":"\u9879\u76ee\u7ec4\u6210\u5458,\u590d\u9009\u6846","isflow":"1"},isedit=0,mid='0',data={};
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
		<div style="padding-bottom:15px;"><span onclick="location.reload()" style="font-size:24px">项目申报</span></div>
		<div class="tdcont" align="left">
			<form name="myform">
			<input name="id" type="hidden" value="0">
			<input value="0" type="hidden" name="sub_totals0"><input value="0" type="hidden" name="sub_totals1"><div class="mainTitle"><br /></div>
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
<!--<p class="tCenter padBot2 f30 ">流程</p>-->			</form>
		</div>
		<!--<p class="tCenter padBot2 f30 ">流程</p>--><div align="center" style="padding:20px 0px"><table><tr><td><div class="course">提交</div></td><td><div class="coursejt"></div></td><td><div class="coursejts"></div></td><td><div class="course">上级领导审核</div></td><td><div class="coursejt"></div></td><td><div class="coursejts"></div></td><td><div class="course">校项目办公室初审</div></td><td><div class="coursejt"></div></td><td><div class="coursejts"></div></td><td><div class="course">职能部门专家小组评审</div></td><td><div class="coursejt"></div></td><td><div class="coursejts"></div></td><td><div class="course">上传实施方案</div></td><td><div class="coursejt"></div></td><td><div class="coursejts"></div></td><td><div class="course">校项目办公室转送</div></td><td><div class="coursejt"></div></td><td><div class="coursejts"></div></td><td><div class="course">校级专家论证</div></td><td><div class="coursejt"></div></td><td><div class="coursejts"></div></td><td><div class="course">校长办公会审批</div></td><td><div class="coursejt"></div></td><td><div class="coursejts"></div></td><td><div class="course">结束</div></td></tr></table></div>		<div style="height:60px; overflow:hidden"></div>
	</div>
	<div id="ControlRow" align="center" style="background:#eeeeee;border-top:1px #aaaaaa solid;padding:10px 0px; position:fixed;width:100%;bottom:0px;left:0px">
		<span id="msgview"></span>&nbsp; 
		<input id="AltS" style="display:none" type="button" onclick="return c.save()" value="保存(S)" class="webbtn">&nbsp; &nbsp; 
	</div>
</div>
<script>

</script>
<script type="text/javascript" src="webmain/flow/input/inputjs/mode_project_apply.js"></script>
<script type="text/javascript" src="web/res/js/jquery-rockupload.js"></script>
<script type="text/javascript" src="web/res/js/jquery-imgview.js"></script>
</body>
</html>