<?php
class flowbill_shekeClassModel extends Model
{
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
