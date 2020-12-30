<?php defined('HOST') or die('not access');
?>
<script>
    $(document).ready(function (){
        {params}
        let uid = params.uid;
        var a = {
            init: function () {
                js.ajax(js.getajaxurl('apply_sup_detail', 'process_supervision', 'main'), {uid: uid}, function (data) {
                    a.initshow(data);
                }, 'post,json');
            },
            initshow: function (data) {
                $("#name1").val(data['rows'][0].name);
                $("#deptname").val(data['rows'][0].deptname);
                $("#year").val(data['rows'][0].year);
            }
        };
        js.initbtn(a);
        a.init();

        $('#pro').bootstable({
            params: {uid: uid},
            url: js.getajaxurl('apply_sup_detail', 'process_supervision', 'main', {}),
            storeafteraction: 'apply_sup_detailafter',
            isshownumber:false,
            columns: [{
                text: '申报类型', dataIndex: 'table'
            }, {
                text: '项目名称', dataIndex: 'course_name'
            }, {
                text: '申报时间', dataIndex: 'day'
            }, {
                text: '操作', dataIndex: 'caoz'
            }],
        });

        $('#return').click(function () {
            closenowtabs();
        });
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
<div class="tab-content">
    <div class="tab-pane active" id="expert_info">
        <div class="header_title">
            <p>基本信息</p>
        </div>
        <form class="three_columns">
            <div class="form-group">
                <label>姓名:</label>
                <input type="text" class="form-control" readonly id="name1">
            </div>
            <div class="form-group">
                <label>单位:</label>
                <input type="text" class="form-control" readonly id="deptname">
            </div>
            <div class="form-group">
                <label>年度:</label>
                <input type="text" class="form-control" readonly id="year">
            </div>
        </form>
        <div class="header_title">
            <p>关联项目</p>
            <div id="pro"></div>
        </div>
        <button class="btn btn-default" type="button" id="return">返回</button>
    </div>
</div>
