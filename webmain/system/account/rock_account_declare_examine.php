<?php if(!defined('HOST'))die('not access');?>
<script>
    $(document).ready(function(){
        {params}
        var atype=params.atype,zt=params.zt;
        if(!zt)zt='';
        var a = $('#view_{rand}').bootstable({
            tablename:'admin',params:{'atype':atype,'zt':zt},fanye:true,
            url:publicstore('{mode}','{dir}'),
            storeafteraction:'acdeclare_examineafter',storebeforeaction:'acdeclare_examinebefore',
            columns:[{
                text:'账号',dataIndex:'user'
            },{
                text:'姓名',dataIndex:'name'
            },{
                text:'单位',dataIndex:'deptname'
            },{
                text:'电子邮箱',dataIndex:'email'
            },{
                text:'审核状态',dataIndex:'examine_status'
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
                    user:get("de_user").value,
                    name:get("de_name").value,
                    deptname:get("de_deptname").value
                },true);
            },
            reset:function(){
                $('#de_user').val('');
                $('#de_name').val('');
                $('#de_deptname').val('');
                a.setparams({
                    //需搜索的内容
                    user:'',
                    name:'',
                    deptname:''
                },true);
            }
        };
        js.initbtn(c);
        de_examine = function(t_checkid){
            layer.msg(t_checkid);
        };
    });
</script>
<style>
    form[name='declare_examineform']{
        display: flex;
        flex-direction: row;
        align-items: center;
        flex-wrap: wrap;
    }
    form[name='declare_examineform'] .form-group{
        display: flex;
        flex-direction: row;
        align-items: center;
        margin: 10px 5px 10px 0px;
    }
    form[name='declare_examineform'] .form-group label{
        width: 10rem;
        text-align: center;
        margin: 10px 0;
    }
    .account-declare_examine{
        background-color: whitesmoke;
    }
    .de_btngroup button{
        margin: 0 10px;
    }
    .de_btngroup button:nth-of-type(1){
        margin-left: 20px;
    }
    .de_btngroup button{
        border: none;
        outline: none;
    }
    .de_btngroup button:hover, button:focus, button:link, button:active, button:visited{
        border: none !important;
        outline: none !important;
    }
</style>
<div class="account-declare_examine">
    <form name="declare_examineform">
        <div class="form-group">
            <label>账号：</label>
            <input type="text" class="form-control" id="de_user">
        </div>
        <div class="form-group">
            <label>姓名：</label>
            <input type="text" class="form-control" id="de_name">
        </div>
        <div class="form-group">
            <label>单位：</label>
            <input type="text" class="form-control" id="de_deptname">
        </div>
        <div class="form-group de_btngroup">
            <button type="button" class="btn btn-primary" id="de_search" click="search">搜索</button>
            <button type="reset" class="btn btn-default" id="de_reset" click="reset">重置</button>
        </div>
    </form>
</div>
<div class="blank10"></div>
<div id="view_{rand}"></div>
