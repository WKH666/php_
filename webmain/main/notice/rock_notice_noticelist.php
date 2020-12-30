<?php if (!defined('HOST')) die('not access'); ?>
<script>
    var a = '';
    $(document).ready(function () {
        {params}
        a = $('#view_{rand}').bootstable({
            tablename:'notice',celleditor:true,
            url:js.getajaxurl('noticelist','notice','main', {}),
            fanye:true,modename:'通知书列表',
            //storeafteraction:'noticelistafter',
            columns: [{
                text: '发送标题', dataIndex: 'title'
            }, {
                text: '项目名称', dataIndex: 'project_name'
            }, {
                text: '通知类型', dataIndex: 'type'
            }, {
                text: '通知时间', dataIndex: 'optdt'
            }, {
                text: '操作', dataIndex: 'caoz'
            }],
        });

        var c = {
            reload: function () {
                a.reload();
            },
            search: function () {
                a.setparams({
                    type: get('type_{rand}').value,
                    title: get('title_{rand}').value,
                    project_name: get('projectname_{rand}').value,
                }, true);
            },
            reset: function () {
                $("#type_{rand}").val('');
                $("#title_{rand}").val('');
                $("#projectname_{rand}").val('');
                a.setparams({
                    type: '',
                    title: '',
                    project_name:'',
                }, true);
            },
        };
        js.initbtn(c);
        opegs{rand}= function () {
            c.reload();
        }
    });
    //查看通知书批次中的单项通知书详情
    function readNoticeDetail(id,notice_id,type) {
        addtabs({num:'notice_detial',url:'main,notice,readdetail,id='+id+',notice_id='+notice_id+',type='+type,icons:'icon-bookmark-empty',name:'通知书详情'});
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
    <form>
        <section class="serachPanel selBackGround">
            <div class="searchAPanel">
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
                            <span class="reviewContent stateContent">项目名称：</span>
                        </li>
                        <li>
                            <input type="text" id="projectname_{rand}" name="projectname" class="form-control txtPanel">
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
                            <input class="btn_ marH1" type="button" click="search" value="查询"/>
                        </li>
                        <li>
                            <button class="btn_ marH1" type="reset"  click="reset">重置</button>
                        </li>
                    </ul>
                </div>
            </div>
        </section>
    </form>
</div>
<div class="blank10"></div>
<div id="view_{rand}"></div>

