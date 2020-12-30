<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>学科分类</title>
    <link rel="stylesheet" href="mode/ztree/css/zTreeStyle.css">
    <link rel="stylesheet" href="mode/ztree/css/ztree.css">
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
        .subject_sort{
            width: 426px;
            margin-right: 20px;
        }
        .add_sort{
            width: 100%;
        }
        .add_sort_inputgroup form .form-group{
            margin: 10px 0;
            display: flex;
            flex-direction: row;
            align-items: center;
            width: 500px;
        }
        .form-group label{
            width: 100px;
            font-size: 16px;
            font-weight: 100;
            margin: 0;
        }
        form .btn_group button{
            width: 65px;
            height: 35px;
        }
        form .btn_group button:nth-of-type(1){
            margin: 20px;
            outline: none;
            box-shadow: none;
        }
        form .btn_group button:nth-of-type(2){
            outline: none;
            box-shadow: none;
        }
        .search_subject{
            margin: 5px 0;
            position: relative;
        }
        .search_subject i{
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
    <div class="subject_sort">
        <div class="sort_headHandler">学科分类</div>
        <div class="subject_list">
            <div class="search_subject">
                <input type="search" placeholder="请输入内容" class="form-control" id="sort_search" autocomplete="on">
                <i class="glyphicon glyphicon-search" id="search_icon"></i>
            </div>
            <div class="zTreeDemoBackground">
                <ul id="treeDemo_subject" class="ztree"></ul>
            </div>
        </div>
    </div>
    <div class="add_sort">
        <div class="sort_headHandler">添加分类</div>
        <div class="add_sort_inputgroup">
            <form>
                <div class="form-group">
                    <label >学科分类：</label>
                    <input type="text" placeholder="请输入" class="form-control" id="upload_subjectsort" name="upload_subjectsort" autocomplete="on">
                </div>
                <div class="form-group btn_group">
                    <button type="button" class="btn btn-primary" id="sort_upload" disabled>保存</button>
                    <button type="button"class="btn btn-default" id="reset_input">取消</button>
                </div>
            </form>

        </div>
    </div>
</div>
</body>
<script src="mode/ztree/js/jquery.ztree.core.js" type="text/javascript"></script>
<script src="mode/ztree/js/jquery.ztree.excheck.js" type="text/javascript"></script>
<script src="mode/ztree/js/jquery.ztree.exhide.js" type="text/javascript"></script>
<script src="mode/ztree/js/jquery.ztree.exedit.js" type="text/javascript"></script>
<script src="js/js.js" type="text/javascript"></script>
<?php if(!defined('HOST'))die('not access');?>
<script type="text/javascript">
    /**
     * ztree插件setting配置
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
            keep:{
                parent: true
            },
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
        }
    };

    /**
     * 用于当鼠标移动到节点上时，显示用户自定义控件，显示隐藏状态同 zTree 内部的编辑、删除按钮
     * @type {number}
     */
    var newCount = 1;
    function addHoverDom(treeId, treeNode) {
        var sObj = $("#" + treeNode.tId + "_span");
        if (treeNode.editNameFlag || $("#addBtn_"+treeNode.tId).length>0) return;
        if(treeNode.isParent){
            var addStr = "<span class='button add' id='addBtn_" + treeNode.tId
                + "' title='添加节点' onfocus='this.blur();'></span>";
            sObj.after(addStr);

            var btn = $("#addBtn_"+treeNode.tId);
            if (btn) btn.bind("click", function(){
                js.ajax(js.getajaxurl('addicon_subjectsort', 'sort', 'system'), { pid:treeNode.id, name:"新节点" }, function(data) {
                    $.fn.zTree.init($("#treeDemo_subject"),setting,data);
                }, 'post,json');
                return false;
            });

        }


    };


    /**
     * 用于当鼠标移出节点时，隐藏用户自定义控件，显示隐藏状态同 zTree 内部的编辑、删除按钮
     * @param treeId
     * @param treeNode
     */
    function removeHoverDom(treeId, treeNode) {
        $("#addBtn_"+treeNode.tId).unbind().remove();
    };

    /**
     * 用于捕获节点被删除之前的事件回调函数，并且根据返回值确定是否允许删除操作
     * @param treeId
     * @param treeNode
     * @returns {boolean}
     */
    function beforeRemove(treeId, treeNode) {
        var zTree = $.fn.zTree.getZTreeObj("treeDemo_subject");
        zTree.selectNode(treeNode);
        var msg_bool = confirm("确认删除 节点 -- " + treeNode.name + " 吗？");
        if(msg_bool){
            js.ajax(js.getajaxurl('delicon_subjectsort', 'sort', 'system'), { id : treeNode.id}, function(data) {
                $.fn.zTree.init($("#treeDemo_subject"),setting,data);
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
                var zTree = $.fn.zTree.getZTreeObj("treeDemo_subject");
                zTree.cancelEditName();
                alert("节点名称不能为空.");
            }, 0);
            return false;
        }else{
            js.ajax(js.getajaxurl('editicon_subjectsort', 'sort', 'system'), { id : treeNode.id , name: newName}, function(data) {
                $.fn.zTree.init($("#treeDemo_subject"),setting,data);
            }, 'post,json');
        }
        return true;
    }

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
        js.ajax(js.getajaxurl('request_subject', 'sort', 'system'), {}, function(data) {
            $.fn.zTree.init($("#treeDemo_subject"),setting,data);
        }, 'post,json');

        /**
         *  搜索栏按下enter键
         *  模糊搜索(单个或多个字进行搜索，无内容返回全部)
         */
        $("#sort_search").on('keydown',function(event){
            if(event.keyCode == 13){
                js.ajax(js.getajaxurl('search_subjectsort', 'sort', 'system'), {name : $("#sort_search").val()}, function(data) {
                    $.fn.zTree.init($("#treeDemo_subject"),setting,data);
                }, 'post,json')
                return false;
            }else{
                js.ajax(js.getajaxurl('request_subject', 'sort', 'system'), {}, function(data) {
                    $.fn.zTree.init($("#treeDemo_subject"),setting,data);
                }, 'post,json');
                return true;
            }
        });

        /**
         *  搜索栏点击搜索图标
         *  模糊搜索(单个或多个字进行搜索，无内容返回全部)
         */
        $("#search_icon").on('click',function(){
            if($("#sort_search").val() != ''){
                js.ajax(js.getajaxurl('search_subjectsort', 'sort', 'system'), {name : $("#sort_search").val() }, function(data) {
                    $.fn.zTree.init($("#treeDemo_subject"),setting,data);
                    var zTreeObj = $.fn.zTree.getZTreeObj("treeDemo_subject");
                    zTreeObj.expandAll(true);
                }, 'post,json');
                return false;
            }else{
                js.ajax(js.getajaxurl('request_subject', 'sort', 'system'), {}, function(data) {
                    $.fn.zTree.init($("#treeDemo_subject"),setting,data);
                }, 'post,json');
                return true;
            }
        });

        /**
         * 学科分类输入框有无内容时保存按钮改变可用与不可用状态
         */
        $("#upload_subjectsort").on('input',function () {
            if ($("#upload_subjectsort")[0].value != ""){
                $("#sort_upload")[0].disabled = false;
            } else{
                $("#sort_upload")[0].disabled = true;
            }
        });

        /**
         * 保存上传
         */
        $("#sort_upload").click(function(){
            js.ajax(js.getajaxurl('addbtn_subjectsort', 'sort', 'system'), {upload_subjectsort : $("#upload_subjectsort").val()}, function(data) {
                $.fn.zTree.init($("#treeDemo_subject"),setting,data);
            }, 'post,json')
        });

        /**
         * 取消按钮保存按钮改变为不可用状态
         */
        $("#reset_input").click(function(){
            $("#upload_subjectsort").val('');
            $("#sort_upload")[0].disabled = true;
        });


    })
</script>

</html>