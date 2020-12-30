<?php
/*@ liang
@ 根据流程及申报书修改代码段
@ 时间：2017/4/26 9：07
@ 流程简化
@ 模块：project_apply 申报流程模块 独立模块

@ 20170528
@注释了网评专家组的代码
*/

class flow_project_applyClassModel extends flowModel
{


	protected function flowbillwhere($uid, $lx)
	{
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
		$modeid = $this->rock->post('modeid');//申报类型
		$alonetable = $this->rock->post('alonetable');//根据单独的表查询 project_apply或project_sx_apply

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
		//下属
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
			$where.=" and a.status=5";
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

		//根据单独的表查询 project_apply或project_sx_apply
		if($alonetable!=''){
			$where.=" and a.table='$alonetable'";
		}









		return array(
			'table' => '`[Q]flow_bill` a left join `[Q]admin` b on a.uid=b.id left join `whole_projects` c on a.mid=c.id  left join `[Q]flow_course` fc on fc.id=a.nowcourseid',
			'where' => " and a.isdel=0 $where",
			'fields'=> 'a.id as flow_id,a.table as mtype,a.modeid,a.optid,a.nowcheckname,a.status as bill_status,a.allcheckid,a.nowcheckid,a.nowcourseid,a.optid,b.name,b.deptname,c.id,c.num,c.project_name,c.status as cst,c.isturn,c.optname,c.project_head,c.project_apply_time,c.project_select,c.project_xingzhi,c.project_ku,c.isturn,fc.name as flowname,c.exigence_status,c.process_state,c.project_is_guidang,c.project_number,c.project_yushuan',
			'order' => 'a.optdt desc'
		);


	}





