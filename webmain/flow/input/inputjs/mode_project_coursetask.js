//流程模块【project_coursetask.社科课题申报】下录入页面自定义js页面,初始函数
/**
 * 配置布局
 */
function initbodys(){
    if(isturn==0){
        $('#AltS').before('<input id="DraftS" type="button" style="background:#108EE9;color: white;margin-right: 5px;" onclick="return initshuju()" value="保存为草稿" class="webbtn">');
        $('#AltS').css({
            'background' : "#009966",
            'color' : "white"
        });
        $('#AltS').after('<input id="Reset" type="button" style="background:#ffffff;color: black;margin-left: 5px;" onclick="return initreset()" value="取消" class="webbtn">');
    }
}

/**
 * 保存为草稿
 */
function initshuju() {
    //判断需要用户提交的文件是否都已提交
    var file1 = $('#ktfile5').val();
    var file2 = $('#ktfile6').val();
    if (file1 && file2){
        $("input[name='isturn']").remove();
        $("form[name='myform']").append('<input name="isturn" type="hidden" value="0">');
        this.c.save();
    }
    if (!file1){
        layer.msg('请提交申报书!');
    }else if (!file2){
        layer.msg('请提交课题设计论证(活页)!');
    }

}

/**
 * 关闭窗口
 * 返回   存储一个变量在未处理和提出申报不同操作
 */
function initreset(){
    var reload=sessionStorage.getItem('wcl_reload');
    //console.log(reload);
    if (reload==1||reload==2){
        $('#winiframe_spancancel', window.parent.document).click();
    }else{
        parent.window.closenowtabs();
    }
    sessionStorage.removeItem('wcl_reload');
}

/**
 * 隐藏流程
 */
function process_hidden(){
    $("textarea").css('width','98%');
}
process_hidden();

/**
 * 提示信息输入错误或空白
 */
var checkInit = {
    checkPhone: function(p) {
        var obj = /^1[34578]\d{9}$/;
        var results = obj.test(p);
        if(results) {
            return true;
        }
        return false;
    },
    checkEmail: function(p) {
        var obj = /^\w+@(\w+)\.com(\.cn)?$/i;
        var results = obj.test(p);
        return results;
    },
    checkTextareaLength: function(t) {
        if(t >= 801) {
            return false;
        }
    },
    init: function(n, k) {
        k = k || {};
        switch(n) {
            case "phone":
                return this.checkPhone(k);
                break;
            case "email":
                return this.checkEmail(k);
                break;
            case "textArea":
                return this.checkTextareaLength(k);
                break;
        }
    },
};

/* *
* 申报书草稿中的文件回显
*/
//mid.modenums都是从tpl_input_lu拿的;

function caogaoFile() {
    var project_id = mid;
    var modenume = modenums;
//附件数据对象
    var uploadFileArr = {'1': '', '2': '', '3': '', '4': '', '5': '', '6': '','7':''};
    if (project_id!=0){
        $.ajax({
            url: getRootPath() + "/?d=main&m=project_comment&a=UploadFilesData&ajaxbool=true",
            data: {mid: project_id, mode_num: modenume},
            type: 'post',
            dataType: 'json',
            success: function (ds) {
                if (ds.success) {
                    for (var i = 0; i < ds.data.length; i++) {
                        var fileType = ds.data[i]['upload_filetype'];
                        var fileName = ds.data[i]['filename'];
                        var fileid = ds.data[i]['id'];
                        if (fileType == '申报书') {
                            uploadFileArr['5'] = ds.data[i];
                            $('#ktfile5').val(fileName);
                        } else if (fileType == '课题设计论证(活页)') {
                            uploadFileArr['6'] = ds.data[i];
                            $('#ktfile6').val(fileName);
                        }
                    }
                    //检测是否上传
                    checkUploadStatus('ktfile5');
                    checkUploadStatus('ktfile6');
                    sessionStorage.setItem('uploadFileData', JSON.stringify(uploadFileArr));
                }
            }
        });
    }
}
caogaoFile();








