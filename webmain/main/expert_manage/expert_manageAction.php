<?php

class expert_manageClassAction extends Action
{
    public function initAction()
    {

    }

    /**
     * 专家审核列表
     */
    public function expertlistAjax()
    {
        $table = '`[Q]expert_record` as a LEFT JOIN `[Q]admin` as b ON b.id=a.mid';
        $fields = 'a.*';
        $where = 'a.status = 1';
        $order = 'a.id DESC';
        $this->getlist($table, $fields, $where, $order);
    }

    /**
     * 公共的列表获取方法
     */
    public function getlist($table, $fields, $where, $order, $childtable = '')
    {
        $beforea = $this->request('storebeforeaction');//数据权限处理函数
        $aftera = $this->request('storeafteraction');//操作权限处理函数
        if ($beforea != '') {//数据权限处理
            if (method_exists($this, $beforea)) {
                $where .= $this->$beforea();
            }
        }

        $arr = $this->limitRows($table, $fields, $where, $order);
        $arr['totalCount'] = $arr['total'];
        unset($arr['sql'], $arr['total']);
        //echo $arr['sql'];exit;
        //if($arr['totalCount'] == 0) exit('暂无数据');
        if (method_exists($this, $aftera)) {//操作菜单权限处理
            $narr = $this->$aftera($childtable, $arr['rows']);
            if (is_array($narr)) {
                foreach ($narr as $kv => $vv) $arr['rows'][$kv] = $vv;
            }
        }

        $this->returnjson($arr);
    }

    /**
     * 专家审核列表操作获取
     */
    public function expertlistafter($table, $rows)
    {
        foreach ($rows as $k => $rs) {


            $mid = $rs['mid'];
            //获取账号,注册时间
            $r = m("admin")->getone('id=' . $mid . '');
            $rows[$k]['user'] = $r['user'];
            $rows[$k]['register_time'] = $r['adddt'];
            $rows[$k]['caoz'] = '';
            if ($rs['is_check'] == 0) {
                $rows[$k]['check_status'] = '待审核';

                $rows[$k]['caoz'] .= '<a onclick="auditresultscheck(' . $rs['id'] . ')">审核</a>';
            } else {
                $rows[$k]['check_status'] = '已审核';
                $rows[$k]['caoz'] .= '<a onclick="auditresultsee(' . $rs['id'] . ')">查看</a>';
            }
            if ($rs['opt_status'] == 0) {
                $rows[$k]['opt_status'] = '拒绝';
            } else {
                $rows[$k]['opt_status'] = '通过';
            }
        }
        return $rows;
    }

    /**
     * 专家库审核记录
     **/
    public function getresultsAjax()
    {
        $results_id = $_POST['results_id'];
        $rows = m("expert_record")->getone("mid=" . $results_id . "");
        $this->returnjson($rows);
    }

    /**
     * 专家库查看审核记录和审核操作
     **/
    public function getchecksAjax()
    {
        $results_id = $_POST['results_id'];
        $rows = m("expert_record")->getone("id=" . $results_id . "");
        $this->returnjson($rows);
    }

    /**
     * 专家库审核操作
     **/
    public function getseesAjax()
    {
        $results_id = $_POST['results_id'];
        $rows = m("expert_record")->getone("id=" . $results_id . "");
        $this->returnjson($rows);
    }

    /**
     * 专家库搜索功能
     **/
    public function expertlistbefore()
    {
        $user = trim($this->post('user'));
        $name = trim($this->post('name'));
        $company = trim($this->post('company'));
        $where = ' ';
        //查询
        if ($user) {
            $where .= "and b.user like '%$user%'";
        }
        if ($name) {
            $where .= "and a.name like '%$name%'";
        }
        if ($company) {
            $where .= "and a.company like '%$company%'";
        }
        return $where;
    }

    /**
     * 审核列表
     **/
    public function expertchecksAjax()
    {
        $results_id = $_POST['result_id'];
        $id = m("expert_record")->getone('id=' . $results_id . '', 'mid');
        $id1 = $id['mid'];
        $table = '[Q]expert_record';
        $fields = '*';
        $where = "is_check =1 and mid=" . $id1 . "";
        $order = 'id';
        $this->getlist($table, $fields, $where, $order);
    }

