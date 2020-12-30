<?php if (!defined('HOST')) die('not access'); ?>
<link rel="stylesheet" type="text/css" href="mode/bootstrap3.3/css/bootstrap.min.css"/>
<script src="mode/bootstrap3.3/js/bootstrap.min.js"></script>

<style>
    .page_header {
        width: 100%;
        height: 35px;
        background-color: #CDE3F1;
        border-radius: 5px;
        display: flex;
        flex-direction: row;
        align-items: center;
    }

    .page_header p {
        text-align: left;
        text-indent: 10px;
        margin: 0;
    }

    .form_div {
        width: 100%;
        display: flex;
        flex-direction: row;
        margin-left: 10px;
        margin-top: 15px;
    }

    .form-group {
        width: 30%;
        display: flex !important;
        flex-direction: row;
        align-items: center;
    }

    .left_label {
        width: 27%;
        text-align: right;
    }

    .form-control {
        width: 65% !important;
    }

    .textarea_div {
        width: 100%;
        margin-top: 15px;
    }

    .textarea_div p {
        text-indent: 0;
        margin: 0;
        font-size: 14px;
        color: #333333;
        font-weight: bold;
        margin-bottom: 10px;
        margin-left: 6px;
    }

    .textarea_div textarea {
        margin-left: 6px;
        width: 100% !important;
    }

    .check_record_table {
        margin-left: 6px;
        margin-top: 45px;
        margin-bottom: 250px;
    }

    .presubmit_btn {
        background-color: #108ee9;
        color: white !important;
        box-shadow: none !important;
        outline: none !important;
        margin-right: 20px;
        padding: 5px 15px;
    }

    .submit_btn {
        background-color: #009966;
        color: white !important;
        margin-right: 20px;
        padding: 5px 15px;
        outline: none !important;
    }

    .cancel_btn {
        background-color: #f3f3f3;
        padding: 5px 15px;
        outline: none !important;
    }

</style>


