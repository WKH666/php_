<?php
/**
 * 登录控制器
 */

class loginClassAction extends ActionNot {

	public function defaultAction() {
	    $this -> tpltype = 'html';
		$this -> smartydata['ca_adminuser'] = $this -> getcookie('ca_adminuser');
		$this -> smartydata['ca_rempass'] = $this -> getcookie('ca_rempass');
		$this -> smartydata['ca_adminpass'] = $this -> getcookie('ca_adminpass');
	}

	/**
	 * 开发者：gzj
	 * 用户登录检验
	 * 参数:
	 adminuser:YWRtaW4:    账号
	 adminpass:MQ::        密码
	 rempass:1                                   是否记住密码     0否1是
	 verifycode            验证码
	 button:               按钮
	 jmpass:false          密码是否与之前的相同
	 device:1489643944439
	 */
	public function checkAjax() {
		$user = $this -> jm -> base64decode($this -> post('adminuser'));
		$user = str_replace(' ', '', $user);
		$pass = $this -> jm -> base64decode($this -> post('adminpass'));
		$rempass = $this -> post('rempass');
		$jmpass = $this -> post('jmpass');
		$verifycode = $this -> post('verifycode');
		if ($jmpass == 'true')
			$pass = $this -> jm -> uncrypt($pass);
		$arr = m('login') -> start($user, $pass, $verifycode, 'pc');
		$barr = array();
		if (is_array($arr)) {
			$uid = $arr['uid'];
			$name = $arr['name'];
			$user = $arr['user'];
			$token = $arr['token'];
			$face = $arr['face'];
			m('login') -> setsession($uid, $name, $token, $user, $arr['ranking']);
			$this -> rock -> savecookie('ca_adminuser', $user);
			$this -> rock -> savecookie('ca_rempass', $rempass);
			$ca_adminpass = $this -> jm -> encrypt($pass);
			if ($rempass == '0')
				$ca_adminpass = '';
			$this -> rock -> savecookie('ca_adminpass', $ca_adminpass);
			$barr['success'] = true;
			$barr['face'] = $face;
			$barr['maxsize'] = c('upfile') -> getmaxzhao();
		} else {
			$barr['success'] = false;
			$barr['msg'] = $arr;
		}
		$this -> returnjson($barr);
	}

//	/**
//	 * 退出登录(原)
//	 */
//	public function exitAction() {
//		m('login') -> exitlogin('pc', $this -> admintoken);
//		$this -> rock -> location('?m=login');
//	}

	/**
	 * 退出登录
	 */
	public function exitAction() {
		if(getconfig("cas_login")){
			m('login') -> exitlogin('pc', $this -> admintoken);
			$this -> rock -> location('?logout=true');
		}else{
			m('login') -> exitlogin('pc', $this -> admintoken);
			$this -> rock -> location('?m=login');
		}
	}

	/**
	 * 开发者gzj
	 * 验证码生成
	 */
	public function verifycodeAjax() {
		$image = imagecreatetruecolor(100, 30);//设置验证码图片大小的函数
		//设置验证码颜色 imagecolorallocate(int im, int red, int green, int blue);
		$bgcolor = imagecolorallocate($image,255,255,255); //#ffffff
		//区域填充 int imagefill(int im, int x, int y, int col) (x,y) 所在的区域着色,col 表示欲涂上的颜色
		imagefill($image, 0, 0, $bgcolor);
		//设置变量
		$captcha_code = "";

		//生成随机数字
		for ($i = 0; $i < 4; $i++) {
			//设置字体大小
			$fontsize = 6;
			//设置字体颜色，随机颜色
			$fontcolor = imagecolorallocate($image, rand(0, 120), rand(0, 120), rand(0, 120));
			//0-120深颜色
			//设置数字
			$fontcontent = rand(0, 9);
			//连续定义变量
			$captcha_code .= $fontcontent;
			//设置坐标
			$x = ($i * 100 / 4) + rand(5, 10);
			$y = rand(5, 10);

			imagestring($image, $fontsize, $x, $y, $fontcontent, $fontcolor);
		}
		//存到session
		$_SESSION['verifycode'] = md5($captcha_code);
		//增加干扰元素，设置雪花点
//		for ($i = 0; $i < 200; $i++) {
//			//设置点的颜色，50-200颜色比数字浅，不干扰阅读
//			$pointcolor = imagecolorallocate($image, rand(50, 200), rand(50, 200), rand(50, 200));
//			//imagesetpixel — 画一个单一像素
//			imagesetpixel($image, rand(1, 99), rand(1, 29), $pointcolor);
//		}
		//增加干扰元素，设置横线
		for ($i = 0; $i < 4; $i++) {
			//设置线的颜色
			$linecolor = imagecolorallocate($image, rand(80, 220), rand(80, 220), rand(80, 220));
			//设置线，两点一线
			imageline($image, rand(1, 99), rand(1, 29), rand(1, 99), rand(1, 29), $linecolor);
		}

		//设置头部，image/png
		header('Content-Type: image/png');
		//imagepng() 建立png图形函数
		imagepng($image);
		//imagedestroy() 结束图形函数 销毁$image
		imagedestroy($image);
	}

    /**
     * 注册账号页面
     */
    public function registerAction() {}

