<?php
if (!defined('HOST')) die('not access'); ?>
<script>
    var a = '';
    $(document).ready(function () {
        {params}
        if (params.notice_type==3 || params.notice_type == 5 || params.notice_type == 1 || params.notice_type == 6 || params.notice_type == 2){
            //研究基地,普及月,课题立项，课题编制要求，课题结项
            a = $('#view_{rand}').bootstable({
                url: js.getajaxurl('noticeread', 'notice', 'main', {'notice_id':params.notice_id,'notice_type':params.notice_type}),
                fanye: true, modename: '',
                celleditor: true,
                storeafteraction:'noticereadafter',
                columns: [{
                    text: '登记号', dataIndex: 'sericnum'
                }, {
                    text: '项目名称', dataIndex: 'project_name'
                }, {
                    text: '申报类型', dataIndex: 'project_type'
                }, {
                    text: '负责人', dataIndex: 'leader'
                }, {
                    text: '单位', dataIndex: 'company'
                }, {
                    text: '发送时间', dataIndex: 'send_time'
                }, {
                    text: '操作', dataIndex: 'caoz', width: '100px'
                }],
            });
        }
        else if(params.notice_type == 4){
            //常态化
            a = $('#view_{rand}').bootstable({
                url: js.getajaxurl('noticeread', 'notice', 'main', {'notice_id':params.notice_id,'notice_type':params.notice_type}),
                fanye: true, modename: '',
                celleditor: true,
                storeafteraction:'noticereadafter',
                columns: [{
                    text: '登记号', dataIndex: 'sericnum'
                }, {
                    text: '项目名称', dataIndex: 'project_name'
                }, {
                    text: '申报类型', dataIndex: 'project_type'
                }, {
                    text: '负责人', dataIndex: 'contact_person'
                }, {
                    text: '单位', dataIndex: 'company'
                }, {
                    text: '发送时间', dataIndex: 'send_time'
                }, {
                    text: '操作', dataIndex: 'caoz', width: '100px'
                }],
            });
        }
        var c = {
            del: function () {
                a.del();
            },
            reload: function () {
                a.reload();
            },
            search: function () {
                a.setparams({
                    sericnum: get('sericnum_{rand}').value,
                    project_name: get('project_name_{rand}').value,
                  //  project_type: get('project_type_{rand}').value
                    leader: get('leader_{rand}').value,
                    company: get('company_{rand}').value
                }, true);
            },

        };
        js.initbtn(c);
    });

    //重写tabs改变事件
    function thechangetabs(num) {
        $("div[temp='content']").hide();
        $("[temp='tabs']").removeClass();
        var bo = false;
        if (get('content_' + num + '')) {
            $('#content_' + num + '').show();
            $('#tabs_' + num + '').addClass('accive');
            nowtabs = tabsarr[num];
        }
        opentabs.push(num);
        _changhhhsv(num);
    }

    //查看通知书批次中的单项通知书详情
    function readNoticeDetail(id,notice_id,type) {
        addtabs({num:'notice_detial',url:'main,notice,readdetail,id='+id+',notice_id='+notice_id+',type='+type,icons:'icon-bookmark-empty',name:'通知书详情'});
    }

    function dow(pici_id, lx) {
        url = getRootPath() + '/?d=main&m=project_comment&a=getExcel&pici_id=' + pici_id + '&lx=' + lx;
        js.open(url, 800, 500);
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

<form>
    <section class="serachPanel selBackGround">
        <div class="searchAPanel">
            <div class="searchAc1">
                <ul>
                    <li>
                        <span class="reviewContent stateContent">登记号：</span>
                    </li>
                    <li>
                        <input type="text" id="sericnum_{rand}" name="sericnum" class="form-control txtPanel">
                    </li>
                </ul>
            </div>
            <div class="searchAc1">
                <ul>
                    <li>
                        <span class="reviewContent stateContent">项目名称：</span>
                    </li>
                    <li>
                        <input type="text" id="project_name_{rand}" name="project_name" class="form-control txtPanel">
                    </li>
                </ul>
            </div>
            <!--<div class="searchAc1">
                <ul>
                    <li>
                        <span class="reviewContent stateContent">申报类型：</span>
                    </li>
                    <li>
                        <input type="text" id="project_type_{rand}" name="project_type" class="form-control txtPanel">
                    </li>
                </ul>
            </div>-->
            <div class="searchAc1">
                <ul>
                    <li>
                        <span class="reviewContent stateContent">负责人：</span>
                    </li>
                    <li>
                        <input type="text" id="leader_{rand}" name="leader" class="form-control txtPanel">
                    </li>
                </ul>
            </div>
            <div class="searchAc1">
                <ul>
                    <li>
                        <span class="reviewContent stateContent">单位：</span>
                    </li>
                    <li>
                        <input type="text" id="company_{rand}" name="company" class="form-control txtPanel">
                    </li>
                </ul>
            </div>
            <div class="searchAc1">
                <ul>
                    <li>
                        <input class="btn_ marH1" type="button" click="search" value="查询"/>
                    </li>
                    <li>
                        <button class="btn_ marH1" type="reset">重置</button>
                    </li>
                </ul>
            </div>
        </div>
    </section>
</form>
<div id="view_{rand}"></div>