    /**
     * 数据源请求后先处理函数
     **/
    public function expertcheckafter($table, $rows)
    {
        foreach ($rows as $k => $rs) {
            $mid = $rs['mid'];
            //获取账号,注册时间
            $r = m("admin")->getone('id=' . $mid . '');
            $rows[$k]['user'] = $r['user'];
            if ($rs['opt_status'] == 0) {
                $rows[$k]['opt_status'] = '拒绝';
            } else {
                $rows[$k]['opt_status'] = '通过';
            }
        }
        return $rows;
    }

    /**
     * 专家信息
     **/
    public function expertinfoAjax()
    {
        $name = trim($this->post('name'));
        $research_direction = trim($this->post('research_direction'));
        $position = trim($this->post('position'));
        $company = trim($this->post('company'));
        $where = '';
        //查询
        if ($name) {
            $where .= " and xinhu_expert_info.name LIKE '%$name%'";
        }
        if ($research_direction) {
            $where .= " and xinhu_expert_info.research_direction like '%$research_direction%'";
        }
        if ($position) {
            $where .= " and xinhu_expert_info.position like '%$position%'";
        }
        if ($company) {
            $where .= " and xinhu_expert_info.company like '%$company%'";
        }
        $table = '`[Q]expert_info`';
        $fields = '*';
        $new_where = '1=1 and is_expert<>0' . $where;
        $order = '';
        $this->getlist($table, $fields, $new_where, $order);
    }

    public function expertinfoafter($table, $rows)
    {
        foreach ($rows as $k => $rs) {
            $mid = $rs['mid'];
            //获取账号,注册时间
            $r = m("admin")->getone('id=' . $mid . '');
            $rows[$k]['add_time'] = $r['adddt'];
            $rows[$k]['caoz'] = '';
//            $rows[$k]['caoz'].= '<a onclick="inforresultscheck(this,'.$rs['id'].')">查看</a>';
            $rows[$k]['caoz'] .= '<a onclick="expertinfocheck(' . $rs['id'] . ')">查看</a>';
            $rows[$k]['caoz'] .= '<span style="padding:5px;">|</span>';
            $rows[$k]['caoz'] .= '<a onclick="expertinfoedit(' . $rs['id'] . ')">编辑</a>';
        }
        return $rows;
    }

    /**
     * 审核操作保存操作
     **/
    public function savecaozAjax()
    {
        $id = $this->adminid;//当前审核人id
        $results_id = $_POST['results_id'];
        $status1 = $_POST['status'];
        $text1 = $_POST['text'];
        $datetime = date('Y-m-d H:i:s');
        $nation = $_POST['nation'];
        $birth_place = $_POST['birth_place'];
        $birth_date = $_POST['birth_date'];
        $position2 = $_POST['position2'];
        $politic_face = $_POST['politic_face'];
        $graduate_school = $_POST['graduate_school'];
        $academic_degree = $_POST['academic_degree'];
        $address = $_POST['address'];
        $part_time_jobs = $_POST['part_time_jobs'];
        $curriculum_vitae = $_POST['curriculum_vitae'];
        $achievements = $_POST['achievements'];
        $project_review = $_POST['project_review'];
        $where = "id=" . $results_id . "";
        $rb = m('expert_record')->getone($where);


        //审核成功后提交专家信息
        if ($status1 == '通过') {
            $status1 = 1;
            $rows = m('expert_record')->update("`opt_status`='$status1',`is_check`='1',`audit_opinion`='$text1',`optid`='$id',`opt_time`='$datetime',`nation`='$nation',`birth_place`='$birth_place',`birth_date`='$birth_date',`position2`='$position2',`politic_face`='$politic_face',`graduate_school`='$graduate_school',`academic_degree`='$academic_degree',`address`='$address',`part_time_jobs`='$part_time_jobs',`curriculum_vitae`='$curriculum_vitae',`achievements`='$achievements',`project_review`='$project_review'", $where);
            $arr = Array(
                'name' => $rb['name'],
                'sex' => $rb['sex'],
                'mobile' => $rb['mobile'],
                'email' => $rb['email'],
                'company' => $rb['company'],
                'position' => $rb['position'],
                'research_direction' => $rb['research_direction'],
                'graduate_project' => $rb['graduate_project'],
                'nation' => $rb['nation'],
                'birth_date' => $rb['birth_date'],
                'birth_place' => $rb['birth_place'],
                'position2' => $rb['position2'],
                'politic_face' => $rb['politic_face'],
                'graduate_school' => $rb['graduate_school'],
                'academic_degree' => $rb['academic_degree'],
                'address' => $rb['address'],
                'part_time_jobs' => $rb['part_time_jobs'],
                'curriculum_vitae' => $rb['curriculum_vitae'],
                'achievements' => $rb['achievements'],
                'project_review' => $rb['project_review'],
                'is_expert'=>1
            );
            $rb = m('expert_info')->update($arr, "mid = $this->adminid");
            //通过后删除该用户的所有草稿
            m('expert_info')->delete('mid=' . $this->adminid . ' and is_draft=1');
        } else {
            $status1 = 0;
            $rows = m('expert_record')->update("`opt_status`='$status1',`is_check`='1',`audit_opinion`='$text1',`optid`='$id',`opt_time`='$datetime',`nation`='$nation',`birth_place`='$birth_place',`birth_date`='$birth_date',`position2`='$position2',`politic_face`='$politic_face',`graduate_school`='$graduate_school',`academic_degree`='$academic_degree',`address`='$address',`part_time_jobs`='$part_time_jobs',`curriculum_vitae`='$curriculum_vitae',`achievements`='$achievements',`project_review`='$project_review'", $where);
        }
        $this->returnjson($rows);
    }

