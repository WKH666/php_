<?php
class adminClassAction extends Action
{
	public function loadadminAjax()
	{
		$id = (int)$this->get('id',0);
		$data = m('admin')->getone($id);
		if($data){
			$data['pass']='';
		}
		$arr['data'] = $data;

		$this->returnjson($arr);
	}

	public function beforeshow($table)
	{
		$fields = 'id,name,`user`,deptname,`type`,`num`,status,tel,workdate,ranking,superman,loginci,sex,sort,face,is_admin,school_name';
		$s 		= '';
		$key 	= $this->post('key');
		if($key!=''){
			$s = m('admin')->getkeywhere($key);
		}
		//这句是bug修改
		$sql1 = 'alter table `[Q]admin` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;';
		$opts = $this->option->getval('adminbug1');
		if(isempt($opts)){
			$this->db->query($sql1);
			$this->option->setval('adminbug1',$this->now, '用户bug1');
		}
		return array(
			'fields'=> $fields,
			'where'	=> $s.' and id<>1',
			'order'=>'status desc,id desc'
		);
	}

	public function tongxlbeforeshow($table)
	{
		$fields = '`id`,`name`,`deptallname`,`ranking`,`tel`,`mobile`,`email`,`type`,`sort`,`face`';
		$s 		= 'and `status`=1';
		$key 	= $this->post('key');
		$zt 	= $this->post('zt');
		//我直属下属
		if($zt == '0'){
			$s.= ' and '.m('admin')->getdowns($this->adminid,1);
		}
		if($key!=''){
			$s .= m('admin')->getkeywhere($key);
		}
		return array(
			'fields'=> $fields,
			'where'	=> $s,
			'order'	=> 'sort'
		);
	}
	public function fieldsafters($table, $fid, $val, $id)
	{
		$fields = 'sex,ranking,tel,mobile,workdate,email,quitdt';
		if(contain($fields, $fid))m('userinfo')->update("`$fid`='$val'", $id);
	}



	public function publicbeforesave($table, $cans, $id)
	{

	    //$cans为表单提交上来的数据
		$user = strtolower(str_replace(' ','',$cans['user']));
		$name = str_replace(' ','',$cans['name']);
		$num  = str_replace(' ','',$cans['num']);
		$email= str_replace(' ','',$cans['email']);
		$check= c('check');
		$mobile 	= $cans['mobile'];
		$weixinid 	= $cans['weixinid'];
		$pingyin 	= $cans['pingyin'];
		$school_name = $cans['school_name'];
		$msg  = '';
		//if(is_numeric($user))return '用户名不能是数字';
		if($check->isincn($user))return '用户名不能有中文';
		if(!isempt($email) && !$check->isemail($email))return '邮箱格式有误';
		if(!isempt($pingyin) && $check->isincn($pingyin))return '名字拼音不能有中文';
		if(!isempt($num) && $check->isincn($num))return '编号不能有中文';
		if(!isempt($mobile)){
			if(!$check->ismobile($mobile))return '手机格式有误';
		}
		if(isempt($mobile))return '手机号不能为空';
		if(isempt($email))return '邮箱不能为空';
		if(!isempt($weixinid)){
			if(is_numeric($weixinid))return '微信号不能是数字';
			if($check->isincn($weixinid))return '微信号不能有中文';
		}
		$db  = m($table);
		if($msg=='' && $num!='')if($db->rows("`num`='$num' and `id`<>'$id'")>0)$msg ='编号['.$num.']已存在';
		if($msg=='')if($db->rows("`user`='$user' and `id`<>'$id'")>0)$msg ='用户名['.$user.']已存在';
		if($msg=='')if($db->rows("`name`='$name' and `id`<>'$id'")>0)$msg ='姓名['.$name.']已存在';
		$rows = array();
		if($msg == ''){
			$did  = $cans['deptid'];
			$sup  = $cans['superid'];
			$rows = $db->getpath($did, $sup);
		}
		if(isempt($pingyin))$pingyin = c('pingyin')->get($name,1);
		$rows['pingyin'] = $pingyin;
		$rows['user'] 	= $user;
		$rows['name'] 	= $name;
		$rows['email'] 	= $email;
		$rows['school_name'] = $school_name;
		$arr = array('msg'=>$msg, 'rows'=>$rows);
		return $arr;
	}

	public function publicaftersave($table, $cans, $id)
	{
		m($table)->record(array('superman'=>$cans['name']), "`superid`='$id'");
		if(getconfig('systype')=='demo'){
			m('weixin:user')->optuserwx($id);
		}
		$this->updatess('and a.id='.$id.'');
	}

	public function updatedataAjax()
	{
		$a = $this->updatess();
		echo '总'.$a[0].'条记录,更新了'.$a[1].'条';
	}

	public function updatess($whe='')
	{
		return m('admin')->updateinfo($whe);
	}


