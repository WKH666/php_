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
                if(!bools){}
                bools=true;
            },
            itemclick:function(){
            },
            beforeload:function(){
            }
        });
        assessmentList = a;
        sessionStorage.removeItem('wcl_reload');
        sessionStorage.setItem('wcl_reload','2');
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
                console.log(d);
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
            </td>
            <td align="right" nowrap>&nbsp;
                <button class="btn btn-default bgGray" click="daochu,1" type="button" style="display: none">导出</button>
            </td>
        </tr>
    </table>

</div>
<div class="blank10"></div>
<div id="view_{rand}"></div>
