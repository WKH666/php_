<?php

/**
 *    此文件是流程模块【project_coursetask.社科课题申报】对应接口文件。
 *    可在页面上创建更多方法如：public funciton testactAjax()，用js.getajaxurl('testact','mode_project_coursetask|input','flow')调用到对应方法
 */
class mode_project_coursetaskClassAction extends inputAction
{

    //过滤html代码
    private function xxsstolt($uaarr)
    {
        foreach ($uaarr as $k => $v) {
            $vss = strtolower($v);
            if (contain($vss, '<script')) {
                $uaarr[$k] = str_replace(array('<', '>'), array('&lt;', '&gt;'), $v);
            }
        }
        return $uaarr;
    }

    //多行子表的保存
    private function savesubtable($tables, $mid, $xu, $addbo)
    {
        $dbs = m($tables);
        $data = $this->getsubtabledata($xu);
        $len = count($data);
        $idss = '0';
        $whes = '';

        $allfields = $this->db->getallfields('[Q]' . $tables . '');
        $oarray = array();
        if (in_array('optdt', $allfields))
            $oarray['optdt'] = $this->now;
        if (in_array('optid', $allfields))
            $oarray['optid'] = $this->adminid;
        if (in_array('optname', $allfields))
            $oarray['optname'] = $this->adminname;
        if (in_array('uid', $allfields))
            $oarray['uid'] = $this->post('uid', $this->adminid);
        if (in_array('applydt', $allfields) && $addbo)
            $oarray['applydt'] = $this->post('applydt', $this->date);
        if (in_array('status', $allfields))
            $oarray['status'] = 0;
        if (in_array('sslx', $allfields)) {
            $oarray['sslx'] = $xu;
            $whes = ' and `sslx`=' . $xu . '';
        }
        if ($data)
            foreach ($data as $k => $uaarr) {
                $sid = $uaarr['id'];
                $where = "`id`='$sid'";
                $uaarr['mid'] = $mid;
                if ($sid == 0)
                    $where = '';
                foreach ($oarray as $k1 => $v1)
                    $uaarr[$k1] = $v1;

                $dbs->record($uaarr, $where);
                if ($sid == 0)
                    $sid = $this->db->insert_id();
                $idss .= ',' . $sid . '';
            }
        $delwhere = "`mid`='$mid'" . $whes . " and `id` not in($idss)";
        $dbs->delete($delwhere);
    }

    /**
     *    重写函数：保存前处理，主要用于判断是否可以保存
     *    $table String 对应表名
     *    $arr Array 表单参数
     *    $id Int 对应表上记录Id 0添加时，大于0修改时
     *    $addbo Boolean 是否添加时
     *    return array('msg'=>'错误提示内容','rows'=> array()) 可返回空字符串，或者数组 rows 是可同时保存到数据库上数组
     */
    protected function savebefore($table, $arr, $id, $addbo)
    {

    }

    /**
     *    重写函数：保存后处理，主要保存其他表数据
     *    $table String 对应表名
     *    $arr Array 表单参数
     *    $id Int 对应表上记录Id
     *    $addbo Boolean 是否添加时
     */
    protected function saveafter($table, $arr, $id, $addbo)
    {

    }

