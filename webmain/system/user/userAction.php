<?php
class userClassAction extends Action
{

    /**
     * 检测用户是否有专家身份
     * */
    public function check_isExpertAjax(){
        $uid = $this->adminid;
        $urs = $this->db->getone('xinhu_expert_info','mid='.$uid.' and is_draft=0');
        $this->requestsuccess($urs);
    }

    /**
     * 保存用户信息
     * */

    public function save_userinfoAjax(){
        $arr=array(
            'name' => $_REQUEST['input_names'],
            'sex' => $_REQUEST['input_sexs'],
            'ranking' => $_REQUEST['input_positions'],
            'mobile' => $_REQUEST['input_tels'],
            'email' => $_REQUEST['input_emails'],
            'graduate_project' => $_REQUEST['graduate_projects'],
            'research_direction' => $_REQUEST['research_directionss'],
            'bank_name' => $_REQUEST['bank_names'],
            'bank_cardnum' => $_REQUEST['bank_cardnum'],
            'bank_carduser' => $_REQUEST['user_names'],
        );
        $update_id = m('admin')->update($arr,'id='.$this->adminid);
        if ($update_id) {
            $this->requestsuccess($update_id);
        } else {
            $this->requesterror('个人信息更新失败');
        }
    }
}
