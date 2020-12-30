<?php

class userinfoClassAction extends Action
{
    public function userinfobefore($table)
    {
        $table = '`[Q]admin` a left join `[Q]userinfo` b on a.id=b.id';
        $s = '';
        $key = $this->post('key');
        if ($key != '') {
            $s = " and (a.`name` like '%$key%' or a.`user` like '%$key%' or a.`ranking` like '%$key%' or a.`deptname` like '%$key%') ";
        }
        return array(
            'table' => $table,
            'where' => $s,
            'fields' => 'a.name,a.deptname,a.id,a.status,a.ranking,b.id as ids,b.dkip,b.dkmac,b.iskq,b.isdwdk'
        );
    }

    public function userinfoafter($table, $rows)
    {
        $db = m($table);
        foreach ($rows as $k => $rs) {
            if (isempt($rs['ids'])) {
                $db->insert(array(
                    'id' => $rs['id'],
                    'name' => $rs['name'],
                    'deptname' => $rs['deptname'],
                    'ranking' => $rs['ranking']
                ));
            }
        }
        return array('rows' => $rows);
    }

    public function fieldsafters($table, $fid, $val, $id)
    {
        $fields = 'sex,ranking,tel,mobile,workdate,email,quitdt';
        if (contain($fields, $fid)) {
            if ($fid == 'quitdt') {
                $dbs = m($table);
                if (isempt($val)) {
                    $dbs->update('`state`=0', "`id`='$id' and `state`=5");
                } else {
                    $dbs->update('`state`=5', "`id`='$id'");
                }
            }
            m('admin')->update(array($fid => $val), $id);
        }
    }


    public function userinfobeforegeren()
    {
        return ' and id=' . $this->adminid . '';
    }

    //人员分析
    public function useranaybefore()
    {
        return 'and 1=2';
    }

    public function useranayafter($table, $rows)
    {
        $type = $this->post('type', 'deptname');
        $dt = $this->post('dt');
        $db = m('userinfo');
        $where = 'and state<>5';
        if ($dt != '') {
            $where = "and ((state<>5 and workdate<='$dt') or (state=5 and workdate<='$dt' and  quitdt>'$dt'))";
        }

        $rows = $db->getall("id>0 $where", 'deptname,sex,xueli,state,birthday,workdate,quitdt,ranking');

        $nianls = array(
            array(0, '16-20岁', 16, 20),
            array(0, '21-25岁', 21, 25),
            array(0, '26-30岁', 26, 30),
            array(0, '31-40岁', 31, 40),
            array(0, '41岁以上', 41, 9999),
            array(0, '其他', -555, 15),
        );

        $yearls = array(
            array(0, '1年以下', 0, 1),
            array(0, '1-3年', 1, 3),
            array(0, '3-5年', 3, 5),
            array(0, '5-10年', 5, 10),
            array(0, '10年以上', 10, 9999)
        );

        $atatea = explode(',', '试用期,正式,实习生,兼职,临时工,离职');
        foreach ($rows as $k => $rs) {
            $year = '';
            if (!$this->isempt($rs['workdate'])) $year = substr($rs['workdate'], 0, 4);
            $rows[$k]['year'] = $year;

            $lian = $this->jsnianl($rs['birthday']);
            foreach ($nianls as $n => $nsa) {
                if ($lian >= $nsa[2] && $lian <= $nsa[3]) {
                    $rows[$k]['nian'] = $nsa[1];
                    break;
                }
            }

            $state = (int)$rs['state'];
            $rows[$k]['state'] = $atatea[$state];

            //入职连
            $nan = $this->worknx($rs['workdate']);
            foreach ($yearls as $n => $nsa) {
                if ($nan >= $nsa[2] && $nan < $nsa[3]) {
                    $rows[$k]['nianxian'] = $nsa[1];
                    break;
                }
            }
        }

        $arr = array();
        $total = $this->db->count;
        foreach ($rows as $k => $rs) {
            $val = $rs[$type];
            if ($this->isempt($val)) $val = '其他';
            if (!isset($arr[$val])) $arr[$val] = 0;
            $arr[$val]++;
        }

        $a = array();
        foreach ($arr as $k => $v) {
            $a[] = array(
                'name' => $k,
                'value' => $v,
                'bili' => ($this->rock->number($v / $total * 100)) . '%'
            );
        }

        return array(
            'rows' => $a,
            'totalCound' => count($a)
        );
    }