    /**
     * 查看个人专家信息
     */
    public function get_expert_resultsAjax()
    {
        $expert_id = $_POST['expert_id'];
        $rows = m("expert_info")->getone("id=" . $expert_id . "");
        $mid = $rows['mid'];
        $fine_num = $this->db->getall("SELECT COUNT(*) FROM xinhu_penalty_record WHERE uid =$mid");
        $fine_num_str = implode(array_column($fine_num, 'COUNT(*)'));
        $rows['fine_num'] = $fine_num_str;
        $this->returnjson($rows);
    }

    /**
     * 查看审核结果
     */
    public function expert_checksAjax()
    {
        $expert_id = $_POST['expert_id'];
        $mid = implode(m('expert_info')->getone('id=' . $expert_id . '', 'mid'));
        $table = '[Q]expert_record ';
        $fields = '*';
        $where = "is_check =1 and mid=$mid";
        $order = 'id';
        $this->getlist($table, $fields, $where, $order);
    }

    //获取当前用户专家信息审核记录
    public function personal_expertRecordAjax()
    {
        $table = '[Q]expert_record ';
        $fields = '*';
        $where = "is_check =1 and mid=$this->adminid";
        $order = 'opt_time DESC';
        $this->getlist($table, $fields, $where, $order);
    }


    /**
     * 网评记录
     */
    public function get_online_recordAjax()
    {
        $pici_name = trim($this->post('pici_name'));
        $pici_start_time = trim($this->post('pici_start_time'));
        $where = '';
        //查询
        if ($pici_name) {
            $where .= " and xinhu_m_batch.pici_name LIKE '%$pici_name%'";
        }
        if ($pici_start_time) {
            $where .= " and xinhu_m_batch.pici_start_time like '%$pici_start_time%'";
        }

        $expert_id = $_POST['expert_id'];
        $mid = implode(m('expert_info')->getone('id=' . $expert_id . '', 'mid'));
//        $rows = m('m_pua_relation')->getall('uid='.$mid.'','*','id');
//        $pici_id = array_column($rows,'pici_id');
//        $pici_id_str = implode(',', $pici_id);
        $table = '[Q]m_batch as a left join [Q]m_pua_relation as b on a.id = b.pici_id left join [Q]expert_info as c on b.uid = c.mid';
        $fields = 'a.id,a.pici_name,a.pici_start_time,a.com_status,a.project_ids,c.mid as user_id';
//        $new_where = "id in ($pici_id_str)".$where;
        $new_where = "c.mid =$mid" . $where;
        $order = 'id';
        $this->getlist($table, $fields, $new_where, $order);
    }

    public function get_online_recordafter($table, $rows)
    {
        foreach ($rows as $k => $rs) {
            if ($rs['com_status'] == 0) {
                $rows[$k]['com_status'] = '草稿';
            }
            if ($rs['com_status'] == 1) {
                $rows[$k]['com_status'] = '已提交';
            } else {
                $rows[$k]['com_status'] = '已完成';
            }
            $rows[$k]['caoz'] = '';
            $rows[$k]['caoz'] .= '<a onclick="online_recordcheck(' . $rs['id'] . ',' . $rs['user_id'] . ')">查看</a>';

            $project_ids = unserialize($rs['project_ids']);
            $rows[$k]['totalCount'] = count($project_ids);
        }
        return $rows;
    }

