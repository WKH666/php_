<?php if (!defined('HOST')) die('not access'); ?>

<script>
    var a = '';
    var key_word_id = 0;
    console.log('{mode}', '{dir}');
    $(document).ready(function () {
        {params}
        var a = $('#table_annual_list').bootstable({
            tablename:'file',
            url: js.getajaxurl('reportlist', 'annual_report', 'main', {}),
            fanye: true,
            celleditor: true,
            storeafteraction: 'reportlistafter',
            storebeforeaction:'reportlistbefore',
            columns: [
                {
                    text: '文档名称', dataIndex: 'filename', sortable: true
                }, {
                    text: '上传状态', dataIndex: 'upload_status', sortable: true
                }, {
                    text: '上传者', dataIndex: 'optname', sortable: true
                }, {
                    text: '上传时间', dataIndex: 'adddt', sortable: true
                }, {
                    text: '操作', dataIndex: 'caoz', width: '180px'
                }],

        });
        /*下载文件*/
        annualreportdownload = function(annualreport_id,l,p){
            console.log(annualreport_id);
            if(js.isimg(l)){
                $.imgview({url:p});
            }else{
                js.downshow(annualreport_id)
            }
        }
        var c = {
            reload:function(){
                a.reload();
            },
            search:function(){
                a.setparams({
                    //需搜索的内容
                    filename:get('filename').value,
                    optname:get('optname').value,
                },true);
            },
            reset:function(){
                $("#filename").val('');
                $("#optname").val('');
                a.setparams({
                    //需搜索的内容
                    filename:'',
                    optname:'',
                },true);
            },
            upload:function () {
                assessmentList = a;
                var results_url = 'flow,input,annualreportup,modenum=annualreportup';
                addtabs({
                    num:'annualreportup',
                    url:results_url,
                    icons:'',
                    name:'上传年度报告'
                });
            }
        };
        js.initbtn(c);
        annualreportedit = function(annualreport_id){
            assessmentList = a;
            var results_url = 'flow,input,annualreportedit,modenum=annualreportedit,annualreport_id='+annualreport_id;
            addtabs({
                num:'annualreportedit',
                url:results_url,
                icons:'',
                name:'上传年度报告'
            });

            return false;
        }
        annualreportdel = function(annualreport_id){
                js.ajax(js.getajaxurl('delresults','annual_report','main'),{annualreport_id : annualreport_id},function(data){
                    if(data.code == 200){
                        layer.msg(data.msg);
                        c.reload();
                    }else{
                        layer.msg(data.msg);
                    }
                },'post,json');
        }
    });
    //删除关键词
    // function word_del(word_id) {
    //     layer.confirm('确认删除该指标？', {
    //         btn: ['确定', '取消'], //按钮
    //         shade: 0,
    //         skin: 'layui-layer-molv',
    //         closeBtn: 0
    //     }, function () {
    //         js.ajax(js.getajaxurl('worddel', 'basic_manage', 'main'), {'word_id': word_id}, function (da) {
    //             var data = js.decode(da);
    //             layer.msg(data.msg);
    //             a.reload();
    //         });
    //     }, function () {
    //
    //     });
    // }




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
    #upload{
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
                <label>文档名称：</label>
                <input type="text" class="form-control" id="filename" name="filename" placeholder="请输入" autocomplete="off">
            </td>
            <td class="form-group">
                <label>上传者：</label>
                <input type="text" class="form-control" id="optname" name="optname" placeholder="请输入" autocomplete="off">
            </td>
        </tr>
        <tr>
            <td class="form-group">
                <button class="btn btn-default" click="search" type="button" id="search">搜索</button>
                <button class="btn btn-default" click="reset" type="button" id="reset">重置</button>
                <button class="btn btn-default" click="upload" type="button" id="upload">上传</button>
            </td>
        </tr>
        </tbody>
    </table>

</div>
<div id="table_annual_list"></div>

