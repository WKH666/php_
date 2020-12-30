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
    <form id="my_form_info" class="form-inline">
        <div class="form_div">
            <div class="form-group">
                <label for="user_numbers" class="left_label">账号：</label>
                <input type="text" class="form-control" id="user_numbers" name="user_numbers" value="" readonly>
            </div>
            <div class="form-group">
                <label for="user_pswords" class="left_label">密码：</label>
                <input type="text" class="form-control" id="user_pswords" name="user_pswords" placeholder="***********"
                       readonly>
            </div>
            <div class="form-group">
                <label for="user_numbers" class="left_label">单位：</label>
                <input type="text" class="form-control" id="input_companys" name="input_companys" placeholder=""
                       readonly>
            </div>
        </div>

        <div class="form_div">
            <div class="form-group">
                <label for="input_names" class="left_label">姓名：</label>
                <input type="text" class="form-control" id="input_names" name="input_names" placeholder="">
            </div>
            <div class="form-group">
                <label style="margin-bottom: 0;" class="left_label">性别：</label>
                <label class="radio-inline">
                    <input type="radio" name="input_sexs" id="sex_mans" value="男"> 男
                </label>
                <label class="radio-inline">
                    <input type="radio" name="input_sexs" id="sex_womans" value="女" checked> 女
                </label>
            </div>
            <div class="form-group">
                <label for="input_positions" class="left_label">职务/职称：</label>
                <input type="text" class="form-control" id="input_positions" name="input_positions" placeholder="">
            </div>
        </div>

        <div class="form_div">
            <div class="form-group">
                <label for="input_tels" class="left_label">联系电话：</label>
                <input type="tel" class="form-control" id="input_tels" name="input_tels" placeholder="">
            </div>
            <div class="form-group">
                <label for="input_emails" class="left_label">电子邮箱：</label>
                <input type="email" class="form-control" id="input_emails" name="input_emails" placeholder="">
            </div>
            <div class="form-group">
                <label for="graduate_projects" class="left_label">毕业学科：</label>
                <input type="text" class="form-control" id="graduate_projects" name="graduate_projects" placeholder="">
            </div>
        </div>

        <div class="form_div">
            <div class="form-group">
                <label for="research_directionss" class="left_label">研究方向：</label>
                <input type="text" class="form-control" id="research_directionss" name="research_directionss"
                       placeholder="">
            </div>
        </div>

        <div id="bank_info">
            <div class="page_header">
                <p>收款账号信息</p>
            </div>

            <div class="form_div">
                <div class="form-group">
                    <label for="bank_names" class="left_label">开户行：</label>
                    <input type="text" class="form-control" id="bank_names" name="bank_names" placeholder="">
                </div>
            </div>

            <div class="form_div">
                <div class="form-group">
                    <label for="bank_cardnum" class="left_label">账号：</label>
                    <input type="number" class="form-control" id="bank_cardnum" name="bank_cardnum" placeholder="">
                </div>
            </div>

            <div class="form_div">
                <div class="form-group">
                    <label for="user_names" class="left_label">姓名：</label>
                    <input type="text" class="form-control" id="user_names" name="user_names" placeholder="">
                </div>
            </div>
        </div>


        <div style="position: fixed;bottom: 15px;left: 50%;">
            <button class="btn  submit_btn" type="button" onclick="submit_data()">保存</button>
            <button class="btn  cancel_btn" type="button" onclick="close_tabs()">返回</button>
        </div>
    </form>


</div>


<script>
    $(document).ready(function () {
        getUserInfo();
        check_isExpert();
    });


    //获取用户基本信息
    function getUserInfo() {
        $.ajax({
            url: './?a=getuserinfo&m=userinfo&d=main&ajaxbool=true', /*接口域名地址*/
            type: 'post',
            dataType: 'json',
            success: function (res) {
                if (res.code == '1') {
                    $('#input_names').val(res.rows.name);
                    $('input:radio[value=' + res.rows.sex + ']').attr("checked", "checked");
                    $('#input_tels').val(res.rows.mobile);
                    $('#input_emails').val(res.rows.email);
                    $('#input_companys').val(res.rows.deptname);
                    $('#input_positions').val(res.rows.ranking);
                    $('#user_numbers').val(res.rows.user);
                    $('#research_directionss').val(res.rows.research_direction);
                    $('#graduate_projects').val(res.rows.graduate_project);
                    $('#bank_names').val(res.rows.bank_name);
                    $('#bank_cardnum').val(res.rows.bank_cardnum);
                    $('#user_names').val(res.rows.bank_carduser);
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
                console.log(res);
                if (!(res.rows)) {
                    $('#bank_info').css('display', 'none');
                }
            }
        })
    }

    //提交个人信息
    function submit_data() {
        var formData = $('#my_form_info').serialize();
        $.ajax({
            url: './?a=save_userinfo&m=user&d=system&ajaxbool=true',
            type:'post',
            dataType:'json',
            data:formData,
            success:function (res) {
                if(res.code == 1){
                    layer.msg('个人信息更新成功!');
                    close_tabs();
                }
            },
            error:function (res) {
                layer.msg('个人信息更新失败!');
            }
        })
    }

    //关闭页面
    function close_tabs() {
        closenowtabs();
    }

</script>

