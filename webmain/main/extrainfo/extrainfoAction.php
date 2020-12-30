<?php
/**
 * 开发者gzj
 * 采购
 */
class extrainfoClassAction extends Action
{
	private $admin_info = array();//用户信息
	private $group_id = '';//组id（角色id）
	private $Ctable="`[Q]flow_bill` a left join `[Q]admin` b on a.uid=b.id left join `whole_projects` c on a.mid=c.id ";//默认要查询这3个表
	
	
	/**
	 * 采购列表
	 */
	public function purchaseAjax(){
		$project_name = $this->post('project_name');//项目名称
		$total_cost = $this->post('total_cost');//采购总金额
		$time_frame = $this->post('time_frame');//时间范围
		$where = '';//查询条件
	
		if($project_name!='') $where.= ' and c.project_name like "%'.trim($project_name).'%"';
		if($total_cost!='') $where.= ' and mp.total_cost='.trim($total_cost);
		if($time_frame!=""){//时间范围
			list($start_time,$end_time) = explode(',', $time_frame);
			$where.=" and mp.purchase_time between '".$start_time."' and '".$end_time."'";
			unset($start_time,$end_time);
		}
		$table = $this->Ctable.'left join `[Q]mf_purchase` mp on case when c.is_purchase=1 then mp.mtype=a.table and mp.mid=c.id and mp.is_delete=0 else "" end ';
		$fields = 'a.table as mtype,c.project_name,mp.total_cost,DATE_FORMAT(mp.purchase_time, "%Y-%m-%d") as purchase_time,c.is_purchase,mp.purchase_id,c.id as mid,mp.file_ids,mp.purchase_id as id';
		/*@@采购列表显示条件: 已采购   不是作废项目*/
		$where = 'a.isdel=0 and c.is_appropriation=1 and c.status<>5'.$where;
		$order = 'a.optdt desc';
		$this->getlist($table, $fields, $where, $order, 'purchase');
	}



	
	/**
	 * 采购信息添加,编辑
	 */
	public function savepurchaseAjax(){
		$purchase_id = $this->post('purchase_id',0);//采购信息id
		$mid = $this->post('mid');//项目id
		$mtype = $this->post('mtype');//项目模块
		$total_cost = $this->post('total_cost');//采购总金额
		$purchase_time = $this->post('purchase_time');//采购时间
		$file_ids = $this->post('file_ids');//文件ids
		
		if($mid == '')exit('项目id为空');
		if($total_cost == '' && is_numeric($total_cost) && $total_cost<=0)exit('采购总金额不能为空(需大于0)');
		if($purchase_time == '')exit('采购时间不能为空');
		
		$info = '';//返回的信息
		//用id作判断，有传入id则编辑，没有则是添加
		if($purchase_id==0){//添加
			$reinfo = m('mf_purchase')->insert(array(
				'mid' => $mid,
				'mtype' => $mtype,
				'total_cost' => $total_cost,
				'purchase_time' => $purchase_time,
				'file_ids' => $file_ids
			));
			$painfo = m($mtype)->update(array('is_purchase'=>1,'process_state'=>'采购','project_ku'=>'建设库'),"id=$mid");
			//echo m('project_apply')->getLastSql();
			if($reinfo && $painfo)
				$info =array('id' => $reinfo,'success' => true,'msg' => '采购信息录入成功');
			else{
				$info =array('id' => $reinfo,'success' => false,'msg' => '采购信息录入失败');
			}
		}else{//编辑
			$reinfo = m('mf_purchase')->update(array(
				'total_cost' => $total_cost,
				'purchase_time' => $purchase_time,
				'file_ids' => $file_ids
			),"purchase_id=$purchase_id");
			if($reinfo)
				$info =array('id' => $reinfo,'success' => true,'msg' => '采购信息更新成功');
			else{
				$info =array('id' => $reinfo,'success' => false,'msg' => '采购信息更新失败');
			}
		}
		//对相应上传的文件赋予项目id
		$idarr = explode(',', $file_ids);
		foreach ($idarr as $k => $file_id) {
			m('file')->update(array('mid'=>$mid,'mtype'=>$mtype),"id=$file_id");
		}
		unset($v,$file_id);
		$this->returnjson($info);
	}
	
