<?php defined('HOST') or die('not access');?>
<script >
    var arr ={'file1':'','file2':''};
    $(document).ready(function () {
        sessionStorage.clear();
        var c={
            addfile:function(){
                js.upload('_daorufile_excel{rand}',{maxup:'1','title':'选择Word文件',uptype:'doc|docx','urlparams':'noasyn:yes'});
            },
        };
        js.initbtn(c);
        _daorufile_excel{rand}=function(a,xid){
            var f = a[0];
            arr.file1=f;
            // console.log(a[0].filename);
            sessionStorage.setItem('filearr',JSON.stringify(arr)); //json.stringify 转换字符串
            $("#td02").text(a[0].filename);
            $("#td03").text('已上传');
        };
    });
    function addfile_two() {
        js.upload('_daorufile_excel_two{rand}',{maxup:'1','title':'选择Word文件',uptype:'doc|docx','urlparams':'noasyn:yes'});
    }
    _daorufile_excel_two{rand}=function(a,xid){
        var f = a[0];
        arr.file2=f;
        // console.log(f);
        // console.log(a[0].filename);
        sessionStorage.setItem('filearr',JSON.stringify(arr));
        $("#td04").text(a[0].filename);
        $("#td05").text('已上传');
    };


    function ba() {
        closenowtabs();
    }
    function draft() {
        var selectfir = $('#project_select_{rand} option:selected');
        var flow_id = selectfir.val();
        var select = $('#change_type option:selected');
        var change_type = select.val();
        var change_remark = $('#change_remark').val();
        var id1=(JSON.parse(sessionStorage.getItem('filearr')));//json.parse 转换为json
        var files_id =[id1.file1.id,id1.file2.id];
        if (id1!=null) {
            js.ajax(js.getajaxurl('request_add', 'fwork', 'main'),
                {files_id:files_id,upload_status:0,flow_id:flow_id,change_type:change_type,change_remark:change_remark},
                function (data) {
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
        var selectfir = $('#project_select_{rand} option:selected');
        var flow_id = selectfir.val();
        var select = $('#change_type option:selected');
        var change_type = select.val();
        var change_remark = $('#change_remark').val();
        var id1=(JSON.parse(sessionStorage.getItem('filearr')));
        var files_id =[id1.file1.id,id1.file2.id];
        if (id1!=null) {
            js.ajax(js.getajaxurl('request_add', 'fwork', 'main'),
                {files_id:files_id,upload_status:1,flow_id:flow_id,change_type:change_type,change_remark:change_remark},
                function (data) {
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

    /**
     * 获取查询条件
     */
    js.ajax(js.getajaxurl('getsreach','fwork','main'),{},function(ds){
        //选择项目
        $.each(ds,function(k,v) {
            $("#project_select_{rand}").append("<option value='"+v.id+"'>"+v.course_name+"</option>");
        });
    },'post,json');
</script>

<style>
    .three_columns{
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
    }
    .form-group{
        display: flex;
        flex-direction: row;
    }
    .form-group label{
        display: flex;
        justify-content: center;
        align-items: center;
        margin: 0;
        padding: 0;
    }
    .three_columns .form-group label{
        width: 13rem;
    }
    .one_columns .form-group label{
        width: 11rem;
    }
    .one_columns .form-group textarea{
        height: 10rem;
    }
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
        text-align: left;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
    }
    .three_button{
        width: 450px;
        height: 450px;
        position: relative;
        margin-left: 480px;
    }
    .three_button div{
        width: 200px;
        height: 200px;
        position: absolute;
        margin: auto;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
    }
</style>
<div>
    <div class="header_title">
        <p>填写信息</p>
    </div>
    <form class="one_columns">
        <div class="form-group">
            <label>选择项目:</label>
            <select id="project_select_{rand}" name="project_select" class=" selectpicker show-tick form-control">
                <option value="">请选择</option>
            </select>
        </div>
    </form>
    <form class="one_columns">
        <div class="form-group">
            <label>变更类型:</label>
            <select class=" selectpicker show-tick form-control" id="change_type">
                <option value="">请选择</option>
                <option value="0">变更项目负责人</option>
                <option value="1">变更或增加课题组成员</option>
                <option value="2">变更项目管理单位  </option>
                <option value="3">改变成果形式</option>
                <option value="4">改变项目名称</option>
                <option value="5">研究内容有重大调整</option>
                <option value="6">延期</option>
                <option value="7">撤项</option>
                <option value="8">其他</option>
            </select>
        </div>
    </form>
    <form class="one_columns">
        <div class="form-group">
            <label>变更说明:</label>
            <textarea class="form-control" id="change_remark" placeholder="请输入备注"></textarea>
        </div>
    </form>
    <div class="header_title">
        <p>附件资料</p>
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
            <td style="text-align: center">项目变更书</td>
            <td style="color:#C0C0C0" id="td02">支持扩展名：.doc .docx ，文件数最多一个，小于30M的文件</td>
            <td style="color:#FFA500 ;font-weight: bold" id="td03">未上传</td>
            <td><a style="text-decoration: none" click="addfile" >上传</td>
        </tr>
        <tr>
            <td style="text-align: center">变更后课题申报书</td>
            <td style="color:#C0C0C0" id="td04">上传变更的申报书，支持扩展名：.doc .docx ，文件数最多一个，小于30M的文件</td>
            <td style="color: #FFA500;font-weight: bold" id="td05">未上传</td>
            <td><a style="text-decoration: none" onclick="addfile_two()">上传</a></td>
        </tr>
        </tbody>
    </table>
    <div class="three_button">
        <div>
        <button type="button" class="btn btn-primary" onclick="draft()">保存草稿</button>
        <button type="button" class="btn btn-success" onclick="sub()">提交</button>
        <button type="button" class="btn btn-default" id="cancel" onclick="ba()">取消</button>
        </div>
    </div>
</div>