    /**
     * 批次项目信息
     */
    public function project_infoAjax()
    {
        $pici_num = trim($this->post('pici_num'));
        $course_name = trim($this->post('course_name'));
        $modename = trim($this->post('modename'));
        $where = '';
        //查询
        if ($pici_num) {
            $where .= " and c.pici_num LIKE '%$pici_num%'";
        }
        if ($course_name) {
            $where .= " and e.course_name like '%$course_name%'";
        }
        if ($modename) {
            $where .= " and b.modename like '%$modename%'";
        }


        $pici_id = $_POST['pici_id'];
        $user_id = $_POST['user_id'];
        $table = '[Q]m_pxmdf as a left join [Q]flow_bill as b on a.xid = b.id 
                  left join [Q]project_coursetask as e on b.mid=e.id
                  left join [Q]m_batch as c on a.pici_id = c.id 
                  left join [Q]expert_info as d on a.uid = d.mid';
        $fields = 'a.pici_id,b.id,b.mid,b.modename,c.pici_name,c.pici_num,c.mtype,a.user_zongfen,d.name as u_name,e.course_name,b.table,a.uid';
        $new_where = "a.pici_id=$pici_id and a.uid=$user_id" . $where;
        $order = 'a.id';
        $this->getlist($table, $fields, $new_where, $order);
    }

    public function project_infoafter($table, $rows)
    {
        foreach ($rows as $k => $rs) {
            if ($rs['mtype'] == "project_start") {
                $rows[$k]['mtype'] = '立项结审';
            } else {
                $rows[$k]['mtype'] = '结项结审';
            }
            $rows[$k]['caoz'] = '';
            $rows[$k]['caoz'] .= '<a onclick="look(\'' . $rs['pici_id'] . '\',' . '\'' . $rs['table'] . '\'' . ',\'' . $rs['mid'] . '\',\'' . $rs['mtype'] . '\',\'' . $rs['uid'] . '\')">查看</a>';
        }
        return $rows;
    }

    /**
     * 扣罚记录
     */
    public function get_fine_recordAjax()
    {
        $penalty_time = trim($this->post('penalty_time'));
        $where = '';
        //查询
        if ($penalty_time) {
            $where .= " and xinhu_penalty_record.penalty_time LIKE '%$penalty_time%'";
        }

        $expert_id = $_POST['expert_id'];
        $mid = implode(m('expert_info')->getone('id=' . $expert_id . '', 'mid'));
        $table = '[Q]penalty_record as a left join [Q]m_batch as b on a.pici_id = b.id 
                  left join [Q]flow_bill as c on a.xid = c.id
                  left join [Q]project_coursetask as d on c.mid = d.id';
        $fields = 'a.id,a.penalty_reason,a.penalty_time,a.status,b.pici_name,d.course_name';
        $new_where = "a.uid =$mid and a.status=1" . $where;
        $order = 'a.id';
        $this->getlist($table, $fields, $new_where, $order);
    }

    public function get_fine_recordafter($table, $rows)
    {
        foreach ($rows as $k => $rs) {
            $rows[$k]['caoz'] = '';
            $rows[$k]['caoz'] .= '<a onclick="fine_recordclear(' . $rs['id'] . ')">清除</a>';
        }
        return $rows;
    }

    /**
     * 扣罚记录清除
     */
    public function fine_recordclearAjax()
    {
        $fine_id = $_POST['find_id'];
        $_SESSION['xinhu_find_id'] = $fine_id;
        foreach ($fine_id as $v) {
            $arr['status'] = "0";
            $bool = m("penalty_record")->update($arr, "id = $v");
        }
        $this->returnjson($bool);
    }

    /**
     * 扣罚记录重置清除
     */
    public function fine_reecordresetAjax()
    {
        if (!isset($_SESSION['xinhu_find_id'])) {

        } else {
            foreach ($_SESSION['xinhu_find_id'] as $v) {
                $arr['status'] = "1";
                $bool = m("penalty_record")->update($arr, "id=" . $v . "");
            }
            unset($_SESSION['xinhu_find_id']);
            $this->returnjson($bool);
        }
    }

