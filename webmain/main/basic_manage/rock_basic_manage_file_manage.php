<?php if (!defined('HOST')) die('not access'); ?>
<script>
    var a = '';
    var key_word_id = 0;
    console.log('{mode}', '{dir}');
    console.log('fileslist');
    $(document).ready(function () {
        {params}
        sessionStorage.removeItem('upload_type');
        sessionStorage.setItem('upload_type','普通文件');
        var status_arr = [];
         a = $('#table_file_manage').bootstable({
            tablename:'file',
            url: js.getajaxurl('fileslist', 'basic_manage', 'main', {}),
            fanye: true,
            celleditor: true,
            checked:true,
            storeafteraction:'filelistafter',
            storebeforeaction:'filelistbefore',
            columns: [
                {
                    text: '年度', dataIndex: 'nd_year', sortable: true
                }, {
                    text: '文件名称', dataIndex: 'filename', sortable: true
                }, {
                    text: '上传时间', dataIndex: 'adddt', sortable: true
                }, {
                    text: '操作', dataIndex: 'caoz', width: '180px'
                }],
            /*获取选中所有id*/
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
        filesdownload = function(id1,l,p){
            console.log(id1);
            if(js.isimg(l)){
                $.imgview({url:p});
            }else{
                js.downshow(id1)
            }
        },
            filesreportdel = function(id2){
                js.ajax(js.getajaxurl('delfiles','basic_manage','main'),{id2 : id2},function(data){
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
                a.reload();
            },
            search:function(){
                a.setparams({
                    //需搜索的内容
                    nd_year:get('nd_year').value,
                    filename:get('filename').value,
                },true);
            },
            reset:function(){
                $("#nd_year").val('');
                $("#filename").val('');
                a.setparams({
                    //需搜索的内容
                    nd_year:'',
                    filename:'',

                },true);
            },
            addfile:function(){
                sessionStorage.removeItem('fid');
                js.upload('xiazfile_excel{rand}',{maxup:'1','title':'选择档案管理文件',uptype:'','urlparams':'noasyn:yes'});
            },
            // backup:function(fid){
            //     var o1 = get('upexcelbtn{rand}');
            //     o1.html='文件读取中...';
            //     js.ajax(js.getajaxurl('readxls','input','flow'),{'fileid':fid,'modenum':'inforreport'},function(data){
            //         if(data.code == 200){
            //             o1.html='读取成功';
            //             let readrows = data.rows;
            //             o1.disabled=false;
            //             //sessionStorage.removeItem('fid');
            //             sessionStorage.setItem('fid',fid);
            //         }else{
            //             o1.text='读取失败';
            //         }
            //     },'get,json');
            // },
            visible:function () {
                js.ajax(js.getajaxurl('staresults','basic_manage','main'),{status_arr : status_arr},function(data){
                    var fileext=data.fileext;
                    var filepath=data.filepath;
                    var ids=data.ids;
                    $.each(ids,function (index) {
                        console.log(ids[index]);
                        console.log(filepath[index]);
                        if(js.isimg(fileext[index])){
                            $.imgview({url:filepath[index]});
                        }else{
                            setTimeout(function () {
                                js.downshow(ids[index]);/*设置1秒延时执行  不设置导致多个文件还没下载成功就执行下一次循环*/
                            },1000);
                        }
                    });
                    status_arr=[];
                    c.reload();
                },'post,json');
            },
            saved:function () {
            var fid=sessionStorage.getItem('fid');
            var nd=$('#select_year option:selected').val();
            console.log(fid);
            console.log(nd);
            js.ajax(js.getajaxurl('baocun','basic_manage','main'),{'id':fid,'nd':nd},function(data){
                if (data.code==200){
                    $('#myModal').modal('hide');
                    c.reload();
                } else {
                    alert("上传文件失败");
                }
            },'post,json');
        },
            cancel:function () {
                var fid=sessionStorage.getItem('fid');
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
            }
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
            sessionStorage.setItem('fid',f.id);
            //c.backup(f.id);
            $("#excel_{rand}").attr('href',f.filepath);
            $("#excel_{rand}").text(f.filename);
        };
        $("#upload").on('click',function(){
            sessionStorage.removeItem('fid');
            $("#myModal").on("show.bs.modal", function() {
                $("#upexcelbtn{rand}").text('上传文件');
                $("#excel_{rand}").text('');
                $("#excel_{rand}").attr('href','');
            });
        });
    });
    // function saved() {
    //     var fid=window.sessionStorage.getItem('fid');
    //     var nd=$('#select_year option:selected').val();
    //     console.log(fid);
    //     console.log(nd);
    //     js.ajax(js.getajaxurl('baocun','basic_manage','main'),{'id':fid,'nd':nd},function(data){
    //         if (data.code==200){
    //             console.log(88);
    //             c.reload();
    //         } else {
    //             alert("error");
    //         }
    //     },'post,json');
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
    #select_year{
        margin: 5px;
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
                <label>文件名称：</label>
                <input type="text" class="form-control" id="filename" name="filename" placeholder="请输入" autocomplete="off">
            </td>
        </tr>
        <tr>
            <td class="form-group">
                <button class="btn btn-default" click="search" type="button" id="search">搜索</button>
                <button class="btn btn-default" click="reset" type="button" id="reset">重置</button>
                <button class="btn btn-default" click="visible" type="button" id="visible" style="background-color: #5FB878;color: white">批量下载</button>
                <button class="btn btn-default" type="button" id="upload" data-toggle="modal" data-target="#myModal">上传</button>
            </td>
        </tr>
        </tbody>
    </table>


</div>
<div id="table_file_manage"></div>


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
                            <p class="tips">支持扩展名：excel、word、pdf等文件格式</p>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" click="saved" id="saved" >保存</button>
                <button type="button" class="btn btn-default" click="cancel">取消</button>
            </div>
        </div>
    </div>
</div>



