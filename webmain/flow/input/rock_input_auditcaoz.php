<?php defined('HOST') or die('not access'); ?>
<script>
    $(document).ready(function () {
        {params}
        let auditresults_id = params.auditresults_id;
        console.log(auditresults_id);
        var c = {
            init: function () {
                js.ajax(js.getajaxurl('getchecks', 'expert_manage', 'main'), {results_id: auditresults_id}, function (data) {
                    c.initshow(data);
                }, 'post,json');
            },
            initshow: function (data) {
                $("#username").val(data.name);
                var sex1 = data.sex;
                document.getElementById("sext1").innerText = sex1;
                $("#tele").val(data.mobile);
                $("#emails").val(data.email);
                $("#company1").val(data.company);
                $("#position").val(data.position);
                $("#research_direction").val(data.research_direction);
                $("#nation").val(data.nation);
                $("#birth_place").val(data.birth_place);
                $("#birth_date").val(data.birth_date);
                $("#position2").val(data.position2);
                $("#politic_face").val(data.politic_face);
                $("#graduate_school").val(data.graduate_school);
                $("#academic_degree").val(data.academic_degree);
                $("#address").val(data.address);
                $("#part_time_jobs").val(data.part_time_jobs);
                $("#curriculum_vitae").val(data.curriculum_vitae);
                $("#achievements").val(data.achievements);
                $("#project_review").val(data.project_review);
            }
        }
        js.initbtn(c);
        c.init();
        b = $('#audit_cz').bootstable({
            url: js.getajaxurl('expertchecks', 'expert_manage', 'main'),
            params:{'result_id':auditresults_id},
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

    function ba() {
        closenowtabs();
    }

    function save() {
        var nation=$("#nation").val();
        var birth_date=$("#birth_date").val();
        var birth_place=$("#birth_place").val();
        var position2=$("#position2").val();
        var politic_face=$("#politic_face").val();
        var graduate_school=$("#graduate_school").val();
        var academic_degree=$("#academic_degree").val();
        var address=$("#address").val();
        var part_time_jobs=$("#part_time_jobs").val();
        var curriculum_vitae=$("#curriculum_vitae").val();
        var achievements=$("#achievements").val();
        var project_review=$("#project_review").val();
        var checkValue = $('input:radio[name="optionsRadios"]:checked').val();
        var sheheyijian = $("#sheheyijian").val();
        {params}
        let auditresults_id = params.auditresults_id;
        js.ajax(js.getajaxurl('savecaoz', 'expert_manage', 'main'),
            {results_id: auditresults_id,status:checkValue,text:sheheyijian,
                nation:nation,birth_date:birth_date,birth_place:birth_place,
                position2:position2,politic_face:politic_face,graduate_school:graduate_school,
                academic_degree:academic_degree,address:address,part_time_jobs:part_time_jobs,
                curriculum_vitae:curriculum_vitae,achievements:achievements,project_review:project_review,
            }, function (data) {
            if (data==true){
                closenowtabs();
                try {assessmentList.reload();}catch (e) {}
            } else {
                alert("认证信息和审核操作要填写完整");
            }
        }, 'post,json');
    }
</script>

<style>
    .three_columns {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
    }

    .form-group {
        display: flex;
        flex-direction: row;
    }

    .form-group label {
        display: flex;
        justify-content: center;
        align-items: center;
        margin: 0;
        padding: 0;
    }

    .three_columns .form-group label {
        width: 13rem;
    }

    .one_columns .form-group label {
        width: 11rem;
    }

    .one_columns .form-group textarea {
        height: 10rem;
    }

    .header_title {
        background: #CDE3F1;
        border-radius: 5px;
    }

    .header_title p {
        padding: 5px 0;
    }

    .header_title:nth-of-type(2) p {
        margin: 0;
    }

    #results_table {
        width: 100%;
    }

    #results_table thead, tbody tr td {
        height: 30px;
    }

    #results_table thead tr td {
        text-align: center;
        background: #F2F2F2;
    }

    #results_table tbody {
    }

    #results_table tbody tr td {
        text-align: center;
        border: 1px solid #d1d1d1;
        box-sizing: border-box;
    }

    #results_table tbody tr td:nth-of-type(1) {
        text-align: left;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
    }

    #sheheyijian {
        width: 1300px;
        margin: 11px 0 0 58px;
        border-radius: 3px;
        border: 1px solid #ccc;
    }

    #forms {
        border: none;
        width: 50px;
        height: 20px;
        margin-top: 10px;
        margin-left: -40px;
    }

