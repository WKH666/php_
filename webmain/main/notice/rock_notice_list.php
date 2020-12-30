<?php if (!defined('HOST')) die('not access'); ?>
<script>
    var a = '';
    $(document).ready(function () {
        {params}
        a = $('#view_{rand}').bootstable({
            tablename: 'notice', params: {}, fanye: true,
            url: publicstore('{mode}', '{dir}'),
            storeafteraction: 'informationafter', storebeforeaction: 'informationbefore',
            columns: [{
                text: '发送标题', dataIndex: 'title'
            }, {
                text: '通知类型', dataIndex: 'type'
            }, {
                text: '发布数量', dataIndex: 'num'
            }, {
                text: '发布人', dataIndex: 'opt'
            }, {
                text: '状态', dataIndex: 'fabu_status'
            }, {
                text: '发布时间', dataIndex: 'optdt', sortable: true
            }, {
                text: '操作', dataIndex: 'caoz'
            }],
        });

        var c = {
            reload: function () {
                a.reload();
            },
            search: function () {
                var time_frame = '';//时间范围
                if(get('dt1_{rand}').value != "" && get('dt2_{rand}').value != ""){
                    time_frame = get('dt1_{rand}').value+','+get('dt2_{rand}').value
                }
                a.setparams({
                    launch_time:time_frame,
                    type: get('type_{rand}').value,
                    title: get('title_{rand}').value
                }, true);
            },
            reset: function () {
                $("#type_{rand}").val('');
                $("#title_{rand}").val('');
                $("#dt1_{rand}").val('');
                $("#dt2_{rand}").val('');
                a.setparams({
                    type: '',
                    launch_time: '',
                    title: '',
                }, true);
            },
            fabu: function () {
                /*11/05修改 start*/
                /*$.ajax({
                    url:'?m=index&a=getshtml&surl='+jm.base64encode('main/notice/rock_notice_fabutype')+'',
                    type:'get',
                    success: function(da){
                        $('#mainloaddiv').remove();
                        var s = da;
                        /!*s = s.replace(/\{rand\}/gi, rand);
                        s = s.replace(/\{adminid\}/gi, adminid);
                        s = s.replace(/\{adminname\}/gi, adminname);
                        s = s.replace(/\{mode\}/gi, mode);
                        s = s.replace(/\{dir\}/gi, dir);
                        s = s.replace(/\{params\}/gi, "var params={"+urlpms+"};");*!/
                        var obja = $('#content_notice');//notice是后台菜单编辑的编号
                        obja.html(s);
                    },
                    error:function(){
                        $('#mainloaddiv').remove();
                        var s = 'Error:加载出错喽,'+url+'';
                        $('#content_'+num+'').html(s);
                    }
                });*/
                assessmentList = a;
                addtabs({num: 'fabutype_p', url: 'main,notice,fabutype', icons: 'icon-bookmark-empty', name: '选择发布类型'});
                /*end*/
            },
            clickdt:function(o1, lx){
                $(o1).rockdatepicker({initshow:true,view:'date',inputid:'dt'+lx+'_{rand}'});
            },
        };
        js.initbtn(c);
        opegs{rand}= function () {
            c.reload();
        }
    });

    //查看已发布的通知书
    function read(notice_id, notice_type) {
        addtabs({num: 'fabu_p', url: 'main,notice,read,notice_type='+notice_type+',notice_id='+notice_id, icons: 'icon-bookmark-empty', name: '通知书发送列表'});
    }

    //编辑草稿
    function edit_draft(notice_id) {
        assessmentList = a;
        addtabs({num: 'fabu_p', url: 'main,notice,fabu,notice_id='+notice_id, icons: 'icon-bookmark-empty', name: '编辑通知书'});
    }

