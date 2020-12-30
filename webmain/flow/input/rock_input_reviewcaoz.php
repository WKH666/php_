<?php defined('HOST') or die('not access'); ?>
<script>
    var c='';
    $(document).ready(function () {
        {params}
        let flow_id = params.id;
        console.log(flow_id);
        var c = {
            init: function () {
                js.ajax(js.getajaxurl('get_cz', 'sheke_fwork', 'main'), {id:flow_id}, function (data) {
                    c.initshow(data);
                }, 'post,json');
            },
            initshow: function (data) {
                sessionStorage.clear();
                window.sessionStorage.setItem('table_name',data.table);
                window.sessionStorage.setItem('mid',data.mid);
                window.sessionStorage.setItem('nstatus',data[3]);
                window.sessionStorage.setItem('uid',data[4]);
                $('#project_name').val(data[0]);
                var ct=data.change_type;
                $("#change_type option[value='"+ct+"']").attr('selected',"selected");
                $("#update_remark").val(data.change_remark);
                $("#td02").text(data[1]['filename']);
                $("#td05").text(data[2]['filename']);
                // if(dy==1){
                //     $("input[name='is_delay']").val('1').attr('checked',"checked");
                //     $('#sx').val(data.dealy_day);
                // }else{
                //     $("input[name='is_delay']").val('0').attr('checked',"checked");
                // }
            },
            // see1:function(){
            //     var table=window.sessionStorage.getItem('table_name');
            //     var mid=window.sessionStorage.getItem('mid');
            //     console.log(table);
            //     console.log(mid);
            //     var tit = '项目变更书';
            //     //使用官网url时域名需要更改
            //     var url1='http://www.sheke.com/task.php?a=p&num=';
            //     var num1=url1+table;
            //     var num2='&mid='+mid;
            //     var url=num1+num2;
            //     console.log(num1);
            //     console.log(num2);
            //     //var url = 'http://www.sheke.com/?a=lu&m=input&d=flow&num=project_researchbase&mid=11';
            //     var mxw= 900;
            //     var hm = winHb()-150;if(hm>800)hm=800;if(hm<400)hm=400;
            //     if(url.indexOf('wintype=max')>0){
            //         mxw= 1100;
            //         hm=winHb()-45;
            //     }
            //     var wi = winWb()-150;if(wi>mxw)wi=mxw;if(wi<700)wi=700;
            //     console.log('url----'+url);
            //     js.tanbody('winiframe',tit,wi,410,{
            //         html:'<div style="height:'+hm+'px;overflow:hidden"><iframe src="" name="openinputiframe" width="100%" height="100%" frameborder="0"></iframe></div>',
            //         bbar:'none'
            //     });
            //     openinputiframe.location.href=url;
            //     return false;
            // },
            // see2:function(){
            //     var table=window.sessionStorage.getItem('table_name');
            //     var mid=window.sessionStorage.getItem('mid');
            //     console.log(table);
            //     console.log(mid);
            //     var tit = '变更后课题申报书';
            //     //使用官网url时域名需要更改
            //     var url1='http://www.sheke.com/task.php?a=p&num=';
            //     var num1=url1+table;
            //     var num2='&mid='+mid;
            //     var url=num1+num2;
            //     console.log(num1);
            //     console.log(num2);
            //     var mxw= 900;
            //     var hm = winHb()-150;if(hm>800)hm=800;if(hm<400)hm=400;
            //     if(url.indexOf('wintype=max')>0){
            //         mxw= 1100;
            //         hm=winHb()-45;
            //     }
            //     var wi = winWb()-150;if(wi>mxw)wi=mxw;if(wi<700)wi=700;
            //     console.log('url----'+url);
            //     js.tanbody('winiframe',tit,wi,410,{
            //         html:'<div style="height:'+hm+'px;overflow:hidden"><iframe src="" name="openinputiframe" width="100%" height="100%" frameborder="0"></iframe></div>',
            //         bbar:'none'
            //     });
            //     openinputiframe.location.href=url;
            //     return false;
            // },
            xia1:function () {
                js.ajax(js.getajaxurl('get_cz', 'sheke_fwork', 'main'), {id:flow_id}, function (data) {
                    if(js.isimg(data[1]['fileext'])){
                        $.imgview({url:data[1]['filepath']});
                    }else{
                        js.downshow(data[1]['id']);/*设置1秒延时执行  不设置导致多个文件还没下载成功就执行下一次循环*/
                    }
                }, 'post,json');
            },
            xia2:function () {
                js.ajax(js.getajaxurl('get_cz', 'sheke_fwork', 'main'), {id:flow_id}, function (data) {
                    if(js.isimg(data[2]['fileext'])){
                        $.imgview({url:data[2]['filepath']});
                    }else{
                        js.downshow(data[2]['id']);/*设置1秒延时执行  不设置导致多个文件还没下载成功就执行下一次循环*/
                    }
                }, 'post,json');
            }
        }
        js.initbtn(c);
        c.init();
        b = $('#review_cz').bootstable({
            url: js.getajaxurl('audit_record', 'sheke_fwork', 'main'),
            params:{'id':flow_id},
            storeafteraction: 'audit_record_after',
            fanye: true,
            celleditor: true,
            pageSize:4,
            columns: [
                {
                    text: '审核人', dataIndex: 'name',
                }, {
                    text: '审核状态', dataIndex: 'zt',
                }, {
                    text: '审核意见', dataIndex: 'audit_opinion',
                }, {
                    text: '审核时间', dataIndex: 'audit_time',
                },]
        });

    });
    function ba() {
        {params}
        let flow_id = params.id;
        var nstatus=window.sessionStorage.getItem('nstatus');
        var table=window.sessionStorage.getItem('table_name');
        var uid=window.sessionStorage.getItem('uid');
        var mid=window.sessionStorage.getItem('mid');
        js.ajax(js.getajaxurl('qxcaoz', 'sheke_fwork', 'main'),
            {id: flow_id,nstatus:nstatus,mid:mid,uid:uid,table:table},
            function (data) {
                if (data!=false){
                    closenowtabs();
                } else {
                }
            }, 'post,json');
    }
    function up_book() {
        var table=window.sessionStorage.getItem('table_name');
        var mid=window.sessionStorage.getItem('mid');
        console.log(table);
        console.log(mid);
        var tit = '修改申报书';
        //var url1='http://www.sheke.com/?a=lu&m=input&d=flow&num=';
        var url1=getRootPath()+'/?a=lu&m=input&d=flow&num=';
        var num1=url1+table;
        var num2='&mid='+mid+'&callback=';
        var url=num1+num2;
        console.log(num1);
        console.log(num2);
        //var url = 'http://www.sheke.com/?a=lu&m=input&d=flow&num=project_researchbase&mid=11';
        var mxw= 900;
        var hm = winHb()-150;if(hm>800)hm=800;if(hm<400)hm=400;
        if(url.indexOf('wintype=max')>0){
            mxw= 1100;
            hm=winHb()-45;
        }
        var wi = winWb()-150;if(wi>mxw)wi=mxw;if(wi<700)wi=700;
        js.tanbody('winiframe',tit,wi,410,{
            html:'<div style="height:'+hm+'px;overflow:hidden"><iframe src="" name="openinputiframe" width="100%" height="100%" frameborder="0"></iframe></div>',
            bbar:'none'
        });
        openinputiframe.location.href=url;
        return false;
    }
    function save() {
        {params}
        let flow_id = params.id;
        var table=window.sessionStorage.getItem('table_name');
        var nstatus=window.sessionStorage.getItem('nstatus');
        var uid=window.sessionStorage.getItem('uid');
        var mid=window.sessionStorage.getItem('mid');
        var ct=$("#change_type option[selected='selected']").val();
        var up_remark=$("#update_remark").val();
        var delay=$("input[name='is_delay']:checked").val();
        var oR=$("input[name='optionsRadios']:checked").val();
        var sheheyijian = $("#sheheyijian").val();
        var sx= $("#sx").val();
        js.ajax(js.getajaxurl('savecaoz', 'sheke_fwork', 'main'),
            {id: flow_id,change_type:ct,change_remark:up_remark, audit_opinion:sheheyijian,is_delay:delay,audit_result:oR, delay_day:sx,nstatus:nstatus,uid:uid,table:table,mid:mid},
            function (data) {
                if (data!=false){
                    closenowtabs();
                    try {
                        assessmentList.reload();
                    }catch (e) {

                    }
                } else {
                    alert("认证信息和审核操作要填写完整");
                }
            }, 'post,json');
    }
    /**
     * 获取当前项目的域名和项目名
     */
    function getRootPath() {
        //获取当前网址，如： http://localhost:8083/uimcardprj/share/meun.jsp
        var curWwwPath = window.document.location.href;
        //获取主机地址之后的目录，如： uimcardprj/share/meun.jsp
        var pathName = window.document.location.pathname;
        var pos = curWwwPath.indexOf(pathName);
        //获取主机地址，如： http://localhost:8083
        var localhostPaht = curWwwPath.substring(0, pos);
        //获取带"/"的项目名，如：/uimcardprj
        var projectName = pathName.substring(0, pathName.substr(1).indexOf('/') + 1);
        return (localhostPaht + projectName);
    }
