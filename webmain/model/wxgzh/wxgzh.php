<?php
class wxgzhModel extends Model
{
	//定义远程连接的
	protected $URL_public		= 'https:&#47;&#47;qyapi.weixin.qq.com/cgi-bin/';
	
//	protected $URL_gettoken		= 'token';
//	protected $URL_jsapiticket	= 'ticket/getticket';
	
	protected $URL_gettoken='gettoken';
	
	public $appid 		= '';
	public $backarr 	= array();
	private $secret 	= '';
	public function initWxgzh(){}
	
	public function initModel()
	{
		$this->backarr	= array('errcode'=>-1, 'msg'=>'sorry,error');
		$this->option	= m('option');
		$this->initWxgzh();
	}
	
	public function gettourl($can)
	{
		$url = $this->URL_public;
		if(substr($url,0,4)!='http'){
			$url=$this->rock->jm->uncrypt($url);
			$url.=$this->rock->jm->uncrypt($this->$can);
		}else{
			$url.=$this->$can;
		}
		return $url;
	}

	//读取配置
	public function readwxset()
	{
		if($this->appid!='')return;
		$this->appid 	= $this->option->getval('wxgzh_appid');
		$this->secret	= $this->option->getval('wxgzh_secret');
		$this->corpid	= $this->option->getval('weixin_corpid');
		return $this->appid;
	}
	
	//判断是否可以使用公众号定位的
	public function isusegzh()
	{
		if($this->rock->web!='wxbro')return 0;
		$this->readwxset();
		$is = 1;
		if($this->appid=='' || $this->secret=='' || !isempt($this->corpid))$is = 0;
		return $is;
	}
	
	//获取token
	public function gettoken()
	{
		$time 	= date('Y-m-d H:i:s', time()-2*3600);
		$num 	= 'wxgzh_token';
		$rs		= $this->option->getone("`num`='$num' and `optdt`>'$time'");
		$val 	= '';
		if($rs)$val = $rs['value'];
		if(isempt($val)){
			$this->readwxset();
			$secret = $this->secret;
			if($this->appid=='' || $this->secret=='')showreturn('','没有设置公众号',201);
			if(isempt($secret))return '';
			//企业号的设置
			$url 	= 'https://qyapi.weixin.qq.com/cgi-bin/gettoken?corpid='.$this->appid.'&corpsecret='.$secret.'';
		
			$result = c('curl')->getcurl($url);
			if($result != ''){
				$arr	= json_decode($result);
				if(!isset($arr->access_token)){
					showreturn('',$result,201);
				}else{
					$val 	= $arr->access_token;
					$this->option->setval($num, $val);
				}
			}	
		}

		return $val;
	}
	
	public function getlogin_code(){
		
		$url='https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxfb2ce3bfe3276283&redirect_uri=http%3a%2f%2fwjmobile.iok.la%2fxiangmukuv0.2%2f%3fm%3dlogin%26d%3dwe&response_type=code&scope=snsapi_base&state=STATE#wechat_redirect';
		c('curl')->getcurl($url);
		
		
//		https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxfb2ce3bfe3276283&redirect_uri=http%3a%2f%2fwjmobile.iok.la%2fxiangmukuv0.2%2fapi.php%3fm%3dopenwx%26openkey%3dxaingmuku%26a%3dlogin_wx&response_type=code&scope=SCOPE&state=STATE#wechat_redirect
	}
	
	
	public function getuserid($code)
	{
		
		if($code){
			$time 	= date('Y-m-d H:i:s', time()-2*3600);
			$num 	= 'wxgzh_token';
			$rs		= $this->option->getone("`num`='$num' and `optdt`>'$time'");
			$val 	= '';
			if($rs)$val = $rs['value'];
			if(isempt($val)){
					$val	= $this->gettoken();	
			}
			
			$url 	= 'https://qyapi.weixin.qq.com/cgi-bin/user/getuserinfo?access_token='.$val.'&code='.$code;
			$result = c('curl')->getcurl($url);
				if($result != ''){
					$arr	= json_decode($result);
					
					if(!empty($arr->UserId)){
						//存入
						
						$this->rock->setsession('wx_userid',$arr->UserId);
 						//重定向浏览器
						
						return TRUE;
						
					}

					if(!empty($arr->OpenId)){
						//跳转提示页面
						
						return false;
					}
					
					if($arr->errcode=='40029'){
						$url_lencode=urlencode(getconfig('url').'api.php?m=openwx&openkey=xaingmuku&a=login_wx');
						header("Location: https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxfb2ce3bfe3276283&redirect_uri=".$url_lencode."&response_type=code&scope=SCOPE&state=STATE#wechat_redirect");
						
					}
			
				
				}
				return $val;	
		}else{
			
			
		}	
		
		
	}

	
	public function setbackarr($code, $msg)
	{
		$this->backarr	= array('errcode'=>$code, 'msg'=>$msg);
	}
	
	 public function doSend($touser, $toparty, $totag,$agentid,$data)
    {
        $template = array(
            'touser' => $touser,
            'toparty'=>$toparty,
            'totag'=>$totag,
            'msgtype'=>'news',
            'agentid'=>$agentid,
            'news'=>$data,
   		
            
        );
        $json_template = json_encode($template,JSON_UNESCAPED_UNICODE);

        $url = "https://qyapi.weixin.qq.com/cgi-bin/message/send?access_token=".$this->gettoken();
        
        $result = c('curl')->postcurl($url,$json_template);
		
		$arr	= json_decode($result);
		
		return $arr;

      
    }
    
    
}