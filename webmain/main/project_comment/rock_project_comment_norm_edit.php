<?php if (!defined('HOST')) die('not access'); ?>
<script>
    var delids = new Array();//记录移除的id数组
    var norm_id = 0;//指标id
    $(document).ready(function () {
        {params}
        norm_id = params.norm_id;
        if (!norm_id) norm_id = 0;
        var index = 1;//序号
        /*
        * 编辑
        * */
        if (norm_id != 0) {//如果是编辑，则获取相应的表单数据
            $("#submitBtn_{rand}").html('保存');
            $("#submitBtn_{rand}").attr('onclick', 'save()');
            //加载动画
            var bgs = '<div id="mainloaddiv" style="width:' + viewwidth + 'px;height:' + viewheight + 'px;overflow:hidden;background:#000000;color:white;filter:Alpha(opacity=20);opacity:0.2;z-index:3;position:absolute;left:0px;line-height:' + viewheight + 'px;top:0px;" align="center"><img src="images/mloading.gif"  align="absmiddle">&nbsp;加载中...</div>';
            $('#indexcontent').append(bgs);
            js.ajax(js.getajaxurl('getnormdetail', 'project_comment', 'main'), {norm_id: norm_id}, function (ds) {
                if (!ds.success) {
                    layer.msg('获取指标数据失败');
                } else {
                    var norm_info = $.parseJSON(ds.data);
                    console.log(norm_info.info);
                   // console.log(norm_info);
                    //数据代入
                    $('#norm_name').val(norm_info.name);//指标名称
                    $('#mtype').val(norm_info.mtype);//项目类别
                    //删除所有main-target
                    $(".main-target").remove();

                    //一级指标和二级指标的代入
                    var norm_html = '';
                    $.each(norm_info.info, function (oneKey, oneEl) {
                        norm_html += "<div class='main-target' data-id=" + oneEl.id + ">";
                        norm_html += "	<div>";
                        norm_html += "		<ul class='table-ul'>";
                        norm_html += "			<li class='oneli inlinethis'>";
                        norm_html += "			<div class='text-space'>序号：</div>";
                        norm_html += "			<input class='xmk-input xmk-ssm-input main-target-index' name='index' type='text' value='" + oneEl.sort + "'/>";
                        norm_html += "			</li>";
                        norm_html += "			<li class='twoli inlinethis'>";
                        norm_html += "			<div class='text-space firtarget'>一级指标名称：</div>";
                        norm_html += "			<input class='xmk-input xmk-md-input main-target-title' type='text' name='title' value='" + oneEl.option_msg + "' placeholder='一级标题'/>";
                        norm_html += "			</li>";
                        norm_html += "			<li class='threeli inlinethis'>";
                        norm_html += "			<div class='text-space letter'>分数：</div>";
                        norm_html += "			<input class='xmk-input xmk-sdm-input main-target-score' type='text' name='option' value='" + oneEl.option_fenzhi + "' placeholder='该项分数'/>";
                        norm_html += "			</li>";
                        norm_html += "			<li class='fourli inlinethis'>";
                        norm_html += "			<span class='xmk-span-btn remove-target'>移 除</span>";
                        norm_html += "			</li>";
                        norm_html += "		</ul>";
                        norm_html += "	</div>";
                        norm_html += "	<div class='sub-target-container'>";
                        $.each(oneEl.info, function (twoKey, twoEl) {
                            norm_html += "		<div class='sub-target' data-id=" + twoEl.id + ">";
                            norm_html += "			<ul class='table-ul'>";
                            norm_html += "				<li class='oneli inlinethis'>";
                            norm_html += "				<div>二级指标内容：</div>";
                            norm_html += "				</li>";
                            norm_html += "				<li class='twoli inlinethis'>";
                            norm_html += "				<input class='xmk-input xmk-lg-input xmk-left-fix sub-title' name='title' type='text' value='" + twoEl.option_msg + "' placeholder='二级标题'/>";
                            norm_html += "				</li>";
                            norm_html += "				<li class='threeli inlinethis'>";
                            norm_html += "				<div class='text-space'>赋分范围：</div>";
                            norm_html += "				<input class='xmk-input xmk-sm-input' name='minsorce' type='text' value='" + twoEl.minscore + "'/>";
                            norm_html += "				<div>~</div>";
                            norm_html += "				<input class='xmk-input xmk-sm-input' name='maxsorce' type='text' value='" + twoEl.maxscore + "'/>";
                            norm_html += "				</li>";
                            norm_html += "				<li class='fourli inlinethis'>";
                            norm_html += "				<span class='xmk-span-btn add-item'>添加</span><span class='xmk-span-btn remove-item'>移除</span>";
                            norm_html += "				</li>";
                            norm_html += "			</ul>";
                            norm_html += "		</div>";
                        });
                        norm_html += "	</div>";
                        norm_html += "</div>";
                    });
                    $("#form_{rand}").append(norm_html);
                    index = norm_info.info.length;//从最后一个数开始
                    $('#mainloaddiv').remove();
                }
            }, 'post,json');
        }


        /*
        * 增加
        * */
        //添加一级指标
        $("body").on("click", '#addmaintarget', function () {
            /*update：2017年5月21日20:21:18
             * 这里的变量内容留空，这是为了当初方便调试，从代码里分离的
             * 提示内容，即灰色字体写在placeholder里了
             *
             * */
            var title = ''; //一级标题
            var score = ''; //分数
            var minscore = ''; //分数下限
            var maxscore = score; //分数上线
            var subtitle = ''; //二级标题
            index++;
            //var ele = '<div class="main-target"><div><ul class="table-ul"><li class="oneli inlinethis"><div class="text-space">序号</div><input class="xmk-input xmk-ssm-input main-target-index"type="text"value="'+index+'"/></li><li class="twoli inlinethis"><div class="text-space">一级指标</div><input class="xmk-input xmk-md-input main-target-title"type="text"value="'+title+'"placeholder="一级标题"/></li><li class="threeli inlinethis"><div class="text-space letter">分数</div><input class="xmk-input xmk-sdm-input main-target-score"type="text"value="'+score+'"placeholder="该项分数"/></li><li class="fourli inlinethis"><span class="xmk-span-btn remove-target">移除</span></li></ul></div><div class="sub-target-container"><div class="sub-target"><ul class="table-ul"><li class="oneli inlinethis"><div>二级指标内容</div></li><li class="twoli inlinethis"><input class="xmk-input xmk-lg-input xmk-left-fix sub-title"type="text"value="'+subtitle+'"placeholder="二级标题"/></li><li class="threeli inlinethis"><div class="text-space">赋分范围</div><input class="xmk-input xmk-sm-input"type="text"value="'+minscore+'"/><div>~</div><input class="xmk-input xmk-sm-input"type="text"value="'+maxscore+'"/></li><li class="fourli inlinethis"><span class="xmk-span-btn add-item">添加</span><span class="xmk-span-btn remove-item">移除</span></li></ul></div></div></div>';
            var ele = '<div class="main-target" data-id=0 data-control="add">' +
                '<div>' +
                '<ul class="table-ul">' +
                '<li class="oneli inlinethis">' +
                '<div class="text-space">序号：</div>' +
                '<input class="xmk-input xmk-ssm-input main-target-index"name="index"type="text"value="' + index + '"/>' +
                '</li>' +
                '<li class="twoli inlinethis">' +
                '<div class="text-space firtarget">一级指标名称：</div>' +
                '<input class="xmk-input xmk-md-input main-target-title"type="text"name="title"value="' + title + '"placeholder="一级标题"/>' +
                '</li>' +
                '<li class="threeli inlinethis">' +
                '<div class="text-space letter">分数：</div>' +
                '<input class="xmk-input xmk-sdm-input main-target-score"type="text"name="option"value="' + score + '"placeholder="该项分数"/>' +
                '</li>' +
                '<li class="fourli inlinethis">' +
                '<span class="xmk-span-btn remove-target">移 除</span>' +
                '</li>' +
                '</ul>' +
                '</div>' +
                '<div class="sub-target-container">' +
                '<div class="sub-target">' +
                '<ul class="table-ul">' +
                '<li class="oneli inlinethis">' +
                '<div>二级指标内容：</div>' +
                '</li>' +
                '<li class="twoli inlinethis">' +
                '<input class="xmk-input xmk-lg-input xmk-left-fix sub-title"name="title"type="text"value="' + subtitle + '"placeholder="二级标题"/>' +
                '</li>' +
                '<li class="threeli inlinethis">' +
                '<div class="text-space">赋分范围：</div>' +
                '<input class="xmk-input xmk-sm-input"name="minsorce"type="text"value="' + minscore + '"/>' +
                '<div>~</div>' +
                '<input class="xmk-input xmk-sm-input"name="maxsorce"type="text"value="' + maxscore + '"/>' +
                '</li>' +
                '<li class="fourli inlinethis">' +
                '<span class="xmk-span-btn add-item">添加</span>' +
                '<span class="xmk-span-btn remove-item">移除</span>' +
                '</li>' +
                '</ul>' +
                '</div>' +
                '</div>' +
                '</div>';
            //if(index==2)ele = '<div class="main-target"><div><ul class="table-ul"><li class="oneli inlinethis"><div class="text-space">序号</div><input class="xmk-input xmk-ssm-input main-target-index"name="index"type="text"value="1"/></li><li class="twoli inlinethis"><div class="text-space">一级指标</div><input class="xmk-input xmk-md-input main-target-title"type="text"name="title"value=""placeholder="一级标题"/></li><li class="threeli inlinethis"><div class="text-space letter">分数</div><input class="xmk-input xmk-sdm-input main-target-score"type="text"name="option"value=""placeholder="该项分数"/></li><li class="fourli inlinethis"><span class="xmk-span-btn remove-target">移除</span></li></ul></div><div class="sub-target-container"><div class="sub-target"><ul class="table-ul"><li class="oneli inlinethis"><div>二级指标内容</div></li><li class="twoli inlinethis"><input class="xmk-input xmk-lg-input xmk-left-fix sub-title"name="title"type="text"value=""placeholder="二级标题"/></li><li class="threeli inlinethis"><div class="text-space">赋分范围</div><input class="xmk-input xmk-sm-input"name="minsorce"type="text"value=""/><div>~</div><input class="xmk-input xmk-sm-input"name="maxsorce"type="text"value=""/></li><li class="fourli inlinethis"><span class="xmk-span-btn add-item">添加</span><span class="xmk-span-btn remove-item">移除</span></li></ul></div></div></div>';
            $("#form_{rand}").append(ele);

            /*
             * 2017年5月22日10:03:49
             * --增加指标后页面自动下滚
             */

            //$('#indexcontent').animate({scrollTop: $('#content_add_norm').height()}, 1000);
            $('#indexcontent').scrollTop($('#content_add_norm').height());
        });
        /*
         2017年5月23日12:27:29新增几处验证
         * */
        $("body").on("change", '.main-target-index,.main-target-score,input[name="minsorce"],input[name="maxsorce"]', function () {//验证序号
            var c = $(this);
            if (/[^\d]/.test(c.val())) {//替换非数字字符
                var temp_amount = c.val().replace(/[^\d]/g, '');
                $(this).val(temp_amount);
            }
        });


        $("body").on("change", '.main-target-index', function () {//验证序号
            var index = [];
            var count = 0;
            $('.main-target-index').each(function (i) {
                index.push($(this).val());
            });

            for (var j = 0; j < index.length; j++) {
                if ($(this).val() == index[j]) {
                    count++;
                }
            }
            if (count > 1) {
                layer.msg("项目序号重复了，请修改！");
            }
        });


        //添加二级指标
        $("#form_{rand}").on("click", '.add-item', function () {
            var subtitle = ''; //二级标题
            var minscore = ''; //最小分数
            var maxscore = ''; //最大分数
            //var ele = '<div class="sub-target"><ul class="table-ul"><li class="oneli inlinethis"><div>二级指标内容</div></li><li class="twoli inlinethis"><input class="xmk-input xmk-lg-input xmk-left-fix sub-title"type="text"value="'+subtitle+'"placeholder="二级标题"/></li><li class="threeli inlinethis"><div class="text-space">赋分范围</div><input class="xmk-input xmk-sm-input"type="text"value="'+minscore+'"/><div>~</div><input class="xmk-input xmk-sm-input"type="text"value="'+maxscore+'"/></li><li class="fourli inlinethis"><span class="xmk-span-btn add-item">添加</span><span class="xmk-span-btn remove-item">移除</span></li></ul></div>';
            var ele = '<div class="sub-target" data-id=0 data-control="add">' +
                '<ul class="table-ul">' +
                '<li class="oneli inlinethis">' +
                '<div>二级指标内容：</div>' +
                '</li>' +
                '<li class="twoli inlinethis">' +
                '<input class="xmk-input xmk-lg-input xmk-left-fix sub-title"type="text"name="title"value="' + subtitle + '"placeholder="二级标题"/>' +
                '</li>' +
                '<li class="threeli inlinethis">' +
                '<div class="text-space">赋分范围：</div>' +
                '<input class="xmk-input xmk-sm-input"name="minsorce"type="text"value="' + minscore + '"/>' +
                '<div>~</div>' +
                '<input class="xmk-input xmk-sm-input"name="maxsorce"type="text"value="' + maxscore + '"/>' +
                '</li>' +
                '<li class="fourli inlinethis">' +
                '<span class="xmk-span-btn add-item">添加</span>' +
                '<span class="xmk-span-btn remove-item">移除</span>' +
                '</li>' +
                '</ul>' +
                '</div>';
            $(this).parent().parent().parent().after(ele);
        });

        //二级指标移除
        $("body").on("click", '.remove-item', function () {
            var count = $(this).parent().parent().parent().parent().children('.sub-target').length;
            if (count > 1) {
                if ($(this).parent().parent().parent().attr('data-id') != 0)
                //console.log($(this).parent().parent().parent().attr('data-id'))
                    delids.push($(this).parent().parent().parent().attr('data-id'));
                $(this).parent().parent().parent().remove();
            }
            /*		else {
                        alert("请至少保留一项");
                    }*/
        });

        //一级指标移除
        $("body").on("click", '.remove-target', function () {
            if ($(this).parent().parent().parent().parent().attr('data-id') != 0)
            //console.log($(this).parent().parent().parent().parent().attr('data-id'));
                delids.push($(this).parent().parent().parent().parent().attr('data-id'));
            $(this).parent().parent().parent().parent().remove();
        });

        //判断该指标是否为编辑操作
        $("body").on("change", "input[type='text']", function () {
            //寻找父级main-target
            if ($(this).parents('.main-target') && $(this).parents('.main-target').attr('data-id') != 0)
                $(this).parents('.main-target').attr('data-control', 'edit');
        });

    });


    function getData(type) {
        var name = $('#norm_name').val();//指标名称
        var mtype = $('#mtype').val();//项目类别
        /*var first_norm_title = $('#first_norm_title').val();//一级指标名称
        var second_norm_title = $('#second_norm_title').val();//二级指标名称
        var natural_title = $('#natural_title').val();//指标赋分名称
        var option_title = $('#option_title').val();//指标分值名称*/

        // json 对象初始化
        /*var data = {
            "name": name,
            //"mtype": mtype,
            "first_norm_title": first_norm_title,
            "second_norm_title": second_norm_title,
            "natural_title": natural_title,
            "option_title": option_title,
            "info": [],
        }*/
        var data = {
            "name": name,
            "mtype": mtype,
            "info": [],
        }
        var info = new Array();

        // 第一次遍历开始
        //
        // 节点获取
        if (type != undefined) var main_target = $('.main-target[data-control="' + type + '"]');
        else var main_target = $('.main-target');

        // 把第一次循环的数据放进 JSON 对象
        //data['info'].push(firstItem);
        $.each(main_target, function (k, el) {
            //排序号
            var sort = $(el).children("div").children("ul").find('input[name="index"]').val();
            //一级标题
            var title = $(el).children("div").children("ul").find('input[name="title"]').val();
            //分数
            var score = $(el).children("div").children("ul").find('input[name="option"]').val();
            var firstItem = {
                "id": $(el).attr('data-id'),
                "option_msg": title,
                "option_fenzhi": score,
                //"option_range": "",
                "sort": sort,
                "level": 1,
                "info": [],
            }
            // 第一次循环继续中，轮到sub-target-container了
            var sub_target_container = $(el).children('.sub-target-container');
            var sun_target = sub_target_container.children('.sub-target');
            $.each(sun_target, function (sub_k, sub_el) {//sub-target
                // 获取二级标题
                var sub_title = $(sub_el).children("ul").find('input[name="title"]').val();
                // 获取赋分下限
                var scoreMin = $(sub_el).children("ul").find('input[name="minsorce"]').val();
                // 获取赋分上限
                var scoreMax = $(sub_el).children("ul").find('input[name="maxsorce"]').val();
                var secondItem = {
                    "id": $(sub_el).attr('data-id'),
                    "option_msg": sub_title,
                    "minscore": scoreMin,
                    'maxscore': scoreMax,
                    "sort": sub_k + 1,
                    "level": 2
                }
                // 把循环一次的数据放进 firstItem 的 '二级标题' 中，
                firstItem['info'].push(secondItem);
            });
            info[k] = firstItem;
        });
        if (type != undefined) return info;
        data['info'] = info.sort(compare('sort'));
        //console.info(data);
        //console.info(JSON.stringify(data));
        return data;
    }

    //获取编辑数据
    function getEditData() {
        //要添加的数据
        var addData = getData('add');
        //修改的数据
        var editData = getData('edit');
        //删除的数据
        var delData = delids;

        var name = $('#norm_name').val();//指标名称
        var mtype = $('#mtype').val();//项目类别

        var data = {
            "norm_id": norm_id,
            "name": name,
            "mtype": mtype,
            "edit_control": {
                "addinfo": addData,
                "editinfo": editData,
                "delinfo": delData
            }
        };
        return data;
    }

    //判断序号是否有重复
    function nemberRepeat() {
        var tmp = [];
        arr = [];
        num = false;
        $('.main-target-index').each(function (i) {
            arr.push($(this).val());
        });
        for (var i in arr) {
            if (tmp.indexOf(arr[i]) == -1) {
                tmp.push(arr[i])
            } else {
                num = arr[i];
            }
        }
        return num;

    }

    //编辑保存
    function save() {
        if (checkFilling()) {
            layer.msg("你的信息尚未填写完整！");
        } else if (checkSubScore()) {
            layer.msg("子项目最大分值超过了父项目或最低分超过最高分，请修改！");
        } else if (checkTotalScore()) {
            layer.msg("所有指标分数之和没有等于100分，请修改！");
        } else if (nemberRepeat()) {
            layer.msg("项目序号" + nemberRepeat() + "重复了，请修改！");
        } else {
            var data = getEditData();
            //console.log(data);
            js.ajax(js.getajaxurl('normedit', 'project_comment', 'main'), data, function (ds) {
                layer.msg(ds.msg);
                closenowtabs();
                project_norm.reload();
            }, 'post,json');
        }
    }


    //数组根据某个元素对比
    function compare(property) {
        return function (a, b) {
            var value1 = a[property];
            var value2 = b[property];
            return value1 - value2;
        }
    }

    //保证所有表单都已被填写
    function checkFilling() {
        for (var i = 0; i < $('.add-target-page input[type="text"]').length; i++) {
            if ($('.add-target-page input[type="text"]')[i].value == "") {
                //alert("请补完未填选！");
                return true;
            }
        }
    }

    //总分要等于100
    function checkTotalScore() {
        var totalscore = 0;
        var data = getData();
        for (var i = 0; i < data.info.length; i++) {
            totalscore += parseInt(data.info[i].option_fenzhi);
        }

        if (totalscore != 100) {
            return true;
        }
    }

    //子项最大分值和要小于父项
    function checkSubScore() {
        var data = getData();
        for (var i = 0; i < data.info.length; i++) {
            var total = 0;
            for (var j = 0; j < data.info[i].info.length; j++) {
                total += parseInt(data.info[i].info[j].maxscore) - parseInt(data.info[i].info[j].minscore);
                //console.log(parseInt(data.info[i].info[j].maxscore)+'-----'+parseInt(data.info[i].info[j].minscore));
                //低分超过高分
                if (parseInt(data.info[i].info[j].maxscore) < parseInt(data.info[i].info[j].minscore)) {
                    return data.info[i].sort;
                }
                //最大分大过分值
                if (parseInt(data.info[i].info[j].maxscore) > parseInt(data.info[i].option_fenzhi)) {
                    return data.info[i].sort;
                }
                //最小分大过分值
                if (parseInt(data.info[i].info[j].minscore) > parseInt(data.info[i].option_fenzhi)) {
                    //alert('低分超过高分');
                    return data.info[i].sort;
                }
            }
            if (total > parseInt(data.info[i].option_fenzhi)) {
                //console.log(total);
                return data.info[i].sort; //超过该项最大分值
            }
        }
    }

    //检验表单是否填写正确
    function confirmSubmit() {
        if (checkFilling()) {
            layer.msg("你的信息尚未填写完整！");
        } else if (checkSubScore()) {
            layer.msg("序号" + checkSubScore() + "的子项目最大分值超过了父项目或最低分超过最高分，请修改！");
        } else if (checkTotalScore()) {
            layer.msg("所有指标分数之和没有等于100分，请修改！");
        } else if (nemberRepeat()) {
            layer.msg("项目序号" + nemberRepeat() + "重复了，请修改！");
        } else {
            toSubmit();
        }

    }


    //提交
    function toSubmit() {
        var data = getData();
        //console.log(data);
        js.ajax(js.getajaxurl('addnorm', 'project_comment', 'main'), data, function (ds) {
            layer.msg(ds.msg);
            closenowtabs();
            project_norm.reload();
        }, 'post,json');

    }

    //预览
    function preview() {
        var data = JSON.stringify(getData());
        sessionStorage.setItem("normpreview", data);
        var url = getRootPath() + '/webmain/main/project_comment/rock_project_comment_norm_look.php';
        //测试站预览地址
        //var url = getRootPath() + '/webmain/main/project_comment/rock_project_comment_norm_look.php';
        js.open(url, 900, 600);
    }

    //关闭页面
    function cancel_func() {
        closenowtabs();
        project_norm.reload();
    }
