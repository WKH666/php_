<?php if(!defined('HOST'))die('not access');?>
<script >
    $(document).ready(function() {
        //{params}
        //var atype=params.atype;
        var bools = false;
        var a = $('#change_review_{rand}').bootstable({
            //tablename:'flow_bill',
            //params:{'atype':atype},
            fanye: true,
            //url:publicstore('{mode}','{dir}'),
            url: js.getajaxurl('review_list', 'sheke_fwork', 'main', {}),
            storeafteraction: 'review_after',
            storebeforeaction: 'review_before',
            columns: [{
                text: '登记号', dataIndex: 'sericnum'
            }, {
                text: '项目名称', dataIndex: 'pn'
            }, {
                text: '变更类型', dataIndex: 'ct'
            }, {
                text: '审核状态', dataIndex: 'zt', sortable: true
            }, {
                text: '审核时间', dataIndex: 'optdt', sortable: true
            }, {
                text: '操作', dataIndex: 'caoz'
            }],
        });
        var c = {
            reload: function () {
                a.reload();
            },
            search: function () {
                console.log(get('change_type_{rand}').value);
                a.setparams({
                    sericnum: get('sericnum_{rand}').value,
                    project_name: get('project_name_{rand}').value,
                    change_type: get('change_type_{rand}').value
                }, true);
            },
            reset: function () {
                $("#sericnum_{rand}").val('');
                $("#project_name_{rand}").val('');
                $("#change_type_{rand}").find("option:selected").removeAttr("selected");
                a.setparams({
                    //需搜索的内容
                    sericnum: '',
                    project_name: '',
                    change_type: '',
                }, true);
            },
        };
        js.initbtn(c);
        reviewcaoz = function (id) {
            assessmentList = a;
            var results_url = 'flow,input,reviewcaoz,modenum=reviewcaoz,id=' + id;
            addtabs({
                num: 'reviewcaoz',
                url: results_url,
                icons: '',
                name: '变更审核操作'
            });

            return false;
        },
    reviewcheck = function (id) {
        //assessmentList = a;
        var results_url = 'flow,input,reviewcheck,modenum=reviewcaoz,id=' + id;
        addtabs({
            num: 'reviewcheck',
            url: results_url,
            icons: '',
            name: '查看变更审核'
        });
        return false;
    }



    });
</script>
<div>
    <table width="100%">
        <tr>
            <td  style="padding-left:10px">
                <input class="form-control" style="width:180px" id="sericnum_{rand}"   placeholder="登记号">
            </td>
            <td  style="padding-left:10px">
                <input class="form-control" style="width:180px" id="project_name_{rand}"   placeholder="项目名称">
            </td>
            <td  style="padding-left:10px">
                <select class="form-control" style="width:180px" id="change_type_{rand}"   placeholder="变更类型">
                    <option>请选择</option>
                    <option value="0">变更项目负责人</option><option value="1">变更或增加课题组成员</option><option value="2">变更项目管理单位</option>
                    <option value="3">改变成果形式</option><option value="4">改变项目名称</option><option value="5">研究内容有重大调整</option>
                    <option value="6">延期</option><option value="7">撤项</option><option value="8">其他</option>
                </select>
            </td>

            <td  style="padding-left:10px">
                <button class="btn btn-primary" click="search" type="button">搜索</button>
            </td>
            <td  style="padding-left:10px">
                <button class="btn btn-default" click="reset" type="button" id="reset">重置</button>
            </td>
            <td  width="80%" style="padding-left:10px">
            </td>
        </tr>
    </table>

</div>
<div class="blank10"></div>
<div id="change_review_{rand}"></div>
