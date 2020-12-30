<?php defined('HOST') or die('not access'); ?>

<script>
    var b_paper = null;
    $(document).ready(function(){
        {params}
        let expert_id = params.expert_id;
        b_paper = $('#paper_table').bootstable({
            tablename:'thesis_query',fanye:true,
            url:js.getajaxurl('get_paper_record','expert_manage','main', {}),
            params:{'expert_id':expert_id},
            columns:[{
                text:'年度',dataIndex:'year'
            },{
                text:'作者',dataIndex:'author'
            },{
                text:'所在单位',dataIndex:'location_unit'
            },{
                text:'题名',dataIndex:'title'
            },{
                text:'刊名',dataIndex:'serial_title'
            },{
                text:'Roll-卷',dataIndex:'roll'
            },{
                text:'Period-期',dataIndex:'period'
            },{
                text:'PageCount-页码',dataIndex:'pagecount'
            }],
        });

    });

    function paper_search() {
        b_paper.setparams({
            //需搜索的内容
            year:get('year').value,
            title:get('title').value,
            serial_title:get('serial_title').value,
        },true);
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