    private function jsnianl($dt)
    {
        $nY = date('Y') + 1;
        $lx = 0;
        if (!$this->isempt($dt) && !$this->contain($dt, '0000')) {
            $ss = explode('-', $dt);
            $saa = (int)$ss[0];
            $lx = $nY - $saa;
        }
        return $lx;
    }

    //计算工作年限的
    private function worknx($dt)
    {
        $w = 0;
        if (!$this->isempt($dt) && !$this->contain($dt, '0000')) {
            $startt = strtotime($dt);
            $date = date('Y-m-d');
            $endtime = strtotime($date);
            $w = (int)(($endtime - $startt) / (24 * 3600) / 365);
        }
        return $w;
    }


    //获取用户基本信息
    public function getuserinfoAjax()
    {
        $urs = $this->db->getone("xinhu_admin", "id=" . $this->adminid . "");
        if ($urs) {
            $this->requestsuccess($urs);
        } else {
            $this->requesterror('数据请求返回错误');
        }
    }

    //获取用户的其他专家认证信息
    public function getOtherInfoAjax()
    {
        //先判断是否存在草稿,不存在获取的已保存的专家信息,
        $r1 = m('expert_info')->getone('mid=' . $this->adminid . ' and is_draft=1');
        if ($r1) {
            $this->requestsuccess($r1);
        }else{
            $r2 = m('expert_info')->getone('mid=' . $this->adminid . ' and is_draft=0');
            if ($r2){
                $this->requestsuccess($r2);
            }else{
                $this->requesterror('无专家认证信息');
            }
        }
    }

    //提交专家信息进行审核
    public function saveExpertInfoAjax()
    {
        //查询当前用户是否已有专家信息,有的话就先向专家审核记录表添加一条记录,等待审核通过再更新专家信息
        $mid = $this->adminid;
        $r = m("expert_info")->getone('mid=' . $mid . ' and is_draft=0');
        if ($r) {
            //判断专家是否有提交但未审核的记录，有则提示用户等审核了再更新信息
            $rb = m('expert_record')->getone("mid = $mid and is_check=0");
            if ($rb){
                $this->requesterror('您先前提交的信息更新还未审核,请等审核通过再提交');
            }else{
                $arr = Array(
                    'mid' => $this->adminid,
                    'name' => $_REQUEST['input_name'],
                    'sex' => $_REQUEST['input_sex'],
                    'mobile' => $_REQUEST['input_tel'],
                    'email' => $_REQUEST['input_email'],
                    'company' => $_REQUEST['input_company'],
                    'position' => $_REQUEST['input_position'],
                    'research_direction' => $_REQUEST['research_direction'],
                    'graduate_project' => $_REQUEST['graduate_project'],
                    'nation' => $_REQUEST['input_nation'],
                    'birth_date' => $_REQUEST['input_datetime'],
                    'birth_place' => $_REQUEST['input_location'],
                    'position2' => $_REQUEST['input_position2'],
                    'politic_face' => $_REQUEST['politic_face'],
                    'graduate_school' => $_REQUEST['graduate_school'],
                    'academic_degree' => $_REQUEST['input_academic'],
                    'address' => $_REQUEST['input_address'],
                    'part_time_jobs' => $_REQUEST['part_time_jobs'],
                    'curriculum_vitae' => $_REQUEST['curriculum_vitae'],
                    'achievements' => $_REQUEST['achievements'],
                    'project_review' => $_REQUEST['project_review'],
                );
                $insert_id = m('expert_record')->insert($arr);
                if ($insert_id) {
                    $this->requestsuccess($insert_id);
                } else {
                    $this->requesterror('专家数据更新提交失败');
                }
            }
        } else {
            $arr = Array(
                'mid' => $this->adminid,
                'name' => $_REQUEST['input_name'],
                'sex' => $_REQUEST['input_sex'],
                'mobile' => $_REQUEST['input_tel'],
                'email' => $_REQUEST['input_email'],
                'company' => $_REQUEST['input_company'],
                'position' => $_REQUEST['input_position'],
                'research_direction' => $_REQUEST['research_direction'],
                'graduate_project' => $_REQUEST['graduate_project'],
                'nation' => $_REQUEST['input_nation'],
                'birth_date' => $_REQUEST['input_datetime'],
                'birth_place' => $_REQUEST['input_location'],
                'position2' => $_REQUEST['input_position2'],
                'politic_face' => $_REQUEST['politic_face'],
                'graduate_school' => $_REQUEST['graduate_school'],
                'academic_degree' => $_REQUEST['input_academic'],
                'address' => $_REQUEST['input_address'],
                'part_time_jobs' => $_REQUEST['part_time_jobs'],
                'curriculum_vitae' => $_REQUEST['curriculum_vitae'],
                'achievements' => $_REQUEST['achievements'],
                'project_review' => $_REQUEST['project_review'],
                'is_expert'=>0
            );
            //专家信息表加一条数据
            $insert_id = m('expert_info')->insert($arr);
            //专家审核记录表加一条数据
            $insert_id2 = m('expert_record')->insert($arr);
            //删除草稿
            m('expert_info')->delete('mid=' . $mid . ' and is_draft=1');
            if ($insert_id && $insert_id2) {
                $this->requestsuccess($insert_id);
            } else {
                $this->requesterror('专家数据新增失败');
            }
        }

    }

