<?php defined('HOST') or die('not access'); ?>
<script>
    $(document).ready(function () {
        {params}
        let annualreport_id = params.annualreport_id;
        console.log(annualreport_id);
        sessionStorage.removeItem('filearr');
        var c={
            init: function () {
                js.ajax(js.getajaxurl('get_edit', 'annual_report', 'main'), {results_id: annualreport_id}, function (data) {
                    c.initshow(data);
                }, 'post,json');
            },
            initshow: function (data) {
                console.log(111);
                console.log(data['upload_filetype']);
                $("#td01").text(data['upload_filetype']);
                $("#td02").text(data['filename']);
                $("#td03").text('已上传');
            },
            addfile:function(){
                js.upload('_daorufile_excel{rand}',{maxup:'1','title':'选择Excel文件',uptype:'xls|xlsx','urlparams':'noasyn:yes'});
            },
        };
        c.init();
        js.initbtn(c);
        _daorufile_excel{rand}=function(a,xid){
            var f = a[0];
            console.log(f);
            console.log(a[0].id);
            console.log(annualreport_id);
            sessionStorage.setItem('filearr',JSON.stringify(f));
            $("#td01").text(a[0].upload_filetype);
            $("#td02").text(a[0].filename);
            $("#td03").text('已上传');
        }

    });

    function ba() {
        closenowtabs();
    }

    function draft() {
        {params}
        let annualreport_id = params.annualreport_id;
        var id1=(JSON.parse(sessionStorage.getItem('filearr')));
        console.log(id1);
        if (id1!=null) {
            js.ajax(js.getajaxurl('getsave', 'annual_report', 'main'), {results_id: id1.id,del_id:annualreport_id,upload_status:0}, function (data) {
                if (data){
                   closenowtabs();
                    try {
                        assessmentList.reload();
                    }catch (e) {

                    }
                }else {
                    alert("文件上传失败");
                }
            }, 'post,json');
        }else {
            alert('文件未上传！');
        }
    }
    function sub() {
        {params}
        let annualreport_id = params.annualreport_id;
        var id1=(JSON.parse(sessionStorage.getItem('filearr')));
        console.log(id1);
        if (id1!=null) {
            js.ajax(js.getajaxurl('getsave', 'annual_report', 'main'), {results_id: id1.id,del_id:annualreport_id,upload_status:1}, function (data) {
                if (data){
                    closenowtabs();
                    try {
                        assessmentList.reload();
                    }catch (e) {
                    }
                }else {
                    alert("文件上传失败");
                }
            }, 'post,json');
        }else {
            alert('文件未上传！');
        }
    }
</script>
<style>
    .header_title{
        background: #CDE3F1;
        border-radius: 5px;
    }
    .header_title p{
        padding: 5px 0;
    }
    .header_title:nth-of-type(2) p{
        margin: 0;
    }
    #results_table{
        width:100%;
    }
    #results_table thead,tbody tr td{
        height: 30px;
    }
    #results_table thead tr td{
        text-align: center;
        background: #F2F2F2;
    }
    #results_table tbody{}
    #results_table tbody tr td{
        text-align: center;
        border: 1px solid #d1d1d1;
        box-sizing: border-box;
    }
    #results_table tbody tr td:nth-of-type(1){
        text-align: center;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
    }
    p {
        margin: 0 0 0;
         }
    .one_columns .form-group label {
        width: 11rem;
    }

    .one_columns .form-group textarea {
        height: 10rem;
    }
    #td03{
        color: orange
    }
</style>
<div class="header_title">
    <p>年度报告</p>
</div>
<table id="results_table" cellpadding="0" cellspacing="0">
    <thead>
    <tr>
        <td>附件类型</td>
        <td>文件名称</td>
        <td>上传状态</td>
        <td>操作</td>
    </tr>
    </thead>
    <tbody id="results_tbody">
        <tr>
            <td id="td01"></td>
            <td id="td02">支持扩展名：.xlsx.xls的文件</td>
            <td id="td03">未上传</td>
            <td>
                <a href="#" id="upexcelbtn{rand}" click="addfile" style="color: #3D8EDB;text-decoration: none" >上传文件</a>
               </td>
        </tr>
    </tbody>
</table>
<form class="one_columns">
    <div class="form-group" style="margin-left: 8px">
        <button class="btn btn-primary btn-sm" type="button" onclick="draft();" id="s" style="margin: 25px" >保存草稿</button>
        <button class="btn btn-success btn-sm" type="button" onclick="sub();" >提交</button>
        <button class="btn-sm" type="button" style="margin-left: 20px" onclick="ba();">取消</button>
    </div>
</form>

