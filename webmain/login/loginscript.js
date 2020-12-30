var oldpass='',initlogo='images/logo.png',olduser;
//绑定回车键事件
document.onkeydown = function(e){
    if(!e) e = window.event;
    if((e.keyCode || e.which) == 13) loginsubmit();
}
$(function(){
	//验证码显示
	$('#captcha_img').prop('src', js.getajaxurl('verifycode','login'));
	$('#captcha_img').click(function(){
		$('#captcha_img').prop('src', js.getajaxurl('verifycode','login'));
	});
});
function initbody(){
	
	form('adminuser').focus();
	oldpass	= form('adminpass').value;
	olduser	= form('adminuser').value;
	if(form('adminuser').value!=''){
		form('adminpass').focus();
	}
	
	resizewh();
	$(window).resize(resizewh);
	//var sf = js.getoption('loginface');
	//if(sf)get('imglogo').src=sf;
	$(form('adminuser')).change(function(){
		changeuserface(this.value);
	});
	yunanimate();
}
function yunanimate(){
	var whe=winWb();
	//$('#yun1').animate({'left':''+(whe)+'px'},10000);
	//$('#yun2').animate({'left':''+(whe)+'px'},20000);
}
function resizewh(){
	var h = ($(document).height()-510)*0.5;
	$('#topheih').css('height',''+h+'px');
}
function changeuserface(v){
	var sf = js.getoption('loginface');
	if(!sf)return;
	if(v==''||v!=olduser){
		get('imglogo').src=initlogo;
	}else{
		get('imglogo').src=sf;
	}
}
function loginsubmit(){
	if(js.bool)return false;
	var user = form('adminuser').value;
	var pass = form('adminpass').value;
	var device= js.cookie('deviceid');
	var verifycode = form('verifycode').value;
	if(device=='')device=js.now('time');
	js.savecookie('deviceid', device, 365);
	if(user==''){
		js.setmsg('帐号不能为空','red');
		form('adminuser').focus();
		$('#captcha_img').attr('src', js.getajaxurl('verifycode','login'));//重新加载验证码
		return false;
	}
	if(pass==''){
		js.setmsg('密码不能为空','red');
		form('adminpass').focus();
		$('#captcha_img').attr('src', js.getajaxurl('verifycode','login'));//重新加载验证码
		return false;
	}
	if(verifycode==''){
		js.setmsg('验证码不能为空','red');
		form('verifycode').focus();
		$('#captcha_img').attr('src', js.getajaxurl('verifycode','login'));//重新加载验证码
		return false;
	}
	js.setmsg('登录中...');
	form('button').disabled=true;
	var data	= js.getformdata();
	var url		= js.getajaxurl('check','login');
	
	data.jmpass	= 'false';
	data.device = device;
	data.verifycode = verifycode;
	data.adminuser = jm.base64encode(user);
	data.adminpass = jm.base64encode(pass);
	if(oldpass==pass)data.jmpass= 'true';
	js.bool		= true;
	js.ajax(url,data,function(a){
		if(a.success){
			get('imglogo').src=a.face;
			js.setoption('loginface', a.face);
			js.setoption('uploadmaxsize',a.maxsize);
			var ltype=js.request('ltype');
			if(ltype=='1' && history.length>1){
				history.back();
			}else{
				js.setmsg('登录成功,跳转中..','#419af1');
				location.href='?m=index';
			}
		}else{
			js.setmsg(a.msg,'red');
			form('button').disabled=false;
			js.bool	= false;
			$('#captcha_img').attr('src', js.getajaxurl('verifycode','login'));//重新加载验证码
		}
	},'post,json');
}