	protected function flowdatalog($arr){

	//var_dump($arr);
		//当状态stype为word，只保留输出表单内容
		$stype	= $this->rock->post('stype');//状态


/*		职能部门专家小组
		注释时间 @2017/4/26
		注释人 @liang
		注释原因 流程简化*/
/*		if(!empty($arr['flowinfor']['nowcourse']['id']) && $arr['flowinfor']['nowcourse']['id']=='54'){



			$arr['flowinfor']['checkfields']['project_z_zhuanjia']['inputstr']='<table width="95%" border="0" cellpadding="0" cellspacing="0" id="project_z_zhuanjia" class="ke-zeroborder">
		  <tbody>
		    <tr>
		      <td width="10%" class="ys3">序号</td>
		      <td class="ys3"><span ><font color="red">*</font></span>姓名</td>
		      <td class="ys3"><font color="red">*</font>单位</td>
		      <td class="ys3"><font color="red">*</font>职务/职称</td>
		      <td class="ys3">备注</td>
		    </tr>
		    <tr>
		      <td class="ys3"><input class="inputs" style="text-align:center" readonly="" temp="xuhao" type="text" value="1" name="xuhao0_0">
		        <input value="0" type="hidden" name="sid0_0"></td>
		      <td class="ys3"><input class="inputs" type="text" value="" name="project_z_n0_0"></td>
		      <td class="ys3"><input class="inputs" type="text" value="" name="project_z_d0_0"></td>
		      <td class="ys3"><input class="inputs" type="text" value="" name="project_z_zw0_0"></td>
		      <td class="ys3"><input class="inputs" type="text" value="" name="project_z_bz0_0"></td>

		    </tr>
		    <tr>
		      <td class="ys3"><input class="inputs" style="text-align:center" readonly="" temp="xuhao" type="text" value="2" name="xuhao0_1">
		        <input value="0" type="hidden" name="sid0_1"></td>
		      <td class="ys3"><input class="inputs" type="text" value="" name="project_z_n0_1"></td>
		      <td class="ys3"><input class="inputs" type="text" value="" name="project_z_d0_1"></td>
		      <td class="ys3"><input class="inputs" type="text" value="" name="project_z_zw0_1"></td>
		      <td class="ys3"><input class="inputs" type="text" value="" name="project_z_bz0_1"></td>

		    </tr>
		    <tr>
		      <td class="ys3"><input class="inputs" style="text-align:center" readonly="" temp="xuhao" type="text" value="3" name="xuhao0_2">
		        <input value="0" type="hidden" name="sid0_2"></td>
		      <td class="ys3"><input class="inputs" type="text" value="" name="project_z_n0_2"></td>
		      <td class="ys3"><input class="inputs" type="text" value="" name="project_z_d0_2"></td>
		      <td class="ys3"><input class="inputs" type="text" value="" name="project_z_zw0_2"></td>
		      <td class="ys3"><input class="inputs" type="text" value="" name="project_z_bz0_2"></td>

		    </tr>
		    <tr>
		      <td class="ys3"><input class="inputs" style="text-align:center" readonly="" temp="xuhao" type="text" value="4" name="xuhao0_3">
		        <input value="0" type="hidden" name="sid0_3"></td>
		      <td class="ys3"><input class="inputs" type="text" value="" name="project_z_n0_3"></td>
		      <td class="ys3"><input class="inputs" type="text" value="" name="project_z_d0_3"></td>
		      <td class="ys3"><input class="inputs" type="text" value="" name="project_z_zw0_3"></td>
		      <td class="ys3"><input class="inputs" type="text" value="" name="project_z_bz0_3"></td>

		    </tr>
                <tr>
		      <td class="ys3"><input class="inputs" style="text-align:center" readonly="" temp="xuhao" type="text" value="5" name="xuhao0_3">
		        <input value="0" type="hidden" name="sid0_3"></td>
		      <td class="ys3"><input class="inputs" type="text" value="" name="project_z_n0_4"></td>
		      <td class="ys3"><input class="inputs" type="text" value="" name="project_z_d0_4"></td>
		      <td class="ys3"><input class="inputs" type="text" value="" name="project_z_zw0_4"></td>
		      <td class="ys3"><input class="inputs" type="text" value="" name="project_z_bz0_4"></td>

		    </tr>
                <tr>
		      <td class="ys3"><input class="inputs" style="text-align:center" readonly="" temp="xuhao" type="text" value="6" name="xuhao0_3">
		        <input value="0" type="hidden" name="sid0_3"></td>
		      <td class="ys3"><input class="inputs" type="text" value="" name="project_z_n0_5"></td>
		      <td class="ys3"><input class="inputs" type="text" value="" name="project_z_d0_5"></td>
		      <td class="ys3"> <input class="inputs" type="text" value="" name="project_z_zw0_5"></td>
		      <td class="ys3"><input class="inputs" type="text" value="" name="project_z_bz0_5"></td>

		    </tr>
                  <tr>
		      <td class="ys3"><input class="inputs" style="text-align:center" readonly="" temp="xuhao" type="text" value="7" name="xuhao0_3">
		        <input value="0" type="hidden" name="sid0_3"></td>
		      <td class="ys3"><input class="inputs" type="text" value="" name="project_z_n0_6"></td>
		      <td class="ys3"><input class="inputs" type="text" value="" name="project_z_d0_6"></td>
		      <td class="ys3"><input class="inputs" type="text" value="" name="project_z_zw0_6"></td>
		      <td class="ys3"><input class="inputs" type="text" value="" name="project_z_bz0_6"></td>

		    </tr>
                  <tr>
		      <td class="ys3"><input class="inputs" style="text-align:center" readonly="" temp="xuhao" type="text" value="8" name="xuhao0_3">
		        <input value="0" type="hidden" name="sid0_3"></td>
		      <td class="ys3"><input class="inputs" type="text" value="" name="project_z_n0_7"></td>
		      <td class="ys3"><input class="inputs" type="text" value="" name="project_z_d0_7"></td>
		      <td class="ys3"><input class="inputs" type="text" value="" name="project_z_zw0_7"></td>
		      <td class="ys3"><input class="inputs" type="text" value="" name="project_z_bz0_7"></td>

		    </tr>
                  <tr>
		      <td class="ys3"><input class="inputs" style="text-align:center" readonly="" temp="xuhao" type="text" value="9" name="xuhao0_3">
		        <input value="0" type="hidden" name="sid0_3"></td>
		      <td class="ys3"><input class="inputs" type="text" value="" name="project_z_n0_8"></td>
		      <td class="ys3"><input class="inputs" type="text" value="" name="project_z_d0_8"></td>
		      <td class="ys3"><input class="inputs" type="text" value="" name="project_z_zw0_8"></td>
		      <td class="ys3"><input class="inputs" type="text" value="" name="project_z_bz0_8"></td>

		    </tr>
		  </tbody>
		</table>';
		$arr['flowinfor']['checkfields']['project_z_zhuanjia']['showinpus']='1';
		$arr['flowinfor']['checkfields']['project_z_zhuanjia']['name']='参与专家组名单';


		}*/

		//校专家组 修改从网评中读取
		if(!empty($arr['flowinfor']['nowcourse']['id']) && $arr['flowinfor']['nowcourse']['id']=='57'){



			$arr['flowinfor']['checkfields']['project_x_zhuanjia']['inputstr']='<table width="95%" border="0" cellpadding="0" cellspacing="0" id="project_x_zhuanjia" class="ke-zeroborder">
		  <tbody>
		    <tr>
		      <td width="10%" class="ys3">序号</td>
		      <td class="ys3"><span ><font color="red">*</font></span>姓名</td>
		      <td class="ys3"><font color="red">*</font>单位</td>
		      <td class="ys3"><font color="red">*</font>职务/职称</td>
		      <td class="ys3">备注</td>
		    </tr>
		    <tr>
		      <td class="ys3"><input class="inputs" style="text-align:center" readonly="" temp="xuhao" type="text" value="1" name="xuhao0_0">
		        <input value="0" type="hidden" name="sid0_0"></td>
		      <td class="ys3"><input class="inputs" type="text" value="" name="project_z_n0_0"></td>
		      <td class="ys3"><input class="inputs" type="text" value="" name="project_z_d0_0"></td>
		      <td class="ys3"><input class="inputs" type="text" value="" name="project_z_zw0_0"></td>
		      <td class="ys3"><input class="inputs" type="text" value="" name="project_z_bz0_0"></td>
		      
		    </tr>
		    <tr>
		      <td class="ys3"><input class="inputs" style="text-align:center" readonly="" temp="xuhao" type="text" value="2" name="xuhao0_1">
		        <input value="0" type="hidden" name="sid0_1"></td>
		      <td class="ys3"><input class="inputs" type="text" value="" name="project_z_n0_1"></td>
		      <td class="ys3"><input class="inputs" type="text" value="" name="project_z_d0_1"></td>
		      <td class="ys3"><input class="inputs" type="text" value="" name="project_z_zw0_1"></td>
		      <td class="ys3"><input class="inputs" type="text" value="" name="project_z_bz0_1"></td>
		      
		    </tr>
		    <tr>
		      <td class="ys3"><input class="inputs" style="text-align:center" readonly="" temp="xuhao" type="text" value="3" name="xuhao0_2">
		        <input value="0" type="hidden" name="sid0_2"></td>
		      <td class="ys3"><input class="inputs" type="text" value="" name="project_z_n0_2"></td>
		      <td class="ys3"><input class="inputs" type="text" value="" name="project_z_d0_2"></td>
		      <td class="ys3"><input class="inputs" type="text" value="" name="project_z_zw0_2"></td>
		      <td class="ys3"><input class="inputs" type="text" value="" name="project_z_bz0_2"></td>
		      
		    </tr>
		    <tr>
		      <td class="ys3"><input class="inputs" style="text-align:center" readonly="" temp="xuhao" type="text" value="4" name="xuhao0_3">
		        <input value="0" type="hidden" name="sid0_3"></td>
		      <td class="ys3"><input class="inputs" type="text" value="" name="project_z_n0_3"></td>
		      <td class="ys3"><input class="inputs" type="text" value="" name="project_z_d0_3"></td>
		      <td class="ys3"><input class="inputs" type="text" value="" name="project_z_zw0_3"></td>
		      <td class="ys3"><input class="inputs" type="text" value="" name="project_z_bz0_3"></td>
		      
		    </tr>
                <tr>
		      <td class="ys3"><input class="inputs" style="text-align:center" readonly="" temp="xuhao" type="text" value="5" name="xuhao0_3">
		        <input value="0" type="hidden" name="sid0_3"></td>
		      <td class="ys3"><input class="inputs" type="text" value="" name="project_z_n0_4"></td>
		      <td class="ys3"><input class="inputs" type="text" value="" name="project_z_d0_4"></td>
		      <td class="ys3"><input class="inputs" type="text" value="" name="project_z_zw0_4"></td>
		      <td class="ys3"><input class="inputs" type="text" value="" name="project_z_bz0_4"></td>
		      
		    </tr>
                <tr>
		      <td class="ys3"><input class="inputs" style="text-align:center" readonly="" temp="xuhao" type="text" value="6" name="xuhao0_3">
		        <input value="0" type="hidden" name="sid0_3"></td>
		      <td class="ys3"><input class="inputs" type="text" value="" name="project_z_n0_5"></td>
		      <td class="ys3"><input class="inputs" type="text" value="" name="project_z_d0_5"></td>
		      <td class="ys3"><input class="inputs" type="text" value="" name="project_z_zw0_5"></td>
		      <td class="ys3"><input class="inputs" type="text" value="" name="project_z_bz0_5"></td>
		      
		    </tr>
                  <tr>
		      <td class="ys3"><input class="inputs" style="text-align:center" readonly="" temp="xuhao" type="text" value="7" name="xuhao0_3">
		        <input value="0" type="hidden" name="sid0_3"></td>
		      <td class="ys3"><input class="inputs" type="text" value="" name="project_z_n0_6"></td>
		      <td class="ys3"><input class="inputs" type="text" value="" name="project_z_d0_6"></td>
		      <td class="ys3"><input class="inputs" type="text" value="" name="project_z_zw0_6"></td>
		      <td class="ys3"><input class="inputs" type="text" value="" name="project_z_bz0_6"></td>
		      
		    </tr>
                  <tr>
		      <td class="ys3"><input class="inputs" style="text-align:center" readonly="" temp="xuhao" type="text" value="8" name="xuhao0_3">
		        <input value="0" type="hidden" name="sid0_3"></td>
		      <td class="ys3"><input class="inputs" type="text" value="" name="project_z_n0_7"></td>
		      <td class="ys3"><input class="inputs" type="text" value="" name="project_z_d0_7"></td>
		      <td class="ys3"><input class="inputs" type="text" value="" name="project_z_zw0_7"></td>
		      <td class="ys3"><input class="inputs" type="text" value="" name="project_z_bz0_7"></td>
		      
		    </tr>
                  <tr>
		      <td class="ys3"><input class="inputs" style="text-align:center" readonly="" temp="xuhao" type="text" value="9" name="xuhao0_3">
		        <input value="0" type="hidden" name="sid0_3"></td>
		      <td class="ys3"><input class="inputs" type="text" value="" name="project_z_n0_8"></td>
		      <td class="ys3"><input class="inputs" type="text" value="" name="project_z_d0_8"></td>
		      <td class="ys3"><input class="inputs" type="text" value="" name="project_z_zw0_8"></td>
		      <td class="ys3"><input class="inputs" type="text" value="" name="project_z_bz0_8"></td>
		      
		    </tr>
		  </tbody>
		</table>';
		$arr['flowinfor']['checkfields']['project_x_zhuanjia']['showinpus']='1';
		$arr['flowinfor']['checkfields']['project_x_zhuanjia']['name']='校级参与专家组名单';


		}

		if($stype=='word'){

			$arr['word_name']=$arr['modename'];

			$arr['modename']='';
			$arr['title']='';
			$arr['readarr']="";
			$arr['logarr']="";
			$arr['isedit']="";
			$arr['isdel']="";
			$arr['isflow']="";
			$arr['flowinfor']="";
		}
		$arr['title']='';
		return $arr;

	}

