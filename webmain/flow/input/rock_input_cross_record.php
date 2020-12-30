<?php defined('HOST') or die('not access'); ?>

<script>
    var b_cross = null;
    $(document).ready(function () {
        {params}
        let expert_id = params.expert_id;
        b_cross = $('#cross_table').bootstable({
            url: js.getajaxurl('get_cross_record', 'expert_manage', 'main', {}),
            params: {'expert_id': expert_id},
            tablename: 'item_query', fanye: true,
            columns: [{
                text: '类型', dataIndex: 'type'
            }, {
                text: '年度', dataIndex: 'all_year', sortable: true
            }, {
                text: '项目负责人', dataIndex: 'project_controller'
            }, {
                text: '所在单位', dataIndex: 'location_unit'
            }, {
                text: '项目类别', dataIndex: 'pile_sorts'
            }, {
                text: '项目名称', dataIndex: 'project_name'
            }, {
                text: '经费/万元', dataIndex: 'money'
            }, {
                text: '预计完成时间', dataIndex: 'expected_time', sortable: true
            }],
        });
        // var c = {
        //     reload:function(){
        //         a.reload();
        //     },
        //     search:function(){
        //         a.setparams({
        //             //需搜索的内容
        //             type:get('type').value,
        //             all_year:get('all_year').value,
        //             project_name:get('project_name').value,
        //         },true);
        //     },
        // };
        // js.initbtn(c);
    });

    function cross_search() {
        b_cross.setparams({
            //需搜索的内容
            type: get('type').value,
            all_year: get('all_year').value,
            project_name: get('project_name').value,
        }, true);
    }
</script>