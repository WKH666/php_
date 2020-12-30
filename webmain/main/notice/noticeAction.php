<?php

class noticeClassAction extends Action
{
    /**
     * 前置操作
     * @param $table
     * @return array
     */
    public function informationbefore($table)
    {
        $type = $this->post('type');
        $time_frame = $this->post('launch_time');//发布时间
        $title = $this->post('title');
        $where = '';
        $uid = $this->adminid;

        //获得当前登录者的职业
        $now_user_ranking = $this->getsession('adminranking');

        if ($now_user_ranking == '申报者') {
            /*申报者*/
            $where .= "";
        } else if ($now_user_ranking == '社科管理员') {
            /*社科管理员*/
            $where = "";
        }

        //查询
        if ($type) {
            $where .= " and a.type=" . $type;
        }
        if ($time_frame != "") {//时间范围
            list($start_time, $end_time) = explode(',', $time_frame);
            $where .= " and a.optdt between '" . $start_time . "' and '" . $end_time . "'";
            unset($start_time, $end_time);
        }
        if ($title) {
            $where .= " and a.title like '%$title%'";
        }


        $table = "`[Q]notice` a  ";

        return array(
            'table' => $table,
            'where' => "$where",
            'fields' => 'a.*',
            'order' => 'a.id desc'
        );
    }

    /**
     * 后置操作
     * @param $table
     * @param $rows
     * @return array
     */
    public function informationafter($table, $rows)
    {
        //单据数据
        $rows = m('notice')->format($rows);
        return array(
            'rows' => $rows
        );
    }