	protected function flowsubmit($na, $sm){



	}

	/*public function isreadqx()
	{
		$bo = false;
		if($this->uid==$this->adminid && $this->adminid>0)$bo=true;
		if(!$bo && $this->isflow==1){
			if($this->billrs){
				$allcheckid = $this->billrs['allcheckid'];
				if(contain(','.$allcheckid.',',','.$this->adminid.','))$bo = true;
			}
		}
		if(!$bo){
			if($this->urs && contain($this->urs['superpath'],'['.$this->adminid.']'))$bo = true;
		}
		if(!$bo)$bo = $this->flowisreadqx();
		if(!$bo){
			$where 	= $this->viewmodel->viewwhere($this->moders, $this->adminid, $this->flowviewufieds);
			$tos 	= $this->rows("`id`='$this->id'  $where ");
			if($tos>0)$bo=true;
		}
		if(!$bo)$this->echomsg('无权限查看模块['.$this->modenum.'.'.$this->modename.']'.$this->uname.'的数据，'.c('xinhu')->helpstr('cxqx').'');
	}*/


	//流程申报书中字段自定义赋值
	public function flowrsreplace($rs){



//		project_name
//		project_number
//		base_deptname
//		project_year
//		project_apply_time
//
//
//		&nbsp;
		if(!empty($rs['base_deptname'])){
			$rs['base_deptname']=$this->str_add($rs['base_deptname'],10);
			$rs['project_z_bumeng']=$this->str_add($rs['project_z_bumeng'],10);
			$rs['project_year']=$this->str_add($rs['project_year'],10);
			$rs['project_name']=$this->str_add($rs['project_name'],18);
			//@流程简化 项目编号无下划线
			$rs['project_number']=$this->str_add($rs['project_number'],18);
            $rs['project_yushuan']=$this->str_add($rs['project_yushuan'],12);
		}

/*		业务主管部门对应申报书的时间
		注释时间 @2017/4/26
		注释人 @liang
		注释原因 流程简化
		备注 @同时去除元素表单中的数据
			@详情页面的数据*/
/*		if(!empty($rs['project_zhineng_time'])){
				$project_zhineng_time_array= explode('-',$rs['project_zhineng_time']);

				//y业务主管部门时间
				$rs['zhineng_year']=$project_zhineng_time_array[0];
				$rs['zhineng_month']=$project_zhineng_time_array[1];
				$rs['zhineng_day']=$project_zhineng_time_array[2];

		}*/

/*		校长办公会审批结果
		注释时间 @2017/4/26
		注释人 @liang
		注释原因 流程简化
		备注 @同时去除元素表单中的数据
			@详情页面的数据*/
/*		if(!empty($rs['project_x_time'])){

				$project_x_time_array= explode('-',$rs['project_x_time']);


				$rs['x_year']=$project_x_time_array[0];
				$rs['x_month']=$project_x_time_array[1];
				$rs['x_day']=$project_x_time_array[2];

		}*/

/*		职能部门专家组的模板标签
		注释时间 @2017/4/26
		注释人 @liang
		注释原因 流程简化
		备注 @同时去除元素表单中的数据
			@详情页面的数据
			@多余数据库表 m_project_z*/
		/*$z_table=m('m_project_z')->getall('mid='.$rs['id'],'*','sort ASC');

		if(!empty($z_table)){

			$table_html="";
			foreach($z_table as $k=>$value){
				$table_html.='<tr>';
				$table_html.='<td style="padding:3px;border-top:1px #000000 solid;" align="center">'.$value['sort'].'</td>';
				$table_html.='<td style="padding:3px;border:1px #000000 solid;border-bottom:none;" align="center">'.$value['project_z_n'].'</td>';
				$table_html.='<td style="padding:3px;border:1px #000000 solid;border-bottom:none;" align="center">'.$value['project_z_d'].'</td>';
				$table_html.='<td style="padding:3px;border:1px #000000 solid;border-bottom:none;" align="center">'.$value['project_z_zw'].'</td>';
				$table_html.='<td style="padding:3px;border:1px #000000 solid;border-bottom:none;" align="center">'.$value['project_z_qm'].'</td>';
				$table_html.='<td style="padding:3px;border:1px #000000 solid;border-bottom:none;" align="center">'.$value['project_z_bz'].'</td>';
				$table_html.='</tr>';
			}
			$table_top='';
			$table_top='<table width="100%" class="createrows" border="0" cellspacing="0" cellpadding="0" align="center" style="border-collapse:collapse;">
  					<tbody>
   					 <tr>
      					  <td style="padding:3px;border:1px #000000 solid;border-top:none;border-left:none"  align="center"><b></b></td>
					      <td style="padding:3px;border:1px #000000 solid;border-top:none" align="center">名称</td>
					      <td style="padding:3px;border:1px #000000 solid;border-top:none" align="center">所在单位</td>
					      <td style="padding:3px;border:1px #000000 solid;border-top:none" align="center">职务/职称</td>
					      <td style="padding:3px;border:1px #000000 solid;border-top:none;border-right:none" align="center">签名 </td>
					      <td style="padding:3px;border:1px #000000 solid;border-top:none;border-right:none">备注</td>
    				</tr>';
		    $table_footd='</tbody></table>';

		    $rs['z_table_all']=$table_top.$table_html.$table_footd;

		}else{

			$rs['z_table_all']='<table width="100%" class="createrows" border="0" cellspacing="0" cellpadding="0" align="center" style="border-collapse:collapse;">
								 <tbody>
								    <tr>
								      <td width="11%"  align="center" style="padding:3px;border:1px #000000 solid;border-top:none;border-left:none">序号</td>
								      <td width="15%"  align="center" style="padding:3px;border:1px #000000 solid;border-top:none">名称</td>
								      <td width="21%"  align="center" style="padding:3px;border:1px #000000 solid;border-top:none">单位</td>
								      <td width="17%"  align="center" style="padding:3px;border:1px #000000 solid;border-top:none">职务/职称</td>
								      <td width="18%"  align="center" style="padding:3px;border:1px #000000 solid;border-top:none">签名</td>
								      <td width="18%" align="center" style="padding:3px;border:1px #000000 solid;border-top:none;border-right:none">备注</td>
								    </tr>
								    <tr>
								      <td style="padding:3px;border:1px #000000 solid;border-left:none" align="center">1</td>
								      <td style="padding:3px;border:1px #000000 solid" align="center"></td>
								      <td style="padding:3px;border:1px #000000 solid" align="center"></td>
								      <td style="padding:3px;border:1px #000000 solid" align="center"></td>
								      <td style="padding:3px;border:1px #000000 solid" align="center"></td>
								      <td style="padding:3px;border:1px #000000 solid;border-right:none" align="center"></td>
								    </tr>
								    <tr>
								      <td style="padding:3px;border:1px #000000 solid;border-left:none" align="center">2</td>
								      <td style="padding:3px;border:1px #000000 solid" align="center"></td>
								      <td style="padding:3px;border:1px #000000 solid" align="center"></td>
								      <td style="padding:3px;border:1px #000000 solid" align="center"></td>
								      <td style="padding:3px;border:1px #000000 solid" align="center"></td>
								      <td style="padding:3px;border:1px #000000 solid;border-right:none" align="center"></td>
								    </tr>
								    <tr>
								      <td style="padding:3px;border:1px #000000 solid;border-left:none" align="center">3</td>
								      <td style="padding:3px;border:1px #000000 solid" align="center"></td>
								      <td style="padding:3px;border:1px #000000 solid" align="center"></td>
								      <td style="padding:3px;border:1px #000000 solid" align="center"></td>
								      <td style="padding:3px;border:1px #000000 solid" align="center"></td>
								      <td style="padding:3px;border:1px #000000 solid;border-right:none" align="center"></td>
								    </tr>
								    <tr>
								      <td style="padding:3px;border:1px #000000 solid;border-left:none;border-bottom:none" align="center">4</td>
								      <td style="padding:3px;border:1px #000000 solid;border-bottom:none" align="center"></td>
								      <td style="padding:3px;border:1px #000000 solid;border-bottom:none" align="center"></td>
								      <td style="padding:3px;border:1px #000000 solid;border-bottom:none" align="center"></td>
								      <td style="padding:3px;border:1px #000000 solid;border-bottom:none" align="center"></td>
								      <td style="padding:3px;border:1px #000000 solid;border-right:none;border-bottom:none" align="center"></td>
								    </tr>
								      <tr>
								      <td style="padding:3px;border:1px #000000 solid;border-left:none;border-bottom:none" align="center">5</td>
								      <td style="padding:3px;border:1px #000000 solid;border-bottom:none" align="center"></td>
								      <td style="padding:3px;border:1px #000000 solid;border-bottom:none" align="center"></td>
								      <td style="padding:3px;border:1px #000000 solid;border-bottom:none" align="center"></td>
								      <td style="padding:3px;border:1px #000000 solid;border-bottom:none" align="center"></td>
								      <td style="padding:3px;border:1px #000000 solid;border-right:none;border-bottom:none" align="center"></td>
								    </tr>
								      <tr>
								      <td style="padding:3px;border:1px #000000 solid;border-left:none;border-bottom:none" align="center">6</td>
								      <td style="padding:3px;border:1px #000000 solid;border-bottom:none" align="center"></td>
								      <td style="padding:3px;border:1px #000000 solid;border-bottom:none" align="center"></td>
								      <td style="padding:3px;border:1px #000000 solid;border-bottom:none" align="center"></td>
								      <td style="padding:3px;border:1px #000000 solid;border-bottom:none" align="center"></td>
								      <td style="padding:3px;border:1px #000000 solid;border-right:none;border-bottom:none" align="center"></td>
								    </tr>
								      <tr>
								      <td style="padding:3px;border:1px #000000 solid;border-left:none;border-bottom:none" align="center">7</td>
								      <td style="padding:3px;border:1px #000000 solid;border-bottom:none" align="center"></td>
								      <td style="padding:3px;border:1px #000000 solid;border-bottom:none" align="center"></td>
								      <td style="padding:3px;border:1px #000000 solid;border-bottom:none" align="center"></td>
								      <td style="padding:3px;border:1px #000000 solid;border-bottom:none" align="center"></td>
								      <td style="padding:3px;border:1px #000000 solid;border-right:none;border-bottom:none" align="center"></td>
								    </tr>
								      <tr>
								      <td style="padding:3px;border:1px #000000 solid;border-left:none;border-bottom:none" align="center">8</td>
								      <td style="padding:3px;border:1px #000000 solid;border-bottom:none" align="center"></td>
								      <td style="padding:3px;border:1px #000000 solid;border-bottom:none" align="center"></td>
								      <td style="padding:3px;border:1px #000000 solid;border-bottom:none" align="center"></td>
								      <td style="padding:3px;border:1px #000000 solid;border-bottom:none" align="center"></td>
								      <td style="padding:3px;border:1px #000000 solid;border-right:none;border-bottom:none" align="center"></td>
								    </tr>
								      <tr>
								      <td style="padding:3px;border:1px #000000 solid;border-left:none;border-bottom:none" align="center">9</td>
								      <td style="padding:3px;border:1px #000000 solid;border-bottom:none" align="center"></td>
								      <td style="padding:3px;border:1px #000000 solid;border-bottom:none" align="center"></td>
								      <td style="padding:3px;border:1px #000000 solid;border-bottom:none" align="center"></td>
								      <td style="padding:3px;border:1px #000000 solid;border-bottom:none" align="center"></td>
								      <td style="padding:3px;border:1px #000000 solid;border-right:none;border-bottom:none" align="center"></td>
								    </tr>
								  </tbody>
								</table>';
		}*/

		//校级专家组的模板标签
		$x_table=m('m_project_x')->getall('mid='.$rs['id'],'*','sort ASC');

		if(!empty($x_table)){

			$table_html="";
			foreach($x_table as $k=>$value){
				$table_html.='<tr>';
				$table_html.='<td style="padding:3px;border-top:1px #000000 solid;" align="center">'.$value['sort'].'</td>';
				$table_html.='<td style="padding:3px;border:1px #000000 solid;border-bottom:none;" align="center">'.$value['project_x_n'].'</td>';
				$table_html.='<td style="padding:3px;border:1px #000000 solid;border-bottom:none;" align="center">'.$value['project_x_d'].'</td>';
				$table_html.='<td style="padding:3px;border:1px #000000 solid;border-bottom:none;" align="center">'.$value['project_x_zw'].'</td>';
				$table_html.='<td style="padding:3px;border:1px #000000 solid;border-bottom:none;" align="center">'.$value['project_x_bz'].'</td>';
				$table_html.='</tr>';
			}

			$table_top='';
			$table_top='<table width="100%" class="createrows" border="0" cellspacing="0" cellpadding="0" align="center" style="border-collapse:collapse;">
						<tbody>
    					<tr>
					      <td style="padding:3px;border:1px #000000 solid;border-top:none;border-left:none"  align="center"><b></b></td>
					      <td style="padding:3px;border:1px #000000 solid;border-top:none" align="center">名称</td>
					      <td style="padding:3px;border:1px #000000 solid;border-top:none" align="center">所在单位</td>
					      <td style="padding:3px;border:1px #000000 solid;border-top:none" align="center">职务/职称</td>
					      <td style="padding:3px;border:1px #000000 solid;border-top:none;border-right:none">备注</td>
    					</tr>';
		  $table_footd='';
		  $table_footd='</tbody></table>';

		  $rs['x_table_all']=$table_top.$table_html.$table_footd;

		}else{
				$rs['x_table_all']='<table width="100%" class="createrows" border="0" cellspacing="0" cellpadding="0" align="center" style="border-collapse:collapse;">
					<tbody>
				    <tr>
				      <td width="11%"  align="center" style="padding:3px;border:1px #000000 solid;border-top:none;border-left:none">序号</td>
				      <td width="15%"  align="center" style="padding:3px;border:1px #000000 solid;border-top:none">名称</td>
				      <td width="21%"  align="center" style="padding:3px;border:1px #000000 solid;border-top:none">单位</td>
				      <td width="17%"  align="center" style="padding:3px;border:1px #000000 solid;border-top:none">职务/职称</td>
				      <td width="18%" align="center" style="padding:3px;border:1px #000000 solid;border-top:none;border-right:none">备注</td>
				    </tr>
				    <tr>
				      <td style="padding:3px;border:1px #000000 solid;border-left:none" align="center">1</td>
				      <td style="padding:3px;border:1px #000000 solid" align="center"></td>
				      <td style="padding:3px;border:1px #000000 solid" align="center"></td>
				      <td style="padding:3px;border:1px #000000 solid" align="center"></td>
				      <td style="padding:3px;border:1px #000000 solid;border-right:none" align="center"></td>
				    </tr>
				    <tr>
				      <td style="padding:3px;border:1px #000000 solid;border-left:none" align="center">2</td>
				      <td style="padding:3px;border:1px #000000 solid" align="center"></td>
				      <td style="padding:3px;border:1px #000000 solid" align="center"></td>
				      <td style="padding:3px;border:1px #000000 solid" align="center"></td>
				      <td style="padding:3px;border:1px #000000 solid;border-right:none" align="center"></td>
				    </tr>
				    <tr>
				      <td style="padding:3px;border:1px #000000 solid;border-left:none" align="center">3</td>
				      <td style="padding:3px;border:1px #000000 solid" align="center"></td>
				      <td style="padding:3px;border:1px #000000 solid" align="center"></td>
				      <td style="padding:3px;border:1px #000000 solid" align="center"></td>
				      <td style="padding:3px;border:1px #000000 solid;border-right:none" align="center"></td>
				    </tr>
				    <tr>
				      <td style="padding:3px;border:1px #000000 solid;border-left:none;border-bottom:none" align="center">4</td>
				      <td style="padding:3px;border:1px #000000 solid;border-bottom:none" align="center"></td>
				      <td style="padding:3px;border:1px #000000 solid;border-bottom:none" align="center"></td>
				      <td style="padding:3px;border:1px #000000 solid;border-bottom:none" align="center"></td>
				      <td style="padding:3px;border:1px #000000 solid;border-right:none;border-bottom:none" align="center"></td>
				    </tr>
				      <tr>
				      <td style="padding:3px;border:1px #000000 solid;border-left:none;border-bottom:none" align="center">5</td>
				      <td style="padding:3px;border:1px #000000 solid;border-bottom:none" align="center"></td>
				      <td style="padding:3px;border:1px #000000 solid;border-bottom:none" align="center"></td>
				      <td style="padding:3px;border:1px #000000 solid;border-bottom:none" align="center"></td>
				      <td style="padding:3px;border:1px #000000 solid;border-right:none;border-bottom:none" align="center"></td>
				    </tr>
				      <tr>
				      <td style="padding:3px;border:1px #000000 solid;border-left:none;border-bottom:none" align="center">6</td>
				      <td style="padding:3px;border:1px #000000 solid;border-bottom:none" align="center"></td>
				      <td style="padding:3px;border:1px #000000 solid;border-bottom:none" align="center"></td>
				      <td style="padding:3px;border:1px #000000 solid;border-bottom:none" align="center"></td>
				      <td style="padding:3px;border:1px #000000 solid;border-right:none;border-bottom:none" align="center"></td>
				    </tr>
				      <tr>
				      <td style="padding:3px;border:1px #000000 solid;border-left:none;border-bottom:none" align="center">7</td>
				      <td style="padding:3px;border:1px #000000 solid;border-bottom:none" align="center"></td>
				      <td style="padding:3px;border:1px #000000 solid;border-bottom:none" align="center"></td>
				      <td style="padding:3px;border:1px #000000 solid;border-bottom:none" align="center"></td>
				      <td style="padding:3px;border:1px #000000 solid;border-right:none;border-bottom:none" align="center"></td>
				    </tr>
				      <tr>
				      <td style="padding:3px;border:1px #000000 solid;border-left:none;border-bottom:none" align="center">8</td>
				      <td style="padding:3px;border:1px #000000 solid;border-bottom:none" align="center"></td>
				      <td style="padding:3px;border:1px #000000 solid;border-bottom:none" align="center"></td>
				      <td style="padding:3px;border:1px #000000 solid;border-bottom:none" align="center"></td>
				      <td style="padding:3px;border:1px #000000 solid;border-right:none;border-bottom:none" align="center"></td>
				    </tr>
				    <tr>
				      <td style="padding:3px;border:1px #000000 solid;border-left:none;border-bottom:none" align="center">9</td>
				      <td style="padding:3px;border:1px #000000 solid;border-bottom:none" align="center"></td>
				      <td style="padding:3px;border:1px #000000 solid;border-bottom:none" align="center"></td>
				      <td style="padding:3px;border:1px #000000 solid;border-bottom:none" align="center"></td>
				      <td style="padding:3px;border:1px #000000 solid;border-right:none;border-bottom:none" align="center"></td>
				    </tr>
				  </tbody>
				</table>';
		}





		//审核流程ID
		$upper_lead_id='50';//上级领导审核
		$project_office_id='51';//校项目办公室初审
		//$xiaoban_zhuanjia='57';//校专家组 20170528网评功能

/*		@流程简化
		注释时间 @2017/4/26
		注释人 liang
		备注 对应的流程需要关闭	*/
/*		$business_dept_id='54';//业务主管（职能专家组）
		$xiaoban_shengpi='58';//校专家意见审批*/


		if(!empty($rs['project_apply_time'])){
			$datetime= explode('-',$rs['project_apply_time']);
			$rs['apply_year']=$datetime[0];
			$rs['apply_month']=$datetime[1];
			$rs['apply_day']=$datetime[2];
		}

		$loger=$this->getlog();
		//提取状态和负责人、意见
		foreach($loger as $k=>$value){
			//领导审核

			if($value['courseid']==$upper_lead_id){
				$rs['upper_lead_head']=$value['name'];
				$rs['upper_lead_statusname']=$value['statusname'];
				$rs['upper_lead_explain']=$value['explain'];



				$upper_lead_datetime= explode('(',$value['optdt']);
				$upper_lead_datetime_array= explode('-',$upper_lead_datetime[0]);

				//签字时间
				$rs['upper_lead_year']=$upper_lead_datetime_array[0];
				$rs['upper_lead_month']=$upper_lead_datetime_array[1];
				$rs['upper_lead_day']=$upper_lead_datetime_array[2];


				$upper_lead_id="";

			}

			//项目办公室
			if($value['courseid']==$project_office_id && $value['statusname']=='通过'){
				$rs['project_office_head']=$value['name'];
				$rs['project_office_statusname']=$value['statusname'];
				$rs['project_office_explain']=$value['explain'];
				$project_office="";

			}
			//项目办公室
			if($value['courseid']==$project_office_id && $value['statusname']=='不通过'){
				$rs['no_project_office_head']=$value['name'];
				$rs['no_project_office_statusname']=$value['statusname'];
				$rs['no_project_office_explain']=$value['explain'];
				$project_office="";

			}


/*			职能部门（业务主管）
			注释时间 @2017/4/26
			注释人 @liang
			注释原因 流程简化
			备注 @同时去除元素表单中的数据
				@详情页面的数据*/
			/*if($value['courseid']==$business_dept_id ){
				if(!empty($rs['idss'])){
					$rs['zhineng_file']="";
				}else{
					if(isset($value['explain_file'])){
						$rs['zhineng_file']=$value['explain_file'];
					}

				}


			}*/


			//校级专家组论证意见
			//注释原因网评专家取代
//			if($value['courseid']==$xiaoban_zhuanjia){
//
//
//				if(!empty($rs['idss'])){
//					if(isset($value['explain'])){
//						$rs['xiaoban_shengpi_yijian']=$value['explain'];
//						$rs['xiangban_file']="";
//					}
//
//
//				}else{
//					if(isset($value['yijian'])){
//						$rs['xiaoban_shengpi_yijian']=$value['yijian'];
//						$rs['xiangban_file']=$value['explain_file'];
//					}
//
//				}
//			}

	/*		校长办公会审批结果
			注释时间 @2017/4/26 暂定
			注释人 @liang
			注释原因 @流程简化
	 				@4/26 暂定不显示在详情界面
			备注 @同时去除元素表单中的数据
				@详情页面的数据*/
/*			if($value['courseid']==$xiaoban_shengpi){
				if(!empty($rs['idss'])){

					$rs['x_file']="";

				}else{
					if(isset($value['explain_file'])){
					$rs['x_file']=$value['explain_file'];
					}

				}
			}*/

//			if($value['courseid']==$business_dept_id && ){
//				$rs['no_business_dept_head']=$value['name'];
//				$rs['no_business_dept_statusname']=$value['statusname'];
//				$rs['no_business_dept_explain']=$value['explain'];
//				$business_dept="";
//
//			}
//			if($value['courseid']==$business_dept_id && $value['statusname']=='通过'){
//				$rs['business_dept_head']=$value['name'];
//				$rs['business_dept_statusname']=$value['statusname'];
//				$rs['business_dept_explain']=$value['explain'];
//				$business_dept="";
//
//			}

		}



		return $rs;
		}

/*	默认下一步审核人
		注释时间 2017/4/26
		注释人 liang
		@流程简化
	备注：该方法数据还在文件 tpl_mode_p.thml 中有使用 也需要注释*/
	/*protected function nextflowcheckname($mid,$buzou_id){




		$arr="";

		if($buzou_id=='55' || $buzou_id=='57' || $buzou_id=="58"){

			$arr=m('flow_log')->getone('mid='.$mid.' and courseid=51','checkname,checkid');


		}

		return $arr;

	}*/
		//封面的下滑线计算
		protected function str_add($str,$le){
			$mb_big_one=mb_strwidth($str,"utf-8");//中文两个字节 英文一个字节
			$mb_nsx_one=mb_strlen($str,"utf-8");//中英文一个字节

			if($mb_big_one==$mb_nsx_one){
				$ctuon=$mb_big_one/2;

				$legth_str='';
				for($i=$ctuon;$i<$le;$i++){
				$legth_str=$legth_str."&nbsp;";

				}
				return $str=$str.$legth_str;


			}else{


				$oncd=$mb_big_one-$mb_nsx_one;//等于字符串包含几个中文

				$ctuon=$oncd+($mb_nsx_one-$oncd)/2;

				$legth_str='';
				for($i=$ctuon;$i<$le;$i++){
					$legth_str=$legth_str."&nbsp;";

				}
				return $str=$str.$legth_str;
				}

		}



