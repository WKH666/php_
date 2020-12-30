<?php 
/**
*	api的入口地址请求访问，访问方法：http://我的域名/api.php?m=index&a=方法
*	主页：http://www.minephone.com/
*	软件：广州迈峰网络科技有限公司
*	作者:root
*/
define('ENTRANCE', 'task');
include_once('config/config.php');
$d			= $rock->get('d','task');
$m			= $rock->get('m','mode');
include_once('include/View.php');