    /**
     * 发布通知书
     */
    public function fabuAjax()
    {
        $time = date('Y-m-d');
        $mod = m('notice');
        $todoMod = m('todo');
        $billMod = m('flow_bill');
        $is_draft = $_GET['is_draft'];
        $sendfile_status = $_GET['sendfile_status'];
        $flowfile__status = $_GET['flowfile__status'];
        $notice_id = $_POST['notice_id'];
        $add = array(
            'opt' => $this->getsession('adminid'),
            'optdt' => $time,
        );

        if ($_POST['title']) $add['title'] = $_POST['title'];
        else backmsg('缺少标题');

        if ($_POST['type']) $add['type'] = $mod->type_num[$_POST['type']];
        else backmsg('缺少发布类型');

        if ($_POST['remark']) $add['remark'] = $_POST['remark'];

        $add['is_mail'] = $_POST['is_mail'];
        $re = '';
        //课题立项通知书1,成果编辑要求6,课题结项通知书2
        if ($add['type'] == 1 || $add['type'] == 6 || $add['type'] == 2) {
            /*处理发送人*/
            $dataArrs = array();
            //数据库已存在，但用户没选择新的上传文件
            if (!$sendfile_status) {
                $dataArrs = $this->ktsend_who($add, $notice_id, $add['type']);
                $sericnum = explode(',', trim($add['sericnum'], ','));
                $sericnum = "'" . implode("','", $sericnum) . "'";
                /*获取发送人的uid*/
                $uids = $billMod->getall(" `sericnum` in ($sericnum) ", 'uid');
                $uids = array_column($uids, 'uid');
                $uids_arr = array_unique($uids);//数组
                $uids = implode(",", $uids_arr);//字符串
                $sent_body = isset($add['remark']) ? $add['remark'] : "你有 " . $mod->type_zh[$add['type']] . " 需要查看";
                /*站内信推送*/
                $todoMod->add($uids, $add['title'], $sent_body);
                /*邮件推送*/
                if ($add['is_mail']) {
                    $msg = m('email')->sendmail($add['title'], $sent_body, $uids);
                }
            }
            /*流程附件*/
            if (!$flowfile__status) {
                $this->save_files($add);
            }
            //判断是否是草稿 0草稿1已发布
            if ($is_draft) {
                $add['com_status'] = 1;
            } else {
                $add['com_status'] = 0;
            }

            //保存通知书,判断是否已存在，存在则更新
            if ($notice_id && $sendfile_status == 0) {
                //编辑草稿状态并上传了新的发送人文件
                $re = $mod->update($add, 'id = ' . $notice_id);
                $rl = m('project_approval')->delete('notice_id=' . $notice_id);
                $arr = array();
                foreach ($dataArrs as $v) {
                    if ($add['type'] == 1) {
                        $arr = array(
                            'notice_id' => $notice_id,
                            'type' => $add['type'],
                            'notice_num' => $v[0],
                            'notice_order' => $v[1],
                            'nd_year' => $v[2],
                            'sericnum' => $v[3],
                            'projectstart_num' => $v[4],
                            'keti_type' => $v[5],
                            'project_name' => $v[6],
                            'leader' => $v[7],
                            'position' => $v[8],
                            'company' => $v[9],
                            'achievement_type' => $v[10],
                            'fund' => $v[11],
                            'finish_time' => $v[12],
                            'opt_time' => $v[13],
                            'send_time' => $time,
                        );
                    } elseif ($add['type'] == 6) {
                        $arr = array(
                            'notice_id' => $notice_id,
                            'type' => $add['type'],
                            'sericnum' => $v[0],
                            'keti_type' => '课题申报',
                            'project_name' => $v[1],
                            'send_time' => $time,
                        );
                    }else if ($add['type'] == 2){
                        $arr = array(
                            'notice_id' => $notice_id,
                            'type' => $add['type'],
                            'notice_num' => $v[0],
                            'notice_order' => $v[1],
                            'nd_year' => $v[2],
                            'sericnum' => $v[3],
                            'kt_num'=>$v[4],
                            'projectstart_num' => $v[5],
                            'project_name' => $v[6],
                            'leader' => $v[7],
                            'position' => $v[8],
                            'company' => $v[9],
                            'achievement_type' => $v[10],
                            'appraisal_grade'=>$v[11],
                            'opt_time' => $v[12],
                            'send_time' => $time,
                        );
                    }
                    $ro = m('project_approval')->insert($arr);
                }
            } else if ($notice_id && $sendfile_status == 1) {
                //编辑草稿状态并未上传新的发送人文件
                $re = $mod->update($add, 'id = ' . $notice_id);
            } else if ($sendfile_status == 0 && !($notice_id)) {
                //正式发布状态
                $re = $mod->insert($add);
                if ($re) {
                    foreach ($dataArrs as $v) {
                        $arr = array();
                        if ($add['type'] == 1) {
                            $arr = array(
                                'notice_id' => $re,
                                'type' => $add['type'],
                                'notice_num' => $v[0],
                                'notice_order' => $v[1],
                                'nd_year' => $v[2],
                                'sericnum' => $v[3],
                                'projectstart_num' => $v[4],
                                'keti_type' => $v[5],
                                'project_name' => $v[6],
                                'leader' => $v[7],
                                'position' => $v[8],
                                'company' => $v[9],
                                'achievement_type' => $v[10],
                                'fund' => $v[11],
                                'finish_time' => $v[12],
                                'opt_time' => $v[13],
                                'send_time' => $time,
                            );
                        } elseif ($add['type'] == 6) {
                            $arr = array(
                                'notice_id' => $re,
                                'type' => $add['type'],
                                'sericnum' => $v[0],
                                'keti_type' => '课题申报',
                                'project_name' => $v[1],
                                'send_time' => $time,
                            );
                        }else if ($add['type'] == 2){
                            $arr = array(
                                'notice_id' => $re,
                                'type' => $add['type'],
                                'notice_num' => $v[0],
                                'notice_order' => $v[1],
                                'nd_year' => $v[2],
                                'sericnum' => $v[3],
                                'kt_num'=>$v[4],
                                'projectstart_num' => $v[5],
                                'keti_type'=>$v[6],
                                'project_name' => $v[7],
                                'leader' => $v[8],
                                'position' => $v[9],
                                'company' => $v[10],
                                'achievement_type' => $v[11],
                                'appraisal_grade'=>$v[12],
                                'opt_time' => $v[13],
                                'send_time' => $time,
                            );
                        }
                        $ro = m('project_approval')->insert($arr);
                    }
                }
            }

        }
        else if ($add['type'] == 3 || $add['type'] == 4 || $add['type'] == 5) {
            /*处理发送人*/
            $re_datas = array();
            if (!$sendfile_status) {
                $re_datas = $this->send_who($add);
                /*获取发送人的uid*/
                $sericnum = explode(',', trim($add['sericnum'], ','));
                $sericnum = "'" . implode("','", $sericnum) . "'";
                $uids = $billMod->getall(" `sericnum` in ($sericnum) ", 'uid');
                $uids = array_column($uids, 'uid');
                $uids_arr = array_unique($uids);//数组
                $uids = implode(",", $uids_arr);//字符串
                $sent_body = isset($add['remark']) ? $add['remark'] : "你有 " . $mod->type_zh[$add['type']] . " 需要查看";
                /*站内信推送*/
                $todoMod->add($uids, $add['title'], $sent_body);
                /*邮件推送*/
                if ($add['is_mail']) {
                    $msg = m('email')->sendmail($add['title'], $sent_body, $uids);
                }
            }
            /*流程附件*/
            if (!$flowfile__status) {
                $this->save_files($add);
            }


            //判断是否是草稿
            if ($is_draft) {
                $add['com_status'] = 1;
            } else {
                $add['com_status'] = 0;
            }

            //保存通知书,判断是否已存在，存在则更新
            if ($notice_id && $sendfile_status == 0) {
                $re = $mod->update($add, 'id = ' . $notice_id);
                $rl = m('notice_preprocess')->delete('notice_id=' . $notice_id);
                foreach ($re_datas as $v) {
                    $arr = array();
                    if ($add['type'] == 4) {
                        $arr = array(
                            'notice_id' => $re,
                            'type' => $add['type'],
                            'sericnum' => $v[1],
                            'project_name' => $v[2],
                            'company' => $v[3],
                            'contact_person' => $v[4],
                            'send_time' => $time,
                        );
                        $rm = m('notice_preprocess')->insert($arr);
                    } else {
                        $arr = array(
                            'notice_id' => $re,
                            'type' => $add['type'],
                            'sericnum' => $v[1],
                            'project_name' => $v[2],
                            'company' => $v[3],
                            'leader' => $v[4],
                            'contact_person' => $v[5],
                            'send_time' => $time,
                        );
                        $rm = m('notice_preprocess')->insert($arr);
                    }

                }
            } else if ($notice_id && $sendfile_status == 1) {
                $re = $mod->update($add, 'id = ' . $notice_id);
            } else if ($sendfile_status == 0 && !($notice_id)) {
                $re = $mod->insert($add);
                if ($re) {
                    foreach ($re_datas as $v) {
                        $arr = array();
                        if ($add['type'] == 4) {
                            $arr = array(
                                'notice_id' => $re,
                                'type' => $add['type'],
                                'sericnum' => $v[1],
                                'project_name' => $v[2],
                                'company' => $v[3],
                                'contact_person' => $v[4],
                                'send_time' => $time,
                            );
                            $rm = m('notice_preprocess')->insert($arr);
                        } else {
                            $arr = array(
                                'notice_id' => $re,
                                'type' => $add['type'],
                                'sericnum' => $v[1],
                                'project_name' => $v[2],
                                'company' => $v[3],
                                'leader' => $v[4],
                                'contact_person' => $v[5],
                                'send_time' => $time,
                            );
                            $rm = m('notice_preprocess')->insert($arr);
                        }

                    }
                }
            }
        }
        if ($re) {
            echo json_encode(array(
                'code' => 200,
                'data' => $add,
                'msg' => '处理成功'
            ));
        }

    }

