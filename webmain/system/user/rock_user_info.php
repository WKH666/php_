<?php if (!defined('HOST')) die('not access'); ?>
<link rel="stylesheet" type="text/css" href="mode/bootstrap3.3/css/bootstrap.min.css"/>
<script src="mode/bootstrap3.3/js/bootstrap.min.js"></script>

<style>
    .page_header {
        width: 100%;
        height: 35px;
        background-color: #CDE3F1;
        border-radius: 5px;
        margin-top: 20px;
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
        background-color: #108ee9;
        color: white !important;
        margin-right: 20px;
        padding: 5px 15px;
        outline: none !important;
    }

    .expert_btn {
        background-color: #108ee9;
        color: white !important;
        margin-left: 20px;
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
        <p>账号信息</p>
    </div>
    <form id="my_form" class="form-inline">
        <div class="form_div">
            <div class="form-group">
                <label for="user_number" class="left_label">账号：</label>
                <input type="text" class="form-control" id="user_number" name="user_number" placeholder="" readonly>
            </div>
            <div class="form-group">
                <label for="user_psword" class="left_label">密码：</label>
                <input type="text" class="form-control" id="user_psword" name="user_psword" placeholder="***********"
                       readonly>
            </div>
            <div class="form-group">
                <label for="input_company" class="left_label">单位：</label>
                <input type="text" class="form-control" id="input_company" name="input_company" placeholder="" readonly>
            </div>
        </div>

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
                <label for="input_position" class="left_label">职务/职称：</label>
                <input type="text" class="form-control" id="input_position" name="input_position" placeholder=""
                       readonly>
            </div>
        </div>

        <div class="form_div">
            <div class="form-group">
                <label for="input_tel" class="left_label">联系电话：</label>
                <input type="tel" class="form-control" id="input_tel" name="input_tel" placeholder="" readonly>
            </div>
            <div class="form-group">
                <label for="input_email" class="left_label">电子邮箱：</label>
                <input type="email" class="form-control" id="input_email" name="input_email" placeholder="" readonly>
            </div>
            <div class="form-group">
                <label for="graduate_project" class="left_label">毕业学科：</label>
                <input type="text" class="form-control" id="graduate_project" name="graduate_project" placeholder=""
                       readonly>
            </div>
        </div>

        <div class="form_div">
            <div class="form-group">
                <label for="research_directions" class="left_label">研究方向：</label>
                <input type="text" class="form-control" id="research_directions" name="research_directions"
                       placeholder="" readonly>
            </div>
            <div class="form-group">
                <label style="margin-bottom: 0;" class="left_label">专家身份：</label>
                <label class="radio-inline">
                    <input type="radio" name="is_expert" id="is_expert" value="是" checked><span id="expert{rand}">是</span>
                </label>
                <label>
                    <input type="button" value="更新信息" class="btn expert_btn" onclick="update_expert()">
                </label>
                <label style="margin-left: 20px;color: red;"><span>审核结果：</span><span>通过</span></label>
            </div>

            <div class="form-group">
                <label style="margin-bottom: 0;" class="left_label">账号开启：</label>
                <label class="radio-inline">
                    <input type="radio" name="is_open" id="is_open" value="是" checked>是
                </label>
            </div>
        </div>
        <div id="bank_info{rand}">
            <div class="page_header">
                <p>收款账号信息</p>
            </div>

            <div class="form_div">
                <div class="form-group">
                    <label for="bank_name" class="left_label">开户行：</label>
                    <input type="text" class="form-control" id="bank_name" name="bank_name" placeholder="" readonly>
                </div>
            </div>

            <div class="form_div">
                <div class="form-group">
                    <label for="bank_num" class="left_label">账号：</label>
                    <input type="number" class="form-control" id="bank_num" name="bank_num" placeholder="" readonly>
                </div>
            </div>

            <div class="form_div">
                <div class="form-group">
                    <label for="user_name" class="left_label">姓名：</label>
                    <input type="text" class="form-control" id="user_name" name="user_name" placeholder="" readonly>
                </div>
            </div>
        </div>
        <div style="position: fixed;bottom: 15px;left: 50%;">
            <button class="btn  submit_btn" type="button" onclick="user_infoedit()">修改</button>
            <button class="btn  cancel_btn" type="button" onclick="close_tabs()">返回</button>
        </div>
    </form>


</div>


<script>
    $(document).ready(function () {
        getUserInfo();
        getSubject();
        check_isExpert();
    });

    //跳到更新专家信息页面
    function update_expert() {
        addtabs({
            num: 'update_expert',
            url: 'main,userinfo,check_expert',
            icons: 'icon-bookmark-empty',
            name: '认证/更新专家信息'
        });
    }

    //跳到个人信息修改界面
    function user_infoedit() {
        addtabs({num: 'infoedit', url: 'system,user,infoedit', icons: 'icon-bookmark-empty', name: '修改个人信息'});
    }

    //学科分类
    function getSubject() {
        $.ajax({
            url: './?a=subject_classification&m=project_comment&d=main&ajaxbool=true',
            type: 'post',
            dataType: 'json',
            success: function (res) {
                if (res.code == '1') {
                    $.each(res.rows, function (k, v) {
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
                if (res.code == '1') {
                    $('#input_name').val(res.rows.name);
                    $('input:radio[value=' + res.rows.sex + ']').attr("checked", "checked");
                    $('#input_tel').val(res.rows.mobile);
                    $('#input_email').val(res.rows.email);
                    $('#input_company').val(res.rows.deptname);
                    $('#input_position').val(res.rows.ranking);
                    $('#user_number').val(res.rows.user);
                    $('#research_directions').val(res.rows.research_direction);
                    $('#graduate_project').val(res.rows.graduate_project);
                    $('#bank_name').val(res.rows.bank_name);
                    $('#bank_num').val(res.rows.bank_cardnum);
                    $('#user_name').val(res.rows.bank_carduser);
                } else {
                    console.log(res.msg);
                }
            }
        });

    }

    //检测用户是否是专家
    function check_isExpert() {
        $.ajax({
            url: './?a=check_isExpert&m=user&d=system&ajaxbool=true',
            type: 'post',
            dataType: 'json',
            success: function (res) {
                if (!(res.rows)) {
                    $('#bank_info{rand}').css('display', 'none');
                    $('#expert{rand}').text('否');
                } else {
                    $('#expert{rand}').text('是');
                }
            }
        })
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
                console.log(res);
                if (res.code == 1) {
                    layer.msg('专家认证信息提交成功');
                    close_tabs();
                }

            },
            error: function (res) {
                layer.msg('专家认证信息提交失败');
            }
        })
    }


    //关闭页面
    function close_tabs() {
        closenowtabs();
    }

</script>

