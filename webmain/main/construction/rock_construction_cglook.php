<?php if (!defined('HOST')) die('not access'); ?>
<div align="center">
    <div style="padding:10px;width:720px">
        <div class="headerTitle" style="width: 100%;">
            <div>
                <span class="titleContent f20">采购信息</span>
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
                <td style="width: 20%" class="pad2" align="right">经费金额(元)：</td>
                <td align="left"><font style="padding-left: 1rem;" name="budget_amount"></font></td>
            </tr>
            <tr>
                <td style="width: 20%" class="pad2" align="right">采购项目名称：</td>
                <td align="left"><font style="padding-left: 1rem;" name="pack_name"></font></td>
            </tr>
            <tr>
                <td style="width: 20%" class="pad2" align="right">采购项目编码：</td>
                <td align="left"><font style="padding-left: 1rem;" name="pack_encode"></font></td>
            </tr>
            <tr>
                <td style="width: 20%" class="pad2" align="right">采购项目金额(元)：</td>
                <td align="left"><font style="padding-left: 1rem;" name="budget"></font></td>
            </tr>
            <tr>
                <td style="width: 20%" class="pad2" align="right">采购方式：</td>
                <td align="left"><font style="padding-left: 1rem;" name="purchase_type"></font></td>
            </tr>
            <tr>
                <td style="width: 20%" class="pad2" align="right">中标金额(元)：</td>
                <td align="left"><font style="padding-left: 1rem;" name="bid_budget"></font></td>
            </tr>
            <tr>
                <td style="width: 20%" class="pad2" align="right">中标供应商：</td>
                <td align="left"><font style="padding-left: 1rem;" name="bid_supplier"></font></td>
            </tr>
            <tr>
                <td style="width: 20%" class="pad2" align="right">中标时间：</td>
                <td align="left"><font style="padding-left: 1rem;" name="bid_date"></font></td>
            </tr>
            <tr>
                <td style="width: 20%" class="pad2" align="right">合同编号：</td>
                <td align="left"><font style="padding-left: 1rem;" name="contract_code"></font></td>
            </tr>
            <tr>
                <td style="width: 20%" class="pad2" align="right">合同签订时间：</td>
                <td align="left"><font style="padding-left: 1rem;" name="contract_date"></font></td>
            </tr>
            <tr>
                <td style="width: 20%" class="pad2" align="right">合同金额(元)：</td>
                <td align="left"><font style="padding-left: 1rem;" name="contract_money"></font></td>
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