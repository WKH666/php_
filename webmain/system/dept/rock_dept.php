<?php ///*if(!defined('HOST'))die('not access');*/?>
<!--<script >-->
<!--    $(document).ready(function(){-->
<!--        var a = $('#veiw_{rand}').bootstable({-->
<!--            tablename:'dept',modenum:'dept',celleditor:true,-->
<!--            url:js.getajaxurl('data','dept','system'),tree:true,-->
<!--            columns:[{-->
<!--                text:'名称',dataIndex:'name',align:'left'-->
<!--            },{-->
<!--                text:'编号',dataIndex:'num',editor:true-->
<!--            },{-->
<!--                text:'负责人',dataIndex:'headman'-->
<!--            },{-->
<!--                text:'上级ID',dataIndex:'pid'-->
<!--            },{-->
<!--                text:'排序号',dataIndex:'sort',editor:true-->
<!--            },{-->
<!--                text:'ID',dataIndex:'id'-->
<!--            }],-->
<!--            itemclick:function(d){-->
<!--                btn(false,d);-->
<!--            }-->
<!--        });-->
<!---->
<!--        var c = {-->
<!--            del:function(){-->
<!--                a.del();-->
<!--            },-->
<!--            clickwin:function(o1,lx){-->
<!--                var h = $.bootsform({-->
<!--                    title:'组织结构',height:400,width:400,-->
<!--                    tablename:'dept',isedit:lx,-->
<!--                    url:js.getajaxurl('publicsave','dept','system'),-->
<!--                    params:{int_filestype:'sort'},-->
<!--                    submitfields:'name,sort,headman,headid,pid,num',-->
<!--                    items:[{-->
<!--                        labelText:'名称',name:'name',required:true-->
<!--                    },{-->
<!--                        labelText:'编号',name:'num'-->
<!--                    },{-->
<!--                        name:'headid',type:'hidden'-->
<!--                    },{-->
<!--                        labelText:'负责人',type:'changeuser',changeuser:{-->
<!--                            type:'usercheck',idname:'headid',title:'选择部门负责人'-->
<!--                        },name:'headman',clearbool:true-->
<!--                    },{-->
<!--                        labelText:'上级ID',name:'pid',value:0,type:'number'-->
<!--                    },{-->
<!--                        labelText:'序号',name:'sort',type:'number',value:'0'-->
<!--                    }],-->
<!--                    success:function(){-->
<!--                        a.reload();-->
<!--                    }-->
<!--                });-->
<!--                if(lx==1){-->
<!--                    h.setValues(a.changedata);-->
<!--                }-->
<!--                h.getField('name').focus();-->
<!--                return h;-->
<!--            },-->
<!--            clickdown:function(){-->
<!--                if(a.changeid==0)return;-->
<!--                var a1 = this.clickwin(false,0);-->
<!--                a1.setValue('pid', a.changeid);-->
<!--            }-->
<!--        };-->
<!---->
<!--        function btn(bo,d){-->
<!--            get('edit_{rand}').disabled = bo;-->
<!--            get('down_{rand}').disabled = bo;-->
<!--            if(d.id==1)bo=true;-->
<!--            get('del_{rand}').disabled = bo;-->
<!--        }-->
<!---->
<!--        js.initbtn(c);-->
<!--    });-->
<!--</script>-->
<!---->
<!--<div>-->
<!--    <ul class="floats">-->
<!--        <li class="floats50">-->
<!--            -->
<!--            <button class="btn btn-primary" click="clickwin,0" type="button"><i class="icon-plus"></i> 新增</button> &nbsp;-->
<!--            -->
<!--            <button class="btn btn-success" click="clickdown" id="down_{rand}" disabled type="button"><i class="icon-plus"></i> 新增下级</button>-->
<!--        </li>-->
<!--        <li class="floats50" style="text-align:right">-->
<!--            <button class="btn btn-info" id="edit_{rand}" click="clickwin,1" disabled type="button"><i class="icon-edit"></i> 编辑 </button>-->
<!--            <button class="btn btn-danger" id="del_{rand}" click="del" disabled type="button"><i class="icon-trash"></i> 删除</button> &nbsp;-->
<!---->
<!--        </li>-->
<!--    </ul>-->
<!--</div>-->
<!--<div class="blank10"></div>-->
<!--<div id="veiw_{rand}"></div>-->
<!--<div class="tishi">组织结构必须只能有一个最顶级的，ID必须为1，且不允许删除</div>-->



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>组织架构</title>
    <link rel="stylesheet" href="mode/ztree/css/zTreeStyle.css">
    <link rel="stylesheet" href="mode/ztree/css/ztree.css">
    <link href="mode/bootstrap/css/bootstrap-table.min.css" rel="stylesheet">
    <script src="mode/bootstrap/js/bootstrap-table.min.js"></script>
    <script src="mode/bootstrap/js/bootstrap-table-ZN.min.js"></script>
    <style>
        .sort{
            display: flex;
            flex-direction: row;
            width: 100%;
        }
        .sort_headHandler{
            background-color: #CDE3F1;
            padding: 5px 0 5px 5px;
            border-radius: 5px;
        }
        .group_sort{
            width: 426px;
            margin-right: 20px;
        }
        .group_person{
            width: 100%;
        }
        .search_group{
            margin: 5px 0;
            position: relative;
        }
        .search_group i{
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: gray;
        }
        /*滚动条的宽度*/
        ::-webkit-scrollbar {
            width:5px;
            height:5px;
        }
        /*外层轨道。可以用display:none让其不显示，也可以添加背景图片，颜色改变显示效果*/
        ::-webkit-scrollbar-track {
            width: 6px;
            background-color: #f0f6e4;
            -webkit-border-radius: 2em;
            -moz-border-radius: 2em;
            border-radius:2em;
        }
        /*滚动条的设置*/
        ::-webkit-scrollbar-thumb {
            background-color: #e2e8d6;
            background-clip:padding-box;
            min-height:28px;
            -webkit-border-radius: 2em;
            -moz-border-radius: 2em;
            border-radius:2em;
        }
        /*滚动条移上去的背景*/
        ::-webkit-scrollbar-thumb:hover {
            background-color:#fff;
        }
    </style>
