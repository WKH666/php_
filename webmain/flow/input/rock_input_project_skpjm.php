<?php defined('HOST') or die('not access'); ?>
<table style="width:100%;" cellpadding="2" cellspacing="0" border="1" bordercolor="#000000">
    <tbody>
    <tr>
        <td>*项目名称
            <br />
        </td>
        <td colspan="3">{project_name}
            <br />
        </td>
    </tr>
    <tr>
        <td>*活动形式
            <br />
        </td>
        <td colspan="3">{activity_form}
            <div style="display: flex;flex-direction: row;flex-wrap: wrap;">
                <div><input type="checkbox" id="report_lecture" value="报告讲座" name="activitybox">报告讲座</div>
                <div><input type="checkbox" id="exhibition_performance" value="展览展示展演" name="activitybox">展览展示展演</div>
                <div><input type="checkbox" id="consulting_service" value="广场咨询服务" name="activitybox">广场咨询服务</div>
                <div><input type="checkbox" id="quiz_show" value="知识竞赛" name="activitybox">知识竞赛</div>
                <div><input type="checkbox" id="popular_reading" value="社科普及读物或教材" name="activitybox">社科普及读物或教材</div>
                <div><input type="checkbox" id="grassroots" value="社科进基层" name="activitybox">社科进基层</div>
                <div><input type="checkbox" id="micro_social_sciences" value="微社科" name="activitybox">微社科</div>
                <div><input type="checkbox" id="other" value="其他" name="activitybox">其他</div>
            </div>
            <br />
        </td>
    </tr>
    <tr>
        <td>*项目简介
            <br />
        </td>
        <td colspan="3">{project_introduction}
            <br />
        </td>
    </tr>
    <tr>
        <td>*活动时间
            <br />
        </td>
        <td colspan="3">{activity_time}
            <br />
        </td>
    </tr>
    <tr>
        <td>*活动地点
            <br />
        </td>
        <td colspan="3">{activity_site}
            <br />
        </td>
    </tr>
    <tr>
        <td>*承办单位
            <br />
        </td>
        <td colspan="3">{undertake_unit}
            <br />
        </td>
    </tr>
    <tr>
        <td>*参加对象
            <br />
        </td>
        <td>{people_join}
            <br />
        </td>
        <td>*预计参加人数
            <br />
        </td>
        <td>{people_num}
            <br />
        </td>
    </tr>
    <tr>
        <td>*负责人
            <br />
        </td>
        <td>{in_charge_person}
            <br />
        </td>
        <td>*联系人
            <br />
        </td>
        <td>{contact_person}
            <br />
        </td>
    </tr>
    <tr>
        <td>*联系地址
            <br />
        </td>
        <td>{contact_address}
            <br />
        </td>
        <td>*邮编
            <br />
        </td>
        <td>{zip_code}
            <br />
        </td>
    </tr>
    <tr>
        <td>*联系电话
            <br />
        </td>
        <td>{contact_phone}
            <br />
        </td>
        <td>*手机
            <br />
        </td>
        <td>{mobile_phone}
            <br />
        </td>
    </tr>
    <tr>
        <td>*传真
            <br />
        </td>
        <td>{fax}
            <br />
        </td>
        <td>*活动预约电话
            <br />
        </td>
        <td>{booking_hotline}
            <br />
        </td>
    </tr>
    <tr>
        <td>*填报单位意见
            <br />
        </td>
        <td colspan="3">{filling_company_opinion}
            <br />
            <div style="float: right;margin-right: 150px;margin-bottom: 20px;"><p >盖章：</p></div>
        </td>
    </tr>
    <tr>
        <td>说明
            <br />
        </td>
        <td colspan="3">{instructions}
            <br />
        </td>
    </tr>
    <tr>
        <td>评估分值
            <br />
        </td>
        <td colspan="3">{evaluation_score}
            <br />
        </td>
    </tr>
    </tbody>
</table>
<script>
    $(document).ready(function () {
        $("input[name='evaluation_score']").attr("readonly",true);
        $("textarea").css('width','98%');
        var activitybox = document.getElementsByName("activitybox");
        var str = [];
        for(var i=0;i<activitybox.length;i++) {
            activitybox[i].onchange=function() {
                if(this.checked) {
                    // str = str + this.value + ",";
                    str.push(this.value);
                }else {
                    // str = str.replace(this.value,",");
                    target_index = str.indexOf(this.value);
                    str.splice(target_index,1);
                }
                $("input[name='activity_form']").val(str);
            }
        }

    });
</script>