    /**
     * 注册账号操作
     */
    public function register_addAjax() {
        $email = $_POST['email'];
        $password = md5($_POST['password_queren']);
        $email_code = $_POST['email_code'];
        $adddt = date("Y-m-d h:i:s",time());
        //检查该邮箱是否已注册账号
        $sql = "select * from xinhu_admin where email='$email'";
        $result = $this->db->getall($sql);
        if (!$result && $email && $password){
            if ($email_code == $_SESSION['email_code']){
                $sql = "insert into xinhu_admin (user,email,pass,adddt) values ('$email','$email','$password','$adddt')";
                $result = $this->db->query($sql);
                if ($result){
                    echo json_encode(array(
                        'code' => 1,
                        'result' => '注册成功，请完善资料',
                    ));
                }else{
                    echo json_encode(array(
                        'code' => 0,
                        'result' => '注册失败',
                    ));
                }
            }else{
                echo json_encode(array(
                    'code' => 0,
                    'result' => '验证码错误',
                ));
            }
        }else{
            echo json_encode(array(
                'code' => 0,
                'result' => '该账号已注册',
            ));
        }
        $_SESSION['email_code'] = "";
    }

    /**
     * 完善资料页面
     */
    public function improve_informationAction() {

    }

    /**
     * 完善资料入库
     */
    public function improve_information_insertAjax() {
        $school_name = $_POST['school_name'];
        $ranking = $_POST['ranking'];
        $name = $_POST['name'];
        $mobile = $_POST['mobile'];
        $graduate_project = $_POST['graduate_project'];
        $research_direction = $_POST['research_direction'];
        $email = $_POST['email'];
        if ($email){
            $sql = "update xinhu_admin set school_name='$school_name',ranking='$ranking',name='$name',mobile='$mobile',graduate_project='$graduate_project',research_direction='$research_direction' where email='$email'";
            $result = $this->db->query($sql);
            if ($result){
                echo json_encode(array(
                    'code' => 1,
                    'result' => '完善资料成功',
                ));
            }else{
                echo json_encode(array(
                    'code' => 0,
                    'result' => '完善资料失败',
                ));
            }
        }else{
            echo json_encode(array(
                'code' => 0,
                'result' => '完善资料失败',
            ));
        }
    }

    /**
     * 忘记密码页面
     */
    public function forget_passwordAction() {

    }

    /**
     * 忘记密码验证
     */
    public function forget_password_inspectAjax() {
        $email = $_POST['email'];
        $email_code = $_POST['email_code'];
        //检查该邮箱是否已注册账号
        $sql = "select * from xinhu_admin where email='$email'";
        $result = $this->db->getall($sql);
        if ($result && $email && $email_code){
            if ($email_code == $_SESSION['email_code']){
                echo json_encode(array(
                    'code' => 1,
                    'result' => '验证成功，请重置密码',
                ));
            }else{
                echo json_encode(array(
                    'code' => 0,
                    'result' => '验证码错误',
                ));
            }
        }else{
            echo json_encode(array(
                'code' => 0,
                'result' => '该账号未注册',
            ));
        }
        $_SESSION['email_code'] = "";
    }

    /**
     * 设置密码页面
     */
    public function set_passwordAction() {
    }

    /**
     * 提交设置密码
     */
    public function set_password_insertAjax() {
        $email = $_POST['email'];
        $password = md5($_POST['password_queren']);
        $sql = "update xinhu_admin set pass='$password' where email='$email'";
        $result = $this->db->query($sql);
        if ($result){
            echo json_encode(array(
                'code' => 1,
                'result' => '修改密码成功',
            ));
        }else{
            echo json_encode(array(
                'code' => 0,
                'result' => '修改密码失败',
            ));
        }
    }

    /**
     * 邮箱验证码
     */
    public function email_codeAjax() {
        $email = $_POST['email'];//获取收件人邮箱
        //$sendmail = '1302083198@qq.com'; //发件人邮箱
        $sendmail = '910257544@qq.com'; //发件人邮箱
        $sendmailpswd = "hzpmtvuchlvebfgd"; //客户端授权密码,而不是邮箱的登录密码，就是手机发送短信之后弹出来的一长串的密码
        $send_name = '自动发送';// 设置发件人信息，如邮件格式说明中的发件人，
        $toemail = $email;//定义收件人的邮箱
        $to_name = '注册用户';//设置收件人信息，如邮件格式说明中的收件人
        require_once "./include/PHPMailer/class.phpmailer.php";
        $mail = new PHPMailer();
        $mail->isSMTP();// 使用SMTP服务
        $mail->CharSet = "utf8";// 编码格式为utf8，不设置编码的话，中文会出现乱码
        $mail->Host = "smtp.qq.com";// 发送方的SMTP服务器地址
        $mail->SMTPAuth = true;// 是否使用身份验证
        $mail->Username = $sendmail;//// 发送方的
        $mail->Password = $sendmailpswd;//客户端授权密码,而不是邮箱的登录密码！
        $mail->SMTPSecure = "ssl";// 使用ssl协议方式
        $mail->Port = 465;//  qq端口465或587）
        $mail->setFrom($sendmail, $send_name);// 设置发件人信息，如邮件格式说明中的发件人，
        $mail->addAddress($toemail, $to_name);// 设置收件人信息，如邮件格式说明中的收件人，
        $mail->addReplyTo($sendmail, $send_name);// 设置回复人信息，指的是收件人收到邮件后，如果要回复，回复邮件将发送到的邮箱地址
        $mail->Subject = "珠海社会科学";// 邮件标题

        $code=rand(100000,999999);
        $_SESSION['email_code'] = $code;
        //return $code."----".session("code");
        $mail->Body = "您正在注册账号，邮件内容是您的验证码是：'$code'，如果非本人操作无需理会！";// 邮件正文
        //$mail->AltBody = "This is the plain text纯文本";// 这个是设置纯文本方式显示的正文内容，如果不支持Html方式，就会用到这个，基本无用
        if (!$mail->send()) { // 发送邮件
            echo "Message could not be sent.";
            echo "Mailer Error: " . $mail->ErrorInfo;// 输出错误信息
        } else {
            echo json_encode(array(
                'code' => $code,
            ));
         }
    }

}
