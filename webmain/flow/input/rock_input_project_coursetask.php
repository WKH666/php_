<?php defined('HOST') or die('not access'); ?>
<script>
    $(document).ready(function () {
        {params}
        let table_id = params.id;
        let table_name=params.table;
        console.log(table_id);
        console.log(table_name);
        var c = {
            init: function () {
                js.ajax(js.getajaxurl('project_course_task', 'sheke_fwork', 'main'), {id:table_id,table_name:table_name}, function (data) {
                    c.initshow(data);
                }, 'post,json');
            },
            initshow: function (data) {
                $("#course_name").val(data.course_name);
                $("#specific_keywords").val(data.course_name);
                $("#words_num").val(data.words_num);
                $("#expected_completion_time").val(data.expected_completion_time);
                $("#together_build_course").val(data.together_build_course);
                $("#self_finance").val(data.self_finance);
                $("#contact_person").val(data.contact_person);
                $("#tel_num").val(data.tel_num);
                $("#leader_name").val(data.leader_name);
                $("#leader_sex").val(data.leader_sex);
                $("#leader_birth").val(data.leader_birth);
                $("#leader_politics_position").val(data.leader_politics_position);
                $("#leader_major_position").val(data.leader_major_position);
                $("#leader_research_expertise").val(data.leader_research_expertise);
                $("#leader_education").val(data.leader_education);
                $("#leader_academic_de").val(data.leader_academic_de);
                $("#leader_address").val(data.leader_address);
                $("#leader_postcode").val(data.leader_postcode);
            }
        }
        js.initbtn(c);
        c.init();
    });
    function ba() {
        closenowtabs();
    }
</script>
<style>
    input[type='text']{
        outline: none;
        border: none;
    }