<div style="width: 100%;height: 100%;padding: 10px 0;">
    <div class="page_header">
        <p>认证信息</p>
    </div>
    <form id="my_form" class="form-inline">
        <div class="form_div">
            <div class="form-group">
                <label for="input_name" class="left_label">姓名：</label>
                <input type="text" class="form-control" id="input_name" name="input_name" placeholder="" readonly>
            </div>
            <div class="form-group">
                <label style="margin-bottom: 0;" class="left_label">性别：</label>
                <label class="radio-inline">
                    <input type="radio" name="input_sex" id="sex_man" value="男" readonly> 男
                </label>
                <label class="radio-inline">
                    <input type="radio" name="input_sex" id="sex_woman" value="女" checked readonly> 女
                </label>
            </div>
            <div class="form-group">
                <label for="input_name" class="left_label">联系电话：</label>
                <input type="tel" class="form-control" id="input_tel" name="input_tel" placeholder="" readonly>
            </div>
        </div>

        <div class="form_div">
            <div class="form-group">
                <label for="input_name" class="left_label">电子邮箱：</label>
                <input type="email" class="form-control" id="input_email" name="input_email" placeholder="" readonly>
            </div>
            <div class="form-group">
                <label for="input_name" class="left_label">单位：</label>
                <input type="text" class="form-control" id="input_company" name="input_company" placeholder="" readonly>
            </div>
            <div class="form-group">
                <label for="input_name" class="left_label">职务/职称：</label>
                <input type="text" class="form-control" id="input_position" name="input_position" placeholder="" readonly>
            </div>
        </div>

        <div class="form_div">
            <div class="form-group">
                <label for="input_name" class="left_label">毕业学科：</label>
                <!--<input type="text" class="form-control" id="graduate_project" name="graduate_project"
                       placeholder="">-->
                <select class="form-control" id="graduate_project" name="graduate_project">
                    <option value="">请选择</option>
                </select>
            </div>
            <div class="form-group">
                <label for="input_name" class="left_label">研究方向：</label>
                <input type="text" class="form-control" id="research_directions" name="research_direction"
                       placeholder="">
            </div>
        </div>

        <div class="form_div">
            <div class="form-group">
                <label for="input_name" class="left_label">民族：</label>
                <input type="text" class="form-control" id="input_nation" name="input_nation" placeholder="请填写">
            </div>
            <div class="form-group">
                <label for="input_datetime" class="left_label">出生日期：</label>
                <input type="date" class="form-control" id="input_datetime" name="input_datetime">
            </div>
            <div class="form-group">
                <label for="input_location" class="left_label">籍贯：</label>
                <input type="text" class="form-control" id="input_location" name="input_location" placeholder="请填写">
            </div>
        </div>

        <div class="form_div">
            <div class="form-group">
                <label for="input_position2" class="left_label">职务/职称：</label>
                <input type="text" class="form-control" id="input_position2" name="input_position2" placeholder="请填写">
            </div>

            <div class="form-group">
                <label for="politic_face" class="left_label">政治面貌：</label>
                <input type="text" class="form-control" id="politic_face" name="politic_face" placeholder="请填写">
            </div>

            <div class="form-group">
                <label for="graduate_school" class="left_label">毕业院校：</label>
                <input type="text" class="form-control" id="graduate_school" name="graduate_school" placeholder="请填写">
            </div>
        </div>

        <div class="form_div">
            <div class="form-group">
                <label for="input_academic" class="left_label">学历学位：</label>
                <input type="text" class="form-control" id="input_academic" name="input_academic" placeholder="请填写">
            </div>

            <div class="form-group">
                <label for="input_address" class="left_label">通信地址：</label>
                <input type="text" class="form-control" id="input_address" name="input_address" placeholder="请填写">
            </div>
        </div>

        <div class="textarea_div">
            <p>社会兼职情况：</p>
            <textarea class="form-control" rows="5" name="part_time_jobs" id="part_time_jobs"></textarea>
        </div>
        <div class="textarea_div">
            <p>个人简历：</p>
            <textarea class="form-control" rows="5" name="curriculum_vitae" id="curriculum_vitae"></textarea>
        </div>
        <div class="textarea_div">
            <p>学科专业主要成绩、主要成果、承担科研项目及获奖情况（近5年）：</p>
            <textarea class="form-control" rows="5" name="achievements" id="achievements"></textarea>
        </div>
        <div class="textarea_div">
            <p>项目评审经历：</p>
            <textarea class="form-control" rows="5" name="project_review" id="project_review"></textarea>
        </div>

        <div style="position: fixed;bottom: 15px;left: 50%;z-index: 999;">
            <button class="btn presubmit_btn" type="button" onclick="draftExpertInfo()">保存草稿</button>
            <button class="btn  submit_btn" type="button" onclick="saveExpertInfo()">提交</button>
            <button class="btn  cancel_btn" type="button" onclick="close_tabs()">取消</button>
        </div>
    </form>

    <table class="table table-striped table-bordered table-hover check_record_table" id="check_record_table">
     <!--   <thead>
        <tr>
            <th style="width: 50px;">序号</th>
            <th style=" text-align: center">审核结果</th>
            <th style=" text-align: center">审核意见</th>
            <th style=" text-align: center">审核人</th>
        </tr>
        </thead>-->
    </table>

</div>