	/**
	 * 验收列表
	 */
	public function accpetAjax(){
		$project_name = $this->post('project_name');//项目名称
		$project_head = $this->post('project_head');//项目负责人
		$time_frame = $this->post('time_frame');//时间范围
		$where = '';//查询条件
		
		if($project_name!='') $where.= ' and c.project_name like "%'.trim($project_name).'%"';
		if($project_head!='') $where.= ' and ma.accept_user_name like "%'.trim($project_head).'%"';
		if($time_frame!=""){//时间范围
			list($start_time,$end_time) = explode(',', $time_frame);
			$where.=" and ma.accept_time between '".$start_time."' and '".$end_time."'";
			unset($start_time,$end_time);
		}
		$table = $this->Ctable.'left join `[Q]mf_accept` ma on case when c.is_accept=1 then ma.mtype=a.table and ma.mid=c.id  and ma.is_delete=0 else "" end ';
		$fields = 'a.table as mtype,c.project_name,ma.accept_msg,DATE_FORMAT(ma.accept_time, "%Y-%m-%d") as accept_time,c.is_accept,c.id as mid,ma.accept_user_name,ma.file_ids,TRIM(c.project_xingzhi) as project_xingzhi,ma.accept_id as id';
		/*@@验收列表显示条件:该项目已经资金拨付,已采购*/
		$where = 'a.isdel=0 and c.is_purchase=1 and c.is_appropriation=1 and c.status<>5 '.$where;
		$order = 'a.optdt desc';
		$this->getlist($table, $fields, $where, $order, 'accept');
	}


	/**
	 * 验收信息添加,编辑
	 */
	public function saveacceptAjax(){
		$accept_id = $this->post('accept_id');//验收信息id
		$mid = $this->post('mid');//项目id
		$mtype = $this->post('mtype');//项目模块
		$accept_user_name = $this->post('accept_user_name');//验收负责人
		$accept_msg = $this->post('accept_msg');//验收说明
		$accept_time = $this->post('accept_time');//验收时间
		$file_ids = $this->post('file_ids');//文件ids
		
		if($mid == '' || $mid==0)exit('项目id为空');
		if($accept_user_name == '')exit('验收负责人姓名不能为空');
		if($accept_msg == '')exit('验收说明不能为空');
		if($accept_time == '')exit('验收时间不能为空');
		
		$info = '';//返回的信息
		//有传入id并且id不为0则是编辑，否则是添加
		if($accept_id==0){//添加
			$reinfo = m('mf_accept')->insert(array(
				'mid' => $mid,
				'mtype' => $mtype,
				'accept_user_name' => $accept_user_name,
				'accept_msg' => $accept_msg,
				'accept_time' => $accept_time,
				'file_ids' => $file_ids
			));
			$painfo = m($mtype)->update(array('is_accept'=>1,'process_state'=>'验收'),"id=$mid");
			//echo m($mtype)->getLastSql();
			if($reinfo && $painfo)
				$info =array('id' => $reinfo,'success' => true,'msg' => '验收信息录入成功');
			else{
				$info =array('id' => $reinfo,'success' => false,'msg' => '验收信息录入失败');
			}
		}else{//编辑
			$reinfo = m('mf_accept')->update(array(
				'accept_user_name' => $accept_user_name,
				'accept_msg' => $accept_msg,
				'accept_time' => $accept_time,
				'file_ids' => $file_ids
			),"accept_id=$accept_id");
			if($reinfo)
				$info =array('id' => $reinfo,'success' => true,'msg' => '验收信息更新成功');
			else{
				$info =array('id' => $reinfo,'success' => false,'msg' => '验收信息更新失败');
			}
		}
		//对相应上传的文件赋予项目id
		$idarr = explode(',', $file_ids);
		foreach ($idarr as $k => $file_id) {
			m('file')->update(array('mid'=>$mid,'mtype'=>$mtype),"id=$file_id");
		}
		unset($v,$file_id);
		$this->returnjson($info);
	}


	
	
