<?php
/*@ liang
@ 根据流程及申报书修改代码段
@ 时间：2017/4/26 9：07
@ 流程简化
@ 模块：project_sx_apply 申报流程模块 独立模块*/

class flow_project_sx_applyClassModel extends flowModel
{
	
		protected function flowdatalog($arr){

//			m('shengbao_qk')->
//			//申报专业（群）现有校内实训室（基地）情况表
//			$arr['sutable_shengbao_qk']
			$stype	= $this->rock->post('stype');//状态
			if($stype=='word'){
			
			$arr['word_name']=$arr['modename'];
			
			$arr['modename']='';
			$arr['title']='';
			$arr['readarr']="";
			$arr['logarr']="";
			$arr['isedit']="";
			$arr['isdel']="";
			$arr['isflow']="";
			$arr['flowinfor']="";
			}
			$arr['title']='';
			return $arr;

		}
		
		
		
		public function flowrsreplace($rs){
			$shengbao_qk_table=m('shengbao_qk')->getall('mid='.$rs['id']);
			

			//实训申报的表格含有二级，需要些代码支持
			$table_qk='<table width="100%" class="createrows" border="0" cellspacing="0" cellpadding="0" align="center" style="border-collapse:collapse;">
  								<tr><tbody>
							    <td rowspan="2" align="center" style="padding: 3px;border: 1px #000000 solid;border-top: none;border-left: none;">序号</td>
							    <td rowspan="2" align="center" style="padding: 3px;border: 1px #000000 solid;border-top: none;border-left: none;">名称</td>
							    <td rowspan="2" align="center" style="padding: 3px;border: 1px #000000 solid;border-top: none;border-left: none;">建筑面积（平米）</td>
							    <td colspan="2" align="center" style="padding: 3px;border: 1px #000000 solid;border-top: none;border-left: none;">仪器设备</td>
							    <td colspan="2" align="center" style="padding: 3px;border: 1px #000000 solid;border-top: none;border-left: none;">其中：大型专用仪器设备</td>
							    <td rowspan="2" align="center" style="padding: 3px;border: 1px #000000 solid;border-top: none;border-left: none;">主要实训项目</td>
							    <td rowspan="2" align="center" style="padding: 3px;border: 1px #000000 solid;border-top: none;border-left: none;">面向其他专业</td>
 
  								</tr>
  								<tr>
							    <td align="center" style="padding: 3px;border: 1px #000000 solid;border-top: none;border-left: none;">台/套</td>
							    <td align="center" style="padding: 3px;border: 1px #000000 solid;border-top: none;border-left: none;">总值（万元）</td>
							    <td align="center" style="padding: 3px;border: 1px #000000 solid;border-top: none;border-left: none;">台/套</td>
							    <td align="center" style="padding: 3px;border: 1px #000000 solid;border-top: none;border-left: none;">总值（万元）</td>
  								</tr>';
			
			foreach($shengbao_qk_table as $k=>$value){
				$table_qk.=" <tr>
							<td style='padding: 3px;border: 1px #000000 solid;border-top: none;border-left: none;' align='center'>".($k+1)."</td>
							<td style='padding: 3px;border: 1px #000000 solid;border-top: none;border-left: none;' align='center'>".$value['xianyou_sx_mc']."</td>
						    <td style='padding: 3px;border: 1px #000000 solid;border-top: none;border-left: none;' align='center'>".$value['xianyou_sx_mj']."</td>
						    <td style='padding: 3px;border: 1px #000000 solid;border-top: none;border-left: none;' align='center'>".$value['xianyou_sx_yqts']."</td>
						    <td style='padding: 3px;border: 1px #000000 solid;border-top: none;border-left: none;' align='center'>".$value['xiangyou_sx_zz']."</td>
						    <td style='padding: 3px;border: 1px #000000 solid;border-top: none;border-left: none;' align='center'>".$value['xianyou_sx_dxts']."</td>
						    <td style='padding: 3px;border: 1px #000000 solid;border-top: none;border-left: none;' align='center'>".$value['xianyou_sx_dxzz']."</td>
						    <td style='padding: 3px;border: 1px #000000 solid;border-top: none;border-left: none;' align='center'>".$value['xianyou_sx_zysm']."</td>
						    <td style='padding: 3px;border: 1px #000000 solid;border-top: none;border-left: none;' align='center'>".$value['xianyou_sx_mxqt']."</td>
						    </tr>";
				
			}
			
			
			$table_qk.='</tbody></table>';
		
			$rs['shengbao_qk_table']=$table_qk;//赋值 申报书中使用 shengbao_qk_table
			
			$rs['project_yushuan']=$rs['project_yushuan']/(10000);//计划投资
			$rs['xuexiaozichou']=$rs['xuexiaozichou']/(10000);//学校自筹
			$rs['qiyetouru']=$rs['qiyetouru']/(10000);//企业投入
			$rs['zhijintouruqita']=$rs['zhijintouruqita']/(10000);//其他资金
			
			$rs['zx_caizhen']=$rs['zx_caizhen']/(10000);//计划投资
			
			
			return $rs;
		}
		
