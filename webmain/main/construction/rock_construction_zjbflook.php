<?php if (!defined('HOST')) die('not access'); ?>
<div align="center">
    <div style="padding:10px;width:720px">
        <div class="headerTitle" style="width: 100%;">
            <div>
                <span class="titleContent f20">经费信息</span>
            </div>
        </div>
        <table cellspacing="0" border="1" width="100%" align="center" cellpadding="0">
            <tr>
                <td style="width: 20%" class="pad1" align="right">经费年份：</td>
                <td align="left"><font style="padding-left: 1rem;" name="construction_annual"></font></td>
            </tr>
            <tr>
                <td style="width: 20%" class="pad1" align="right">经费项目编号：</td>
                <td align="left"><font style="padding-left: 1rem;" name="budget_code"></font></td>
            </tr>
            <tr>
                <td style="width: 20%" class="pad1" align="right">经费项目名称：</td>
                <td align="left"><font style="padding-left: 1rem;" name="budget_name"></font></td>
            </tr>
            <tr>
                <td style="width: 20%" class="pad1" align="right">经费类别：</td>
                <td align="left"><font style="padding-left: 1rem;" name="budget_category"></font></td>
            </tr>
            <tr>
                <td style="width: 20%" class="pad2" align="right">经费来源：</td>
                <td align="left"><font style="padding-left: 1rem;" name="budget_source"></font></td>
            </tr>
            <tr>
                <td style="width: 20%" class="pad2" align="right">经费卡号：</td>
                <td align="left"><font style="padding-left: 1rem;" name="budget_card_number"></font></td>
            </tr>
            <tr>
                <td style="width: 20%" class="pad2" align="right">经费金额(元)：</td>
                <td align="left"><font style="padding-left: 1rem;" name="budget_amount"></font></td>
            </tr>
            <tr>
                <td style="width: 20%" class="pad2" align="right">经费余额(元)：</td>
                <td align="left"><font style="padding-left: 1rem;" name="remainder"></font></td>
            </tr>
            <tr>
                <td style="width: 20%" class="pad2" align="right">所属部门：</td>
                <td align="left"><font style="padding-left: 1rem;" name="department"></font></td>
            </tr>
            <tr>
                <td style="width: 20%" class="pad2" align="right">经费负责人：</td>
                <td align="left"><font style="padding-left: 1rem;" name="budget_director"></font></td>
            </tr>
            <tr>
                <td style="width: 20%" class="pad2" align="right">联系电话：</td>
                <td align="left"><font style="padding-left: 1rem;" name="budget_director_number"></font></td>
            </tr>
            <tr>
                <td style="width: 20%" class="pad2" align="right">创建人：</td>
                <td align="left"><font style="padding-left: 1rem;" name="create_name"></font></td>
            </tr>
            <tr>
                <td style="width: 20%" class="pad2" align="right">创建日期：</td>
                <td align="left"><font style="padding-left: 1rem;" name="create_date"></font></td>
            </tr>
            <tr>
                <td style="width: 20%" class="pad2" align="right">拨付资料：</td>
                <td align="left">
                    <div id="view_fileidview_{rand}" style="padding-left: 1rem;width:100%;height:80px;border:1px #cccccc solid; background:white;overflow:auto"></div>
                </td>
            </tr>
            <tr>
                <td style="width: 20%" class="pad2" align="right">拨付时间：</td>
                <td align="left"><font style="padding-left: 1rem;" name="appropriation_time"></font></td>
            </tr>
        </table>

        <script>
            $(document).ready(function () {
                {params};
                js.ajax(
                    js.getajaxurl('loadform', 'construction', 'main', {
                        id: params.id,
                        table: jm.base64encode('appropriation')
                    }), {}, function(rs){
                    $.each(rs.data, function(k,el){
                        if($('font[name="'+k+'"]')){
                            $('font[name="'+k+'"]').html(el);
                        }
                    });
                },'post,json');
            });
        </script>
    </div>
</div>