	/**
	 * 付款列表
	 */
	public function paymentAjax(){
		$project_name = $this->post('project_name');//项目名称
		$payment_user_name = $this->post('payment_user_name');//付款人
		$time_frame = $this->post('time_frame');//时间范围
		$where = '';//查询条件
	
		if($project_name!='') $where.= ' and c.project_name like "%'.trim($project_name).'%"';
		if($payment_user_name!='') $where.= ' and mp.total_cost='.trim($payment_user_name);
		if($time_frame!=""){//时间范围
			list($start_time,$end_time) = explode(',', $time_frame);
			$where.=" and mp.payment_time between '".$start_time."' and '".$end_time."'";
			unset($start_time,$end_time);
		}
		$table = $this->Ctable.'left join `[Q]mf_payment` mp on case when c.is_payment=1 then mp.mtype=a.table and mp.mid=c.id and mp.is_delete=0 else "" end ';
		$fields = 'a.table as mtype,c.project_name,mp.payment_msg,DATE_FORMAT(mp.payment_time, "%Y-%m-%d") as payment_time,c.is_payment,c.id as mid,mp.file_ids,mp.payment_id as id,c.project_xingzhi,mp.payment_user_name';
		/*@@付款列表显示条件:已拨付和已验收和 已采购 和已验收*/
		$where = 'a.isdel=0 and c.status<>5 and c.is_purchase=1 and is_appropriation=1 and c.is_purchase=1 and c.is_accept=1'.$where;
		$order = 'a.optdt desc';
		$this->getlist($table, $fields, $where, $order, 'payment');
	}
	
	
	/**
	 * 付款信息添加,编辑
	 */
	public function savepaymentAjax(){
		$payment_id = $this->post('payment_id');//付款信息id
		$mid = $this->post('mid');//项目id
		$mtype = $this->post('mtype');//项目模块
		$payment_user_name = $this->post('payment_user_name');//付款负责人
		$payment_msg = $this->post('payment_msg');//付款说明
		$payment_time = $this->post('payment_time');//付款时间
		$file_ids = $this->post('file_ids');//文件ids
		
		if($mid == '' || $mid==0)exit('项目id为空');
		if($payment_user_name == '')exit('付款负责人姓名不能为空');
		if($payment_msg == '')exit('付款说明不能为空');
		if($payment_time == '')exit('付款时间不能为空');
		
		$info = '';//返回的信息
		//有传入id并且id不为0则是编辑，否则是添加
		if($payment_id==0){//添加
			$reinfo = m('mf_payment')->insert(array(
				'mid' => $mid,
				'mtype' => $mtype,
				'payment_user_name' => $payment_user_name,
				'payment_msg' => $payment_msg,
				'payment_time' => $payment_time,
				'file_ids' => $file_ids
			));
			$painfo = m($mtype)->update(array('is_payment'=>1,'process_state'=>'付款'),"id=$mid");
			//echo m($mtype)->getLastSql();
			if($reinfo && $painfo)
				$info =array('id' => $reinfo,'success' => true,'msg' => '付款信息录入成功');
			else{
				$info =array('id' => $reinfo,'success' => false,'msg' => '付款信息录入失败');
			}
		}else{//编辑
			$reinfo = m('mf_payment')->update(array(
				'payment_user_name' => $payment_user_name,
				'payment_msg' => $payment_msg,
				'payment_time' => $payment_time,
				'file_ids' => $file_ids
			),"payment_id=$payment_id");
			if($reinfo)
				$info =array('id' => $reinfo,'success' => true,'msg' => '付款信息更新成功');
			else{
				$info =array('id' => $reinfo,'success' => false,'msg' => '付款信息更新失败');
			}
		}
		//对相应上传的文件赋予项目id
		$idarr = explode(',', $file_ids);
		foreach ($idarr as $k => $file_id) {
			m('file')->update(array('mid'=>$mid,'mtype'=>$mtype),"id=$file_id");
		}
		unset($v,$file_id);
		$this->returnjson($info);
	}

	
	/**
	 * 绩效考评列表
	 */
	public function evaluationAjax(){
		$project_select = $this->post('project_select');//项目类别
		$deptname = $this->post('deptname');//申报单位
		$time_frame = $this->post('time_frame');//时间范围
		$project_name = $this->post('project_name');//项目名称
		$project_number = $this->post('project_number');//项目编号
		$optname = $this->post('optname');//项目负责人
		$where = '';//查询条件
		
		if($project_select!='') $where.= ' and c.project_select="'.trim($project_select).'"';
		if($deptname!='') $where.= ' and TRIM(b.deptname)="'.trim($deptname).'"';
		if($time_frame!=""){//时间范围
			list($start_time,$end_time) = explode(',', $time_frame);
			$where.=" and mp.evaluation_time between '".$start_time."' and '".$end_time."'";
			unset($start_time,$end_time);
		}
		if($project_name!='') $where.= ' and c.project_name like "%'.trim($project_name).'%"';
		if($project_number!='') $where.= ' and c.project_number="'.trim($project_number).'"';
		if($optname!='') $where.= ' and b.optname like "%'.trim($optname).'%"';
		$table = $this->Ctable.'left join `[Q]mf_evaluation` mp on case when c.is_evaluation=1 then mp.mtype=a.table and mp.mid=c.id and mp.is_delete=0 else "" end ';
		$fields = 'a.table as mtype,c.project_name,DATE_FORMAT(mp.evaluation_time, "%Y-%m-%d") as evaluation_time,c.is_evaluation,c.id as mid,mp.file_ids,mp.evaluation_id as id,c.project_xingzhi,a.optname,b.deptname,c.project_select,c.project_number';
		/*@@付款列表显示条件:已拨付和已付款 和 已采购 和已验收 和已付款*/
		$where = 'a.isdel=0 and c.status<>5 and c.is_appropriation=1 and c.is_purchase=1 and c.is_accept=1 and c.is_payment=1 '.$where;
		$order = 'a.optdt desc';
		$this->getlist($table, $fields, $where, $order, 'evaluation');
	}
	
	
	/**
	 * 考评信息添加,编辑
	 */
	public function saveevaluationAjax(){
		$evaluation_id = $this->post('evaluation_id');//考评信息id
		$mid = $this->post('mid');//项目id
		$mtype = $this->post('mtype');//项目模块
		$evaluation_time = $this->post('evaluation_time');//考评时间
		$file_ids = $this->post('file_ids');//文件ids
		
		if($mid == '' || $mid==0)exit('项目id为空');
		if($evaluation_time == '')exit('考评时间不能为空');
		
		$info = '';//返回的信息
		//有传入id并且id不为0则是编辑，否则是添加
		if($evaluation_id==0){//添加
			$reinfo = m('mf_evaluation')->insert(array(
				'mid' => $mid,
				'mtype' => $mtype,
				'evaluation_time' => $evaluation_time,
				'file_ids' => $file_ids
			));
			$painfo = m($mtype)->update(array('is_evaluation'=>1,'process_state'=>'考评','project_ku'=>'归档'),"id=$mid");
			//echo m($mtype)->getLastSql();
			if($reinfo && $painfo)
				$info =array('id' => $reinfo,'success' => true,'msg' => '考评信息录入成功');
			else{
				$info =array('id' => $reinfo,'success' => false,'msg' => '考评信息录入失败');
			}
		}else{//编辑
			$reinfo = m('mf_evaluation')->update(array(
				'evaluation_time' => $evaluation_time,
				'file_ids' => $file_ids
			),"evaluation_id=$evaluation_id");
			if($reinfo)
				$info =array('id' => $reinfo,'success' => true,'msg' => '考评信息更新成功');
			else{
				$info =array('id' => $reinfo,'success' => false,'msg' => '考评信息更新失败');
			}
		}
		//对相应上传的文件赋予项目id
		$idarr = explode(',', $file_ids);
		foreach ($idarr as $k => $file_id) {
			m('file')->update(array('mid'=>$mid,'mtype'=>$mtype),"id=$file_id");
		}
		unset($v,$file_id);
		$this->returnjson($info);
	}

	
	/**
	 * 资金拨付列表
	 */
	public function appropriationAjax(){
		$project_select = $this->post('project_select');//项目类别
		$deptname = $this->post('deptname');//申报单位
		$time_frame = $this->post('time_frame');//时间范围
		$project_name = $this->post('project_name');//项目名称
		$project_number = $this->post('project_number');//项目编号
		$optname = $this->post('optname');//项目负责人
		$where = '';//查询条件
		
		if($project_select!='') $where.= ' and c.project_select="'.trim($project_select).'"';
		if($deptname!='') $where.= ' and b.deptname="'.trim($deptname).'"';
		if($time_frame!=""){//时间范围
			list($start_time,$end_time) = explode(',', $time_frame);
			$where.=" and mp.appropriation_time between '".$start_time."' and '".$end_time."'";
			unset($start_time,$end_time);
		}
		if($project_name!='') $where.= ' and c.project_name like "%'.trim($project_name).'%"';
		if($project_number!='') $where.= ' and c.project_number="'.trim($project_number).'"';
		if($optname!='') $where.= ' and b.optname like "%'.trim($optname).'%"';
		$table = $this->Ctable.'left join `[Q]mf_appropriation` mp on case when c.is_appropriation=1 then mp.mtype=a.table and mp.mid=c.id and mp.is_delete=0 else "" end';
		$fields = 'a.table as mtype,c.project_name,DATE_FORMAT(mp.appropriation_time, "%Y-%m-%d") as appropriation_time,c.is_appropriation,c.id as mid,mp.file_ids,mp.appropriation_id as id,c.project_xingzhi,a.optname,b.deptname,c.project_select,c.project_number';
		/*@@资金拨付列表显示条件:库状态为"侯建库","建设库","归档"*/
		$where = 'a.isdel=0 and TRIM(c.project_ku) in ("侯建库","建设库","归档") and c.status<>5 '.$where;
		$order = 'a.optdt desc';
		$this->getlist($table, $fields, $where, $order, 'appropriation');
	}
	
	
	/**
	 * 资金拨付信息添加,编辑
	 */
	public function saveappropriationAjax(){
		$appropriation_id = $this->post('appropriation_id');//拨款信息id
		$mid = $this->post('mid');//项目id
		$mtype = $this->post('mtype');//项目模块
		$appropriation_time = $this->post('appropriation_time');//拨款时间
		$file_ids = $this->post('file_ids');//文件ids
		
		if($mid == '' || $mid==0)exit('项目id为空');
		if($appropriation_time == '')exit('付款时间不能为空');
		
		$info = '';//返回的信息
		//有传入id并且id不为0则是编辑，否则是添加
		if($appropriation_id==0){//添加
			$reinfo = m('mf_appropriation')->insert(array(
				'mid' => $mid,
				'mtype' => $mtype,
				'appropriation_time' => $appropriation_time,
				'file_ids' => $file_ids
			));
			$painfo = m($mtype)->update(array('is_appropriation'=>1),"id=$mid");
			//echo m($mtype)->getLastSql();
			if($reinfo && $painfo)
				$info =array('id' => $reinfo,'success' => true,'msg' => '拨付信息录入成功');
			else{
				$info =array('id' => $reinfo,'success' => false,'msg' => '拨付信息录入失败');
			}
		}else{//编辑
			$reinfo = m('mf_appropriation')->update(array(
				'appropriation_time' => $appropriation_time,
				'file_ids' => $file_ids
			),"appropriation_id=$appropriation_id");
			if($reinfo)
				$info =array('id' => $reinfo,'success' => true,'msg' => '拨付信息更新成功');
			else{
				$info =array('id' => $reinfo,'success' => false,'msg' => '拨付信息更新失败');
			}
		}
		//对相应上传的文件赋予项目id
		$idarr = explode(',', $file_ids);
		foreach ($idarr as $k => $file_id) {
			m('file')->update(array('mid'=>$mid,'mtype'=>$mtype),"id=$file_id");
		}
		unset($v,$file_id);
		$this->returnjson($info);
	}


