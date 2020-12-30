<?php if(!defined('HOST'))die('not access');?>
<script >
$(document).ready(function(){
	{params};
	var id = params.id;
	if(!id)id = 0;
    js.ajax(js.getajaxurl('getsreachcondition','project_manage','main'),{},function(ds){
        //申报单位
        $.each(ds.sbdwarr, function(k,v) {
            $("#dept_{rand}").append("<option value='"+v.name+"'>"+v.name+"</option>");
        });
    },'post,json,false');

    var submitfields = 'mtype,mid,file_ids,appropriation_time,project_zhijingly,financial_card_number,balance_of_funds,amount_of_funds,dept,person_in_charge,telphone';
	var h = $.bootsform({
		window:false,rand:'{rand}',tablename:'mf_appropriation',
		url:js.getajaxurl('saveappropriation','construction','main'),
		modenum:'mf_appropriation',
		submitfields:submitfields,
		success:function(){
			closenowtabs();
			try{appropriationlist.reload();}catch(e){}
		},
        loadafter:function(a){
            h.setValue('project_zhijingly', a.data.budget_source);
            h.setValue('financial_card_number', a.data.budget_card_number);
            h.setValue('balance_of_funds', a.data.budget_amount);
            h.setValue('amount_of_funds', a.data.remainder);
            h.setValue('dept', a.data.department);
            h.setValue('person_in_charge', a.data.budget_director);
            h.setValue('telphone', a.data.budget_director_number);
            if(a.data.length != 0){
                js.downupshow(a.data.files,'fileidview_{rand}');
            }
        }
	});
	h.forminit();
	if(id!=0){//如果是编辑，则获取相应的表单数据
		h.load(js.getajaxurl('loadform','construction','main',{id:id,table:jm.base64encode('appropriation')}));
	}
    $("#id_{rand}").val(params.id);//如果有id则是编辑，添加id则是0
    h.setValue('mid', params.mid);
    h.setValue('mtype', params.mtype);
    h.setValue('proName', params.project_name);
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
		<input id="id_{rand}" name="appropriation_id" value="0" type="hidden" />
		<input id="mid_{rand}" name="mid" value="0" type="hidden" />
		<input id="mtype_{rand}" name="mtype" value="" type="hidden" />
		
		<div class="headerTitle" style="width: 100%;">
			<div>
				<span class="titleContent f20" >录入拨付信息</span>
			</div>
		</div>
		<table cellspacing="0" border="0" width="100%" align="center" cellpadding="0">
		<tr>
        	<td style="width: 5%" class="pad1">项目名称：</td>
            <td>
                <input readonly class="form-control" name="proName" id="proName_{rand}" >
                <!--<span class="proName" id="proName_{rand}"></span>-->
            </td>
        </tr>
        <tr>
            <td style="width: 5%" class="pad2">经费来源：</td>
            <td>
                <!--<input class="form-control" name="project_zhijingly" id="project_zhijingly_{rand}" >-->
                <select class="form-control" name="project_zhijingly" id="project_zhijingly_{rand}">
                    <option value="" style="display: none;">-请选择-</option>
                    <option value="学校">学校</option>
                    <option value="央财">央财</option>
                    <option value="省财">省财</option>
                    <option value="市财">市财</option>
                    <option value="其它">其它</option>
                </select>
            </td>
        </tr>
        <tr>
            <td style="width: 5%" class="pad2">经费卡号：</td>
            <td><input class="form-control" name="financial_card_number" id="financial_card_number_{rand}" ></td>
        </tr>
        <tr>
            <td style="width: 5%" class="pad2">经费金额(元)：</td>
            <td><input type="number" class="form-control" name="balance_of_funds" id="balance_of_funds_{rand}" ></td>
        </tr>
        <tr>
            <td class="pad1">经费余额(元)：</td>
            <td><input type="number" class="form-control" name="amount_of_funds" id="amount_of_funds_{rand}" ></td>
        </tr>
        <tr>
            <td style="width: 5%" class="pad2">所属部门：</td>
            <td>
                <!--<input class="form-control" name="dept" id="dept_{rand}" >-->
                <select class="form-control" name="dept" id="dept_{rand}">
                    <option value="" style="display: none;">-请选择-</option>
                </select>
            </td>
        </tr>
        <tr>
            <td style="width: 5%" class="pad2">经费负责人：</td>
            <td><input class="form-control" name="person_in_charge" id="person_in_charge_{rand}" ></td>
        </tr>
        <tr>
            <td style="width: 5%" class="pad2">联系电话：</td>
            <td><input class="form-control" name="telphone" id="telphone_{rand}" ></td>
        </tr>
		<tr>
			<td colspan="2">
				<p><input name="file_ids" type="hidden" id="fileidview_{rand}-inputEl"></p>
				<span>拨付资料</span>
				<div id="view_fileidview_{rand}" style="width:100%;height:80px;border:1px #cccccc solid; background:white;overflow:auto"></div>
				<div id="fileupaddbtn" class="marS2"><a href="javascript:;" class="cbtn_" click="upload">＋添加文件</a></div>
			</td>
		</tr>
		<tr>
			<td align="left" width="4%">拨付时间：</td>
			<td align="left" style="width: 33%;">
                <div class="input-group">
                    <input readonly class="form-control" name="appropriation_time" id="dt1_{rand}" >
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