    //保存草稿中的专家信息
    public function draftExpertInfoAjax()
    {
        //查询当前用户是否已有专家信息的草稿,已有则更新
        $mid = $this->adminid;
        $r = m("expert_info")->getone('mid=' . $mid . ' and is_draft=1');
        if ($r) {
            $arr = Array(
                'name' => $_REQUEST['input_name'],
                'sex' => $_REQUEST['input_sex'],
                'mobile' => $_REQUEST['input_tel'],
                'email' => $_REQUEST['input_email'],
                'company' => $_REQUEST['input_company'],
                'position' => $_REQUEST['input_position'],
                'research_direction' => $_REQUEST['research_direction'],
                'graduate_project' => $_REQUEST['graduate_project'],
                'nation' => $_REQUEST['input_nation'],
                'birth_date' => $_REQUEST['input_datetime'],
                'birth_place' => $_REQUEST['input_location'],
                'position2' => $_REQUEST['input_position2'],
                'politic_face' => $_REQUEST['politic_face'],
                'graduate_school' => $_REQUEST['graduate_school'],
                'academic_degree' => $_REQUEST['input_academic'],
                'address' => $_REQUEST['input_address'],
                'part_time_jobs' => $_REQUEST['part_time_jobs'],
                'curriculum_vitae' => $_REQUEST['curriculum_vitae'],
                'achievements' => $_REQUEST['achievements'],
                'project_review' => $_REQUEST['project_review'],
                'is_draft' => 1,
            );
            $update_id = m('expert_info')->update($arr, 'mid=' . $mid . ' and is_draft=1');
            if ($update_id) {
                $this->requestsuccess($update_id);
            } else {
                $this->requesterror('专家数据更新失败');
            }
        } else {
            $arr = Array(
                'mid' => $this->adminid,
                'name' => $_REQUEST['input_name'],
                'sex' => $_REQUEST['input_sex'],
                'mobile' => $_REQUEST['input_tel'],
                'email' => $_REQUEST['input_email'],
                'company' => $_REQUEST['input_company'],
                'position' => $_REQUEST['input_position'],
                'research_direction' => $_REQUEST['research_direction'],
                'graduate_project' => $_REQUEST['graduate_project'],
                'nation' => $_REQUEST['input_nation'],
                'birth_date' => $_REQUEST['input_datetime'],
                'birth_place' => $_REQUEST['input_location'],
                'position2' => $_REQUEST['input_position2'],
                'politic_face' => $_REQUEST['politic_face'],
                'graduate_school' => $_REQUEST['graduate_school'],
                'academic_degree' => $_REQUEST['input_academic'],
                'address' => $_REQUEST['input_address'],
                'part_time_jobs' => $_REQUEST['part_time_jobs'],
                'curriculum_vitae' => $_REQUEST['curriculum_vitae'],
                'achievements' => $_REQUEST['achievements'],
                'project_review' => $_REQUEST['project_review'],
                'is_draft' => 1,
            );
            $insert_id = m('expert_info')->insert($arr);
            if ($insert_id) {
                $this->requestsuccess($insert_id);
            } else {
                $this->requesterror('专家数据新增失败');
            }
        }
    }
}
