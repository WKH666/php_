<?php 
class testClassAction extends ActionNot{
	
	//测试地址http://127.0.0.1/app/xinhu/?m=test&d=public
	public function defaultAction()
	{
		$this->display = false;
		
		//$a = m('weixin:media')->upload('upload/2017-02/08_10092129.doc');
		
		$a = c('xinhu')->getdata('mode');
		
		
		//$a = m('weixin:chat')->send(8,'user',1,'1D7MJJCzzXWYqlWLq2HPidCeZHv7mMWGIzyi8FMUxmdC_BrPkpxWbQU18OvOCcHIs5QrSF_XCSk7bGS-kJXC2Vw');
		print_r($a);
		echo ''.$this->now.'<br>';
	}
	
}