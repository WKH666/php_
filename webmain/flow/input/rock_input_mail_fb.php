<?php defined('HOST') or die('not access');?>
<script>
    $(document).ready(function(){
        var status_arr1 = [];
        $("#receive_id").each(function () {
            $(this).on("click",function () {
                    console.log($("#send_type").val());
                    if ($("#send_type").val()=="按用户"){
                        $('#receive_id').attr('data-toggle','modal');
                        $('#receive_id').attr('data-target','#myModal');
                    }else if ($("#send_type").val()=="按角色"){
                        $('#receive_id').attr('data-toggle','modal');
                        $('#receive_id').attr('data-target','#mySecondModal');

                    }
                    else {
                        $('#receive_id').removeAttr('data-toggle');
                        $('#receive_id').removeAttr('data-target');
                        alert("请选择群发类型");
                    }
                }
            );
        });
        //用户列表
        var adminList = $('#adminListTable').bootstable({
            tablename:'admin',
            url: js.getajaxurl('admin_list', 'mail', 'main', {}),
            fanye: true,
            celleditor: true,
            //isshownumber: false, //是否显示序号
            checked:true,
            storebeforeaction:'admin_before',
            columns: [
                {
                    text: '姓名',
                    dataIndex: 'name',
                    sortable: true
                }, {
                    text: '单位',
                    dataIndex: 'deptname',
                    sortable: true
                }, {
                    text: '角色',
                    dataIndex: 'ranking',
                    sortable: true
                }],
            /*获取选中所有id*/
            load:function(){
                $("#visible")[0].disabled = true;
                $(".table thead input[type='checkbox']").on('click',function(){
                    $("#visible")[0].disabled = false;
                    let tbody = $(".table tbody input[type='checkbox']").length;
                    if($(this)[0].checked){
                        for(var i = 0; i< tbody ; i++){
                            status_arr1.push($(".table tbody input[type='checkbox']")[i].value);
                            $(".table tbody input[type='checkbox']")[i].checked = true;
                        }
                    }else{
                        for(var i = 0; i< tbody ; i++){
                            status_arr1 = [];
                            $(".table tbody input[type='checkbox']")[i].checked = false;
                            $("#visible")[0].disabled = true;
                        }
                    }
                });
                $(".table tbody input[type='checkbox']").on('click',function(){
                    $("#visible")[0].disabled = false;
                    let checkbox_id = $(this).val();
                    if($(this)[0].checked){
                        status_arr1.push(checkbox_id);
                    }else{
                        if(status_arr1){
                            for(var i = 0; i<status_arr1.length;i++){
                                if(status_arr1[i] == checkbox_id){
                                    status_arr1.splice(i,1);
                                    if(status_arr1.length == 0){
                                        $("#visible")[0].disabled = true;
                                    }else{}
                                }
                            }
                        }else{}
                    }
                });
            }


        });
        var d = {
            reload:function(){
                adminList.reload();
            },
            search:function(){
                adminList.setparams({
                    //需搜索的内容
                    admin_name:get('admin_name').value,
                    deptname:get('deptname').value,
                    ranking:get('ranking').value,
                },true);
            },
            reset:function(){
                $("#admin_name").val('');
                $("#deptname").val('');
                $("#ranking").val('');
                adminList.setparams({
                    //需搜索的内容
                    admin_name:'',
                    deptname:'',
                    ranking:''
                },true);
            },
            addfile:function(){
                sessionStorage.removeItem('filearr');
                js.upload('xiazfile_excel{rand}',{maxup:'1','title':'选择站内信文件',uptype:'xls|xlsx|doc|docx|pdf','urlparams':'noasyn:yes'});
            },
            // backup:function(fid){
            //     var o1 = get('upexcelbtn{rand}');
            //     o1.html='文件读取中...';
            //     js.ajax(js.getajaxurl('readxls','input','flow'),{'fileid':fid,'modenum':'inforreport'},function(data){
            //         if(data.code == 200){
            //             o1.html='读取成功';
            //             let readrows = data.rows;
            //             o1.disabled=false;
            //             sessionStorage.clear();
            //             window.sessionStorage.setItem('fid',fid);
            //         }else{
            //             o1.text='读取失败';
            //         }
            //     },'get,json');
            // },
            visible:function () {
                js.ajax(js.getajaxurl('vis_results','mail','main'),{status_arr1:status_arr1},function(data){
                    var ids=data.user;
                    var str="";
                    if (data.code==200) {
                        $.each(ids,function (index) {
                            var s=JSON.stringify(data.user[index]);
                            str +=s+",";
                        })
                    }else{
                        alert(data.msg);
                    }
                    if (str.length > 0) {
                        str = str.substr(0,str.length - 1);
                    }
                    var p=str.split('"');
                    var z=p.join('');
                    $('#receive_id').val(z);
                    $('#myModal').modal("hide");
                    status_arr1=[];
                    d.reload();
                },'post,json');
            },
            qd:function () {
                var chk_v=[];
                $('input[name="checkItem"]:checked').each(function () {
                    chk_v.push($(this).val());
                });
                js.ajax(js.getajaxurl('qd_results','mail','main'),{status_arr2:chk_v},function(data){
                    var ids=data.user;
                    var p=ids.split('"');
                    var z=p.join('');
                    $('#receive_id').val(z);
                    $('#mySecondModal').modal("hide");
                    d.reload();
                },'post,json');
            }
        };
        js.initbtn(d);
        xiazfile_excel{rand}=function(a,xid){
            var t=$('#select_year option:selected').val();
            console.log(t);
            console.log(a);
            var f = a[0];
            // c.backup(f.id);
            sessionStorage.setItem('filearr',JSON.stringify(f));
            $("#excel_{rand}").attr('href',f.filepath);
            $("#excel_{rand}").text(f.filename);
        };
    });
    function draft() {
        var id1=(JSON.parse(sessionStorage.getItem('filearr')));
        console.log(id1);
        var send_type=$('#send_type').val();
        var receive_id=$('#receive_id').val();
        var remark=$('#send_remark').val();
        var titles=$('#send_titles').val();
        var is_send=$(':radio[name="is_send"]:checked').val();
        if (id1!=null) {
            js.ajax(js.getajaxurl('dra_data', 'mail', 'main'), {results_id: id1.id,send_type:send_type,receive_id:receive_id,remark:remark,titles:titles,is_send:is_send,send_status:0}, function (data) {
                if (data){
                    closenowtabs();
                    try {
                        assessmentList.reload();
                    }catch (e) {

                    }
                }else {
                    alert("文件上传失败");
                }
            }, 'post,json');
        } else {
            alert('文件未上传！');
        }
    }
    function sub() {
        var id1=(JSON.parse(sessionStorage.getItem('filearr')));
        console.log(id1);
        var send_type=$('#send_type').val();
        var receive_id=$('#receive_id').val();
        var remark=$('#send_remark').val();
        var titles=$('#send_titles').val();
        var is_send=$(':radio[name="is_send"]:checked').val();
        if (id1!=null) {
            js.ajax(js.getajaxurl('rec_data', 'mail', 'main'), {results_id: id1.id,send_type:send_type,receive_id:receive_id,remark:remark,titles:titles,is_send:is_send,send_status:1}, function (data) {
                if (data){
                    closenowtabs();
                    try {
                        assessmentList.reload();
                    }catch (e) {

                    }
                }else {
                    alert("文件上传失败");
                }
            }, 'post,json');
        } else {
            alert('文件未上传！');
        }
    }
    function ba() {
        closenowtabs();
    }
    function findjs() {
        var v=$('#ranked').val();
        var jueList=document.getElementById('jueList');
        var cd=jueList.rows.length;
        var seCol=1;
        for (var i=1;i<cd;i++){
            var tx=jueList.rows[i].cells[seCol].innerHTML;
            if(tx.match(v) || tx.toUpperCase().match(v.toUpperCase())){//用match函数进行筛选，如果input的值，即变量 key的值为空，返回的是ture，
                jueList.rows[i].style.display='';//显示行操作，
            }else{
                jueList.rows[i].style.display='none';//隐藏行操作
            }
        }
    }
    function czjs() {
        $('#ranked').val('');
        var jueList = document.getElementById('jueList');
        var cd=jueList.rows.length;
        for (var i=1;i<cd;i++) {
            jueList.rows[i].style.display = '';
        }
    }