</script>

<style>
    input[type="radio"]{
        margin-bottom: 4px;
    }
    .three_columns {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
    }

    .form-group {
        display: flex;
        flex-direction: row;
    }

    .form-group label {
        display: flex;
        justify-content: center;
        align-items: center;
        margin: 0;
        padding: 0;
    }

    .three_columns .form-group label {
        width: 13rem;
    }

    .one_columns .form-group label {
        width: 11rem;
    }

    .one_columns .form-group textarea {
        height: 10rem;
    }

    .header_title {
        background: #CDE3F1;
        border-radius: 5px;
    }

    .header_title p {
        padding: 5px 0;
    }

    .header_title:nth-of-type(2) p {
        margin: 0;
    }
    #results_table{
        width:100%;
    }

    #results_table thead, tbody tr td {
        height: 30px;
    }

    #results_table thead tr td {
        text-align: center;
        background: #F2F2F2;
    }

    #results_table tbody {
    }

    #results_table tbody tr td {
        text-align: center;
        border: 1px solid #d1d1d1;
        box-sizing: border-box;
    }

    #results_table tbody tr td:nth-of-type(1) {
        text-align: left;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
    }

    #sheheyijian {
        width: 1300px;
        margin: 11px 0 0 58px;
        border-radius: 3px;
        border: 1px solid #ccc;
    }

