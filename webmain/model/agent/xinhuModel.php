<?php
/**
*	迈峰网络科技有限公司应用
*/
class agent_xinhuClassModel extends agentModel
{
	protected function agentdata($uid, $lx)
	{
		$rows[] = array(
			'title' => '欢迎使用项目库信息管理系统',
			'cont'	=> '官网：<a href="http://www.minephone.com/" target="_blank">http://www.minephone.com/</a><br>版本：'.VERSION.'',
			'statuscolor' => 'green',
			'statustext'  => '官网'
		);
		$rows[] = array(
			'title' => '开源协议',
			'cont'	=> '仅限迈峰公司商用',
			'statuscolor' => 'green',
			'statustext'  => '官网'
		);
		$rows[] = array(
			'title' => '相关帮助',
			'cont'	=> '1、常见使用问题，<a href="" target="_blank">[查看]</a><br>2、使用前必读 ，<a href="void(-1)" target="_blank">[查看]</a><br>3、二次开发前必读 ，<a href="void(-1)" target="_blank">[查看]</a><br>4、更多帮助问题列表 ，<a href="void(-1)" target="_blank">[查看]</a>',
			'statuscolor' => 'green',
			'statustext'  => '官网'
		);
		$arr['rows'] 	= $rows;
		return $arr;
	}
}