<?php 
//项目库cas单点登录
$cas = true;//是否开启cas

if ($cas) {
	require_once dirname(__FILE__)."/include/CAS/CAS.php";
	//指定log文件
	phpCAS::setDebug('./caslog.log');
	//指定cas地址,第一个为cas版本，一般为CAS_VERSION_2_0 第二个参数为hostname，第三个为ids的端口，第四个是ids的上下文，第五个是是否是https。
	phpCAS::client(CAS_VERSION_2_0,'ids.gdit.edu.cn',80,'authserver',false);
	//sso退出时，cas会请post应用带上logoutRequest参数，请求应用地址。
	//设置no ssl，即忽略证书检查。如果需要ssl，请用 phpCAS::setCasServerCACert()设置ssl证书。
	phpCAS::setNoCasServerValidation();
	//phpCAS::handleLogoutRequests()可以响应sso退出请求，注销当前用户认证凭据。
	phpCAS::handleLogoutRequests();
	phpCAS::forceAuthentication();
	
	//用户信息可由：
	$_SESSION['user'] = phpCAS::getUser();//取用户
	//$_SESSION['user_attribute'] = phpCAS::getAttributes();//取用户属性，返回数组，存在多值两个方法获取
} else {
	$_SESSION['user'] = '';//输入用户登录信息
}