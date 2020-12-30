<?php if(!defined('HOST'))die('not access');?>
<script>
    $(document).ready(function(){
        {params}
        var atype=params.atype,zt=params.zt;
        var status_arr = [];
        if(!zt)zt='';
        var a = $('#view_{rand}').bootstable({
            tablename:'achievement_query',
            params:{'atype':atype,'zt':zt},
            fanye:true,
            checked:true,
            url:publicstore('{mode}','{dir}'),
            storeafteraction:'inforresultsafter',storebeforeaction:'inforresultsbefore',
            columns:[{
                text:'成果编号',dataIndex:'identifier'
            },{
                text:'成果形式',dataIndex:'form'
            },{
                text:'名称',dataIndex:'name'
            },{
                text:'作者',dataIndex:'author',sortable:true
            },{
                text:'所在单位',dataIndex:'location_unit'
            },{
                text:'结项时间',dataIndex:'update_time',sortable:true
            },{
                text:'是否可见',dataIndex:'status_text'
            },{
                text:'操作',dataIndex:'caoz'
            }],
            load:function(){
                $("#visible")[0].disabled = true;
                $(".table thead input[type='checkbox']").on('click',function(){
                    $("#visible")[0].disabled = false;
                    let tbody = $(".table tbody input[type='checkbox']").length;
                    if($(this)[0].checked){
                        for(var i = 0; i< tbody ; i++){
                            status_arr.push($(".table tbody input[type='checkbox']")[i].value);
                            $(".table tbody input[type='checkbox']")[i].checked = true;
                        }
                    }else{
                        for(var i = 0; i< tbody ; i++){
                            status_arr = [];
                            $(".table tbody input[type='checkbox']")[i].checked = false;
                            $("#visible")[0].disabled = true;
                        }
                    }
                });
                $(".table tbody input[type='checkbox']").on('click',function(){
                    $("#visible")[0].disabled = false;
                    let checkbox_id = $(this).val();
                    if($(this)[0].checked){
                        status_arr.push(checkbox_id);
                    }else{
                        if(status_arr){
                            for(var i = 0; i<status_arr.length;i++){
                                if(status_arr[i] == checkbox_id){
                                    status_arr.splice(i,1);
                                    if(status_arr.length == 0){
                                        $("#visible")[0].disabled = true;
                                    }else{}
                                }
                            }
                        }else{}
                    }
                });
            }
        });
        var c = {
            reload:function(){
                a.reload();
            },
            search:function(){
                a.setparams({
                    //需搜索的内容
                    identifier:get('results_number').value,
                    form:get('results_form').value,
                    name:get('results_name').value,
                    author:get('author').value,
                    location_unit:get('unit').value,
                    update_time:get('post_time').value,
                    keywords:get('keywords').value,
                },true);
            },
            daoru:function(){
                managelistinforresults = a;
                addtabs({num:'daoruinforresults',url:'flow,input,daoru,modenum=inforresults',icons:'plus',name:'导入成果信息与文档'});
            },
            searches:function(){
                $("#results_number").val('');
                $("#results_form").val('');
                $("#results_name").val('');
                $("#author").val('');
                $("#unit").val('');
                $("#post_time").val('');
                $("#keywords").val('');
                a.setparams({
                    //需搜索的内容
                    identifier:'',
                    form:'',
                    name:'',
                    author:'',
                    location_unit:'',
                    update_time:'',
                    keywords:''
                },true);
            },
            visible:function () {
                js.ajax(js.getajaxurl('staresults','information_base','main'),{status_arr : status_arr},function(data){
                    if(data.code == 200){
                        layer.msg(data.msg);
                        c.reload();
                    }else{
                        layer.msg(data.msg);
                    }
                },'post,json');
            }
        };
        js.initbtn(c);
        inforresultscheck = function(inforresults_id){
            var results_url = 'flow,input,check,modenum=inforresults,inforresults_id=' + inforresults_id;
            addtabs({
                num:'checkinforresults',
                url:results_url,
                icons:'',
                name:'查看成果信息'
            });
            return false;
        }
        inforresultsdel = function(current_row,current_index){
            var current_checkbox = current_row.parentNode.parentNode.children[1].children[0].checked;
            if(current_checkbox){
                js.ajax(js.getajaxurl('delresults','information_base','main'),{current_index : current_index},function(data){
                    if(data.code == 200){
                        layer.msg(data.msg);
                        c.reload();
                    }else{
                        layer.msg(data.msg);
                    }
                },'post,json');
            }else{
                layer.msg('未勾选复选框');
            }
        }

    });
</script>
<style>
    .results-form{
        background:#F7F7F7;
    }
    .results-form form{
        display: flex;
        flex-direction: row;
        flex-wrap: wrap;
        padding-top: 10px;
    }
    .form-group{
        display: flex;
        flex-direction: row;
        /*margin-right: 15px;*/
    }
    .form-group:nth-last-child(2){
        margin-right:20px;
    }
    .form-group label{
        width:15rem;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .form-group button{
        margin: 0 10px;
    }
    #search,#daoru,#visible{
        background: #108EE9;
        color: #fff;
        border-color: #108EE9;
    }
</style>
<div class="results-form">
    <form>
        <div class="form-group">
            <label>成果编号:</label>
            <input type="text" class="form-control" id="results_number" name="results_number" placeholder="请输入" autocomplete="off">
        </div>
        <div class="form-group">
            <label>成果形式:</label>
            <input type="text" class="form-control" id="results_form" name="results_form" placeholder="请输入" autocomplete="off">
        </div>
        <div class="form-group">
            <label>名称:</label>
            <input type="text" class="form-control" id="results_name" name="results_name" placeholder="请输入" autocomplete="off">
        </div>
        <div class="form-group">
            <label>作者:</label>
            <input type="text" class="form-control" id="author" name="author" placeholder="请输入" autocomplete="off">
        </div>
        <div class="form-group">
            <label>所在单位:</label>
            <input type="text" class="form-control" id="unit" name="unit" placeholder="请输入" autocomplete="off">
        </div>
        <div class="form-group">
            <label>结项时间:</label>
            <input type="text" class="form-control" id="post_time" name="post_time" placeholder="请输入" autocomplete="off">
        </div>
        <div class="form-group">
            <label>关键词:</label>
            <input type="text" class="form-control" id="keywords" name="keywords" placeholder="请输入名称或者摘要关键词检索" autocomplete="off">
        </div>
        <div class="form-group">
            <button type="button" class="btn btn-default" id="search" click="search">搜索</button>
            <button type="reset" class="btn btn-default" id="reset" click="searches">重置</button>
            <button type="button" class="btn btn-default" id="daoru" click="daoru">导入</button>
            <button type="button" class="btn btn-default" id="visible" click="visible">批量可见</button>
        </div>
    </form>
</div>
<div class="blank10"></div>
<div id="view_{rand}"></div>
