<?php

/*
 * http://localhost/xiangmukuV0.4/api.php?m=openupdateuser&a=updatemsg&openkey=46f86faa6bbf9ac94a7e459509a20ed0
 */
class openupdateuserClassAction extends openapiAction{
	
	private $conn           = '';//数据库连接信息
	
	public function initAction(){
		set_time_limit(0);
		$this->display = false;
		$openkey = $this -> post('openkey');
		if ($openkey != '46f86faa6bbf9ac94a7e459509a20ed0')
			$this -> showreturn('', 'openend not access', 201);
		
		$UserName = getconfig("orcle_db_user");
		$Password = getconfig("orcle_db_pass");
		$InstanceName = getconfig("orcle_instance_name");
		$ConnectUrl = getconfig("orcle_db_host");
		$Port = getconfig("orcle_port");
		$Code = getconfig("orcle_code");
		$this->conn = oci_connect($UserName, $Password, "{$ConnectUrl}:{$Port}/{$InstanceName}", $Code);

		$content = "调用时间：".date("Y-m-d H:i:s",time()).PHP_EOL;
        $content .= "=================================================".PHP_EOL;
        if(!$this->conn){
            $content .= "orcle数据库连接失败".PHP_EOL;
            $Error = oci_error();
            $content .= print_r(htmlentities($Error['message']), true).PHP_EOL;
        }else{
            $content .= "orcle数据库连接成功".PHP_EOL;
        }
        $content .= "orcle数据库连接".PHP_EOL;
        $content .= "=================================================".PHP_EOL.PHP_EOL.PHP_EOL.PHP_EOL.PHP_EOL;
        file_put_contents('updateuser.log', $content, FILE_APPEND);

        if(!$this->conn){
			$Error = oci_error();
		    print htmlentities($Error['message']);
		    exit;
		}
	}
	
	//获取全部用户信息
	public function getUserData(){
		$this->initAction();
		//查询用户信息视图获取用户
		$column = array("ZGH","XM","DEPT_ID","SZDW","ZGHID","DWLB","RYLB","XMPYM","CYM","XB","GJDQ","SFZJLX","SFZH","CSRQ","AGE","GJ","HKXZ","HKSZD","CSD","MZ","SFHQ","ZZMM","RDSJ","HYZT","BZ","MOBILE1","MOBILE2","EMAIL","JTDZ","JTLXFS","CJGZSJ","LXGL","LYSJ","XNGL","GZDD","JZGLB","SFSYBZ","ZGZT","SFXWFP","GWMC","RXGWSJ","GWBZ","XZZW","XZZWJB","RXXZZWJBSJ","ZGWLB","ZGWDJ","ZGWPYSJ","SFSJT","SJTGWLB","SJTGWDJ","SJTGWPYSJ","ZGXL","HDZGXLJG","ZGXLBYSJ","ZGXLZY","ZGXW","HDZGXWJG","ZGXWBYSJ","ZGXWZY","ZYLY","ZYTC","ZGZYJSZWMC","ZGZYJSZWDJ","ZGZYJSZWZY","ZGZYJSZWHQRQ","PRZYJSZWMC","PRZYJSZWDJ","ZYZGMC","ZYZGDJ","ZYZGFZDW","ZYZGTGRQ","SFSS","SSLX","SFGGJS","SFZYFZ","SFZYDTR","SFYGXJSZGZ","GXJSZGZFZDW","GXJSZGZHM","GXSZGZTGRQ","BY1","WID","CZLX","CLRQ","SJLY","ZHZT","LZRQ","ROWID");
		$sql = 'SELECT "ZGH","XM","DEPT_ID","SZDW","ZGHID","DWLB","RYLB","XMPYM","CYM","XB","GJDQ","SFZJLX","SFZH","CSRQ","AGE","GJ","HKXZ","HKSZD","CSD","MZ","SFHQ","ZZMM","RDSJ","HYZT","BZ","MOBILE1","MOBILE2","EMAIL","JTDZ","JTLXFS","CJGZSJ","LXGL","LYSJ","XNGL","GZDD","JZGLB","SFSYBZ","ZGZT","SFXWFP","GWMC","RXGWSJ","GWBZ","XZZW","XZZWJB","RXXZZWJBSJ","ZGWLB","ZGWDJ","ZGWPYSJ","SFSJT","SJTGWLB","SJTGWDJ","SJTGWPYSJ","ZGXL","HDZGXLJG","ZGXLBYSJ","ZGXLZY","ZGXW","HDZGXWJG","ZGXWBYSJ","ZGXWZY","ZYLY","ZYTC","ZGZYJSZWMC","ZGZYJSZWDJ","ZGZYJSZWZY","ZGZYJSZWHQRQ","PRZYJSZWMC","PRZYJSZWDJ","ZYZGMC","ZYZGDJ","ZYZGFZDW","ZYZGTGRQ","SFSS","SSLX","SFGGJS","SFZYFZ","SFZYDTR","SFYGXJSZGZ","GXJSZGZFZDW","GXJSZGZHM","GXSZGZTGRQ","BY1","WID","CZLX","CLRQ","SJLY","ZHZT","LZRQ","ROWID" FROM T_JZG';//查询T_JZG视图
		$result = oci_parse($this->conn, $sql);
		if(!$result){
			$e = oci_error($this->conn);
			print htmlentities($e['message']);
			exit;
		}
		oci_execute($result);
		$rows = array();
		//$num = 1;
		while($row=oci_fetch_array($result, OCI_ASSOC)){
			foreach ($column as $k => $val) {
				if(!isset($row[$val])){
					$row[$val] = '';
				}
			}
			unset($k, $val);
			$this->checkUserExist($row);
			//echo $num.'<br>';
			//$num++;
		}
		oci_free_statement($result);
		oci_close($this->conn);
		return $rows;
	}
	