	/**
	 * 获取统计数
	 */
	public function getStatistics(){
		//获取处理中
		$arr['clz'] = $this->rows('status=0');
		//获取已审核
		$arr['ysh'] = $this->rows('status=1');
		//获取未通过
		$arr['wtg'] = $this->rows('status=2');
		//获取全部
		$arr['all'] = $this->rows('status IS NOT NULL');
		return $arr;
	}

	//流程审核
/*	protected function flowcheckafter($zt, $sm){

		if($zt==1){
 * 20170610 换函数
 * */


				//职能部门专家组表格录入数据库
				//循环行数
/*				注释时间 @2017/4/26
				注释人 @liang
				注释原因 流程简化
				备注 @同时去除元素表单中的数据
					@详情页面的数据
					@去除审核的填写数据*/
		/*		$project_z_zhuanjia 	= $this->rock->post('project_z_zhuanjia');//行数
				if(!empty($project_z_zhuanjia)){
						$arr='';
						//初始化表单
						$m_project_z=m('m_project_z');
						$arr['mid']=$this->rock->post('mid');

						$sw=$m_project_z->delete('mid='.$arr['mid']);
						//删除成功
						if($sw){
							$project_z_zhuanjia 	= $this->rock->post('project_z_zhuanjia');//行数
							$arr['sort']=0;
							for($i=1;$i<=$project_z_zhuanjia;$i++){
							//获取数据
							$arr['sort']=$arr['sort']+1;
							$arr['project_z_n']= $this->rock->post('project_z_zhuanjia_'.$i.'_0');
							$arr['project_z_d']= $this->rock->post('project_z_zhuanjia_'.$i.'_1');
							$arr['project_z_zw']= $this->rock->post('project_z_zhuanjia_'.$i.'_2');
							$arr['project_z_qm']= $this->rock->post('project_z_zhuanjia_'.$i.'_0');
							$arr['project_z_bz']= $this->rock->post('project_z_zhuanjia_'.$i.'_4');
//							var_dump($arr);
							//插入数据
							$m_project_z->insert($arr);

							}
						}

				}*/

