<?php

class fworkClassAction extends Action
{

    public function getmodearrAjax()
    {
        $rows = m('mode')->getmoderows($this->adminid, 'and islu=1');
        $row = array();
        $viewobj = m('view');
        foreach ($rows as $k => $rs) {
            $lx = $rs['type'];
            if (!$viewobj->isadd($rs['id'], $this->adminid)) continue;
            if (!isset($row[$lx])) $row[$lx] = array();
            $row[$lx][] = $rs;
        }
        $this->returnjson(array('rows' => $row));
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


    public function flowbillbefore($table)
    {
        $lx = $this->post('atype');
        $dt = $this->post('dt1');
        $key = $this->post('key');
        $zt = $this->post('zt');
        $modeid = (int)$this->post('modeid', '0');
        $uid = $this->adminid;
        $where = 'and a.uid=' . $uid . '';
        //待办
        if ($lx == 'daib') {
            $where = 'and a.`status` not in(1,2) and ' . $this->rock->dbinstr('a.nowcheckid', $uid);
        }

        if ($lx == 'xia') {
            $where = 'and ' . $this->rock->dbinstr('b.superid', $uid);
        }

        if ($lx == 'jmy') {
            $where = 'and ' . $this->rock->dbinstr('a.allcheckid', $uid);
        }

        if ($lx == 'mywtg') {
            $where .= " and a.status=2";
        }

        if ($zt != '') $where .= " and a.status='$zt'";
        if ($dt != '') $where .= " and a.applydt='$dt'";
        if ($modeid > 0) $where .= ' and a.modeid=' . $modeid . '';
        if (!isempt($key)) $where .= " and (b.`name` like '%$key%' or b.`deptname` like '%$key%' or a.sericnum like '$key%')";


        return array(
            'table' => '`[Q]flow_bill` a left join `[Q]admin` b on a.uid=b.id',
            'where' => " and a.isdel=0 $where",
            'fields' => 'a.*,b.name,b.deptname',
            'order' => 'a.optdt desc'
        );
    }

    public function flowbillafter($table, $rows)
    {
        $rows = m('flowbill')->getbilldata($rows);
        return array(
            'rows' => $rows,
            'flowarr' => m('mode')->getmodemyarr($this->adminid)
        );
    }


    public function meetqingkbefore($table)
    {
        $pid = $this->option->getval('hyname', '-1', 2);
        return array(
            'where' => "and `pid`='$pid'",
            'order' => 'sort',
            'field' => 'id,name',
        );
    }

    public function meetqingkafter($table, $rows)
    {
        $dtobj = c('date');
        $startdt = $this->post('startdt', $this->date);
        $enddt = $this->post('enddt');
        if ($enddt == '') $enddt = $dtobj->adddate($startdt, 'd', 7);
        $jg = $dtobj->datediff('d', $startdt, $enddt);
        if ($jg > 30) $jg = 30;
        $flow = m('flow:meet');
        $data = m('meet')->getall("`status`=1 and `type`=0 and `startdt`<='$enddt 23:59:59' and `enddt`>='$startdt' order by `startdt` asc", 'hyname,title,startdt,enddt,state,joinname,optname');
        $datss = array();
        foreach ($data as $k => $rs) {
            $rs = $flow->flowrsreplace($rs);
            $key = substr($rs['startdt'], 0, 10) . $rs['hyname'];
            if (!isset($datss[$key])) $datss[$key] = array();
            $str = '[' . substr($rs['startdt'], 11, 5) . '→' . substr($rs['enddt'], 11, 5) . ']' . $rs['title'] . '(' . $rs['joinname'] . ') ' . $rs['state'] . '';
            $datss[$key][] = $str;
        }

        $columns = $rows;
        $barr = array();
        $dt = $startdt;
        for ($i = 0; $i <= $jg; $i++) {
            if ($i > 0) $dt = $dtobj->adddate($dt, 'd', 1);
            $w = $dtobj->cnweek($dt);
            $status = 1;
            if ($w == '六' || $w == '日') $status = 0;
            $sbarr = array(
                'dt' => '星期' . $w . '<br>' . $dt . '',
                'status' => $status
            );
            foreach ($rows as $k => $rs) {
                $key = $dt . $rs['name'];
                $str = '';
                if (isset($datss[$key])) {
                    foreach ($datss[$key] as $k1 => $strs) {
                        $str .= '' . ($k1 + 1) . '.' . $strs . '<br>';
                    }
                }
                $sbarr['meet_' . $rs['id'] . ''] = $str;
            }
            $barr[] = $sbarr;
        }
        $arr['columns'] = $columns;
        $arr['startdt'] = $startdt;
        $arr['enddt'] = $enddt;
        $arr['rows'] = $barr;
        $arr['totalCount'] = $jg + 1;

        return $arr;
    }

    /**
     * 变更申请列表
     */
    public function change_requestAjax()
    {
        $uid = $this->adminid;
        $sericnum = trim($this->post('sericnum'));
        $project_name = trim($this->post('project_name'));
        $change_type = trim($this->post('change_type'));
        $where = '';
        //查询
        if ($sericnum) {
            $where .= " and a.sericnum like '%$sericnum%'";
        }
        if ($project_name) {
            $where .= " and c.course_name like '%$project_name%'";
        }
        if ($change_type) {
            $where .= " and a.change_type like '%$change_type%'";
        }
        $table = '[Q]flow_bill as a left join [Q]project_coursetask as c on a.mid=c.id left join [Q]admin as b on a.optid=b.id';
        $fields = 'a.id,a.sericnum,a.change_type,a.applydt,a.change_course,a.character_status,b.school_name,c.course_name';
        $new_where = "a.uid = $uid and a.is_change = 1" . $where;
        $order = 'a.id';
        $this->getlist($table, $fields, $new_where, $order);
    }

    public function change_requestafter($table, $rows)
    {
        foreach ($rows as $k => $rs) {
            $changetype = array(
                '0'=>'变更项目负责人',
                '1'=>'变更或增加课题组成员',
                '2'=>'变更项目管理单位',
                '3'=>'改变成果形式',
                '4'=>'改变项目名称',
                '5'=>'研究内容有重大调整',
                '6'=>'延期',
                '7'=>'撤项',
                '8'=>'其他',
            );
            $rows[$k]['change_type'] = $changetype[$rs['change_type']];
            if ($rs['school_name']) {
                if ($rs['change_course'] == 1) {
                    $rows[$k]['change_course'] = '待高校审核';
                }
                if ($rs['change_course'] == 2) {
                    $rows[$k]['change_course'] = '待社科管理员审核';
                }
                if ($rs['change_course'] == 0 && $rs['character_status'] == 1) {
                    $rows[$k]['change_course'] = '已完成';
                }
            }else{
                if ($rs['change_course'] == 2) {
                    $rows[$k]['change_course'] = '待社科管理员审核';
                }
                if ($rs['change_course'] == 0 && $rs['character_status'] == 1) {
                    $rows[$k]['change_course'] = '已完成';
                }
            }
            $rows[$k]['caoz'] = '';
            $rows[$k]['caoz'] .= '<a style="text-decoration: none" onclick="look(' . $rs['id'] . ')">查看</a>';
        }
        return $rows;
    }

    /**
     * 查看变更课题记录
     * 1.基础信息
     */
    public function base_infoAjax()
    {
        $request_id = $_POST['request_id'];
        $table = '[Q]flow_bill as a left join [Q]project_coursetask as c on a.mid=c.id ';
        $fields = 'a.change_type,a.change_remark,c.course_name,a.mid,a.table';
        $where = "a.id = $request_id ";
        $this->getlist($table, $fields, $where, '');
    }

    /**
     *查看变更课题记录
     * 2.附件资料
     */
    public function attached_infoAjax()
    {
        $request_id = $_POST['request_id'];
        $table = '[Q]flow_bill as a left join [Q]file as b on a.id=b.flow_id';
        $fields = 'b.id as file_id,b.filename,b.fileext,b.filepath,b.upload_filetype';
        $where = "a.id = $request_id ";
        $this->getlist($table, $fields, $where, '');
    }

    public function attached_infoafter($table, $rows)
    {
        foreach ($rows as $k => $rs) {
            $rows[$k]['upload_status'] = '<p style="color: #00aa00;font-weight: bold">已上传</p>';
            $rows[$k]['caoz'] = '';
            $rows[$k]['caoz'] .= '<a onclick="expertinfocheck(' . $rs['file_id'] . ')" style="text-decoration: none">查看</a>';
            $rows[$k]['caoz'] .= '<span style="padding:5px;">|</span>';
            $rows[$k]['caoz'] .= '<a href="javascript:;" onclick="filedownload(' . $rs['file_id'] . ',\'' . $rs['fileext'] . '\',\'' . $rs['filepath'] . '\')" style="text-decoration: none">下载</a>';
        }
        return $rows;
    }

    /**
     *查看变更课题记录
     * 3.审核记录
     */
    public function addit_recordsAjax()
    {
        $request_id = $_POST['request_id'];
        $table = '[Q]flow_bill_record1 as a left join [Q]admin as b on a.uid=b.id';
        $fields = 'b.name,a.audit_result,a.audit_opinion,a.audit_time';
        $where = "a.mid = $request_id ";
        $this->getlist($table, $fields, $where, '');
    }

    public function addit_recordsafter($table, $rows)
    {
        foreach ($rows as $k => $rs) {
            if ($rs['audit_result'] == 0) {
                $rows[$k]['audit_result'] = '未通过';
            }
            if ($rs['audit_result'] == 1) {
                $rows[$k]['audit_result'] = '通过';
            }
        }
        return $rows;
    }

    /**
     * 发起变更课题记录
     */
    public function request_addAjax()
    {
        $flow_id = $_POST['flow_id'];
        $change_type = $_POST['change_type'];
        $change_remark = $_POST['change_remark'];
        $files_id = implode(",", $_POST['files_id']);
        $upload_status = $_POST['upload_status'];

        if ($files_id != '' || $upload_status != '') {
            m('flow_bill')->update("change_type=$change_type,change_remark='$change_remark',is_change=1", "id=$flow_id");
            $upda = m("xfile")->update("upload_status=$upload_status,flow_id=$flow_id", "id in ($files_id)");
            $this->returnjson($upda);
        } else {
            return false;
        }
    }

    /**
     * 获取选择项目的下拉框
     */
    public function getsreachAjax()
    {
        $sql = 'SELECT b.id,a.course_name,b.nowcourseid,b.status,b.uid FROM xinhu_project_coursetask AS a';
        $sql .= ' LEFT JOIN xinhu_flow_bill AS b ON a.id = b.mid ';
        $sql .= " WHERE b.nowcourseid != 103 AND b.nowcourseid != 91 AND b.status = 0 and b.`table` = 'project_coursetask' and b.uid = $this->adminid";
        $rows = $this->db->getall($sql);
        $this->returnjson($rows);
    }
}
