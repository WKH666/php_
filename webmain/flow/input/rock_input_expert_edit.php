<?php defined('HOST') or die('not access');?>
<script >
    $(document).ready(function(){
        {params}
        let expert_id = params.expert_id;
        var c={
            init:function(){
                js.ajax(js.getajaxurl('get_expert_results','expert_manage','main'),{expert_id : expert_id},function (data) {
                    c.initshow(data);
                },'post,json');
            },
            initshow:function(data){
                $("#expert_name").val(data.name);
                $("#sex").val(data.sex);
                $("#mobile").val(data.mobile);
                $("#email").val(data.email);
                $("#expert_company").val(data.company);
                $("#expert_position").val(data.position);
                $("#graduate_project").val(data.graduate_project);
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
        };
        js.initbtn(c);
        c.init();

        $('#table').bootstable({
            params:{expert_id : expert_id},
            url:js.getajaxurl('expert_checks','expert_manage','main', {}),
            storeafteraction:'expertcheckafter',
            columns:[{
                text:'审核结果',dataIndex:'opt_status'
            },{
                text:'审核意见',dataIndex:'audit_opinion'
            },{
                text:'审核人',dataIndex:'user'
            },{
                text:'审核时间',dataIndex:'opt_time'
            }],
        });

        $('#submit').click(function () {
            var expert_name = $("#expert_name").val();
            var mobile = $("#mobile").val();
            var email = $("#email").val();
            var expert_position = $("#expert_position").val();
            var graduate_project = $("#graduate_project").val();
            var nation = $("#nation").val();
            var birth_date = $("#birth_date").val();
            var birth_place = $("#birth_place").val();
            var position2 = $("#position2").val();
            var politic_face = $("#politic_face").val();
            var graduate_school = $("#graduate_school").val();
            var academic_degree = $("#academic_degree").val();
            var address = $("#address").val();
            var part_time_jobs = $("#part_time_jobs").val();
            var curriculum_vitae = $("#curriculum_vitae").val();
            var achievements = $("#achievements").val();
            var project_review = $("#project_review").val();
            js.ajax(js.getajaxurl('update_expert_info','expert_manage','main'),
                {
                    expert_id : expert_id,
                    expert_name : expert_name,
                    mobile : mobile,
                    email : email,
                    expert_position : expert_position,
                    graduate_project : graduate_project,
                    nation : nation,
                    birth_date : birth_date,
                    birth_place : birth_place,
                    position2 : position2,
                    politic_face : politic_face,
                    graduate_school : graduate_school,
                    academic_degree : academic_degree,
                    address : address,
                    part_time_jobs : part_time_jobs,
                    curriculum_vitae : curriculum_vitae,
                    achievements : achievements,
                    project_review : project_review
                },
                function (data) {
                    alert("编辑成功！");
                    location.reload();
                },'post,json');
        });


        $('#return').click(function () {
            closenowtabs();
        })
    });

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
    #sex{
        background: #fff;
        border: none;
        outline: none;
    }
    #spot{
        margin-top: 10px;
    }
</style>
<div>
    <div class="header_title">
        <p>认证信息</p>
    </div>
    <form class="three_columns">
        <div class="form-group">
            <label>姓名:</label>
            <input type="text" class="form-control"  id="expert_name">
        </div>
        <div class="form-group">
            <label>性别:</label>
            <input type="radio" readonly checked="checked" id="spot" >
            <input type="text" class="form-control" readonly id="sex" >
        </div>
        <div class="form-group">
            <label>联系电话:</label>
            <input type="text" class="form-control"  id="mobile">
        </div>
    </form>
    <form class="three_columns">
        <div class="form-group">
            <label>电子邮箱:</label>
            <input type="text" class="form-control" id="email">
        </div>
        <div class="form-group">
            <label>单位:</label>
            <input type="text" class="form-control" readonly id="expert_company">
        </div>
        <div class="form-group">
            <label>职务/职称:</label>
            <input type="text" class="form-control"  id="expert_position">
        </div>
    </form>
    <form class="three_columns">
        <div class="form-group">
            <label>研究方向:</label>
            <input type="text" class="form-control" id="expert_research_direction">
        </div>
    </form>
    <form class="three_columns">
        <div class="form-group">
            <label>民族:</label>
            <input type="text" class="form-control"  id="nation">
        </div>
        <div class="form-group">
            <label>出生年月:</label>
            <input type="text" class="form-control"  id="birth_date">
        </div>
        <div class="form-group">
            <label>籍贯:</label>
            <input type="text" class="form-control"   id="birth_place">
        </div>
    </form>
    <form class="three_columns">
        <div class="form-group">
            <label>职务/职称:</label>
            <input type="text" class="form-control"  id="position2">
        </div>
        <div class="form-group">
            <label>政治面貌:</label>
            <input type="text" class="form-control"  id="politic_face">
        </div>
        <div class="form-group">
            <label>毕业院校:</label>
            <input type="text" class="form-control"  id="graduate_school">
        </div>
    </form>
    <form class="three_columns">
        <div class="form-group">
            <label>学历学位:</label>
            <input type="text" class="form-control"  id="academic_degree">
        </div>
        <div class="form-group">
            <label>通信地址:</label>
            <input type="text" class="form-control"  id="address">
        </div>
    </form>
    <form class="one_columns">
        <div class="form-group">
            <label>社会兼职情况:</label>
            <textarea class="form-control"  id="part_time_jobs"></textarea>
        </div>
    </form>
    <form class="one_columns">
        <div class="form-group">
            <label>个人简历:</label>
            <textarea class="form-control"  id="curriculum_vitae"></textarea>
        </div>
    </form>
    <form class="one_columns">
        <div class="form-group">
            <label>学科专业主要成绩、主要成果、承担科研项目及获奖情况（近5年）:</label>
            <textarea class="form-control"  id="achievements"></textarea>
        </div>
    </form>
    <form class="one_columns">
        <div class="form-group">
            <label>项目评审经历:</label>
            <textarea class="form-control"  id="project_review"></textarea>
        </div>
    </form>
    <div id="table"></div>
    <button class="btn btn-default" type="button" id="submit">提交</button>
    <button class="btn btn-default" type="button" id="return">返回</button>
</div>
