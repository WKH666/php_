<?php if (!defined('HOST')) die('not access'); ?>

<script>
    var mail_info= '';
    var key_word_id = 0;
    console.log('{mode}', '{dir}');
    $(document).ready(function () {
        {params}
        js.ajax(js.getajaxurl('getRank', 'mail', 'main'),{id:key_word_id}, function (data) {
            console.log(data);
            // sessionStorage.removeItem('mail_rank');
            // sessionStorage.setItem('mail_rank',data);
            if (data!="管理员"){
                $("#fabu").hide();
                console.log(123);
                mail_info = $('#table_mail2').bootstable({
                    url: js.getajaxurl('maillist', 'mail', 'main', {}),
                    fanye: true,
                    celleditor: true,
                    storeafteraction: 'maillistafter',
                    storebeforeaction: 'maillistbefore',
                    columns: [
                        {
                            text: '发送标题', dataIndex: 'send_title', sortable: true
                        }, {
                            text: '发送类型', dataIndex: 'send_type', sortable: true
                        }, {
                            text: '发送时间', dataIndex: 'send_time', sortable: true
                        }, {
                            text: '操作', dataIndex: 'caoz', width: '180px'
                        }],
                });
            }else {
                $("#fabu").show();
                console.log(456);
                mail_info = $('#table_mail').bootstable({
                    url: js.getajaxurl('maillist', 'mail', 'main', {}),
                    fanye: true,
                    celleditor: true,
                    storeafteraction: 'maillistafter',
                    storebeforeaction: 'maillistbefore',
                    columns: [
                        {
                            text: '发送标题', dataIndex: 'send_title', sortable: true
                        }, {
                            text: '发送类型', dataIndex: 'send_type', sortable: true
                        }, {
                            text: '状态', dataIndex: 'send_status', sortable: true
                        }, {
                            text: '发送时间', dataIndex: 'send_time', sortable: true
                        }, {
                            text: '操作', dataIndex: 'caoz', width: '180px'
                        }],
                });
            }
        }, 'post,json');

        mailresultscheck = function(id){
            var results_url = 'flow,input,mail_check,modenum=mail_check,id='+id;
            addtabs({
                num:'mail_check',
                url:results_url,
                icons:'',
                name:'查看站内信详情'
            });
            return false;
        },
            mailresultscheck2 = function(id){
                var results_url = 'flow,input,mail_check2,modenum=mail_check2,id='+id;
                addtabs({
                    num:'mail_check2',
                    url:results_url,
                    icons:'',
                    name:'查看站内信详情'
                });
                return false;
            },
            mailresultedit = function(id){
                assessmentList=mail_info;
                var results_url = 'flow,input,mail_edit,modenum=mail_edit,id='+id;
                addtabs({
                    num:'mail_edit',
                    url:results_url,
                    icons:'',
                    name:'编辑站内信'
                });
                return false;
            },
            mailresultdel = function(id){
            js.ajax(js.getajaxurl('delresult','mail','main'),{id : id},function(data){
                if(data.code == 200){
                    layer.msg(data.msg);
                    c.reload();
                }else{
                    layer.msg(data.msg);
                }
            },'post,json');
        }
        var c = {
            reload:function(){
                mail_info.reload();
            },
            search:function(){
                mail_info.setparams({
                    //需搜索的内容
                    send_title:get('send_title').value,
                    send_time:get('send_time').value,
                },true);
            },
            reset:function(){
                $("#send_title").val('');
                $("#send_time").val('');
                mail_info.setparams({
                    //需搜索的内容
                    send_title:'',
                    send_time:'',
                },true);
            },
            fabu:function () {
                assessmentList=mail_info;
                var results_url = 'flow,input,mail_fb,modenum=mail_fb';
                addtabs({
                    num:'mail_fb',
                    url:results_url,
                    icons:'',
                    name:'发布站内信'
                });
            }
        };
        js.initbtn(c);





    });






</script>

<style>
    #mytable{
        width: 100%;
    }
    #mytable tbody{
        display: flex;
        flex-direction: row;
        justify-content: space-between;
    }
    #mytable tr{
        display: flex;
        flex-direction: row;
        align-items: center;
        width: inherit;
    }
    #mytable .form-group{
        display: flex;
        flex-direction: row;
        justify-content: center;
        align-items: center;
        margin: 10px;
    }
    #mytable .form-group label{
        width: 100px;
        margin: 0;
        text-align: center;
    }
    #mytable .form-group button{
        margin: 0 5px;
    }
    #search{
        background-color: #108EE9;
        border-color: #108EE9;
        color: white;
    }
</style>


<div>
    <table id="mytable">
        <tbody>
        <tr>
            <td class="form-group">
                <label>发送标题：</label>
                <input type="text" class="form-control" id="send_title" name="send_title" placeholder="请输入" autocomplete="off">
            </td>
            <td class="form-group">
                <label>发送时间：</label>
                <input type="text" class="form-control" id="send_time" name="send_time" placeholder="请输入" autocomplete="off">
            </td>
        </tr>
        <tr>
            <td class="form-group">
                <button class="btn btn-default" click="search" type="button" id="search">搜索</button>
                <button class="btn btn-default" click="reset" type="button" id="reset">重置</button>
                <button class="btn btn-primary" click="fabu" type="button" id="fabu">发布</button>
            </td>
        </tr>
        </tbody>
    </table>

</div>
<div id="table_mail"></div>
<div id="table_mail2"></div>

