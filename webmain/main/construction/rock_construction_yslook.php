<?php if (!defined('HOST')) die('not access'); ?>
<div align="center">
    <div style="padding:10px;width:720px">
        <div class="headerTitle" style="width: 100%;">
            <div>
                <span class="titleContent f20">验收信息</span>
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
                <td style="width: 20%" class="pad2" align="right">验收时间：</td>
                <td align="left"><font style="padding-left: 1rem;" name="accept_date"></font></td>
            </tr>
            <tr>
                <td style="width: 20%" class="pad2" align="right">验收地点：</td>
                <td align="left"><font style="padding-left: 1rem;" name="accept_place"></font></td>
            </tr>
            <tr>
                <td style="width: 20%" class="pad2" align="right">存在问题：</td>
                <td align="left"><font style="padding-left: 1rem;" name="quality_cdt"></font></td>
            </tr>
            <tr>
                <td style="width: 20%" class="pad2" align="right">验收人员：</td>
                <td align="left"><font style="padding-left: 1rem;" name="it_order"></font></td>
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