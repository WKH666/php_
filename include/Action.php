<?php
/**
	*****************************************************************
	* 联系QQ： 290802026/1073744729									*
	* 版  本： V2.0													*
	* 开发者：雨中磐石工作室										*
	* 邮  箱： qqqq2900@126.com										*
	* 网  址： http://www.xh829.com/								*
	* 说  明: 主控制器处理											*
	* 备  注: 未经允许不得商业出售，代码欢迎参考纠正				*
	*****************************************************************
*/

abstract class mainAction{

	public $rock;
	public $db;
	public $smarty;
	public $smartydata	= array();	//模版数据
	public $assigndata	= array();
	public $display		= true;		//是否显示模板
	public $bodytitle	= '';		//副标题
	public $keywords	= '';		//关键词
	public $description	= '';		//说明
	public $linkdb		= true;		//是否连接数据库
	public $params		= array();	//参数
	public $now;
	public $date;
	public $ip;
	public $web;
	public $title		= TITLE;
	public $titles		= '';
	public $option;
	public $jm;

	public $table;
	public $extentid	= 0;
	public $importjs	= '';
	public $perfix		= '';
	public $tplname		= '';		//模板文件
	public $tplpath		= '';		//模板文件路径
	public $tpltype		= 'tpl';
	public $tpldom		= 'html';
	public $displayfile	= '';


	public function __construct()
	{
		$this->rock		= $GLOBALS['rock'];
		$this->smarty	= $GLOBALS['smarty'];
		$this->jm		= c('jm', true);
		$this->now		= $this->rock->now();
		$this->date		= $this->rock->date;
		$this->ip		= $this->rock->ip;
		$this->web		= $this->rock->web;
		$this->perfix	= PREFIX;
		$this->display	= true;
		$this->initMysql();
		$this->initConstruct();
		$this->initProject();
		$this->initAction();
		$this->beforeAction();
	}

	public function defaultAction(){}
	public function initAction(){}
	public function initProject(){}
	public function afterAction(){}
	public function initMysql(){}
	public function beforeAction(){}

	public function T($n)
	{
		return $this->perfix.''.$n;
	}

	public function assign($k, $v)
	{
		$this->assigndata[$k]=$v;
	}

	private function initConstruct()
	{
		$linkdb			= $this->rock->get('linkdb','true');
		$this->params	= explode('-', $this->rock->get('s'));	//参数
		if($linkdb == 'true' && $this->linkdb){
			$this->initMysqllink();
		}
	}

	private function initMysqllink()
	{
		$this->db		= import(DB_DRIVE);
		$GLOBALS['db']	= $this->db;
		include_once(''.ROOT_PATH.'/include/Model.php');
		$this->option	= m('option');
	}

	private function setBasedata()
	{
		$this->smartydata['bodytitle']	= $this->bodytitle;
		$this->smartydata['keywords']	= $this->keywords;
		$this->smartydata['description']= $this->description;
		$this->smartydata['title']		= $this->title;
		$this->smartydata['titles']		= $this->titles;
		$this->smartydata['rewrite']	= REWRITE;
		$this->smartydata['now']		= $this->now;
		$this->smartydata['web']		= $this->rock->web;
		$this->smartydata['ip']			= $this->ip;
		$this->smartydata['url']		= URL;
		$this->smartydata['urly']		= URLY;
		$this->assign('web', $this->rock->web);
	}

	public function setSmartyData()
	{
		$this->setBasedata();
	}

	public function setHtmlData()
	{
		$this->setBasedata();

	}

	public function getsession($name,$dev='')
	{
		return $this->rock->session($name, $dev);
	}

	public function post($na, $dev='', $lx=0)
	{
		return $this->rock->post($na, $dev, $lx);
	}

	public function get($na, $dev='', $lx=0)
	{
		return $this->rock->get($na, $dev, $lx);
	}

	public function request($na, $dev='', $lx=0)
	{
		return $this->rock->request($na, $dev, $lx);
	}

	public function isempt($str)
	{
		return $this->rock->isempt($str);
	}

	public function contain($str, $a)
	{
		return $this->rock->contain($str, $a);
	}

	public function getcookie($name, $dev='')
	{
		return $this->rock->cookie($name, $dev);
	}

	public function stringformat($str, $arr=array())
	{
		return $this->rock->stringformat($str, $arr);
	}

	public function getcan($i,$dev='')
	{
		$val	= '';
		if(isset($this->params[$i]))$val=$this->params[$i];
		if($this->rock->isempt($val)){
			$val=$dev;
		}else{
			$val=str_replace('[a]','-',$val);
		}
		return $val;
	}

	public function getmnumAjax()
	{
		$mnum	= $this->rock->request('mnum');
		$rows	= $this->option->getmnum($mnum);
		echo json_encode($rows);
	}

	public function returnjson($arr)
	{
		echo json_encode($arr);
		exit();
	}

	public function showreturn($arr='', $msg='', $code=200)
	{
		showreturn($arr, $msg, $code);
	}


	/**
	 * 获取查询条件
	 */
	public function getsreachconditionAjax(){
		//项目分类
		$xmflarr = m('option')->getall('pid=313','name');
		//申报单位
		$sbdwarr = m('dept')->getall('(pid>1 OR id=3) AND id<=52','name');
		//紧急程度
		$jjcdarr = m('option')->getall('pid=321','name');
		//库性质
		$kxzarr = m('option')->getall('pid=308','name');
		//所在库
		$szkarr = $this->getLibraryState();

		$arr = array('xmflarr'=>$xmflarr,'sbdwarr'=>$sbdwarr,'jjcdarr'=>$jjcdarr,'kxzarr'=>$kxzarr,'szkarr'=>$szkarr);
		$this->returnjson($arr);
	}

	/**
	 * 所有的库状态和进程状态对应的级联关系
	 * 申报中：上级领导审核、校项目办公室初审、职能部门专家小组评审
	 * 预备库：上传实施方案、校级专家论证、校项目办公室转送、校长办公会审批
	 * 侯建库：结转、暂停、停止、退出、再启动、出库
	 * 建设库：采购、验收、付款
	 * 归档：考评、归档
	 */
	public function getlibrarystate(){
		//获取全部的库状态
		$library_state = m('option')->getall('pid=285','id,name');
		return $library_state;
	}
	public function getprocessstateAjax(){
		//根据库状态获取进程状态
		$library_state_id = $this->post('library_state_id');
		$process_state = m('option')->getall('pid='.$library_state_id,'id,name','sort asc');
		$this->returnjson($process_state);
	}


	/**
	 * 获取项目状态
	 */
	public function getstatusconditionAjax(){
		$arr = m('option')->getall('id>=299 and id<=304','name');
		$this->returnjson($arr);
	}

}