	//获取全部部门信息
	public function getDeptData(){
		$this->initAction();
		//查询部门信息视图获取部门信息
		$column = array("DEPT_ID","DWDM","DWMC","DWLB","DWQC");
		$sql = 'SELECT "DEPT_ID","DWDM","DWMC","DWLB","DWQC" FROM T_DW';//查询T_JZG视图
		$result = oci_parse($this->conn, $sql);
		if(!$result){
			$e = oci_error($this->conn);
			print htmlentities($e['message']);
			exit;
		}
		oci_execute($result);
		$rows = array();
		while($row=oci_fetch_array($result, OCI_ASSOC)){
			foreach ($column as $k => $val) {
				if(!isset($row[$val])){
					$row[$val] = '';
				}
			}
			unset($k, $val);
			$this->checkDeptExist($row);
		}
		oci_free_statement($result);
		oci_close($this->conn);
		return $rows;
	}
	
	//获取本系统中的所有的工号
	public function getSystemNum(){
		$system_user_num = m('admin')->getall("", "num");
		$nums = array();
		foreach ($system_user_num as $k => $val) {
			$nums[] = $val['num'];
		}
		unset($k, $val);
		return $nums;
	}
	
	//获取部门信息对比表中的DEPT_ID
	public function getDeptCheckDWDM(){
		$system_user_num = m('dept_check')->getall("", "DWDM");
		$nums = array();
		foreach ($system_user_num as $k => $val) {
			$nums[] = $val['DWDM'];
		}
		unset($k, $val);
		return $nums;
	}
	
