<?php
class deptClassModel extends Model
{
	public function getdata()
	{
		$rows = $this->getall('1=1','id,name,pid,sort','sort');
		$dbs  = m('admin');
		foreach($rows as $k=>$rs){
			$stotal = $dbs->rows("`status`=1 and instr(`deptpath`,'[".$rs['id']."]')>0");
			$rows[$k]['stotal'] = $stotal;
		}
		return $rows;
	}
	
	//当前用户是否是部门负责人
	public function getuidhead($uid){
		$where=$this->rock->dbinstr('headid', $uid);
		$rows=$this->getall($where,'id,name,pid,sort','sort');
		$all_id="";
		foreach($rows as $k=>$va){
			if($k==0){
				$all_id.=$va['id'];
			}else{
				$all_id.=",".$va['id'];
			}
			
			$tat_id=$this->getrows("`pid`=".$va['id'],'id');
			
			foreach($tat_id as $j=>$jav){
				$all_id.=','.$jav['id'];
				
			}
			
			
		}
		
		return $all_id;
	}
	
}