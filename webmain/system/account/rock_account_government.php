<?php if(!defined('HOST'))die('not access');?>
<script>
    $(document).ready(function(){
        {params}
        var atype=params.atype,zt=params.zt;
        if(!zt)zt='';
        var a = $('#view_{rand}').bootstable({
            tablename:'admin',params:{'atype':atype,'zt':zt},fanye:true,
            url:publicstore('{mode}','{dir}'),
            storeafteraction:'acgovernmentafter',storebeforeaction:'acgovernmentbefore',
            columns:[{
                text:'账号',dataIndex:'user'
            },{
                text:'姓名',dataIndex:'name'
            },{
                text:'单位',dataIndex:'deptname'
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
                    user:get("ag_user").value,
                    name:get("ag_name").value,
                    deptname:get("ag_deptname").value
                },true);
            },
            reset:function(){
                $('#ag_user').val('');
                $('#ag_name').val('');
                $('#ag_deptname').val('');
                a.setparams({
                    //需搜索的内容
                    user:'',
                    name:'',
                    deptname:''
                },true);
            }
        };
        js.initbtn(c);
        ag_check = function(t_checkid){
            var government_url = 'system,account,governmentcheck,modenum=government,government_id=' + t_checkid;
            addtabs({
                num:'checkgovernment',
                url:government_url,
                icons:'',
                name:'查看政府账号'
            });
            return false;
        };
        ag_edit = function(t_editid){
            layer.msg(t_editid);
        };
        ag_del = function(t_delid){
            layer.msg(t_delid);
        };
    });
</script>
<style>
    form[name='governmentform']{
        display: flex;
        flex-direction: row;
        align-items: center;
        flex-wrap: wrap;
    }
    form[name='governmentform'] .form-group{
        display: flex;
        flex-direction: row;
        align-items: center;
        margin: 10px 5px 10px 0px;
    }
    form[name='governmentform'] .form-group label{
        width: 10rem;
        text-align: center;
        margin: 10px 0;
    }
    .account-government{
        background-color: whitesmoke;
    }
    .ag_btngroup button{
        margin: 0 10px;
    }
    .ag_btngroup button:nth-of-type(1){
        margin-left: 20px;
    }
    .ag_btngroup button{
        border: none;
        outline: none;
    }
    .ag_btngroup button:hover, button:focus, button:link, button:active, button:visited{
        border: none !important;
        outline: none !important;
    }
</style>
<div class="account-government">
    <form name="governmentform">
        <div class="form-group">
            <label>账号：</label>
            <input type="text" class="form-control" id="ag_user">
        </div>
        <div class="form-group">
            <label>姓名：</label>
            <input type="text" class="form-control" id="ag_name">
        </div>
        <div class="form-group">
            <label>单位：</label>
            <input type="text" class="form-control" id="ag_deptname">
        </div>
        <div class="form-group ag_btngroup">
            <button type="button" class="btn btn-primary" id="ag_search" click="search">搜索</button>
            <button type="reset" class="btn btn-default" id="ag_reset" click="reset">重置</button>
            <button type="button" class="btn btn-primary" id="ag_add" click="add">新增</button>
            <button type="button" class="btn btn-primary" id="ag_daoru" click="daoru">导入</button>
        </div>
    </form>
</div>
<div class="blank10"></div>
<div id="view_{rand}"></div>