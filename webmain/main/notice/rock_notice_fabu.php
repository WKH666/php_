<?php if(!defined('HOST'))die('not access');?>

<style>
    .enterprise_info_head {
        background-color: #B0DAF8;
        height: 35px;
        border-radius: 5px;
        margin-bottom: 30px;
        line-height: 35px;
        margin-top: 20px;
    }
    .enterprise_info_head p {
        margin: 0px;
        font-weight: unset;
    }
</style>
<form id="add-form" class="form-horizontal nice-validator n-default n-bootstrap" role="form" data-toggle="validator" method="POST" action="" novalidate="novalidate">
    <!--<input type="button" id="back_btn" value="返回" class="btn btn-default" style="margin-top: 30px;">-->
    <input type="hidden" id="type_" name="type" value="">
    <div class="col-sm-12 enterprise_info_head">
        <p>基础信息</p>
    </div>

    <div class="enterprise_info col-sm-12">
        <div class="form-group col-sm-6">
            <label class="control-label col-xs-12 col-sm-4">发送标题:</label>
            <div class="col-xs-12 col-sm-8">
                <input data-rule="required" id="title_" class="form-control  gray_color" name="title" type="text" value="" placeholder="请输入">
            </div>
        </div>
    </div>
    <div class="enterprise_info col-sm-12">
        <div class="form-group col-sm-6">
            <label class="control-label col-xs-12 col-sm-4">发送说明:</label>
            <div class="col-xs-12 col-sm-8">
                <textarea class="form-control" id="remark_" name="remark" rows="3" placeholder="请输入"></textarea>
            </div>
        </div>
    </div>
    <div class="enterprise_info col-sm-12">
        <div class="form-group col-sm-6">
            <label class="control-label col-xs-12 col-sm-4">同步邮件:</label>
            <div class="col-xs-12 col-sm-8">
                <label class="radio-inline">
                    <input type="radio" name="is_mail" id="open"  value="1"> 是
                </label>
                <label class="radio-inline">
                    <input type="radio" name="is_mail"  value="0" id="close" checked> 否
                </label>
            </div>
        </div>
    </div>

    <div class="col-sm-12 enterprise_info_head">
        <p>发送信息</p>
    </div>
    <div class="enterprise_info col-sm-12">
        <div class="form-group col-sm-6">
            <label class="control-label col-xs-12 col-sm-4">发送人:</label>
            <div class="col-xs-12 col-sm-8">
                <div style="display: flex;align-items: center;" class="post_files">
                    <input id="post_files_" name="post_files" style="float:left;margin-top: 3px;width:150px" type="file">
                    <a target="_blank" href="./export_templates/beselected_import.xls">模板下载</a>
                </div>
                <div class="uploadLimited" style="margin-top: 20px;margin-bottom: 10px" id="sendfile_name"></div>
                <div class="uploadLimited" style="margin-top: 5px;">格式要求：支持xls、xlsx。</div>
            </div>
        </div>
    </div>

    <div class="enterprise_info col-sm-12">
        <div class="form-group col-sm-6">
            <label class="control-label col-xs-12 col-sm-4">流程附件:</label>
            <div class="col-xs-12 col-sm-8">
                <div class="flow_files">
                    <input id="files_" name="files_[]" style="margin-top: 3px;width:150px" type="file" multiple="multiple">
                </div>
                <div class="uploadLimited" style="margin-top: 20px;margin-bottom: 10px" id="flowfile_name"></div>
                <div class="uploadLimited" style="margin-top: 5px;">格式要求：支持doc、 docx。</div>
            </div>
        </div>
    </div>
    <button type="button" class="btn btn-primary" onclick="save_draft()" style="background: #108ee9;">保存草稿</button>
    <button type="button" class="btn btn-primary" onclick="submit_()" style="background: #009966;">提交</button>
    <button type="button" class="btn btn-default" onclick="closenowtabs()">取消</button>
    <input type="text" value="0" hidden="true" id="notice_id" name="notice_id">
