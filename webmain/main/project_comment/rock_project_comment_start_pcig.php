<?php if(!defined('HOST'))die('not access');?>

<script>

    var a = '';
    var selected_id = new Array();
    var pici_id = '';
    $(document).ready(function(){
        {params}
        pici_id = params.pici_id;
        $('#myTab_{rand} li:eq(0) a').tab('show');

        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {});

        js.ajax(js.getajaxurl('pici_model', 'project_comment', 'main'), {pici_id:params.pici_id}, function(ds) {
            jsonstr=ds.data;

            formForm(jsonstr);

        }, 'post,json');


        a = $('#project_{rand}').bootstable({
            tablename:'m_pxm_relation',celleditor:true,
            url:js.getajaxurl('startpciglist','project_comment','main', {pici_id:params.pici_id,mtype:'project_start'}),
            fanye:true,modename:'网评列表',
            celleditor:true,storeafteraction:'startpciglistafter',
            //storebeforeaction:'dataauthbefore',
            columns:[{
                text: '<label><input type="checkbox" id="selall{rand}" onclick="selall(this)"/>全选</label>',
                dataIndex: 'bill_id',
                renderer: function(v, d) {
                    return '<input type="checkbox" name="selproject_{rand}" value="' + v + '" />';
                }
            },{
                text:'项目名称',dataIndex:'project_name'
            },{
                text:'申报类型',dataIndex:'modename'
            },{
                text:'负责人',dataIndex:'leader_name'
            },{
                text:'总分',dataIndex:'user_zongfen',sortable: true
            },{
                text:'评审状态',dataIndex:'pingshen_status',sortable: true
            },{
                text:'操作',dataIndex:'caoz',width:'180px'
            }],
        });
        assessmentList = a;

        var c={
            search:function(){
                a.setparams({
                    project_name:get('project_name_{rand}').value,
                    pinshen_status:get('status_select_{rand}').value
                },true);
            },
        };
        js.initbtn(c);

    });

    //列表全选
    function selall(el) {
        var cboxs = $("input[name='selproject_{rand}']");
        //判断全选按钮是否被选中
        if($(el).is(':checked')) {
            $.each(cboxs, function(k, v) {
                $(v).prop("checked", "checked");
            });
        } else {
            $.each(cboxs, function(k, v) {
                $(v).removeAttr("checked");
            });
        }
    }

    //该函数接受一段json字符串为参数，并生成表格
    function formForm(jsonstr) {
        var jsonobj = $.parseJSON(jsonstr);
        $('.xmk-panel-title').html(jsonobj.name);
        var tr = '';
        for(var i = 0; i < jsonobj.info.length; i++) { //循环最外层，一共有多少项总指标
            var td = '';
            var mainTarget = '';
            var itr = '';
            for(var j = 0; j < jsonobj.info[i].info.length; j++) { //连同里层一起循环，一次性把所有行都画出来

                var subTarget = []; //subTarget.length=0;
                var sortRange = []; //sortRange.length=0;
                var mainTarget = []; //mainTarget.length=0;
                var inputScore = []; //inputScore.length=0;
                mainTarget[0] = '<td rowspan="' + jsonobj.info[i].info.length + '">' + jsonobj.info[i].option_msg + '<br />(' + '<span>' + jsonobj.info[i].option_fenzhi + '</span>' + '分)</td>'; //主项标题，需要合并
                subTarget[j] = jsonobj.info[i].info[j].option_msg; //子项标题
                sortRange[j] = jsonobj.info[i].info[j].minscore + '-' + jsonobj.info[i].info[j].maxscore; //子项分值区间
                //inputScore[0] = '<td rowspan="' + jsonobj.info[i].info.length + '"><input name="option" type="text" placeholder="填写分数"></input></td>'; //主项分值填写，需要合并
                //inputScore[0] = '<td rowspan="' + jsonobj.info[i].info.length + '"></td>'; //主项分值填写，需要合并
                itr += '<tr>' + mainTarget[j] + '<td style="text-align: left;">' + subTarget[j] + '</td><td>' + sortRange[j] + '</td>' + inputScore[j] + '</tr>';
            }
            tr += itr;

        }
        $('#mytable tbody').append(tr);
    }

    //num:申报编号,对应flow_bill表的table字段
    //mid:申报编号,对应flow_bill表的mid字段
    //project_name:申报项目名称
    function check_project(num,mid,project_name){
        addtabs({num:'check_'+num+'_{rand}'+mid,url:getRootPath()+'/task.php?a=p&num='+num+'&mid='+mid+'&btnstyle=1',icons:'icon-bookmark-empty',name:'['+project_name+']详情'});
    }

    //评分
    function startpcig_comment(pici_id,num,mid,uid){
        if(get('tabs_add_norm')) closetabs('add_norm');
        if(get('tabs_edit_norm')) closetabs('edit_norm');
        addtabs({num:'edit_norm',url:'main,project_comment,expert,pici_id='+pici_id+',num='+num+',mid='+mid+',uid='+uid,icons:'icon-bookmark-empty',name:'项目评分'});
        thechangetabs('edit_norm');
    }

    //查看
    function startpcig_look(pici_id,num,mid){
        expert_list=a;
        if(get('tabs_look_norm')) closetabs('look_norm');
        addtabs({num:'look_norm',url:'main,project_comment,expert_look,pici_id='+pici_id+',num='+num+',mid='+mid+',type=project_start',icons:'icon-bookmark-empty',name:'查看评分'});
        thechangetabs('look_norm');
    }

    //编辑
    function comment_capgao(pici_id,num,mid,com_status){
        expert_list=a;
        if(get('tabs_add_norm')) closetabs('add_norm');
        if(get('tabs_edit_norm')) closetabs('edit_norm');
        addtabs({num:'edit_norm',url:'main,project_comment,expert_edit,pici_id='+pici_id+',num='+num+',mid='+mid,icons:'icon-bookmark-empty',name:'项目评分'});
        thechangetabs('edit_norm');
    }

    //获取url中的参数
    function getUrlParam(name) {
        var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)"); //构造一个含有目标参数的正则表达式对象
        var r = window.location.search.substr(1).match(reg);  //匹配目标参数
        if (r != null) return unescape(r[2]); return null; //返回参数值
    }

    //发送数据前检测专家收款信息是否完整
    function save() {
        selected_id = new Array();
        var cboxs = $("input[name='selproject_{rand}']");
        $.each(cboxs, function(k, v) {
            if (v.checked){
                selected_id.push($(v).val());
            }
        });
        if (selected_id.length!=0){
            $.ajax({
                url: getRootPath() + "/?d=main&m=project_comment&a=user_bankInfo&ajaxbool=true",
                type: 'post',
                dataType: 'json',
                success: function (res) {
                    var data = res.rows;

                    if (data.bank_name ==null || data.bank_cardnum ==null || data.bank_carduser ==null){
                        layer.confirm('你的收款信息待完善，请完善收款信息后再提交.',
                            {btn:['去完善','取消提交'],closeBtn:0,},
                            function () {
                                addtabs({num: 'infoedit', url: 'system,user,infoedit', icons: 'icon-bookmark-empty', name: '修改个人信息'});
                                layer.close(layer.index);
                            },
                            function () {}
                        )
                    }else {
                        layer.open({
                            type: 1,
                            skin: 'layui-layer-demo', //加上边框
                            area: ['420px', '260px'], //宽高
                            closeBtn: 0, //不显示关闭按钮
                            shadeClose: true, //开启遮罩关闭
                            content: '<div>  ' +
                                '<p>一旦提交，评分无法修改。提交前请确认个人收款信息是否正确。</p>' +
                                '<p>'+data.bank_carduser+'</p>' +
                                '<p>'+data.bank_cardnum+'</p>' +
                                '<p>'+data.bank_name+'</p>' +
                                '<div class="layui-layer-btn layui-layer-btn-">' +
                                '<a class="layui-layer-btn0" onclick="save_score()">确认提交</a>' +
                                '<a class="layui-layer-btn1" onclick="change_info(layer.index)">修改信息</a>' +
                                '</div>'+
                                '</div>',
                        });
                    }
                },
                error: function () {

                }
            });
        }else{
            layer.msg('请勾选项目!');
        }

    }

    //批量提交评审结果
    function save_score() {
        $.ajax({
            url:'./?a=batch_score&m=project_comment&d=main&ajaxbool=true',
            type:'post',
            dataType:'json',
            data:{project_id:selected_id,pici_id:pici_id,types:'project_start'},
            success:function (res) {
                parent.layer.msg('提交评审成功');
                parent.assessmentList.reload();
            }
        })
    }

    //修改个人信息
    function change_info(i) {
        addtabs({num: 'infoedit', url: 'system,user,infoedit', icons: 'icon-bookmark-empty', name: '修改个人信息'});
        layer.close(i);
    }

