<?php defined('HOST') or die('not access');?>
<script >
    $(document).ready(function(){
        {params}
        let id = params.id;
        console.log(id);
        var c={
            init:function(){
                js.ajax(js.getajaxurl('getchecks', 'mail','main'),{results_id : id},function (data) {
                    c.initshow(data);
                },'post,json');
            },
            initshow:function(data){
                $("#send_type").val(data.send_type);
                $("#receive_id").val(data.receive_id);
                $("#send_titles").val(data.send_title);
                $("#send_remark").val(data.send_remark);
                if (data.is_send==1) {
                    $('#is_send').attr('checked',true);
                    document.getElementById('is_send2').innerText = "是";
                }else {
                    document.getElementById('is_send2').innerText = "否"
                }
                $("#excel_{rand}").attr('href',data.filepath);
                $("#excel_{rand}").text(data.filename+"      下载");
            }
        }
        js.initbtn(c);
        c.init();
    });
    function ba() {
        closenowtabs();

    }
</script>

<style>

    textarea#send_remark{
        height: 100px;
    }
    input#is_send{
        height: 25px;
        border: none;
        width: 25px;
    }
    label#is_send2{
        width: 25px;
        margin-top: 5px;
    }
    three_columns{
        display: grid;
        grid-template-columns:  1fr 1fr  1fr;
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
</style>
<div>
    <div class="header_title">
        <p>基础信息</p>
    </div>
    <form class="three_columns">
        <div class="form-group">
            <label>群发类型:</label>
            <input type="text" class="form-control" readonly id="send_type">
        </div>
    </form>
    <form class="three_columns">
        <div class="form-group">
            <label>接受者:</label>
            <input type="text" class="form-control" readonly id="receive_id">
        </div>
    </form>
    <form class="three_columns">
        <div class="form-group">
            <label>发送标题:</label>
            <textarea  class="form-control" readonly id="send_titles"></textarea>
        </div>
    </form>
    <form class="three_columns">
        <div class="form-group">
            <label>发送说明:</label>
            <textarea class="form-control" readonly id="send_remark"></textarea>
        </div>
    </form>
    <form class="three_columns">
        <div class="form-group">
            <label >同步邮件:</label>
            <input type="radio" class="form-control" disabled id="is_send">
            <label id="is_send2"></label>
        </div>
    </form>
    <div class="header_title">
        <p>信息附件</p>
    </div>

    <form class="three_columns">
        <div class="form-group">
        <a id="excel_{rand}" href="" target="_blank" style="margin: 30px"></a>
        </div>
    </form>
    <div id="word_table2"></div>
</div>
<form class="one_columns">
    <div class="form-group" style="margin-left: 8px;margin-top: 100px">
        <button class="btn-sm" type="button"  style="margin-left: 20px" onclick="ba();">返回</button>
    </div>
</form>

</div>