    /**
     *  重写保存函数
     */
    public function saveAjax()
    {
        $id = (int)$this->request('id');
        $modenum = $this->request('sysmodenum');
        $uid = $this->adminid;
        $admin_u_info = m('admin')->getone('id=' . $uid, 'is_admin,school_name');
        $this->flow = m('flow')->initflow($modenum);
        $this->moders = $this->flow->moders;
        $modeid = $this->moders['id'];
        $isflow = $this->moders['isflow'];
        $flownum = $this->moders['num'];
        $table = $this->moders['table'];
        $checkobj = c('check');
        if ($this->isempt($table))
            $this->backmsg('模块未设置表名');
        $fieldsarr = $this->flow->fieldsarr;
        if (!$fieldsarr)
            $this->backmsg('没有录入元素');
        $db = m($table);
        $addbo = false;
        $where = "`id`='$id'";
        $oldrs = false;
        $this->mdb = $db;
        $isturn = $this->rock->post('isturn');
        if ($isturn == "0") {
            $subna = '保存';
        } else {
            $subna = '提交';
        }

        if ($id == 0) {
            $where = '';
            $addbo = true;
        } else {
            $oldrs = $db->getone($id);
            if (!$oldrs)
                $this->backmsg('记录不存在');
            if ($isflow == 1) {
                $bos = false;
                if ($admin_u_info['is_admin'] != 1) {
                    if ($oldrs['uid'] == $uid || $oldrs['optid'] == $uid)
                        $bos = true;
                    if ($oldrs['status'] == 1)
                        $bos = false;
                    if (!$bos)
                        $this->backmsg('不允许编辑,可能已审核通过/不是你的单据');
                } else if ($admin_u_info['is_admin'] == 1) {
                    $bos = true;
                }
            }
            if ($isturn == "0") {
                $subna = '保存';
            } else {
                $subna = '编辑';
            }

        }
        if ($oldrs)
            $this->rs = $oldrs;
        $uaarr = $farrs = array();
        foreach ($fieldsarr as $k => $rs) {
            $fid = $rs['fields'];
            if (substr($fid, 0, 5) == 'temp_')
                continue;
            $val = $this->post($fid);
            if ($rs['isbt'] == 1 && isempt($val))
                $this->backmsg('' . $rs['name'] . '不能为空');
            if (!isempt($val) && $rs['fieldstype'] == 'email') {
                if (!$checkobj->isemail($val))
                    $this->backmsg('' . $rs['name'] . '格式不对');
            }
            $uaarr[$fid] = $val;
            $farrs[$fid] = array('name' => $rs['name']);
            //判断是否与高校关联
            if ($admin_u_info['school_name'] != '') {
                $uaarr['have_school'] = 1;
            } else {
                $uaarr['have_school'] = 0;
            }
        }

        //人员选择保存的
        foreach ($fieldsarr as $k => $rs) {
            if (substr($rs['fieldstype'], 0, 6) == 'change') {
                if (!$this->isempt($rs['data'])) {
                    $fid = $rs['data'];
                    if (isset($uaarr[$fid]))
                        continue;
                    $val = $this->post($fid);
                    if ($rs['isbt'] == 1 && $this->isempt($val))
                        $this->backmsg('' . $rs['name'] . 'id不能为空');
                    $uaarr[$fid] = $val;
                    $farrs[$fid] = array('name' => $rs['name'] . 'id');
                }
            }
            if ($rs['fieldstype'] == 'num') {
                $fid = $rs['fields'];
                if ($this->flow->rows("`$fid`='{$uaarr[$fid]}' and `id`<>$id") > 0)
                    $uaarr[$fid] = $this->flow->createbianhao($rs['data'], $fid);
            }
        }

        //默认字段保存
        $allfields = $this->db->getallfields('[Q]' . $table . '');
        if (in_array('optdt', $allfields))
            $uaarr['optdt'] = $this->now;
        if (in_array('optid', $allfields))
            $uaarr['optid'] = $this->adminid;
        if (in_array('optname', $allfields))
            $uaarr['optname'] = $this->adminname;
        if (in_array('uid', $allfields))
            $uaarr['uid'] = $this->post('uid', $this->adminid);
        if (in_array('applydt', $allfields) && $id == 0)
            $uaarr['applydt'] = $this->post('applydt', $this->date);
        if ($addbo) {
            if (in_array('createdt', $allfields))
                $uaarr['createdt'] = $this->now;
            if (in_array('adddt', $allfields))
                $uaarr['adddt'] = $this->now;
            if (in_array('createid', $allfields))
                $uaarr['createid'] = $this->adminid;
            if (in_array('createname', $allfields))
                $uaarr['createname'] = $this->adminname;
        }
        if ($isflow == 1) {
            $uaarr['status'] = '0';//错误
        } else {
            if (in_array('status', $allfields))
                $uaarr['status'] = (int)$this->post('status', '1');
            if (in_array('isturn', $allfields))
                $uaarr['isturn'] = (int)$this->post('isturn', '1');
        }

        //保存条件的判断
        foreach ($fieldsarr as $k => $rs) {
            $ss = '';
            if (isset($uaarr[$rs['fields']]))
                $ss = $this->flow->savedatastr($uaarr[$rs['fields']], $rs, $uaarr);
            if ($ss != '')
                $this->backmsg($ss);
        }

        //判断保存前的
        $ss = '';
        $befa = $this->savebefore($table, $uaarr, $id, $addbo);
        if (is_string($befa)) {
            $ss = $befa;
        } else {
            if (isset($befa['msg']))
                $ss = $befa['msg'];
            if (isset($befa['rows'])) {
                if (is_array($befa['rows']))
                    foreach ($befa['rows'] as $bk => $bv)
                        $uaarr[$bk] = $bv;
            }
        }
        if (!$this->isempt($ss))
            $this->backmsg($ss);
        $uaarr = $this->xxsstolt($uaarr);//过滤特殊文字

        foreach ($uaarr as $kf => $kv) {
            if (!in_array($kf, $allfields)) {
                $this->backmsg('模块主表[' . $this->flow->mtable . ']上字段[' . $kf . ']不存在');
            }
        }

        $bo = $db->record($uaarr, $where);
        if (!$bo)
            $this->backmsg($this->db->error());
        if ($id == 0)
            $id = $this->db->insert_id();
        //文件上传更新mid和mtype字段
        m('file')->addfile($this->post('fileid'), $table, $id);


        //保存多行子表
        $tabless = $this->moders['tables'];

        if (!isempt($tabless)) {
            $tablessa = explode(',', $tabless);
            foreach ($tablessa as $zbx => $tablessas) {
                $this->savesubtable($tablessas, $id, $zbx, $addbo);
            }
        }

        //保存后处理
        $this->saveafter($table, $uaarr, $id, $addbo);

        //保存修改记录
        $editcont = '';
        if ($oldrs) {
            $newrs = $db->getone($id);
            $editcont = m('edit')->recordsave($farrs, $table, $id, $oldrs, $newrs);
        }
        $msg = '';
        $this->flow->editcont = $editcont;
        $this->flow->loaddata($id, false);
        $this->flow->submit($subna);
        $this->backmsg('', $msg, $id);
    }

