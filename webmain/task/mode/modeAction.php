<?php
class modeClassAction extends ActionNot
{
	public function initAction()
	{
		$aid 	= (int)$this->get('adminid');
		$token 	= $this->get('token');
		$aid 	= m('login')->autologin($aid, $token);
		if($aid==0){
			$this->mweblogin(1);
		}
		$this->getlogin(1);
	}

	public function defaultAction()
	{
		$fn	 	= $this->get('fn');
		$title 	= $this->rock->jm->base64decode($this->get('title'));
		if($title!='')$this->title = $title;
		$path 	= P.'/task/mode/html/'.$fn.'.html';
		if(!file_exists($path))exit('not found '.$fn.'');
		$this->displayfile = $path;
	}

	//移动端页面详情
	public function xAction()
	{
		$num = $this->get('modenum');
		if($num=='')$num=$this->get('num');

		$mid 	 = (int)$this->get('mid');
		if($num=='' || $mid==0)exit('无效请求');


		$arr 	 = m('flow')->getdatalog($num, $mid, 1);
		$pagetitle 		= $arr['title'];
		$this->title 	= $arr['title'];
		if($pagetitle=='')$pagetitle = $arr['modename'];
		$this->smartydata['arr'] = $arr;

		$spagepath 	= P.'/flow/page/viewpage_'.$num.'_1.html';
		if(!file_exists($spagepath)){
			$spagepath = '';
		}
		$isheader = 0;
		if($this->web != 'wxbro' && $this->web != 'xinhu' && $this->get('show')=='we')$isheader=1;
		$this->assign('isheader', $isheader);
		$this->smartydata['spagepath']		= $spagepath;
		$this->smartydata['pagetitle']		= $pagetitle;
	}

	//pc端页面详情
	public function pAction()
	{
		$num = $this->get('modenum');
		if($num=='')$num=$this->get('num');

		$mid 	 = (int)$this->get('mid');
		if($num=='' || $mid==0)exit('无效请求');
		$stype 			= $this->get('stype');
        $pinshen 		= $this->get('pinshen');
		$btnstyle       = $this->get('btnstyle');//添加审核信息和状态信息学按钮
		$btntype        = $this->get('btntype');//进入页面默认跳转到哪个部分

		$arr 	 		= m('flow')->getdatalog($num, $mid, 0);

		//获取状态更改记录信息
		$arr['status_log'] = m('mf_status_log')->getall('mtype="'.$num.'" and mid='.$mid,'update_status,update_time,remark,file_ids,carryover_yeas');
		$this_msg =  m('mf_status_log')->getone('mtype="'.$num.'" and mid='.$mid,'update_status,carryover_yeas','status_id desc');
		$arr['now_status_log'] = $this_msg['update_status'];
		$arr['carryover_yeas'] = $this_msg['carryover_yeas'];
		//循环查出文件名称和地址
		foreach ($arr['status_log'] as $k => $v) {
			$file_id_arr = explode(',', $v['file_ids']);
			$files = array();//定义存储文件信息的临时数组
			foreach ($file_id_arr as $key => $value) {
				$files[$key] = m('file')->getone('id='.$value,'id,filename,filepath');
			}
			$arr['status_log'][$k]['files'] = $files;
		}
		unset($k,$v,$key,$value,$files,$file_id_arr);
		//获取当前项目的是否是为库项目，和项目年份,当库状态为侯建库和当前项目已经考评才能进行归档
		$statusarr = m($num)->getone('id='.$mid,'project_year,project_xingzhi,is_evaluation,project_ku,project_is_guidang');
		$arr['project_year'] = $statusarr['project_year'];//项目年份
		$arr['project_xingzhi'] = $statusarr['project_xingzhi'];//是否为库项目
		$arr['is_evaluation'] = $statusarr['is_evaluation'];//是否已考评
		$arr['project_ku'] = $statusarr['project_ku'];//库状态
		$arr['project_is_guidang'] = $statusarr['project_is_guidang'];//当前项目是否归档

		//获取当前项目的所有文件
		$currentfiles = m('file')->getall('mid='.$mid,'*');
		//根据流程id获取当前项目的审核时上传的文件
		$flowidarr = m('flow_log')->getall('`table`="project_apply" and `mid`='.$mid,'id');
		$flowids = '';
		foreach($flowidarr as $k=>$rs){
			$flowids.=','.$rs['id'].'';
		}
		unset($k,$rs);
		$farr = m('file')->getfile('flow_log', substr($flowids,1));
		foreach ($farr as $k => $v) {
			if($log_file=m('file')->getone('id='.$v['id'],'*')){
				array_push($currentfiles,$log_file);
			}
		}
		unset($k,$v);
		// 对数组某个字段进行排序(1\正序2\倒序)
    	$arr['currentfiles'] = sortArrByField($currentfiles,'id','1');

		$pagetitle 		= $arr['title'];
		$this->title 	= $arr['title'];
		if($pagetitle=='')$pagetitle = $arr['modename'];
		$this->smartydata['arr'] = $arr;

		$spagepath 	= P.'/flow/page/viewpage_'.$num.'_0.html';

		if(!file_exists($spagepath)){
			$spagepath = '';
		}

		$this->smartydata['spagepath']		= $spagepath;
		$this->smartydata['pagetitle']		= $pagetitle;
		$this->assign('stype', $stype);
		$this->assign('pinshen', $pinshen);
		$this->assign('btnstyle', $btnstyle);
		$this->assign('btntype', $btntype);
		if($stype=='word'){

//		if($arr['word_name']==''){
//
//			m('file')->fileheader(date("Y/m/d").'.mht');
//		}else{
//
//
//			m('file')->fileheader($arr['word_name'].'.html');
//		}

		}
	}

	//下载
	public function downAction()
	{
		$this->display = false;
		$id  = (int)$this->jm->gettoken('id');
		m('file')->show($id);
	}









	//导出页面
	public function eAction()
	{
		$num	= $this->get('num');
		$event	= $this->get('event');
		$stype	= $this->get('stype');

		$arr 	= m('flow')->printexecl($num, $event);
		$this->title = $arr['moders']['name'];
		$urlstr	= '?a=e&num='.$num.'&event='.$event.'';
		$this->assign('arr', $arr);
		$this->assign('urlstr', $urlstr);
		$this->assign('stype', $stype);
		if($stype!=''){
			$filename = $this->title;
			header('Content-type:application/vnd.ms-excel');
			header('Content-disposition:attachment;filename='.iconv("utf-8","gb2312",$filename).'.'.$stype.'');
		}
	}

	//邮件上打开详情
	public function aAction()
	{
		$num = $this->get('num');
		$mid = $this->get('mid');
		$act = 'p';
		if($this->rock->ismobile())$act='x';
		$url = 'task.php?a='.$act.'&num='.$num.'&mid='.$mid.'';
		$this->rock->location($url);
	}

}