</form>
<script>
    var sendfile_status = 0;
    var flowfile__status = 0;

    $(function () {
        {params}
        var type = params.type;
        $('#type_').val(type);
        var type_num = new Array();
        type_num[1]="kt_lx";
        type_num[2]="kt_jx";
        type_num[3]="pjy_rx";
        type_num[4]="cth_rx";
        type_num[5]="yjjd_lx";
        type_num[6]="kt_bzyq";
        type_num[7]="hqrd_jx";
        if (params.notice_id){
            $('#notice_id').val(params.notice_id);
            $.ajax({
                url: getRootPath() + "/?d=main&m=notice&a=notice_draft&ajaxbool=true&notice_id="+params.notice_id, /*接口域名地址*/
                type:'post',
                data:'',
                dataType:'json',
                processData: false,
                contentType: false,
                success:function (res) {
                    if (res.code == 200){
                        $('#title_').val(res.data.title);
                        $('#remark_').val(res.data.remark);
                        $('#type_').val(type_num[res.data.type]);
                        if (res.data.is_mail == 0){
                            $("#close").attr("checked","checked");
                        }else if (res.data.is_mail == 1){
                            $("#open").attr("checked","checked");
                        }
                        if (res.data.send_files){
                            $('#sendfile_name').text(res.data.send_files);
                            sendfile_status=1;
                        }
                        if (res.data.flow_files){
                            $('#flowfile_name').text(res.data.flow_files);
                            flowfile__status=1;
                        }

                    }
                }
            })

        }
    });

    function submit_() {
        var formData = new FormData($("#add-form")[0]);  //重点：要用这种方法接收表单的参数
        $.ajax({
            url: getRootPath() + "/?d=main&m=notice&a=fabu&ajaxbool=true&is_draft=1&sendfile_status="+sendfile_status+'&flowfile__status='+flowfile__status,
            type:'post',
            data: formData,
            dataType: 'json',
            processData: false,
            contentType: false,
            success:function(res){
                if(res.code==200){
                    layer.msg('发布成功');
                    closenowtabs();
                    thechangetabs('notice');
                    try {
                        assessmentList.reload();
                    } catch (e) {
                    }
                }else{
                    layer.msg(res.msg);
                }
            }
        })
    }

    //保存草稿
    function save_draft() {
        var formData = new FormData($("#add-form")[0]);
        $.ajax({
            url: getRootPath() + "/?d=main&m=notice&a=fabu&ajaxbool=true&is_draft=0&sendfile_status="+sendfile_status+'&flowfile__status='+flowfile__status,
            type:'post',
            data: formData,
            dataType: 'json',
            processData: false,
            contentType: false,
            success:function(res){
                if(res.code==200){
                    layer.msg('保存草稿成功');
                    closenowtabs();
                    thechangetabs('notice');
                    try {
                        assessmentList.reload();
                    } catch (e) {
                    }
                }else{
                    layer.msg('保存草稿失败');
                }
            }
        })
    }

    //重写tabs改变事件
    function thechangetabs(num){
        $("div[temp='content']").hide();
        $("[temp='tabs']").removeClass();
        var bo = false;
        if(get('content_'+num+'')){
            $('#content_'+num+'').show();
            $('#tabs_'+num+'').addClass('accive');
            nowtabs = tabsarr[num];
        }
        opentabs.push(num);
        _changhhhsv(num);
    }

    $(".post_files").on("change","#post_files_",function(){
        var file = $(this).val();
        var fileName = getFileName(file);
        $('#sendfile_name').text(fileName);
        sendfile_status = 0;
    });

    $(".flow_files").on("change","#files_",function(){
        var file = $(this).val();
        var fileName = getFileName(file);
        $('#flowfile_name').text(fileName);
        sendfile_status = 0
    });


    function getFileName(o){
        var pos=o.lastIndexOf("\\");
        return o.substring(pos+1);
    }
</script>
