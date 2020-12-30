<?php
/**
*	对外开发接口文件
*	createname：广州迈峰网络科技
*	homeurl：http://www.minephone.com/
*	Copyright (c) 2016 minephone (minephone.com)
*	Date:2017-04-01
*	explain：返回200为正常
*/
class openapiAction extends ActionNot
{
	private $openkey = '';
	public 	$postdata= '';
	
	public function initAction()
	{
		$this->display= false;
		$openkey 		= $this->post('openkey');
		$this->openkey 	= getconfig('openkey');
		if(HOST != '127.0.0.1' && $this->openkey != ''){
			if($openkey != md5($this->openkey))$this->showreturn('', 'openkey not access', 201);
		}
		if(isset($GLOBALS['HTTP_RAW_POST_DATA']))$this->postdata = $GLOBALS['HTTP_RAW_POST_DATA'];
	}
	
	public function getvals($nae, $dev='')
	{
		$sv = $this->rock->jm->base64decode($this->post($nae));
		if($this->isempt($sv))$sv=$dev;
		return $sv;
	}
}