    /**
     * 发表论文记录
     */
    public function get_paper_recordAjax()
    {
        $year = trim($this->post('year'));
        $title = trim($this->post('title'));
        $serial_title = trim($this->post('serial_title'));
        $where = '';
        //查询
        if ($year) {
            $where .= " and xinhu_thesis_query.year LIKE '%$year%'";
        }
        if ($title) {
            $where .= " and xinhu_thesis_query.title like '%$title%'";
        }
        if ($serial_title) {
            $where .= " and xinhu_thesis_query.serial_title like '%$serial_title%'";
        }
        $expert_id = $_POST['expert_id'];
        $mid = implode(m('expert_info')->getone('id=' . $expert_id . '', 'mid'));
        $table = '[Q]thesis_query ';
        $fields = '*';
        $new_where = "uid=$mid" . $where;
        $order = 'id';
        $this->getlist($table, $fields, $new_where, $order);
    }

    /**
     * 纵横项目记录
     */
    public function get_cross_recordAjax()
    {
        $type = trim($this->post('type'));
        $all_year = trim($this->post('all_year'));
        $project_name = trim($this->post('project_name'));
        $where = '';
        //查询
        if ($type) {
            $where .= " and xinhu_item_query.type LIKE '%$type%'";
        }
        if ($all_year) {
            $where .= " and xinhu_item_query.all_year like '%$all_year%'";
        }
        if ($project_name) {
            $where .= " and xinhu_item_query.project_name like '%$project_name%'";
        }


        $expert_id = $_POST['expert_id'];
        $mid = implode(m('expert_info')->getone('id=' . $expert_id . '', 'mid'));
        $table = '[Q]item_query ';
        $fields = '*';
        $new_where = "uid=$mid" . $where;
        $order = 'id';
        $this->getlist($table, $fields, $new_where, $order);
    }

    /**
     * 获奖记录
     */
    public function get_prize_recordAjax()
    {
        $award_time = trim($this->post('award_time'));
        $winning_unit = trim($this->post('winning_unit'));
        $prize = trim($this->post('prize'));
        $where = '';
        //查询
        if ($award_time) {
            $where .= " and xinhu_award_query.award_time LIKE '%$award_time%'";
        }
        if ($winning_unit) {
            $where .= " and xinhu_award_query.winning_unit like '%$winning_unit%'";
        }
        if ($prize) {
            $where .= " and xinhu_award_query.prize like '%$prize%'";
        }


        $expert_id = $_POST['expert_id'];
        $mid = implode(m('expert_info')->getone('id=' . $expert_id . '', 'mid'));
        $table = '[Q]award_query ';
        $fields = '*';
        $new_where = "uid=$mid" . $where;
        $order = 'id';
        $this->getlist($table, $fields, $new_where, $order);
    }

    /**
     * 编辑专家信息
     */
    public function update_expert_infoAjax()
    {
        $expert_id = $this->post('expert_id');
        $expert_name = $this->post('expert_name');
        $mobile = $this->post('mobile');
        $email = $this->post('email');
        $expert_position = $this->post('expert_position');
        $graduate_project = $this->post('graduate_project');
        $nation = $this->post('nation');
        $birth_date = $this->post('birth_date');
        $birth_place = $this->post('birth_place');
        $position2 = $this->post('position2');
        $politic_face = $this->post('politic_face');
        $graduate_school = $this->post('graduate_school');
        $academic_degree = $this->post('academic_degree');
        $address = $this->post('address');
        $part_time_jobs = $this->post('part_time_jobs');
        $curriculum_vitae = $this->post('curriculum_vitae');
        $achievements = $this->post('achievements');
        $project_review = $this->post('project_review');
        $arr['name'] = $expert_name;
        $arr['mobile'] = $mobile;
        $arr['email'] = $email;
        $arr['position'] = $expert_position;
        $arr['graduate_project'] = $graduate_project;
        $arr['nation'] = $nation;
        $arr['birth_date'] = $birth_date;
        $arr['birth_place'] = $birth_place;
        $arr['position2'] = $position2;
        $arr['politic_face'] = $politic_face;
        $arr['academic_degree'] = $academic_degree;
        $arr['graduate_school'] = $graduate_school;
        $arr['address'] = $address;
        $arr['part_time_jobs'] = $part_time_jobs;
        $arr['curriculum_vitae'] = $curriculum_vitae;
        $arr['achievements'] = $achievements;
        $arr['project_review'] = $project_review;
        $rows = m('expert_info')->update($arr, "id=" . $expert_id . "");
        $this->returnjson($rows);
    }
}