</script>
<style type="text/css">
    .serachPanel {
        display: block;
        padding-left: 1%;
        /*min-width: 700px;*/
    }

    .serachPanel .searchAc1 {
        display: inline-block;
        /*width: 17%;
        min-width: 271px;*/
    }

    .serachPanel .searchAc1 ul li {
        float: left;
        height: 40px;
        line-height: 33px;
        /*padding: 0% 1%;*/
        padding-right: 10px;
    }

    .serachPanel .searchAPanel {
        position: relative;
    }

    .searchAc {
        text-align: center;
        margin-top: 5px;
        margin-bottom: 10px;
    }

    .searchAc a {
        font-size: 12px;
        color: #555555;
    }

    .selSearch {
        height: 32px;
        line-height: 32px;
    }

    .form-control {
        height: 32px;
        line-height: 32px;
    }

    .btn-default {
        padding: 5px 12px;
    }

    .tTabc ul li {
        width: 100%;
        text-align: center;
        border: 1px solid #eee;
        padding-bottom: 2%;
        margin-top: 2%;
    }

    .tTabc ul li span {
        display: block;
        text-align: center;
        font-size: 20px;
        /*margin: 1% 0%;*/
    }

    .searchAc1 .stateContent {
        display: inline-block;
        /*width: 90px;
        text-indent: 20%;*/
    }


    /*流程*/
    .processDe {
        background-color: #419af1;
        color: #fff;
        display: inline-block;
        font-size: 20px;
        padding: 3% 0%;
        width: 12%;
        text-align: center;
        border-radius: 5px;
    }

    .bgG {
        background-color: #848484 !important;
    }

    .processImg {
        color: #fff;
        display: inline-block;
        font-size: 20px;
        padding: 2% 4%;
        width: 10%;
        text-align: center;
    }

    .processImg img {
        text-align: center;
        width: auto;
        max-width: 100%;
    }

    #layerhtml {
        font-size: 24px;
        display: table-cell;
        vertical-align: middle;
    }

    .layui-layer-content {
        display: table;
        width: 100%;
        padding: 0% 5%;
    }

    a[name="shenbao"] {
        color: #428bca !important;
        cursor: pointer;
    }

    /*返回上一级a标签的样式*/
    .callbackone {
        position: absolute;
        display: block;
        right: 5.8%;
        bottom: 5%;
        font-size: 15px;
    }
</style>
<div>
<!--    <table id="mytable">
        <tbody>
        <tr>
            <td class="form-group">
                <label>通知类型：</label>
                <input class="form-control" id="type_{rand}" placeholder="请输入">
            </td>
            <td class="form-group">
                <label>发布时间：</label>
                <input class="form-control" id="optdt_{rand}" placeholder="请输入">
            </td>
        </tr>
        <tr>
            <td class="form-group">
                <button class="btn btn-default" click="search" type="button" id="search">搜索</button>
                <button class="btn btn-default" click="reset" type="button" id="reset">重置</button>
                <button class="btn btn-default" click="fabu" type="button" id="fabu">发布</button>
            </td>
        </tr>
        </tbody>
    </table>-->
    <form>
        <section class="serachPanel selBackGround">
            <div class="searchAPanel">
                <div class="searchAc1" style="min-width: 440px;">
                    <ul>
                        <li>
                            <span class="reviewContent stateContent">发布时间：</span>
                        </li>
                        <li >
                            <div class="timepicker" style="margin-bottom: 0.7%;">
                                <div style="display: inline-block;">
                                    <div  class="input-group txtPanel">
                                        <input placeholder="开始" readonly class="form-control" id="dt1_{rand}" >
                                        <span class="input-group-btn">
                                    <button class="btn btn-default" click="clickdt,1" type="button">
                                    <i class="icon-calendar"></i>
                                    </button> </span>
                                    </div>
                                </div>
                                <div style="display: inline-block;">
                                    <div  class="input-group txtPanel">
                                        <input placeholder="结束" readonly class="form-control" id="dt2_{rand}" >
                                        <span class="input-group-btn">
                                    <button class="btn btn-default" click="clickdt,2" type="button">
                                    <i class="icon-calendar"></i>
                                    </button> </span>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="searchAc1">
                    <ul>
                        <li>
                            <span class="reviewContent stateContent">通知类型：</span>
                        </li>
                        <li>
                            <select class="form-control txtPanel" id="type_{rand}" name="type">
                                <option value="">请选择</option>
                                <option value="1">课题申报立项通知书</option>
                                <option value="2">课题申报结项通知书</option>
                                <option value="3">普及月申报入选通知书</option>
                                <option value="4">常态化申报入选通知书</option>
                                <option value="5">研究基地立项通知书</option>
                            </select>
                        </li>
                    </ul>
                </div>
                <div class="searchAc1">
                    <ul>
                        <li>
                            <span class="reviewContent stateContent">发送标题：</span>
                        </li>
                        <li>
                            <input type="text" id="title_{rand}" name="title" class="form-control txtPanel">
                        </li>
                    </ul>
                </div>
                <div class="searchAc1">
                    <ul>
                        <li>
                            <input class="btn_ marH1" type="button" click="search" value="查询"/>
                        </li>
                        <li>
                            <button class="btn_ marH1" type="reset"  click="reset">重置</button>
                        </li>
                        <li>
                            <button class="btn_ marH1"  click="fabu" type="button" id="fabu">发布</button>
                        </li>
                    </ul>
                </div>
            </div>
        </section>
    </form>
</div>
<div class="blank10"></div>
<div id="view_{rand}"></div>
<div class="tishi">提示：删除将会是彻底删除，不能恢复，请谨慎操作！如提示无删除权限，请到[流程模块→流程模块权限]上添加权限。
 <div>
