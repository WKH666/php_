<?php
class constructionClassAction extends Action
{
    private $ph_conn = '';//采购系统中间库连接信息
    private $ph_errormsg = '';//错误信息
    private $admin_info = array();//用户信息
    private $group_id = '';//组id（角色id）
    private $Ctable="`[Q]flow_bill` a left join `[Q]admin` b on a.uid=b.id left join `whole_projects` c on a.mid=c.id ";//默认要查询这3个表

    //连接采购系统中间库
    private function ph_connect(){
        $this->ph_conn = @new mysqli(getconfig('ph_host'), getconfig('ph_user'), getconfig('ph_pass'), getconfig('ph_base'));
        if (mysqli_connect_errno()) {
            exit('采购系统数据库连接失败，请联系管理员！');
            //$this->ph_conn 	= null;
            //$this->ph_errormsg	= mysqli_connect_error();
        }else{
            //$this->selectdb(getconfig('ph_base'));
            //$this->ph_conn->query("SET NAMES 'utf8'");
            mysqli_query($this->ph_conn, "SET NAMES 'utf8'");
        }
    }

    //查询列表
    private function ph_get_all($sql){
        $rows = array();
        $result = mysqli_query($this->ph_conn, $sql);
        while($row=mysqli_fetch_assoc($result)){
        //返回根据从结果集取得的行生成的数组，如果没有更多行则返回 FALSE。
            $rows[] = $row;
        }
        return $rows;
    }

    //查询某行信息
    private function ph_get_one($sql){
        $result = mysqli_query($this->ph_conn, $sql);
        return mysqli_fetch_assoc($result);
    }

    //数据插入
    private function ph_insert($data = array()){
        $values = '';
        $columnArr = array_keys($data);
        foreach ($columnArr as $val){
            if(is_numeric($data[$val])){
                $values .= ','.$data[$val];
            }else{
                $values .= ',"'.$data[$val].'"';
            }
        }
        $columns = implode(',', $columnArr);
        $values = trim($values, ',');
        $sql = "INSERT INTO `budget_project` ($columns) VALUES ($values)";
        $result = mysqli_query($this->ph_conn, $sql);
        //if(!empty($result)) $result = mysqli_insert_id($this->ph_conn);
        return $result;
    }

    //数据更新
    private function ph_update($data = array(), $where){
        $content = '';
        foreach ($data as $key=>$val){
            if(is_numeric($val)){
                $content .= ",$key=$val";
            }else{
                $content .= ",$key='$val'";
            }
        }
        $content = trim($content, ',');
        $sql = "UPDATE `budget_project` SET $content WHERE $where ";
        return mysqli_query($this->ph_conn, $sql);
    }


    /**
     * 资金拨付列表
     * @param string $project_select 项目类型
     * @param string $deptname 申报单位
     * @param time $time_frame 申报时间范围
     * @param string $project_name 项目名称
     * @param string $project_number 项目编号
     * @param string $optname 项目负责人
     */
    public function appropriationAjax(){
        $project_select = $this->post('project_select');//项目类别
        $deptname = $this->post('deptname');//申报单位
        $time_frame = $this->post('time_frame');//时间范围
        $project_name = $this->post('project_name');//项目名称
        $project_number = $this->post('project_number');//项目编号
        $optname = $this->post('optname');//项目负责人
        $where = '';//查询条件

        if($project_select!='') $where.= ' AND c.project_select="'.trim($project_select).'"';
        if($deptname!='') $where.= ' AND b.deptname="'.trim($deptname).'"';
        if($time_frame!=""){//时间范围
            list($start_time,$end_time) = explode(',', $time_frame);
            $where.=" AND mp.appropriation_time between '".$start_time."' AND '".$end_time."'";
            unset($start_time,$end_time);
        }
        if($project_name!='') $where.= ' AND c.project_name like "%'.trim($project_name).'%"';
        if($project_number!='') $where.= ' AND c.project_number="'.trim($project_number).'"';
        if($optname!='') $where.= ' AND b.optname like "%'.trim($optname).'%"';
        $table = $this->Ctable.'left join `[Q]mf_appropriation` mp on case when c.is_appropriation=1 then mp.mtype=a.table AND mp.mid=c.id AND mp.is_delete=0 else "" end';
        $fields = 'a.table as mtype,c.project_name,DATE_FORMAT(mp.appropriation_time, "%Y-%m-%d") as appropriation_time,c.is_appropriation,c.id as mid,mp.file_ids,mp.appropriation_id as id,c.project_xingzhi,a.optname,b.deptname,c.project_select,c.project_number';
        /*@@资金拨付列表显示条件:库状态为"侯建库","建设库","归档" 并且已网评、未删除、未作废*/
        $where = 'a.isdel=0 AND c.is_wp=1 AND TRIM(c.project_ku) in ("侯建库","建设库","归档") AND c.status<>5 '.$where;
        $order = 'a.optdt desc';
        $this->getlist($table, $fields, $where, $order, 'appropriation');
    }


