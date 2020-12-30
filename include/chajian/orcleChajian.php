<?php 
/**
	orcle从orcle更新用户数据到本地数据库
*/
class orcleChajian extends Chajian{
	
	private $conn           = '';//数据库连接信息
    
	//连接orcle数据库  
	public function initChajian(){
		$UserName = getconfig("orcle_db_user");
		$Password = getconfig("orcle_db_pass");
		$InstanceName = getconfig("orcle_instance_name");
		$ConnectUrl = getconfig("orcle_db_host");
		$Port = getconfig("orcle_port");
		$Code = getconfig("orcle_code");
		$this->conn = oci_connect($UserName, $Password, "{$ConnectUrl}:{$Port}/{$InstanceName}", $Code); 
		if(!$this->conn){
			$Error = oci_error();
		    print htmlentities($Error['message']);
		    exit;
		}
	}
	
	//获取orcle数据库的最新的某个用户数据
	//用工号作为唯一判断,$th_no
	public function getData($th_no){
		//查询用户信息视图获取当前登录用户的新
		//$sql = "select * from dba_users"; //debug,到时要换成查询视图的语句
		$sql = "SELECT * FROM T_JZG WHERE ZGH='{$th_no}'";//查询T_JZG视图
		$result = oci_parse($this->conn, $sql);
		if(!$result){
			$e = oci_error($this->conn);
			print htmlentities($e['message']);
			exit;
		}
		oci_execute($result); 
		$r=oci_fetch_array($result, OCI_ASSOC);
		if(!$r){
			$e = oci_error($this->conn);
			print htmlentities($e['message']);
			exit;
		}
		oci_free_statement($result);
		oci_close($this->conn);
		return $r;
	}
	
	//获取orcle数据库的最新的某个部门
	//用部门id作为唯一判断,$dept_id
	public function getData($th_no){
		//查询用户信息视图获取当前登录用户的新
		//$sql = "select * from dba_users"; //debug,到时要换成查询视图的语句
		$sql = "SELECT * FROM T_JZG WHERE ZGH='{$th_no}'";//查询T_JZG视图
		$result = oci_parse($this->conn, $sql);
		if(!$result){
			$e = oci_error($this->conn);
			print htmlentities($e['message']);
			exit;
		}
		oci_execute($result); 
		$r=oci_fetch_array($result, OCI_ASSOC);
		if(!$r){
			$e = oci_error($this->conn);
			print htmlentities($e['message']);
			exit;
		}
		oci_free_statement($result);
		oci_close($this->conn);
		return $r;
	}
	
	
	//checkup 检查数据是否存在
	//存在则判断用户数据是否有改变，有改变则更新用户数据，没改变则不作操作
	//不存在则插入数据
	public function checkup($th_no){
		$admin_info = m('admin')->getone("num like '{$th_no}'");
		$user_info = m('userinfo')->getone("num like '{$th_no}'");
		if(empty($admin_info) && empty($user_info)){
			//从Orcle中获取到数据
			$data = c('orcle')->getData($th_no);
			c('orcle')->insertData($data);
		}else{
			$flag = true;//$flag = true则为没改变，false则为已改变
			//从Orcle中获取到数据
			$data = c('orcle')->getData($th_no);
			$up_amdin_data = $up_userinfo_data = array();
			if($admin_info['name'] != $data['XM']){
				$flag = false;
			}
			//这里差一个部门名称的判断
			
			//离职判断
			if(strpos($data['RYLB'], "离") !== -1){
				$flag = false;
			}
			if($admin_info['ranking'] != $data['GWBZ']){
				$flag = false;
			}
			if($admin_info['mobile'] != $data['MOBILE1']){
				$flag = false;
			}
			if($user_info['idnum'] != $data['SFZH']){
				$flag = false;
			}
			if(!$flag){
				c('orcle')->updateData($data);
			}
		}
	}
	
	
	//更新用户信息
	public function updateData($data){
		//更新pl_admin表数据
	}
	