</script>

<style>
    .submit_btn{
        background-color: #108ee9!important;
    }
    .preview_btn{
        background-color: #cccccc!important;
        color: black!important;
    }
    .cancel_btn{
        background-color: #f3f3f3!important;
        color: black!important;
    }


    .xmk-lg-v-space {
        display: block;
        height: 80px;
    }

    .xmk-md-v-space {
        display: block;
        height: 40px;
    }

    .xmk-sm-v-space {
        display: block;
        height: 20px;
    }

    .xmk-lg-h-space {
        display: inline-block;
        width: 30%;
    }

    .xmk-md-h-space {
        display: inline-block;
        width: 20%;
    }

    .xmk-sm-h-space {
        display: inline-block;
        width: 10%;
    }

    .xmk-btn {
        display: inline-block;
        height: 28px;
        background-color: #244D81;
        border-radius: 5px;
        border: none;
        color: #fff;
        font-size: 12px;
        padding: 5px 15px;
        margin-left: 15px;
        line-height: 14px;
    }

    .xmk-btn:hover {
        background-color: #337AB7;
    }

    .xmk-btn-grey {
        background-color: #898989;

    }

    .xmk-panel-title {
        display: block;
        width: 100%;
        color: #fff;
        background-color: #456791;
        padding: 5px 0 5px 20px;
    }

    .xmk-sub-title {
        display: block;
        background-color: #F1F9EC;
        color: #3AA59B;
        font-size: 22px;
        text-align: center;
        padding: 10px 0;

    }

    .xmk-table {
        width: 100%;
        border: none;
        border-collapse: collapse;
        text-align: center;
    }

    .xmk-table a {
        text-decoration: none;
    }

    .xmk-table a:visited {
        color: #000;
    }

    .xmk-link {
        color: #000;
        text-decoration: none;
    }

    .xmk-lg-height {
        height: 44px;
        line-height: 44px;
    }

    .xmk-td-center {
        text-align: center;
    }

    .xmk-td-border {
        border: solid #D6DED3 1px;
    }

    .xmk-target-content {
        border-top: solid #999 1px;
        line-height: 30px;
    }

    .xmk-target-item {
        margin: 10px 0px;
    }

    .xmk-flowing-bar {
        display: block;
        width: 100%;
        position: fixed;
        margin-left: -10px;
        bottom: 0px;
        border-top: solid #ccc 2px;
        padding: 10px 0;
        background-color: #fff;
        text-align: center;
    }

    .xmk-input {
        display: inline-block;
        border: solid #ccc 1px;
        border-radius: 5px;
        text-align: center;
    }

    .xmk-md-input {
        width: 200px;
        height: 28px;
    }

    .xmk-sdm-input {
        width: 100px;
        height: 28px;
    }

    .xmk-ssm-input {
        width: 42px !important;
        height: 28px;
    }

    .xmk-sm-input {
        width: 50px;
        height: 28px;
    }

    .xmk-lg-input {
        /*width: 490px;*/
        width: 100%;
        height: 28px;
        margin-right: 10px;
    }

    .xmk-left-fix {
        text-align: left;
        padding-left: 10px;
    }

    .xmk-span-btn {
        display: inline-block;
        width: 30px;
        height: 28px;
        font-size: 14px;
        line-height: 28px;
        color: #888;
        cursor: pointer;
        margin-left: 10px;
    }

    .remove-target {
        background-color: #D9534F;
        color: #fff;
        width: 68px;
        height: 26px;
        line-height: 26px;
        border-radius: 5px;
    }

    .remove-target:hover {
        background-color: #C9302C;
    }

    .main-target {
        border-top: solid #ccc 1px;
        padding-top: 10px;
        margin-top: 10px;
    }

    .pop-div {
        background-color: #fff;
        display: none;
        position: fixed;
        top: 20%;
        z-index: 999;
        margin-left: 150px;
        width: 700px;
        border: solid #ccc 1px;
        text-align: center;
        padding-bottom: 50px;
    }

    .pop-div p {
        margin: 80px 0;
        font-size: 20px;
    }

    .pop-div button {
        margin: 0 50px;
    }

    .xmk-pages {
        text-align: center;
    }

    .xmk-pages a {
        text-decoration: none;
        color: #000;
        font-size: 14px;
    }

    .search-target > * {
        margin-right: 60px;
        margin-bottom: 20px;
    }

    .xmk-btn-container {
        text-align: right;
    }

    .xmk-btn-container > * {
        margin-right: 20px;
    }

    .add-target-project {
        margin-top: 28px;
        padding-top: 10px;
        border-top: solid #ccc 3px;
    }

    .add-project-title {
        display: block;
        height: 30px;
    }

    .add-project-title > * {
        height: 30px;
        vertical-align: top;
        margin-bottom: 10px;
    }

    .add-project-title > *:nth-child(0), .add-project-title > *:nth-child(1) {
        margin-right: 20px;
    }

    .add-project-title button {
        float: right;
        margin-left: 20px;
    }

    .fqwp-cancel {
        text-align: center;
    }

    .main-target-index {
        width: 30px;
        height: 26px;
        line-height: 16px;
    }

    .main-target-title {
        text-align: left;
        padding-left: 10px;
        width: 100%;
        margin-right: 10px;
    }

    /*.twoli .inlinethis{
        display: inline-block;
        width: 100%;
    }*/
    .main-target {
        /*border: solid #ccc 1px;*/
        padding: 0;
        /*  border-radius: 5px;*/
    }

    .main-target-score {
        width: 107px;
    }

    .sub-target {
        padding-left: 10px;
    }

    .main-target > div:first-of-type {
        background-color: #f1f1f1;
        padding-left: 10px;
        border-radius: 5px;
    }

    .main-target > div > .xmk-span-btn {
        display: inline-block;
        background-color: #ccc;
        height: 28px;
        width: 80px;
        text-align: center;
        color: #fff;
        line-height: 28px;
        border-radius: 5px;
    }

    .sub-target > input:nth-child(2), .sub-target > input:nth-child(3) {
        width: 50px;
    }

    .form-div {
        /*border: 1px solid #ccc;*/
        /*padding-left: 10px;*/
        /* border-radius: 5px;*/
        /*height: 50px;*/
        /*line-height: 50px;*/
    }

    .table-ul {
        text-align: center;
        display: flex;
        /*justify-content: center;*/
        /*align-items: center;*/
    }

    .table-ul .inlinethis {
        text-align: center;
        display: flex;
        height: 42px;
        /*justify-content: center;*/
        align-items: center;
    }

    .table-ul .oneli {
        width: 110px;
        justify-content: center;
    }

    .table-ul .twoli {
        width: calc(100% - 400px);
        justify-content: center;
    }

    .table-ul .threeli {
        width: 202px;
    }

    .table-ul .fourli {
        width: 90px;
    }

    .text-space {
        padding-right: 15px;
    }

    .letter {
        letter-spacing: 10px;
    }

    .main-target .table-ul .twoli > div {
        /*margin-left: -69px;*/
    }

    .firtarget {
        display: inline-block;
        width: 135px;

    }

    .main-target:last-child {
        margin-bottom: 200px;;
    }

    #addmaintarget {
        background-color: #009966;
    }


    .data_header {
        background-color: #CDE3F1;
        width: 100%;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: left;
        margin-top: 30px;
        margin-bottom: 15px;
    }

    .data_header p {
        font-size: 15px;
        font-weight: 600;
        text-indent: 10px!important;
    }

    .file {
        position: relative;
        display: inline-block;
        background: #D0EEFF;
        border: 1px solid #99D3F5;
        border-radius: 4px;
        padding: 4px 12px;
        overflow: hidden;
        color: #1E88C7;
        text-decoration: none;
        text-indent: 0;
        line-height: 20px;
    }

    .file input {
        position: absolute;
        font-size: 100px;
        right: 0;
        top: 0;
        opacity: 0;
    }

    .file:hover {
        background: #AADFFD;
        border-color: #78C3F3;
        color: #004974;
        text-decoration: none;
    }