</style>
<div>
    <div class="header_title">
        <p>认证信息</p>
    </div>
    <form class="three_columns">
        <div class="form-group">
            <label>姓名:</label>
            <input type="text" class="form-control" readonly id="username">
        </div>
        <div class="form-group">
            <label>性别:</label>
            <input type="radio" class="form-control" readonly id="forms" checked>
            <label id="sext1" style="margin-left: -60px;"></label>
        </div>
        <div class="form-group">
            <label>联系电话:</label>
            <input type="text" class="form-control" readonly id="tele">
        </div>
    </form>
    <form class="three_columns">
        <div class="form-group">
            <label>电子邮箱:</label>
            <input type="text" class="form-control" readonly id="emails">
        </div>
        <div class="form-group">
            <label>单位:</label>
            <input type="text" class="form-control" readonly id="company1">
        </div>
        <div class="form-group">
            <label>职务/职称:</label>
            <input type="text" class="form-control" readonly id="position">
        </div>
    </form>
    <form class="one_columns">
        <div class="form-group">
            <label>研究方向:</label>
            <input type="text" class="form-control" readonly id="research_direction">
        </div>
    </form>
    <form class="three_columns">
        <div class="form-group">
            <label>民族:</label>
            <input type="text" class="form-control" required="required" id="nation" readonly>
        </div>
        <div class="form-group">
            <label>出生年月:</label>
            <input onclick="js.datechange(this,'datetime')"value class="form-control input datesss" inputtype="datetime" id="birth_date" name="etoc" readonly>
        </div>
        <div class="form-group">
            <label>籍贯:</label>
            <input type="text" class="form-control" required="required" id="birth_place" readonly>
        </div>
    </form>
    <form class="three_columns">
        <div class="form-group">
            <label>职务/职称:</label>
            <input type="text" class="form-control" required="required" id="position2" readonly>
        </div>
        <div class="form-group">
            <label>政治面貌:</label>
            <input type="text" class="form-control" required="required" id="politic_face" readonly>
        </div>
        <div class="form-group">
            <label>毕业院校:</label>
            <input type="text" class="form-control" required="required" id="graduate_school" readonly>
        </div>
    </form>
    <form class="three_columns">
        <div class="form-group">
            <label>学历学位:</label>
            <input type="text" class="form-control" required="required" id="academic_degree" readonly>
        </div>
        <div class="form-group">
            <label>通信地址:</label>
            <input type="text" class="form-control" required="required" id="address" readonly>
        </div>

    </form>

    <form class="one_columns">
        <label>社会兼职情况:</label>
        <div class="form-group">
            <textarea class="form-control" required="required" id="part_time_jobs" readonly></textarea>
        </div>
    </form>
    <form class="one_columns">
        <label>个人简历:</label>
        <div class="form-group">
            <textarea class="form-control" required="required" id="curriculum_vitae" readonly></textarea>
        </div>
    </form>
    <form class="one_columns">
        <label>学科专业主要成绩、主要成果、承担科研项目及获奖情况（近5年）:</label>
        <div class="form-group">
            <textarea class="form-control" required="required" id="achievements" readonly></textarea>
        </div>
    </form>
    <form class="one_columns">
        <label>项目评审经历:</label>
        <div class="form-group">
            <textarea class="form-control" required="required" id="project_review" readonly></textarea>
        </div>
    </form>
    <div id="audit_cz"></div>

</div>
<div class="header_title" style="margin-top: 30px">
    <p>审核操作</p>
</div>
<form class="three_columns">
    <div class="form-group">
        <label>审核结果:</label>
        <div class="radio">
            <label>
                <input type="radio" name="optionsRadios" id="oR1" value="通过">
                <label style="margin-left: 29px">通过</label>
            </label>

        </div>
        <div class="radio" style="margin-top:9px">
            <label>
                <input type="radio" name="optionsRadios" id="oR2" value="拒绝">
                <label style="margin-left: 29px;">拒绝</label>
            </label>

        </div>
    </div>
</form>
<form class="one_columns">
    <div class="form-group" style="margin-left: 8px">
        <div class="form-group">
            <label>审核意见:</label>
            <input type="text" placeholder="请输入" required="required" id="sheheyijian">
        </div>
    </div>
</form>
<form class="one_columns">
    <div class="form-group" style="margin-left: 8px">
        <button class="btn btn-primary btn-sm" type="button" onclick="save();">保存</button>
        <button class="btn-sm" type="button" style="margin-left: 20px" onclick="ba();">返回</button>
    </div>
</form>

</div>