    /**
     * 资金拨付信息添加,编辑
     */
    public function saveappropriationAjax(){
        $this->ph_connect();
        $appropriation_id = $this->post('appropriation_id');//拨款信息id
        $mid = $this->post('mid');//项目id
        $mtype = $this->post('mtype');//项目模块
        $appropriation_time = $this->post('appropriation_time');//拨款时间
        $file_ids = $this->post('file_ids');//文件ids

        $project_zhijingly = $this->post('project_zhijingly');//经费来源
        $financial_card_number = $this->post('financial_card_number');//经费卡号
        $balance_of_funds = $this->post('balance_of_funds');//经费金额
        $amount_of_funds = $this->post('amount_of_funds');//经费余额
        $dept = $this->post('dept');//所属部门
        $person_in_charge = $this->post('person_in_charge');//负责人
        $telphone = $this->post('telphone');//联系电话

        if($mid == '' || $mid==0)exit('项目id为空');
        if($appropriation_time == '')exit('付款时间不能为空');
        if(empty($project_zhijingly))exit('请选择经费来源');
        if(empty($balance_of_funds))exit('经费金额不能为空');
        if(empty($dept))exit('请选择所属部门');
        if(empty($person_in_charge))exit('负责人不能为空');
        if(empty($telphone))exit('联系电话不能为空');

        $info = array();
        $project_info = m($mtype)->getone("id=$mid");
        //有传入id并且id不为0则是编辑，否则是添加
        if($appropriation_id==0){//添加
            //获取经费项目表（budget_project）最后一行的id
            $maxId = $this->ph_get_one("SELECT MAX(id) as maxid from `budget_project` limit 1")['maxid'];
            $insertId = intval($maxId) + 1;
            $insertData = array(
                'id' => $insertId,
                'construction_annual' => date('Y', strtotime($project_info['project_apply_time'])),
                'budget_code' => $project_info['project_number'],
                'budget_name' => $project_info['project_name'],
                'budget_category' => $project_info['project_select'],
                'budget_source' => $project_zhijingly,
                'budget_card_number' => $financial_card_number,
                'budget_amount' => $balance_of_funds,
                'remainder' => $amount_of_funds,
                'department' => $dept,
                'budget_director' =>$person_in_charge,
                'budget_director_number' => $telphone,
                'create_name' => $project_info['project_head'],
                'create_date' => $project_info['project_apply_time'],
                'status' => 1,
            );
            $rs = $this->ph_insert($insertData);

            $reinfo = m('mf_appropriation')->insert(array(
                'mid' => $mid,
                'mtype' => $mtype,
                'appropriation_time' => $appropriation_time,
                'file_ids' => $file_ids
            ));
            $painfo = m($mtype)->update(array('is_appropriation'=>1),"id=$mid");
            //echo m($mtype)->getLastSql();
            if($rs && $reinfo && $painfo)
                $info =array('id' => $reinfo,'success' => true,'msg' => '拨付信息录入成功');
            else{
                $info =array('id' => $reinfo,'success' => false,'msg' => '拨付信息录入失败');
            }
        }else{//编辑
            $updateData = array(
                'budget_source' => $project_zhijingly,
                'budget_card_number' => $financial_card_number,
                'budget_amount' => $balance_of_funds,
                'remainder' => $amount_of_funds,
                'department' => $dept,
                'budget_director' =>$person_in_charge,
                'budget_director_number' => $telphone,
                'status' => 1,
            );
            $rs = $this->ph_update($updateData, "budget_code='".$project_info['project_number']."' AND status=1");
            $reinfo = m('mf_appropriation')->update(array(
                'appropriation_time' => $appropriation_time,
                'file_ids' => $file_ids
            ),"appropriation_id=$appropriation_id");
            if($rs || $reinfo)
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
        mysqli_close($this->ph_conn);
        $this->returnjson($info);
    }



    /**
     * 采购、验收、付款列表
     */
    public function purchaseAjax(){
        $this->ph_connect();
        $condition = '';$order = '';
        $budget_name = $this->post('budget_name');//项目名称
        $p_cost = $this->post('budget');//采购金额范围
        $contract_date = $this->post('contract_date');//合同签订时间范围
        $it_order = $this->post('it_order');//验收人员
        $accept_date = $this->post('accept_date');//验收时间范围
        $s_pay_money = $this->post('s_pay_money');//应付金额（元）范围
        $pay_date = $this->post('pay_date');//付款时间范围
        $is_cg = $this->post('is_cg');//是否已采购
        $is_ys = $this->post('is_ys');//是否已验收
        $is_fk = $this->post('is_fk');//是否已付款

        if(!empty($budget_name)) $condition.= ' AND budget_name LIKE "%'.trim($budget_name).'%"';
        if(!empty($p_cost)){
            list($min_p_cost,$max_p_cost) = explode(',', $p_cost);
            $condition.= " AND p_cost BETWEEN ".$min_p_cost." AND \'".$max_p_cost."\'";
        }
        if(!empty($contract_date)){//合同签订时间范围
            list($start_time,$end_time) = explode(',', $contract_date);
            $condition.=" AND contract_date BETWEEN '".$start_time."' AND '".$end_time."'";
            unset($start_time,$end_time);
        }
        if(!empty($it_order)) $condition.= " AND it_order LIKE '%".trim($it_order)."%'";
        if(!empty($accept_date)){//验收时间范围
            list($start_time,$end_time) = explode(',', $accept_date);
            $condition.=" AND accept_date BETWEEN '".$start_time."' AND '".$end_time."'";
            unset($start_time,$end_time);
        }
        if(!empty($s_pay_money)){
            list($min_s_pay_money,$max_s_pay_money) = explode(',', $p_cost);
            $condition.= " AND s_pay_money BETWEEN ".$min_s_pay_money." AND \'".$max_s_pay_money."\'";
        }
        if(!empty($pay_date)){//付款时间范围
            list($start_time,$end_time) = explode(',', $pay_date);
            $condition.=" AND pay_date BETWEEN '".$start_time."' AND '".$end_time."'";
            unset($start_time,$end_time);
        }
		
		if(!empty($is_cg)){
			$condition.=" AND contract_date IS NOT NULL ";
		}
		if(!empty($is_ys)){
			$condition.=" AND accept_date IS NOT NULL ";
		}
		if(!empty($is_fk)){
			$condition.=" AND pay_date IS NOT NULL ";
		}

        //var_dump(trim($condition));exit;
        if(!empty($condition)) $condition = "WHERE ".trim($condition, 'AND');
        if(!empty($order)) $order = "ORDER BY $order";
        $count = $this->ph_get_one("SELECT COUNT(*) as count FROM pub_project $condition")['count'];
        $rows = $this->ph_get_all("SELECT * FROM pub_project $condition $order ".$this->getLimit());
        $data['rows'] = $rows;
        $data['totalCount'] = $count;
        mysqli_close($this->ph_conn);
        $this->returnjson($data);
    }



	/**
	 * 绩效考评列表
	 */
	public function evaluationAjax(){
		$this->ph_connect();
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
		$project_numbers = $this->ph_get_all("SELECT budget_code FROM pub_project WHERE contract_date IS NOT NULL AND accept_date IS NOT NULL AND pay_date IS NOT NULL");
		$where_str = '';
		foreach ($project_numbers as $key => $value) {
			$where_str .= ",'".$value['budget_code']."'";
		}
		unsert($key, $value);
		$where .= "AND c.project_number IN (".$where_str.")";
		
		$table = $this->Ctable.'left join `[Q]mf_evaluation` mp on case when c.is_evaluation=1 then mp.mtype=a.table and mp.mid=c.id and mp.is_delete=0 else "" end ';
		$fields = 'a.table as mtype,c.project_name,DATE_FORMAT(mp.evaluation_time, "%Y-%m-%d") as evaluation_time,c.is_evaluation,c.id as mid,mp.file_ids,mp.evaluation_id as id,c.project_xingzhi,a.optname,b.deptname,c.project_select,c.project_number';
		/*@@付款列表显示条件:已拨付和已付款 和 已采购 和已验收 和已付款*/
		$where = 'a.isdel=0 and c.status<>5 and c.is_appropriation=1 and c.is_purchase=1 and c.is_accept=1 and c.is_payment=1 '.$where;
		$order = 'a.optdt desc';
		mysqli_close($this->ph_conn);
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
        $uptable = m("mf_$table")->update(array('is_delete=1'),'mid='.$mid.' AND mtype='.$mtype);
        $upprojectapply = m($mtype)->update(array("is_$table=0"),'id='.$mid);
        $arr = array();
        if($uptable && $upprojectapply){
            $arr = array('id'=>$uptable,'success'=>true,'msg'=>'删除成功');
        }else{
            $arr = array('id'=>$uptable,'success'=>false,'msg'=>'删除失败');
        }
        $this->returnjson($arr);
    }


    //根据项目编号获取对应的采购、验收、付款信息
    public function loadpubprojectAjax(){
        $this->ph_connect();
        $id = (int)$this->request('id',0);//对应的id
        $pub_project = $this->ph_get_one("SELECT * FROM `pub_project` WHERE id=$id limit 1");
        $arr['data'] = $pub_project;
        mysqli_close($this->ph_conn);
        $this->returnjson($arr);
    }



    /**
     * 获取表单信息
     */
    public function loadformAjax(){
        $this->ph_connect();
        $id = (int)$this->request('id',0);//对应的id
        $table = base64_decode($this->request('table'));//表名
        $data = m("mf_$table")->getone($table."_id=$id");
        $data['files'] = array();
        if(!empty($data['file_ids'])){
            foreach(explode(',', $data['file_ids']) as $k => $v) {
                $data['files'][$k] = m('file')->getone('id='.$v);
            }
            unset($k,$v);
        }
        $project_info = m($data['mtype'])->getone("id=".$data['mid']);
        //var_dump($project_info);
        $budget_project = $this->ph_get_one("SELECT * FROM `budget_project` WHERE budget_code='".$project_info['project_number']."' AND status=1 limit 1");
        $arr['data'] = array_merge($data, $budget_project);
        mysqli_close($this->ph_conn);
        $this->returnjson($arr);
    }

//    /**
//     * 获取对应上传了的文件
//     */
//    public function getupfilesAjax(){
//        $id = $this->request('id');//对应的id
//        $table = base64_decode($this->request('table'));//表名
//        $info = m("mf_$table")->getone($table."_id=$id",'file_ids');
//        $files = array();
//        foreach(explode(',', $info['file_ids']) as $k => $v) {
//            $files[$k] = m('file')->getone('id='.$v);
//        }
//        unset($k,$v);
//        if($info['file_ids']=='') $this->returnjson('');
//        $this->returnjson($files);
//    }


    /**
     * 数据权限处理方法
     * 申报者（数据：自己的项目）$group_id=1
     * 上级领导（数据：该单位的项目）$group_id=2
     * 校项目办公室（数据：全部）$group_id=3
     */
    public function dataauthbefore(){
        //获取当前账号的角色
        $this->admin_info=m('admin')->getone('id='.$this->adminid);
        $this->group_id=m('sjoin')->getone('sid='.$this->adminid.' AND type="gu"','max(mid) as mid')['mid'];
        $where='';//条件
        switch ((int)$this->group_id) {
            case 1:
                $where.=" AND a.uid=".$this->adminid." AND c.isturn=1";
                break;
            case 2:
                $s=m('dept')->getuidhead($this->adminid);
                $where=" AND deptid in(".$s.") AND c.isturn=1";
                break;
            case 3:
                $where=" AND c.isturn=1";
                break;
            default:
                $where=" AND c.isturn=1";
                break;
        }

        if($this->admin_info['is_admin']==1){
            $where=" AND c.isturn=1";
        }
        return $where;
    }


    /**
     * 操作菜单处理方法
     * 申报者（权限：采购增查改、资金拨付查、验收查、付款查、考评查） group_id=1
     * 上级领导（权限：采购查、资金拨付查、验收查、付款查、考评查） group_id=2
     * 校项目办公室（权限：采购增查改、资金拨付增查改、验收增查改、付款增查改、考评增查改）group_id=3
     */
    public function constructionafter($table,$rows){
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
        return $rows;
    }

}