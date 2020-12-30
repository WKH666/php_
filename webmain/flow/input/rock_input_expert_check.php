<?php defined('HOST') or die('not access');
include_once('webmain/flow/input/rock_input_online_record.php');
include_once('webmain/flow/input/rock_input_fine_record.php');
include_once('webmain/flow/input/rock_input_paper_record.php');
include_once('webmain/flow/input/rock_input_cross_record.php');
include_once('webmain/flow/input/rock_input_prize_record.php');
?>
<script>
    $(document).ready(function (){
        {params}
        let expert_id = params.expert_id;
        var a = {
            init: function () {
                js.ajax(js.getajaxurl('get_expert_results', 'expert_manage', 'main'), {expert_id: expert_id}, function (data) {
                    a.initshow(data);
                }, 'post,json');
            },
            initshow: function (data) {
                $("#expert_name").val(data.name);
                $("#sex").val(data.sex);
                $("#mobile").val(data.mobile);
                $("#email").val(data.email);
                $("#expert_company").val(data.company);
                $("#expert_position").val(data.position);
                $("#graduate_project").val(data.graduate_project);
                $("#expert_research_direction").val(data.research_direction);
                $("#fine_num").val(data.fine_num);
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
        js.initbtn(a);
        a.init();

        $('#table').bootstable({
            params: {expert_id: expert_id},
            url: js.getajaxurl('expert_checks', 'expert_manage', 'main', {}),
            storeafteraction: 'expertcheckafter',
            columns: [{
                text: '审核结果', dataIndex: 'opt_status'
            }, {
                text: '审核意见', dataIndex: 'audit_opinion'
            }, {
                text: '审核人', dataIndex: 'user'
            }, {
                text: '审核时间', dataIndex: 'opt_time'
            }],
        });

        $('#return').click(function () {
            closenowtabs();
        });
        $('#online_return').click(function () {
            closenowtabs();
        });
        $('#fine_return').click(function () {
            closenowtabs();
        })

    });


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

    #sex {
        background: #fff;
        border: none;
        outline: none;
    }

    #spot {
        margin-top: 10px;
    }

    .results-form {
        background: #F7F7F7;
    }

    .results-form form {
        display: flex;
        flex-direction: row;
        flex-wrap: wrap;
        padding-top: 10px;
    }

    .form-group {
        display: flex;
        flex-direction: row;
        /*margin-right: 15px;*/
    }

    .form-group:nth-last-child(2) {
        margin-right: 20px;
    }

    .form-group label {
        width: 15rem;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .form-group button {
        margin: 0 10px;
    }

    #search, #daoru, #visible {
        background: #108EE9;
        color: #fff;
        border-color: #108EE9;
    }
</style>
<ul class="nav nav-tabs">
    <li class="active"><a href="#expert_info" data-toggle="tab">专家信息</a></li>
    <li><a href="#online_record" data-toggle="tab">网评记录</a></li>
    <li><a href="#fine_record" data-toggle="tab">扣罚记录</a></li>
    <li><a href="#paper_record" data-toggle="tab">发表论文记录</a></li>
    <li><a href="#cross_record" data-toggle="tab">纵横项目记录</a></li>
    <li><a href="#prize_record" data-toggle="tab">获奖记录</a></li>
