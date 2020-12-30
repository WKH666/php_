<?php
class project_sx_applyClassAction extends Action
{

	public function initAction()
	{
		$this->admin=m('admin');
		$this->flowbill=m('flowbill');
		$this->flowcourse=m('flowcourse');

	}
	public function project_applybefore($table)
	{
			/*搜索条件缺少预算
		需要修改人 @guo 	*/
		$dt 	= $this->rock->post('dt1');//申报时间
		$key 	= $this->rock->post('key');
		$kzt    = $this->rock->post('kzt');//列表条件,$kzt='xmk'则是项目库的库状态的查询条件
		$zt 	= $this->rock->post('zt');//审核状态
		$bdt 	= $this->rock->post('bdt');//最近$bdt个月
		$xmfl   = $this->rock->post('xmfl');//项目分类
		$sbdw   = $this->rock->post('sbdw');//申报单位
		$jjcd   = $this->rock->post('jjcd');//紧急程度
		$xmbh   = $this->rock->post('xmbh');//项目编号
		$xmmc   = $this->rock->post('xmmc');//项目名称
		$fzr    = $this->rock->post('fzr');//项目负责人
		$xmys   = $this->rock->post('xmys');//项目预算
		$time_frame = $this->rock->post('time_frame');//时间范围
		$modeid = $this->rock->post('modeid');//项目预算
		$lx= $this->rock->post('lx');//项目预算
		$ui=$this->adminid;
		$where='';

		//项目库管理只获取库状态为侯建库的项目
		//if($kzt!='')$where.=" and c.project_ku='".trim($kzt)."'";
		switch ($kzt) {
			case 'xmk':
				$where.=" and (c.project_ku in ('侯建库','建设库','归档'))";
				break;

			default:
				break;
		}

		//待办
		if($lx=='daib'){
			$where	= 'and a.uid='.$uid.'';
			$where	= 'and a.`status`=0 and '.$this->rock->dbinstr('a.nowcheckid', $uid);

		}

		if($lx=='xia'){
			$where	= 'and a.uid='.$uid.'';
			$where	= 'and '.$this->rock->dbinstr('b.superid', $uid);

		}

		if($lx=='jmy'){
			$where	= 'and a.uid='.$uid.'';

			$where	= 'and '.$this->rock->dbinstr('a.allcheckid', $uid);
		}

		if($lx=='mywtg'){
			$where	= 'and a.uid='.$uid.'';
			$where.=" and a.status=2";
		}
		if($lx=='my'){

			$admin_info=m('admin')->getone('id='.$uid);
			$s=m('dept')->getuidhead($uid);

			if($s && $admin_info['is_admin']==0){

				$where="and deptid in(".$s.") and c.isturn=1 and c.project_xingzhi!='非库项目'";

			}else if($admin_info['is_admin']==1){
				$where="and c.project_xingzhi!='非库项目'";
			}else
				$where="and a.uid=".$uid." and c.project_xingzhi!='非库项目'";{
			}

			//如果是管理员 则可以查看全部数据

		}
		if($lx=='all'){
			$where	= "and c.isturn=1 and c.project_xingzhi!='非库项目'";
		}
		if($zt!='')$where.=" and a.status='$zt'";
		if($dt!='')$where.=" and a.applydt='$dt'";
//		if($modeid>0)$where.=' and a.modeid='.$modeid.'';

		switch($modeid){
			case 1:$modeid = 54;
			case 2:$modeid = 57;
			default:$modeid ='1=1';
		}

		if(!isempt($key))$where.=" and (b.`name` like '%$key%' or b.`deptname` like '%$key%' or a.sericnum like '$key%')";

		//guozhijie
		//时间
		if(!isempt($bdt)){
			$start_time = date('Y-m-01', strtotime('-'.$bdt.' month'));
			$end_time = date('Y-m-d', time());
			$where.=" and c.applydt between '".$start_time."' and '".$end_time."'";
		}
		//时间范围
		if($time_frame!=""){
			list($start_time,$end_time) = explode(',', $time_frame);
			$where.=" and c.project_apply_time between '".$start_time."' and '".$end_time."'";
			unset($start_time,$end_time);
		}

		//项目分类
		if($xmfl!='')$where.=" and c.project_select='".trim($xmfl)."'";
		//申报单位
		if($sbdw!='')$where.=" and b.deptname='".trim($sbdw)."'";
		//紧急程度
		if($jjcd!='')$where.=" and c.exigence_status='".trim($jjcd)."'";
		//项目编号
		if($xmbh!='')$where.=" and c.project_number='".trim($xmbh)."'";
		//项目名称
		if($xmmc!='')$where.=" and c.project_name like '%".trim($xmmc)."%'";
		//项目负责人
		if($fzr!='')$where.=" and c.project_head='".trim($fzr)."'";
		//项目预算
		if($xmys!=''){
			list($yusuanone,$yusuantwo) = explode(',', $xmys);
			$where.=" and c.project_yushuan between ".($yusuanone*10000)." and ".($yusuantwo*10000);
		}
		return array(
			'table' => '`[Q]flow_bill` a left join `[Q]admin` b on a.uid=b.id left join `whole_projects` c on a.mid=c.id left join `[Q]flow_course` fc on fc.id=a.nowcourseid',
			'where' => " and a.isdel=0 $where",
			'fields'=> 'a.modeid,a.id as fid,a.optid,a.nowcheckname,a.status as bill_status,a.allcheckid,a.nowcheckid,a.nowcourseid,a.optid,b.name,b.deptname,c.id,c.num,c.project_name,c.status as cst,c.isturn,c.optname,c.project_head,c.project_apply_time,c.project_select,c.project_xingzhi,c.project_ku,c.isturn,fc.name as flowname,c.exigence_status,c.process_state,c.project_is_guidang,project_yushuan',
			'order' => 'a.optdt desc'
		);

	}

