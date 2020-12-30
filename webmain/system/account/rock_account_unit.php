<?php if(!defined('HOST'))die('not access');?>
<script>
    $(document).ready(function(){
        {params}
        var atype=params.atype,zt=params.zt;
        if(!zt)zt='';
        var a = $('#view_{rand}').bootstable({
            tablename:'admin',params:{'atype':atype,'zt':zt},fanye:true,
            url:publicstore('{mode}','{dir}'),
            storeafteraction:'acunitafter',storebeforeaction:'acunitbefore',
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
                    user:get("au_user").value,
                    name:get("au_name").value,
                    deptname:get("au_deptname").value,
                    res_dir:get("au_res_dir").value
                },true);
            },
            reset:function(){
                $('#au_user').val('');
                $('#au_name').val('');
                $('#au_deptname').val('');
                $('#au_res_dir').val('');
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
        au_check = function(t_checkid){
            var unit_url = 'system,account,unitcheck,modenum=unit,unit_id=' + t_checkid;
            addtabs({
                num:'checkunit',
                url:unit_url,
                icons:'',
                name:'查看单位账号'
            });
            return false;
        };
        au_edit = function(t_editid){
            layer.msg(t_editid);
        };
        au_del = function(t_delid){
            layer.msg(t_delid);
        };
    });
</script>
<style>
    form[name='unitform']{
        display: flex;
        flex-direction: row;
        align-items: center;
        flex-wrap: wrap;
    }
    form[name='unitform'] .form-group{
        display: flex;
        flex-direction: row;
        align-items: center;
        margin: 10px 5px 10px 0px;
    }
    form[name='unitform'] .form-group label{
        width: 10rem;
        text-align: center;
        margin: 10px 0;
    }
    .account-unit{
        background-color: whitesmoke;
    }
    .au_btngroup button{
        margin: 0 10px;
    }
    .au_btngroup button:nth-of-type(1){
        margin-left: 20px;
    }
    .au_btngroup button{
        border: none;
        outline: none;
    }
    .au_btngroup button:hover, button:focus, button:link, button:active, button:visited{
        border: none !important;
        outline: none !important;
    }
</style>
<div class="account-unit">
    <form name="unitform">
        <div class="form-group">
            <label>账号：</label>
            <input type="text" class="form-control" id="au_user">
        </div>
        <div class="form-group">
            <label>姓名：</label>
            <input type="text" class="form-control" id="au_name">
        </div>
        <div class="form-group">
            <label>单位：</label>
            <input type="text" class="form-control" id="au_deptname">
        </div>
        <div class="form-group">
            <label>研究方向：</label>
            <input type="text" class="form-control" id="au_res_dir">
        </div>
        <div class="form-group au_btngroup">
            <button type="button" class="btn btn-primary" id="au_search" click="search">搜索</button>
            <button type="reset" class="btn btn-default" id="au_reset" click="reset">重置</button>
            <button type="button" class="btn btn-primary" id="au_add" click="add">新增</button>
            <button type="button" class="btn btn-primary" id="au_daoru" click="daoru">导入</button>
        </div>
    </form>
</div>
<div class="blank10"></div>
<div id="view_{rand}"></div>