				//校专家表格录入数据库
				//注释原因网评功能取代
//				$project_x_zhuanjia 	= $this->rock->post('project_x_zhuanjia');//行数
//				if(!empty($project_x_zhuanjia)){
//						$arr='';
//						//初始化表单
//						$m_project_x=m('m_project_x');
//						$arr['mid']=$this->rock->post('mid');
//
//						$sw=$m_project_x->delete('mid='.$arr['mid']);
//						//删除成功
//						if($sw){
//							$project_x_zhuanjia 	= $this->rock->post('project_x_zhuanjia');//行数
//							$arr['sort']=0;
//							for($i=1;$i<=$project_x_zhuanjia;$i++){
//							//获取数据
//							$arr['sort']=$arr['sort']+1;
//							$arr['project_x_n']= $this->rock->post('project_x_zhuanjia_'.$i.'_0');
//							$arr['project_x_d']= $this->rock->post('project_x_zhuanjia_'.$i.'_1');
//							$arr['project_x_zw']= $this->rock->post('project_x_zhuanjia_'.$i.'_2');
//
//							$arr['project_x_qm']= $this->rock->post('project_x_zhuanjia_'.$i.'_0');
//							//修改为默认姓名做为签名
//							//$arr['project_x_qm']= $this->rock->post('project_x_zhuanjia_'.$i.'_3');
///*							@流程简化
//							注释时间 2017/4/26
//							注释原因:不要签名列*/
//							//$arr['project_x_bz']= $this->rock->post('project_x_zhuanjia_'.$i.'_4');
//
//							//插入数据
//							$m_project_x->insert($arr);
//
//							}
//						}
//
//				}