    /**
     * 获取通知书草稿
     * */
    public function notice_draftAjax()
    {
        $notice_id = $this->get('notice_id');
        $re = m('notice')->getone('id = ' . $notice_id);
        if ($re) {
            echo json_encode(array(
                'code' => 200,
                'msg' => '处理成功',
                'data' => $re
            ));
        }
    }

    /**
     * 保存通知书草稿
     * */
    /* public function savedraftAjax(){
         $time = date('Y-m-d H:i:s');
         $mod = m('notice');
         $todoMod = m('todo');
         $billMod = m('flow_bill');

         $add = array(
             'opt' => $this->getsession('adminid'),
             'optdt' => $time,
         );
     }*/
    /**
     * 课题立项处理发送人
     * */
    private function ktsend_who(&$add, $notice_id = 0, $type)
    {

        $add['send_files'] = $_FILES["post_files"]["name"];
        $fileName = $_FILES["post_files"]["tmp_name"];
        if (!file_exists($fileName))
            backmsg('请上传发送人文件');

        $size = $_FILES['post_files']['size'];
        if ($size > 2097152)
            backmsg('发送人文件不可大于2M');

        //xls:application/vnd.ms-excel
        //xlsx:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet
        $file_type = $_FILES['post_files']['type'];
        if (!in_array($file_type, ['application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']))
            backmsg('发送人只能使用xls或xlsx的文件');
        require_once "./include/PHPExcel/IOFactory.php";
        $objPHPExcel = PHPExcel_IOFactory::load($fileName);
        $sheetCount = $objPHPExcel->getSheetCount();
        $sheetSelected = 0;
        $objPHPExcel->setActiveSheetIndex($sheetSelected);
        $rowCount = $objPHPExcel->getActiveSheet()->getHighestRow();//行数
        $columnCount = $objPHPExcel->getActiveSheet()->getHighestColumn();//列数

        //单独拿出项目编号
        $dataArrs = array();
        $dataArr = array();
        $add['sericnum'] = '';

        for ($row = 2; $row <= $rowCount; $row++) {
            $dataArr = array();
            for ($column = 'A'; $column <= $columnCount; $column++) {
                $val = $objPHPExcel->getActiveSheet()->getCell($column . $row)->getValue();
                if ($type==1){
                    if ($column == 'M' || $column == 'N') {
                        if ($val) {
                            $p_val = PHPExcel_Shared_Date::ExcelToPHP($val);
                            $val = date('Y/m/d', $p_val);
                        }
                    }
                }else if ($type==2){
                    if ( $column == 'N') {
                        if ($val) {
                            $p_val = PHPExcel_Shared_Date::ExcelToPHP($val);
                            $val = date('Y/m/d', $p_val);
                        }
                    }
                }
                if ($val) $dataArr[] = trim($val);
            }
            if ($dataArr && $type == 1) {
                $dataArrs[] = $dataArr;
                $add['sericnum'] .= $dataArr[3] . ',';
            } else if ($dataArr && $type == 6) {
                $dataArrs[] = $dataArr;
                $add['sericnum'] .= $dataArr[0] . ',';
            }else if($dataArr && $type == 2){
                $dataArrs[] = $dataArr;
                $add['sericnum'] .= $dataArr[3] . ',';
            }


        }
        $add['num'] = count($dataArrs);

        return $dataArrs;
    }

