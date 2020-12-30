<?php defined('HOST') or die('not access'); ?>
<script>
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
                window.sessionStorage.setItem('id01',data[1]['id']);
                $('#project_name1').val(data[0]);
                var ct=data.change_type;
                $("#change_type1").val(ct);
                if (ct==0){$("#change_type1").val('变更项目负责人');}else if (ct==1){$("#change_type1").val('变更或增加课题组成员');
                }else if (ct==2){$("#change_type1").val('变更项目管理单位');} else if (ct==3){$("#change_type1").val('改变成果形式');}
                else if (ct==4){$("#change_type1").val('改变项目名称');} else if (ct==5){$("#change_type1").val('研究内容有重大调整');}
                else if (ct==6){$("#change_type1").val('延期');} else if (ct==7){$("#change_type1").val('撤项');}
                else{$("#change_type1").val('其他');}
                $("#update_remark").val(data.change_remark);
                // console.log(data[1]['filename']);
                // console.log(data[0]);
                // console.log(data[1]);
                $("#td02").text(data[1]['filename']);
                $("#td05").text(data[2]['filename']);
                var dy=data.is_delay;
                if(dy==1){
                    $("#sext1").text("是");
                    $('#sx').val(data.delay_day);
                }else{
                    $("#sext1").text("否");
                }
            },
            // see1:function(){
            //     var id1=window.sessionStorage.getItem('id01');
            //     console.log(id1);
            //     js.ajax(js.getajaxurl('changeToPdf','changePdf', 'public'), {id:id1}, function (data) {
            //         alert(data);
            //     }, 'get,json');
            //
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
        b = $('#review_ck').bootstable({
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
        closenowtabs();
    }
</script>

<style>
    input[name="delay"]{
        margin-top: 10px;
        margin-right: 5px;
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

    #results_table {
        width: 100%;
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
    table tr td{
        text-align: center;
    }

</style>
<div>
    <div class="header_title">
        <p>基础信息</p>
    </div>
    <form class="one_columns">
        <div class="form-group">
            <label>选择项目:</label>
            <input type="text" class="form-control"  readonly id="project_name1">
        </div>
    </form>
    <form class="one_columns">
        <div class="form-group">
            <label>变更类型:</label>
            <input type="text" class="form-control" readonly id="change_type1" >
        </div>
    </form>
    <form class="one_columns">
        <div class="form-group">
            <label style="margin-left: -3px">变更说明:</label>
            <textarea class="form-control" readonly id="update_remark"></textarea>
        </div>
    </form>
    <form class="three_columns">
        <div class="form-group">
            <label>是否延期:</label>
            <input type="radio"   readonly id="delay1" name="delay" value="1" checked><label id="sext1" style="margin-left: -60px;"></label>
        </div>
        <div class="form-group">
            <label>完成时限:</label>
            <input type="text" readonly class="form-control"  id="sx">
        </div>
        <div class="form-group">

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
                <td id="td03" style="color: green">已上传</td>
                <td>
<!--                    <a href="#" id="ck" click="see1" style="color: #3D8EDB;text-decoration: none" >查看</a>-->
<!--                    <span style="padding:5px;">|</span>-->
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
<!--                    <a href="#" id="ck" click="see2" style="color: #3D8EDB;text-decoration: none" >查看</a>-->
<!--                    <span style="padding:5px;">|</span>-->
                    <a href="#" id="xz" click="xia2" style="color: #3D8EDB;text-decoration: none" >下载</a>
                </td>
            </tr>
            </tbody>
        </table>
    <div class="header_title" style="margin-top: 30px">
        <p>审核记录</p>
    </div>
    <div id="review_ck"></div>

</div>
    <div class="form-group"></div>
    <div class="form-group" style="margin-left: 8px">
        <button class="btn-sm" type="button" style="margin-left: 20px" onclick="ba();">返回</button>
    </div>
</form>

</div>