</style>
<div>
    <div class="header_title">
        <p>基础信息</p>
    </div>
    <form class="one_columns">
        <div class="form-group">
            <label>选择项目:</label>
            <input type="text" class="form-control" readonly id="project_name" style="margin-left: 8px">
            <input type="button" class="btn-primary" onclick="up_book()" style="border-radius: 5px" value="修改申报书">
        </div>
    </form>
    <form class="one_columns">
        <div class="form-group">
            <label>变更类型:</label>
            <select class="form-control" id="change_type">
                <option value="0">变更项目负责人</option>
                <option value="1">变更或增加课题组成员</option>
                <option value="2">变更项目管理单位</option>
                <option value="3">改变成果形式</option>
                <option value="4">改变项目名称</option>
                <option value="5">研究内容有重大调整</option>
                <option value="6">延期</option>
                <option value="7">撤项</option>
                <option value="8">其他</option>
            </select>
        </div>
    </form>
    <form class="one_columns">
        <div class="form-group">
            <label style="margin-left: -3px">变更说明:</label>
            <textarea class="form-control" required="required" id="update_remark"></textarea>
        </div>
    </form>
    <form class="one_columns">
        <div class="form-group">
            <label>是否延期:</label>
            <div class="radio">
                <label>
                    <input type="radio" name="is_delay" id="is_delay1" value="1">
                    <label style="margin-left: 25px">是</label>
                </label>
            </div>
            <div class="radio" style="margin-top:9px">
                <label>
                    <input type="radio" name="is_delay" id="is_delay0" value="0">
                    <label style="margin-left: 25px;">否</label>
                </label>
            </div>
            <label>完成时限:</label>
            <input onclick="js.datechange(this,'datetime')" name="etoc" class="input datesss" inputtype="date" id="sx" style="width: 300px;margin-top: 5px">
        </div>
    </form>
    <div class="header_title" style="margin-top: 30px">
        <p>附件资料</p>
    </div>
    <table id="results_table" cellpadding="0" cellspacing="0">
        <thead>
        <tr>
            <td>附件类型</td>
            <td>文件名称</td>
            <td>上传状态</td>
            <td>操作</td>
        </tr>
        </thead>
        <tbody id="results_tbody">
        <tr>
            <td id="td01">项目变更书</td>
            <td id="td02">支持扩展名：.xlsx.xls的文件</td>
            <td id="td03"style="color: green">已上传</td>
            <td>
<!--                <a href="#" id="ck" click="see1" style="color: #3D8EDB;text-decoration: none" >查看</a>-->
<!--                <span style="padding:5px;">|</span>-->
                <a href="#" id="xz" click="xia1" style="color: #3D8EDB;text-decoration: none" >下载</a>
            </td>
        </tr>
        </tbody>
        <tbody id="results_tbody">
        <tr>
            <td id="td04">变更后课题申报书</td>
            <td id="td05">支持扩展名：.xlsx.xls的文件</td>
            <td id="td06" style="color: green">已上传</td>
            <td>
<!--                <a href="#" id="ck" click="see2" style="color: #3D8EDB;text-decoration: none" >查看</a>-->
<!--                <span style="padding:5px;">|</span>-->
                <a href="#" id="xz" click="xia2" style="color: #3D8EDB;text-decoration: none" >下载</a>
            </td>
        </tr>
        </tbody>
    </table>
    <div class="header_title" style="margin-top: 30px">
        <p>审核记录</p>
    </div>
    <div id="review_cz"></div>

</div>
<div class="header_title" style="margin-top: 30px">
    <p>审核操作</p>
</div>
<form class="one_columns">
        <p style="color: red"> 审核提醒：填写审核必须先仔细查看附件详细的资料文档</p>
</form>
<form class="three_columns">
    <div class="form-group">
        <label>审核结果:</label>
        <div class="radio">
            <label>
                <input type="radio" name="optionsRadios" id="oR1" value="1">
                <label style="margin-left: 29px">通过</label>
            </label>

        </div>
        <div class="radio" style="margin-top:9px">
            <label>
                <input type="radio" name="optionsRadios" id="oR2" value="0">
                <label style="margin-left: 43px;">未通过</label>
            </label>

        </div>
    </div>
</form>
<form class="one_columns">
    <div class="form-group" style="margin-left: 8px">
        <div class="form-group">
            <label>审核意见:</label>
            <input type="text" placeholder="请输入备注" id="sheheyijian">
        </div>
    </div>
</form>
<form class="one_columns">
    <div class="form-group" style="margin-left: 8px">
        <button class="btn btn-primary btn-sm" type="button" onclick="save();">提交</button>
        <button class="btn-sm" type="button" style="margin-left: 20px" onclick="ba();">取消</button>
    </div>
</form>

</div>