</ul>
<div class="tab-content">
    <div class="tab-pane active" id="expert_info">
        <div class="header_title">
            <p>认证信息</p>
        </div>
        <form class="three_columns">
            <div class="form-group">
                <label>姓名:</label>
                <input type="text" class="form-control" readonly id="expert_name">
            </div>
            <div class="form-group">
                <label>性别:</label>
                <input type="radio" readonly checked="checked" id="spot">
                <input type="text" class="form-control" readonly id="sex">
            </div>
            <div class="form-group">
                <label>联系电话:</label>
                <input type="text" class="form-control" readonly id="mobile">
            </div>
        </form>
        <form class="three_columns">
            <div class="form-group">
                <label>电子邮箱:</label>
                <input type="text" class="form-control" readonly id="email">
            </div>
            <div class="form-group">
                <label>单位:</label>
                <input type="text" class="form-control" readonly id="expert_company">
            </div>
            <div class="form-group">
                <label>职务/职称:</label>
                <input type="text" class="form-control" readonly id="expert_position">
            </div>
        </form>
        <form class="three_columns">
            <div class="form-group">
                <label>毕业学科:</label>
                <input type="text" class="form-control" readonly id="graduate_project">
            </div>
            <div class="form-group">
                <label>研究方向:</label>
                <input type="text" class="form-control" readonly id="expert_research_direction">
            </div>
            <div class="form-group">
                <label>扣罚次数:</label>
                <input type="text" class="form-control" readonly id="fine_num">
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
            <div class="form-group">
                <label>社会兼职情况:</label>
                <textarea class="form-control" readonly id="part_time_jobs"></textarea>
            </div>
        </form>
        <form class="one_columns">
            <div class="form-group">
                <label>个人简历:</label>
                <textarea class="form-control" readonly id="curriculum_vitae"></textarea>
            </div>
        </form>
        <form class="one_columns">
            <div class="form-group">
                <label>学科专业主要成绩、主要成果、承担科研项目及获奖情况（近5年）:</label>
                <textarea class="form-control" readonly id="achievements"></textarea>
            </div>
        </form>
        <form class="one_columns">
            <div class="form-group">
                <label>项目评审经历:</label>
                <textarea class="form-control" readonly id="project_review"></textarea>
            </div>
        </form>
        <div class="header_title">
            <div id="table"></div>
        </div>
        <button class="btn btn-default" type="button" id="return">返回</button>
    </div>
    <div class="tab-pane" id="online_record">
        <div class="results-form">
            <form>
                <div class="form-group">
                    <label>所属批次:</label>
                    <input type="text" class="form-control" id="pici_name" name="pici_name" placeholder="请输入" autocomplete="off">
                </div>
                <div class="form-group">
                    <label>评审时间:</label>
                    <input type="text" class="form-control" id="pici_start_time" name="pici_start_time" placeholder="请输入" autocomplete="off">
                </div>
                <div class="form-group">
                    <button type="button" class="btn btn-default" id="search_online" click="search">搜索</button>
                    <button type="reset" class="btn btn-default" id="reset">重置</button>
                </div>
            </form>
        </div>
        <div class="blank10"></div>
        <div id="online_table"></div>
        <button class="btn btn-default" type="button" id="online_return">返回</button>
    </div>
    <div class="tab-pane" id="fine_record">
        <div class="results-form">
            <form>
                <div class="form-group">
                    <label>扣罚时间:</label>
                    <input type="text" class="form-control" id="penalty_time" name="penalty_time" placeholder="请输入" autocomplete="off">
                </div>
                <div class="form-group">
                    <button type="button" class="btn btn-default" id="search_fine" onclick="fine_recordreset()">搜索</button>
                    <button type="reset" class="btn btn-default" id="reset">重置</button>
                </div>
            </form>
        </div>
        <div class="blank10"></div>
        <button type="button" class="btn btn-default" id="clear" onclick="fine_recordreset()">重置清除</button>
        <div id="fine_table"></div>
        <button class="btn btn-default" type="button" id="fine_return">返回</button>
    </div>
    <div class="tab-pane" id="paper_record">
        <div class="results-form">
            <form>
                <div class="form-group">
                    <label>年度:</label>
                    <input type="text" class="form-control" id="year" name="year" placeholder="请输入" autocomplete="off">
                </div>
                <div class="form-group">
                    <label>题名:</label>
                    <input type="text" class="form-control" id="title" name="title" placeholder="请输入" autocomplete="off">
                </div>
                <div class="form-group">
                    <label>刊名:</label>
                    <input type="text" class="form-control" id="serial_title" name="serial_title" placeholder="请输入" autocomplete="off">
                </div>
                <div class="form-group">
                    <button type="button" class="btn btn-default" id="search_paper" onclick="paper_search()">搜索</button>
                    <button type="reset" class="btn btn-default" id="reset">重置</button>
                </div>
            </form>
        </div>
        <div class="blank10"></div>
        <div id="paper_table"></div>

    </div>
    <div class="tab-pane" id="cross_record">
        <div class="results-form">
            <form>
                <div class="form-group">
                    <label>类型:</label>
                    <input type="text" class="form-control" id="type" name="type" placeholder="请输入" autocomplete="off">
                </div>
                <div class="form-group">
                    <label>年度:</label>
                    <input type="text" class="form-control" id="all_year" name="all_year" placeholder="请输入" autocomplete="off">
                </div>
                <div class="form-group">
                    <label>项目名称:</label>
                    <input type="text" class="form-control" id="project_name" name="project_name" placeholder="请输入" autocomplete="off">
                </div>
                <div class="form-group">
                    <button type="button" class="btn btn-default" id="search_cross" onclick="cross_search()">搜索</button>
                    <button type="reset" class="btn btn-default" id="reset" >重置</button>
                </div>
            </form>
        </div>
        <div class="blank10"></div>
        <div id="cross_table"></div>
    </div>
    <div class="tab-pane" id="prize_record">
        <div class="results-form">
            <form>
                <div class="form-group">
                    <label>时间:</label>
                    <input type="text" class="form-control" id="award_time" name="award_time" placeholder="请输入" autocomplete="off">
                </div>
                <div class="form-group">
                    <label>所在单位:</label>
                    <input type="text" class="form-control" id="winning_unit" name="winning_unit" placeholder="请输入" autocomplete="off">
                </div>
                <div class="form-group">
                    <label>奖项:</label>
                    <input type="text" class="form-control" id="prize" name="prize" placeholder="请输入" autocomplete="off">
                </div>
                <div class="form-group">
                    <button type="button" class="btn btn-default" id="search_prize" onclick="prize_search()">搜索</button>
                    <button type="reset" class="btn btn-default" id="reset">重置</button>
                </div>
            </form>
        </div>
        <div class="blank10"></div>
        <div id="prize_table"></div>
    </div>
</div>
