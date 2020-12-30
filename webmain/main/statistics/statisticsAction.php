<?php
class statisticsClassAction extends Action{

    /**
     * 项目基本信息查询
     */
    public function selectbaseprojectAjax(){
        $key 	= $this->rock->post('key');
        $bdt 	= $this->rock->post('bdt');//最近$bdt个月
        $xmfl   = $this->rock->post('xmfl');//项目分类
        $sbdw   = $this->rock->post('sbdw');//申报单位
        $jjcd   = $this->rock->post('jjcd');//紧急程度
        $xmbh   = $this->rock->post('xmbh');//项目编号
        $xmmc   = $this->rock->post('xmmc');//项目名称
        $fzr    = $this->rock->post('fzr');//项目负责人
        $xmys   = $this->rock->post('xmys');//项目预算
        $time_frame = $this->rock->post('time_frame');//时间范围
        $modeid = $this->rock->post('modeid');//申报类型
        $alonetable = $this->rock->post('alonetable');//根据单独的表查询 project_apply或project_sx_apply

        $where='';
        if(!isempt($key))$where.=" and (b.`name` like '%$key%' or b.`deptname` like '%$key%' or a.sericnum like '$key%')";
        //时间范围
        if($time_frame!=""){
            list($start_time,$end_time) = explode(',', $time_frame);
            $where.=" and c.project_apply_time between '".$start_time."' and '".$end_time."'";
            unset($start_time,$end_time);
        }
        switch($modeid){
            case 1:
                $modeid = 54;
                $where.=' and a.modeid='.$modeid;
                break;
            case 2:
                $modeid = 57;
                $where.=' and a.modeid='.$modeid;
                break;
            default:$modeid ='1=1';
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

        //根据单独的表查询 project_apply或project_sx_apply
        if($alonetable!=''){
            $where.=" and a.table='$alonetable'";
        }
        $table = '`[Q]flow_bill` a left join `[Q]admin` b on a.uid=b.id left join `whole_projects` c on a.mid=c.id  left join `[Q]flow_course` fc on fc.id=a.nowcourseid';
        $where = " a.isdel=0 $where";
        $fields = 'a.id as flow_id,a.table as mtype,a.modeid,a.optid,a.nowcheckname,a.status as bill_status,a.allcheckid,a.nowcheckid,a.nowcourseid,a.optid,b.name,b.deptname,c.id,c.num,c.project_name,c.status as cst,c.isturn,c.optname,c.project_head,c.project_apply_time,c.project_select,c.project_xingzhi,c.project_ku,c.isturn,fc.name as flowname,c.exigence_status,c.process_state,c.project_is_guidang,c.project_number,c.project_yushuan';
        $order = 'a.optdt desc';
        $rows = $this->limitRows($table,$fields,$where,$order);
        $rows['totalCount'] = count($rows['rows']);
        unset($rows['sql'], $rows['total']);
        if($rows['totalCount'] == 0){
            exit('暂无数据');
        }
        return $this->returnjson($rows);
    }


	public function getcensusAjax(){
		$time_frame = $this->post('time_frame');//时间范围
		$dept_name = $this->post('dept_name');//单位部门名称
		$project_select = $this->post('project_select');//项目类别
		$project_year = $this->post('project_year');//项目年份
		$project_ku = $this->post('project_ku');//所在库
		$project_xingzhi = $this->post('project_xingzhi');//项目性质
		//$process_state = $this->post('process_state');//进程状态
		$achievements = $this->post('achievements');//考评状态
		$bdt 	= $this->rock->post('bdt');//最近$bdt个月
		$childgroupwhere = $this->rock->post('childgroupwhere');//子表的分类条件
		$execldown		= $this->request('execldown');//是否导出
		$where = '';//查询条件
		//时间范围
		if($time_frame!=""){
			list($start_time,$end_time) = explode(',', $time_frame);
			$where.=" and c.project_apply_time between '".$start_time."' and '".$end_time."'";
			unset($start_time,$end_time);
		}
		
		//最近几个月
		if($bdt!=''){
			$start_time = date('Y-m-01', strtotime('-'.$bdt.' month'));
			$end_time = date('Y-m-d', time());
			$where.=" and c.project_apply_time between '".$start_time."' and '".$end_time."'";
			unset($start_time,$end_time);
		}
		
		if($project_xingzhi!="")$where.=" and TRIM(c.project_xingzhi)='".trim($project_xingzhi)."'";
		if($dept_name==""){//单位
			$where.=" and TRIM(b.deptname) in (select TRIM(name) from pl_dept)";
		}else{
			$where.=" and TRIM(b.deptname)='".trim($dept_name)."'";
		}
		
		if($project_select==""){//项目分类
			$where.=" and TRIM(c.project_select) in (select TRIM(name) from pl_option where pid=313)";
		}else{
			$where.=" and TRIM(c.project_select)='".trim($project_select)."'";
		}
		
		if($project_year!="")$where.=" and TRIM(c.project_year)=".trim($project_year);//项目年份
		if($project_ku==""){//项目库状态
			$where.=" and TRIM(c.project_ku) in (select trim(name) from pl_option where pid=285)";
		}else{
			$where.=" and TRIM(c.project_ku)='".trim($project_ku)."'";
		}
//		if($process_state==""){//项目进程状态
//			$where.=" and (TRIM(c.process_state) in (select name from pl_option where id>=330 and id<=349) or TRIM(fc.name) in (select name from pl_option where id>=330 and id<=349))";
//		}else{
//			$where.=" and TRIM(c.process_state)='".trim($process_state)."'";
//		}
		if($achievements!="")$where.=" and c.is_evaluation=".$achievements;//是否已考评

		
		$table = '`[Q]flow_bill` a left join `[Q]admin` b on a.uid=b.id left join `[Q]project_apply` c on a.mid=c.id left join `[Q]flow_course` fc on fc.id=a.nowcourseid ';
		$fields = 'b.deptname,c.project_ku,c.project_year,c.project_select,case when c.is_evaluation=0 then "未考评" else "已考评" end as evaluation,c.project_xingzhi,count(c.id) as project_count,c.project_apply_time,case when c.project_ku in ("申报中","预备库") then fc.name else c.process_state end as process_state,c.project_apply_time,sum(c.project_yushuan) as project_yushuan';
		$where = 'a.isdel=0 '.$where;
		$order = 'b.deptname,a.optdt desc';
		$grouparr = array();//分组条件
		switch ($childgroupwhere) {
			case 1:
				$arr['group'] = 'deptname';//根据单位分组
				break;
			case 2:
				$arr['group'] = 'process_state';//根据进程状态分组
				break;
			case 3:
				$arr['group'] = 'project_xingzhi';//根据库性质分组
				break;
			default:
				$arr['group'] = 'deptname,project_ku,project_year,project_select,evaluation,project_xingzhi,process_state';
				break;
		}
		$arr = $this->limitRows($table,$fields,$where,$order,$arr);
		$arr['totalCount'] = count($arr['rows']);
		unset($arr['sql'],$arr['total']);
		if($arr['totalCount'] == 0){
			exit('暂无数据');
		}
		if($execldown == 'true'){
			$this->exceldown($arr);
			return;
		}
		return $this->returnjson($arr);
	}

	public function exceldown($arr)
	{
		$fields = explode(',', $this->post('excelfields','',1));
		$header = explode(',', $this->post('excelheader','',1));
		$title	= $this->post('exceltitle','',1);
		$rows	= $arr['rows'];
		$headArr	= array();
		for($i=0; $i<count($fields); $i++){
			$headArr[$fields[$i]] = $header[$i];
		}
		$url 		= c('html')->execltable($title, $headArr, $rows);
		$this->returnjson(array(
			'url'		=> $url, 
			'totalCount'=> $arr['totalCount'],
			'downCount' => count($rows)
		));
	}

	//end
}