	//检查该部门是否存在于本系统
	public function checkDeptExist($val){
		if(in_array($val['DWDM'], $this->getDeptCheckDWDM())){
			//echo 'ok<br>';//存在
			//更新对应的部门名
			//判断名称是否有改变
			$dwdm = m('dept_check')->getone("DWDM='".$val['DWDM']."'", "DWMC,THMC");
			if($dwdm['DWMC'] != $val['DWMC']){
				m('dept_check')->update(array('DWMC'=>$val['DWMC'], 'THMC'=>$val['DWMC']), "DWDM='".$val['DWDM']."'");
				$dept = m('dept')->getone("name like '".$dwdm['THMC']."'", "id");
				$dept_id = $dept['id'];
				m('dept')->update(array('name'=>$val['DWMC']),"id=$dept_id");
			}
		}else{
			//echo '<font color="red">false</font><br>';//不存在，则添加该用户信息
			m('dept')->insert(array(
				'num' => $val['DEPT_ID'],
				'code' => $val['DWDM'],
				'name' => $val['DWMC'],
				'pid' => 0,
				'sort' => 0,
				'optdt' => date("Y-m-d H:i:s", time()),
			));
			m('dept_check')->insert(array(
				'DEPT_ID' => $val['DEPT_ID'],
				'DWDM' => $val['DWDM'],
				'DWMC' => $val['DWMC'],
				'DWLB' => $val['DWLB'],
				'DWQC' => $val['DWQC'],
				'THMC' => $val['DWMC'],
			));
		}
	}
	