</head>
<body>
<div class="sort">
    <div class="group_sort">
        <div class="sort_headHandler">组织架构</div>
        <div class="group_list">
            <div class="search_group">
                <input type="search" placeholder="请输入内容" class="form-control" id="dept_search" autocomplete="on">
                <i class="glyphicon glyphicon-search" id="search_icon_dept"></i>
            </div>
            <div class="zTreeDemoBackground">
                <ul id="treeDemo_dept" class="ztree"></ul>
            </div>
        </div>
    </div>

    <div class="group_person">
        <div class="sort_headHandler">组织人员</div>
        <div class="person_content">
            <table id="realTime_Table" data-click-to-select="true" class="table table-bordered" data-page-size="10"></table>
        </div>
    </div>
    <div class="modal_group"></div>
</div>
</body>
<script src="mode/ztree/js/jquery.ztree.core.js" type="text/javascript"></script>
<script src="mode/ztree/js/jquery.ztree.excheck.js" type="text/javascript"></script>
<script src="mode/ztree/js/jquery.ztree.exedit.js" type="text/javascript"></script>
<script src="mode/ztree/js/jquery.ztree.exhide.js" type="text/javascript"></script>
<script src="js/js.js"></script>
<?php if(!defined('HOST'))die('not access');?>
<script type="text/javascript">
    /**
     * ztree插件setting配置
     * @type {{view: {selectedMulti: boolean, addHoverDom: addHoverDom, removeHoverDom: removeHoverDom, showIcon: boolean, expandSpeed: string}, data: {simpleData: {idKey: string, enable: boolean, pIdKey: string, rootPId: number}}, edit: {removeTitle: string, enable: boolean, renameTitle: string, showRenameBtn: boolean, editNameSelectAll: boolean, showRemoveBtn: boolean}, callback: {onClick: zTreeOnClick, beforeRemove: beforeRemove, beforeRename: beforeRename}}}
     */
    var setting = {
        view: {
            expandSpeed:"fast",
            addHoverDom: addHoverDom,
            removeHoverDom: removeHoverDom,
            selectedMulti: false,
            showIcon: false
        },
        edit: {
            enable: true,
            showRemoveBtn: true,
            showRenameBtn: true,
            editNameSelectAll: true,
            removeTitle: "删除节点",
            renameTitle: "编辑节点名称"
        },
        data: {
            simpleData: {
                enable: true,
                idKey: "id",
                pIdKey: "pid",
                rootPId: 0
            }
        },
        callback: {
            beforeRemove: beforeRemove,
            beforeRename: beforeRename,
            onClick: zTreeOnClick
        }
    };

    /**
     * 用于当鼠标移动到节点上时，显示用户自定义控件，显示隐藏状态同 zTree 内部的编辑、删除按钮
     * @type {number}
     */
    var newCount = 1;
    function addHoverDom(treeId, treeNode) {
        var sObj = $("#" + treeNode.tId + "_span");
        if (treeNode.editNameFlag || $("#addBtn_"+treeNode.tId).length>0 || $("#seeBtn_"+treeNode.tId).length>0) return;
        if(treeNode.isParent){
            var addStr = "<span class='button add' id='addBtn_" + treeNode.tId
                + "' title='添加节点' onfocus='this.blur();' ></span>";
            addStr += "<span class='button see' id='seeBtn_" + treeNode.tId
                + "' title='查看节点' onfocus='this.blur();' ></span>";
            sObj.after(addStr);
            var add_btn = $("#addBtn_"+treeNode.tId);
            var see_btn = $("#seeBtn_"+treeNode.tId);
            if(add_btn && see_btn){
                add_btn.bind("click", function(){
                    var html = '<!-- 模态框（Modal） -->\n' +
                        '    <div class="modal fade" id="myModalupload_'+treeNode.id+'" tabindex="-1" role="dialog" aria-labelledby="myModalLabel_upload" aria-hidden="true">\n' +
                        '        <div class="modal-dialog">\n' +
                        '            <div class="modal-content">\n' +
                        '                <div class="modal-header">\n' +
                        '                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>\n' +
                        '                    <h4 class="modal-title" id="myModalLabel_upload" style="text-align: center;">添加/编辑组织</h4>\n' +
                        '                </div>\n' +
                        '                <div class="modal-body" style="display: flex;justify-content: center;align-items: center;">\n' +
                        '                    <form>\n' +
                        '                        <div class="form-group" style="display: flex;flex-direction: row;align-items: center;margin: 0;">\n' +
                        '                            <label style="width: 150px;text-align: right;margin: 0;">组织编号：</label>\n' +
                        '                            <input type="text" class="form-control" placeholder="请输入，仅支持字母输入" value="" id="organization_num"><br/>\n' +
                        '                        </div>\n' +
                        '                        <div class="form-group" style="display: flex;flex-direction: row;align-items: center;margin: 0;">\n' +
                        '                            <h6 style="color: red;text-align: right;width: 100%;">*一级组织才有编号，用于申报书登记号的生成规则</h6>\n' +
                        '                        </div>\n' +
                        '                        <div class="form-group" style="display: flex;flex-direction: row;align-items: center;">\n' +
                        '                            <label style="width: 150px;text-align: right;margin: 0;">组织名称：</label>\n' +
                        '                            <input type="text" class="form-control" placeholder="请输入" value="" id="organization_name">\n' +
                        '                        </div>\n' +
                        // '                        <div class="form-group" style="display: flex;flex-direction: row;align-items: center;">\n' +
                        // '                            <label style="width: 150px;text-align: right;margin: 0;">负责人：</label>\n' +
                        // '                            <input type="text" class="form-control" placeholder="请输入" value="">\n' +
                        // '                        </div>\n' +
                        // '                        <div class="form-group" style="display: flex;flex-direction: row;align-items: center;">\n' +
                        // '                            <label style="width: 150px;text-align: right;margin: 0;">联系电话：</label>\n' +
                        // '                            <input type="text" class="form-control" placeholder="请输入" value="">\n' +
                        // '                        </div>\n' +
                        '                    </form>\n' +
                        '                </div>\n' +
                        '                <div class="modal-footer" style="display: flex;flex-direction: row;justify-content: center;">\n' +
                        '                    <button type="button" class="btn btn-primary" id="'+treeNode.id+'" onclick="addbtn_click(this)">保存</button>\n' +
                        '                    <button type="button" class="btn btn-default" data-dismiss="modal" onclick="resetbtn_click()">取消</button>\n' +
                        '                </div>\n' +
                        '            </div><!-- /.modal-content -->\n' +
                        '        </div><!-- /.modal -->\n' +
                        '    </div>';
                    $(".modal_group").append(html);
                    $('#myModalupload_'+treeNode.id).modal('show');
                    return false;
                });
                see_btn.bind('click',function(){
                    js.ajax(js.getajaxurl('seeicon_deptsort', 'dept', 'system'), {id : treeNode.id }, function(data) {
                        if(data.num == null) data.num = '';
                        if(data.name == null) data.name = '';
                        if(data.controller == null) data.controller = '';
                        if(data.mobile == null) data.mobile = '';
                        var html = '<div class="modal fade" id="myModalkeep_'+treeNode.id+'" tabindex="-1" role="dialog" aria-labelledby="myModalLabel_keep" aria-hidden="true">\n' +
                            '        <div class="modal-dialog">\n' +
                            '            <div class="modal-content">\n' +
                            '                <div class="modal-header">\n' +
                            '                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>\n' +
                            '                    <h4 class="modal-title" id="myModalLabel_keep" style="text-align: center">查看组织</h4>\n' +
                            '                </div>\n' +
                            '                <div class="modal-body" style="display: flex;justify-content: center;align-items: center;">\n' +
                            '                    <form>\n' +
                            '                        <div class="form-group" style="display: flex;flex-direction: row;align-items: center;">\n' +
                            '                            <label style="width: 150px;text-align: right;margin: 0;">组织编号：</label>\n' +
                            '                            <input type="text" class="form-control" value="'+ data.num +'">\n' +
                            '                        </div>\n' +
                            '                        <div class="form-group" style="display: flex;flex-direction: row;align-items: center;">\n' +
                            '                            <label style="width: 150px;text-align: right;margin: 0;">组织名称：</label>\n' +
                            '                            <input type="text" class="form-control" value="'+ data.name +'">\n' +
                            '                        </div>\n' +
                            // '                        <div class="form-group" style="display: flex;flex-direction: row;align-items: center;">\n' +
                            // '                            <label style="width: 150px;text-align: right;margin: 0;">负责人：</label>\n' +
                            // '                            <input type="text" class="form-control" value="'+ data.controller +'">\n' +
                            // '                        </div>\n' +
                            // '                        <div class="form-group" style="display: flex;flex-direction: row;align-items: center;">\n' +
                            // '                            <label style="width: 150px;text-align: right;margin: 0;">联系电话：</label>\n' +
                            // '                            <input type="text" class="form-control" value="'+ data.mobile +'">\n' +
                            // '                        </div>\n' +
                            '                    </form>\n' +
                            '                </div>\n' +
                            '                <div class="modal-footer" style="display: flex;flex-direction: row;justify-content: center;">\n' +
                            '                    <button type="button" class="btn btn-primary" id="'+treeNode.id+'" onclick="seebtn_click(this)">保存</button>\n' +
                            '                    <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>\n' +
                            '                </div>\n' +
                            '            </div><!-- /.modal-content -->\n' +
                            '        </div><!-- /.modal -->\n' +
                            '    </div>';
                        $(".modal_group").append(html);
                        $('#myModalkeep_'+treeNode.id).modal('show');
                    }, 'post,json');
                    return false;
                });
            }
        }else{
            var addStr = "<span class='button see' id='seeBtn_" + treeNode.tId
                + "' title='查看节点' onfocus='this.blur();' ></span>";
            sObj.after(addStr);
            var see_btn = $("#seeBtn_"+treeNode.tId);
            if(see_btn){
                see_btn.bind('click',function(){
                    js.ajax(js.getajaxurl('seeicon_deptsort', 'dept', 'system'), {id : treeNode.id }, function(data) {
                        if(data.num == null) data.num = '';
                        if(data.name == null) data.name = '';
                        if(data.controller == null) data.controller = '';
                        if(data.mobile == null) data.mobile = '';
                        var html = '<div class="modal fade" id="myModalkeep_'+treeNode.id+'" tabindex="-1" role="dialog" aria-labelledby="myModalLabel_keep" aria-hidden="true">\n' +
                            '        <div class="modal-dialog">\n' +
                            '            <div class="modal-content">\n' +
                            '                <div class="modal-header">\n' +
                            '                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>\n' +
                            '                    <h4 class="modal-title" id="myModalLabel_keep" style="text-align: center">查看组织</h4>\n' +
                            '                </div>\n' +
                            '                <div class="modal-body" style="display: flex;justify-content: center;align-items: center;">\n' +
                            '                    <form>\n' +
                            '                        <div class="form-group" style="display: flex;flex-direction: row;align-items: center;">\n' +
                            '                            <label style="width: 150px;text-align: right;margin: 0;">组织编号：</label>\n' +
                            '                            <input type="text" class="form-control" value="'+ data.num +'">\n' +
                            '                        </div>\n' +
                            '                        <div class="form-group" style="display: flex;flex-direction: row;align-items: center;">\n' +
                            '                            <label style="width: 150px;text-align: right;margin: 0;">组织名称：</label>\n' +
                            '                            <input type="text" class="form-control" value="'+ data.name +'">\n' +
                            '                        </div>\n' +
                            // '                        <div class="form-group" style="display: flex;flex-direction: row;align-items: center;">\n' +
                            // '                            <label style="width: 150px;text-align: right;margin: 0;">负责人：</label>\n' +
                            // '                            <input type="text" class="form-control" value="'+ data.controller +'">\n' +
                            // '                        </div>\n' +
                            // '                        <div class="form-group" style="display: flex;flex-direction: row;align-items: center;">\n' +
                            // '                            <label style="width: 150px;text-align: right;margin: 0;">联系电话：</label>\n' +
                            // '                            <input type="text" class="form-control" value="'+ data.mobile +'">\n' +
                            // '                        </div>\n' +
                            '                    </form>\n' +
                            '                </div>\n' +
                            '                <div class="modal-footer" style="display: flex;flex-direction: row;justify-content: center;">\n' +
                            '                    <button type="button" class="btn btn-primary" id="'+treeNode.id+'" onclick="seebtn_click(this)">保存</button>\n' +
                            '                    <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>\n' +
                            '                </div>\n' +
                            '            </div><!-- /.modal-content -->\n' +
                            '        </div><!-- /.modal -->\n' +
                            '    </div>';
                        $(".modal_group").append(html);
                        $('#myModalkeep_'+treeNode.id).modal('show');
                    }, 'post,json');
                    return false;
                });
            }
        }
    };

    /**
     * 添加/编辑组织弹出框保存添加
     * @param obj
     */
    function addbtn_click(obj){
        // var num = obj.parentNode.parentNode.children[1].children[0][0].value;
        // var name = obj.parentNode.parentNode.children[1].children[0][1].value;
        // var controller = obj.parentNode.parentNode.children[1].children[0][2].value;
        // var mobile = obj.parentNode.parentNode.children[1].children[0][3].value;
        var num = $("#organization_num").val();
        var name = $("#organization_name").val();
        if(num && name){
            js.ajax(js.getajaxurl('addicon_deptsort_upload', 'dept', 'system'), {
                pid : obj.id,
                num : num,
                name : name,
                // controller : controller,
                // mobile : mobile
            }, function(data) {
                if(data){
                    $("#organization_num").val('');
                    $("#organization_name").val('');
                    $('#myModalupload_'+obj.id).modal('hide');
                    $.fn.zTree.init($("#treeDemo_dept"),setting,data);
                }else{
                    console.log(data);
                }
            }, 'post,json');
        }
    }

    /**
     * 添加/编辑组织弹出框取消重置
     */
    function resetbtn_click(){
        $("#organization_num").val('');
        $("#organization_name").val('');
    }

    /**
     * 查看组织弹出框保存添加
     * @param obj
     */
    function seebtn_click(obj){
        var num = obj.parentNode.parentNode.children[1].children[0][0].value;
        var name = obj.parentNode.parentNode.children[1].children[0][1].value;
        // var controller = obj.parentNode.parentNode.children[1].children[0][2].value;
        // var mobile = obj.parentNode.parentNode.children[1].children[0][3].value;
        js.ajax(js.getajaxurl('seeicon_deptsort_upload', 'dept', 'system'), {
            id : obj.id ,
            num : num,
            name : name,
            // controller : controller,
            // mobile : mobile
        }, function(data) {
            if(data){
                $('#myModalkeep_'+obj.id).modal('hide');
                $.fn.zTree.init($("#treeDemo_dept"),setting,data);
            }else{
                console.log(data);
            }
        }, 'post,json');
    }

    /**
     * 用于当鼠标移出节点时，隐藏用户自定义控件，显示隐藏状态同 zTree 内部的编辑、删除按钮
     * @param treeId
     * @param treeNode
     */
    function removeHoverDom(treeId, treeNode) {
        $("#addBtn_"+treeNode.tId).unbind().remove();
        $("#seeBtn_"+treeNode.tId).unbind().remove();
    };

    /**
     * 用于捕获节点被删除之前的事件回调函数，并且根据返回值确定是否允许删除操作
     * @param treeId
     * @param treeNode
     * @returns {boolean}
     */
    function beforeRemove(treeId, treeNode) {
        var zTree = $.fn.zTree.getZTreeObj("treeDemo_dept");
        zTree.selectNode(treeNode);
        var msg_bool = confirm("确认删除 节点 -- " + treeNode.name + " 吗？");
        if(msg_bool){
            js.ajax(js.getajaxurl('publicdel', 'index'), { id : treeNode.id,table:'xinhu_dept',modenum:'dept'}, function(data) {
                js.ajax(js.getajaxurl('request_dept', 'dept', 'system'),{}, function(data) {
                    $.fn.zTree.init($("#treeDemo_dept"),setting,data);
                }, 'post,json');
            }, 'post,json');
            return true;
        }else{
            return false;
        }
    }

    /**
     * 用于捕获节点编辑名称结束（Input 失去焦点 或 按下 Enter 键）之后，更新节点名称数据之前的事件回调函数，并且根据返回值确定是否允许更改名称的操作
     * @param treeId
     * @param treeNode
     * @param newName
     * @returns {boolean}
     */
    function beforeRename(treeId, treeNode, newName) {
        if (newName.length == 0) {
            setTimeout(function() {
                var zTree = $.fn.zTree.getZTreeObj("treeDemo_dept");
                zTree.cancelEditName();
                alert("节点名称不能为空.");
            }, 0);
            return false;
        }else{
            js.ajax(js.getajaxurl('editicon_deptsort', 'dept', 'system'), { id : treeNode.id , name: newName}, function(data) {
                $.fn.zTree.init($("#treeDemo_dept"),setting,data);
            }, 'post,json');
        }
        return true;
    }

    /**
     * 用于捕获节点被点击的事件回调函数
     * @param event
     * @param treeId
     * @param treeNode
     */
    function zTreeOnClick(event, treeId, treeNode) {
        var curWwwPath = window.document.location.href;
        var pathName = window.document.location.pathname;
        var pos = curWwwPath.indexOf(pathName);
        var current_directoryPath = pathName.substring(0,pathName.lastIndexOf("/"));
        var localhostPaht = curWwwPath.substring(0,pos);
        var table_requestPath = localhostPaht + current_directoryPath + "/" + js.getajaxurl('gettable', 'dept', 'system');
        var table_requestPath2 = localhostPaht + current_directoryPath + "/" + js.getajaxurl('request_person', 'dept', 'system');
        $.post(table_requestPath2,{ name : treeNode.name },function(data){
            $('#realTime_Table').bootstrapTable('destroy');
            $("#realTime_Table").bootstrapTable({
                url:table_requestPath,
                method: 'post',
                search: false,
                pagination: true,
                pageSize: 15,
                pageList: [5, 10, 15, 20],
                showColumns: false,
                showRefresh: false,
                showToggle: false,
                locale: "zh-CN",
                striped: true,
                columns:[
                    {field: 'i', title: '序号', formatter:function (value,row,index) { return index+1; }},
                    {field: 'user', title: '账号'},
                    {field: 'name', title: '姓名'},
                    {field: 'email', title: '电子邮箱'},
                    {field: 'status', title: '状态'},
                    {field: 'adddt', title: '添加时间'}
                ]
            });


        });
    };

