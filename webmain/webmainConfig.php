<?php
if(!defined('HOST'))die('not access');
//[管理员]在2020-05-21 17:02:30通过[系统→系统工具→系统设置]，保存修改了配置文件
return array(
	'url'	=> 'http://localhost/ProjectLibrary/',	//系统URL
	'localurl'	=> '',	//本地系统URL，用于服务器上浏览地址
    //'url'	=> 'http://'.HOST.'/sheke/',	//系统URL
    //'localurl'	=> 'http://keti.zhsk.gov.cn/',	//本地系统URL，用于服务器上浏览地址
	'title'	=> '珠海社科项目库管理系统',	//系统默认标题
	'apptitle'	=> '项目库',	//APP上或PC客户端上的标题
	'db_host'	=> '183.56.203.217',	//数据库地址
	'db_user'	=> 'sheke',	//数据库用户名
	'db_pass'	=> 'sheke!@#',	//数据库密码
	'db_base'	=> 'zh_sheke_dev_db',	//数据库名称
   /* 'db_host'	=> 'localhost',
||||||| .r1257
	'db_host'	=> '127.0.0.1',	//数据库地址
	'db_user'	=> 'root',	//数据库用户名
	'db_pass'	=> 'root',	//数据库密码
	'db_base'	=> 'sheke',	//数据库名称
   /* 'db_host'	=> 'localhost',
=======

    //测试站
    'db_host'	=> '183.56.203.217',
    'db_user'	=> 'sheke',
    'db_pass'	=> 'sheke!@#',
    'db_base'	=> 'zh_sheke_dev_db',
	//本地
    /*'db_host'	=> 'localhost',
>>>>>>> .r1271
    'db_user'	=> 'root',
    'db_pass'	=> 'root',
    'db_base'	=> 'sheke_db',*/


    /*'db_host'	=> 'localhost',	//数据库地址
    'db_user'	=> 'sheke',	//数据库用户名
    'db_pass'	=> '&sheke!%(10',	//数据库密码
    'db_base'	=> 'zh_sheke_db',	//数据库名称*/

	'perfix'	=> 'xinhu_',	//数据库表名前缀
	'qom'	=> 'xinhu_',	//session、cookie前缀
	'highpass'	=> '',	//超级管理员密码，可用于登录任何帐号
	'db_drive'	=> 'mysqli',	//操作数据库驱动有mysql,mysqli,pdo三种
	'randkey'	=> 'rpyhltwmxuibfnsdjqevozckag',	//系统随机字符串密钥
	'asynkey'	=> 'a292ffd0d0efef0acf06a80bd38ab52c',	//这是异步任务key
	'openkey'	=> 'bb349b4e6f9cd35a911fa4226ae13a6e',	//对外接口openkey
	'wxopenkey'	=> '7c84187d483d671faa1d2180e99a02b8',
	'sqllog'	=> false,	//是否记录sql日志保存upload/sqllog下
	'asynsend'	=> false,	//是否异步发送提醒消息，为true需开启服务端
	'install'	=> true,	//已安装，不要去掉啊
	'xinhukey'	=> '',
	'cas_login'	=> false,
	'orcle_db_host'	=> '10.30.254.30',
	'orcle_db_user'	=> 'third_zcsb',
	'orcle_db_pass'	=> 'third_zcsbadmin',
	'orcle_instance_name'	=> 'urpdb',
	'orcle_port'	=> '1521',
	'orcle_code'	=> 'AL32UTF8',
	'leader'	=> 'Array',
	'ph_host'	=> '10.30.252.57',
	'ph_user'	=> 'gdit_user',
	'ph_pass'	=> '123456',
	'ph_base'	=> 'cg_library',
	'ph_perfix'	=> '',

);
