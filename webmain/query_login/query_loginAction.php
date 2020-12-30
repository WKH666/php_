<?php
/**
 * 登录控制器
 */

class query_loginClassAction extends ActionNot {

    public function initAction()
    {
        $deptrows = $this->db->getall("SELECT id,num,name,pid FROM xinhu_dept WHERE pid = 0");
        $subjectrows = $this->db->getall("SELECT id,pid,name FROM xinhu_subject_sort WHERE del_status = 0");
        $this->assign('deptrows',$deptrows);
        $this->assign('subjectrows',$subjectrows);
    }

    public function defaultAction() {
		$this -> tpltype = 'html';
	}

	/**
	 * 开发者：gzj
	 * 用户登录检验
	 * 参数:
	 adminuser:账号
	 adminpass:密码
	 device:1489643944439
	 */
	public function checkAjax() {
		$user = $this -> jm -> base64decode($this -> post('adminuser'));
		$user = str_replace(' ', '', $user);
		$pass = $this -> jm -> base64decode($this -> post('adminpass'));
		$arr = m('query_login') -> start($user, $pass, 'pc');
		$barr = array();
		if (is_array($arr)) {
			$uid = $arr['uid'];
			$name = $arr['name'];
			$user = $arr['user'];
			$token = $arr['token'];
			m('query_login') -> setsession($uid, $name, $token, $user, $arr['ranking']);
            $log = m('query_login') -> getsavesession();
            $barr['success'] = true;
            $barr['msg'] = $arr;
		} else {
			$barr['success'] = false;
			$barr['msg'] = $arr;
		}
		$this -> returnjson($barr);
	}

	/**
	 * 退出登录
	 */
	public function exitAction() {
        m('query_login') -> exitlogin('pc', $this -> admintoken);
        $this -> rock -> location('?m=query_login');
	}


    /**
     * 提交密码修改
     */
    public function loadexitAjax(){
        $fmail = $this->post('setmail');
        $fpwd = md5($this->post('setpwd'));
        $rows = $this->db->getall("SELECT * FROM xinhu_admin WHERE email = '$fmail'");
        if(count($rows)>1){
            $arr = array(
                'msg' => '邮箱有重复无法修改',
                'TotalCount' => count($rows)
            );
            $this->returnjson($arr);
        }else if(count($rows)==1){
            foreach($rows as $k => $v){
                $modify["pass"] = $fpwd;
            }
            $bool= m("admin")->update($modify, "id = ".$rows[$k]['id'].'');
            if($bool){
                $arr = array(
                    'msg' => '提交成功<br/>已成功修改',
                    'TotalCount' => count($rows),
                );
                $this->returnjson($arr);
            }
        }
    }

    /**
     * 注册账号->提交资料
     */
    public function uloaddataAjax(){
        $uemail = $this->post('email');
        $upass = md5($this->post('pass'));
        $udeptid = $this->post('deptid');
        $udeptname = $this->post('deptname');
        $udeptallname = $this->post('deptallname');
        $uranking = $this->post('ranking');
        $uuser = $this->post('user');
        $uname = $this->post('name');
        $umobile = $this->post('mobile');
        $psubjectid = $this->post('subjectid');
        $uhead_subject = $this->post('head_subject');
        $ures_dir = $this->post('res_dir');
        $udepath = "[".$udeptid."]";
        $adarr = array(
            'email' => $uemail,
            'pass' => $upass,
            'deptid' => $udeptid,
            'deptname' => $udeptname,
            'deptallname' => $udeptallname,
            'deptpath' => $udepath,
            'ranking' => $uranking,
            'user' => $uuser,
            'name' => $uname,
            'mobile' => $umobile,
            'adddt' => date('Y-m-d h:i:s', time())
        );
        $padminid= m("admin")->insert($adarr); //返回插入新的id
        if($padminid){
            $perarr = array(
                'adminid' => $padminid,
                'subjectid' => $psubjectid,
                'head_subject' => $uhead_subject,
                'res_dir' => $ures_dir
            );
            m("perfect_data")->insert($perarr); //返回插入新的id
            if($perarr){
                echo json_encode(array(
                    'code' => 1,
                    'msg' => '提交成功<br/>注册申请已提交,1-3工作审核通过后即可登录'
                ));
            }
        }else{
            echo json_encode(array(
                'code' => 0,
                'msg' => '提交资料失败'
            ));
        }
    }


    /**
     * 邮件发送
     * @param $to 接收人
     * @param string $subject 邮件标题
     * @param string $content 邮件内容(html模板渲染后的内容)
     * @return bool
     * @throws phpmailerException
     */
    public function send_emailAjax(){
        $remail = $this->post('remail');
        $vcode = $this->post('vscode');
    // 引入PHPMailer的核心文件
        require_once("PHPMailer/class.phpmailer.php");
        require_once("PHPMailer/class.smtp.php");
    // 实例化PHPMailer核心类
        $mail = new PHPMailer();
    // 是否启用smtp的debug进行调试 开发环境建议开启 生产环境注释掉即可 默认关闭debug调试模式
        $mail->SMTPDebug = 1;
    // 使用smtp鉴权方式发送邮件
        $mail->isSMTP();
    // smtp需要鉴权 这个必须是true
        $mail->SMTPAuth = true;
    // 链接qq域名邮箱的服务器地址
        $mail->Host = 'smtp.qq.com';
    // 设置使用ssl加密方式登录鉴权
        $mail->SMTPSecure = 'ssl';
    // 设置ssl连接smtp服务器的远程服务器端口号
        $mail->Port = 465;
    // 设置发送的邮件的编码
        $mail->CharSet = 'UTF-8';
    // 设置发件人昵称 显示在收件人邮件的发件人邮箱地址前的发件人姓名
        $mail->FromName = '886';
    // smtp登录的账号 QQ邮箱即可
        $mail->Username = '1239724372@qq.com';
    // smtp登录的密码 使用生成的授权码
        $mail->Password = 'wjykjtzxqwejibdi';
    // 设置发件人邮箱地址 同登录账号
        $mail->From = '1239724372@qq.com';
    // 邮件正文是否为html编码 注意此处是一个方法
        $mail->isHTML(true);
    // 设置收件人邮箱地址
        $mail->addAddress($remail);
    // 添加多个收件人 则多次调用方法即可
//        $mail->addAddress('2863271799@qq.com');
    // 添加该邮件的主题
        $mail->Subject = '珠海社科网';
    // 添加邮件正文
        $mail->Body = '<p>珠海社科网账号邮箱注册验证码:</p>' . $vcode;
    // 发送邮件 返回状态
        $status = $mail->send();
        if($status){
            echo json_encode(array(
                'msg' => '验证码已发送到邮箱',
                'vscode' => $vcode
            ));
        }else{
            echo json_encode(array(
                'msg' => '验证码发送失败'
            ));
        }
    }





}