</script>

<style>

    textarea#send_remark{
        height: 100px;
    }
    input#is_send1{
        height: 25px;
        border: none;
        width: 25px;
    }
    input#is_send0{
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
            <select type="text" class="form-control"  id="send_type" required>
                <option value="0">请选择群发类型</option>
                <option id="op1" value="按用户" >按用户</option>
                <option id="op2" value="按角色">按角色</option>
            </select>
        </div>
    </form>
    <form class="three_columns">
        <div class="form-group">
            <label>接收者:</label>
            <input type="text" class="form-control"  id="receive_id" placeholder="请选择" required>
            <input type="hidden" id="hid" value="">
        </div>
    </form>
    <form class="three_columns">
        <div class="form-group">
            <label>发送标题:</label>
            <textarea  class="form-control"  id="send_titles" placeholder="请输入" required></textarea>
        </div>
    </form>
    <form class="three_columns">
        <div class="form-group">
            <label>发送说明:</label>
            <textarea class="form-control"  id="send_remark" placeholder="请输入" required></textarea>
        </div>
    </form>
    <form class="three_columns">
        <div class="form-group">
            <label >同步邮件:</label>
            <input type="radio" class="form-control"  id="is_send1" name="is_send" value="1"><label id="is_send2">是</label>
            <input type="radio" class="form-control"  id="is_send0" name="is_send" value="0"> <label id="is_send2">否</label>
        </div>
    </form>
    <div class="header_title">
        <p>信息附件</p>
    </div>

    <form class="form-horizontal" style="margin: 30px">
        <div class="form-group">
            <label class="col-sm-2 control-label" style="width: 90px">信息附件:</label>
            <div class="col-sm-10" style="margin-top: 7px">
                <button style="border-radius: 7px;width: 85px;margin: 5px;color: #3D8EDB"class="btn-default" id="upexcelbtn{rand}" click="addfile" >上传文件</button>
                <a id="excel_{rand}" href="" target="_blank"></a>
            </div>
        </div>
        <div class="col-sm-1">
        </div>
        <div class="col-sm-11">
            <p class="tips">支持扩展名：excel、word、pdf等文件格式</p>
        </div>
        <div class="form-group col-sm-6" style="margin-top: 35px" >
            <button class="btn btn-primary btn-sm col-sm-2" type="button" onclick="draft();" id="s" style="margin: 25px" >保存草稿</button>
            <button class="btn btn-success btn-sm col-sm-2" type="button" onclick="sub(); " style="margin: 25px">提交</button>
            <button class="btn-sm col-sm-2" type="button " style="margin: 25px" onclick="ba();">取消</button>
        </div>
    </form>
    <div id="word_table2"></div>
