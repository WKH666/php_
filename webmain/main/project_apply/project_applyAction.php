<?php
class project_applyClassAction extends Action
{

	public function initAction()
	{
		$this->admin=m('admin');
		$this->flowbill=m('flowbill');
		$this->flowcourse=m('flowcourse');
	}


	/*public function project_applyafter($table,$rows)
	{

		foreach($rows as $k=>$rs){
			//去除两边空格
			$rs['project_xingzhi'] = trim($rs['project_xingzhi']);//是否为库项目
			$rs['project_ku'] = trim($rs['project_ku']);//项目状态

			if($rs['cst']==0 && $rs['isturn']!=0){
				$statustext='待<font color="#5a5a5a">'.$rs['nowcheckname'].'</font>审核';
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
				//$rows[$k]['caoz'] = '<a onclick="check_project('.$rs['num'].','.$rs['id'].','.$project_name.')">查看</a>';
				$rows[$k]['caoz'] = '<a onclick="check_project('.$num.','.$rs['id'].','.$project_name.')">查看</a>';
				$rows[$k]['caoz'].= '<span style="padding:5px;">|</span>';
				$rows[$k]['caoz'].= '<a onclick="edit_project('.$num.','.$rs['id'].','.$project_name.')">编辑</a>';
				$rows[$k]['caoz'].= '<span style="padding:5px;">|</span>';
				$rows[$k]['caoz'].= '<a onclick="delc('.$rs['flow_id'].')">删除</a>';
			//}else if($rs['nowcheckid']==$this->adminid && $rs['cst']==0){
			//debug 这里修改为可以多人处理
			}else if(in_array($this->adminid, explode(',', $rs['nowcheckid'])) && $rs['cst']==0){
				//需要审核
				$rows[$k]['caoz'] = '<a onclick="check_project('.$num.','.$rs['id'].','.$project_name.')">查看</a>';
				$rows[$k]['caoz'].= '<span style="padding:5px;">|</span>';
				$rows[$k]['caoz'].= '<a onclick="chuli_project('.$num.','.$rs['id'].','.$project_name.')">处理</a>';
			}else if($rs['optid']==$this->adminid && $rs['bill_status']==2){
				$rows[$k]['caoz'] = '<a onclick="check_project('.$num.','.$rs['id'].','.$project_name.')">查看</a>';
				$rows[$k]['caoz'].= '<span style="padding:5px;">|</span>';
				$rows[$k]['caoz'].= '<a onclick="edit_project('.$num.','.$rs['id'].','.$project_name.')">编辑</a>';
			}else{
				$rows[$k]['caoz'] = '<a onclick="check_project('.$num.','.$rs['id'].','.$project_name.')">查看</a>';
			}

		}
		return array('rows'=>$rows);
	}*/

    public function project_applybefore($table)
    {
        $lx 	= $this->post('atype');
        $sericnum 	= $this->post('sericnum');
        $project_name 	= $this->post('project_name');
        $apply_type 	= $this->post('apply_type');
        $uid 	= $this->adminid;
        $where	= "and a.uid=$uid";
        $flowbill_sheke_model = m('projectapply');

        //获得当前登录者的职业
        $now_user_ranking = $this->getsession('adminranking');
        if ($now_user_ranking == '申报者') {
            /*申报者*/
            $where	.= " and a.status=0";//状态：0为处理中 2为退回修改
        }else {
            /*社科管理员*/
            $where	.= " and a.status=0";
        }

        //查询
        if ($sericnum) {
            $where .= " and a.sericnum like '%$sericnum%'";
        }
        if ($project_name) {
            $where .= " and ( d.activity_name like '%$project_name%' or e.research_base like '%$project_name%' or f.project_name like '%$project_name%' )";
        }
        if ($apply_type) {
            if ($apply_type == '常态化科普申报') {
                $apply_type = $flowbill_sheke_model::$skcth;
            } elseif ($apply_type == '研究基地申报') {
                $apply_type = $flowbill_sheke_model::$researchbase;
            } elseif ($apply_type == '普及月申报') {
                $apply_type = $flowbill_sheke_model::$skpjm;
            } elseif($apply_type == '课题申报') {
                $apply_type = $flowbill_sheke_model::$coursetask;
            }
            $where .= " and a.table='$apply_type'";
        }

        $table = '`[Q]flow_bill` a left join `[Q]admin` b on a.uid=b.id';
        $table .= " left join `[Q]flow_course` c on c.id=a.nowcourseid";
        $table .= " left join `[Q]" . $flowbill_sheke_model::$skcth . "` d on d.id=a.mid";
        $table .= " left join `[Q]" . $flowbill_sheke_model::$researchbase . "` e on e.id=a.mid";
        $table .= " left join `[Q]" . $flowbill_sheke_model::$skpjm . "` f on f.id=a.mid";
        $table .= " left join `[Q]" . $flowbill_sheke_model::$coursetask . "` g on g.id=a.mid";
        return array(
            'table' => $table,
            'where' => " and a.isdel=0 $where",
            'fields'=> 'a.*,c.name as apply_progress,d.activity_name,e.research_base,f.project_name,g.course_name',
            'order' => 'a.optdt desc'
        );

    }

    public function project_applyafter($table,$rows)
    {
        $rows = m('projectapply')->getbilldata($rows);
        return array(
            'rows'		=> $rows,
            'flowarr' 	=> m('mode')->getmodemyarr($this->adminid),
        );
    }


	/**
	 * 获取统计数据
	 */
	public function project_apply_countAjax(){
		$arr = m('flow:project_apply')->getStatistics();
		$this->returnjson($arr);
	}



	/**
	 * 获取详细流程信息
	 */
	public function getflowdetailAjax(){
		$resultarr = array();//返回的级联数组
		//获取全部的库状态
		$library_state = m('option')->getall('pid=285','id,name');
		//var_dump($library_state);
		$this->returnjson($library_state);
	}

	/**
	 * 获取处理流程
	 */
	public function getprojectflowinfoAjax(){
		$mid = $this->post('mid');//项目id
		$mtype = $this->post('mtype');//项目类型
		//获取处理流程
		$flowarr = m('flow')->getdatalog("$mtype", $mid, 0)['flowinfor'];
		//$flowarr = m('flow:project_apply')->getprojectflow();
		$this->returnjson($flowarr);
	}

	public function deleteAjax(){
		$id=$this->rock->post('id');
		$flow_bill=m('flow_bill')->getone('id='.$id);

		$is_admin = m('admin')->getone('id='.$this->adminid,'is_admin')['is_admin'];
		if($flow_bill['uid']==$this->adminid || $this->adminid==1 || $is_admin==1){
			m('flow_bill')->delete($id);
			//删除数据库
			$arr = array('id'=>$id,'success'=>true,'msg'=>'删除成功');
		}else{
			$arr = array('id'=>$id,'success'=>true,'msg'=>'非正常操作');
		}
		$this->returnjson($arr);
	}

}
