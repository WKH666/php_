<?php if(!defined('HOST'))die('not access');?>
<script>
    $(document).ready(function(){
        {params}
        var atype=params.atype,zt=params.zt;
        if(!zt)zt='';
        var a = $('#view_{rand}').bootstable({
            tablename:'flow_bill',params:{'atype':atype,'zt':zt},fanye:true,
            url:js.getajaxurl('apply_sup','process_supervision','main', {}),
            columns:[{
                text:'年度',dataIndex:'year'
            },{
                text:'申报类型',dataIndex:'table'
            },{
                text:'姓名',dataIndex:'name'
            },{
                text:'单位',dataIndex:'deptname'
            },{
                text:'关联项目数',dataIndex:'count'
            },{
                text:'操作',dataIndex:'caoz'
            }],
        });

        var c = {
            reload:function(){
                a.reload();
            },
            search:function(){
                a.setparams({
                    //需搜索的内容
                    table:$('#table').val(),
                    name:$('#name').val(),
                },true);
            },
            reset:function(){
                $("#table").val('');
                $("#name").val('');
                a.setparams({
                    //需搜索的内容
                    table:'',
                    name:'',
                },true);
            },
        };
        js.initbtn(c);
        readsupDetail = function(uid){
            var results_url = 'flow,input,sup_detail,modenum=expertresults,uid=' + uid ;
            addtabs({
                num:'expertresults',
                url:results_url,
                icons:'',
                name:'申报监管详情'
            });
            return false;
        };
    });
</script>
<style>
    .cross-form{
        background:#F7F7F7;
    }
    .cross-form form{
        display: flex;
        flex-direction: row;
        flex-wrap: wrap;
        padding-top: 10px;
    }
    .form-group{
        display: flex;
        flex-direction: row;
        /*margin-right: 15px;*/
    }
    .form-group:nth-last-child(2){
        margin-right: 20px;
    }
    .form-group label{
        width:15rem;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .form-group button{
        margin: 0 10px;
    }
    #search,#downout{
        background: #108EE9;
        color: #fff;
        border-color: #108EE9;
    }
    .tips{
        text-indent: 8em;
    }
    .modal-backdrop{
        z-index:0;
        display: none;
    }
    .modal-header{
        border-bottom: 0px;
    }
    .modal-footer{
        border-top: 0px;
    }
</style>
<div class="cross-form">
    <form>
        <div class="form-group">
            <label>申报类型:</label>
            <input type="text" class="form-control" id="table"  placeholder="请输入">
        </div>
        <div class="form-group">
            <label>负责人:</label>
            <input type="text" class="form-control" id="name"  placeholder="请输入">
        </div>
        <div class="form-group">
            <button type="button" class="btn btn-default" id="search" click="search">搜索</button>
            <button type="reset" class="btn btn-default" id="reset">重置</button>
        </div>
    </form>
</div>
<div class="blank10"></div>
<div id="view_{rand}"></div>