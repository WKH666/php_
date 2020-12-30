//流程模块【project_skpjm.社科普及月项目申报】下录入页面自定义js页面,初始函数
/**
 * 配置布局
 */

function initbodys(){
    if(isturn==0){
        $('#AltS').before('<input id="DraftS" type="button" style="background:#108EE9;color: white;margin-right: 5px;" onclick="return initshuju()" value="保存为草稿" class="webbtn">');
        $('#AltS').css({
            'background' : "#009966",
            'color' : "white"
        })
        $('#AltS').after('<input id="Reset" type="button" style="background:#ffffff;color: black;margin-left: 5px;" onclick="return initreset()" value="取消" class="webbtn">');
    }
}

/**
 * 保存为草稿
 */
function initshuju() {
    $("input[name='isturn']").remove();
    $("form[name='myform']").append('<input name="isturn" type="hidden" value="0">');
    this.c.save();
}

/**
 * 关闭窗口
 * 返回
 */
function initreset(){
    var reload=sessionStorage.getItem('wcl_reload');
    //console.log(reload);  存储一个变量在未处理和提出申报不同操作
    if (reload==1||reload==2){
        $('#winiframe_spancancel', window.parent.document).click();
    }else{
        parent.window.closenowtabs();
    }
    sessionStorage.removeItem('wcl_reload');
}


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
}