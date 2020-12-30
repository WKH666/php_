<?php if(!defined('HOST'))die('not access');?>
<script >
    $(document).ready(function(){
        {params}
        var atype=params.atype,zt=params.zt;
        if(!zt)zt='';
        var a = $('#view_{rand}').bootstable({
            tablename:'flow_bill',params:{'atype':atype,'zt':zt},fanye:true,
            // url:js.getajaxurl('publicstore','information','main'),
            url:publicstore('{mode}','{dir}'),
            storeafteraction:'informationafter',storebeforeaction:'informationbefore',
            columns:[{
                text:'登记号',dataIndex:'sericnum'
            },{
                text:'项目名称',dataIndex:'project_name'
            },{
                text:'申报类型',dataIndex:'modename'
            },{
                text:'申报进度',dataIndex:'name'
            },{
                text:'申请时间',dataIndex:'applydt',sortable:true
            },{
                text:'操作',dataIndex:'caozuo'
            }],
        });

        var c = {
            reload:function(){
                a.reload();
            },
            search:function(){
                a.setparams({
                    //需搜索的内容
                    sericnum:get('sericnum_{rand}').value,
                    project_name:get('project_name_{rand}').value,
                    modename:get('modename_{rand}').value
                },true);
            },
            daochu:function(){
                a.exceldown(nowtabs.name);
            },
            searches:function(){
                $("#sericnum_{rand}").val('');
                $("#project_name_{rand}").val('');
                $("#modename_{rand}").val('');
                a.setparams({
                    //需搜索的内容
                    sericnum:'',
                    project_name:'',
                    modename:''
                },true);
            }

        };
        js.initbtn(c);
        opegs{rand}=function(){
            c.reload();
        }

    });
</script>
<style>
    #mytable{
        width: 100%;
    }
    #mytable tbody{
        display: flex;
        flex-direction: row;
        justify-content: space-between;
    }
    #mytable tr{
        display: flex;
        flex-direction: row;
        align-items: center;
        width: inherit;
    }
    #mytable .form-group{
        display: flex;
        flex-direction: row;
        justify-content: center;
        align-items: center;
        margin: 10px;
    }
    #mytable .form-group label{
        width: 100px;
        margin: 0;
        text-align: center;
    }
    #mytable .form-group button{
        margin: 0 5px;
    }
    #search{
        background-color: #108EE9;
        border-color: #108EE9;
        color: white;
    }
    #daochu{
        background-color: #108EE9;
        border-color: #108EE9;
        color: white;
    }
</style>
<div>
    <table id="mytable">
        <tbody>
        <tr>
            <td class="form-group">
                <label>登记号：</label>
                <input class="form-control" id="sericnum_{rand}" placeholder="请输入">
            </td>
            <td class="form-group">
                <label>项目名称：</label>
                <input class="form-control" id="project_name_{rand}" placeholder="请输入">
            </td>
            <td class="form-group">
                <label>申报类型：</label>
                <input class="form-control" id="modename_{rand}" placeholder="请输入">
            </td>
        </tr>
        <tr>
            <td class="form-group">
                <button class="btn btn-default" click="search" type="button" id="search">搜索</button>
                <button class="btn btn-default" click="searches" type="button" id="reset">重置</button>
                <button class="btn btn-default" click="daochu,1" type="button" id="daochu">导出</button>
            </td>
        </tr>
        </tbody>
    </table>

</div>
<div class="blank10"></div>
<div id="view_{rand}"></div>
<div class="tishi">提示：删除将会是彻底删除，不能恢复，请谨慎操作！如提示无删除权限，请到[流程模块→流程模块权限]上添加权限。<div>