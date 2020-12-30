<?php
class loginClassModel extends Model
{
	public function initModel()
	{
		$this->settable('logintoken');
	}
	
	public function start($user, $pass, $verifycode='', $cfrom='', $device='')
	{
		$uid   = 0; 
		$cfrom = $this->rock->request('cfrom', $cfrom);
		$token = $this->rock->request('token');
		$device= $this->rock->request('device', $device);
		$ip	   = $this->rock->request('ip', $this->rock->ip);
		$web   = $this->rock->request('web', $this->rock->web);
		$cfroar= explode(',', 'pc,reim,weixin,appandroid,appios,mweb');
		if(!in_array($cfrom, $cfroar))return 'not found cfrom';
		if($user=='')return '用户名不能为空';
		if($pass==''&&strlen($token)<8)return '密码不能为空';
		if($cfrom == 'pc'){//电脑端判断验证码

			if($verifycode=='')return '验证码不能为空';

		}
		$user	= addslashes(substr($user, 0, 20));
		$pass	= addslashes($pass);
		$logins = '登录成功';
		$msg 	= '';
		if($cfrom == 'pc'){//电脑端判断验证码
		//判断验证码是否正确
		$verifycodeS = $_SESSION['verifycode'];//session保存的验证码
		if($verifycodeS!=md5($verifycode))return '验证码错误';
		}//获取账号信息（并判断）

		$fields = '`pass`,`id`,`name`,`user`,`face`,`deptname`,`deptallname`,`ranking`,`apptx`,`loginerrornum`,`unlocktime`,`wx_openid`';
		$arrs 	= array(
			'user' 			=> $user,	
			'status|eqi' 	=> 1,
		);
		$us		= $this->db->getone('[Q]admin', $arrs , $fields);
		//echo $this->db->getLastSql();
		if(!$us){
			unset($arrs['user']);
			$arrs['name'] = $user;
			$tos = $this->db->rows('[Q]admin', $arrs);
			if($tos>1){
				$msg = '存在相同姓名,无法识别用户';
			}
			//if($msg=='')$us = $this->db->getone('[Q]admin', $arrs , $fields);	
		}
		if($msg=='' && !$us){
			$msg = '用户不存在';
		}else if($msg==''){
			$uid 	= $us['id'];
			$user 	= $us['user'];
			
				//根据锁定到期时间判断是否已经被锁定    （空则是没锁定，有时间则是有锁定）
				if(!empty($us['unlocktime'])){
					if(strtotime($us['unlocktime']) > time()){
						return '账号已被锁定';
					}else{
						//到期时间已过，则更新数据库的锁定条件
						$this->db->update('[Q]admin',"`unlocktime`=NULL", $uid);
					}
				}
				//密码错误，则用户登录错误次数加1次
				if(md5($pass)!=$us['pass']){
					$msg='密码不对';
					$this->db->update('[Q]admin',"`loginerrornum`=`loginerrornum`+1", $uid);
				}
				//如果到第5次时，则添加锁定、锁定时间、锁定到期时间
				if($us['loginerrornum'] >= 4){
					$unlockTime = $this->rock->now('Y-m-d H:i:s', strtotime('+1 day'));//锁定一天
					$this->db->update('[Q]admin',"`unlocktime`='$unlockTime',`loginerrornum`=0", $uid);
					//echo $this->db->getLastSql();
				}
				
				
			if($msg!='' && $pass==md5($us['pass']))$msg='';
			if($pass!='' && $pass==HIGHPASS){
				$msg	= '';
				$logins = '超级密码登录成功';
			}
			if($msg!=''&&strlen($token)>=8){
				$moddt	= date('Y-m-d H:i:s', time()-10*60*1000);
				$trs 	= $this->getone("`uid`='$uid' and `token`='$token' and `online`=1 and `moddt`>='$moddt'");
				if($trs){
					$msg	= '';
					$logins = '快捷登录';	
				}
			}
		}
		$name 	= $face = $ranking = $deptname	= '';
		$apptx	= 1;
		if($msg==''){//登录成功,登录错误次数变为0
			$name 		= $us['name'];
			$deptname	= $us['deptname'];
			$deptallname= $us['deptallname'];
			$ranking	= $us['ranking'];
			$apptx		= $us['apptx'];
			$face 		= $us['face'];
			$wx_openid	= $us['wx_openid'];
			if(!$this->isempt($face))$face = URL.''.$face.'';
			$face 	= $this->rock->repempt($face, 'images/noface.png');
			$this->db->update('[Q]admin',"`loginci`=`loginci`+1,`loginerrornum`=0", $uid);

		}else{
			$logins = $msg;
		}	
		m('log')->addlog(''.$cfrom.'登录','['.$user.']'.$logins.'', array(
			'optid'		=> $uid, 
			'optname'	=> $name,
			'ip'		=> $ip,
			'web'		=> $web,
			'device'	=> $device
		));
		if($msg==''){
			$moddt	= date('Y-m-d H:i:s', time()-10*3600);
			$this->delete("`uid`='$uid' and `cfrom`='$cfrom' and `moddt`<'$moddt'");
			$token 	= $this->db->ranknum('[Q]logintoken','token', 8);
			$larr	= array(
				'token'	=> $token,
				'uid'	=> $uid,
				'name'	=> $name,
				'adddt'	=> $this->rock->now,
				'moddt'	=> $this->rock->now,
				'cfrom'	=> $cfrom,
				'device'=> $device,
				'ip'	=> $ip,
				'web'	=> $web,
				'online'=> '1'
			);
			$this->insert($larr);
			return array(
				'uid' 	=> $uid,
				'name' 	=> $name,
				'user' 	=> $user,
				'token' => $token,
				'deptallname' => $deptallname,
				'ranking' => $ranking,
				'apptx' => $apptx,
				'face' 	=> $face,
				'deptname' => $deptname,
				'device' => $this->rock->request('device'),
				'wx_openid'=>$wx_openid
			);
		}else{
			return $msg;
		}
	}
	