	//更新部门的层级关系
	public function setLevel(){
		$pid = 0;
		$depts = m('dept')->getall("code is not null");
		foreach ($depts as $k => $val) {
			switch (mb_strlen($val['code'],"utf-8")) {
				case 4:
					$dwdm = substr($val['code'], 0, 2);
					$thmc = m('dept_check')->getone("DWDM=$dwdm", "THMC");
					$dept = m('dept')->getone("name like '".$thmc['THMC']."'", "id");
					$pid = $dept['id'];
					m('dept')->update(array('pid'=>$pid), "id=".$val['id']);
					break;
				case 6:
					$dwdm = substr($val['code'], 0, 4);
					$thmc = m('dept_check')->getone("DWDM=$dwdm", "THMC");
					$dept = m('dept')->getone("name like '".$thmc['THMC']."'", "id");
					$pid = $dept['id'];
					m('dept')->update(array('pid'=>$pid), "id=".$val['id']);
					break;
				default:
					break;
			}
		}
		unset($k, $val);
	}

	
	//检查该用户是否存在于本系统
	public function checkUserExist($val){
		if(in_array($val['ZGH'], $this->getSystemNum())){
			//存在
			//echo 'exist<br>';
			$admin = m('admin')->getone("num=".$val['ZGH']);
			$userinfo = m('userinfo')->getone("num=".$val['ZGH']);
			$admin_data = $userinfo_data = array();
			if($admin['name'] != $val['XM']){
				$admin_data['name'] = $val['XM'];
				$userinfo_data['name'] = $val['XM'];
			}
			//这里差一个部门名称的判断
			if($admin['deptname'] != $val['SZDW']){
				$dept = m('dept')->getone("name like '".$val['SZDW']."'", "id,name,pid");
				$dept = m('dept')->getone("name like '".$val['SZDW']."'", "id,name,pid");
				$pid = $dept['pid'];
				$detpid = $dept['id'];
				$deptname = $dept['name'];
				$deptallname = $dept['name'];
				$deptpath = '['.$dept['id'].']';
				
				while ($pid > 0) {
					$temp = m('dept')->getone("id = $pid", "id,name,pid");
					$pid = $temp['pid'];
					$deptallname = $temp['name'].'/'.$deptallname;
					$deptpath = '['.$temp['id'].'],'.$deptpath;
				}
				$admin_data['deptid'] = $detpid;
				$admin_data['deptname'] = $deptname;
				$admin_data['deptallname'] = $deptallname;
				$admin_data['deptpath'] = $deptpath;
			}
			
			//离职判断
			if(strpos($val['RYLB'], "离") === false){
				$admin_data['status'] = 1;
				$userinfo_data['state'] = 1;
			}else{
				$admin_data['status'] = 0;
				$userinfo_data['state'] = 5;
			}
			//GWMC、GWBZ、XZZW
			if($admin['ranking'] != $val['GWMC'] && $admin['ranking'] != $val['GWBZ'] && $admin['ranking'] != $val['XZZW']){
				$ranking = '';
				if(!empty($val['XZZW'])){
					$ranking = $val['XZZW'];
				}
				if(!empty($val['GWBZ'])){
					$ranking = $val['GWBZ'];
				}
				if(!empty($val['GWMC'])){
					$ranking = $val['GWMC'];
				}
				$admin_data['ranking'] = $userinfo_data['ranking'] = $ranking;
				
				//判断是否是领导
				//处长、院长、正处、科长、部长、主任、馆长、校长
				//GWMC、GWBZ、XZZW、    XZZWJB
				$leader = getconfig('leader');
				$is_leader = false;//默认不是领导
				foreach ($leader as $l_k => $l_val) {
					if(strpos($val['GWMC'], $l_val) !== false || strpos($val['GWBZ'], $l_val) !==false || strpos($val['XZZW'], $l_val) !==false){
						$is_leader = true;
						continue;
					}
				}
				unset($l_k, $l_val);
				if($is_leader){
					if(strpos($val['XZZWJB'], "正") !== false){
						$updateheadman = m('dept')->update(array(
							'headman'=>$val['XM'],
							'headid'=>$admin['id'],
						),"id=".$admin['deptid']);
					}
					$mid = 2;
				}else{
					$mid = 1;
				}
				//若不为部门负责人|校项目管理办公室，则全部设为申报者
				$update_sjoin = m('sjoin')->update(array(
					'mid'=>$mid,//1为申报者 , 2位单位领导
					'indate'=>date('Y-m-d H:i:s', time())
				),"type='gu' and sid=".$admin['id']);
			}
			if($admin['mobile'] != $val['MOBILE1'] && $admin['mobile'] != $val['MOBILE2']){
				$admin_data['mobile'] = $val['MOBILE1']==''?$val['MOBILE2']:$val['MOBILE1'];
				$userinfo_data['mobile'] = $val['MOBILE1']==''?$val['MOBILE2']:$val['MOBILE1'];
			}
			if($userinfo['idnum'] != $val['SFZH']){
				$userinfo_data['idnum'] = $val['SFZH'];
			}
			m('admin')->update($admin_data,"num=".$val['ZGH']);
			m('userinfo')->update($userinfo_data,"num=".$val['ZGH']);
		}else{
			//不存在，则添加该用户信息
			//echo '<font color="red">no exist</font><br>';
			$detpid = '';$deptname = '';$deptallname = '';$deptpath = '';$thmc = '';
			$thmc = m('dept_check')->getone("DWMC like '%".$val['SZDW']."%'", "THMC");
			if(!empty($thmc)){
				//特殊处理
				$dept = m('dept')->getone("name like '".$thmc['THMC']."'", "id,name,pid");
				$pid = $dept['pid'];
				$detpid = $dept['id'];
				$deptname = $dept['name'];
				$deptallname = $dept['name'];
				$deptpath = '['.$dept['id'].']';
				
				while ($pid > 0) {
					$temp = m('dept')->getone("id = $pid", "id,name,pid");
					$pid = $temp['pid'];
					$deptallname = $temp['name'].'/'.$deptallname;
					$deptpath = '['.$temp['id'].'],'.$deptpath;
				}
			}
			
			//离职判断
			$status = 0;$state = 5;
			if(strpos($val['RYLB'], "离") === false){
				$status = 1;
				$state = 1;
			}else{
				$status = 0;
				$state = 5;
			}
			//向pl_admin插入数据
			$admin_id = m('admin')->insert(array(
				'num'=>$val['ZGH'],
				'user'=>$val['ZGH'],
				'name'=>$val['XM'],
				'pass'=>md5(substr($val['SFZH'],-6)),
				'loginci'=>0,
				'status'=>$status,
				'type'=>0,//全部为普通用户
				'sex'=>$val['XB'],
				'tel'=>NULL,
				'face'=>NULL,
				'deptid'=>$detpid,//根据部门修改id
				'deptname'=>$deptname,//部门名称
				'deptallname'=>$deptallname,//部门全部路径
				'superid'=>NULL,
				'ranking'=>$val['GWMC'],
				'sort'=>0,
				'deptpath'=>$deptpath,
				'superpath'=>NULL,
				'groupname'=>NULL,
				'mobile'=>$val['MOBILE1']==''?$val['MOBILE2']:$val['MOBILE1'],
				'apptx'=>1,
				'workdate'=>$val['RXGWSJ'],
				'email'=>$val['EMAIL'],
				'lastpush'=>NULL,
				'adddt'=>date('Y-m-d H:i:s', time()),
				'weixinid'=>NULL,
				'quitdt'=>NULL,
				'style'=>0,
				'pingyin'=>c('pingyin')->get($val['XM'],1),//需要一个中文转拼音的方法
				'emailpass'=>NULL,
				'loginerrornum'=>0,
				'unlocktime'=>NULL,
				'wx_openid'=>$val['ZGH'],
				'is_admin'=>0,
			));
			
			//向pl_userinfo插入数据
			$lastid = (int)m('userinfo')->getone('','max(id) as lastid','id desc')['lastid'];//获取最后一行的id
			$userinfoid = $lastid + 1;
			$res2 = m('userinfo')->insert(array(
				'id'=>$userinfoid,//获取最后一行的id
				'name'=>$val['XM'],
				'num'=>$val['ZGH'],
				'deptname'=>$deptname,//部门名称
				'ranking'=>$val['GWMC']==NULL?'':$val['GWMC'],
				'dkip'=>NULL,
				'dkmac'=>NULL,
				'state'=>$state,
				'sex'=>$val['XB'],
				'tel'=>NULL,
				'mobile'=>$val['MOBILE1']==''?$val['MOBILE2']:$val['MOBILE1'],
				'workdate'=>$val['RXGWSJ'],
				'email'=>$val['EMAIL'],
				'quitdt'=>NULL,
				'iskq'=>1,
				'isdwdk'=>0,
				'birthday'=>NULL,
				'xueli'=>NULL,
				'birtype'=>0,
				'minzu'=>NULL,
				'hunyin'=>NULL,
				'jiguan'=>NULL,
				'nowdizhi'=>NULL,
				'housedizhi'=>NULL,
				'syenddt'=>NULL,
				'positivedt'=>NULL,
				'bankname'=>NULL,
				'banknum'=>NULL,
				'zhaopian'=>NULL,
				'idnum'=>$val['SFZH'],
				'spareman'=>NULL,
				'sparetel'=>NULL,
			));
			
			//判断是否是领导
			//处长、院长、正处、科长、部长、主任、馆长、校长
			//GWMC、GWBZ、XZZW、    XZZWJB
			$leader = getconfig('leader');
			$is_leader = false;//默认不是领导
			foreach ($leader as $l_k => $l_val) {
				if(strpos($val['GWMC'], $l_val) !== false || strpos($val['GWBZ'], $l_val) !==false || strpos($val['XZZW'], $l_val) !==false){
					$is_leader = true;
					continue;
				}
			}
			unset($l_k, $l_val);
			if($is_leader){
				if(strpos($val['XZZWJB'], "正") !== false){
					$updateheadman = m('dept')->update(array(
						'headman'=>$val['XM'],
						'headid'=>$admin_id,
					),"id=$detpid");
				}
				$mid = 2;
			}else{
				$mid = 1;
			}
			//若不为部门负责人|校项目管理办公室，则全部设为申报者
			$insert_sjoin = m('sjoin')->insert(array(
				'type'=>'gu',
				'mid'=>$mid,//1为申报者 , 2位单位领导
				'sid'=>$admin_id,
				'indate'=>date('Y-m-d H:i:s', time())
			));
		}
	}
	
	public function updatemsgAction(){
		$this->getDeptData();
		$this->setLevel();
		$this->getUserData();
	}

}

?>