		protected function flowcheckafter($zt,$sm){
		
			$mid=$this->rock->post('mid');
			//查询实训申报书的流程状态
			$project_sx_apply=m('project_sx_apply');
			$flow_info=m('flow_bill')->getone('mid='.$mid." and modeid=57");
				
			if($zt==3){
				$sz_ku['project_ku']='';
				$project_sx_apply->update($sz_ku,'id='.$mid);
			}
		}
		
		protected function flowcheckfinsh($zt){
			$mid=$this->rock->post('mid');
			//查询实训申报书的流程状态
			$project_sx_apply=m('project_sx_apply');
			$flow_info=m('flow_bill')->getone('mid='.$mid." and modeid=57");
				
				//1 状态为进行
			if($zt==1){
				
			
				$sz_ku['project_ku']='预备库';
				$project_sx_apply->update($sz_ku,'id='.$mid);
							
			}
			
		}



		//流程提醒检查
		public function nexttodo($nuid, $type, $sm='', $act='')
		{	
		
			//$nuid 可能为数组
			$cont	= '';
			$gname	= '流程待办';
			$project_sx_apply=m('project_sx_apply')->getone('id='.$this->id);
			$flow_bill=m('flow_bill')->getone('mid='.$project_sx_apply['id']);
			$flow_course=m('flow_course')->getone('id='.$flow_bill['nowcourseid']);
			$userinfo=m('admin')->getone('id='.$nuid);
			if($type=='submit' || $type=='next'){
				$cont = '你有的['.$project_sx_apply['project_name'].']需要处理';
				//if($sm!='')$cont.='，说明:'.$sm.'';
				
			
				
			}
			//审核不通过
			if($type == 'nothrough'){
				//$cont = '你提交['.$project_sx_apply['project_name'].']'.$userinfo['name'].'处理['.$act.']，原因:['.$sm.']';
				$cont = '你提交['.$project_sx_apply['project_name'].']'.$userinfo['name'].'';
				$gname= '流程申请';
			
	
			}
			if($type == 'finish'){
				$cont = '你提交的['.$project_sx_apply['project_name'].']已全部处理完成';
	
			}
			if($type == 'zhui'){
				$cont = '你有['.$userinfo['name'].']的['.$project_sx_apply['project_name'].']需要处理，追加说明:['.$sm.']';
			}
			//退回
			if($type == 'tui'){
				$cont = '['.$userinfo['name'].']退回单据['.$project_sx_apply['project_name'].']到你这请及时处理，说明:'.$sm.'';
				
			}
		
		
		
		
//		'description'=>'项目名称：'.$project_sx_apply['project_name']."\n项目编号：".$project_sx_apply['project_number']."\n项目负责人：".$project_sx_apply['project_head']."\n申报时间：".$project_sx_apply['project_sx_apply_time'],
//		
//		if($userinfo['wx_openid']!=''){
//				
//				$data=array(
//		 			'articles'=>array(
//			            	0=>array(
//			            	"title" =>$userinfo['name'].', 您有项目待处理',
//			            	'description'=>'项目名称：'.$project_sx_apply['project_name']."\n当前进程状态：".$flow_course['name']."\n申报时间：".$project_sx_apply['project_apply_time'],
//							"url" =>getconfig('url')."index.php?m=ying&d=we&num=project_sx_apply",
//					        "picurl" =>""
//									)
//							)
//						);
//						
//			
//			m('wxgzh:wxgzh')->doSend($userinfo['wx_openid'],'@all','@all',1, $data);
//		}
		
		
		
		
			if($cont!='')$this->push($nuid, $gname, $cont);
		
		
		
		}

