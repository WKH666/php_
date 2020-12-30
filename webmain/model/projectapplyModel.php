<?php
class projectapplyClassModel extends Model
{
//	public $statustext;
//	public $statuscolor;
//
//	public function initModel()
//	{
//		$this->settable('flow_bill');
//		$this->statustext	= explode(',','待处理,已审核,处理不通过,,,已作废');
//		$this->statuscolor	= explode(',',',,,,,');
//	}
//
//	/**
//	*	获取状态
//	*/
//	public function getstatus($zt, $lx=0)
//	{
//		$a1	= $this->statustext;
//		$a2	= $this->statuscolor;
//		$str 		= '<font color='.$a2[$zt].'>'.$a1[$zt].'</font>';
//		if($lx==0){
//			return $str;
//		}else{
//			return array($a1[$zt], $a2[$zt]);
//		}
//	}
//
//	//数据处理
//	public function getrecord($uid, $lx, $page, $limit)
//	{
//		$srows	= array();
//		$where	= 'uid='.$uid.'';
//		$isdb	= 0;
//		$flowbill=m('flowbill');
//		//未通过
//		if($lx=='flow_wtg'){
//			$where .= ' and `status`=2';
//		}
//
//		if($lx=='flow_dcl'){
//			$where .= ' and `status`=0';
//		}
//
//		//已完成
//		if($lx=='flow_ywc'){
//			$where .= ' and `status`=1';
//		}
//
//		//待办
//		if($lx=='daiban_daib' || $lx=='daiban_def'){
//			$where	= '`status`=0 and '.$flowbill->rock->dbinstr('nowcheckid', $uid);
//			$isdb	= 1;
//		}
//
//		//经我处理
//		if($lx=='daiban_jwcl'){
//			$where	= $flowbill->rock->dbinstr('allcheckid', $uid);
//		}
//
//		//我全部下级申请
//		if($lx=='daiban_myxia'){
//			$where 	= m('admin')->getdownwheres('uid', $uid, 0);
//		}
//
//		//我直属下级申请
//		if($lx=='daiban_mydown'){
//			$where 	= m('admin')->getdownwheres('uid', $uid, 1);
//		}
//
//		$key 	= $this->rock->post('key');
//		if(!isempt($key))$where.=" and (`optname` like '%$key%' or `modename` like '%$key%' or `sericnum` like '$key%')";
//
//		$arr 	=$flowbill->getlimit('`isdel`=0 and '.$where, $page,'*','`optdt` desc', $limit);
//
//
//	}
//
//	//获取待办处理数字
//	public function daibanshu($uid)
//	{
//		$where	= '`status`=0 and isdel=0 and '.$this->rock->dbinstr('nowcheckid', $uid);
//		$to 	= $this->rows($where);
//		return $to;
//	}
//
//	//未通过的
//	public function applymywgt($uid)
//	{
//		$where	= '`status`=2 and isdel=0 and `uid`='.$uid.'';
//		$to 	= $this->rows($where);
//		return $to;
//	}
//
//	//单据数据
//	public function getbilldata($rows)
//	{
//		$srows	= array();
//		$modeids= '0';
//		foreach($rows as $k=>$rs)$modeids.=','.$rs['modeid'].'';
//		$modearr= array();
//		if($modeids!='0'){
//			$moders = m('flow_set')->getall("`id` in($modeids)",'id,num,name,summary');
//			foreach($moders as $k=>$rs)$modearr[$rs['id']] = $rs;
//		}
//		foreach($rows as $k=>$rs){
//			$modename	= $rs['modename'];
//			$summary	= '';
//			$modenum 	= '';
//			$statustext	= '记录不存在';
//			$statuscolor= '#888888';
//			$wdst 		= 0;
//			$ishui 		= 0;
//			if(isset($modearr[$rs['modeid']])){
//				$mors 	= $modearr[$rs['modeid']];
//				$modename 	= $mors['name'];
//				$summary 	= $mors['summary'];
//				$modenum 	= $mors['num'];
//				$rers 		= $this->db->getone('[Q]'.$rs['table'].'', $rs['mid']);
//
//
//				$summary	= $this->rock->reparr($summary, $rers);
//				if($rers){
//					$wdst		 = $rers['status'];
//					$statustext  = $this->statustext[$wdst];
//					$statuscolor = $this->statuscolor[$wdst];
//					if($rers['isturn']==0){
//						$statustext  = '待提交';
//						$statuscolor = '';
//						$wdst		 = 1;
//					}
//					if($rers['status']==5)$ishui = 1;
//				}else{
//					$this->update('isdel=1', $rs['id']);
//				}
//			}
//			$status = '<font color="'.$statuscolor.'">'.$statustext.'</font>';
//			if($wdst==0)$status='待<font color="blue">'.$rs['nowcheckname'].'</font>处理';
//
//			$srows[]= array(
//				'id' 		=> $rs['mid'],
//				'optdt' 	=> $rs['optdt'],
//				'applydt' 	=> $rs['applydt'],
//				'name' 		=> $rs['name'],
//				'deptname' 	=> $rs['deptname'],
//				'sericnum' 	=> $rs['sericnum'],
//				'ishui' 	=> $ishui,
//				'modename' 	=> $modename,
//				'modenum' 	=> $modenum,
//				'summary' 	=> $summary,
//				'status'	=> $status
//			);
//		}
//		return $srows;
//	}
//
//	public function homelistshow()
//	{
//		$arr 	= $this->getrecord($this->adminid, 'flow_dcl', 1, 5);
//		$rows  	= $arr['rows'];
//		$arows 	= array();
//		foreach($rows as $k=>$rs){
//			$cont = '【'.$rs['modename'].'】单号:'.$rs['sericnum'].',日期:'.$rs['applydt'].'，<font color="'.$rs['statuscolor'].'">'.$rs['statustext'].'</font>';
//
//			$arows[] = array(
//				'cont' 		=> $cont,
//				'modename' 	=> $rs['modename'],
//				'modenum' 	=> $rs['modenum'],
//				'id' 		=> $rs['id'],
//				'count'		=> $arr['count']
//			);
//		}
//		return $arows;
//	}

/*
 * 修改后
 * */
    /**
     * 社科常态化科普项目申报
     * @var string
     */
    public static $skcth = 'project_skcth';