    /**
     * 处理发送人
     */
    private function send_who(&$add)
    {
        $add['send_files'] = $_FILES["post_files"]["name"];
        $fileName = $_FILES["post_files"]["tmp_name"];
        if (!file_exists($fileName))
            backmsg('请上传发送人文件');

        $size = $_FILES['post_files']['size'];
        if ($size > 2097152)
            backmsg('发送人文件不可大于2M');

        //xls:application/vnd.ms-excel
        //xlsx:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet
        $file_type = $_FILES['post_files']['type'];
        if (!in_array($file_type, ['application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']))
            backmsg('发送人只能使用xls或xlsx的文件');
        require_once "./include/PHPExcel/IOFactory.php";
        $objPHPExcel = PHPExcel_IOFactory::load($fileName);
        $sheetCount = $objPHPExcel->getSheetCount();
        $sheetSelected = 0;
        $objPHPExcel->setActiveSheetIndex($sheetSelected);
        $rowCount = $objPHPExcel->getActiveSheet()->getHighestRow();//行数
        $columnCount = $objPHPExcel->getActiveSheet()->getHighestColumn();//列数

        //单独拿出项目编号
        $dataArrs = array();
        $dataArr = array();
        $add['sericnum'] = '';
        /*11月06日修改*/
        /* for ($row = 2; $row <= $rowCount; $row++) {
             for ($column = 'A'; $column <= $columnCount; $column++) {
                 $val = $objPHPExcel->getActiveSheet()->getCell($column . $row)->getValue();
                 print_r($val);
                 if ($val) $dataArr[] = trim($val);
             }
             $val = $objPHPExcel->getActiveSheet()->getCell($column . $row)->getValue();
             if ($val) $dataArr[] = trim($val);
         }*/
        for ($row = 3; $row <= $rowCount; $row++) {
            $dataArr = array();
            for ($column = 'A'; $column <= $columnCount; $column++) {
                $val = $objPHPExcel->getActiveSheet()->getCell($column . $row)->getValue();
                if ($val) $dataArr[] = trim($val);
            }
            if ($dataArr) {
                $dataArrs[] = $dataArr;
                $add['sericnum'] .= $dataArr[1] . ',';
            }
        }
        $add['num'] = count($dataArrs);
        return $dataArrs;
    }

    /**
     * 保存流程附件
     */
    private function save_files(&$add)
    {
        $add['flow_files'] = $_FILES["files_"]["name"][0];
        //若上传了一个附件以上，则开始处理附件
        $fileName_1 = $_FILES["files_"]["tmp_name"][0];//第一个附件
        if ($fileName_1 && file_exists($fileName_1)) {

            $tmp_name_arr = $_FILES["files_"]["tmp_name"];
            $size_arr = $_FILES["files_"]["size"];
            $type_arr = $_FILES["files_"]["type"];
            $name_arr = $_FILES["files_"]["name"];

            /* 1.检测文件数量，文件大小，类型等 */
            foreach ($tmp_name_arr as $k => $v) {
                if ($k > 9) backmsg('选择的流程附件不可多于10个');

                if ($size_arr[$k] > 2097152)
                    backmsg('单个流程附件不可大于2M');

                //doc:application/msword
                //docx:application/vnd.openxmlformats-officedocument.wordprocessingml.document
                if (!in_array($type_arr[$k], ['application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document']))
                    backmsg('流程附件只能使用doc或docx的文件');
            }

            /* 2.生成压缩文件 */
            //这里需要注意该目录是否存在，并且有创建的权限
            $zipname = './upload/sk_tongzhishu/' . date('Ymd_His_') . mt_rand() . '.zip';//当前时间加上随机数保证文件唯一
            $zip = new ZipArchive();
            $res = $zip->open($zipname, ZipArchive::CREATE);
            if ($res != true) backmsg('无法创建压缩文件');
            foreach ($tmp_name_arr as $k => $v) {
                $zip->addFile($v, $name_arr[$k]);
            }

            //关闭文件
            $zip->close();

            $add['files'] = $zipname;
        }
    }

    /**
     * 通知书项目发送列表
     * */
    public function noticereadAjax()
    {
        $notice_id = $this->post('notice_id');
        $notice_type = $this->post('notice_type');
        $mwhere = 'a.notice_id = ' . $notice_id . ' and a.type=' . $notice_type;
        $sericnum = trim($this->post('sericnum'));
        $project_name = trim($this->post('project_name'));
        $leader = trim($this->post('leader'));
        $company = trim($this->post('company'));

        if ($sericnum) {
            $mwhere .= " and a.sericnum like '%$sericnum%' ";
        }
        if ($project_name) {
            $mwhere .= " and a.project_name like '%$project_name%' ";
        }
        if ($leader) {
            if ($notice_type == 3 || $notice_type == 5 || $notice_type == 1 || $notice_type == 2) {
                $mwhere .= " and a.leader like '%$leader%' ";
            } else if ($notice_type == 4) {
                $mwhere .= " and a.contact_person like '%$leader%' ";
            }
        }
        if ($company) {
            $mwhere .= " and a.company like '%$company%' ";
        }


        $table = '';
        //课题申报
        if ($notice_type == 1 || $notice_type == 6 || $notice_type == 2) {
            $table = '`[Q]project_approval` a ';
        } else if ($notice_type == 3 || $notice_type == 4 || $notice_type == 5) {
            $table = '`[Q]notice_preprocess` a ';
        }
        $fields = 'a.*';
        $where = "1=1 and " . $mwhere;
        $order = '';
        $this->getlist($table, $fields, $where, $order);
    }

    public function noticereadafter($table, $rows)
    {
        foreach ($rows as $k => $v) {
            $rows[$k]['project_type'] = m('notice')->type_project[$v['type']];
            $rows[$k]['caoz'] = '<a onclick="readNoticeDetail(' . $v['id'] . ',' . $v['notice_id'] . ',' . $v['type'] . ')">查看</a>';
        }
        return $rows;
    }

