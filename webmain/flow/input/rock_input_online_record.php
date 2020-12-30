<?php defined('HOST') or die('not access'); ?>

<script>
    $(document).ready(function(){
        {params}
        let expert_id = params.expert_id;
        var b = $('#online_table').bootstable({
            tablename:'m_batch',fanye:true,
            url:js.getajaxurl('get_online_record','expert_manage','main', {}),
            storeafteraction: 'get_online_recordafter',
            params:{'expert_id':expert_id},
            columns:[{
                text:'所属批次',dataIndex:'pici_name'
            },{
                text:'评审时间',dataIndex:'pici_start_time'
            },{
                text:'项目数量',dataIndex:'totalCount'
            },{
                text:'评审状态',dataIndex:'com_status'
            },{
                text:'操作',dataIndex:'caoz'
            }],
        });
         c = {
            reload:function(){
                b.reload();
            },
            search:function(){
                b.setparams({
                    //需搜索的内容
                    pici_name:get('pici_name').value,
                    pici_start_time:get('pici_start_time').value,
                },true);
            },
        };
        js.initbtn(c);
        online_recordcheck = function(pici_id,user_id){
            var results_url = 'flow,input,project_info,modenum=results,pici_id=' + pici_id + ',user_id=' +user_id;
            addtabs({
                num:'results',
                url:results_url,
                icons:'',
                name:'批次项目信息'
            });
            return false;
        };

    });
</script>