    /*表单的预期成果形式*/
    public function pre_achievement_func()
    {
        $arr[] = array(
            "value" => '专著',
            "name" => '专著',
        );
        $arr[] = array(
            "value" => '论文',
            "name" => '论文',
        );
        $arr[] = array(
            "value" => '研究报告',
            "name" => '研究报告',
        );
        return $arr;
    }

    /*
     * 表单的成果去向
     * */
    public function achievement_where_func()
    {
        $arr[] = array(
            "value" => '公开出版',
            "name" => '公开出版',
        );
        $arr[] = array(
            "value" => '公开发表',
            "name" => '公开发表',
        );
        $arr[] = array(
            "value" => '提交相关部门应用',
            "name" => '提交相关部门应用',
        );
        $arr[] = array(
            "value" => '递交相关领导批阅',
            "name" => '递交相关领导批阅',
        );
        return $arr;
    }

    /*表单的关键词分类*/
    public function keyword_classification_func()
    {
        $table = '[Q]key_word';
        $fields = '*';
        $where = "del_status=0";
        $order = 'add_time desc';
        $keyword_arr = array();
        // $arr =  $this->getlist($table, $fields, $where, $order);
        $arr = $this->limitRows($table, $fields, $where, $order);
        for ($i = 0; $i < count($arr['rows']); $i++) {
            $keyword_arr[] = array(
                "value" => $arr['rows'][$i]['name'],
                "name" => $arr['rows'][$i]['name'],
            );
        }
        return $keyword_arr;
    }

    /*表单的学科分类*/
    public function subject_classification_func()
    {
        $table = '[Q]subject_sort';
        $fields = 'name,id,pid';
        $where = "del_status=0";
        $order = 'add_time desc';
        $arr = $this->limitRows($table, $fields, $where, $order);
        $rows = $arr['rows'];
        $new_rows = array();
        foreach ($rows as $k => $v) {
            foreach ($rows as $n => $m) {
                if ($rows[$k]['id'] == $rows[$n]['pid']) {
                    $new_rows[] = array(
                        'value' => $rows[$k]['name'] . '-' . $rows[$n]['name'],
                        'name' => $rows[$k]['name'] . '-' . $rows[$n]['name'],
                    );
                }
            }
        }
        return $new_rows;
    }

    /*是否共建课题*/
    public function create_subject_func()
    {
        $html = '<span id="div_together_build_course" class="divinput">';
        $html .= '&nbsp;&nbsp;<label><input name="together_build_course" value="是" type="radio" checked>是</label>';
        $html .= '&nbsp;&nbsp;<label><input name="together_build_course" value="否" type="radio">否</label>';
        $html .= '</span>';
        return $html;
    }

    /*是否同意自筹经费*/
    public function self_finance_func(){
        $html = '<span id="div_self_finance" class="divinput">';
        $html .= '&nbsp;&nbsp;<label><input name="self_finance" value="是" type="radio" checked>是</label>';
        $html .= '&nbsp;&nbsp;<label><input name="self_finance" value="否" type="radio">否</label>';
        $html .= '</span>';
        return $html;
    }

}
