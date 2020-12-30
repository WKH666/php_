<?php
/**
*	要修改配置文件在：webmain/webmainConfig.php
*	true调试模式，false上线模式
*/
@session_start();
if(!ini_get('date.timezone') )date_default_timezone_set('Asia/Shanghai');
header('Content-Type:text/html;charset=utf-8');
define('ROOT_PATH',str_replace('\\','/',dirname(dirname(__FILE__))));
define('DEBUG', true);
include_once(''.ROOT_PATH.'/include/rockFun.php');
include_once(''.ROOT_PATH.'/include/Chajian.php');
include_once(''.ROOT_PATH.'/include/class/rockClass.php');
$rock 		= new rockClass();
$db			= null;
$smarty		= false;
define('HOST', $rock->host);
define('REWRITE', 'true');
if(!defined('PROJECT'))define('PROJECT', $rock->get('p', 'webmain'));
if(!defined('ENTRANCE'))define('ENTRANCE', 'index');
error_reporting(DEBUG ? E_ALL : 0);
$config		= array(
	'title'		=> '项目库',
    'url'		=> 'http://'.HOST.'/sheke/',
    'urly'		=> '',
   //测试站
   /* 'db_host'	=> '112.124.118.133',
    'db_user'	=> 'sheke',
    'db_pass'	=> 'sheke!@#',
    'db_base'	=> 'zh_sheke_dev_db',*/

    'db_host'	=> 'localhost',
    'db_user'	=> 'root',
    'db_pass'	=> 'root',
    'db_base'	=> 'sheke_db',
   //正式站
   /* 'urly'		=> 'http://keti.zhsk.gov.cn/',
    'db_host'	=> 'localhost',
    'db_user'	=> 'sheke',
    'db_pass'	=> '&sheke!%(10',
    'db_base'	=> 'zh_sheke_db',*/

    'perfix'	=> 'xinhu_',
    'qom'		=> 'xinhu_',
	'highpass'	=> '',
	'install'	=> false,
	'version'	=> require('version.php'),
	'path'		=> 'index',
	'updir'		=> 'upload',
	'dbencrypt'	=> false,
	'sqllog'	=> false,
	'db_drive'	=> 'mysqli',
	'asynsend'	=> false,	//是否异步发送提醒消息，为true需开启服务端
	'install'	=> true,	//已安装，不要去掉啊
    'asynkey'	=> 'a292ffd0d0efef0acf06a80bd38ab52c',	//这是异步任务key
	'openkey'	=> 'bb349b4e6f9cd35a911fa4226ae13a6e',	//对外接口openkey
);

$_confpath		= $rock->strformat('?0/?1/?1Config.php', ROOT_PATH, PROJECT);
if(file_exists($_confpath)){
	$_tempconf	= require($_confpath);
	foreach($_tempconf as $_tkey=>$_tvs)$config[$_tkey] = $_tvs;
}

define('TITLE', $config['title']);
define('URL', $config['url']);
define('URLY', $config['urly']);
define('PATH', $config['path']);

define('DB_DRIVE', $config['db_drive']);
define('DB_HOST', $config['db_host']);
define('DB_USER', $config['db_user']);
define('DB_PASS', $config['db_pass']);
define('DB_BASE', $config['db_base']);

define('UPDIR', $config['updir']);
define('PREFIX', $config['perfix']);
define('QOM', $config['qom']);
define('VERSION', $config['version']);
define('HIGHPASS', $config['highpass']);
define('SYSURL', ''.URL.PATH.'.php');
$rock->initRock();