</div>


<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">选择用户</h4>
            </div>
            <div class="modal-body">
                <form>
                    <section class="serachPanel selBackGround">
                        <div class="searchAPanel">
                            <!--姓名--><!--单位-->
                            <div class="searchAc1">
                                <ul>
                                    <li style="margin: 5px">
                                        <span>姓名：</span>
                                        <input type="text" name="admin_name" id="admin_name" class="form-control txtPanel" placeholder="请输入">
                                        <span>单位：</span>
                                        <input type="text" name="deptname" id="deptname"class="form-control txtPanel" placeholder="请输入">
                                    </li>
                                </ul>
                            </div>
                            <!--角色--><!--搜索，重置-->
                            <div class="searchAc1">
                                <ul>
                                    <li style="margin: 5px">
                                        <span class="reviewContent stateContent">角色：</span>
                                        <input type="text" id="ranking" name="ranking" class="form-control txtPanel" placeholder="请输入">
                                        <button class="btn btn-default" click="search" type="button" id="search" style="margin-left: 20px">搜索</button>
                                        <button type="button" click="reset" id="reset" class="btn btn-default " style="margin-left: 15px">重置</button>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </section>
                </form>
                <div class="blank10"></div>
                <div id="adminListTable"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" click="visible" id="visible" data-dismiss="modal">确定</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="mySecondModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">选择角色</h4>
            </div>
            <div class="modal-body">
                <form>
                    <section class="serachPanel selBackGround">
                        <div class="searchAPanel">
                            <!--角色--><!--搜索，重置-->
                            <div class="searchAc1">
                                <ul>
                                    <li style="margin: 5px">
                                        <span class="reviewContent stateContent">角色：</span>
                                        <input type="text" id="ranked" name="ranked" class="form-control txtPanel" placeholder="请输入">
                                        <button class="btn btn-default" onclick="findjs()" type="button" id="search2" style="margin-left: 20px">搜索</button>
                                        <button type="button" onclick="czjs()" id="reset2" class="btn btn-default " style="margin-left: 15px">重置</button>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </section>
                    <table class="table table-hover" id="jueList">
                        <thead>
                        <tr class='caption'>
                            <td class='item'><input type="checkbox" id="checkAll" name="checkAll" /></td>
                            <td class='item'>角色</td>
                        </tr>
                        </thead>
                        <tbody>
                        <tr class='list'>
                            <td><input type="checkbox" name="checkItem" value="社科管理员"/></td>
                            <td>社科管理员</td>
                        </tr>
                        <tr class='list'>
                            <td><input type="checkbox" name="checkItem" value="申报者"/></td>
                            <td>申报者</td>
                        </tr>
                        <tr class='list'>
                            <td><input type="checkbox" name="checkItem" value="专家"/></td>
                            <td>专家</td>
                        </tr>
                        <tr class='list'>
                            <td><input type="checkbox" name="checkItem" value="高校科研人员"/></td>
                            <td>高校科研人员</td>
                        </tr>
                        <tr class='list'>
                            <td><input type="checkbox" name="checkItem" value="系统顶级管理员"/></td>
                            <td>系统顶级管理员</td>
                        </tr>
                        </tbody>
                    </table>
                </form>
                <div class="blank10"></div>
                <div id="jue_{rand}"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" click="qd" id="qd" data-dismiss="modal">确定</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
            </div>
        </div>
    </div>
</div>