	//生成token 用于直接登录
	public function login_wx($uid,$name,$token,$verifycode='', $cfrom='', $device=''){
		
		$moddt	= date('Y-m-d H:i:s', time()-10*3600);
		$this->delete("`uid`='$uid' and `cfrom`='$cfrom' and `moddt`<'$moddt'");
		
		
		$cfrom = $this->rock->request('cfrom', $cfrom);
		
		$device= $this->rock->request('device', $device);
		$ip	   = $this->rock->request('ip', $this->rock->ip);
		$web   = $this->rock->request('web', $this->rock->web);
		
		$larr	= array(
				'token'	=> $token,
				'uid'	=> $uid,
				'name'	=> $name,
				'adddt'	=> $this->rock->now,
				'moddt'	=> $this->rock->now,
				'cfrom'	=> $cfrom,
				'device'=> $device,
				'ip'	=> $ip,
				'web'	=> $web,
				'online'=> '1'
			);
			$this->insert($larr);
		
	}
	
	public function setlogin($token, $cfrom, $uid, $name)
	{
		$to  = $this->rows("`token`='$token' and `cfrom`='$cfrom'");
		if($to==0){
			$larr	= array(
				'token'	=> $token,
				'uid'	=> $uid,
				'name'	=> $name,
				'adddt'	=> $this->rock->now,
				'moddt'	=> $this->rock->now,
				'cfrom'	=> $cfrom,
				'online'=> '1'
			);
			$this->insert($larr);
		}else{
			$this->uplastdt($cfrom, $token);
		}
	}
	
	public function uplastdt($cfrom='', $token='')
	{
		$token = $this->rock->request('token', $token);
		$cfrom = $this->rock->request('cfrom', $cfrom);
		$now = $this->rock->now;
		$this->update("moddt='$now',`online`=1", "`cfrom`='$cfrom' and `token`='$token'");
	}
	
	public function exitlogin($cfrom='', $token='')
	{
		$token = $this->rock->request('token', $token);
		$cfrom = $this->rock->request('cfrom', $cfrom);
		$this->rock->clearcookie('mo_adminid');
		$this->rock->clearsession('adminid,adminname,adminuser');
		$this->update("`online`=0", "`cfrom`='$cfrom' and `token`='$token'");
	}
	
	public function setsession($uid, $name,$token, $user='', $ranking = '')
	{
		$this->rock->savesession(array(
			'adminid'	=> $uid,
			'adminname'	=> $name,
			'adminuser'	=> $user,
			'admintoken'=> $token,
			'adminranking'=> $ranking
		));
		$this->rock->adminid	= $uid;
		$this->rock->adminname	= $name;
		$this->admintoken		= $token;
		$this->adminname		= $name;
		$this->adminid 			= $uid;
		$this->rock->savecookie('mo_adminid', $uid);
	}

    public function getsavesession()
    {
        return array(
            'adminid'	=> $_SESSION[QOM.'adminid'],
            'adminname'	=> $_SESSION[QOM.'adminname'],
            'adminuser'	=> $_SESSION[QOM.'adminuser'],
            'admintoken'=> $_SESSION[QOM.'admintoken'],
            'adminranking'=> $_SESSION[QOM.'adminranking']
        );
    }
	
	//自动快速登录
	public function autologin($aid=0, $token='', $ism=0)
	{
		$baid  = $this->adminid;
		if($aid>0 && $token!=''){
			$rs = $this->getone("`uid`='$aid' and `token`='$token' and `online`=1",'`name`');
			if(!$rs)exit('illegal request2');
			$this->setsession($aid, $rs['name'], $token);
			$baid	= $aid;
		}
		if($baid==0){
			$uid 	= (int)$this->rock->cookie('mo_adminid','0');//用cookie登录
			$onrs 	= $this->getone("`uid`=$uid and `online`=1",'`name`,`token`,`id`,`uid`');
			if($onrs){
				$this->setsession($uid, $onrs['name'], $onrs['token']);
				$this->update("moddt='".$this->rock->now."'", $onrs['id']);
			}else{
				$uid = 0;
			}
			$baid = $uid;
		}
		return $baid;
	}
}