</style>

<div class="add-target-page" align="center">
    <div id='form_{rand}' style="width: 100%;">
        <div class="data_header"><p>基础信息</p></div>
        <div class="form-div">
            <ul class="table-ul"
                style="display: flex;flex-direction: row;align-items: center;justify-content: space-between;">
                <li class="inlinethis" style="width:500px;justify-content: center;">
                    <div class="text-space" style="width: 125px;">指标名称：</div>
                    <input class="xmk-input xmk-lg-input" id="norm_name" type="text" placeholder="指标名称"
                           style="text-align: left;padding-left: 10px;"/>
                </li>
                <li class="inlinethis" style="width: 500px;">
                    <div class="text-space">项目类别：</div>
                    <select class="xmk-input xmk-lg-input" id="mtype" style="width: 400px;">
                        <option value="project_start" class="xmk-input">项目初评</option>
                        <option value="project_end" class="xmk-input">结项评审</option>
                    </select>
                </li>
            </ul>
        </div>
        <div class="data_header"><p>指标信息</p></div>

        <!--<form>
            <div style="display: flex;flex-direction: row;justify-content: flex-start; width: 100%"><a href="javascript:;" class="file">导入数据
                    <input type="file" name="import_file" id="import_btn" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                </a></div>
        </form>-->

        <div class="main-target">
            <!--一级指标名称-->
            <div>
                <ul class="table-ul">
                    <li class="oneli inlinethis">
                        <div class="text-space">序号：</div>
                        <input class="xmk-input xmk-ssm-input main-target-index" name="index" type="text" value="1"/>
                    </li>
                    <li class="twoli inlinethis">
                        <div class="text-space firtarget">一级指标名称：</div>
                        <input class="xmk-input xmk-md-input main-target-title" type="text" name="title" value=""
                               placeholder="一级标题"/>
                    </li>
                    <li class="threeli inlinethis">
                        <div class="text-space letter">分数：</div>
                        <input class="xmk-input xmk-sdm-input main-target-score" type="text" name="option" value=""
                               placeholder="该项分数"/>
                    </li>
                    <li class="fourli inlinethis">
                        <span class="xmk-span-btn remove-target">移 除</span>
                    </li>
                </ul>
            </div>
            <!--二级指标内容-->
            <div class="sub-target-container">
                <div class="sub-target">
                    <ul class="table-ul">
                        <li class="oneli inlinethis">
                            <div>二级指标内容：</div>
                        </li>
                        <li class="twoli inlinethis">
                            <input class="xmk-input xmk-lg-input xmk-left-fix sub-title" name="title" type="text"
                                   value="" placeholder="二级标题"/>
                        </li>
                        <li class="threeli inlinethis">
                            <div class="text-space">赋分范围：</div>
                            <input class="xmk-input xmk-sm-input" name="minsorce" type="text" value=""/>
                            <div>~</div>
                            <input class="xmk-input xmk-sm-input" name="maxsorce" type="text" value=""/>
                        </li>
                        <li class="fourli inlinethis">
                            <span class="xmk-span-btn add-item">添加</span><span
                                    class="xmk-span-btn remove-item">移除</span>
                        </li>
                    </ul>
                </div>
                <div class="sub-target">
                    <ul class="table-ul">
                        <li class="oneli inlinethis">
                            <div>二级指标内容：</div>
                        </li>
                        <li class="twoli inlinethis">
                            <input class="xmk-input xmk-lg-input xmk-left-fix sub-title" type="text" name="title"
                                   value="" placeholder="二级标题"/>
                        </li>
                        <li class="threeli inlinethis">
                            <div class="text-space">赋分范围：</div>
                            <input class="xmk-input xmk-sm-input" name="minsorce" type="text" value=""/>
                            <div>~</div>
                            <input class="xmk-input xmk-sm-input" name="maxsorce" type="text" value=""/>
                        </li>
                        <li class="fourli inlinethis">
                            <span class="xmk-span-btn add-item">添加</span><span
                                    class="xmk-span-btn remove-item">移除</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="xmk-flowing-bar">
            <button class="xmk-btn" id="addmaintarget">增加</button>
            <button class="xmk-btn submit_btn" id="submitBtn_{rand}" onclick="confirmSubmit()">提交</button>
            <button class="xmk-btn preview_btn" id="preview" onclick="preview()">预览</button>
            <button class="xmk-btn cancel_btn" id="cancel_btn" onclick="cancel_func()">取消</button>
        </div>
    </div>

</div>