	/**
	 * 公共的列表获取方法
	 */
	public function getlist($table,$fields,$where,$order,$childtable){
		$beforea = $this->request('storebeforeaction');//数据权限处理函数
		$aftera = $this->request('storeafteraction');//操作权限处理函数
		if($beforea != ''){//数据权限处理
			if(method_exists($this, $beforea)){
				$where .= $this->$beforea();
			}
		}
		$arr = $this->limitRows($table,$fields,$where,$order);
		$arr['totalCount'] = $arr['total'];
		unset($arr['sql'],$arr['total']);
		//echo $arr['sql'];exit;
		if($arr['totalCount'] == 0) exit('暂无数据');
		if(method_exists($this, $aftera)){//操作菜单权限处理
			$narr	= $this->$aftera($childtable,$arr['rows']);
			if(is_array($narr)){
				foreach($narr as $kv=>$vv)$arr['rows'][$kv]=$vv;
			}
		}
		$this->returnjson($arr);
	}
	
	/**
	 * 公共的删除方法
	 */
	public function publicdelAjax(){
		$mid = $this->request('mid');//项目id
		$table = base64_decode($this->request('table'));//对应的数据库表
		$mtype = base64_decode($this->request('mtype'));//项目模块
		$uptable = m("mf_$table")->update(array('is_delete=1'),'mid='.$mid.' and mtype='.$mtype);
		$upprojectapply = m($mtype)->update(array("is_$table=0"),'id='.$mid);
		$arr = array();
		if($uptable && $upprojectapply){
			$arr = array('id'=>$uptable,'success'=>true,'msg'=>'删除成功');
		}else{
			$arr = array('id'=>$uptable,'success'=>false,'msg'=>'删除失败');
		}
		$this->returnjson($arr);
	}

	
	
