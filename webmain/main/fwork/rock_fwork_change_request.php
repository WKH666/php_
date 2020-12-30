<?php if (!defined('HOST')) die('not access'); ?>
<script>
    var  a='';
    $(document).ready(function () {
        {params}
        var a = $('#view_{rand}').bootstable({
            tablename: 'flow_bill', fanye: true,
            url: js.getajaxurl('change_request', 'fwork', 'main', {}),
            storeafteraction: 'change_requestafter',
            columns: [{
                text: '登记号', dataIndex: 'sericnum'
            }, {
                text: '项目名称', dataIndex: 'course_name'
            }, {
                text: '变更类型', dataIndex: 'change_type'
            }, {
                text: '变更进度', dataIndex: 'change_course'
            }, {
                text: '申请时间', dataIndex: 'applydt', sortable: true
            }, {
                text: '操作', dataIndex: 'caoz'
            }],
        });
        assessmentList = a;

        var c = {
            reload: function () {
                a.reload();
            },
            search: function () {
                a.setparams({
                    sericnum: get('sericnum').value,
                    project_name: get('project_name').value,
                    apply_type: get('apply_type').value
                }, true);
            },
        };
        js.initbtn(c);
        look = function (request_id) {
            var results_url = 'flow,input,request_check,modenum=results,request_id=' + request_id;
            addtabs({
                num: 'results',
                url: results_url,
                icons: '',
                name: '查看变更课题记录'
            });
            return false;
        };
    });

    function request_add() {
        assessmentList = a;
        var results_url = 'flow,input,request_add,modenum=results';
        addtabs({
            num: 'results',
            url: results_url,
            icons: '',
            name: '发起变更课题申报'
        });
    }
</script>
<style>
    .cross-form {
        background: #F7F7F7;
    }

    .cross-form form {
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

    #search, #downout {
        background: #108EE9;
        color: #fff;
        border-color: #108EE9;
    }

    .tips {
        text-indent: 8em;
    }

    .modal-backdrop {
        z-index: 0;
        display: none;
    }

    .modal-header {
        border-bottom: 0px;
    }

    .modal-footer {
        border-top: 0px;
    }
</style>
<div class="cross-form">
    <form>
        <div class="form-group">
            <label>登记号:</label>
            <input type="text" class="form-control" id="sericnum" placeholder="请输入">
        </div>
        <div class="form-group">
            <label>项目名称:</label>
            <input type="text" class="form-control" id="project_name" placeholder="请输入">
        </div>
        <div class="form-group">
            <label>变更类型:</label>
            <input type="text" class="form-control" id="apply_type" placeholder="请输入">
        </div>
        <div class="form-group">
            <button type="button" class="btn btn-default" id="search" click="search">搜索</button>
            <button type="reset" class="btn btn-default" id="reset">重置</button>
            <button type="button" class="btn btn-primary" id="add" onclick="request_add()">添加</button>
        </div>
    </form>
</div>
<div class="blank10"></div>
<div id="view_{rand}"></div>
