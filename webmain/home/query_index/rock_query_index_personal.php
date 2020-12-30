<?php if(!defined('HOST'))die('not access');?>
<script>
$(document).ready(function(){
    js.ajax(js.getajaxurl('getloginlog','query_index','home'),{},function(res){
        if(res.success ){
            if(res.rows){
                let perlog = res.rows;
                let rdir = perlog.rdir;
                $("#span_adminuser").text(perlog.user);
                $("#span_adminemail").text(perlog.email);
                $("#span_adminphone").text(perlog.mobile);
                $("#span_adminrelname").text(perlog.name);
                $("#span_locationunit").text(perlog.deptname);
                $("#span_workname").text(perlog.ranking);
                $("#span_researchplace").text(rdir[0]['res_dir']);
                $("#input_adminuser").val(perlog.user);
                $("#input_adminemail").val(perlog.email);
                $("#input_adminphone").val(perlog.mobile);
                $("#input_adminrelname").val(perlog.name);
                $("#input_locationunit").val(perlog.deptname);
                $("#input_workname").val(perlog.ranking);
                $("#input_researchplace").val(rdir[0]['res_dir']);
            }
        }
    },'post,json');

    for(var i = 0; i < $("span").length; i++){
        $("span")[i].className = '';
    }
    for(var j = 0; j < $(".form-group span").length; j++){
        $(".form-group span").css('display','block');
    }
    $("#cdata").on('click',function(){
        for(var j = 0; j < $(".form-group input").length; j++){
            $(".form-group input").css('display','block');
        }
        for(var j = 0; j < $(".form-group span").length; j++){
            $(".form-group span").css('display','none');
        }
        $("#datak").css('display','block');
        $("#datar").css('display','block');
    });
    $("#cpwd").on('click',function(){
        addtabs({num:'pass',url:'system,geren,pass',name:'修改密码',hideclose:true});
        return false;
    });
    $("#datak").on('click',function(){
        let formdata = {
            'user':$("#input_adminuser").val(),
            'email':$("#input_adminemail").val(),
            'mobile':$("#input_adminphone").val(),
            'name':$("#input_adminrelname").val(),
            'deptname':$("#input_locationunit").val(),
            'ranking':$("#input_workname").val(),
            'res_dir':$("#input_researchplace").val()
        };
        js.ajax(js.getajaxurl('updateloginlog','query_index','home'),formdata,function(res){
            if(res.code == 200){
                layer.msg(res.msg);
            }else{
                layer.msg(res.msg);
            }
        });
    });
    $("#datar").on('click',function(){
        for(var j = 0; j < $(".form-group input").length; j++){
            $(".form-group input").css('display','none');
        }
        for(var j = 0; j < $(".form-group span").length; j++){
            $(".form-group span").css('display','block');
        }
        $("#datak").css('display','none');
        $("#datar").css('display','none');
    });
});
</script>
<style>
    .header_title{
        display: flex;
        flex-direction: row;
        align-items: center;
    }
    .header_title p{
        font-size: 24px;
    }
    .header_title form[name='modifyform']{
        display: flex;
        flex-direction: row;
        align-items: center;
        margin: 0 20px;
    }
    .header_title form[name='modifyform'] .form-group{
        margin: 0 10px;
    }
    .datalist form[name='personaol_data']{
        display: flex;
        flex-direction: row;
        flex-wrap: wrap;
        padding: 10px;
    }
    .datalist form[name='personaol_data'] .form-group{
        display: flex;
        flex-direction: row;
        align-items: center;
        margin: 10px;
    }
    .datalist form[name='personaol_data'] .form-group label{
        width: 10rem;
        margin: 0;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .form-group input{
        display: none;
    }
    .form-group span{
        display: none;
    }
    #datak,#datar{
        display: none;
        margin-right: 20px;
    }
</style>
<div class="container-personal">
    <div class="header_title">
        <p>个人信息</p>
        <form name="modifyform">
            <div class="form-group"><button type="button" class="btn btn-default" id="cdata"><i class="glyphicon glyphicon-edit"></i>修改资料</button></div>
            <div class="form-group"><button type="button" class="btn btn-default" id="cpwd"><i class="glyphicon glyphicon-edit"></i>修改密码</button></div>
        </form>
    </div>
    <div class="datalist">
        <form name="personaol_data">
            <div class="form-group">
                <label>用户账号:</label>
                <span class="form-control" id="span_adminuser"></span>
                <input type="text" class="form-control" id="input_adminuser" value="">
            </div>
            <div class="form-group">
                <label>电子邮箱:</label>
                <span class="form-control" id="span_adminemail"></span>
                <input type="text" class="form-control" id="input_adminemail" value="">
            </div>
            <div class="form-group">
                <label>手机号码:</label>
                <span class="form-control" id="span_adminphone"></span>
                <input type="text" class="form-control" id="input_adminphone" value="">
            </div>
            <div class="form-group">
                <label>真实姓名:</label>
                <span class="form-control" id="span_adminrelname"></span>
                <input type="text" class="form-control" id="input_adminrelname" value="">
            </div>
            <div class="form-group">
                <label>关联单位:</label>
                <span class="form-control" id="span_locationunit"></span>
                <input type="text" class="form-control" id="input_locationunit" value="">
            </div>
            <div class="form-group">
                <label>职务职称:</label>
                <span class="form-control" id="span_workname"></span>
                <input type="text" class="form-control" id="input_workname" value="">
            </div>
            <div class="form-group">
                <label>研究方向:</label>
                <span class="form-control" id="span_researchplace"></span>
                <input type="text" class="form-control" id="input_researchplace" value="">
            </div>
            <div class="form-group">
                <button type="button" class="btn btn-primary" id="datak">保存</button>
                <button type="button" class="btn btn-default" id="datar">取消</button>
            </div>
        </form>
    </div>
</div>
