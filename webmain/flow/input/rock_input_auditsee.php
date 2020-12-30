<?php defined('HOST') or die('not access');?>
<script >
    $(document).ready(function(){
        {params}
        let auditresults_id = params.auditresults_id;
        console.log(auditresults_id);
        var c={
            init:function(){
                js.ajax(js.getajaxurl('getsees', 'expert_manage','main'),{results_id : auditresults_id},function (data) {
                    c.initshow(data);
                },'post,json');
            },
            initshow:function(data){
                $("#username").val(data.name);
                var sex1=data.sex;
                document.getElementById("sext1").innerText=sex1;
                $("#tele").val(data.mobile);
                $("#emails").val(data.email);
                $("#company1").val(data.company);
                $("#position").val(data.position);
                $("#research_direction").val(data.research_direction);
                $("#nation").val(data.nation);
                $("#birth_date").val(data.birth_date);
                $("#birth_place").val(data.birth_place);
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
        b = $('#audit_ck').bootstable({
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
    #forms{
        border: none;
        width:50px;
        height:20px;
        margin-top:10px;
        margin-left:-40px;
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
            <input type="radio" class="form-control" readonly id="forms" checked style="border: none;width:50px;height:20px;margin-top:10px;margin-left:-40px">
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
            <input type="text" class="form-control" readonly id="nation">
        </div>
        <div class="form-group">
            <label>出生年月:</label>
            <input type="text" class="form-control" readonly id="birth_date">
        </div>
        <div class="form-group">
            <label>籍贯:</label>
            <input type="text" class="form-control" readonly id="birth_place">
        </div>
    </form>
    <form class="three_columns">
        <div class="form-group">
            <label>职务/职称:</label>
            <input type="text" class="form-control" readonly id="position2">
        </div>
        <div class="form-group">
            <label>政治面貌:</label>
            <input type="text" class="form-control" readonly id="politic_face">
        </div>
        <div class="form-group">
            <label>毕业院校:</label>
            <input type="text" class="form-control" readonly id="graduate_school">
        </div>
    </form>
    <form class="three_columns">
        <div class="form-group">
            <label>学历学位:</label>
            <input type="text" class="form-control" readonly id="academic_degree">
        </div>
        <div class="form-group">
            <label>通信地址:</label>
            <input type="text" class="form-control" readonly id="address">
        </div>

    </form>

    <form class="one_columns">
        <label>社会兼职情况:</label>
        <div class="form-group">

            <textarea class="form-control" readonly id="part_time_jobs"></textarea>
        </div>
    </form>
    <form class="one_columns">
        <label>个人简历:</label>
        <div class="form-group">

            <textarea class="form-control" readonly id="curriculum_vitae"></textarea>
        </div>
    </form>
    <form class="one_columns">
        <label>学科专业主要成绩、主要成果、承担科研项目及获奖情况（近5年）:</label>
        <div class="form-group">
            <textarea class="form-control" readonly id="achievements"></textarea>
        </div>
    </form>
    <form class="one_columns">
        <label>项目评审经历:</label>
        <div class="form-group">
            <textarea class="form-control" readonly id="project_review"></textarea>
        </div>
    </form>
    <div id="audit_ck"></div>
</div>
<form class="one_columns">
    <div class="form-group" style="margin-left: 8px;margin-top: 100px">
        <button class="btn-sm" type="button"  style="margin-left: 20px" onclick="ba();">返回</button>
    </div>
</form>

</div>

