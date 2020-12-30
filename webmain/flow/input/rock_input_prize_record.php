<?php defined('HOST') or die('not access'); ?>
<script>
    var b_prize = null;
    $(document).ready(function () {
        {params}
        let expert_id = params.expert_id;
        b_prize = $('#prize_table').bootstable({
            url: js.getajaxurl('get_prize_record', 'expert_manage', 'main', {}),
            params: {'expert_id': expert_id},
            tablename: 'award_query', fanye: true,
            columns: [{
                text: '时间', dataIndex: 'award_time', sortable: true
            }, {
                text: '获奖者', dataIndex: 'winner'
            }, {
                text: '获奖单位', dataIndex: 'winning_unit'
            }, {
                text: '奖项', dataIndex: 'prize'
            }, {
                text: '奖项内容', dataIndex: 'prize_content'
            }, {
                text: '颁发机构', dataIndex: 'issuing_authority'
            }],
        });

        // var c = {
        //     reload:function(){
        //         a.reload();
        //     },
        //     search:function(){
        //         a.setparams({
        //             //需搜索的内容
        //             award_time:get('award_time').value,
        //             winning_unit:get('winning_unit').value,
        //             prize:get('prize').value,
        //         },true);
        //     },
        // };
        // js.initbtn(c);
        // },
    });

    function prize_search() {
        b_prize.setparams({
            //需搜索的内容
            award_time: get('award_time').value,
            winning_unit: get('winning_unit').value,
            prize: get('prize').value,
        }, true);
    }

</script>

