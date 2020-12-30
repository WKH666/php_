<?php
/**
 * 开发者gzj
 * 项目库模块
 */
class project_manageClassAction extends Action
{
	/**
	 * 保存更改状态记录
	 */
	public function saveAjax(){
		$mid = $this->post('mid');//项目id
		$mtype = $this->post('mtype');//项目模块
		$update_status = $this->post('upldate_status');//状态
		$remark = $this->post('remark');//备注
		$file_ids = $this->post('file_ids');//文件ids
		$carryover_yeas = $this->post('carryover_yeas');//项目年份
		$theTime = date('Y-m-d H:i:s', time());//更新时间
		
		if($mid == '' && $mid == 0)exit('项目id为空');
		if($update_status == '')exit('项目状态不能为空');
		if($remark == '')exit('备注不能为空');
		
		$addinfo = m('mf_status_log')->insert(array(
			'mid' => $mid,
			'mtype' => $mtype,
			'update_status' => $update_status,
			'remark' => $remark,
			'file_ids' => $file_ids,
			'carryover_yeas' => $carryover_yeas,
			'update_time' => $theTime
		));
		
		//更新当前项目的流程状态
		$pro_up = m('project_apply')->update(array('process_state'=>$update_status),'id='.$mid);
		//对相应上传的文件赋予项目id
		$idarr = explode(',', $file_ids);
		foreach ($idarr as $k => $file_id) {
			m('file')->update(array('mid'=>$mid,'mtype'=>$mtype),"id=$file_id");
		}
		if($addinfo && $pro_up){
			$info =array(
				'id' => $addinfo,
				'success' => true,
				'msg' => '更新成功'
			);
		}else{
			$info =array(
				'id' => $addinfo,
				'success' => false,
				'msg' => '更新失败'
			);
		}
		$this->returnjson($info);
	}
	
	
	public function project_applyafter($table,$rows){
		foreach($rows as $k=>$rs){
			//去除两边空格
			$rs['project_xingzhi'] = trim($rs['project_xingzhi']);//是否为库项目
			$rs['project_ku'] = trim($rs['project_ku']);//项目状态
			$project_name="'".$rs['project_name']."'";
			//未提交
			if($rs['project_xingzhi']=='非库项目'){
				//查看 编辑 删除
				$rows[$k]['caoz'] = '<a onclick="check_project(\''.$rs['mtype'].'\','.$rs['id'].','.$project_name.')">查看</a>';
				$rows[$k]['caoz'].= '<span style="padding:5px;">|</span>';
				$rows[$k]['caoz'].= '<a onclick="edit_project(\''.$rs['mtype'].'\','.$rs['id'].','.$project_name.')">编辑</a>';
				$rows[$k]['caoz'].= '<span style="padding:5px;">|</span>';
				$rows[$k]['caoz'].= '<a onclick="manage(\''.$rs['mtype'].'\','.$rs['id'].','.$project_name.')">管理</a>';
				$rows[$k]['caoz'].= '<span style="padding:5px;">|</span>';
				$rows[$k]['caoz'].= '<a onclick="del(this)">删除</a>';
			}else if($rs['project_xingzhi']=='库项目'){
				//需要审核
				$rows[$k]['caoz'] = '<a onclick="check_project(\''.$rs['mtype'].'\','.$rs['id'].','.$project_name.')">查看</a>';
				$rows[$k]['caoz'].= '<span style="padding:5px;">|</span>';
				$rows[$k]['caoz'].= '<a onclick="edit_project(\''.$rs['mtype'].'\','.$rs['id'].','.$project_name.')">编辑</a>';
				$rows[$k]['caoz'].= '<span style="padding:5px;">|</span>';
				$rows[$k]['caoz'].= '<a onclick="manage(\''.$rs['mtype'].'\','.$rs['id'].','.$project_name.')">管理</a>';
				if($rs['project_is_guidang']!=1){
					$rows[$k]['caoz'].= '<span style="padding:5px;">|</span>';
					$rows[$k]['caoz'].= '<a onclick="edit_status('.$rs['id'].',\''.$rs['mtype'].'\','.$project_name.')">状态</a>';
				}
			}
			
		}
		return array('rows'=>$rows);
	}
	
	
	/**
	 * 项目库信息导出
	 */
//	public function todownexcelAjax(){
//		list($table,$where,$fields,$order) = m('flow:project_apply')->flowbillwhere();
//		$excelinfo = array();//导出excel的内容
//		array_push($excelinfo,array(
//			'title'=>'项目库列表',
//			'headArr'=>array(
//				'project_name' => '项目名称',
//		        'project_yushuan' => '预算（万元）',
//		        'deptname' => '申报单位',
//		        'project_head' => '项目负责人',
//		        'project_head_phone' => '联系电话',
//		        'project_z_bumeng' => '主管部门',
//		        'project_apply_time' => '申报时间',
//		        'project_year' => '实施年度',
//		        'project_z_bumeng' => '业务主管部门',
//		        'project_select' => '分类',
//		        'process_state' => '流程状态',
//		        'project_xingzhi' => '项目性质',
//		        'paiming' => '排名',
//				'lunzhengtime' => '论证时间',
//				'zhuanjiaxiaozu' => '专家小组',
//				'lunzhengjielun' => '论证结论',
//				'piwen' => '批文',
//				'jingfeixiangmuchuchu' => '经费项目出处',
//				'jingfeibianhao' => '经费编号',
//				'chuku' => '出库',
//				'beizhu' => '备注'
//			),
//			'rows'=>$this->limitRows($table,$fields,$where,$order)['rows']
//		));
//		$this->exceldown($arr);
//		return;
//	}

	//end
}