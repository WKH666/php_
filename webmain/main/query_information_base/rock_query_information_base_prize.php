<?php if(!defined('HOST'))die('not access');?>
<script>
    $(document).ready(function(){
        {params}
        var atype=params.atype,zt=params.zt;
        if(!zt)zt='';
        var a = $('#view_{rand}').bootstable({
            tablename:'award_query',params:{'atype':atype,'zt':zt},fanye:true,
            url:publicstore('{mode}','{dir}'),
            storeafteraction:'inforprizeafter',storebeforeaction:'inforprizebefore',
            columns:[{
                text:'时间',dataIndex:'award_time',sortable:true
            },{
                text:'获奖者',dataIndex:'winner'
            },{
                text:'获奖单位',dataIndex:'winning_unit'
            },{
                text:'奖项',dataIndex:'prize'
            },{
                text:'奖项内容',dataIndex:'prize_content'
            },{
                text:'颁发机构',dataIndex:'issuing_authority'
            }],
        });

        var c = {
            reload:function(){
                a.reload();
            },
            search:function(){
                a.setparams({
                    //需搜索的内容
                    award_time : get('datepicker').value,
                    data_type : get('dropdown').innerText,
                    search_content : get('searchInput').value
                },true);
            },
        };
        js.initbtn(c);
        laydate.render({
            elem: '#datepicker',
            type: 'year'
        });
        $(".dropdown-menu").on('click','li',function(){
            let droptarget = $(this).text();
            $("#dropdown span:first").text(droptarget);
        });
    });
</script>
<style>
    .date_yearpicker{
        display: flex;
        flex-direction: row;
        width: 100%;
        margin: 10px 0;
    }
    .date_yearpicker label{
        margin: 0;
        padding: 0;
        width: 100px;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .msg_search{
        display: flex;
        flex-direction: row;
        width: 100%;
        margin: 10px 0;
    }
    .msg_search label{
        margin: 0;
        padding: 0;
        width: 100px;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .msg_search .input-group{
        width: 100%;
        border: 1px solid #3071A9;
        border-radius: 5px;
    }
    .msg_search .input-group-btn{
        border: none;
    }
    .msg_search #dropdown{
        border: none;
    }
    .msg_search #searchInput{
        border: none;
    }
</style>
<div class="container-prize">
    <div class="date_yearpicker">
        <label>时间范围:</label>
        <input type="text" class="form-control" id="datepicker" autocomplete="off">
    </div>
    <div class="msg_search">
        <label>信息搜索:</label>
        <div class="input-group">
            <div class="input-group-btn">
                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="dropdown">
                    <span>选项</span>
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu">
                    <li><a href="#">获奖者</a></li>
                    <li><a href="#">获奖单位</a></li>
                    <li><a href="#">奖项内容</a></li>
                    <li><a href="#">颁发机构</a></li>
                </ul>
            </div>
            <input type="text" class="form-control" placeholder="请输入搜索内容" id="searchInput">
            <div class="input-group-btn">
                <button class="btn btn-primary" type="button" click="search" autocomplete="on">搜索</button>
            </div>
        </div>
    </div>
</div>
<div class="blank10"></div>
<div id="view_{rand}"></div>