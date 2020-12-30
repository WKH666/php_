$(document).ready(function(){

	/**
	 * 忘记账号(用户登录->账号验证)
	 */
	$("#forid").on('click',function(){
		$("#form1").hide();
		$("#form4").show();
	});
	/**
	 * 注册账号(用户登录->用户注册)
	 */
	$("#regid").on('click',function(){
		$("#form1").hide();
		$("#form2").show();
	});


    $("#iform2").on('click',function(){
        location.reload();
    });


    $("#iform4").on('click',function(){
        location.reload();
    });


    /**
     * 用户登录->登录
     */
    $("#lsub").on('click',function(){
        if(!$("#adminuser").val()){
            layer.tips('用户名请填写', '#adminuser', {
                tips: [2, '#0FA6D8'] //还可配置颜色
            });
        }else if(!$("#adminpass").val()){
            layer.tips('密码请填写', '#adminpass', {
                tips: [2, '#0FA6D8'] //还可配置颜色
            });
        }else if($("#adminuser").val() && $("#adminpass").val()){
            let adminuser = $("#adminuser").val();
            let adminpass = $("#adminpass").val();
            var device= js.cookie('deviceid');
            if(device=='')device=js.now('time');
            js.savecookie('deviceid', device, 365);
            js.bool		= true;
            js.ajax(js.getajaxurl('check','query_login'),{device:device,adminuser:jm.base64encode(adminuser),adminpass:jm.base64encode(adminpass)},function(res){
                if(res.success){
                    layer.msg('登录成功,跳转中..');
                    setTimeout(function(){
                        location.href='?m=query_index';
                    },1000);
                    // $(".lmaisft").hide();
                    // $("#tipps").show();
                }else{
                    layer.msg(res.msg);
                }
            },'post,json');
        }
    });
    /**
     * 用户注册->提交注册
     */
    $("#esub").on('click',function(){
        let getivscode = window.sessionStorage.getItem('irvscode');
        if($("#ecode").val() && $("#pwd").val() && $("#rpwd").val()){
            if($("#ecode")[0].value == getivscode){
                if($("#pwd")[0].value != $("#rpwd")[0].value){
                    layer.tips('两次密码不相同请重新填写', '#rpwd', {
                        tips: [2, '#d84545'] //还可配置颜色
                    });
                }
                else{
                    $("#form2").hide();
                    $("#form3").show();
                }
            }else{
                layer.tips('邮箱验证码输入错误', '#ecode', {
                    tips: [2, '#d84545'] //还可配置颜色
                });
            }
        }else if(!$("#ecode").val()){
            layer.tips('邮箱验证码请填写', '#ecode', {
                tips: [2, '#0FA6D8'] //还可配置颜色
            });
        }else if(!$("#pwd").val()){
            layer.tips('密码请填写', '#pwd', {
                tips: [2, '#0FA6D8'] //还可配置颜色
            });
        }else if(!$("#rpwd").val()){
            layer.tips('密码请填写', '#rpwd', {
                tips: [2, '#0FA6D8'] //还可配置颜色
            });
        }
    });
	/**
	 * 用户注册->发送验证码
	 */
	$("#vcode").on('click',function(){
        let irtimeup = 60;
		let iremail = $("#email").val();
		if(iremail){
            let irvscode = '';
            for(var i=0;i<6;i++){
                irvscode += Math.floor(Math.random()*10);
            }
            js.ajax(js.getajaxurl('send_email','query_login'),{remail:iremail,vscode:irvscode},function(res){
                window.sessionStorage.removeItem('irvscode');
                window.sessionStorage.setItem('irvscode',irvscode);
                layer.msg(res.msg);
                var timer = setInterval(function () {
                    irtimeup--;
                    $("#vcode").val(irtimeup + "s");
                    $('#vcode').attr("disabled",true);
                    if(irtimeup == 0){
                        irtimeup = 60;
                        clearInterval(timer);
                        $("#vcode").val('发送验证码');
                        $("#vcode").removeAttr('disabled');
                    }
                },1000);
            },'post,json');
        }else{
            layer.tips('未填写邮箱', '#email', {
                tips: [2, '#d84545'] //还可配置颜色
            });
        }

	});
    /**
     * 忘记密码->发送验证码
     */
	$("#ivcode").on('click',function(){
        let itimeup = 60;
        let ifemail = $("#iemail").val();
        if(ifemail){
            let ifvscode = '';
            for(var i=0;i<6;i++){
                ifvscode += Math.floor(Math.random()*10);
            }
            js.ajax(js.getajaxurl('send_email','query_login'),{remail:ifemail,vscode:ifvscode},function(res){
                window.sessionStorage.removeItem('ifvscode');
                window.sessionStorage.setItem('ifvscode',ifvscode);
                layer.msg(res.msg);
                var timer = setInterval(function () {
                    itimeup--;
                    $("#ivcode").val(itimeup + "s");
                    $('#ivcode').attr("disabled",true);
                    if(itimeup == 0){
                        itimeup = 60;
                        clearInterval(timer);
                        $("#ivcode").val('发送验证码');
                        $("#ivcode").removeAttr('disabled');
                    }
                },1000);
            },'post,json');
        }else{
            layer.tips('未填写邮箱', '#iemail', {
                tips: [2, '#d84545'] //还可配置颜色
            });
        }
    });
	/**
	 * 完善资料->提交资料
	 */
	$("#psub").on('click',function(){
		if(!$("#realname").val()){
            layer.tips('真实姓名请填写', '#realname', {
                tips: [2, '#0FA6D8'] //还可配置颜色
            });
		}else if(!$("#mphone").val()){
            layer.tips('手机号码请填写', '#mphone', {
                tips: [2, '#0FA6D8'] //还可配置颜色
            });
        }else if(!$("#research").val()){
            layer.tips('研究方向请填写', '#research', {
                tips: [2, '#0FA6D8'] //还可配置颜色
            });
        }else{
            let deptIndex = $("#unit")[0].selectedIndex;
            let subjectIndex = $("#subject_sort")[0].selectedIndex;
            let uload_d = {
                'email' : $("#email").val(),
                'pass' : $("#pwd").val(),
                'deptid': $("#unit")[0][deptIndex].id.substr(5),
                'deptname' : $("#unit").val(),
                'deptallname' : $("#unit").val(),
                'ranking' : $("#workname").val(),
                'user' : $("#username").val(),
                'name' : $("#realname").val(),
                'mobile' : $("#mphone").val(),
                'subjectid' : $("#subject_sort")[0][subjectIndex].id.substr(8),
                'head_subject' : $("#subject_sort").val(),
                'res_dir' : $("#research").val()
            };
            if(uload_d.deptname == '选择关联单位'){
                uload_d.deptname = '';
            }
            if(uload_d.head_subject == '选择负责学科'){
                uload_d.head_subject = '';
            }
            console.log(uload_d);
            js.ajax(js.getajaxurl('uloaddata','query_login'),uload_d,function(data){
                if(data.code == 1){
                    layer.msg(data.msg, {time: 2000, icon:1});
                    setTimeout(function(){
                        location.reload();
                    },1000);
                }else{
                    layer.msg(data.msg, {time: 2000, icon:2});
                }
            },'post,json');
		}
	});
    /**
     * 账号验证->提交验证
     */
    $("#isub").on('click',function(){
        if(!$("#iecode").val()){
            layer.tips('邮箱验证码请填写', '#iecode', {
                tips: [2, '#0FA6D8'] //还可配置颜色
            });
        }else{
            let session_ivcode = window.sessionStorage.getItem('ifvscode');
            if($("#iecode")[0].value == session_ivcode){
                let forget_email = $("#iemail").val();
                window.sessionStorage.removeItem('fg_email');
                window.sessionStorage.setItem('fg_email',forget_email);
                $("#form4").hide();
                $("#form5").show();
                let fg_email = window.sessionStorage.getItem('fg_email');
                $("#set_email").val(fg_email);
            }
        }
    });
    /**
     * 设置密码->提交修改
     */
    $("#pwsub").on('click',function(){
        if(!$("#setpwd").val()){
            layer.tips('密码请填写', '#setpwd', {
                tips: [2, '#0FA6D8'] //还可配置颜色
            });
        }else if(!$("#setrpwd").val()){
            layer.tips('密码请填写', '#setrpwd', {
                tips: [2, '#0FA6D8'] //还可配置颜色
            });
        }else if($("#setpwd")[0].value != $("#setrpwd")[0].value){
            layer.tips('两次密码不相同请重新填写', '#setrpwd', {
                tips: [2, '#d84545'] //还可配置颜色
            });
        }else{
            let setmail = $("#set_email").val();
            let setpwd = $("#setpwd").val();
            js.ajax(js.getajaxurl('loadexit','query_login'),{setmail:setmail,setpwd:setpwd},function(res){
                if(res.TotalCount != 1){
                    layer.msg(res.msg, {time: 2000, icon:2});
                }else if(res.TotalCount == 1){
                    layer.msg(res.msg, {time: 2000, icon:1});
                    setTimeout(function(){
                        location.reload();
                    },1000);
                }
            },'post,json');
        }
    });

    /**
     * 手机号码正则验证
     */
	$("#mphone").on('blur',function(){
	    let $mphone = $(this).val();
	    let regular = /^1[3|4|5|7|8]\d{9}$/;
	    if($mphone.length > 0){
            if($mphone.match(regular)){}
            else{
                layer.tips('此手机号不符合手机号编码规则', '#mphone', {
                    tips: [2, '#d84545'] //还可配置颜色
                });
            }
        }
    });
    /**
     * 密码可见
     */
    $("#pwd").on('input',function(){
        let $pwd_val = $(this).val();
        if($pwd_val.length > 0){
            $(".pwd_input i").css('display','block');
        }else{
            $(".pwd_input i").css('display','none');
        }
    });
    $("#pwd").on('focus',function(){
        $(".rpwd_input i").css('display','none');
    });

    $("#rpwd").on('input',function(){
        let $rpwd_val = $(this).val();
        if($rpwd_val.length > 0){
            $(".rpwd_input i").css('display','block');
        }else{
            $(".rpwd_input i").css('display','none');
        }
    });
    $("#rpwd").on('focus',function(){
        $(".pwd_input i").css('display','none');
    });


    /**
     * 密码可见
     */
    $("#setpwd").on('input',function(){
        let $setpwd_val = $(this).val();
        if($setpwd_val.length > 0){
            $(".set_pwdinput i").css('display','block');
        }else{
            $(".set_pwdinput i").css('display','none');
        }
    });
    $("#setpwd").on('focus',function(){
        $(".set_rpwdinput i").css('display','none');
    });


    $("#setrpwd").on('input',function(){
        let $setrpwd_val = $(this).val();
        if($setrpwd_val.length > 0){
            $(".set_rpwdinput i").css('display','block');
        }else{
            $(".set_rpwdinput i").css('display','none');
        }
    });
    $("#setrpwd").on('focus',function(){
        $(".set_pwdinput i").css('display','none');
    });


    /**
     * 改变密码输入框类型
     */
    $(".pwd_input i").on('click',function(){
        if($(".pwd_input input").attr('type') == 'password'){
            $(".pwd_input i").attr('class','glyphicon glyphicon-eye-open');
            $(".pwd_input input").attr('type','text');
        }else if($(".pwd_input input").attr('type') == 'text'){
            $(".pwd_input i").attr('class','glyphicon glyphicon-eye-close');
            $(".pwd_input input").attr('type','password');
        }
    });

    $(".rpwd_input i").on('click',function(){
        if($(".rpwd_input input").attr('type') == 'password'){
            $(".rpwd_input i").attr('class','glyphicon glyphicon-eye-open');
            $(".rpwd_input input").attr('type','text');
        }else if($(".rpwd_input input").attr('type') == 'text'){
            $(".rpwd_input i").attr('class','glyphicon glyphicon-eye-close');
            $(".rpwd_input input").attr('type','password');
        }
    });

    /**
     * 改变密码输入框类型
     */
    $(".set_pwdinput i").on('click',function(){
        if($(".set_pwdinput input").attr('type') == 'password'){
            $(".set_pwdinput i").attr('class','glyphicon glyphicon-eye-open');
            $(".set_pwdinput input").attr('type','text');
        }else if($(".set_pwdinput input").attr('type') == 'text'){
            $(".set_pwdinput i").attr('class','glyphicon glyphicon-eye-close');
            $(".set_pwdinput input").attr('type','password');
        }
    });

    $(".set_rpwdinput i").on('click',function(){
        if($(".set_rpwdinput input").attr('type') == 'password'){
            $(".set_rpwdinput i").attr('class','glyphicon glyphicon-eye-open');
            $(".set_rpwdinput input").attr('type','text');
        }else if($(".set_rpwdinput input").attr('type') == 'text'){
            $(".set_rpwdinput i").attr('class','glyphicon glyphicon-eye-close');
            $(".set_rpwdinput input").attr('type','password');
        }
    });

    /**
     * 注册邮箱正则
     */
    $("#email").on('blur',function(){
        let $remail = $(this).val();
        let regulr_email = /^\w[-\w.+]*@([A-Za-z0-9][-A-Za-z0-9]+\.)+[A-Za-z]{2,14}$/;
        if($remail.length > 0){
            if($remail.match(regulr_email)){
                // console.log(regulr_email.test($remail));
                // console.log($remail.match(regulr_email));
            }else{
                layer.tips('填写正确的邮箱地址', '#email', {
                    tips: [2, '#d84545'] //还可配置颜色
                });
            }
        }
    });

    /**
     * 验证邮箱正则
     */
    $("#iemail").on('blur',function(){
        let $iemail = $(this).val();
        let regulr_iemail = /^[A-Za-z\d]+([-_.][A-Za-z\d]+)*@([A-Za-z\d]+[-.])+[A-Za-z\d]{2,4}$/ ;
        if($iemail.length > 0){
            if($iemail.match(regulr_iemail)){
            }else{
                layer.tips('填写正确的邮箱地址', '#iemail', {
                    tips: [2, '#d84545'] //还可配置颜色
                });
            }
        }
    });

    /**
     * 申请预览
     */
    $("#apply").on('click',function(){
        layer.msg('提交成功<br/>预览申请已提交,1-3工作审核通过后即可登录', {time: 2000, icon:1});
        setTimeout(function(){
            location.reload();
        },1000);
    });

});