</style>
<table style="width:100%;" cellpadding="2" cellspacing="0" border="1" bordercolor="#000000">
    <tbody>
    <tr>
        <td style="text-align:center;" colspan="3">*课题名称</td>
        <td colspan="9">
            <span class="divinput">
                <input class="input" type="text" id="course_name">
            </span>
        </td>
    </tr>
    <tr>
        <td style="text-align:center;" colspan="3">*学科分类<br/></td>
        <td colspan="9">
            <span>
                <label>
                <input name="subject" type="checkbox" value="生物学-昆虫学">生物学-昆虫学
                </label>
                &nbsp;&nbsp;
                <label>
                <input name="subject" type="checkbox" value="IT-人工智能">IT-人工智能
                </label>
                &nbsp;&nbsp;
                <label>
                <input name="subject" type="checkbox" value="工程学-勘查技术与工程">工程学-采矿工程
                </label>
                &nbsp;&nbsp;
                <label>
                <input name="subject" type="checkbox" value="工程学-采矿工程">工程学-采矿工程
                </label>
                &nbsp;&nbsp;
                <label>
                <input name="subject" type="checkbox" value="党史、党建-共产党">工程学-采矿工程
                </label>
                &nbsp;&nbsp;
                <label>
                <input name="subject" type="checkbox" value="马列、科设-马克思主义">工程学-采矿工程
                </label>
                &nbsp;&nbsp;
            </span>
        </td>
    </tr>
    <tr>
        <td style="text-align:center;" colspan="3">*关键词分类<span></span><br/></td>
        <td colspan="4">
            <span>
                <label>
                <input name="keyword" type="checkbox" value="金融">金融
                </label>
                &nbsp;&nbsp;
                <label>
                <input name="keyword" type="checkbox" value="体育">体育
                </label>
                &nbsp;&nbsp;
                <label>
                <input name="keyword" type="checkbox" value="土木工程">土木工程
                </label>
                &nbsp;&nbsp;
                <label>
                <input name="subject" type="checkbox" value="软件设计">软件设计
                </label>
                &nbsp;&nbsp;
            </span>
        </td>
        <td style="text-align:center;" colspan="2"><p style="text-align:center;">*具体关键词</p>
            <p style="text-align:center;">(不超过3个)</p></td>
        <td style="text-align:center;" colspan="3">
            <span class="divinput">
                <input class="input" type="text" id="specific_keywords">
            </span>
        </td>
    </tr>
    <tr>
        <td style="text-align:center;" colspan="3">*预期成果形式<span> </span><br/></td>
        <td colspan="9"><span>
                <label>
                <input name="pre_achievement" type="checkbox" value="专著">专著
                </label>
                &nbsp;&nbsp;
                <label>
                <input name="pre_achievement" type="checkbox" value="论文">论文
                </label>
                &nbsp;&nbsp;
                <label>
                <input name="pre_achievement" type="checkbox" value="研究报告">土木工程
                </label>
                &nbsp;&nbsp;
            </span>
            <br/>
        </td>
    </tr>
    <tr>
        <td style="text-align:center;" colspan="3">*成果去向<br/></td>
        <td colspan="9">
            <span>
                <label>
                <input name="achievement_where" type="checkbox" value="公开出版">公开出版
                </label>
                &nbsp;&nbsp;
                <label>
                <input name="achievement_where" type="checkbox" value="公开发表">公开发表
                </label>
                &nbsp;&nbsp;
                <label>
                <input name="achievement_where" type="checkbox" value="提交相关部门应用">提交相关部门应用
                </label>
                &nbsp;&nbsp;
                <input name="achievement_where" type="checkbox" value="递交相关领导批阅">递交相关领导批阅
                </label>
                &nbsp;&nbsp;
            </span>
        </td>
    </tr>
    <tr>
        <td style="text-align:center;" colspan="3">*字数<span> </span><br/></td>
        <td style="text-align:center;" colspan="4">
            <span class="divinput">
                <input class="input" type="text" id="words_num">
            </span>
        </td>
        <td style="text-align:center;" colspan="2">*预计完成时间</td>
        <td style="text-align:center;" colspan="3" id="expected_completion_time"></td>
    </tr>
    <tr>
        <td style="text-align:center;" colspan="3"><strong>*是否为学科共建课题</strong><br/></td>
        <td style="text-align:center;" colspan="4">
            <span class="divinput">
                <input class="input" type="text" id="together_build_course">
            </span>
        </td>
        </td>
        <td style="text-align:center;" colspan="2"><strong>*是否同意自筹经费</strong><br/></td>
        <td style="text-align:center;" colspan="3">
            <span class="divinput">
                <input class="input" type="text" id="self_finance">
            </span>
        </td>
    </tr>
    <tr>
        <td style="text-align:center;" colspan="3">*单位联系人<span> </span><br/></td>
        <td style="text-align:center;" colspan="4">
            <span class="divinput">
                <input class="input" type="text" id="contact_person">
            </span>
        </td>
        <td style="text-align:center;" colspan="2">*电话<br/></td>
        <td style="text-align:center;" colspan="3">
            <span class="divinput">
                <input class="input" type="text" id="tel_num">
            </span>
        </td>
    </tr>
    <tr>
        <td colspan="12" style="text-align:center;">课题负责人及课题组主要成员(一般不超过5人)简况<br/></td>
    </tr>
    <tr>
        <td colspan="12" style="text-align:center;">课题负责人简况<br/></td>
    </tr>
    <tr><!--<td style="text-align:center;" rowspan="4">课题负责人<br /></td>-->
        <td style="text-align:center;" colspan="3">*姓名</td>
        <td style="text-align:center;" colspan="2"><br/>
            <span class="divinput">
                <input class="input" type="text" id="leader_name">
            </span>
        </td>
        <td style="text-align:center;" colspan="1">*性别</td>
        <td style="text-align:center;" colspan="1"><br/>
            <span class="divinput">
                <input class="input" type="text" id="leader_sex">
            </span>
        </td>
        <td style="text-align:center;" colspan="3">*出生日期</td>
        <td style="text-align:center;" colspan="2">{leader_birth}</td>
    </tr>
    <tr>
        <td style="text-align:center;" colspan="3">*所在单位</td>
        <td style="text-align:center;" colspan="2">
            <span class="divinput">
                <input class="input" type="text" id="leader_company">
            </span>
        </td>
        <td style="text-align:center;" colspan="1">*行政职务</td>
        <td style="text-align:center;" colspan="1">
            <span class="divinput">
                <input class="input" type="text" id="leader_politics_position">
            </span>
        </td>
        <td style="text-align:center;" colspan="3">*专业职称</td>
        <td style="text-align:center;" colspan="2">
            <span class="divinput">
                <input class="input" type="text" id="leader_major_position">
            </span>
        </td>
    </tr>
    <tr>
        <td style="text-align:center;" colspan="3">*研究专长</td>
        <td style="text-align:center;" colspan="2">
            <span class="divinput">
                <input class="input" type="text" id="leader_research_expertise">
            </span>
        </td>
        <td style="text-align:center;" colspan="1">*学历</td>
        <td style="text-align:center;" colspan="1">
            <span class="divinput">
                <input class="input" type="text" id="leader_education">
            </span>
        </td>
        <td style="text-align:center;" colspan="3">*学位</td>
        <td style="text-align:center;" colspan="2">
            <span class="divinput">
                <input class="input" type="text" id="leader_academic_de">
            </span>
        </td>
    </tr>
    <tr>
        <td style="text-align:center;" colspan="3">*通讯地址</td>
        <td style="text-align:center;" colspan="4">
            <span class="divinput">
                <input class="input" type="text" id="leader_address">
            </span>
        </td>
        <td style="text-align:center;" colspan="3">*邮政编码</td>
        <td style="text-align:center;" colspan="2">
            <span class="divinput">
                <input class="input" type="text" id="leader_postcode">
            </span>
        </td>
    </tr>
    <tr>
        <td colspan="12" style="text-align:center;">课题主要成员简况<br/></td>
    </tr>
    </tbody>