    /**
     * 社科研究基地年度项目申报;
     * @var string
     */
    public static $researchbase = 'project_researchbase';

    /**
     * 社科普及月项目申报;
     * @var string
     */
    public static $skpjm = 'project_skpjm';

    /**
     * 课题项目申报;
     * @var string
     */
    public static $coursetask = 'project_coursetask';

    public function initModel()
    {
        $this->settable('flow_bill');
    }

    //单据数据
    public function getbilldata($rows)
    {
        $srows	= array();
        $modeids= '0';
        foreach($rows as $k=>$rs)$modeids.=','.$rs['modeid'].'';
        $modearr= array();
        if($modeids!='0'){
            $moders = m('flow_set')->getall("`id` in($modeids)",'id,num,name,summary,statusstr');
            foreach($moders as $k=>$rs)$modearr[$rs['id']] = $rs;
        }
        $flow = m('flow:user');
        foreach($rows as $k=>$rs){
            $modename	= $rs['modename'];
            $summary	= '';
            $modenum 	= '';
            $statustext	= '记录不存在';
            $statuscolor= '#888888';
            $wdst 		= 0;
            $ishui 		= 0;
            if(isset($modearr[$rs['modeid']])){
                $mors 		= $modearr[$rs['modeid']];
                $modename 	= $mors['name'];
                $summary 	= $mors['summary'];
                $modenum 	= $mors['num'];
                $rers 		= $this->db->getone('[Q]'.$rs['table'].'', $rs['mid']);
                $summary	= $this->rock->reparr($summary, $rers);
                if($rers){
                    $wdst		 = $rers['status'];
                    if($rers['isturn']==0)$wdst=-1;
                    $nowsets	 = '<font color="#555555">'.$rs['nowcheckname'].'</font>';
                    //if($wdst!=0)$nowsets = '';
                    $ztarr 		 = $flow->getstatus($rers, $mors['statusstr'], $nowsets);
                    $statustext  = $ztarr[0];
                    $statuscolor = $ztarr[1];
                    if($rers['status']==5)$ishui = 1;
                }else{
                    $this->update('isdel=1', $rs['id']); //记录已经不存在了
                }
            }

            $status = '<font color="'.$statuscolor.'">'.$statustext.'</font>';
            if($wdst==0)$status= $statustext;
            switch ($rs['table']) {
                case self::$skcth:
                    $rs['project_name'] = $rs['activity_name'];
                    $rs['apply_type'] = '常态化科普申报';
                    break;
                case self::$researchbase:
                    $rs['project_name'] = $rs['research_base'];
                    $rs['apply_type'] = '研究基地申报';
                    break;
                case self::$skpjm:
                    $rs['apply_type'] = '普及月申报';
                    break;
                case self::$coursetask:
                    $rs['project_name'] = $rs['course_name'];
                    $rs['apply_type'] = '课题申报';
                    break;
                default:
                    ;
            }
            $apply_progress = '已完成';
            if ($rs['apply_progress']) {
                $apply_progress = $rs['apply_progress'];
            } else {
                if ($rs['status'] == 0) {
                    $apply_progress = '草稿';
                }
            }

            $srows[]= array(
                'id' 		=> $rs['mid'],
                'optdt' 	=> $rs['optdt'],
                'applydt' 	=> $rs['applydt'],
                //'name' 		=> $rs['name'],
                //'deptname' 	=> $rs['deptname'],
                'sericnum' 	=> $rs['sericnum'],
                'project_name' 	 => $rs['project_name'],
                'apply_type' 	 => $rs['apply_type'],
                'apply_progress' => $apply_progress,
                'ishui' 	=> $ishui,
                'modename' 	=> $modename,
                'modenum' 	=> $modenum,
                'summary' 	=> $summary,
                'status'	=> $status
            );
        }
        return $srows;
    }

}