<script>
    $(document).ready(function () {
        getUserInfo();
        getSubject();
        getOtherInfo();

        a = $('#check_record_table').bootstable({
            url: js.getajaxurl('personal_expertRecord', 'expert_manage', 'main'),
            tablename: 'expert_record',
            pageSize:4,
            fanye: true,
            celleditor: true,
            storeafteraction: 'expertcheckafter',
            columns: [
                {
                    text: '审核结果', dataIndex: 'opt_status', sortable: true
                }, {
                    text: '审核意见', dataIndex: 'audit_opinion', sortable: true
                }, {
                    text: '审核人', dataIndex: 'user', sortable: true
                }, {
                    text: '审核时间', dataIndex: 'opt_time', sortable: true
                },]
        });

    });
    //学科分类
    function getSubject() {
        $.ajax({
            url: './?a=subject_classification&m=project_comment&d=main&ajaxbool=true',
            type: 'post',
            dataType: 'json',
            success: function (res) {
                if (res.code == '1') {
                    $.each(res.rows, function(k, v) {
                        $("#graduate_project").append("<option value='" + v.name + "'>" + v.name + "</option>");
                    });
                } else {
                    console.log(res.msg);
                }
            }
        });
    }

    //获取用户基本信息
    function getUserInfo() {
        $.ajax({
            url: './?a=getuserinfo&m=userinfo&d=main&ajaxbool=true', /*接口域名地址*/
            type: 'post',
            dataType: 'json',
            success: function (res) {
                //console.log(res);
                if (res.code == '1') {
                    $('#input_name').val(res.rows.name);
                    $('input:radio[value=' + res.rows.sex + ']').attr("checked", "checked");
                    $('#input_tel').val(res.rows.mobile);
                    $('#input_email').val(res.rows.email);
                    $('#input_company').val(res.rows.deptname);
                    $('#input_position').val(res.rows.ranking);
                } else {
                    console.log(res.msg);
                }
            }
        });
    }

    //获取用户其他的专家认证信息
    function getOtherInfo() {
        $.ajax({
            url: './?a=getOtherInfo&m=userinfo&d=main&ajaxbool=true', /*接口域名地址*/
            type: 'post',
            dataType: 'json',
            success: function (res) {
                console.log(res);
                if (res.code == '1') {
                    $('#research_directions').val(res.rows.research_direction);
                    $('#graduate_project').val(res.rows.graduate_project);
                    $('#input_nation').val(res.rows.nation);
                    $('#input_datetime').val(res.rows.birth_date);
                    $('#input_location').val(res.rows.birth_place);
                    $('#input_position2').val(res.rows.position2);
                    $('#politic_face').val(res.rows.politic_face);
                    $('#graduate_school').val(res.rows.graduate_school);
                    $('#input_academic').val(res.rows.academic_degree);
                    $('#input_address').val(res.rows.address);
                    $('#part_time_jobs').val(res.rows.part_time_jobs);
                    $('#curriculum_vitae').val(res.rows.curriculum_vitae);
                    $('#achievements').val(res.rows.achievements);
                    $('#project_review').val(res.rows.project_review);
                } else {
                    console.log(res.msg);
                }
            }
        });
    }

    //提交表单
    function saveExpertInfo() {
        var formData = $.param({"is_draft": 0,}) + "&" + $('#my_form').serialize();
        $.ajax({
            url: './?a=saveExpertInfo&m=userinfo&d=main&ajaxbool=true',
            type: 'post',
            dataType: 'json',
            data: formData,
            success: function (res) {
                if (res.code == 1) {
                    layer.msg('专家认证信息提交成功');
                    close_tabs();
                }else{
                    layer.msg(res.msg);
                }
            },
            error: function (res) {
                layer.msg('专家认证信息提交失败');
            }
        }   )
    }

    //保存草稿
    function draftExpertInfo() {
        var formData = $.param({"is_draft": 1,}) + "&" + $('#my_form').serialize();
        $.ajax({
            url: './?a=draftExpertInfo&m=userinfo&d=main&ajaxbool=true',
            type: 'post',
            dataType: 'json',
            data: formData,
            success: function (res) {
                console.log(res);
                if (res.code == 1) {
                    layer.msg('专家认证信息草稿保存成功');
                    close_tabs();
                }

            },
            error: function (res) {
                layer.msg('专家认证信息草稿保存失败');
            }
        })
    }

    //关闭页面
    function close_tabs() {
        closenowtabs();
    }

</script>