					//审核流程ID
/*					$upper_lead_id='50';//上级领导审核
					$project_office_id='51';//校项目办公室初审
 * * 20170610 换函数
 * */
					//$xiaoban_zhuanjia='57';//校专家组  网评增加去除

/*					注释时间 @2017/4/26
					注释人 @liang
					注释原因 流程简化
					备注 @关闭流程*/
/*					$business_dept_id='54';//业务主管（职能专家组）
					$upload_shishifnagan='59';//上传实施方案
					$xiaoban_zhuansong='55';//校项目办公室转送
 					$xiaoban_shengpi='58';//校专家意见审批*/


/*				$mid=$this->rock->post('mid');
				$flow_info=m('flow_bill')->getone('mid='.$mid." and modeid=54");
				$project_apply=m('project_apply');
				$project_apply_status=$project_apply->getone('id='.$mid,'status');

				if($project_apply_status=='0'){

							//上级领导审核
							if($flow_info['nowcourseid']==$upper_lead_id){
								$sz_ku['project_ku']='申报中';
								$project_apply->update($sz_ku,'id='.$mid);
							}
 * * * 20170610 换函数
 * */


							/*职能部门
							注释原因 流程简化*/
//							if($flow_info['nowcourseid']==$business_dept_id){
//								$sz_ku['project_ku']='申报中';
//								$project_apply->update($sz_ku,'id='.$mid);
//							}