    public function getdetaildataAjax()
    {
        $id = $_POST['id'];
        $notice_id = $_POST['notice_id'];
        $type = $_POST['type'];
        $rs = '';
        if ($type == 1 || $type == 2 || $type == 6) {
            $table = "[Q]notice as a left join [Q]project_approval as b on a.id=b.notice_id";
            $fields = ' a.title,a.remark,a.files,a.is_mail,a.flow_files,b.*';
            $where = " b.id = " . $id . " and b.notice_id = " . $notice_id;
            $rs = $this->limitRows("$table", "$fields", "$where");
        } else if ($type == 3 || $type == 4 || $type == 5) {
            $table = "[Q]notice ";
            $fields = 'title,remark,files,is_mail,flow_files';
            $where = "id = " . $notice_id;
            $rs = $this->limitRows("$table", "$fields", "$where");
        }
        if ($rs) {
            $this->showreturn($rs);
        } else {
            $this->showreturn('', $rs, 201);
        }
    }

    //申报者角色通知书列表
    public function noticelistAjax()
    {
        $uid = $this->adminid;
        //发送标题
        $titles = trim($this->post('title'));
        //通知类型
        $type = $this->post('type');
        //项目名称
        $project_name = trim($this->post('project_name'));
        $where1 = '';
        $where2 = '';
        if ($titles) {
            $where1 .= " and title like '%$titles%'";
        }
        if ($type) {
            $where1 .= " and type = $type";
        }
        if ($project_name) {
            $where2 .= " and project_name like '%$project_name%'";
        }
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

    //课题立项通知书详情
    public function gettzsdetailAjax()
    {
        $id = $_REQUEST['id'];
        $notice_id = $_REQUEST['notice_id'];
        $type = $_REQUEST['type'];
        $table = " [Q]project_approval as a";
        $fields = ' a.*';
        $where = " a.id = " . $id . " and a.notice_id = " . $notice_id . " and a.type=" . $type;
        $rs = $this->limitRows("$table", "$fields", "$where");
        $data = $rs['rows'][0];
        $send_time = explode('-', $data['opt_time']);
        //获取参与人
        $table2 = "[Q]flow_bill as a left join [Q]project_coursemember as b on a.mid = b.mid";
        $fields2 = ' b.name';
        $where2 = " a.sericnum = '" . $data['sericnum'] . "'";
        $rs2 = $this->limitRows("$table2", "$fields2", "$where2");
        $course_menber = '';
        foreach ($rs2['rows'] as $item) {
            $course_menber .= $item['name'] . ",";
        }
        $data['send_time'] = $send_time;
        $data['menber_name'] = $course_menber;
        echo json_encode(array(
            'success' => true,
            'data' => $data,
            'code' => 200
        ));
    }

    //下载课题立项通知书
    public function downtzsAction()
    {
        $id = $_GET['id'];
        $notice_id = $_GET['notice_id'];
        $type = $_GET['type'];
        $table = " [Q]project_approval as a";
        $fields = ' a.*';
        $where = " a.id = " . $id . " and a.notice_id = " . $notice_id . " and a.type=" . $type;
        $rs = $this->limitRows("$table", "$fields", "$where");
        $data = $rs['rows'][0];
        $send_time = explode('-', $data['opt_time']);
        //获取参与人
        $table2 = "[Q]flow_bill as a left join [Q]project_coursemember as b on a.mid = b.mid";
        $fields2 = ' b.name';
        $where2 = " a.sericnum = '" . $data['sericnum'] . "'";
        $rs2 = $this->limitRows("$table2", "$fields2", "$where2");
        $course_menber = '';
        foreach ($rs2['rows'] as $item) {
            $course_menber .= $item['name'] . ",";
        }

        if($type ==1){
            $html = ' <div>
            <p style="font-size:22pt; line-height:150%; margin:0pt; orphans:0; text-align:center; widows:0"><span
                        style="font-family:\'Times New Roman\'; font-size:22pt; font-weight:bold">&#xa0;</span></p>
            <p style="font-size:22pt; line-height:150%; margin:0pt; orphans:0; text-align:center; widows:0"><span
                        style="font-family:\'Times New Roman\'; font-size:22pt; font-weight:bold">&#xa0;</span></p>
            <p style="font-size:22pt; line-height:150%; margin:0pt; orphans:0; text-align:center; widows:0"><span
                        style="font-family:\'Times New Roman\'; font-size:22pt; font-weight:bold">&#xa0;</span></p>
            <p style="font-size:22pt; line-height:150%; margin:0pt; orphans:0; text-align:center; widows:0"><span
                        style="font-family:\'Times New Roman\'; font-size:22pt; font-weight:bold">&#xa0;</span></p>
            <p style="font-size:16pt; line-height:150%; margin:0pt; orphans:0; text-align:center; widows:0"><span
                        style="font-family:仿宋; font-size:16pt">珠社科规划办通〔</span><span
                        style="background-color:#008080; font-family:仿宋; font-size:16pt">' . $data['notice_num'] . '</span><span
                        style="font-family:仿宋; font-size:16pt">〕</span><span
                        style="background-color:#008080; font-family:仿宋; font-size:16pt">' . $data['notice_order'] . '</span><span
                        style="font-family:仿宋; font-size:16pt">号 </span></p>
            <p style="font-size:22pt; line-height:150%; margin:0pt; orphans:0; text-align:center; widows:0"><span
                        style="font-family:\'Times New Roman\'; font-size:22pt; font-weight:bold">&#xa0;</span></p>
            <p style="margin:0pt; orphans:0; text-align:center; widows:0"><span
                        style="font-family:方正小标宋简体; font-size:22pt; font-weight:normal">课题立项通知书</span></p>
            <p style="margin:0pt; orphans:0; text-align:center; widows:0"><span
                        style="font-family:\'Times New Roman\'; font-size:22pt; font-weight:bold">&#xa0;</span></p>
            <p style="line-height:23pt; margin:0pt; orphans:0; text-align:justify; widows:0"><span
                        style="font-family:仿宋; font-size:16pt">课题负责人</span><span
                        style="font-family:仿宋; font-size:16pt; text-decoration:underline">' . $data['leader'] . '</span><span
                        style="font-family:仿宋; font-size:16pt">:</span></p>
            <p style="line-height:23pt; margin:0pt; orphans:0; text-align:justify; text-indent:32.25pt; widows:0"><span
                        style="font-family:仿宋; font-size:16pt">你所申报的珠海市</span><span
                        style="background-color:#008080; font-family:仿宋; font-size:16pt">' . $data['nd_year'] . '</span><span
                        style="font-family:仿宋; font-size:16pt">年度哲学社科规划课题，经社科专家评审，市社科规划领导小组研究同意予以立项。</span></p>
            <p style="line-height:23pt; margin:0pt; orphans:0; text-align:justify; text-indent:32.25pt; widows:0"><span
                        style="font-family:仿宋; font-size:16pt">项目名称：</span><span
                        style="font-family:仿宋; font-size:16pt; text-decoration:underline">《</span><span
                        style="font-family:仿宋; font-size:16pt; text-decoration:underline">' . $data['project_name'] . ' </span><span
                        style="font-family:仿宋; font-size:16pt; text-decoration:underline">》</span></p>
            <p style="line-height:23pt; margin:0pt; orphans:0; text-align:justify; text-indent:32.25pt; widows:0"><span
                        style="font-family:仿宋; font-size:16pt; text-decoration:underline">（中标单位：</span><span
                        style="font-family:仿宋; font-size:16pt; text-decoration:underline">' . $data['company'] . ' </span><span
                        style="font-family:仿宋; font-size:16pt; text-decoration:underline"></span><span
                        style="font-family:仿宋; font-size:16pt; text-decoration:underline">）</span></p>
            <p style="line-height:23pt; margin:0pt; orphans:0; text-align:justify; text-indent:32.25pt; widows:0"><span
                        style="font-family:仿宋; font-size:16pt">课题</span><span
                        style="font-family:仿宋; font-size:16pt">类型：</span><span
                        style="font-family:仿宋; font-size:16pt; text-decoration:underline">' . $data['keti_type'] . '</span></p>
            <p style="line-height:23pt; margin:0pt; orphans:0; text-align:justify; text-indent:32.25pt; widows:0"><span
                        style="font-family:仿宋; font-size:16pt; text-decoration:none">成果形式：</span><span
                        style="font-family:仿宋; font-size:16pt; text-decoration:underline">' . $data['achievement_type'] . ' </span></p>
            <p style="line-height:23pt; margin:0pt; orphans:0; text-align:justify; text-indent:32.25pt; widows:0"><span
                        style="font-family:仿宋; font-size:16pt">立项编号：</span><span
                        style="font-family:仿宋; font-size:16pt; text-decoration:underline"> ' . $data['projectstart_num'] . '</span></p>
            <p style="line-height:23pt; margin:0pt; orphans:0; text-align:justify; text-indent:32.25pt; widows:0"><span
                        style="font-family:仿宋; font-size:16pt">资助经费：</span><span
                        style="font-family:仿宋; font-size:16pt; text-decoration:underline">' . $data['fund'] . '</span></p>
            <p style="line-height:23pt; margin:0pt; orphans:0; text-align:justify; text-indent:32.25pt; widows:0"><span
                        style="font-family:仿宋; font-size:16pt">完成时限：</span><span
                        style="font-family:仿宋; font-size:16pt; text-decoration:underline">' . $data['finish_time'] . '</span></p>
            <p style="line-height:23pt; margin:0pt; orphans:0; text-align:justify; text-indent:32.25pt; widows:0"><span
                        style="font-family:仿宋; font-size:16pt">课题</span><span style="font-family:仿宋; font-size:16pt">课题参与人：</span><span
                        style="font-family:仿宋; font-size:16pt; text-decoration:underline">' . $course_menber . '</span></p>
            <p style="line-height:23pt; margin:0pt; orphans:0; text-align:justify; text-indent:32.25pt; widows:0"><span
                        style="font-family:仿宋; font-size:16pt">请按有关要求办理相关手续，开展课题研究工作。</span></p>
            <p style="line-height:23pt; margin:0pt; orphans:0; text-align:justify; text-indent:32.25pt; widows:0"><span
                        style="font-family:仿宋; font-size:16pt">特此通知。</span></p>
            <p style="line-height:23pt; margin:0pt; orphans:0; text-align:right; widows:0"><span
                        style="font-family:仿宋; font-size:16pt">&#xa0;</span></p>
            <p style="line-height:23pt; margin:0pt; orphans:0; text-align:right; widows:0"><span
                        style="font-family:仿宋; font-size:16pt">珠海市哲学社会科学规划领导小组办公室</span></p>
            <p style="line-height:23pt; margin:0pt 32pt 0pt 0pt; orphans:0; text-align:center; widows:0"><span
                        style="font-family:仿宋; font-size:16pt">                       </span><span
                        style="background-color:#008080; font-family:仿宋; font-size:16pt">' . $send_time[0] . '</span><span
                        style="font-family:仿宋; font-size:16pt">年</span><span
                        style="font-family:仿宋; font-size:16pt"> </span><span
                        style="background-color:#008080; font-family:仿宋; font-size:16pt">' . $send_time[1] . '</span><span
                        style="font-family:仿宋; font-size:16pt">月</span><span
                        style="background-color:#008080; font-family:仿宋; font-size:16pt">' . $send_time[2] . '</span><span
                        style="font-family:仿宋; font-size:16pt">日</span></p>
            <p style="line-height:23pt; margin:0pt; orphans:0; text-align:justify; widows:0"><span
                        style="font-family:仿宋; font-size:16pt; font-weight:bold; text-decoration:underline">                      </span><span
                        style="font-family:仿宋; font-size:16pt; font-weight:bold; text-decoration:underline">                              </span>
            </p>
            <p style="line-height:23pt; margin:0pt 32pt 0pt 0pt; orphans:0; text-align:justify; widows:0"><span
                        style="font-family:仿宋; font-size:16pt">抄送：</span><span style="font-family:仿宋; font-size:16pt">课题负责人所在单位 </span><span
                        style="font-family:仿宋; font-size:16pt">                                                </span>
            </p></div>';
            $this->start();
            $word_name = '课题立项通知书.doc';
            echo $html;
            $this->save($word_name);
            ob_flush();
            flush();
        }
        else if ($type==2){
            $html = ' <div>
            <p style="font-size:22pt; line-height:150%; margin:0pt; orphans:0; text-align:center; widows:0"><span
                        style="font-family:\'Times New Roman\'; font-size:22pt; font-weight:bold">&#xa0;</span></p>
            <p style="font-size:16pt; line-height:150%; margin:0pt; orphans:0; text-align:center; widows:0"><span
                        style="font-family:仿宋; font-size:16pt">珠社科规划办通〔</span><span
                        style="background-color:#008080; font-family:仿宋; font-size:16pt">' .$data['notice_num']. '</span><span
                        style="font-family:仿宋; font-size:16pt">〕</span><span
                        style="background-color:#008080; font-family:仿宋; font-size:16pt"> ' . $data['notice_order'] . ' </span><span
                        style="font-family:仿宋; font-size:16pt">号 </span></p>
            <p style="font-size:22pt; line-height:150%; margin:0pt; orphans:0; text-align:center; widows:0"><span
                        style="font-family:\'Times New Roman\'; font-size:22pt; font-weight:bold">&#xa0;</span></p>
            <p style="line-height:26pt; margin:0pt; orphans:0; text-align:center; widows:0"><span
                        style="font-family:方正小标宋简体; font-size:22pt; font-weight:normal">珠海市哲学社会科学规划课题</span></p>
            <p style="line-height:26pt; margin:0pt; orphans:0; text-align:center; widows:0"><span
                        style="font-family:方正小标宋简体; font-size:22pt; font-weight:normal">结项通知书</span></p>
            <p style="line-height:26pt; margin:0pt; orphans:0; text-align:justify; widows:0"><span
                        style="font-family:仿宋; font-size:16pt">&#xa0;</span></p>
            <p style="line-height:26pt; margin:0pt; orphans:0; text-align:justify; widows:0"><span
                        style="font-family:仿宋; font-size:16pt">课题负责人</span><span
                        style="font-family:仿宋; font-size:16pt; text-decoration:underline"> ' . $data['leader'] . ' </span><span
                        style="font-family:仿宋; font-size:16pt">:</span></p>
            <p style="line-height:28pt; margin:0pt; orphans:0; text-align:justify; widows:0"><span
                        style="font-family:仿宋; font-size:16pt">    您所承担的珠海市</span><span
                        style="background-color:#008080; font-family:仿宋; font-size:16pt"> ' . $data['nd_year'] . ' </span><span
                        style="font-family:仿宋; font-size:16pt">年度哲学社科规划课题:</span></p>
            <p style="line-height:26pt; margin:0pt; orphans:0; text-align:justify; text-indent:32.25pt; widows:0"><span
                        style="font-family:仿宋; font-size:16pt">课题批准号：</span><span
                        style="font-family:仿宋; font-size:16pt; text-decoration:underline">  ' . $data['kt_num'] . '  </span></p>
            <p style="line-height:26pt; margin:0pt; orphans:0; text-align:justify; text-indent:32.25pt; widows:0"><span
                        style="font-family:仿宋; font-size:16pt">课题名称：</span><span
                        style="font-family:仿宋; font-size:16pt; text-decoration:underline">《</span><span
                        style="font-family:仿宋; font-size:16pt; text-decoration:underline"> ' . $data['project_name'] . '  </span><span
                        style="font-family:仿宋; font-size:16pt; text-decoration:underline">》</span></p>
            <p style="line-height:26pt; margin:0pt; orphans:0; text-align:justify; text-indent:32.25pt; widows:0"><span
                        style="font-family:仿宋; font-size:16pt">课题类型：</span><span
                        style="font-family:仿宋; font-size:16pt; text-decoration:underline"> ' . $data['keti_type'] . ' </span></p>
            <p style="line-height:26pt; margin:0pt; orphans:0; text-align:justify; text-indent:32.25pt; widows:0"><span
                        style="font-family:仿宋; font-size:16pt; text-decoration:none">成果形式：</span><span
                        style="font-family:仿宋; font-size:16pt; text-decoration:underline">' . $data['achievement_type'] . ' </span></p>
            <p style="line-height:26pt; margin:0pt; orphans:0; text-align:justify; text-indent:32.25pt; widows:0"><span
                        style="font-family:仿宋; font-size:16pt">课题</span><span
                        style="font-family:仿宋; font-size:16pt">参与人：</span><span
                        style="font-family:仿宋; font-size:16pt; text-decoration:underline">'.$course_menber.'</span></p>
            <p style="line-height:28pt; margin:0pt; orphans:0; text-align:justify; text-indent:21pt; widows:0"><span
                        style="font-family:仿宋; font-size:16pt">鉴定等级：</span><span
                        style="font-family:仿宋; font-size:16pt; text-decoration:underline"> ' . $data['appraisal_grade'] . '</span></p>
            <p style="line-height:28pt; margin:0pt; orphans:0; text-align:justify; text-indent:21pt; widows:0"><span
                        style="font-family:仿宋; font-size:16pt">根据《珠海市哲学社会科学规划项目管理办法》有关规定，予以</span><span
                        style="font-family:仿宋; font-size:16pt">结项。</span></p>
            <p style="line-height:28pt; margin:0pt; orphans:0; text-align:justify; text-indent:21pt; widows:0"><span
                        style="font-family:仿宋; font-size:16pt">特此通知。</span></p>
            <p style="line-height:28pt; margin:0pt; orphans:0; text-align:justify; widows:0"><span
                        style="font-family:仿宋; font-size:16pt">               </span><span
                        style="font-family:仿宋_GB2312; font-size:16pt">珠海市哲学社会科学</span><span
                        style="font-family:仿宋_GB2312; font-size:16pt">规划</span><span
                        style="font-family:仿宋_GB2312; font-size:16pt">领导小组办公室</span>
            </p>
            <p style="line-height:28pt; margin:0pt; orphans:0; text-align:justify; widows:0"><span
                        style="font-family:仿宋; font-size:16pt">                           </span><span
                        style="background-color:#008080; font-family:仿宋; font-size:16pt"> '.$send_time[0].'</span><span
                        style="font-family:仿宋; font-size:16pt">年</span><span
                        style="background-color:#008080; font-family:仿宋; font-size:16pt"> '.$send_time[1].' </span><span
                        style="font-family:仿宋; font-size:16pt">月</span><span
                        style="background-color:#008080; font-family:仿宋; font-size:16pt"> '.$send_time[2].' </span><span
                        style="font-family:仿宋; font-size:16pt">日</span></p>
            <p style="line-height:28pt; margin:0pt; orphans:0; text-align:justify; widows:0"><span
                        style="font-family:仿宋; font-size:16pt; font-weight:bold; text-decoration:underline"> </span><span
                        style="font-family:仿宋; font-size:16pt; font-weight:bold; text-decoration:underline"></span>
            </p>
            <p style="line-height:28pt; margin:0pt 32pt 0pt 0pt; orphans:0; text-align:justify; widows:0"><span
                        style="font-family:仿宋; font-size:16pt">抄送：</span><span
                        style="font-family:仿宋; font-size:16pt">课题负责人所在单位 </span><span
                        style="font-family:仿宋; font-size:16pt">                                                </span><span
                        style="font-family:仿宋_GB2312; font-size:16pt"> </span></p></div>';
            $this->start();
            $word_name = '课题结项通知书.doc';
            echo $html;
            $this->save($word_name);
            ob_flush();
            flush();
        }
    }

    public function start()
    {
        ob_start();
        echo '<html xmlns:o="urn:schemas-microsoft-com:office:office"
                    xmlns:w="urn:schemas-microsoft-com:office:word"
                    xmlns="http://www.w3.org/TR/REC-html40">';
    }

    public function save($path)
    {
        echo "</html>";
        $data = ob_get_contents();
        ob_end_clean();
        //利用Iconv函数对文件名进行重新编码
        $path = iconv('utf-8', 'gb2312', $path);
        $fileName = "./upload/kttzs/" . date('YmdHis', time()) . $path . "";
        $this->writefile($fileName, $data);
    }

    public function writefile($path, $data)
    {
        $fp = fopen($path, "wb");
        fwrite($fp, $data);
        fclose($fp);
        ob_end_clean();//清除缓存以免乱码出现
        header("Cache-Control: max-age=0");
        header("Content-Description: File Transfer");
        header('Content-disposition: attachment; filename=' . basename($path)); //文件名
        header("Content-Type: application/doc"); //文件格式:doc
        header("Content-Transfer-Encoding: binary"); // 告诉浏览器，这是二进制文件
        header('Content-Length: ' . filesize($path)); //告诉浏览器,文件大小
        @readfile($path);//输出文件;
    }

    /**
     * 公共列表获取方法
     * */
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
        if (method_exists($this, $aftera)) {//操作菜单权限处理
            $narr = $this->$aftera($childtable, $arr['rows']);
            if (is_array($narr)) {
                foreach ($narr as $kv => $vv) $arr['rows'][$kv] = $vv;
            }
        }

        $this->returnjson($arr);
    }
}
