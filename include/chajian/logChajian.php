<?php 
/**
	html相关插件
*/
class logChajian extends Chajian{
	
	/**
	 * 记录发送的模板消息
	 * $data=array(
	 * pici_id 批次id
	 * pici_name 批次名称
	 * uid 用户id
	 * uname 用户名 
	 * type 模板类型   0、发起时  1、追加项目时   2、结束前一天   3、结束
	 * remark 备注
	 * );
	 */
	public function marklog($data){
		//专家没有微信公众号的openid
		m('wechat_msg_log')->insert(array(
			'pici_id'=>$data["pici_id"],
			'pici_name'=>$data['pici_name'],
			'uid'=>$data['uid'],
			'uname'=>$data['uname'],
			'type'=>$data['type'],
			'add_time'=>date('Y-m-d H:i:s',time()),
			'remark'=>$data['remark']
		));
	}
}                                  