							/*上传实施方案
							注释原因 流程简化*/
							/*if($flow_info['nowcourseid']==$upload_shishifnagan){
								$sz_ku['project_ku']='预备库';
								$project_apply->update($sz_ku,'id='.$mid);
							}*/

							/*校办公室转送
							注释原因 流程简化*/
						/*	if($flow_info['nowcourseid']==$xiaoban_zhuansong){
								$sz_ku['project_ku']='预备库';
								$project_apply->update($sz_ku,'id='.$mid);
							}*/

							//校级专家组 20170528网评去除
//							if($flow_info['nowcourseid']==$xiaoban_zhuanjia){
//								//注释原因 （原有的下一步的校长办公会因为暂定 ,现有进入侯建库的状态改为校级专家组）
//							/*	$sz_ku['project_ku']='预备库';
//								$project_apply->update($sz_ku,'id='.$mid);*/
//
//								$sz_ku['project_ku']='侯建库';
//								$sz_ku['process_state']='侯建库';
//								$project_apply->update($sz_ku,'id='.$mid);
//							}

							/*校长办公会审批
							注释原因 流程简化(暂定)*/
/*							if($flow_info['nowcourseid']==$xiaoban_shengpi){
								$sz_ku['project_ku']='侯建库';
								$sz_ku['process_state']='侯建库';
								$project_apply->update($sz_ku,'id='.$mid);
							}*/



/*				}else{
 * * 20170610 换函数
 * */
					//校项目办公室
						/*	if($flow_info['nowcourseid']==$project_office_id){
								$sz_ku['project_ku']='预备库';
								$project_apply->update($sz_ku,'id='.$mid);
					}
				}


		}


	}
						 *  20170610 换函数
						 * */

	protected function flowcheckafter($zt,$sm){

			$mid=$this->rock->post('mid');
			//查询实训申报书的流程状态
			$project_apply=m('project_apply');
			$flow_info=m('flow_bill')->getone('mid='.$mid." and modeid=54");

			if($zt==3){
				$sz_ku['project_ku']='';
				$project_apply->update($sz_ku,'id='.$mid);
			}
	}

	protected function flowcheckfinsh($zt){
				//1 状态为进行
			if($zt==1){
				$mid=$this->rock->post('mid');
				$flow_info=m('flow_bill')->getone('mid='.$mid." and modeid=54");
				//查询实训申报书的流程状态
				$project_apply=m('project_apply');


				$sz_ku['project_ku']='预备库';
				$project_apply->update($sz_ku,'id='.$mid);

				}

	}

	//流程提醒检查
	public function nexttodo($nuid, $type, $sm='', $act='')
	{

		//$nuid 可能为数组
		$cont	= '';
		$gname	= '流程待办';
		$project_apply=m('project_apply')->getone('id='.$this->id);
		$flow_bill=m('flow_bill')->getone('mid='.$project_apply['id']);
		$flow_course=m('flow_course')->getone('id='.$flow_bill['nowcourseid']);
		$userinfo=m('admin')->getone('id='.$nuid);
		if($type=='submit' || $type=='next'){
			$cont = '你有的['.$project_apply['project_name'].']需要处理';
			//if($sm!='')$cont.='，说明:'.$sm.'';



		}
		//审核不通过
		if($type == 'nothrough'){
			//$cont = '你提交['.$project_apply['project_name'].']'.$userinfo['name'].'处理['.$act.']，原因:['.$sm.']';
			$cont = '你提交['.$project_apply['project_name'].']'.$userinfo['name'].'';
			$gname= '流程申请';


		}
		if($type == 'finish'){
			$cont = '你提交的['.$project_apply['project_name'].']已全部处理完成';

		}
		if($type == 'zhui'){
			$cont = '你有['.$userinfo['name'].']的['.$project_apply['project_name'].']需要处理，追加说明:['.$sm.']';
		}
		//退回
		if($type == 'tui'){
			$cont = '['.$userinfo['name'].']退回单据['.$project_apply['project_name'].']到你这请及时处理，说明:'.$sm.'';

		}




//		'description'=>'项目名称：'.$project_apply['project_name']."\n项目编号：".$project_apply['project_number']."\n项目负责人：".$project_apply['project_head']."\n申报时间：".$project_apply['project_apply_time'],
//
//		if($userinfo['wx_openid']!=''){
//
//				$data=array(
//		 			'articles'=>array(
//			            	0=>array(
//			            	"title" =>$userinfo['name'].', 您有项目待处理',
//			            	'description'=>'项目名称：'.$project_apply['project_name']."\n当前进程状态：".$flow_course['name']."\n申报时间：".$project_apply['project_apply_time'],
//							"url" =>getconfig('url')."index.php?m=ying&d=we&num=project_apply",
//					        "picurl" =>""
//									)
//							)
//						);
//
//
//			m('wxgzh:wxgzh')->doSend($userinfo['wx_openid'],'@all','@all',1, $data);
//		}




		if($cont!='')$this->push($nuid, $gname, $cont);



	}

