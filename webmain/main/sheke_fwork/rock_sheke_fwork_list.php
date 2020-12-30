<?php if(!defined('HOST'))die('not access');?>
<script >
$(document).ready(function(){
	{params}
	var atype=params.atype;
	var bools=false;
	var a = $('#view_{rand}').bootstable({
		tablename:'flow_bill',params:{'atype':atype},fanye:true,
		url:publicstore('{mode}','{dir}'),
		storeafteraction:'flowbillafter',storebeforeaction:'flowbillbefore',
		columns:[{
			text:'登记号',dataIndex:'sericnum'
		},{
            text:'项目名称',dataIndex:'project_name'
        },{
            text:'申报类型',dataIndex:'apply_type'
        },{
            text:'申报进度',dataIndex:'apply_progress'
        },{
            text:'状态',dataIndex:'status',sortable:true
        },{
			text:'申报日期',dataIndex:'applydt',sortable:true
		},{
			text:'操作时间',dataIndex:'optdt',sortable:true
		},{
            text:'操作',dataIndex:'caozuo',callback:'opegs{rand}'
        }],
		celldblclick:function(){
			c.view();
		},
		load:function(a){
			if(!bools){
				//js.setselectdata(get('mode_{rand}'),a.flowarr,'id');
			}
			bools=true;
		},
		itemclick:function(){
			//btn(false);
		},
		beforeload:function(){
			//btn(true);
		}
	});
    assessmentList = a;
    sessionStorage.removeItem('wcl_reload');
    sessionStorage.setItem('wcl_reload','1');
	/*function btn(bo){
		get('xiang_{rand}').disabled = bo;
	}*/
	xing{rand}=function(oi){
		a.changedata = a.getData(oi);
		c.view();
	}
	var c = {
		reload:function(){
			a.reload();
		},
		view:function(){
			var d=a.changedata;
			openxiangs(d.modename,d.modenum,d.id,'opegs{rand}');
		},
		search:function(){
			a.setparams({
                sericnum:get('sericnum_{rand}').value,
                project_name:get('project_name_{rand}').value,
                apply_type:get('apply_type_{rand}').value
			},true);
		},
		clickdt:function(o1, lx){
			$(o1).rockdatepicker({initshow:true,view:'date',inputid:'dt'+lx+'_{rand}'});
		},
		daochu:function(){
			a.exceldown(nowtabs.name);
		},
		changlx:function(o1,lx){
			$("button[id^='state{rand}']").removeClass('active');
			$('#state{rand}_'+lx+'').addClass('active');
			this.search();
		}
	};
	js.initbtn(c);
	$('#mode_{rand}').change(function(){
		c.search();
	});
	opegs{rand}=function(){
		c.reload();
	};
	
	if(atype=='mywtg'){
		$('#stewwews{rand}').hide();
	}

	//已处理的需要导出功能
    if (atype === 'yichuli') {
        $('.bgGray').show();
    }

});
</script>
<div>
	<table width="100%">
	<tr>
        <!--<td nowrap>
            <select style="width:150px" id="mode_{rand}" class="form-control" ><option value="0">-选择模块-</option></select>
        </td>
        <td  style="padding-left:10px">
            <div style="width:140px"  class="input-group">
                <input placeholder="申请日期" readonly class="form-control" id="dt1_{rand}" >
                <span class="input-group-btn">
                    <button class="btn btn-default" click="clickdt,1" type="button"><i class="icon-calendar"></i></button>
                </span>
            </div>
        </td>
        <td  style="padding-left:10px">
            <input class="form-control" style="width:180px" id="key_{rand}"   placeholder="姓名/部门/单号">
        </td>-->
        <td  style="padding-left:10px">
            <input class="form-control" style="width:180px" id="sericnum_{rand}"   placeholder="登记号">
        </td>
        <td  style="padding-left:10px">
            <input class="form-control" style="width:180px" id="project_name_{rand}"   placeholder="项目名称">
        </td>
        <td  style="padding-left:10px">
            <input class="form-control" style="width:180px" id="apply_type_{rand}"   placeholder="申报类型">
        </td>
	
        <td  style="padding-left:10px">
            <button class="btn btn-default" click="search" type="button">搜索</button>
        </td>
        <td  width="80%" style="padding-left:10px">

            <!--<div id="stewwews{rand}" class="btn-group">
            <button class="btn btn-default" id="state{rand}_" click="changlx," type="button">全部状态</button>
            <button class="btn btn-default" id="state{rand}_0" click="changlx,0" type="button">待审核</button>
            <button class="btn btn-default" id="state{rand}_1" style="color:green" click="changlx,1" type="button">已审核</button>
            <button class="btn btn-default" id="state{rand}_2" style="color:red" click="changlx,2" type="button">未通过</button>
            </div>-->
        </td>


        <td align="right" nowrap>
            <!--<button class="btn btn-default" id="xiang_{rand}" click="view" disabled type="button">详情</button>--> &nbsp;
            <button class="btn btn-default bgGray" click="daochu,1" type="button" style="display: none">导出</button>
        </td>
	</tr>
	</table>
</div>
<div class="blank10"></div>
<div id="view_{rand}"></div>
