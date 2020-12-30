<?php
class project_archiveClassAction extends Action
{
	private $admin_info = array();//用户信息
	private $group_id = '';//组id（角色id）
	
	/**
	 * 项目归档管理列表
	 */
	public function archiveAjax(){
		$xmfl   = $this->rock->post('xmfl');//项目分类
		$sbdw   = $this->rock->post('sbdw');//申报单位
		$xmbh   = $this->rock->post('xmbh');//项目编号
		$xmmc   = $this->rock->post('xmmc');//项目名称
		$time_frame = $this->rock->post('time_frame');//时间范围
//		$beforea		= $this->request('storebeforeaction');//数据权限处理函数
		$aftera = $this->request('storeafteraction');//操作权限处理函数
		$where = '';//查询条件
	
//		if($beforea != ''){//数据权限处理
//			if(method_exists($this, $beforea)){
//				$where .= $this->$beforea();
//			}
//		}
		
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
		//项目编号
		if($xmbh!='')$where.=" and c.project_number='".trim($xmbh)."'";
		//项目名称
		if($xmmc!='')$where.=" and c.project_name like '%".trim($xmmc)."%'";
		
		$table = '`[Q]flow_bill` a left join `[Q]admin` b on a.uid=b.id left join `whole_projects` c on a.mid=c.id left join `[Q]flow_course` fc on fc.id=a.nowcourseid';
		$fields = 'a.table as mtype,a.modeid,a.optid,a.nowcheckname,a.status as bill_status,a.allcheckid,a.nowcheckid,a.nowcourseid,a.optid,b.name,b.deptname,c.id,c.num,c.project_name,c.status as cst,c.isturn,c.optname,c.project_head,c.project_apply_time,c.project_select,c.project_xingzhi,c.project_ku,c.isturn,fc.name as flowname,c.exigence_status,c.process_state,c.project_is_guidang,c.project_number,c.project_guidang_date,c.project_guidang_user';
		/*@@归档管理列表显示条件:已考评*/
		$where = "a.isdel=0 and c.is_evaluation=1 $where";
		$order = 'a.optdt desc';
		$arr = $this->limitRows($table,$fields,$where,$order);
		$arr['totalCount'] = $arr['total'];
		unset($arr['sql'],$arr['total']);
		//echo $arr['sql'];exit;
		if($arr['totalCount'] == 0){
			exit('暂无数据');
		}
		if(method_exists($this, $aftera)){//操作菜单权限处理
			$narr	= $this->$aftera('purchase',$arr['rows']);
			if(is_array($narr)){
				foreach($narr as $kv=>$vv)$arr['rows'][$kv]=$vv;
			}
		}
		return $this->returnjson($arr);
	}

	/**
	 * 数据权限处理方法
	 * 申报者（数据：自己的项目）$group_id=1
	 * 上级领导（数据：该单位的项目）$group_id=2
	 * 校项目办公室（数据：全部）$group_id=3
	 */
//	public function dataauthbefore(){
//		//获取当前账号的角色
//		$this->admin_info=m('admin')->getone('id='.$this->adminid);
//		$this->group_id=m('sjoin')->getone('sid='.$this->adminid.' and type="gu"','max(mid) as mid')['mid'];
//		//echo m('sjoin')->getLastSql();
//		$where='';//条件
//		switch ((int)$this->group_id) {
//			case 1:
//				$where.=" and a.uid=".$this->adminid." and c.isturn=1";
//				break;
//			case 2:
//				$s=m('dept')->getuidhead($this->adminid);
//				$where=" and deptid in(".$s.") and c.isturn=1";
//				break;
//			case 3:
//				$where=" and c.isturn=1";
//				break;
//			default:
//				$where=" and c.isturn=1";
//				break;
//		}
//		
//		if($this->admin_info['is_admin']==1){
//			$where=" and c.isturn=1";
//		}
//		return $where;
//	}
	
	
	/**
	 * 获取操作菜单权限
	 */
	public function archiveafter($table,$rows){
		foreach($rows as $k=>$rs){
			$rows[$k]['caoz']='';
			//判断是否有归档,有则只显示查看,没有则显示查看、归档
			if((int)$rs['project_is_guidang']==1){//有归档
				$rows[$k]['caoz']='<a onclick="check_project(\''.$rs['mtype'].'\','.$rs['id'].',\''.$rs['project_name'].'\')">查看</a>';
			}else{
				$rows[$k]['caoz']='<a onclick="check_project(\''.$rs['mtype'].'\','.$rs['id'].',\''.$rs['project_name'].'\')">查看</a><span style="padding:5px;">|</span><a onclick="project_guidang(\''.$rs['mtype'].'\','.$rs['id'].',\''.$rs['project_name'].'\')">归档</a>';
			}
		}
		//var_dump($rows);exit;
		return $rows;
	}


	/**
	 * 项目归档
	 */
	public function filesaveAjax(){
		$mid = $this->post('mid');//项目id
		$mtype = $this->post('mtype');
		$gdate = $this->post('gdate');//归档日期
		$gpeople = $this->post('gpeople');//归档人
		$gremark = $this->post('gremark');//归档说明
		if($mid=='')exit('项目id不能为空');
		if($gdate=='')exit('归档日期不能为空');
		if($gpeople=='')exit('归档人不能为空');
		if($gpeople=='')exit('归档说明不能为空');
		$gresult = m($mtype)->update(array('project_guidang_date'=>$gdate,'project_guidang_user'=>$gpeople,'project_guidang_bz'=>$gremark,'project_is_guidang'=>1,'process_state'=>'归档'),'id='.$mid);
		//echo m('project_apply')->getLastSql();
		if($gresult)
			$info =array('id' => $gresult,'success' => true,'msg' => '归档成功');
		else{
			$info =array('id' => $gresult,'success' => false,'msg' => '归档失败');
		}
		$this->returnjson($info);
	}
}