	public function project_applyafter($table,$rows)
	{

		foreach($rows as $k=>$rs){
			//去除两边空格
			$rs['project_xingzhi'] = trim($rs['project_xingzhi']);//是否为库项目
			$rs['project_ku'] = trim($rs['project_ku']);//项目状态

			if($rs['cst']==0 && $rs['isturn']!=0){
				$statustext='待<font color="#5a5a5a">'.$rs['nowcheckname'].'</font>处理';
			}else if($rs['isturn']==0){
				$statustext='待提交';
			}else{
			    $statustext= $this->flowbill->getstatus($rs['cst'],$lx=0);

			}

			if(!$rs['cst']==5){
				$flowname=$this->db->getone('[Q]flow_course','setid='.$rs['modeid'].' and id='.$rs['nowcourseid'],'name');
				$rows[$k]['flowname']= $flowname['name'];

			}else{
				$rows[$k]['flowname']= '';

			}
			$rows[$k]['statustext'] = $statustext;
			$project_name="'".$rs['project_name']."'";
			$num="'".$rs['num']."'";
			//未提交
			if($rs['isturn']==0){
				//查看 编辑 删除
				//$rows[$k]['caoz'] ="<a onclick='check_project(".$num.",".$rs['id'].",".$project_name.")'>查看</a>";

				$rows[$k]['caoz'] = '<a onclick="check_project('.$num.','.$rs['id'].','.$project_name.')">查看</a>';
				$rows[$k]['caoz'].= '<span style="padding:5px;">|</span>';
				$rows[$k]['caoz'].= '<a onclick="edit_project('.$rs['num'].','.$rs['id'].','.$project_name.')">编辑</a>';
				$rows[$k]['caoz'].= '<span style="padding:5px;">|</span>';
				$rows[$k]['caoz'].= '<a onclick="del(this)">删除</a>';
			//}else if($rs['nowcheckid']==$this->adminid){
			//debug 这里改为可以多人审核
			}else if(in_array($this->adminid, explode(',', $rs['nowcheckid']))){
				//需要审核
				$rows[$k]['caoz'] = '<a onclick="check_project('.$rs['num'].','.$rs['id'].','.$project_name.')">查看</a>';
				$rows[$k]['caoz'].= '<span style="padding:5px;">|</span>';
				$rows[$k]['caoz'].= '<a onclick="chuli_project('.$rs['num'].','.$rs['id'].','.$project_name.')">处理</a>';
			}else if($rs['optid']==$this->adminid && $rs['bill_status']==2){
				$rows[$k]['caoz'] = '<a onclick="check_project('.$rs['num'].','.$rs['id'].','.$project_name.')">查看</a>';
				$rows[$k]['caoz'].= '<span style="padding:5px;">|</span>';
				$rows[$k]['caoz'].= '<a onclick="edit_project('.$rs['num'].','.$rs['id'].','.$project_name.')">编辑</a>';
			}else{
				$rows[$k]['caoz'] = '<a onclick="check_project('.$rs['num'].','.$rs['id'].','.$project_name.')">查看</a>';
			}






		}




		return array('rows'=>$rows);


	}


	/**
	 * 获取统计数据
	 */
	public function project_apply_countAjax(){
		$arr = m('flow:project_apply')->getStatistics();
		$this->returnjson($arr);
	}


	/**
	 * 项目归档
	 */
	public function filesaveAjax(){
		$mid = $this->post('mid');//项目id
		$gdate = $this->post('gdate');//归档日期
		$gpeople = $this->post('gpeople');//归档人
		$gremark = $this->post('gremark');//归档说明
		if($mid=='')exit('项目id不能为空');
		if($gdate=='')exit('归档日期不能为空');
		if($gpeople=='')exit('归档人不能为空');
		if($gpeople=='')exit('归档说明不能为空');
		$gresult = m('project_apply')->update(array('project_guidang_date'=>$gdate,'project_guidang_user'=>$gpeople,'project_guidang_bz'=>$gremark,'project_is_guidang'=>1,'process_state'=>归档),'id='.$mid);
		//echo m('project_apply')->getLastSql();
		if($gresult)
			$info =array('id' => $gresult,'success' => true,'msg' => '归档成功');
		else{
			$info =array('id' => $gresult,'success' => false,'msg' => '归档失败');
		}
		$this->returnjson($info);
	}

	/**
	 * 获取详细流程信息
	 */
	public function getflowdetailAjax(){
		$resultarr = array();//返回的级联数组
		//获取全部的库状态
		$library_state = m('option')->getall('pid=285','id,name');
		$this->returnjson($library_state);
	}

	/**
	 * 获取处理流程
	 */
	public function getprojectflowinfoAjax(){
		$mid = $this->post('mid');//项目id
		//获取处理流程
		$flowarr = m('flow')->getdatalog('project_apply', $mid, 0)['flowinfor'];
		//$flowarr = m('flow:project_apply')->getprojectflow();
		$this->returnjson($flowarr);
	}
}