		public function push($receid, $gname='', $cont, $title='', $wkal=0)
		{
			$userinfo=m('admin')->getone('id='.$receid);
			
			
			if($this->isempt($receid) && $wkal==1)$receid='all';
			if($this->isempt($receid))return false;
			if($gname=='')$gname = $this->modename;
			$reim	= m('reim');
			$url 	= ''.URL.'task.php?a=p&num='.$this->modenum.'&mid='.$this->id.'';
			$wxurl 	= ''.URL.'task.php?a=x&num='.$this->modenum.'&mid='.$this->id.'';
			$emurl 	= ''.URL.'task.php?a=a&num='.$this->modenum.'&mid='.$this->id.'';
			if($this->id==0){
				$url = '';$wxurl = '';$emurl='';
			}
			$slx	= 0;
			$pctx	= $this->moders['pctx'];
			$mctx	= $this->moders['mctx'];
			$wxtx	= $this->moders['wxtx'];
			$emtx	= $this->moders['emtx'];
			if($pctx==0 && $mctx==1)$slx=2;
			if($pctx==1 && $mctx==0)$slx=1;
			if($pctx==0 && $mctx==0)$slx=3;
			$this->rs['now_adminname'] 	= $this->adminname;
			$this->rs['now_modename'] 	= $this->modename;
			$cont	= $this->rock->reparr($cont, $this->rs);
			if(contain($receid,'u') || contain($receid, 'd'))$receid = m('admin')->gjoin($receid);
			m('todo')->addtodo($receid, $this->modename, $cont, $this->modenum, $this->id);
			$reim->pushagent($receid, $gname, $cont, $title, $url, $wxurl, $slx);
			
			
			if($title=='')$title = $this->modename;
			//邮件提醒发送不发送全体人员的，太多了
			if($emtx == 1 && $receid != 'all'){
				$emcont = '您好：<br>'.$cont.'(邮件由系统自动发送)';
				if($emurl!=''){
					$emcont.='<br><a href="'.$emurl.'" target="_blank" style="color:blue"><u>详情&gt;&gt;</u></a>';
				}
				m('email')->sendmail($title, $emcont, $receid);
			}
			
			
			
			//yanshou 
			//微信提醒发送
			$project_sx_apply=m('project_sx_apply')->getone('id='.$this->id);
			$flow_bill=m('flow_bill')->getone('mid='.$this->id);
			$flow_course=m('flow_course')->getone('id='.$flow_bill['nowcourseid']);
	
			$url=getconfig('url').'task.php?a=x&num='.$this->modenum.'&mid='.$this->id.'&show=we';
			
			if($userinfo['wx_openid']!=''){
					
					$data=array(
			 			'articles'=>array(
				            	0=>array(
				            	"title" =>$userinfo['name'].', 您有项目待处理',
				            	'description'=>'项目名称：'.$project_sx_apply['project_name']."\n负责人：".$project_sx_apply['project_head']."\n申报时间：".$project_sx_apply['project_apply_time'],
								"url" =>$url,
						        "picurl" =>""
										)
								)
							);
							
				
				m('wxgzh:wxgzh')->doSend($userinfo['wx_openid'],'@all','@all',1, $data);
			}
		
		}
	
}