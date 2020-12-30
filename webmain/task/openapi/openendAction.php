<?php

class openendClassAction extends openapiAction {
	public function initAction() {
		$this -> display = false;
		$openkey = $this -> post('openkey');

		if ($openkey != '46f86faa6bbf9ac94a7e459509a20ed0')
			$this -> showreturn('', 'openend not access', 201);
	}

	//http://localhost/xiangmukuV0.4/api.php?m=openend&a=end&openkey=46f86faa6bbf9ac94a7e459509a20ed0
	//http://xmk.gdit.edu.cn/api.php?m=openend&a=end&openkey=46f86faa6bbf9ac94a7e459509a20ed0
	/**
	 * 网评结束提示
	 * 分数统计、状态更改
	 */
	public function endAction() {
		//获取所有批次
		//查询状态是1进行中 且 当前时间>结束时间
		//专家打分草稿->3作废
		//专家提交打分-》统计总分-》项目表

		$pici = m('m_batch') -> getall('com_status=1');

		//遍历所有批次
		foreach ($pici as $k => $v) {
			//结束时间不为空的,为空则跳过，继续执行
			if (!empty($v['pici_end_time'])) {
				//var_dump($v);
				
				//判断当前时间是否大于等于网评批次的结束时间,只是结束了批次才发送消息
				//并且还没发送的
				if (time() >= strtotime($v['pici_end_time']) && !$this->is_send($v['id'],3)) {

					//判断专家是否已经提交评分,还没有提交就当弃权 com_status 0草稿 1提交 3弃权
					$pxmdf = m('m_pxmdf') -> getall("pici_id=" . $v['id'] . " and com_status=0");

					foreach ($pxmdf as $pxmdf_k => $pxmdf_v) {
						m('m_pxmdf') -> update(array('com_status' => 3), "id=" . $pxmdf_v['id']);
					}

					//项目总分数和平均分统计
					//平均分=总分（去除最高分、最低分）/个数（总个数-2）
					foreach (unserialize($v['project_ids']) as $project_k => $project_v) {
						$pxmdf_info = m('m_pxmdf') -> getone("pici_id=" . $v['id'] . " and mtype='" . $v['mtype'] . "' and xid=" . $project_v . " and com_status=1", "sum(user_zongfen) as zongfen,count(*) as count,id,max(user_zongfen) as max,min(user_zongfen) as min");
						$pingjunfen = 0;
						if($pxmdf_info && (int)$pxmdf_info['count']>2){
							$total = (int)$pxmdf_info['zongfen'] - (int)$pxmdf_info['max'] - (int)$pxmdf_info['min'];
							$count = (int)$pxmdf_info['count'] - 2;
							$pingjunfen = $total / $count;
						}else{
							if($pxmdf_info) $pingjunfen = (int)$pxmdf_info['zongfen'] / (int)$pxmdf_info['count'];
						}
						m('m_pxm_relation') -> update(array("zongfen" => $pxmdf_info['zongfen'], "pingjunfen" => $pingjunfen), "pici_id=" .$v['id'].' and xid='.$project_v);
					}

					//更改状态
					m('m_batch') -> update(array("com_status" => 2), "id=" . $v['id']);

					//对该批次中的专家发送消息
					foreach (unserialize($v['expert_ids']) as $expert_k => $expert_v) {

						//专家账号信息
						$userinfo = m('admin') -> getone("id=$expert_v");
						
						$logData=array(
							'pici_id'=>$v['id'],
							'pici_name'=>$v['pici_name'],
							'uid'=>$userinfo['id'],
							'uname'=>$userinfo['name'],
							'type'=>3
						);
						//判断该专家是否有相应的企业号账号,没有则不发送消息
						if (!empty($userinfo['wx_openid'])) {

							$data['articles'][0] = array("title" => '网评结束', 'description' => "详情请登录电脑端查看\n批次名称：" . $v['pici_name'] . "\n评审时间：" . date('Y-m-d H:i', strtotime($v['pici_start_time'])) . '至' . date('Y-m-d H:i', strtotime($v['pici_end_time'])) . "\n网评项目数：" . count(unserialize($v['project_ids'])) . "个", "url" => "", "picurl" => "");

							$flag=m('wxgzh:wxgzh')->doSend($userinfo['wx_openid'],'@all','@all',1, $data);
							if($flag->errcode==0){
								//发送成功
								$logData['remark']='发送成功';
							}else{
								//发送错误
								$logData['remark']='发送错误：\r\n'.$flag->errmsg;
							}
						} else {
							//专家没有微信公众号的openid
							$logData['remark']='发送失败：\r\n专家没有微信公众号的openid';
						}
						c('log')->marklog($logData);//记录发送消息信息
				
					}
					unset($expert_k, $expert_v);

				} else if (strtotime($v['pici_end_time']) - time() <= 86400 && !$this->is_send($v['id'],2) && time() < strtotime($v['pici_end_time'])) {
					//var_dump($v);exit;
					
					$pxmdf = $this->db->getall("SELECT pf.*,count(xid) as no_comment_num,a.name,a.wx_openid FROM pl_m_pxmdf pf LEFT JOIN pl_admin a ON a.id=pf.uid WHERE pici_id=".$v['id']." and com_status=0 group by uid");
					//echo m('m_pxmdf')->getLastSql();
					foreach ($pxmdf as $pxmdf_k => $pxmdf_v) {
						$logData=array(
							'pici_id'=>$v['id'],
							'pici_name'=>$v['pici_name'],
							'uid'=>$pxmdf_v['uid'],
							'uname'=>$pxmdf_v['name'],
							'type'=>2
						);
						//判断该专家是否有相应的企业号账号,没有则不发送消息
						if (!empty($pxmdf_v['wx_openid'])) {
							$data['articles'][0] = array("title" => '网评进度提醒，你有网评项目即将结束', 'description' => "该网评将在".date('Y-m-d H:i', strtotime($v['pici_end_time']))."结束，请尽快网评\n详情请登录电脑端查看\n批次名称：" . $v['pici_name'] . "\n评审时间：" . date('Y-m-d H:i', strtotime($v['pici_start_time'])) . '至' . date('Y-m-d H:i', strtotime($v['pici_end_time'])) ."\n网评项目数：".count(unserialize($v['project_ids']))."个\n未网评项目数：".$pxmdf_v['no_comment_num']."个", "url" => "", "picurl" => "");
							$flag = m('wxgzh:wxgzh')->doSend($pxmdf_v['wx_openid'],'@all','@all',1, $data);
							if($flag->errcode==0){
								//发送成功
								$logData['remark']='发送成功';
							}else{
								//发送错误
								$logData['remark']='发送错误：\r\n'.$flag->errmsg;
							}
						} else {
							//专家没有微信公众号的openid
							$logData['remark']='发送失败：\r\n专家没有微信公众号的openid';
						}
						c('log')->marklog($logData);//记录发送消息信息
					}
					unset($pxmdf_k,$pxmdf_v);
				}
				unset($k, $v);
			}

		}
	}


	/**
	 * 查询消息模板是否已发送
	 * $pici_id 批次id
	 * 模板类型  
	 */
	public function is_send($pici_id,$type){
		$flag = m('wechat_msg_log')->getone("pici_id={$pici_id} and type={$type}");
		if($flag){
			return true;
		}else{
			return false;
		}
	}

}
?>