</table>
<table width="100%" border="1" cellpadding="2" cellspacing="0" id="tablesub0">
    <tbody>
    <tr>
        <td style="text-align:center;">序号</td>
        <td style="text-align:center;">姓名</td>
        <td style="text-align:center;">单位</td>
        <td style="text-align:center;">职称</td>
        <td style="text-align:center;">承担任务</td>
    </tr>
    <tr>
        <td style="text-align:center;">[xuhao0,0]</td>
        <td style="text-align:center;">[name0,0]</td>
        <td style="text-align:center;">[company0,0]</td>
        <td style="text-align:center;">[position0,0]</td>
        <td style="text-align:center;">[task0,0]</td>
    </tr>
    <tr>
        <td style="text-align:center;">[xuhao0,1]</td>
        <td style="text-align:center;"><span>[name0,1]</span></td>
        <td style="text-align:center;"><span>[company0,1]</span></td>
        <td style="text-align:center;"><span>[position0,1]</span></td>
        <td style="text-align:center;"><span>[task0,1]</span></td>
    </tr>
    <tr>
        <td style="text-align:center;">[xuhao0,2]</td>
        <td style="text-align:center;"><span>[name0,2]</span></td>
        <td style="text-align:center;"><span>[company0,2]</span></td>
        <td style="text-align:center;"><span>[position0,2]</span></td>
        <td style="text-align:center;"><span>[task0,2]</span></td>
    </tr>
    <tr>
        <td style="text-align:center;">[xuhao0,3]</td>
        <td style="text-align:center;"><span>[name0,3]</span></td>
        <td style="text-align:center;"><span>[company0,3]</span></td>
        <td style="text-align:center;"><span>[position0,3]</span></td>
        <td style="text-align:center;"><span>[task0,3]</span></td>
    </tr>
    <tr>
        <td style="text-align:center;">[xuhao0,4]</td>
        <td style="text-align:center;"><span>[name0,4]</span></td>
        <td style="text-align:center;"><span>[company0,4]</span></td>
        <td style="text-align:center;"><span>[position0,4]</span></td>
        <td style="text-align:center;"><span>[task0,4]</span></td>
    </tr>
    </tbody>

</table>
<button type="button" class="btn-sm btn-default">返回</button>



