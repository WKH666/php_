<?php 
class indexClassAction extends ActionNot{
	
	public function initAction()
	{
		$this->mweblogin(0, true);
	}
	
	public function defaultAction()
	{

//        $data = array(
//            'touser' => 'o065-6mtwTQOOKtdzRI2QuMKVl4A',
//            'template_id' => '9wKb5wdL9XHQs4tAbxwJSp5FVyhsMMc4Ue8S6MrAYc0',
//            'url' => 'www.baidu.com',
//            'title' => '进度提醒',
//            'data' => array(
//                'first' => array('value' => '测试！'),
//                'keyword1' => array('value' =>'测试'),
//                'keyword2' => array('value' => "测试"),
//                'remark' => array('value' => '详情')
//            )
//        );
//
//        pushInterface($data);
        var_dump(1);
		$this->title = getconfig('apptitle','项目库');
	}
	
	public function editpassAction()
	{
		
	}
	
	public function testAction()
	{
		
	}
	
	/**
	*	用户信息
	*/
	public function userinfoAction()
	{
		$uid = (int)$this->get('uid');
		$urs = m('admin')->getone($uid, '`id`,`name`,`deptallname`,`ranking`,`tel`,`email`,`mobile`,`sex`,`face`');
		if(!$urs)exit('not user');
		if(isempt($urs['face']))$urs['face']='images/noface.png';
		$this->assign('arr', $urs);
	}
}