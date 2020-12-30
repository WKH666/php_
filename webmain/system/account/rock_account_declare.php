<?php if(!defined('HOST'))die('not access');?>
<script>
    $(document).ready(function(){
        {params}
        var atype=params.atype,zt=params.zt;
        if(!zt)zt='';
        var a = $('#view_{rand}').bootstable({
            tablename:'admin',params:{'atype':atype,'zt':zt},fanye:true,
            url:publicstore('{mode}','{dir}'),
            storeafteraction:'acdeclareafter',storebeforeaction:'acdeclarebefore',
            columns:[{
                text:'账号',dataIndex:'user'
            },{
                text:'姓名',dataIndex:'name'
            },{
                text:'单位',dataIndex:'deptname'
            },{
                text:'电子邮箱',dataIndex:'email'
            },{
                text:'状态',dataIndex:'status_text'
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
                    user:get("ad_user").value,
                    name:get("ad_name").value,
                    deptname:get("ad_deptname").value
                },true);
            },
            reset:function(){
                $('#ad_user').val('');
                $('#ad_name').val('');
                $('#ad_deptname').val('');
                a.setparams({
                    //需搜索的内容
                    user:'',
                    name:'',
                    deptname:''
                },true);
            }
        };
        js.initbtn(c);
        ad_check = function(t_checkid){
            var declare_url = 'system,account,declarecheck,modenum=declare,declare_id=' + t_checkid;
            addtabs({
                num:'checkdeclare',
                url:declare_url,
                icons:'',
                name:'查看申报账号'
            });
            return false;
        };
        ad_edit = function(t_editid){
            layer.msg(t_editid);
        };
        ad_del = function(t_delid){
            layer.msg(t_delid);
        };
    });
</script>
<style>
    form[name='declareform']{
        display: flex;
        flex-direction: row;
        align-items: center;
        flex-wrap: wrap;
    }
    form[name='declareform'] .form-group{
        display: flex;
        flex-direction: row;
        align-items: center;
        margin: 10px 5px 10px 0px;
    }
    form[name='declareform'] .form-group label{
        width: 10rem;
        text-align: center;
        margin: 10px 0;
    }
    .account-declare{
        background-color: whitesmoke;
    }
    .ad_btngroup button:nth-of-type(1){
        margin:0 20px;
    }
    .ad_btngroup button{
        border: none;
        outline: none;
    }
    .ad_btngroup button:hover, button:focus, button:link, button:active, button:visited{
        border: none !important;
        outline: none !important;
    }
</style>
<div class="account-declare">
    <form name="declareform">
        <div class="form-group">
            <label>账号：</label>
            <input type="text" class="form-control" id="ad_user">
        </div>
        <div class="form-group">
            <label>姓名：</label>
            <input type="text" class="form-control" id="ad_name">
        </div>
        <div class="form-group">
            <label>单位：</label>
            <input type="text" class="form-control" id="ad_deptname">
        </div>
        <div class="form-group ad_btngroup">
            <button type="button" class="btn btn-primary" id="ad_search" click="search">搜索</button>
            <button type="reset" class="btn btn-default" id="ad_reset" click="reset">重置</button>
        </div>
    </form>
</div>
<div class="blank10"></div>
<div id="view_{rand}"></div>