//发送邮箱验证码
function emailcode(){
	var email = document.getElementById('email').value;
	var email_code = "";
	$.ajax({
		url: '?m=login&a=email_code&ajaxbool=true',
		data : {"email":email},
		dataType: 'json',
		type:"post",
		success: function(data) {
			//console.log(data);
			email_code = data.code;
			send_code_time();
		},
		error: function(data) {
			alert('邮件发送失败');
		}
	})
}
//提交注册账号
function register(){
	var email = document.getElementById('email').value;
	var email_code = document.getElementById('email_code').value;
	var password = document.getElementById('password').value;
	var password_queren = document.getElementById('password_queren').value;

	if(email==''){
		alert('邮箱不能为空');
		form('email').focus();
		return false;
	}
	if(email_code==''){
		alert('验证码不能为空');
		form('email_code').focus();
		return false;
	}
	if(password==''){
		alert('密码不能为空');
		form('password').focus();
		return false;
	}
	if(password_queren==''){
		alert('密码不能为空');
		form('password_queren').focus();
		return false;
	}

	if(password === password_queren){
		$.ajax({
			url: '?m=login&a=register_add&ajaxbool=true',
			data : {"email":email,"password_queren":password_queren,"email_code":email_code},
			dataType: 'json',
			type:"post",
			success: function(data) {
				if(data.code == 1){
					alert(data.result);
					location.href='?m=login&a=improve_information&email='+email;
				}else if(data.code == 0 ){
					alert(data.result);
					return false;
				}
			},
			error: function(data) {
				alert('注册失败a');
				return false;
			}
		})
	}else{
		alert("密码不一致");
		return false;
	}
}
//完善账号资料
function improve_information(){
	var school_name = document.getElementById('school_name').value;
	var ranking = document.getElementById('ranking').value;
	var name = document.getElementById('name').value;
	var mobile = document.getElementById('mobile').value;
	var graduate_project = document.getElementById('graduate_project').value;
	var research_direction = document.getElementById('research_direction').value;
	var email = GetQueryString("email");
	if(name==''){
		alert('姓名不能为空');
		form('name').focus();
		return false;
	}
	if(mobile==''){
		alert('手机号码不能为空');
		form('mobile').focus();
		return false;
	}
	if(graduate_project==''){
		alert('毕业学科不能为空');
		form('graduate_project').focus();
		return false;
	}
	if(research_direction==''){
		alert('研究方向不能为空');
		form('research_direction').focus();
		return false;
	}
	$.ajax({
		url: '?m=login&a=improve_information_insert&ajaxbool=true',
		data : {"school_name":school_name,"ranking":ranking,"name":name,"mobile":mobile,"graduate_project":graduate_project,"research_direction":research_direction,"email":email},
		dataType: 'json',
		type:"post",
		success: function(data) {
			if(data.code == 1){
				alert(data.result);
				location.href='?m=login';
			}else if(data.code == 0 ){
				alert(data.result);
				return false;
			}
		},
		error: function(data) {
			alert('注册失败');
			return false;
		}
	})

}
//忘记密码-提交验证
function forget_password(){
	var email = document.getElementById('email').value;
	var email_code = document.getElementById('email_code').value;
	if(email==''){
		alert('邮箱不能为空');
		form('email').focus();
		return false;
	}
	if(email_code==''){
		alert('验证码不能为空');
		form('email_code').focus();
		return false;
	}
	$.ajax({
		url: '?m=login&a=forget_password_inspect&ajaxbool=true',
		data : {"email":email,"email_code":email_code},
		dataType: 'json',
		type:"post",
		success: function(data) {
			if(data.code == 1){
				alert(data.result);
				location.href='?m=login&a=set_password&email='+email;
			}else if(data.code == 0 ){
				alert(data.result);
				return false;
			}
		},
		error: function(data) {
			alert('注册失败');
			return false;
		}
	})

}
//设置密码
function set_password(){
	var password = document.getElementById('password').value;
	var password_queren = document.getElementById('password_queren').value;
	var email = GetQueryString("email");
	if(password==''){
		alert('密码不能为空');
		form('password').focus();
		return false;
	}
	if(password_queren==''){
		alert('密码不能为空');
		form('password_queren').focus();
		return false;
	}
	if(password === password_queren){
		$.ajax({
			url: '?m=login&a=set_password_insert&ajaxbool=true',
			data : {"email":email,"password_queren":password_queren},
			dataType: 'json',
			type:"post",
			success: function(data) {
				if(data.code == 1){
					alert(data.result);
					location.href='?m=login';
				}else if(data.code == 0 ){
					alert(data.result);
					return false;
				}
			},
			error: function(data) {
				alert('修改失败');
				return false;
			}
		})
	}else{
		alert("密码不一致");
		return false;
	}
}
//获取地址栏参数
function GetQueryString(name)
{
	var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
	var r = window.location.search.substr(1).match(reg);
	if(r!=null)return  unescape(r[2]); return null;
}
//点击验证码倒计时
var wait = 60;
function send_code_time(){
	if (wait == 0) {
		document.getElementById("send_code").removeAttribute("disabled");
		document.getElementById("send_code").innerHTML = "发送验证码";
		wait = 60;
	} else {
		document.getElementById("send_code").setAttribute("disabled", true);
		document.getElementById("send_code").innerHTML = wait + "秒后可发送";
		wait--;
		setTimeout(function() {
			send_code_time();
		}, 1000)
	}
}