	/**
	 * 获取表单信息
	 */
	public function loadformAjax(){
		$id = (int)$this->request('id',0);//对应的id
		$table = base64_decode($this->request('table'));//表名
		$data = m("mf_$table")->getone($table."_id=$id");
		//echo m("mf_$table")->getLastSql();
		if($data) $data['pass']='';
		$arr['data'] = $data;
		$this->returnjson($arr);
	}
	
	/**
	 * 获取对应上传了的文件
	 */
	public function getupfilesAjax(){
		$id = $this->request('id');//对应的id
		$table = base64_decode($this->request('table'));//表名
		$info = m("mf_$table")->getone($table."_id=$id",'file_ids');
		$files = array();
		foreach(explode(',', $info['file_ids']) as $k => $v) {
			$files[$k] = m('file')->getone('id='.$v);
		}
		unset($k,$v);
		if($info['file_ids']=='') $this->returnjson('');
		$this->returnjson($files);
	}
	
	
	/**
	 * 数据权限处理方法
	 * 申报者（数据：自己的项目）$group_id=1
	 * 上级领导（数据：该单位的项目）$group_id=2
	 * 校项目办公室（数据：全部）$group_id=3
	 */
	public function dataauthbefore(){
		//获取当前账号的角色
		$this->admin_info=m('admin')->getone('id='.$this->adminid);
		$this->group_id=m('sjoin')->getone('sid='.$this->adminid.' and type="gu"','max(mid) as mid')['mid'];
		$where='';//条件
		switch ((int)$this->group_id) {
			case 1:
				$where.=" and a.uid=".$this->adminid." and c.isturn=1";
				break;
			case 2:
				$s=m('dept')->getuidhead($this->adminid);
				$where=" and deptid in(".$s.") and c.isturn=1";
				break;
			case 3:
				$where=" and c.isturn=1";
				break;
			default:
				$where=" and c.isturn=1";
				break;
		}
		
		if($this->admin_info['is_admin']==1){
			$where=" and c.isturn=1";
		}
		return $where;
	}
	
	
	/**
	 * 操作菜单处理方法
	 * 申报者（权限：采购增查改、资金拨付查、验收查、付款查、考评查） group_id=1
	 * 上级领导（权限：采购查、资金拨付查、验收查、付款查、考评查） group_id=2
	 * 校项目办公室（权限：采购增查改、资金拨付增查改、验收增查改、付款增查改、考评增查改）group_id=3
	 */
	public function extrainfoafter($table,$rows){
		foreach($rows as $k=>$rs){
			if($rs['id']==null)$rs['id']=0;
			switch ((int)$this->group_id) {
				case 1:
					$rows[$k]['caoz']='';
					if((int)$rs['is_'.$table]==0){
						$rows[$k]['caoz'].= '<a onclick="'.$table.'_add('.$rs['mid'].',\''.$rs['mtype'].'\',\''.$rs['project_name'].'\')">录入</a>';
					}else{
						$rows[$k]['caoz'].= '<a onclick="'.$table.'_check('.$rs['mid'].','.$rs['id'].',\''.$rs['project_name'].'\')">查看</a>';
						$rows[$k]['caoz'].= '<span style="padding:5px;">|</span>';
						$rows[$k]['caoz'].= '<a onclick="'.$table.'_edit('.$rs['mid'].',\''.$rs['mtype'].'\','.$rs['id'].',\''.$rs['project_name'].'\')">编辑</a>';
					}
					break;
				case 2:
					$rows[$k]['caoz']='';
					$rows[$k]['caoz'] = '<a onclick="'.$table.'_check('.$rs['mid'].','.$rs['id'].',\''.$rs['project_name'].'\')">查看</a>';
					break;
				case 3:
					$rows[$k]['caoz']='';
					if((int)$rs['is_'.$table]==0){
						$rows[$k]['caoz'].= '<a onclick="'.$table.'_add('.$rs['mid'].',\''.$rs['mtype'].'\',\''.$rs['project_name'].'\')">录入</a>';
					}else{
						$rows[$k]['caoz'].= '<a onclick="'.$table.'_check('.$rs['mid'].','.$rs['id'].',\''.$rs['project_name'].'\')">查看</a>';
						$rows[$k]['caoz'].= '<span style="padding:5px;">|</span>';
						$rows[$k]['caoz'].= '<a onclick="'.$table.'_edit('.$rs['mid'].',\''.$rs['mtype'].'\','.$rs['id'].',\''.$rs['project_name'].'\')">编辑</a>';
					}
					break;
				
				default:
					$rows[$k]['caoz']='';
					if((int)$rs['is_'.$table]==0){
						$rows[$k]['caoz'].= '<a onclick="'.$table.'_add('.$rs['mid'].',\''.$rs['mtype'].'\',\''.$rs['project_name'].'\')">录入</a>';
					}else{
						$rows[$k]['caoz'].= '<a onclick="'.$table.'_check('.$rs['mid'].','.$rs['id'].',\''.$rs['project_name'].'\')">查看</a>';
						$rows[$k]['caoz'].= '<span style="padding:5px;">|</span>';
						$rows[$k]['caoz'].= '<a onclick="'.$table.'_edit('.$rs['mid'].',\''.$rs['mtype'].'\','.$rs['id'].',\''.$rs['project_name'].'\')">编辑</a>';
					}
					break;
			}
		}
		//var_dump($rows);
		return $rows;
	}
	
	//end
}