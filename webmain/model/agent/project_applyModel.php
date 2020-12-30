<?php
class agent_project_applyClassModel extends agentModel
{
	public function initModel()
	{
		$this->settable('flow_bill');
	}
	
	public function gettotal()
	{
		$stotal	= $this->getwdtotal($this->adminid);
		$titles	= '';
		return array('stotal'=>$stotal,'titles'=> $titles);
	}
	
	private function getwdtotal($uid)
	{
		$stotal	= $this->rows("`uid`='$uid' and `status`=2");
		return $stotal;
	}
	
	protected function agentdata($uid, $lx)
	{
		
		//用户信息
		$admin_info=m('admin')->getone('id='.$uid);
		//部门负责人
		$s=m('dept')->getuidhead($uid);
		$project_shuju='1=1';
		//待处理
		if($lx=='daiban_daib'){
			
			$project_shuju	= 'a.status not in(1,2,5)';
			
		}
		
		//已完成
		if($lx=='flow_ywc'){
			
			$project_shuju = 'a.status=1';
		}
		
		//未通过
		if($lx=='flow_wtg'){
			$project_shuju = 'a.status=2';
		}
		
		
		$user_shuju	= ' and a.uid='.$uid.'';
		//是否为管理员 是则查看全部数据 不是则为普通用户查看自己部分的数据
		if($admin_info['is_admin']==1){
			
			$user_shuju=" and c.isturn=1";
			
		}
		
		//是否为部门领导 是查看部门及子部门的数据  不是只能查看自己数据
		if($s && $admin_info['is_admin']!=1){
		
			$user_shuju=" and b.deptid in(".$s.") and c.isturn=1";
		
		}
		
		$where=$project_shuju.$user_shuju;

		
		$arr=$this->getlimit($where,$this->page,'c.id,c.num,c.project_name,c.project_number,c.project_apply_time,c.project_head,c.exigence_status,a.status','',$this->limit,'`[Q]flow_bill` a left join `[Q]admin` b on a.uid=b.id left join `whole_projects` c on a.mid=c.id');
		
				
//$where="project_number='XS2017CG052'";
//		
//		$arr=$this->getlimit($where,1,'*','id asc',100,'[Q]project_apply');
		
				
//$where="project_number='XS2017CG052'";
		
		//$arr=$this->getlimit($where,1,'*','id asc',100,'[Q]project_apply');
		
		foreach($arr['rows'] as $k=>$v){
		
			$a=explode(" ", $v['project_apply_time']);
			
			$arr['rows'][$k]['project_apply_time']=$a[0];
			if($v['exigence_status']==''){
				$arr['rows'][$k]['exigence_status']='暂未分配';
			}
		
			$status_arry=m('flowbill')->getstatus($v['status'],$lx=1);
		
			$arr['rows'][$k]['statustext']=$status_arry[0];
		}
		
		return $arr;
	}
	
	protected function agenttotals($uid)
	{
		return array(
			'mywtg' => $this->getwdtotal($uid)
		);
	}
}