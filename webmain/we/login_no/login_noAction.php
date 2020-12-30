<?php 
class login_noClassAction extends ActionNot{
	
	public function defaultAction()
	{
		


//				$wx_userid=$this->rock->session('wx_userid');
//				$use_o=m('admin')->getone("wx_openid='".$wx_userid."'");
//				$token=m('logintoken')->getone("uid=".$use_o['id'],'token','id desc');
//			
//				if($use_o){
//					
//					//$url = ''.getconfig('url').'?m=login&d=we&status=0&token='.$token['token'].'&user='.$this->jm->base64encode($use_o['user']).'';
//					$url = ''.getconfig('url').'?m=ying&d=we&num=project_apply&status=0&token='.$token['token'].'&user='.$this->jm->base64encode($use_o['user']).'';
//					
//					header("Location: ".$url);
//					//登录
//				}else{
//					header("Location: https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxfb2ce3bfe3276283&redirect_uri=http%3a%2f%2fxmk.gdit.edu.cn%2fapi.php%3fm%3dopenwx%26openkey%3dxaingmuku%26a%3dlogin_wx&response_type=code&scope=snsapi_base&agentid=AGENTID&state=STATE#wechat_redirect");
//					
//				}
			
			
		
	}
	
	/**
	*	微信快捷登录
	*/
	public function wxloginAction()
	{
		$this->display= false;
		if($this->rock->isqywx){
			m('weixinqy:oauth')->login();
		}else{
			m('weixin:oauth')->login();
		}
	}
	
//	public function wx_loginAction()
//	{
//		$this->display= false;
//		$wx_userid=$this->rock->session('wx_userid');
//		$use_o=m('admin')->getone('uid='.$this->adminid);
//		if($use_o['wx_openid']==$wx_userid){
//			//登录
//		}else{
//			//不登录
//		}
//	}
	
	public function wxlogincodeAction()
	{
		$this->display= false;
		if($this->rock->isqywx){
			m('weixinqy:oauth')->logincode();
		}else{
			m('weixin:oauth')->logincode();
		}
	}
}