	//批量导入
	public function saveadminplAjax()
	{
		$rows  	= c('html')->importdata('user,name,sex,ranking,deptname,mobile,email,tel,superman','user,name');
		$oi 	= 0;
		$db 	= m('admin');
		$sort 	= (int)$db->getmou('max(`sort`)', '`id`>0');
		$dbs	= m('dept');
		$py 	= c('pingyin');
		foreach($rows as $k=>$rs){
			$user = $rs['user'];
			$name = $rs['name'];;

			if($db->rows("`user`='$user'")>0)continue;
			if($db->rows("`name`='$name'")>0)continue;

			$arr['user'] = $user;
			$arr['name'] = $name;

			$arr['pingyin'] 	= $py->get($name,1);
			$arr['sex']  		= $rs['sex'];
			$arr['ranking']  	= $rs['ranking'];
			$arr['deptname']  	= $rs['deptname'];
			$arr['mobile']  	= $rs['mobile'];
			$arr['email']  		= $rs['email'];
			$arr['tel']  		= $rs['tel'];
			$arr['superman']  	= $rs['superman'];
			$arr['pass']  		= md5('123456');
			$arr['sort']  		= $sort+$oi;
			$arr['workdate']  	= $this->date;
			$arr['adddt']  		= $this->now;

			//读取上级主管Id
			$superid			= (int)$db->getmou('id', "`name`='".$arr['superman']."'");
			if($superid==0)$arr['superman'] = '';
			$arr['superid'] = $superid;

			//读取部门Id
			$deptid 	= (int)$dbs->getmou('id', "`name`='".$arr['deptname']."'");
			if($deptid==0)$arr['deptname'] = '';
			$arr['deptid'] = $deptid;
			$bo = $db->insert($arr);
			$oi++;
		}
		if($oi>0)$this->updatess();
		backmsg('','成功导入'.$oi.'个用户');
	}

	//修改头像
	public function editfaceAjax()
	{
		$fid = (int)$this->post('fid');
		$uid = (int)$this->post('uid');
		echo m('admin')->changeface($uid, $fid);
	}



	//根据ids获取专家信息列表
	public function getexpertlistAjax(){
		$expertIds = $this->post('expert_ids');//专家ids
		$arr = m('admin')->getall("id in ($expertIds)");
		$this->returnjson($arr);
	}

	//添加校外专家
	public function addotherexpertAjax(){
		$account = $this->post('account');//账号
		$deptname = $this->post('deptname');//单位名称
		$deptid = $this->post('deptid');//单位id
		$username = $this->post('username');//姓名
		$pingyin = $this->post('pingyin');//拼音
		$pass = $this->post('pass');//密码
		$sex = $this->post('sex');//性别
		$mobile = $this->post('mobile');//电话
		$ranking = $this->post('ranking');//职位

		$msg='';
		$info='';
		if(empty($account)) $msg='账号不能为空';
		if(empty($username)) $msg='姓名不能为空';
		if(empty($pass)) $msg='密码不能为空';

		//判断账号不能相同
		$exist = m('admin')->getone("user='$account'");
		//echo m('admin')->getLastSql();
		if($exist) $msg='账号已存在';
		//exit($msg);

		if(empty($msg)){
			$data=array(
				'num'=>$account,
				'user'=>$account,
				'deptname'=>'校外专家',
				'name'=>$username,
				'sex'=>$sex,
				'mobile'=>$mobile,
				'ranking'=>$ranking,
				'deptallname'=>'广东科学技术职业学院/校外专家',
				'deptid'=>54,
				'pingyin'=>$pingyin,
				'pass'=>md5($pass),
				'adddt'=>date('Y-m-d H:i:s',time()),
				'deptpath'=>'[1],[54]',
				'wx_openid'=>$account
			);
			//操作admin表
			$admin_res = m('admin')->insert($data);
			//echo m('admin')->getLastSql();

			$userinfo_res = m('userinfo')->insert(array(
				'deptname'=>'校外专家',
				'name'=>$username,
				'sex'=>$sex,
				'mobile'=>$mobile,
				'ranking'=>$ranking,
				'num'=>$account,
			));

			//赋予角色
			$sjoin_res = m('sjoin')->insert(array(
				'type'=>'gu',
				'mid'=>8,//8是校外专家组
				'sid'=>$admin_res,
				'indate'=>date('Y-m-d H:i:s',time())
			));

			if($userinfo_res && $admin_res && $sjoin_res){
				$rows[0] = m('admin')->getone("id=$admin_res");
				$info=array('id' => $admin_res,'success' => true,'rows' => $rows );
			}else{
				$info=array('id' => $admin_res,'success' => false,'msg' => '添加专家失败');
			}
		}else{
			$info=array('id' => '','success' => false,'msg' => $msg);
		}
		$this->returnjson($info);
	}

    //获取职位
    public function getrankAjax()
    {
        $arr 	= array();
        $rows 	= $this->db->getall('select `ranking` from `[Q]admin` group by `ranking`');
        foreach($rows as $k=>$rs)$arr[] = array('name'=>$rs['ranking'],'value'=>'');
        return $arr;
    }
}