</script>
<script type="text/javascript">
    /**
     * 因jq版本引发的问题解决方法
     */
    jQuery.browser = {};
    (function () {
        jQuery.browser.msie = false;
        jQuery.browser.version = 0;
        if (navigator.userAgent.match(/MSIE ([0-9]+)\./)) {
            jQuery.browser.msie = true;
            jQuery.browser.version = RegExp.$1;
        }
    })();

    $(document).ready(function(){
        /**
         * 页面异步加载树形数据
         */
        js.ajax(js.getajaxurl('request_dept', 'dept', 'system'),{}, function(data) {
            $.fn.zTree.init($("#treeDemo_dept"),setting,data);
        }, 'post,json');


        /**
         *  搜索栏按下enter键
         *  模糊搜索(单个或多个字进行搜索，无内容返回全部)
         */
        $("#dept_search").on('keydown',function(event){
            if(event.keyCode == 13){
                js.ajax(js.getajaxurl('search_deptsort', 'dept', 'system'), {name : $("#dept_search").val() }, function(data) {
                    $.fn.zTree.init($("#treeDemo_dept"),setting,data);
                }, 'post,json');
                return false;
            }else{
                js.ajax(js.getajaxurl('request_dept', 'dept', 'system'), {}, function(data) {
                    $.fn.zTree.init($("#treeDemo_dept"),setting,data);
                }, 'post,json');
                return true;
            }
        });

        /**
         *  搜索栏点击搜索图标
         *  模糊搜索(单个或多个字进行搜索，无内容返回全部)
         */
        $("#search_icon_dept").on('click',function(){
            if($("#dept_search").val() != ''){
                js.ajax(js.getajaxurl('search_deptsort', 'dept', 'system'), {name : $("#dept_search").val() }, function(data) {
                    $.fn.zTree.init($("#treeDemo_dept"),setting,data);
                }, 'post,json');
                return false;
            }else{
                js.ajax(js.getajaxurl('request_dept', 'dept', 'system'), {}, function(data) {
                    $.fn.zTree.init($("#treeDemo_dept"),setting,data);
                }, 'post,json');
                return true;
            }
        });

    })
</script>
</html>