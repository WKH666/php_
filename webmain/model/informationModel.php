<?php
class informationClassModel extends Model
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

            $rs['project_title'] = '';
            if(is_array($rs)){
                if($rs['activity_name']){
                    $rs['project_title'] = $rs['activity_name'];
                }else if($rs['research_base']){
                    $rs['project_title'] = $rs['research_base'];
                }else if($rs['project_name']){
                    $rs['project_title'] = $rs['project_name'];
                }else if($rs['course_name']){
                    $rs['project_title'] = $rs['course_name'];
                }
            }

            if(!$rs['allcheckid'] && !$rs['nowcheckid']){
                $rs['name'] = '草稿';
            }
            if($rs['status'] == 1 && !$rs['nowcheckid']){
                $rs['name'] = '流程处理完成';
            }


            $srows[]= array(
                'id' 		=> $rs['mid'],
                'optdt' 	=> $rs['optdt'],
                'applydt' 	=> $rs['applydt'],
                'name' 		=> $rs['name'],
//				'deptname' 	=> $rs['deptname'],
                'sericnum' 	=> $rs['sericnum'],
                'project_name' 	=> $rs['project_title'],
                'ishui' 	=> $ishui,
                'modename' 	=> $modename,
                'modenum' 	=> $modenum,
                'summary' 	=> $summary,
                'status'	=> $status,
                'nowcourseid' => $rs['nowcourseid'],
                'row' => $rs
            );
        }
        return $srows;
    }
}
