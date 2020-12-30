<?php if (!defined('HOST')) die('not access'); ?>
<div align="center">
    <div style="padding:10px;width:720px">
        <div class="headerTitle" style="width: 100%;">
            <div>
                <span class="titleContent f20">付款信息</span>
            </div>
        </div>
        <table cellspacing="0" border="1" width="100%" align="center" cellpadding="0">
            <tr>
                <td style="width: 20%" class="pad1" align="right">项目编号：</td>
                <td align="left"><font style="padding-left: 1rem;" name="budget_code"></font></td>
            </tr>
            <tr>
                <td style="width: 20%" class="pad1" align="right">项目名称：</td>
                <td align="left"><font style="padding-left: 1rem;" name="budget_name"></font></td>
            </tr>
            <tr>
                <td style="width: 20%" class="pad2" align="right">付款凭证号：</td>
                <td align="left"><font style="padding-left: 1rem;" name="pay_code"></font></td>
            </tr>
            <tr>
                <td style="width: 20%" class="pad2" align="right">付款时间：</td>
                <td align="left"><font style="padding-left: 1rem;" name="pay_date"></font></td>
            </tr>
            <tr>
                <td style="width: 20%" class="pad2" align="right">已付金额(元)：</td>
                <td align="left"><font style="padding-left: 1rem;" name="a_pay_money"></font></td>
            </tr>
            <tr>
                <td style="width: 20%" class="pad2" align="right">应付金额(元)：</td>
                <td align="left"><font style="padding-left: 1rem;" name="s_pay_money"></font></td>
            </tr>
            <tr>
                <td style="width: 20%" class="pad2" align="right">余额(元)：</td>
                <td align="left"><font style="padding-left: 1rem;" name="pay_budget"></font></td>
            </tr>
        </table>

        <script>
            $(document).ready(function () {
                {params};
                js.ajax(
                    js.getajaxurl('loadpubproject', 'construction', 'main', {id: params.id}), {}, function(rs){
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