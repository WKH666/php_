<?php

class indexClassAction extends Action
{

    private function homeicons()
    {

        $myext = $this->getsession('adminallmenuid');
        $where = '';
        if ($myext != '-1') {
            $where = ' and `id` in(' . str_replace(array('[', ']'), array('', ''), $myext) . ')';
        }
        $mrows = m('menu')->getrows("`ishs`=1 and `status`=1 $where ", "`id`,`num`,`name`,`url`,`color`,`icons`", '`sort`');
        return $mrows;
    }

    public function gettotalAjax()
    {
        $loadci = (int)$this->get('loadci', '0');
        $optdta = $this->get('optdt');
        $optdt = $this->now;
        $uid = $this->adminid;
        $arr['optdt'] = $optdt;
        $todo = m('todo')->rows("uid='$uid' and `status`=0 and `tododt`<='$optdt'");
        $arr['todo'] = $todo;
        if ($loadci == 0) {
            $arr['showkey'] = $this->jm->base64encode($this->jm->getkeyshow());
            $arr['menuarr'] = $this->homeicons();
            $arr['token'] = $this->admintoken;
            $tssval = $this->option->getval('systaskrun');
            //没服务端时用户id=1就读取计划任务列表
            if ($this->adminid == 1 && !contain($tssval, $this->rock->date)) {
                $arr['tasklist'] = m('task')->getrunlist();
                $arr['tasklista'] = $tssval;
            }
        }
        $s = $s1 = '';
        if ($loadci == 0) {
            if ($todo > 0) {
                $s = '您还有<font color=red>(' . $todo . ')</font>条未读提醒信息;<a onclick="return opentixiangs()" href="javascript:">[查看]</a>';
                $s1 = '您还有(' . $todo . ')条未读提醒信息;';
            }
        } else {
            if ($todo > 0) {
                $rows = m('todo')->getrows("uid='$uid' and `status`=0 and `optdt`>'$optdta' and `tododt`<='$optdt' order by `id` limit 3");
                foreach ($rows as $k => $rs) {
//					$s .= ''.($k+1).'、['.$rs['title'].']'.$rs['mess'].'。<br>';
//					$s1.= ''.($k+1).'、['.$rs['title'].']'.$rs['mess'].'。'."\n";
                    $s .= '' . ($k + 1) . '、' . $rs['mess'] . '。<br>';
                    $s1 .= '' . ($k + 1) . '、' . $rs['mess'] . '。' . "\n";
                }
            }
        }
        $msgar[0] = $s;
        $msgar[1] = $s1;
        $arr['msgar'] = $msgar;
        $arr['total'] = m('totals')->gettotals($uid);
        $arr['gongarr'] = $this->getgonglist();
        $arr['applyarr'] = m('flowbill')->homelistshow();
        $arr['meetarr'] = m('meet')->getmeethome($this->date, $uid);
        $this->returnjson($arr);
    }

    public function getqrcoresAjax()
    {
        $admin_info = m('admin')->getone('id=' . $this->adminid);

        if (!function_exists('ImageCreate')) {
            echo '' . URL . '?d=we';
        } else if ($admin_info['wx_openid'] == '') {
            echo 'wx';
            //echo 'ok';
        } else {
            echo 'ok';
        }
    }

//	public function getqrcodeAjax()
//	{
//		header("Content-type:image/png");
//		$url = ''.URL.'?m=login&d=we&token='.$this->admintoken.'&user='.$this->jm->base64encode($this->adminuser).'';
//		$img = c('qrcode')->show($url);
//		echo $img;
//	}

    //微信每次登录
    public function getqrcodeAjax()
    {
        header("Content-type:image/png");

//		$url_lencode=urlencode(getconfig('url').'api.php?m=openwx&openkey=xaingmuku&a=login_wx');

        $url_lencode = urlencode(getconfig('url') . 'api.php?m=openwx&openkey=xaingmuku&a=login_wx');
        $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxfb2ce3bfe3276283&redirect_uri=' . $url_lencode . '&response_type=code&scope=snsapi_base&state=STATE#wechat_redirect';


        $img = c('qrcode')->show($url);
        echo $img;
    }

//	public function getqrcodeAjax()
//	{
//		header("Content-type:image/png");
//		$url = ''.URL.'?m=login&d=we&token='.$this->admintoken.'&user='.$this->jm->base64encode($this->adminuser).'';
//		$img = c('qrcode')->show($url);
//		echo $img;
//	}

    private function getgonglist()
    {
        $rows = m('flow')->initflow('gong')->getflowrows($this->adminid, 'wexx');
        return $rows;
    }

    //获取当前用户的提醒信息的个数
    public function showremindnumAjax()
    {
        $uid = $this->adminid;
        $rs = m('todo')->getall("uid = $uid and status =0");
        $count = count($rs);
        $this->returnjson(array('success' => true, 'data' => $count, 'msg' => '获取数据成功'));
    }

    //获取当前用户的通知书
    public function noticePersonAjax(){
        $uid = $this->adminid;
        $where1 = '';
        $where2 = '';
        $type_zh = array(
            1 => '课题申报立项通知书',
            2 => '课题申报结项通知书',
            3 => '普及月申报入选通知书',
            4 => '常态化申报入选通知书',
            5 => '研究基地立项通知书',
            6=>'课题申报编制成果要求',
            7=>'后期认定结项通知书'
        );
        $rs = m('notice')->getall('1=1 ' . $where1);
        $rows = array();
        foreach ($rs as $item) {
            $sericnum_arr = explode(',', $item['sericnum']);
            foreach ($sericnum_arr as $iem) {
                $rs2 = m('flow_bill')->getall("sericnum = '$iem' and uid = $uid", 'id,sericnum');
                if ($rs2) {
                    $notice_id = $item['id'];
                    $arr = array();
                    $rs3 = '';
                    if ($item['type'] == 1 || $item['type'] == 2 || $item['type'] == 6) {
                        $rs3 = m('project_approval')->getone("sericnum = '$iem' and notice_id = $notice_id $where2");
                    } else if ($item['type'] == 3 || $item['type'] == 4 || $item['type'] == 5) {
                        $rs3 = m('project_approval')->getone("sericnum = '$iem' and notice_id = $notice_id $where2");
                    }
                    if ($rs3) {
                        $arr = array(
                            'notice_id' => $item['id'],
                            'id' => $rs3['id'],
                            'type' => $rs3['type'],
                            'project_name' => $rs3['project_name'],
                            'title' => $item['title'],
                            'optdt' => $item['optdt'],
                            'caoz' => '<a onclick="readNoticeDetail(' . $rs3['id'] . ',' . $item['id'] . ',' . $rs3['type'] . ')">查看</a>',
                            'params1'=>$rs3['id'],
                            'params2'=>$item['id'],
                            'params3'=>$rs3['type'],
                        );
                        array_push($rows, $arr);
                    }
                }
            }
        }
        foreach ($rows as $k => $v) {
            $rows[$k]['type'] = $type_zh[$v['type']];
        }
        $datas = array('rows' => array_reverse($rows), 'totalCount' => count($rows));
        $this->returnjson($datas);
    }
}