	//插入用户信息用户信息
	public function insertData($data){
		//判断数据库中是否存在该部门层级
		$cate = m('dept_check')->getone('DEPT_ID like '.$data['DWLB'], '*');
		if(empty($isexist)){
			
		}else{
			
		}
		//判断数据库是否存在该部门
		$isexist = m('dept_check')->getone('DEPT_ID='.$data['DEPT_ID'], '*');
		if(empty($isexist)){
			
		}else{
			
		}
		
		//先判断该用户的部门信息
		//校对部门名称对比表
		$check_info = m('dept_check')->getone('DEPT_ID='.$data['DEPT_ID'], 'deptname,is_dept_id');
		$category_info = m('dept_check')->getone('DWLB like '.$data['DWLB'], 'deptname as category,is_dept_gory as is_cate_gory');
		$detpid = '';
		$detpname = '';
		$deptallname = '';
		$deptpath = '';
		if($check_info){
			$detpid = $check_info['is_dept_id'];
			$detpname = $check_info['deptname'];
			$dept_info = m('dept')->getone('id='.$check_info['is_dept_id'], '');
			$deptallname = 
			$deptpath = '';
		}else{
			
		}
		
		//向pl_admin插入数据
		$admin_id = m('admin')->insert(array(
			'num'=>$data['ZGH'],
			'user'=>$data['ZGH'],
			'name'=>$data['XM'],
			'pass'=>md5(substr($data['SFZH'],-6)),
			'loginci'=>0,
			'status'=>1,
			'type'=>0,//全部为普通用户
			'sex'=>$data['XB'],
			'tel'=>NULL,
			'face'=>NULL,
			'deptid'=>$detpid,//根据部门修改id
			'deptname'=>$detpname,//部门名称
			'deptallname'=>$deptallname,//部门全部路径
			'superid'=>NULL,
			'ranking'=>$data['GWMC'],
			'sort'=>0,
			'deptpath'=>$deptpath,
			'superpath'=>NULL,
			'groupname'=>NULL,
			'mobile'=>$data['MOBILE1']==''?$data['MOBILE2']:$data['MOBILE1'],
			'apptx'=>1,
			'workdate'=>$data['RXGWSJ'],
			'email'=>$data['EMAIL'],
			'lastpush'=>NULL,
			'adddt'=>date('Y-m-d H:i:s', time()),
			'weixinid'=>NULL,
			'quitdt'=>NULL,
			'style'=>0,
			'pingyin'=>c('pingyin')->get($data['XM'],1),//需要一个中文转拼音的方法
			'emailpass'=>NULL,
			'loginerrornum'=>0,
			'unlocktime'=>NULL,
			'wx_openid'=>$data['ZGH'],
			'is_admin'=>0,
		));
		
		//向pl_userinfo插入数据
		$lastid = (int)m('userinfo')->getone('','max(id) as lastid','id desc')['lastid'];//获取最后一行的id
		$userinfoid = $lastid + 1;
		$res2 = m('userinfo')->insert(array(
			'id'=>$userinfoid,//获取最后一行的id
			'name'=>$data['XM'],
			'num'=>$data['ZGH'],
			'deptname'=>$deptname,//部门名称
			'ranking'=>$data['GWMC']==NULL?'':$data['GWMC'],
			'dkip'=>NULL,
			'dkmac'=>NULL,
			'state'=>1,
			'sex'=>$data['XB'],
			'tel'=>NULL,
			'mobile'=>$data['MOBILE1']==''?$data['MOBILE2']:$data['MOBILE1'],
			'workdate'=>$data['RXGWSJ'],
			'email'=>$data['EMAIL'],
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
			'idnum'=>$data['SFZH'],
			'spareman'=>NULL,
			'sparetel'=>NULL,
		));
		
		$isdean = strpos($data['GWMC'], "院长");
		$isjust = strpos($data['XZZWJB'], "正");
		if($isdean !== -1 && $isjust !== -1){
			$mid = 2;
			$updateheadman = m('dept')->update(array(
				'headman'=>$data['XM'],
				'headid'=>$admin_id,
			),"id=$detpid");
		}else{
			$mid = 1;
		}
		//若不为部门负责人，则全部设为申报者
		$insert_sjoin = m('sjoin')->insert(array(
			'type'=>'gu',
			'mid'=>$mid,//1为申报者 , 2位单位领导
			'sid'=>$insertToAdmin,
			'indate'=>date('Y-m-d H:i:s', time())
		));
	}
	
}                                                                                                                                                          