<?php if (!defined('HOST')) die('not access'); ?>

<script>
    var a = '';
    var key_word_id = 0;
    console.log('{mode}', '{dir}');
    $(document).ready(function () {
        {params}
        var a = $('#word_table').bootstable({
            url: js.getajaxurl('report_list', '{mode}', '{dir}', {}),
            fanye: true,
            celleditor: true,
            storeafteraction: 'reportlistafter',
            storebeforeaction:'reportlistbefore',
            columns: [
                {
                    text: '年度', dataIndex: 'nd_year', sortable: true
                }, {
                    text: '文档名称', dataIndex: 'filename', sortable: true
                }, {
                    text: '上传状态', dataIndex: 'upload_status', sortable: true
                }, {
                    text: '上传者', dataIndex: 'optname', sortable: true
                },{
                    text: '单位', dataIndex: 'deptname', sortable: true
                }, {
                    text: '上传时间', dataIndex: 'adddt', sortable: true
                }, {
                    text: '操作', dataIndex: 'caoz', width: '180px'
                }],

        });
        /*下载文件*/
        report_download = function(file_id,l,p){
            console.log(file_id);
            if(js.isimg(l)){
                $.imgview({url:p});
            }else{
                js.downshow(file_id)
            }
        }
        var c = {
            reload:function(){
                a.reload();
            },
            search:function(){
                a.setparams({
                    //需搜索的内容
                    nd_year:get('nd_year').value,
                    filename:get('filename').value,
                    optname:get('optname').value,
                    deptname:get('deptname').value,
                },true);
            },
            reset:function(){
                $("#nd_year").val('');
                $("#filename").val('');
                $("#optname").val('');
                $("#deptname").val('');
                a.setparams({
                    //需搜索的内容
                    nd_year:'',
                    filename:'',
                    optname:'',
                    deptname:''
                },true);
            },
            addfile:function(){
                sessionStorage.clear();
                js.upload('xiazfile_excel{rand}',{maxup:'1','title':'选择Excel文件',uptype:'xls|xlsx','urlparams':'noasyn:yes'});
            },
            backup:function(fid){
                var o1 = get('upexcelbtn{rand}');
                o1.html='文件读取中...';
                js.ajax(js.getajaxurl('readxls','input','flow'),{'fileid':fid,'modenum':'inforreport'},function(data){
                    if(data.code == 200){
                        o1.html='读取成功';
                        let readrows = data.rows;
                        o1.disabled=false;
                        sessionStorage.clear();
                        window.sessionStorage.setItem('fid',fid);
                    }else{
                        o1.text='读取失败';
                    }
                },'get,json');
            },
            saved:function () {
                var fid=window.sessionStorage.getItem('fid');
                var nd=$('#select_year option:selected').val();
                console.log(fid);
                console.log(nd);
                js.ajax(js.getajaxurl('change_status','information','main'),{'id':fid,'nd':nd,'upload_status':1},function(data){
                    if (data!=false){
                        $('#myModal').modal('hide');
                        c.reload();
                    } else {
                        alert("上传文件失败");
                    }
                },'post,json');
            },
            draft:function () {
                var fid=window.sessionStorage.getItem('fid');
                var nd=$('#select_year option:selected').val();
                console.log(fid);
                console.log(nd);
                js.ajax(js.getajaxurl('change_status','information','main'),{'id':fid,'nd':nd,'upload_status':0},function(data){
                    if (data!=false){
                        $('#myModal').modal('hide');
                        c.reload();
                    } else {
                        alert("上传文件失败");
                    }
                },'post,json');
            },
            cancel:function () {
                var fid=window.sessionStorage.getItem('fid');
                console.log(fid);
                js.ajax(js.getajaxurl('cancel','basic_manage','main'),{'id':fid},function(data){
                    if (data.code==200){
                        $('#myModal').modal('hide');
                        c.reload();
                    } else {
                        console.log(data.code);
                        $('#myModal').modal('hide');
                    }
                },'post,json');
            },
        };
        js.initbtn(c);
        var head="<option value=‘‘ selected>请选择年份</option>";/* 文件年度  */
        var year="";
        var i=0;
        for(i=2020;i>1950;i--){
            year+="<option value="+i+">"+i+"</option>";
        }
        var total = head+year;
        $('#select_year').append(total);
        xiazfile_excel{rand}=function(a,xid){
            var t=$('#select_year option:selected').val();
            console.log(t);
            console.log(a);
            $('#myModal').modal('show');
            var f = a[0];
            c.backup(f.id);
            $("#excel_{rand}").attr('href',f.filepath);
            $("#excel_{rand}").text(f.filename);
        };
        $("#upload").on('click',function(){
            sessionStorage.clear();
            $("#myModal").on("show.bs.modal", function() {
                $("#upexcelbtn{rand}").text('上传文件');
                $("#excel_{rand}").text('');
                $("#excel_{rand}").attr('href','');
            });
        });
        report_edit = function(file_id){
            assessmentList = a;
            var results_url = 'flow,input,annualreportedit,modenum=annualreportedit,annualreport_id='+file_id;
            addtabs({
                num:'annualreportedit',
                url:results_url,
                icons:'',
                name:'上传年度报告'
            });

            return false;
        }
        report_del = function(file_id){
            js.ajax(js.getajaxurl('del_results','annual_report','main'),{file_id : file_id},function(data){
                if(data.code == 200){
                    layer.msg(data.msg);
                    c.reload();
                }else{
                    layer.msg(data.msg);
                }
            },'post,json');
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
                <label>年度：</label>
                <input type="text" class="form-control" id="nd_year" name="nd_year" placeholder="请输入" autocomplete="off">
            </td>
            <td class="form-group">
                <label>文档名称：</label>
                <input type="text" class="form-control" id="filename" name="filename" placeholder="请输入" autocomplete="off">
            </td>
            <td class="form-group">
                <label>上传者：</label>
                <input type="text" class="form-control" id="optname" name="optname" placeholder="请输入" autocomplete="off">
            </td>
            <td class="form-group">
                <label>单位：</label>
                <input type="text" class="form-control" id="deptname" name="deptname" placeholder="请输入" autocomplete="off">
            </td>
        </tr>
        <tr>
            <td class="form-group">
                <button class="btn btn-default" click="search" type="button" id="search">搜索</button>
                <button class="btn btn-default" click="reset" type="button" id="reset">重置</button>
                <button class="btn btn-default" type="button" id="upload" data-toggle="modal" data-target="#myModal">上传</button>
            </td>
        </tr>
        </tbody>
    </table>

</div>
<div id="word_table"></div>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">文件上传</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">文件年度</label>
                        <div class="col-sm-10">
                            <select id="select_year" style="width: 250px"></select>
                        </div>
                    </div>
                </form>
                <form class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">档案文件</label>
                        <div class="col-sm-10">
                            <button style="border-radius: 7px;width: 85px;margin: 5px;color: #3D8EDB"class="btn-default" id="upexcelbtn{rand}" click="addfile" >上传文件</button>
                            <a id="excel_{rand}" href="" target="_blank"></a>
                            <p class="tips">支持扩展名：.xlsx.xls的文件</p>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary"  click="draft"  id="saved">保存</button>
                <button type="button" class="btn btn-primary"click="saved" id="saved1" value="1">提交</button>
                <button type="button" class="btn btn-default" click="cancel">取消</button>
            </div>
        </div>
    </div>
</div>

