<?php if (!defined('HOST')) die('not access'); ?>

<script>
    var a = '';
    $(document).ready(function () {
        {params}
        a = $('#table_checkExpert').bootstable({
            tablename: 'expert_record',
            url: js.getajaxurl('expertlist', '{mode}', '{dir}', {}),
            fanye: true,
            celleditor: true,
            storeafteraction: 'expertlistafter',
            storebeforeaction:'expertlistbefore',
            columns: [
                {
                    text: '账号', dataIndex: 'user', sortable: true
                }, {
                    text: '姓名', dataIndex: 'name', sortable: true
                }, {
                    text: '单位', dataIndex: 'company', sortable: true
                }, {
                    text: '电子邮箱', dataIndex: 'email', sortable: true
                }, {
                    text: '认证状态', dataIndex: 'check_status', sortable: true
                }, {
                    text: '注册时间', dataIndex: 'register_time', sortable: true
                }, {
                    text: '操作', dataIndex: 'caoz', width: '180px'
                }],
        });
        assessmentList = a;
        var c = {
            reload:function(){
                a.reload();
            },
            search:function(){
                a.setparams({
                    //需搜索的内容
                    user:get('user').value,
                    name:get('name').value,
                    company:get('company').value,
                },true);
            },
            reset:function(){
                $("#user").val('');
                $("#name").val('');
                $("#company").val('');
                a.setparams({
                    //需搜索的内容
                    user:'',
                    name:'',
                    company:'',
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
        auditresultscheck = function(auditresults_id){
            // a.reload();
            assessmentList = a;
            var results_url = 'flow,input,auditcaoz,modenum=auditresult,auditresults_id=' + auditresults_id;
            addtabs({
                num:'auditresultscheck',
                url:results_url,
                icons:'',
                name:'审核操作'
            });

            return false;
        },
            auditresultsee = function(auditresults_id){
                // a.reload();
                var results_url = 'flow,input,auditsee,modenum=auditsee,auditresults_id=' + auditresults_id;
                addtabs({
                    num:'auditresultssee',
                    url:results_url,
                    icons:'',
                    name:'查看审核记录'
                });
                return false;
            }
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

<!--<div class="results-form">
    <form>
        <div class="form-group">
            <label>帐号:</label>
            <input type="text" placeholder="请输入" class="search_input" id="user" name="user" autocomplete="off">
        </div>
        <div class="form-group">
            <label>姓名:</label>
            <input type="text" placeholder="请输入" class="search_input" id="name" name="name" autocomplete="off">
        </div>
        <div class="form-group">
            <label>单位:</label>
            <input type="text" placeholder="请输入" class="search_input" id="company" name="company" autocomplete="off">
        </div>
            <div class="form-group">
            <button class="btn1 btn_search" type="button"id="search" click="search">搜索</button>
            <button class="btn2 btn_search" type="button" id="reset" click="reset">重置</button>
        </div>
    </form>
</div>-->
<div>
    <table id="mytable">
        <tbody>
        <tr>
            <td class="form-group">
                <label>帐号：</label>
                <input type="text" class="form-control" id="user" name="user" placeholder="请输入" autocomplete="off">
            </td>
            <td class="form-group">
                <label>姓名：</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="请输入" autocomplete="off">
            </td>
            <td class="form-group">
                <label>单位：</label>
                <input type="text" class="form-control" id="company" name="company" placeholder="请输入" autocomplete="off">
            </td>
        </tr>
        <tr>
            <td class="form-group">
                <button class="btn btn-default" click="search" type="button" id="search">搜索</button>
                <button class="btn btn-default" click="reset" type="button" id="reset">重置</button>
            </td>
        </tr>
        </tbody>
    </table>

</div>
<div id="table_checkExpert"></div>



<!---->
<!--<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">-->
<!--    <div class="modal-dialog">-->
<!--        <div class="modal-content">-->
<!--            <div class="modal-header">-->
<!--                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>-->
<!--                <h4 class="modal-title" id="myModalLabel">编辑或增加关键词分类</h4>-->
<!--            </div>-->
<!--            <div class="modal-body">-->
<!--                <label>关键词分类：</label>-->
<!--                <input type="text" placeholder="请输入" id="key_word_input" class="word_input">-->
<!--            </div>-->
<!--            <div class="modal-footer">-->
<!--                <button type="button" class="btn btn-primary" id="submitBtn" onclick="confirmSubmit()">保存</button>-->
<!--                <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>-->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
<!--</div>-->