	public function push($receid, $gname='', $cont, $title='', $wkal=0)
	{
			$userinfo=m('admin')->getone('id='.$receid);


			if($this->isempt($receid) && $wkal==1)$receid='all';
			if($this->isempt($receid))return false;
			if($gname=='')$gname = $this->modename;
			$reim	= m('reim');
			$url 	= ''.URL.'task.php?a=p&num='.$this->modenum.'&mid='.$this->id.'';
			$wxurl 	= ''.URL.'task.php?a=x&num='.$this->modenum.'&mid='.$this->id.'';
			$emurl 	= ''.URL.'task.php?a=a&num='.$this->modenum.'&mid='.$this->id.'';
			if($this->id==0){
				$url = '';$wxurl = '';$emurl='';
			}
			$slx	= 0;
			$pctx	= $this->moders['pctx'];
			$mctx	= $this->moders['mctx'];
			$wxtx	= $this->moders['wxtx'];
			$emtx	= $this->moders['emtx'];
			if($pctx==0 && $mctx==1)$slx=2;
			if($pctx==1 && $mctx==0)$slx=1;
			if($pctx==0 && $mctx==0)$slx=3;
			$this->rs['now_adminname'] 	= $this->adminname;
			$this->rs['now_modename'] 	= $this->modename;
			$cont	= $this->rock->reparr($cont, $this->rs);
			if(contain($receid,'u') || contain($receid, 'd'))$receid = m('admin')->gjoin($receid);
			m('todo')->addtodo($receid, $this->modename, $cont, $this->modenum, $this->id);
			$reim->pushagent($receid, $gname, $cont, $title, $url, $wxurl, $slx);


			if($title=='')$title = $this->modename;
			//邮件提醒发送不发送全体人员的，太多了
			if($emtx == 1 && $receid != 'all'){
				$emcont = '您好：<br>'.$cont.'(邮件由系统自动发送)';
				if($emurl!=''){
					$emcont.='<br><a href="'.$emurl.'" target="_blank" style="color:blue"><u>详情&gt;&gt;</u></a>';
				}
				m('email')->sendmail($title, $emcont, $receid);
			}



			//yanshou
			//微信提醒发送
			$project_apply=m('project_apply')->getone('id='.$this->id);
			$flow_bill=m('flow_bill')->getone('mid='.$this->id);
			$flow_course=m('flow_course')->getone('id='.$flow_bill['nowcourseid']);

			$url=getconfig('url').'task.php?a=x&num='.$this->modenum.'&mid='.$this->id.'&show=we';

			if($userinfo['wx_openid']!=''){

					$data=array(
			 			'articles'=>array(
				            	0=>array(
				            	"title" =>$userinfo['name'].', 您有项目待处理',
				            	'description'=>'项目名称：'.$project_apply['project_name']."\n负责人：".$project_apply['project_head']."\n申报时间：".$project_apply['project_apply_time'],
								"url" =>$url,
						        "picurl" =>""
										)
								)
							);


				m('wxgzh:wxgzh')->doSend($userinfo['wx_openid'],'@all','@all',1, $data);
			}

	}


	//获取菜单
	public function getoptmenu($flx=0)
	{
		$rows 	= $this->db->getrows('[Q]flow_menu',"`setid`='$this->modeid' and `status`=1",'id,wherestr,name,statuscolor,statusvalue,num,islog,issm,type','`sort`');

		$arr 	= array();
		if($rows){

			foreach($rows as $k=>$rs){
				$wherestr 	= $rs['wherestr'];
				$bo 		= false;
				if(isempt($wherestr)){
					$bo = true;
				}else{
					$ewet	= m('where')->getstrwhere($this->rock->jm->base64decode($wherestr));
					$tos 	= $this->rows("`id`='$this->id' and $ewet");
					if($tos>0)$bo = true;
				}
				$rs['lx']	  = $rs['type'];
				$rs['optnum'] = $rs['num'];
				if(!isempt($rs['num'])){
					$glx = $this->flowgetoptmenu($rs['num']);
					if(is_bool($glx))$bo = $glx;
				}
				$rs['optmenuid'] = $rs['id'];
				if(!isempt($rs['statuscolor']))$rs['color']  = $rs['statuscolor'];
				unset($rs['id']);unset($rs['num']);unset($rs['wherestr']);unset($rs['type']);unset($rs['statuscolor']);
				if($bo)$arr[] = $rs;
			}

		}

		$tf_bianji=m('flow_bill')->getone('modeid='.$this->modeid.' and mid='.$this->id);

		//是否有流程
		if($this->isflow==1){

			//初始化 ischeck
			$ischeck = 0;

			//读取项目库基础信息
			$project_apply_info=m('project_apply')->getone('id='.$this->id);

			$nowcheckid = ','.$tf_bianji['nowcheckid'].',';
			if($tf_bianji['status']!=1 && contain($nowcheckid, ','.$this->adminid.',') && $tf_bianji['status']!= 2){
				$ischeck = 1;
			}

			//如果状态为已作废，即nowstatus=5,则不显示
			if($ischeck==1 && $tf_bianji['nstatus']!=5 ){

				$arr[] = array('name'=>'<b>处理</b>','color'=>'#1389D3','lx'=>996);

			}
		}

		//已作废则不能编辑
		if($this->iseditqx()==1 && $this->getflowinfor()['nowstatus']!=5 && $tf_bianji['uid']==$this->adminid &&  $project_apply_info['isturn']==0){
			$arr[] = array('name'=>'编辑','optnum'=>'edit','lx'=>'11','optmenuid'=>-11);
		}
		if($this->iseditqx()==1 && $this->getflowinfor()['nowstatus']!=5 && $tf_bianji['uid']==$this->adminid &&  $tf_bianji['nstatus']==2){
			$arr[] = array('name'=>'编辑','optnum'=>'edit','lx'=>'11','optmenuid'=>-11);
		}

		if($this->isdeleteqx()==1 && $project_apply_info['isturn']==0){
			$arr[] = array('name'=>'删除','color'=>'red','optnum'=>'del','issm'=>0,'islog'=>0,'statusvalue'=>9,'lx'=>'9','optmenuid'=>-9);
		}

		return $arr;
	}

//end
}
