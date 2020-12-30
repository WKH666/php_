<?php if(!defined('HOST'))die('not access');?>

<script>
    var a = '';
    var key_word_id = 0;
    $(document).ready(function(){
        a = $('#word_table').bootstable({
            url:js.getajaxurl('wordlist','basic_manage','main',{}),
            tablename:'key_word',
            fanye:true,
            celleditor:true,
            storeafteraction:'wordlistafter',
            columns:[{
                text:'关键词分类',dataIndex:'name',sortable:true
            },{
                text:'添加时间',dataIndex:'add_time',sortable:true
            },{
                text:'操作',dataIndex:'caoz',width:'180px'
            }],

        });
    });

    //添加关键词
    function word_add() {
        project_norm=a;
        $('#myModal').modal('show');
        $('#key_word_input').val('');
        $("#submitBtn").html('保存');
        $("#submitBtn").attr('onclick','confirmSubmit()');
    }

    //编辑关键词(回显)
    function word_edit(word_id){

        if(word_id!=0){//如果是编辑，则获取相应的表单数据
            key_word_id = word_id;
            $('#myModal').modal('show');
            $("#submitBtn").html('编辑');
            $("#submitBtn").attr('onclick','save()');

            js.ajax(js.getajaxurl('getworddetail', 'basic_manage', 'main'), {word_id:word_id}, function(ds) {
                if(!ds.success){
                    layer.msg('获取关键词分类数据失败');
                }else{
                    var word_info = $.parseJSON(ds.data);
                    console.log(word_info.name);
                    $('#key_word_input').val(word_info.name);

                }
            }, 'post,json');
        }
    }

    //编辑后保存
    function save() {
        project_norm = a;
        var word_name = $('#key_word_input').val();
        var data = {'name':word_name,'word_id':key_word_id};

        js.ajax(js.getajaxurl('wordedit', 'basic_manage', 'main'), data, function(ds) {
            layer.msg(ds.msg);
            $('#myModal').modal('hide');
            project_norm.reload();
        }, 'post,json');
    }

    //保存
    function confirmSubmit() {
        project_norm = a;
        var word_name = $('#key_word_input').val();
        var data = {'name':word_name};

        js.ajax(js.getajaxurl('wordadd', 'basic_manage', 'main'), data, function(ds) {
            layer.msg(ds.msg);
            $('#myModal').modal('hide');
            project_norm.reload();
        }, 'post,json');
    }

    //删除关键词
    function word_del(word_id){
        layer.confirm('确认删除该指标？', {
            btn: ['确定', '取消'], //按钮
            shade: 0,
            skin: 'layui-layer-molv',
            closeBtn:0
        }, function() {
            js.ajax(js.getajaxurl('worddel','basic_manage','main'),{'word_id':word_id},function(da){
                var data =js.decode(da);
                layer.msg(data.msg);
                a.reload();
            });
        }, function() {

        });
    }

    //搜索
     function search_word() {
            var s = get('word_input').value;
            a = $('#word_table').bootstable({
                url:js.getajaxurl('wordsearch','basic_manage','main',{name:s}),
                tablename:'key_word',
                fanye:true,
                celleditor:true,
                storeafteraction:'wordlistafter',
                columns:[{
                    text:'关键词分类',dataIndex:'name',sortable:true
                },{
                    text:'添加时间',dataIndex:'add_time',sortable:true
                },{
                    text:'操作',dataIndex:'caoz',width:'180px'
                }],
            });
            a.reload();
    }

    //重置
    function word_reset() {
        $('#word_input').val('');
        a = $('#word_table').bootstable({
            url:js.getajaxurl('wordlist','basic_manage','main',{}),
            tablename:'key_word',
            fanye:true,
            celleditor:true,
            storeafteraction:'wordlistafter',
            columns:[{
                text:'关键词分类',dataIndex:'name',sortable:true
            },{
                text:'添加时间',dataIndex:'add_time',sortable:true
            },{
                text:'操作',dataIndex:'caoz',width:'180px'
            }],

        });
    }



</script>

<style type="text/css">
    .serachPanel{
        display: block;
        padding-left: 1%;
    }
    .serachPanel .searchAc1{
        display: inline-block;
    }
    .serachPanel .searchAc1 ul li{
        float: left;
        height: 40px;
        line-height: 33px;
        padding-right: 10px;

    }

    .serachPanel{
        position: relative;
    }

    .searchAc1 {
        display: inline-block;
    }

    .btn1{
             border-style: none;
             background-color: #108EE9  ;
             color: #FFFFFF;
             border: 1px solid #D6DED3;
             width: 50px;
             height: 33px;
             border-radius: 5px;
             background-image: none;
         }

    .btn2{
        border-style: none;
        background-color: #ffffff;
        color: black;
        border: 1px solid #D6DED3;
        width: 50px;
        height: 33px;
        border-radius: 5px;
        background-image: none;
    }

    .search_input{
        height: 30px;
        border-radius: 5px;
    }
    .bg_section{
        background-color: #f7f7f7;
        border: 1px solid #f7f7f7;
        border-radius: 3px;
        padding-top: 1%;
        margin-bottom: 15px;
        margin-top: 15px;
    }
    button:active{
       color: #5B5F70;
    }

    label{
        font-size: 19px;
    }

    .word_input{
        width: 270px;
        height: 30px;
        border-radius: 6px;
        text-align: center  ;
    }
    .modal-body{
        height: 200px;
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: center;
    }

</style>

<section class="serachPanel bg_section">
    <div class="searchAc1">
        <ul>
            <li>关键词分类：<input type="text" placeholder="请输入关键词分类名称" class="search_input" id="word_input"></li>
            <li><button class="btn1 btn_search" type="button" onclick="search_word()">搜索</button></li>
            <li><button class="btn2 btn_search" type="button" onclick="word_reset()">重置</button></li>
            <li><button class="btn1 btn_search" onclick="word_add()" type="button">新增</button></li>
        </ul>
    </div>
</section>
<div id="word_table"></div>


<!-- 模态框（Modal） -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">编辑或增加关键词分类</h4>
            </div>
            <div class="modal-body">
                <label>关键词分类：</label>
                <input type="text" placeholder="请输入" id="key_word_input" class="word_input">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="submitBtn"  onclick="confirmSubmit()">保存</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal -->
</div>
