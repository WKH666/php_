<?php defined('HOST') or die('not access');?>
<script >
$(document).ready(function(){
	{params}
	let inforresults_id = params.inforresults_id;
    let achievement_file = '';
	var c={
		init:function(){
            js.ajax(js.getajaxurl('getresults','information_base','main'),{results_id : inforresults_id},function (data) {
                c.initshow(data);
            },'post,json');
		},
		initshow:function(data){
            $("#identifiers").val(data.identifier);
            $("#forms").val(data.form);
            $("#update_times").val(data.update_time);
            $("#names").val(data.name);
            $("#authors").val(data.author);
            $("#location_units").val(data.location_unit);
            $("#serial_titles").val(data.serial_title);
            $("#abstracts").val(data.abstract);
            if(data.file){
                let files = data.file;
                for(var i = 0; i <files.length; i++){
                    achievement_file += '        <tr>\n' +
                        '            <td>'+ files[i].achievement_filename +'</td>\n' +
                        '            <td><a href="'+ files[i].achievement_filepath +'" target="_blank">下载</a></td>\n' +
                        '        </tr>';
                }
                $("#results_tbody").append(achievement_file);
            }
		}
	}
    js.initbtn(c);
    c.init();

    backgo = function(){
        addtabs({num:'results',url:'main,query_information_base,results',name:'成果信息',hideclose:true});
        return false;
    }

});
</script>

<style>
    .three_columns{
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
    }
    .form-group{
        display: flex;
        flex-direction: row;
    }
    .form-group label{
        display: flex;
        justify-content: center;
        align-items: center;
        margin: 0;
        padding: 0;
    }
    .three_columns .form-group label{
        width: 13rem;
    }
    .one_columns .form-group label{
        width: 11rem;
    }
    .one_columns .form-group textarea{
        height: 10rem;
    }
    .header_title{
        background: #CDE3F1;
        border-radius: 5px;
    }
    .header_title p{
        padding: 5px 0;
    }
    .header_title:nth-of-type(2) p{
        margin: 0;
    }
    #results_table{
        width:100%;
    }
    #results_table thead,tbody tr td{
        height: 30px;
    }
    #results_table thead tr td{
        text-align: center;
        background: #F2F2F2;
    }
    #results_table tbody{}
    #results_table tbody tr td{
        text-align: center;
        border: 1px solid #d1d1d1;
        box-sizing: border-box;
    }
    #results_table tbody tr td:nth-of-type(1){
        text-align: left;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
    }
</style>
<div>
    <div class="header_title">
        <p>基本信息</p>
    </div>
    <form class="three_columns">
        <div class="form-group">
            <label>成果编号:</label>
            <input type="text" class="form-control" readonly id="identifiers">
        </div>
        <div class="form-group">
            <label>成果形式:</label>
            <input type="text" class="form-control" readonly id="forms">
        </div>
        <div class="form-group">
            <label>结项时间:</label>
            <input type="text" class="form-control" readonly id="update_times">
        </div>
    </form>
    <form class="three_columns">
        <div class="form-group">
            <label>名称:</label>
            <input type="text" class="form-control" readonly id="names">
        </div>
        <div class="form-group">
            <label>作者:</label>
            <input type="text" class="form-control" readonly id="authors">
        </div>
        <div class="form-group">
            <label>所在单位:</label>
            <input type="text" class="form-control" readonly id="location_units">
        </div>
    </form>
    <form class="one_columns">
        <div class="form-group">
            <label>发表刊物:</label>
            <input type="text" class="form-control" readonly id="serial_titles">
        </div>
    </form>
    <form class="one_columns">
        <div class="form-group">
            <label>摘要:</label>
            <textarea class="form-control" readonly id="abstracts"></textarea>
        </div>
    </form>
    <div class="header_title">
        <p>成果文档</p>
    </div>
    <table id="results_table" cellpadding="0" cellspacing="0">
        <thead>
        <tr>
            <td>文档名称</td>
            <td>操作</td>
        </tr>
        </thead>
        <tbody id="results_tbody">

        </tbody>
    </table>
    <div class="blank20"></div>
    <button type="button" class="btn btn-primary" onclick="return backgo();">返回</button>
</div>