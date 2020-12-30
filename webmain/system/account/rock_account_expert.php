<?php if(!defined('HOST'))die('not access');?>
<script>
    $(document).ready(function(){
        {params}
        var atype=params.atype,zt=params.zt;
        if(!zt)zt='';
        var a = $('#view_{rand}').bootstable({
            tablename:'admin',params:{'atype':atype,'zt':zt},fanye:true,
            url:publicstore('{mode}','{dir}'),
            storeafteraction:'acexpertafter',storebeforeaction:'acexpertbefore',
            columns:[{
                text:'账号',dataIndex:'user'
            },{
                text:'姓名',dataIndex:'name'
            },{
                text:'单位',dataIndex:'deptname'
            },{
                text:'研究方向',dataIndex:'res_dir'
            },{
                text:'电子邮箱',dataIndex:'email'
            },{
                text:'状态',dataIndex:'status'
            },{
                text:'注册时间',dataIndex:'adddt',sortable:true
            },{
                text:'操作',dataIndex:'caoz'
            }]
        });
        var c = {
            reload:function(){
                a.reload();
            },
            search:function(){
                a.setparams({
                    //需搜索的内容
                    user:get("ae_user").value,
                    name:get("ae_name").value,
                    deptname:get("ae_deptname").value,
                    res_dir:get("ae_res_dir").value
                },true);
            },
            reset:function(){
                $('#ae_user').val('');
                $('#ae_name').val('');
                $('#ae_deptname').val('');
                $('#ae_res_dir').val('');
                a.setparams({
                    //需搜索的内容
                    user:'',
                    name:'',
                    deptname:'',
                    res_dir:''
                },true);
            }
        };
        js.initbtn(c);
        ae_check = function(t_checkid){
            layer.msg(t_checkid);
        };
        ae_edit = function(t_editid){
            layer.msg(t_editid);
        };
        ae_del = function(t_delid){
            layer.msg(t_delid);
        };
    });
</script>
<style>
    form[name='expertform']{
        display: flex;
        flex-direction: row;
        align-items: center;
        flex-wrap: wrap;
    }
    form[name='expertform'] .form-group{
        display: flex;
        flex-direction: row;
        align-items: center;
        margin: 10px 5px 10px 0px;
    }
    form[name='expertform'] .form-group label{
        width: 10rem;
        text-align: center;
        margin: 10px 0;
    }
    .account-expert{
        background-color: whitesmoke;
    }
    .ae_btngroup button{
        margin:0 10px;
    }
    .ae_btngroup button:nth-of-type(1){
        margin-left: 20px;
    }
    .ae_btngroup button{
        border: none;
        outline: none;
    }
    .ae_btngroup button:hover, button:focus, button:link, button:active, button:visited{
        border: none !important;
        outline: none !important;
    }
</style>
<div class="account-expert">
    <form name="expertform">
        <div class="form-group">
            <label>账号：</label>
            <input type="text" class="form-control" id="ae_user">
        </div>
        <div class="form-group">
            <label>姓名：</label>
            <input type="text" class="form-control" id="ae_name">
        </div>
        <div class="form-group">
            <label>单位：</label>
            <input type="text" class="form-control" id="ae_deptname">
        </div>
        <div class="form-group">
            <label>研究方向：</label>
            <input type="text" class="form-control" id="ae_res_dir">
        </div>
        <div class="form-group ae_btngroup">
            <button type="button" class="btn btn-primary" id="ae_search" click="search">搜索</button>
            <button type="reset" class="btn btn-default" id="ae_reset" click="reset">重置</button>
            <button type="button" class="btn btn-primary" id="ae_add" click="add">新增</button>
            <button type="button" class="btn btn-primary" id="ae_daoru" click="daoru">导入</button>
        </div>
    </form>
</div>
<div class="blank10"></div>
<div id="view_{rand}"></div>