</script>
<style>

    /*指标表个样式*/
    .xmk-panel-title{
        background: #244d81;
        color: white;
        height: 50px;
        line-height: 50px;
        font-size: 20px;
        display: block;

        text-align: center;
    }
    .xmk-table{
        width: 100%;
        border: none;
        border-collapse: collapse;
        text-align: center;
    }
    .xmk-table{
        width: 100%;
        border: none;
        border-collapse: collapse;
        text-align: center;
    }
    .xmk-table a{
        text-decoration: none;
    }
    .xmk-table a:visited{
        color: #000;
    }
    .xmk-table td{
        border: solid #D6DED3 1px;
        height: 36px;
        padding: 0 10px;
    }
    .xmk-table td {
        border: solid #797979 1px;
        line-height: 30px;
        padding:3px 10px;
    }
    .xmk-table thead td {
        background-color: #fff;
        font-size: 18px;
        line-height: 60px;
    }
    .xmk-table thead{
        background-color: #F1F9EC;

    }
    .xmk-table tfoot td {
        background-color: #fff;
        font-size: 18px;
        line-height: 60px;
    }
    .xmk-table td input {
        border: none;
        width: 100%;
        height: 100%;
        text-align: center;
        outline:medium;
    }
    .xmk-table td input:focus {
        outline: none;
        box-shadow: none;
    }

</style>
<form>
    <section class="serachPanel selBackGround">
        <div class="searchAPanel">
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
            <div class="searchAc1">
                <ul>
                    <li>
                        <span class="reviewContent stateContent">评审状态：</span>
                    </li>
                    <li>
                        <select class="form-control txtPanel" id="status_select_{rand}">
                            <option value="">请选择</option>
                            <option value="0">待评审</option>
                            <option value="2">待提交</option>
                            <option value="1">已提交</option>
                        </select>
                    </li>
                </ul>
            </div>
            <div class="searchAc1" >
                <ul>
                    <li>
                        <input class="btn_ marH1" type="button" click="search" value="查询" />
                    </li>
                    <li>
                        <button class="btn_ marH1" type="reset">重置</button>
                    </li>
                </ul>
            </div>
        </div>
    </section>
</form>
<button id="submit_{rand}" onclick="save()" class="btn_ marH1" style="width: 150px;margin-bottom: 15px;">评审结果批量提交</button>
<div id="project_{rand}"></div>



