<?php defined('HOST') or die('not access'); ?>

<script>
    var b_fine = null;
    var find_id_arr = [];
    $(document).ready(function () {
        {params}
        let expert_id = params.expert_id;
        b_fine = $('#fine_table').bootstable({
            tablename: 'penalty_record', fanye: true,
            url: js.getajaxurl('get_fine_record', 'expert_manage', 'main', {}),
            storeafteraction: 'get_fine_recordafter',
            params: {'expert_id': expert_id},
            columns: [{
                text: '网评记录', dataIndex: 'pici_name'
            }, {
                text: '关联项目', dataIndex: 'course_name'
            }, {
                text: '扣罚原因', dataIndex: 'penalty_reason'
            }, {
                text: '扣罚时间', dataIndex: 'penalty_time'
            }, {
                text: '操作', dataIndex: 'caoz'
            }],
        });

    });

    function fine_search() {
        b_fine.setparams({
            //需搜索的内容
            penalty_time: get('penalty_time').value,
        }, true);
    }

    fine_recordclear = function (fine_id) {
        console.log(find_id_arr);
        find_id_arr.push(fine_id);
        js.ajax(js.getajaxurl('fine_recordclear', 'expert_manage', 'main'), {find_id: find_id_arr}, function (ds) {
            if (ds == true) {
                b_fine.reload();
            } else {
                console.log(ds);
            }
        }, 'post,json');
    };

    fine_recordreset = function () {
        find_id_arr = [];
        js.ajax(js.getajaxurl('fine_reecordreset', 'expert_manage', 'main'), {}, function (ds) {
            if (ds == true) {
                b_fine.reload();
            } else {
                console.log(ds);
            }
        }, 'post,json');
    }
    /*var c1 = {
            reload:function(){
                b1.reload();
            },
            search:function(){
                b1.setparams({
                    //需搜索的内容
                    year:get('year').value,
                    title:get('title').value,
                    serial_title:get('serial_title').value,
                },true);
            },
            reset:function(){
                $("#year").val('');
                $("#title").val('');
                $("#serial_title").val('');
                b1.setparams({
                    //需搜索的内容
                    year:'',
                    title:'',
                    serial_title:'',
                },true);
            },
        };
        js.initbtn(c1);*/
</script>