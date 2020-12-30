<?php 
class flowClassAction extends ActionNot{
	
	public function defaultAction()
	{
		
		$mid=$_GET['mid'];
		$num=$_GET['num'];
		
		$flow_set=m('flow_set')->getone("num='".$num."'");
		
		$flow_course=m('flow_course')->getall("setid='".$flow_set['id']."' and status=1");

		$flow_bill=m('flow_bill')->getone("mid='".$mid."'");
		

		$html='';
		foreach($flow_course as $k=>$v){
			$flow_log=m('flow_log')->getone('courseid='.$v['id'].' and mid='.$mid);
			
			
			if($v['id']==$flow_bill['nowcourseid'] && $flow_bill['status']!=2){
					
				$html.="<li class='tableT'><div class='tableC'><div class='leftCont'><span>".$v['name']."</span><div class='btnPanel'><button class='btnSubmit' onclick='remind(".$mid.",".$flow_bill['nowcourseid'].")'>提醒审核</button></div></div><div class='rightCont'><span>".$flow_log['statusname']."</span></div></div></li>";
				
			}else{
				$html.="<li class='tableT'><div class='tableC'><div class='leftCont'><span>".$v['name']."</span></div><div class='rightCont'><span>".$flow_log['statusname']."</span></div></div></li>";
			}
			
			
			
		}
	
		
		$this->assign('html', $html);	
		
	}

	public function remindAction(){
		
		$mid=$_POST['mid'];
		$lid=$_PSOT['lid'];
		$flow_bill=m('flow_bill')->getone("mid='".$mid."'");
		
		$userinfo=m('admin')->getone('id='.$flow_bill['nowcheckid']);
		$data=array(
		 			'articles'=>array(
			            	0=>array(
			            	"title" =>$userinfo['name'].', 您有项目待处理',
			            	'description'=>'项目名称：'.$project_apply['project_name']."\n当前进程状态：".$flow_course['name']."\n申报时间：".$project_apply['project_apply_time'],
							"url" =>getconfig('url')."index.php?m=ying&d=we&num=project_apply",
					        "picurl" =>""
									)
							)
						);
						
			
			$arr=m('wxgzh:wxgzh')->doSend($userinfo['wx_openid'],'@all','@all',1, $data);
			if($arr->errmsg=='ok'){
				
				echo '发送成功';
				exit;
				
			}
		
	}
	
	
}