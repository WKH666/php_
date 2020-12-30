<?php

class openwxClassAction extends openapiAction
{
	public function initAction()
	{
		$this->display= false;
		$openkey = $this->post('openkey');
		
		if(md5($openkey) != getconfig('wxopenkey'))$this->showreturn('', 'wxopenkey not access', 201);
	}
	
//	public function login_wxAction(){
//	
//		@$code=$_GET["code"];
//		if($code){
//			if(m('wxgzh:wxgzh')->getuserid($code)){
//				header("Location: ".getconfig('url')."/?m=login&d=we&status=1");
//			}else{
//				
////				header("Location: ".getconfig('url')."/?m=login&d=we&status=0");
//				header("Location: http://127.0.0.1/xiangmukuv0.2/webmain/we/login/tpl_login_no.html");
//			}
//			
//		}else{
//			var_dump('缺少参数');
//		}
//		
//		
//	}

		public function login_wxAction(){
			
//		   $this->token= $this->request('token', $this->admintoken);
//				$this->adminid 	 = (int)$this->request('adminid', $this->adminid);
//			$token = $this->admintoken;
//			
//			$uid 	= $arr['uid'];
//			$name 	= $arr['name'];
//			$user 	= $arr['user'];
//			$token 	= $arr['token'];
//			m('login')->setsession($uid, $name, $token, $user);
//	1.用户不是微信企业号的用户，提示非系统用户
//	2.用户微信没有绑定系统中的用户，提示绑定
//	3.用户未登录，生成token，跳转列表
//	4.用户已登录，获取token，跳转列表
		@$code=$_GET["code"];

		if($code){
			//wxgzh 微信模块 判断微信返回的数据中是否存在user_id
			if(m('wxgzh:wxgzh')->getuserid($code)){
				$wx_userid=$this->rock->session('wx_userid');
				$use_o=m('admin')->getone("wx_openid='".$wx_userid."'");//是否绑定
				//$token=m('logintoken')->getone("uid=".$use_o['id'],'token','id desc');
				
				$this->token= $this->request('token', $this->admintoken);
				if(!$this->token){
					$this->token 	= $this->db->ranknum('[Q]logintoken','token', 8);
				}
				
				$to = m('logintoken')->rows("`token`='$this->token' and `uid`='".$use_o['id']."' and `online`=1");
				
				
				if($use_o){
					
					if($to==0){
						
						m('login')->login_wx($use_o['id'],$use_o['name'],$this->token);	
						
							
					}
					
					
					//$url = ''.getconfig('url').'?m=login&d=we&status=0&token='.$token['token'].'&user='.$this->jm->base64encode($use_o['user']).'';
					$url = ''.getconfig('url').'?m=ying&d=we&num=project_apply&status=0&token='.$this->token.'&user='.$this->jm->base64encode($use_o['user']).'';
					
					header("Location: ".$url);
					
					//登录
				}else{
					header("Location: ".getconfig('url')."?m=login&d=we&status=1");
					
				}
			}else{
				
//				header("Location: ".getconfig('url')."/?m=login&d=we&status=0");
				header("Location: ".getconfig('url')."?m=login_no&d=we");
			}
			
		}else{
			header("Location: ".getconfig('url')."?m=login_no&d=we");
		}
		
		
	}


}

?>