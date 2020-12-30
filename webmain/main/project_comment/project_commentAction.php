<?php

class project_commentClassAction extends Action
{

    private static $insertsuccessnum = 0;//添加成功条数
    private $insertsuccesslastid = '';//插入成功的lastid
    public $ps_start_id = '75';

    public function initAction()
    {

    }

    private function getmtype()
    {
        //实训网评操作人添加的指标$mtype=project_sx_apply
        //非实训网评操作人添加的指标$mtype=project_apply
        $mtype = '';
        $sjoin = m('sjoin')->getone('mid=6 and sid=' . $this->adminid);
        if ($sjoin) {
            $mtype = 'project_sx_apply';
        } else {
            $mtype = 'project_apply';
        }
        return $mtype;
    }

    //获取导出按钮
    public function getanniuAjax()
    {
        //实训网评操作人添加的指标$mtype=project_sx_apply
        //非实训网评操作人添加的指标$mtype=project_apply
        $data = '';
        $mtype = $this->getmtype();
        if ($mtype == 'project_sx_apply') {
            $data = '<li><button class="btn_ bgGray " onclick="dowList(4)" type="button">导出</button></li>';
        } elseif ($mtype == 'project_apply') {
            $data = '';
        }
        $this->returnjson(array('success' => true, 'data' => $data));
    }


    /**
     * 获取指标详情
     */
    public function getnormdetail($norm_id, $lx = 0)
    {
        $norm_info = m('m_dafen')->getone('id=' . $norm_id, $fields = 'dafen_model_name as name,dafen_model_num as num,mtype');
        //var_dump($norm_info);
        if ($lx == 0) $norm_info['info'] = $this->getmoreoption($norm_id);
        return json_encode($norm_info);
    }


    /**
     * 多级打分项信息获取（递归函数）
     */
    public function getmoreoption($norm_id, $pid = 0)
    {
        $option_info = m('m_option')->getall("mid=$norm_id and pid=$pid", '*', 'sort,id desc');
        foreach ($option_info as $k => $v) {
            //$option_info[$k] = array('option_msg'=>$v['option_msg'],'option_fenzhi'=>$v['option_fenzhi'],'option_range'=>$v['option_range'],'sort'=>$v['sort']);
            $option_info[$k] = array('id' => $v['id'], 'option_msg' => $v['option_msg'], 'option_fenzhi' => $v['option_fenzhi'], 'minscore' => unserialize($v['option_range'])[0], 'maxscore' => unserialize($v['option_range'])[1], 'sort' => $v['sort']);
            $moreinfo = $this->getmoreoption($norm_id, $v['id']);
            if (!empty($moreinfo)) $option_info[$k]['info'] = $this->getmoreoption($norm_id, $v['id']);
        }
        unset($k, $v);
        return $option_info;
    }


    /**
     * 导入新增指标
     */
    public function importexcelAjax()
    {
        //引用PHPexcel 类
        //$_FILES['file']['tmp_name']:上传文件的临时路径
        include_once(ROOT_PATH . '/include/PHPExcel.php');
        include_once(ROOT_PATH . '/include/PHPExcel/IOFactory.php');//静态类
        $type = 'Excel2007';//设置为Excel5代表支持2003或以下版本，Excel2007代表2007版
        $xlsReader = PHPExcel_IOFactory::createReader($type);
        $path = $_FILES['file']['tmp_name'];
        //$norm_name = $_POST['name'];
        $xlsReader->setReadDataOnly(true);
        $xlsReader->setLoadSheetsOnly(true);
        $Sheets = $xlsReader->load($path);
        //开始读取上传到服务器中的Excel文件，返回一个二维数组
        $dataArray = $Sheets->getSheet(0)->toArray();
        $new_data = array();
        // print_r($dataArray);
        //判断表头是否正确
        if (count($dataArray[0]) === 6) {
            if ($dataArray[0][0] !== '序号' && $dataArray[0][1] !== '一级指标名称' && $dataArray[0][2] !== '分数' && $dataArray[0][3] !== '二级指标内容' && $dataArray[0][4] !== '分值>' && $dataArray[0][5] !== '分值<') {
                $this->returnjson(array('success' => false, 'msg' => '请上传正确的指标excel表'));
            } else {
                //新数组下标初始值
                $c = 0;
                $x = 0;
                $arr_count = count($dataArray);
                foreach ($dataArray as $v => $data) {
                    if ($v !== 0) {
                        if (($v + 1) <= ($arr_count - 1)) {
                            if ($dataArray[$v + 1][0] == $dataArray[$v][0]) {
                                //二级标题
                                $x = $x + 1;
                                $new_data[$c]['info'][$x]['option_msg'] = $dataArray[$v + 1][3];
                                $new_data[$c]['info'][$x]['minscore'] = $dataArray[$v + 1][4];
                                $new_data[$c]['info'][$x]['maxscore'] = $dataArray[$v + 1][5];
                                $new_data[$c]['info'][$x]['sort'] = $x + 1;
                                $new_data[$c]['info'][$x]['level'] = 2;
                            } else {
                                //一级标题
                                $x = 0;
                                $c = $c + 1;
                                $new_data[$c]['option_msg'] = $dataArray[$v + 1][1];
                                $new_data[$c]['option_fenzhi'] = $dataArray[$v + 1][2];
                                $new_data[$c]['sort'] = $dataArray[$v + 1][0];
                                $new_data[$c]['level'] = 1;
                                $new_data[$c]['info'][$x]['option_msg'] = $dataArray[$v + 1][3];
                                $new_data[$c]['info'][$x]['minscore'] = $dataArray[$v + 1][4];
                                $new_data[$c]['info'][$x]['maxscore'] = $dataArray[$v + 1][5];
                                $new_data[$c]['info'][$x]['sort'] = $x + 1;
                                $new_data[$c]['info'][$x]['level'] = 2;
                            }
                        }
                    } else {
                        if (($v + 1) <= ($arr_count - 1)) {
                            //一级标题
                            $new_data[$c]['option_msg'] = $dataArray[$v + 1][1];
                            $new_data[$c]['option_fenzhi'] = $dataArray[$v + 1][2];
                            $new_data[$c]['sort'] = $dataArray[$v + 1][0];
                            $new_data[$c]['level'] = 1;
                            $new_data[$c]['info'][$x]['option_msg'] = $dataArray[$v + 1][3];
                            $new_data[$c]['info'][$x]['minscore'] = $dataArray[$v + 1][4];
                            $new_data[$c]['info'][$x]['maxscore'] = $dataArray[$v + 1][5];
                            $new_data[$c]['info'][$x]['sort'] = $x + 1;
                            $new_data[$c]['info'][$x]['level'] = 2;
                        }
                    }
                }
                //返回excel表里的数据
                $this->returnjson(array('success' => true, 'data' => $new_data));
            }
        } else {
            $this->returnjson(array('success' => false, 'msg' => '请上传正确的指标excel表'));
        }


    }

    /**
     * 新增指标接口
     */
    public function addnormAjax()
    {
        $norm_name = $this->rock->post('name');//指标名称
        $mtype = $this->rock->post('mtype');//项目类型
        @$norm_info = $this->rock->post('info');//指标内容
        //$norm_info = (array)json_decode($this->rock->request('info'));//指标内容(json)
        //var_dump($norm_info);exit;
        $user_id = $this->adminid;//操作人id
        $requestTime = date('Y-m-d H:i:s', time());//获取接口请求时间
        $res_id = m('m_dafen')->insert(array(
            'dafen_model_name' => $norm_name,
            'dafen_model_num' => 'ZB' . date('YmdHis') . rand(0, 1000),
            'mtype' => $mtype,
            'user_id' => $user_id,
            'operating_time' => $requestTime
        ));
        if ($this->insertsuccesslastid = $this->saveNormInfo($res_id, 0, $norm_info)) {
            $reinfo = array('插入成功条数:' . self::$insertsuccessnum, '插入成功的lastid:' . $this->insertsuccesslastid);
            $this->returnjson(array('id' => $reinfo, 'success' => true, 'msg' => '新增成功'));
        } else {
            $this->returnjson(array('id' => '', 'success' => false, 'msg' => '新增失败'));
        }
    }

    /**
     * 批量存储指标内容(递归)
     */
    public function saveNormInfo($norm_id, $pid, $norm_info)
    {
        $rs = array();//定义空数组
        $res_id = false;//最后插入的信息
        foreach ($norm_info as $k => $v) {
            $rs['mid'] = $norm_id;
            $rs['pid'] = $pid;
            $rs['option_msg'] = $v['option_msg'];
            if ($pid != 0) $rs['option_range'] = serialize(array($v['minscore'], $v['maxscore']));//序列化数组
            if ($pid == 0) $rs['option_fenzhi'] = $v['option_fenzhi'];
            $rs['sort'] = $v['sort'];
            $rs['level'] = $v['level'];
            $res_id = m('m_option')->insert($rs);
            if (!$res_id) return false; else self::$insertsuccessnum++;
            if (!empty($v['info'])) {
                $this->saveNormInfo($norm_id, $res_id, $v['info']);
            }
        }
        unset($k, $v);
        return $res_id;
    }


    /**
     * 指标列表
     */
    public function normlistAjax()
    {
        $normName = $this->rock->request('norm_name');//指标名称
        $where = '';

        //实训类的只能看实训类的，非实训类的只能看非实训类的
        /*$mtype = $this->rock->post('mtype');
		$where .= " and mtype='$mtype'";*/

        if ($normName != "") $where .= " and dafen_model_name like '%$normName%'";
        $table = '[Q]m_dafen';
        $fields = '*';
        $where = "dafen_status=0 $where";
        $order = 'operating_time desc';
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
     * 指标列表操作获取
     */
    public function normlistafter($table, $rows)
    {
        foreach ($rows as $k => $rs) {
            $rows[$k]['caoz'] = '';
            $rows[$k]['caoz'] .= '<a onclick="norm_look(' . $rs['id'] . ')">查看</a>';
            $rows[$k]['caoz'] .= '<span style="padding:5px;">|</span>';
            $rows[$k]['caoz'] .= '<a onclick="norm_edit(' . $rs['id'] . ')">编辑</a>';
            $rows[$k]['caoz'] .= '<span style="padding:5px;">|</span>';
            $rows[$k]['caoz'] .= '<a onclick="norm_del(' . $rs['id'] . ')">删除</a>';
        }
        return $rows;
    }

    /**
     * 指标详情返回
     */
    public function getnormdetailAjax()
    {
        $norm_id = $this->rock->request('norm_id');//指标id
        //var_dump($this->getnormdetail($norm_id));exit;
        $this->returnjson(array('success' => true, 'data' => $this->getnormdetail($norm_id)));
    }

    /**
     * 获取pici中model指标详情返回
     */
    public function getpicimodelAjax()
    {
        $pici_id = $this->post('pici_id');//指标id
        //调用批次信息中指标

        $pici_info = m('m_batch')->getone('id=' . $pici_id);

        //调用用户草稿的指标

        $this->returnjson(array('success' => true, 'data' => $pici_info['model']));

    }

    /**
     * 获取草稿中model指标详情返回
     */
    public function getuserpxdfmodelAjax()
    {
        $pici_id = $this->post('pici_id');//指标id
        $mid = $this->post('mid');
        $mtype = $this->post('mtype');
        $uid = $this->adminid;
        $urs = m('flow_bill')->getone('mid=' . $mid . ' and `table`=\'' . $mtype . '\'');
        $bill_id = $urs['id'];
        //$pxmd_info = m('m_pxmdf')->getone('pici_id=' . $pici_id . ' and xid=' . $bill_id . ' and mtype=\'' . $mtype . '\' and com_status=0 and uid=' . $uid);
        //2020-12-01修改
        $pxmd_info = m('m_pxmdf')->getone('pici_id=' . $pici_id . ' and xid=' . $bill_id . ' and mtype=\'' . $mtype . '\' and com_status=2 and uid=' . $uid);
        $this->returnjson(array('success' => true, 'data' => $pxmd_info));
    }

    /**
     * 删除指标
     */
    public function normdelAjax()
    {
        $norm_id = $this->rock->request('norm_id');//指标id
        $delnorm = m('m_dafen')->update(array('dafen_status' => 1), "id=$norm_id");
        if ($delnorm) $this->returnjson(array('id' => $delnorm, 'success' => true, 'msg' => '删除成功'));
        else $this->returnjson(array('id' => $delnorm, 'success' => false, 'msg' => '删除失败'));
    }


    /**
     * 编辑指标
     */
    public function normeditAjax()
    {
        $norm_id = $this->rock->post('norm_id');//指标id
        $norm_name = $this->rock->post('name');//指标名
        //$mtype = $this->getmtype();//项目类型
        @$norm_control = $this->rock->post('edit_control');//指标编辑操作数组

        //更新指标表
        $updafen = m('m_dafen')->update(array(
            'dafen_model_name' => $norm_name,
            'mtype' => $this->rock->post('mtype'),
            'user_id' => $this->adminid,
            'operating_time' => date('Y-m-d H:i:s')
        ), "id=$norm_id");
        //echo m('m_dafen')->getLastSql();

        //对打分信息表内容操作
        if (!empty($norm_control['addinfo'])) {//判断是否有新增
            $this->saveNormInfo($norm_id, 0, $norm_control['addinfo']);
        }
        if (!empty($norm_control['editinfo'])) {//判断是否有更新
            $this->saveOptionInfo($norm_id, 0, $norm_control['editinfo']);
        }
        if (!empty($norm_control['delinfo'])) {//判断是否有删除
            foreach ($norm_control['delinfo'] as $k => $v) {
                $del_ids = m('m_option')->delete("id=$v");
            }
            unset($k, $v);
        }

        if ($updafen) $this->returnjson(array('id' => $updafen, 'success' => true, 'msg' => '编辑成功'));
        else $this->returnjson(array('id' => $updafen, 'success' => false, 'msg' => '编辑失败'));
    }

    /**
     * 更新打分信息表内容（批量更新打分详细信息内容(递归)）
     */
    public function saveOptionInfo($norm_id, $pid, $option_info)
    {
        $rs = array();//定义空数组
        $sub_pid = 0;//传给下一组的pid
        foreach ($option_info as $k => $v) {
            $rs['option_msg'] = $v['option_msg'];
            if (array_key_exists('minscore', $v) && array_key_exists('maxscore', $v)) $rs['option_range'] = serialize(array($v['minscore'], $v['maxscore']));//序列化数组
            if (array_key_exists('option_fenzhi', $v)) $rs['option_fenzhi'] = $v['option_fenzhi'];
            $rs['sort'] = $v['sort'];
            $rs['level'] = $v['level'];
            if ((int)$v['id'] == 0) {//添加
                $rs['mid'] = $norm_id;
                $rs['pid'] = $pid;
                $sub_pid = m('m_option')->insert($rs);
            } else {//修改
                $sub_pid = $v['id'];
                $res_id = m('m_option')->update($rs, 'id=' . $v['id']);
            }
            if (!empty($v['info'])) $this->saveOptionInfo($norm_id, $sub_pid, $v['info']);
        }
        unset($k, $v);
        return $res_id;
    }


    /**
     * 根据ids获取选择了的项目信息
     */
    public function getprojectlistAjax()
    {
        //添加项目列表不显示追加项目前的的项目
        $additems_project_ids = $this->post('additems_project_ids');//追加项目前ids
        $projectIds = $this->post('project_ids');//项目ids
        $mtype = 'project_coursetask';//项目类型

        $keywords = $this->rock->post('keywords');//关键词分类
        $subject = $this->rock->post('subject');//学科分类
        $project_type = $this->rock->post('project_type');//申报类型
        $keywords_detail = $this->rock->post('keywords_detail');//具体关键词
        $project_num = $this->rock->post('project_num');//项目编号
        //评审类型(立项评审,结项评审)
        $ps_status = $this->rock->post('ps_status');
        $where = '';
        /*  if ($projectIds==''){
              //未编辑时获取未网评的
              $where .= " and b.is_wp=0";//是否只获取没网评的
          }*/

        if ($projectIds != '') $where .= " and a.id in ($projectIds)";
        //关键词
        if ($keywords != '') {
            $where .= " and d.keyword_classification='" . $keywords . "'";
        }
        //学科
        if ($subject != '') {
            $where .= " and d.subject_classification='" . $subject . "'";
        }
        //申报类型
        if ($project_type != '') {
            if ($project_type == 'project_coursetask') {
                $mtype = 'project_coursetask';
            }
        }
        //具体关键词
        if ($keywords_detail != '') {
            $where .= " and d.specific_keywords='" . $keywords_detail . "'";
        }
        //项目编号
        if ($project_num != '') {
            $where .= " and a.sericnum='" . $project_num . "'";
        }
        //评审类型(立项评审,结项评审)
        if ($ps_status == '立项评审') {
            $where .= " and a.nowcourseid = 108 and a.status = 0";
            if ($projectIds == '') {
                //未编辑时获取未网评的
                $where .= " and b.is_wp=0";//是否只获取没网评的
            }
        } else if ($ps_status == '结项评审') {
            $where .= " and a.nowcourseid = 103 and a.status = 0";
            if ($projectIds == '') {
                //未编辑时获取未网评的
                $where .= " and b.is_wp_end=0";//是否只获取没网评的
            }
        }

        /* //不显示追加项目前的的项目
         //if ($additems_project_ids != '') $where .= " and c.id not in ($additems_project_ids)";

         //时间范围
         if ($time_frame != "") {
             list($start_time, $end_time) = explode(',', $time_frame);
             $where .= " and c.project_apply_time between '" . $start_time . "' and '" . $end_time . "'";
             unset($start_time, $end_time);
         }

         //申报单位
         if ($sbdw != '') $where .= " and b.deptname='" . trim($sbdw) . "'";
         //项目编号
         if ($xmbh != '') $where .= " and c.project_number='" . trim($xmbh) . "'";
         //项目名称
         if ($xmmc != '') $where .= " and c.project_name like '%" . trim($xmmc) . "%'";
         //项目负责人
         if ($fzr != '') $where .= " and c.project_head='" . trim($fzr) . "'";*/

        /*$table = '`[Q]flow_bill` a left join `[Q]admin` b on a.uid=b.id left join `whole_projects` c on a.mid=c.id left join `[Q]flow_course` fc on fc.id=a.nowcourseid';
        $fields = 'a.table as mtype,a.modeid,a.optid,a.nowcheckname,a.status as bill_status,a.allcheckid,a.nowcheckid,a.nowcourseid,a.optid,b.name,b.deptname,c.id,c.num,c.project_name,c.status as cst,c.isturn,c.optname,c.project_head,c.project_apply_time,c.project_select,c.project_xingzhi,c.project_ku,c.isturn,fc.name as flowname,c.exigence_status,c.process_state,c.project_is_guidang,c.project_number,c.project_yushuan';
        $where = "a.table='$mtype' and c.isturn=1 $where";
        $order = 'a.optdt desc';*/

        /*  $table = "`[Q]flow_bill` a left join `[Q]admin` b on a.uid=b.id left join `[Q]flow_course` fc on fc.id=a.nowcourseid left join `[Q]$mtype` d on a.mid=d.id";
          $fields = 'a.id,a.mid,a.optdt as apply_time,a.modename as project_select,a.sericnum as project_number,a.optid,';
          $fields .= 'a.table as mtype,a.modeid,a.optid,a.nowcheckname,a.status as bill_status,a.allcheckid,a.nowcheckid,a.nowcourseid,';
          $fields .= 'b.name,b.deptname,fc.name as flowname,d.course_name as project_name,d.subject_classification,d.keyword_classification,d.specific_keywords,d.is_wp';
          $where = "a.table='$mtype' $where";
          $order = 'a.optdt desc';
          $arr = $this->limitRows($table, $fields, $where, $order);
          $arr['totalCount'] = $arr['total'];*/

        /*2020-11-30修改*/
        $table = "`[Q]flow_bill` a left join  `[Q]$mtype` b on a.mid=b.id";
        $fields = 'a.id,a.mid,a.optdt as apply_time,a.modename as project_select,a.sericnum as project_number,';
        $fields .= 'a.table as mtype,a.modeid,a.status as bill_status,a.nowcourseid,';
        $fields .= 'b.course_name as project_name,b.subject_classification,b.keyword_classification,b.specific_keywords,b.is_wp';
        $where = "a.table='$mtype' $where";
        $order = 'a.optdt desc';
        $arr = $this->limitRows($table, $fields, $where, $order);
        $arr['totalCount'] = $arr['total'];
        $this->returnjson($arr);
    }

    /**
     * 获取关键词分类
     * */
    public function keyword_classificationAjax()
    {
        $table = '[Q]key_word';
        $fields = '*';
        $where = "del_status=0";
        $order = 'add_time desc';
        $keyword_arr = array();
        $arr = $this->limitRows($table, $fields, $where, $order);

        if ($arr) {
            $this->requestsuccess($arr);
        } else {
            $this->requesterror('关键词分类获取失败');
        }
    }

    /**
     * 获取学科分类
     * */
    public function subject_classificationAjax()
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
        if ($new_rows) {
            $this->requestsuccess($new_rows);
        } else {
            $this->requesterror('学科分类获取失败');
        }

    }


    /**
     * 发起网评保存草稿或提交
     */
    public function savecommentAjax()
    {
        $id = $this->post('id');//id如果id为0则是新增,大于0则为编辑
        $pici_name = $this->post('pici_name');//批次名称
        $pici_start_time = $this->post('pici_start_time');//开始时间
        $pici_end_time = $this->post('pici_end_time');//结束时间
        $pici_norm_id = $this->post('pici_norm_id');//指标id
        $mtype = $this->post('mtype');//评审类型  project_end-结项评审,project_start-立项评审
        $expert_ids = $this->post('expert_ids');//专家ids
        $project_ids = $this->post('project_ids');//项目ids
        $operating_time = date('Y-m-d H:i:s', time());//操作时间
        $is_submit = (int)$this->post('is_submit');//true or false 是否提交

        $msg = '';
        $info = array();
        //保存草稿必须填写批次名称和选择指标，否则列表无法显示
        if ($pici_name == '') $msg = '批次名称不能为空';
        if ($pici_norm_id == '') $msg = '请选择指标';

        //如果是提交，则要全部数据做判断是否为空
        if ($is_submit) {
            if ($pici_start_time == '') $msg = '请选择评审开始时间';
            if ($pici_end_time == '') $msg = '请选择评审结束时间';
            if ($expert_ids == '') $msg = '请选择参与评审的专家';
            if ($project_ids == '') $msg = '请选择需要评审的项目';
        }

        if ($is_submit && $msg == '') {
            //提交
            $pici_id = '';//批次id
            $reinfo = '';//是否成功
            $data = array(
                'pici_num' => 'PC' . time() . rand(1, 1000),
                'pici_name' => $pici_name,
                'pici_start_time' => $pici_start_time,
                'pici_end_time' => $pici_end_time,
                'user_id' => $this->adminid,
                'launch_time' => date('Y-m-d', time()),//网评发起时间
                'operating_time' => $operating_time,
                'pici_norm_id' => $pici_norm_id,
                'expert_ids' => serialize(explode(',', $expert_ids)),
                'project_ids' => serialize(explode(',', $project_ids)),
                'mtype' => $mtype,
                'model' => addslashes($this->getnormdetail($pici_norm_id)),
                'com_status' => 1,
            );
            //如果id=0则是添加，id大于0则为更新
            if ($id == 0) {
                $reinfo = $pici_id = m('m_batch')->insert($data);
            } else {
                $pici_id = $id;
                $reinfo = m('m_batch')->update($data, "id=$id");
            }


            //打分批次中的项目表m_pxm_relation
            $pxmresult = '';
            foreach (explode(',', $project_ids) as $k => $v) {
                //改变项目的网评状态
                if ($v != '') {
                    $rs = m('flow_bill')->getone("id=$v");
                    $tables = $rs['table'];
                    $mid = $rs['mid'];
                    if ($mtype == 'project_start') {
                        m($tables)->update("is_wp=1", "id=$mid");
                        $pxmresult = m('m_pxm_relation')->insert(array(
                            'pici_id' => $pici_id,
                            'xid' => $v,
                            'mtype' => $tables,
                            'pingshen_state' => 1
                        ));
                    } else if ($mtype == 'project_end') {
                        m($tables)->update("is_wp_end=1", "id=$mid");
                        $pxmresult = m('m_pxm_relation')->insert(array(
                            'pici_id' => $pici_id,
                            'xid' => $v,
                            'mtype' => $tables,
                            'pingshen_state' => 2
                        ));
                    }


                }
            }
            unset($k, $v);


            //打分批次中的专家参与人员表m_pua_relation
            $puaresult = '';
            foreach (explode(',', $expert_ids) as $k => $v) {
                if ($v != '') {
                    $puaresult = m('m_pua_relation')->insert(array(
                        'pici_id' => $pici_id,
                        'uid' => $v
                    ));
                }
            }
            unset($k, $v);


            //打分批次中的项目得分表m_pxmdf
            $pxmdfresult = '';
            foreach (explode(',', $expert_ids) as $expert_k => $expert_v) {
                foreach (explode(',', $project_ids) as $projetc_k => $projetc_v) {
                    $rs = m('flow_bill')->getone("id=$projetc_v");
                    $tables = $rs['table'];
                    if ($expert_v != '') {
                        if ($mtype == 'project_start') {
                            $pxmdfresult = m('m_pxmdf')->insert(array(
                                'pici_id' => $pici_id,
                                'uid' => $expert_v,
                                'xid' => $projetc_v,
                                'mtype' => $tables,
                                'pingshen_state' => 1
                            ));
                        } else if ($mtype == 'project_end') {
                            $pxmdfresult = m('m_pxmdf')->insert(array(
                                'pici_id' => $pici_id,
                                'uid' => $expert_v,
                                'xid' => $projetc_v,
                                'mtype' => $tables,
                                'pingshen_state' => 2
                            ));
                        }
                    }
                }
                unset($projetc_k, $projetc_v);
            }
            unset($expert_k, $expert_v);

            if ($reinfo && $pxmresult && $puaresult && $pxmdfresult) {
                //给网评专家们发送微信模板消息
                /*$data = array();
				foreach (explode(',', $expert_ids) as $expert_k => $expert_v) {
					//获取专家信息
					$userinfo = m('admin')->getone("id=$expert_v");
					$articles = "网评项目包括以下项目:\n";//存储专家对应网评项目信息
					$project_num = 0;//评审项目个数
					foreach (explode(',', $project_ids) as $projetc_k => $projetc_v) {
						$project_info = m($mtype)->getone("id=$projetc_v");
						$project_num++;
						//$articles .= $project_num."、".$project_info['project_name']."\n";
					}
					unset($projetc_k,$projetc_v);

					$articles = "网评项目数：".$project_num."个";
					$data['articles'][0] = array(
						"title" =>$userinfo['name'].',您有'.$project_num.'个项目待网评',
		            	"description" => "详情请登录电脑端查看\n批次名称：".$pici_name."\n评审时间：".date('Y-m-d H:i',strtotime($pici_start_time)).'至'.date('Y-m-d H:i',strtotime($pici_end_time))."\n".$articles,
						"url" =>"",
				        "picurl" =>""
					);
					$logData=array(
						'pici_id'=>$pici_id,
						'pici_name'=>$pici_name,
						'uid'=>$userinfo['id'],
						'uname'=>$userinfo['name'],
						'type'=>0
					);
					if($userinfo['wx_openid']!=''){
						$flag = m('wxgzh:wxgzh')->doSend($userinfo['wx_openid'],'@all','@all',1, $data);
						if($flag->errcode==0){
							//发送成功
							$logData['remark']='发送成功';
						}else{
							//发送错误
							$logData['remark']='发送错误：\r\n'.$flag->errmsg;
						}
					}else{
						//专家没有微信公众号的openid
						$logData['remark']='发送失败：\r\n专家没有微信公众号的openid';
					}
					c('log')->marklog($logData);//记录发送消息信息
				}
				unset($expert_k,$expert_v);*/
                $info = array('id' => $reinfo, 'mtype'=>$mtype,'success' => true, 'msg' => '提交成功');
            } else {
                $info = array('id' => $reinfo, 'mtype'=>$mtype,'success' => false, 'msg' => '提交失败');
            }
            $this->returnjson($info);
            exit;
        }
        else if ($msg == '' && $is_submit == 0) {
            //保存草稿
            $data = array(
                'pici_name' => $pici_name,
                'pici_start_time' => $pici_start_time,
                'pici_end_time' => $pici_end_time,
                'user_id' => $this->adminid,
                'operating_time' => $operating_time,
                'pici_norm_id' => $pici_norm_id,
                'expert_ids' => serialize(explode(',', $expert_ids)),
                'project_ids' => serialize(explode(',', $project_ids)),
                'mtype' => $mtype,
                'com_status' => 0
            );
            //如果id=0则是新增草稿，id大于0则为编辑草稿
            if ($id == 0) {
                foreach (explode(',', $project_ids) as $k => $v) {
                    //改变项目的网评状态(在流程模块主表里)
                    $rs = m('flow_bill')->getone("id=$v");
                    $tables = $rs['table'];
                    $mid = $rs['mid'];
                    if ($v != '') {
                        if ($mtype == 'project_start') {
                            m($tables)->update("is_wp=1", "id=$mid");
                        } else if ($mtype == 'project_end') {
                            m($tables)->update("is_wp_end=1", "id=$mid");
                        }
                    }
                }
                $reinfo = m('m_batch')->insert($data);
            } else {
                //草稿中上次存的申报项目在此次编辑中去除了，先把上次的全部设为未网评状态，后面再将此此次编辑的项目设为网评状态
                $re = m('m_batch')->getone("id=$id");
                $projectId = unserialize($re['project_ids']);
                //全部设为未网评状态
                foreach ($projectId as $v) {
                    $rs = m('flow_bill')->getone("id=$v");
                    $tables = $rs['table'];
                    $mid = $rs['mid'];
                    if ($mtype == 'project_start') {
                        m($tables)->update("is_wp=1", "id=$mid");
                    } else if ($mtype == 'project_end') {
                        m($tables)->update("is_wp_end=1", "id=$mid");
                    }
                }
                //将此此次编辑的项目设为编辑状态
                foreach (explode(',', $project_ids) as $k => $v) {
                    //改变项目的网评状态
                    if ($v != '') {
                        $rs = m('flow_bill')->getone("id=$v");
                        $tables = $rs['table'];
                        $mid = $rs['mid'];
                        if ($mtype == 'project_start') {
                            m($tables)->update("is_wp=1", "id=$mid");
                        } else if ($mtype == 'project_end') {
                            m($tables)->update("is_wp_end=1", "id=$mid");
                        }
                    }
                }
                $reinfo = m('m_batch')->update($data, "id=$id");
            }
            if ($reinfo) {
                $info = array('id' => $reinfo, 'mtype'=>$mtype,'success' => true, 'msg' => '保存成功');
            } else {
                $info = array('id' => $reinfo, 'mtype'=>$mtype,'success' => false, 'msg' => '保存失败');
            }
        } else {
            //$info =array('id' => '','success' => false,'msg' => $msg);
            exit($msg);
        }
        $this->returnjson($info);
    }


    /**
     * 追加项目
     */
    public function additemsAjax()
    {
        $pici_id = $this->post('id');//批次id
        $project_ids = $this->post('project_ids');//项目ids
        $operating_time = date('Y-m-d H:i:s', time());//操作时间

        $pici_info = m('m_batch')->getone("id=$pici_id");
        $old_project_ids = implode(',', unserialize($pici_info['project_ids']));//之前所选的项目ids
        $expert_ids = unserialize($pici_info['expert_ids']);
        $mtype = $pici_info['mtype'];

        if ($old_project_ids == $project_ids) {
            $this->returnjson(array('id' => '', 'success' => true, 'msg' => '本次保存无项目追加'));
        }

        $add_ids = str_replace($old_project_ids . ',', '', $project_ids);

        $reinfo = m('m_batch')->update(array(
            'project_ids' => serialize(explode(',', $project_ids)),
            'user_id' => $this->adminid,
            'operating_time' => $operating_time
        ), "id=$pici_id");

        //打分批次中的项目表pl_m_pxm_relation
        $pxmresult = '';
        foreach (explode(',', $add_ids) as $k => $v) {
            m($mtype)->update("is_wp=1", "id=$v");
            $pxmresult = m('m_pxm_relation')->insert(array(
                'pici_id' => $pici_id,
                'xid' => $v,
                'mtype' => $mtype
            ));
        }
        unset($k, $v);


        //打分批次中的项目得分表pl_m_pxmdf
        $pxmdfresult = '';
        foreach ($expert_ids as $expert_k => $expert_v) {
            foreach (explode(',', $add_ids) as $projetc_k => $projetc_v) {
                $pxmdfresult = m('m_pxmdf')->insert(array(
                    'pici_id' => $pici_id,
                    'uid' => $expert_v,
                    'xid' => $projetc_v,
                    'mtype' => $mtype
                ));
            }
            unset($projetc_k, $projetc_v);
        }
        unset($expert_k, $expert_v);

        $info = array();
        if ($reinfo && $pxmresult && $pxmdfresult) {
            //给网评专家们发送微信模板消息
            $data = array();
            $adminname = m('admin')->getone("id=" . $this->adminid)['name'];
            foreach ($expert_ids as $expert_k => $expert_v) {
                //获取专家信息
                $userinfo = m('admin')->getone("id=$expert_v");
                $articles = "追加后的网评项目包括以下项目:\n";//存储专家对应网评项目信息
                $project_num = 0;//评审项目个数
                foreach (explode(',', $project_ids) as $projetc_k => $projetc_v) {
                    $project_info = m($mtype)->getone("id=$projetc_v");
                    $project_num++;
                    //$articles .= $project_num."、".$project_info['project_name']."\n";
                }
                unset($projetc_k, $projetc_v);
                $articles = "网评项目数：{$project_num}个";
                $data['articles'][0] = array(
                    "title" => '操作员【' . $adminname . '】追加了' . count(explode(',', $add_ids)) . '个网评项目',
                    "description" => "详情请登录电脑端查看\n批次名称：" . $pici_info['pici_name'] . "\n评审时间：" . date('Y-m-d H:i', strtotime($pici_info['pici_start_time'])) . '至' . date('Y-m-d H:i', strtotime($pici_info['pici_end_time'])) . "\n" . $articles,
                    "url" => "",
                    "picurl" => ""
                );
                $logData = array(
                    'pici_id' => $pici_id,
                    'pici_name' => $pici_info['pici_name'],
                    'uid' => $userinfo['id'],
                    'uname' => $userinfo['name'],
                    'type' => 1
                );
                if ($userinfo['wx_openid'] != '') {
                    $flag = m('wxgzh:wxgzh')->doSend($userinfo['wx_openid'], '@all', '@all', 1, $data);
                    if ($flag->errcode == 0) {
                        //发送成功
                        $logData['remark'] = '发送成功';
                    } else {
                        //发送错误
                        $logData['remark'] = '发送错误：\r\n' . $flag->errmsg;
                    }
                } else {
                    //专家没有微信公众号的openid
                    $logData['remark'] = '发送失败：\r\n专家没有微信公众号的openid';
                }
                c('log')->marklog($logData);//记录发送消息信息
            }
            unset($expert_k, $expert_v);

            $info = array('id' => $reinfo, 'success' => true, 'msg' => '追加项目成功');
        } else {
            $info = array('id' => $reinfo, 'success' => false, 'msg' => '追加项目失败');
        }
        $this->returnjson($info);
    }


    /**
     * 获取网评信息（编辑草稿）
     */
    public function loadcommentAjax()
    {
        $id = $this->request('id');//网评信息id
        $data = m('m_batch')->getone("id=$id");
        //echo m('m_batch')->getLastSql();
        $mtype = $data['mtype'];
        $expertIds = $data['expert_ids'] = implode(',', unserialize($data['expert_ids']));
        $projectIds = $data['project_ids'] = implode(',', unserialize($data['project_ids']));
        $data['norm_name'] = m('m_dafen')->getone('id=' . $data['pici_norm_id'])['dafen_model_name'];
        //$data['expert_arr'] = m('admin')->getall("id in ($expertIds)");
        $data['expert_arr'] = m('expert_info')->getall("mid in ($expertIds)");


        /*$table = '`[Q]flow_bill` a left join `[Q]admin` b on a.uid=b.id left join `whole_projects` c on a.mid=c.id left join `[Q]flow_course` fc on fc.id=a.nowcourseid';
        $fields = 'a.table as mtype,a.modeid,a.optid,a.nowcheckname,a.status as bill_status,a.allcheckid,a.nowcheckid,a.nowcourseid,a.optid,b.name,b.deptname,c.id,c.num,c.project_name,c.status as cst,c.isturn,c.optname,c.project_head,c.project_apply_time,c.project_select,c.project_xingzhi,c.project_ku,c.isturn,fc.name as flowname,c.exigence_status,c.process_state,c.project_is_guidang,c.project_number,c.project_yushuan';
        $where = "c.id in ($projectIds) and a.table='$mtype'";
        $order = '';*/
        $table = '`[Q]flow_bill` a  left join `[Q]project_coursetask` b on a.mid=b.id';
        $fields = 'a.id,a.mid,a.optdt as apply_time,a.modename as project_select,a.sericnum as project_number,a.table as mtype,a.modeid,';
        $fields .= 'b.course_name as project_name,b.subject_classification,b.keyword_classification,b.specific_keywords';
        $where = "a.id in ($projectIds)";
        $order = '';

        $project_arr = $this->limitRows($table, $fields, $where, $order);
        unset($project_arr['sql'], $project_arr['total']);
        //echo $project_arr['sql'];
        $data['project_arr'] = $project_arr['rows'];
        if ($data) $data['pass'] = '';
        $arr['data'] = $data;
        $this->returnjson($arr);
    }

    /**
     * 编辑时删除上次编辑已选中的项目，该项目变为未网评
     * */
    public function changeWpStatusAjax()
    {
        $bill_id = $this->post('bill_id');
        $rs = m('flow_bill')->getone("id=$bill_id");
        $tables = $rs['table'];
        $mid = $rs['mid'];
        $rm = m($tables)->update("is_wp=0", "id=$mid");
        if ($rm) {
            echo json_encode(array(
                'data' => '',
                'success' => true,
                'msg' => '改变项目网评状态成功'
            ));
        }
    }


    /**
     * 网评项目列表
     */
    public function commentlistAjax()
    {
        $mtype = $this->getmtype();//项目类型
        $time_frame = $this->post('launch_time');//网评发起时间
        $pici_name = $this->post('pici_name');//批次名称
        $sub_where = '';

        //实训类的只能看实训类的，非实训类的只能看非实训类的
        $sub_where .= " and mtype='$mtype'";

        if (!empty($mtype)) $sub_where .= " and mtype='$mtype'";
        if ($time_frame != "") {//时间范围
            list($start_time, $end_time) = explode(',', $time_frame);
            $sub_where .= " and launch_time between '" . $start_time . "' and '" . $end_time . "'";
            unset($start_time, $end_time);
        }
        if (!empty($pici_name)) $sub_where .= " and pici_name like '%$pici_name%'";

        $table = '`[Q]m_batch`';
        $fields = '*';
        $where = "1=1 $sub_where";
        $order = 'operating_time desc';
        $arr = $this->limitRows($table, $fields, $where, $order);
        $arr['totalCount'] = $arr['total'];
        //计算项目个数和判断网评状态和操作
        foreach ($arr['rows'] as $k => $v) {
            $arr['rows'][$k]['project_num'] = count(unserialize($v['project_ids']));
            if ((int)$v['com_status'] == 0) $arr['rows'][$k]['com_status'] = '草稿';
            else if ((int)$v['com_status'] == 1) $arr['rows'][$k]['com_status'] = '进行中';
            else if ((int)$v['com_status'] == 2) $arr['rows'][$k]['com_status'] = '已完成';

            $arr['rows'][$k]['caoz'] = '';
            if ((int)$v['com_status'] != 0) {
                $arr['rows'][$k]['caoz'] .= '<a onclick="look(' . $v['id'] . ')">查看</a>';
            }
            if ((int)$v['com_status'] == 0) {
                $arr['rows'][$k]['caoz'] .= '<a onclick="edit(' . $v['id'] . ')">编辑草稿</a>';
            }
            if ((int)$v['com_status'] == 1) {
                $arr['rows'][$k]['caoz'] .= '<span style="padding:5px;">|</span>';
                $arr['rows'][$k]['caoz'] .= '<a onclick="additems(' . $v['id'] . ')">追加项目</a>';
//				$arr['rows'][$k]['caoz'].= '<span style="padding:5px;">|</span>';
//				$arr['rows'][$k]['caoz'].= '<a onclick="dow('.$v['id'].','.'3'.')">下载项目信息表</a>';

            }
            if ((int)$v['com_status'] == 2) {
                if ($v['mtype'] == 'project_sx_apply') {
                    $arr['rows'][$k]['caoz'] .= '<span style="padding:5px;">|</span>';
                    $arr['rows'][$k]['caoz'] .= '<a onclick="dow(' . $v['id'] . ',' . '0' . ')">下载汇总表</a>';
                    $arr['rows'][$k]['caoz'] .= '<span style="padding:5px;">|</span>';
                    $arr['rows'][$k]['caoz'] .= '<a onclick="dow(' . $v['id'] . ',' . '1' . ')">下载评分表</a>';
                    $arr['rows'][$k]['caoz'] .= '<span style="padding:5px;">|</span>';
                    $arr['rows'][$k]['caoz'] .= '<a onclick="dow(' . $v['id'] . ',' . '3' . ')">下载专家评分表</a>';
                } elseif ($v['mtype'] == 'project_apply') {
                    $arr['rows'][$k]['caoz'] .= '<span style="padding:5px;">|</span>';
                    $arr['rows'][$k]['caoz'] .= '<a onclick="dow(' . $v['id'] . ',' . '2' . ')">下载评分表</a>';
                }
            }
        }
        unset($k, $v);

        unset($arr['sql'], $arr['total']);
        //echo $arr['sql'];exit;
        if ($arr['totalCount'] == 0) exit('暂无数据');
        $this->returnjson($arr);
    }

    /*网评项目列表修改后*/
    public function comment_listAjax()
    {
        $mtype = $this->get('atype');//项目评审类型
        $time_frame = $this->post('launch_time');//网评发起时间
        $pici_name = $this->post('pici_name');//批次名称
        $pici_status = $this->post('pici_status');//评审状态
        $sub_where = '';
        $sub_where .= " and mtype='$mtype'";
        if (!empty($mtype)) $sub_where .= " and mtype='$mtype'";
        if ($time_frame != "") {//时间范围
            list($start_time, $end_time) = explode(',', $time_frame);
            $sub_where .= " and launch_time between '" . $start_time . "' and '" . $end_time . "'";
            unset($start_time, $end_time);
        }
        if (!empty($pici_name)) $sub_where .= " and pici_name like '%$pici_name%'";
        if ($pici_status == '0') {
            $sub_where .= " and com_status = '0'";
        } else if ($pici_status == '1') {
            $sub_where .= " and com_status = '1'";
        } else if ($pici_status == '2') {
            $sub_where .= " and com_status = '2'";
        }
        //判断批次是否已全部评审完
        $ks = m('m_batch')->getall('mtype = \'' . $mtype . '\'');
        foreach ($ks as $k => $h) {
            $is_fineshed = false;
            $ra = m('m_pxmdf')->getall('pici_id = ' . $ks[$k]['id']);
            $i = 0;
            foreach ($ra as $item) {
                if ($item['com_status'] == 1 || $item['com_status'] == 3) {
                    $i++;
                }
            }
            if ($i == count($ra)) {
                $is_fineshed = true;
            }
            if ($is_fineshed) {
                $rb = m('m_batch')->update(array('com_status' => 2), 'id=' . $ks[$k]['id']);
            }
        }
        $table = '`[Q]m_batch`';
        $fields = '*';
        $where = "1=1 $sub_where";
        $order = 'operating_time desc';
        $arr = $this->limitRows($table, $fields, $where, $order);

        $arr['totalCount'] = $arr['total'];
        //计算项目个数和判断网评状态和操作
        foreach ($arr['rows'] as $k => $v) {
            //批次评审时间
            $arr['rows'][$k]['pici_time'] = $v['pici_start_time'] . '~' . $v['pici_end_time'];

            $project_ids = unserialize($v['project_ids']);
            $new_project_ids = array();
            foreach ($project_ids as $i) {
                if ($i != '') {
                    array_push($new_project_ids, $i);
                }
            }
            //项目数量
            $arr['rows'][$k]['project_num'] = count($new_project_ids);
            //评审状态
            if ((int)$v['com_status'] == 0) $arr['rows'][$k]['com_status'] = '草稿';
            else if ((int)$v['com_status'] == 1) $arr['rows'][$k]['com_status'] = '进行中';
            else if ((int)$v['com_status'] == 2) $arr['rows'][$k]['com_status'] = '已完成';
            //相关操作 0草稿 1进行中 2已完成
            $arr['rows'][$k]['caoz'] = '';
            if ((int)$v['com_status'] != 0) {
                $arr['rows'][$k]['caoz'] .= '<a onclick="look(' . $v['id'] . ')">查看</a>';
            }
            if ((int)$v['com_status'] == 0) {
                $arr['rows'][$k]['caoz'] .= '<a onclick="edit(' . $v['id'] . ')">编辑草稿</a>';
            }
            if ((int)$v['com_status'] == 1) {
                //$arr['rows'][$k]['caoz'] .= '<a onclick="additems(' . $v['id'] . ')">追加项目</a>';
                //$arr['rows'][$k]['caoz'].= '<span style="padding:5px;">|</span>';
                //$arr['rows'][$k]['caoz'].= '<a onclick="dow('.$v['id'].','.'3'.')">下载项目信息表</a>';
            }
            if ((int)$v['com_status'] == 2) {
                if ($mtype=='project_end'){
                    $arr['rows'][$k]['caoz'] .= '<span style="padding:5px;">|</span>';
                    $arr['rows'][$k]['caoz'] .= '<a onclick="project_enddow('. $v['id'].',2)">小组评审表</a>';
                    $arr['rows'][$k]['caoz'] .= '<span style="padding:5px;">|</span>';
                    $arr['rows'][$k]['caoz'] .= '<a onclick="project_enddow('. $v['id'].',3)">评审结果表</a>';
                }else if ($mtype=='project_start'){
                    $arr['rows'][$k]['caoz'] .= '<span style="padding:5px;">|</span>';
                    $arr['rows'][$k]['caoz'] .= '<a onclick="project_startdow('. $v['id'].',0)">小组评审表</a>';
                    $arr['rows'][$k]['caoz'] .= '<span style="padding:5px;">|</span>';
                    $arr['rows'][$k]['caoz'] .= '<a onclick="project_startdow('. $v['id'].',1)">评审结果表</a>';
                }
                /* if ($v['mtype'] == 'project_sx_apply') {
                     $arr['rows'][$k]['caoz'] .= '<span style="padding:5px;">|</span>';
                     $arr['rows'][$k]['caoz'] .= '<a onclick="dow(' . $v['id'] . ',' . '0' . ')">下载汇总表</a>';
                     $arr['rows'][$k]['caoz'] .= '<span style="padding:5px;">|</span>';
                     $arr['rows'][$k]['caoz'] .= '<a onclick="dow(' . $v['id'] . ',' . '1' . ')">下载评分表</a>';
                     $arr['rows'][$k]['caoz'] .= '<span style="padding:5px;">|</span>';
                     $arr['rows'][$k]['caoz'] .= '<a onclick="dow(' . $v['id'] . ',' . '3' . ')">下载专家评分表</a>';
                 } elseif ($v['mtype'] == 'project_apply') {
                     $arr['rows'][$k]['caoz'] .= '<span style="padding:5px;">|</span>';
                     $arr['rows'][$k]['caoz'] .= '<a onclick="dow(' . $v['id'] . ',' . '2' . ')">下载评分表</a>';
                 }*/
            }
        }
        unset($k, $v);

        unset($arr['sql'], $arr['total']);
        //echo $arr['sql'];exit;
        //if ($arr['totalCount'] == 0) exit('暂无数据');
        $this->returnjson($arr);
    }

    /**
     * 专家查看属于自己的网评批次列表(立项评审)
     */
    public function startlistAjax()
    {
        $uid = $this->adminid;
        $mtype = $this->get('atype');//项目类型
        $time_frame = $this->post('launch_time');//网评发起时间
        $pici_name = $this->post('pici_name');//批次名称
        $pici_status = $this->post('pici_status');//评审状态
        $sub_where = " and b.uid = '$uid'";
        if (!empty($mtype)) $sub_where .= " and mtype='$mtype'";
        if ($time_frame != "") {//时间范围
            list($start_time, $end_time) = explode(',', $time_frame);
            $sub_where .= " and launch_time between '" . $start_time . "' and '" . $end_time . "'";
            unset($start_time, $end_time);
        }
        if (!empty($pici_name)) $sub_where .= " and pici_name like '%$pici_name%'";
        if ($pici_status == '1') {
            $sub_where .= " and com_status = '1'";
        } else if ($pici_status == '2') {
            $sub_where .= " and com_status = '2'";
        }

        $table = '`[Q]m_batch` a left join [Q]m_pua_relation b on a.id = b.pici_id';
        $fields = '*';
        $where = "com_status<>0 $sub_where";
        $order = 'operating_time desc';
        $arr = $this->limitRows($table, $fields, $where, $order);
        $arr['totalCount'] = $arr['total'];
        //计算项目个数和判断网评状态和操作
        foreach ($arr['rows'] as $k => $v) {
            //批次评审时间
            $arr['rows'][$k]['pici_time'] = $v['pici_start_time'] . '~' . $v['pici_end_time'];

            $project_ids = unserialize($v['project_ids']);
            $new_project_ids = array();
            foreach ($project_ids as $i) {
                if ($i != '') {
                    array_push($new_project_ids, $i);
                }
            }
            //项目数量
            $arr['rows'][$k]['project_num'] = count($new_project_ids);

            //评审状态 给出当前用户个人在当前批次的评审状态和相应的操作
            $rs = m('m_pxmdf')->getall('pici_id = ' . $v['pici_id'] . ' and uid=' . $this->adminid);
            $alreadyCount = 0;
            $arr['rows'][$k]['caoz'] = '';
            foreach ($rs as $item) {
                if ($item['com_status'] == 1) {
                    $alreadyCount++;
                }
            }
            if ($alreadyCount == count($rs)) {
                $arr['rows'][$k]['com_status'] = '已完成';
                $arr['rows'][$k]['caoz'] .= '<a onclick="look_startlist(' . $v['pici_id'] . ')">查看</a>';
            } else {
                $arr['rows'][$k]['com_status'] = '评审中';
                $arr['rows'][$k]['caoz'] .= '<a onclick="look_startlist(' . $v['pici_id'] . ')">评审</a>';
            }
            /*            //评审状态
            if ((int)$v['com_status'] == 0) $arr['rows'][$k]['com_status'] = '草稿';
            else if ((int)$v['com_status'] == 1) $arr['rows'][$k]['com_status'] = '评审中';
            else if ((int)$v['com_status'] == 2) $arr['rows'][$k]['com_status'] = '已完成';
            //相关操作 0草稿 1进行中 2已完成
            $arr['rows'][$k]['caoz'] = '';
            if ((int)$v['com_status'] == 2) {
                $arr['rows'][$k]['caoz'] .= '<a onclick="look(' . $v['pici_id'] . ')">查看</a>';
            }
            if ((int)$v['com_status'] == 1) {
                $arr['rows'][$k]['caoz'] .= '<a onclick="look(' . $v['pici_id'] . ')">评审</a>';
            }*/
        }
        unset($k, $v);

        unset($arr['sql'], $arr['total']);
        $this->returnjson($arr);
    }

    /**
     * 专家查看属于自己的网评批次列表(结项评审)
     * */
    public function endlistAjax()
    {
        $uid = $this->adminid;
        $mtype = $this->get('atype');//项目类型
        $time_frame = $this->post('launch_time');//网评发起时间
        $pici_name = $this->post('pici_name');//批次名称
        $pici_status = $this->post('pici_status');//评审状态
        $sub_where = " and b.uid = '$uid'";
        $sub_where .= " and mtype='$mtype'";
        if (!empty($mtype)) $sub_where .= " and mtype='$mtype'";
        if ($time_frame != "") {//时间范围
            list($start_time, $end_time) = explode(',', $time_frame);
            $sub_where .= " and launch_time between '" . $start_time . "' and '" . $end_time . "'";
            unset($start_time, $end_time);
        }
        if (!empty($pici_name)) $sub_where .= " and pici_name like '%$pici_name%'";
        if ($pici_status == '1') {
            $sub_where .= " and com_status = '1'";
        } else if ($pici_status == '2') {
            $sub_where .= " and com_status = '2'";
        }

        $table = '`[Q]m_batch` a left join [Q]m_pua_relation b on a.id = b.pici_id';
        $fields = '*';
        $where = "com_status<>0  $sub_where";
        $order = 'operating_time desc';
        $arr = $this->limitRows($table, $fields, $where, $order);
        $arr['totalCount'] = $arr['total'];
        //计算项目个数和判断网评状态和操作
        foreach ($arr['rows'] as $k => $v) {
            //批次评审时间
            $arr['rows'][$k]['pici_time'] = $v['pici_start_time'] . '~' . $v['pici_end_time'];

            $project_ids = unserialize($v['project_ids']);
            $new_project_ids = array();
            foreach ($project_ids as $i) {
                if ($i != '') {
                    array_push($new_project_ids, $i);
                }
            }
            //项目数量
            $arr['rows'][$k]['project_num'] = count($new_project_ids);

            //评审状态 给出当前用户个人在当前批次的评审状态和相应的操作
            $rs = m('m_pxmdf')->getall('pici_id = ' . $v['pici_id'] . ' and uid=' . $this->adminid);
            $alreadyCount = 0;
            $arr['rows'][$k]['caoz'] = '';
            foreach ($rs as $item) {
                if ($item['com_status'] == 1) {
                    $alreadyCount++;
                }
            }
            if ($alreadyCount == count($rs)) {
                $arr['rows'][$k]['com_status'] = '已完成';
                $arr['rows'][$k]['caoz'] .= '<a onclick="look(' . $v['pici_id'] . ')">查看</a>';
            } else {
                $arr['rows'][$k]['com_status'] = '评审中';
                $arr['rows'][$k]['caoz'] .= '<a onclick="look(' . $v['pici_id'] . ')">评审</a>';
            }
        }
        unset($k, $v);

        unset($arr['sql'], $arr['total']);
        $this->returnjson($arr);
    }

    /**
     * 专家查看属于自己的网评批次项目列表(立项评审)
     */
    public function startpciglistAjax()
    {
        $sub_where = '';
        $pici_id = $this->post('pici_id');
        $project_name = trim($this->post('project_name'));//批次名称
        $pinshen_status = $this->post('pinshen_status');//评审状态
        $mtype = $this->post('mtype');//评审类型project_start:立项评审,project_end:结项评审
        $uid = $this->adminid;
        $sub_where .= ' and e.uid=' . $uid;
        if (!empty($project_name)) $sub_where .= " and c.course_name like '%$project_name%'";
        if ($pinshen_status == '0') {
            //待提交 有草稿
            $sub_where .= " and e.com_status = '0'";
        } else if ($pinshen_status == '1') {
            $sub_where .= " and e.com_status = '1'";
        } else if ($pinshen_status == '2') {
            //待评审 没草稿
            $sub_where .= " and e.com_status = '2'";
        }
        if ($mtype == 'project_start') {
            $sub_where .= "   and e.pingshen_state = '1'";
      }   /*else if ($mtype == 'project_end') {
            $sub_where .= " and a.pingshen_state = '2'  and e.pingshen_state = '2'";
        }*/
       // $table = '`[Q]m_pxm_relation` a left join `[Q]flow_bill` b on b.id = a.xid left join `[Q]project_coursetask` c on b.mid=c.id left join `[Q]m_batch` d on a.pici_id=d.id left join `[Q]m_pxmdf` e on b.id = e.xid ';
        $table = '`[Q]m_pxmdf` e left join `[Q]flow_bill` b on b.id = e.xid left join `[Q]project_coursetask` c on b.mid=c.id left join `[Q]m_batch` d on e.pici_id=d.id ';
        $fields = 'e.pici_id,e.xid,d.com_status,';
        $fields .= 'b.modename,b.sericnum,b.table,b.id as bill_id,b.mid,';
        $fields .= 'c.subject_classification,c.keyword_classification,c.specific_keywords,c.course_name as project_name,c.leader_name,';
        $fields .= 'd.id as baid,d.com_status,e.user_zongfen,e.com_status as pingshen_status,e.model,e.uid';
        $where = "e.pici_id=" . $pici_id . "$sub_where";
        $order = '';
        $this->getlist($table, $fields, $where, $order);
    }

    public function startpciglistafter($table, $rows)
    {
        foreach ($rows as $k => $rs) {
            $rows[$k]['caoz'] = '';
            /*    if ($rows[$k]['pingshen_status'] == '0'){
                    $rows[$k]['pingshen_status'] = '待评审';
                    $rows[$k]['caoz'] .= '<a onclick="comment(\'' . $rs['baid'] . '\',' .'\'' .$rs['table'] .'\''. ',\'' . $rs['mid'] . '\')">评审</a>';

                }else if ($rows[$k]['pingshen_status'] == '1'){
                    $rows[$k]['pingshen_status'] = '未提交';
                    $rows[$k]['caoz'] .= '<a onclick="comment_capgao(\'' . $rs['baid'] . '\',' .'\'' .$rs['table'] .'\''. ',\'' . $rs['mid'] . '\')">查看(待提交)</a>';

                }else if ($rows[$k]['pingshen_status'] == '2'){
                    $rows[$k]['pingshen_status'] = '已提交';
                    $rows[$k]['caoz'] .= '<a onclick="look(\'' . $rs['baid'] . '\',' .'\'' .$rs['table'] .'\''. ',\'' . $rs['mid'] . '\')">查看</a>';

                }*/
            /*原评审打分*/
            if ((int)$rows[$k]['pingshen_status'] == 0 && $rows[$k]['model'] == null) {
                //没有草稿
                $rows[$k]['caoz'] .= '<a onclick="startpcig_comment(\'' . $rs['baid'] . '\',' . '\'' . $rs['table'] . '\'' . ',\'' . $rs['mid'] . '\',\'' . $rs['uid'] . '\')">评审</a>';
                $rows[$k]['pingshen_status'] = '待评审';
            } else if ((int)$rows[$k]['pingshen_status'] == 2 && $rows[$k]['model'] != null) {
                //编辑草稿
                $rows[$k]['caoz'] .= '<a onclick="comment_capgao(\'' . $rs['baid'] . '\',' . '\'' . $rs['table'] . '\'' . ',\'' . $rs['mid'] . '\',\'' . $rs['pingshen_status'] . '\')">评审</a>';
                $rows[$k]['pingshen_status'] = '待提交';
            } else if ((int)$rows[$k]['pingshen_status'] == 1) {
                $rows[$k]['caoz'] .= '<a onclick="startpcig_look(\'' . $rs['baid'] . '\',' . '\'' . $rs['table'] . '\'' . ',\'' . $rs['mid'] . '\')">查看</a>';
                $rows[$k]['pingshen_status'] = '已提交';
            }
        }
        return $rows;
    }

    /**
     * 专家查看属于自己的网评批次项目列表(结项评审)
     */
    public function endpciglistAjax()
    {
        $sub_where = '';
        $pici_id = $this->post('pici_id');
        $project_name = trim($this->post('project_name'));//批次名称
        $pinshen_status = $this->post('pinshen_status');//评审状态
        $mtype = $this->post('mtype');//评审类型project_start:立项评审,project_end:结项评审
        $uid = $this->adminid;
        $sub_where .= ' and e.uid=' . $uid;
        if (!empty($project_name)) $sub_where .= " and c.course_name like '%$project_name%'";
        if ($pinshen_status == '0') {
            //待提交 有草稿
            $sub_where .= " and e.com_status = '0'";
        } else if ($pinshen_status == '1') {
            $sub_where .= " and e.com_status = '1'";
        } else if ($pinshen_status == '2') {
            //待评审 没草稿
            $sub_where .= " and e.com_status = '2'";
        }
        if ($mtype == 'project_start') {
            $sub_where .= "   and e.pingshen_state = '1'";
        }   /*else if ($mtype == 'project_end') {
            $sub_where .= " and a.pingshen_state = '2'  and e.pingshen_state = '2'";
        }*/
        // $table = '`[Q]m_pxm_relation` a left join `[Q]flow_bill` b on b.id = a.xid left join `[Q]project_coursetask` c on b.mid=c.id left join `[Q]m_batch` d on a.pici_id=d.id left join `[Q]m_pxmdf` e on b.id = e.xid ';
        $table = '`[Q]m_pxmdf` e left join `[Q]flow_bill` b on b.id = e.xid left join `[Q]project_coursetask` c on b.mid=c.id left join `[Q]m_batch` d on e.pici_id=d.id ';
        $fields = 'e.pici_id,e.xid,d.com_status,';
        $fields .= 'b.modename,b.sericnum,b.table,b.id as bill_id,b.mid,';
        $fields .= 'c.subject_classification,c.keyword_classification,c.specific_keywords,c.course_name as project_name,c.leader_name,';
        $fields .= 'd.id as baid,d.com_status,e.user_zongfen,e.com_status as pingshen_status,e.model,e.uid';
        $where = "e.pici_id=" . $pici_id . "$sub_where";
        $order = '';
        $this->getlist($table, $fields, $where, $order);
    }

    public function endpciglistafter($table, $rows)
    {
        foreach ($rows as $k => $rs) {
            $rows[$k]['caoz'] = '';
            if ((int)$rows[$k]['pingshen_status'] == 0 && $rows[$k]['model'] == null) {
                //没有草稿
                $rows[$k]['caoz'] .= '<a onclick="endpcig_comment(\'' . $rs['baid'] . '\',' . '\'' . $rs['table'] . '\'' . ',\'' . $rs['mid'] . '\',\'' . $rs['uid'] . '\')">评审</a>';
                $rows[$k]['pingshen_status'] = '待评审';
            } else if ((int)$rows[$k]['pingshen_status'] == 2 && $rows[$k]['model'] != null) {
                //编辑草稿
                $rows[$k]['caoz'] .= '<a onclick="comment_capgao(\'' . $rs['baid'] . '\',' . '\'' . $rs['table'] . '\'' . ',\'' . $rs['mid'] . '\',\'' . $rs['pingshen_status'] . '\')">评审</a>';
                $rows[$k]['pingshen_status'] = '待提交';
            } else if ((int)$rows[$k]['pingshen_status'] == 1) {
                $rows[$k]['caoz'] .= '<a onclick="endpcig_look(\'' . $rs['baid'] . '\',' . '\'' . $rs['table'] . '\'' . ',\'' . $rs['mid'] . '\')">查看</a>';
                $rows[$k]['pingshen_status'] = '已提交';
            }
        }
        return $rows;
    }

    /**
     * 专家列表
     */
    public function expertdataAjax()
    {
        $sub_where = '';
        $expert_ids = $this->post('expert_ids');//专家ids
        $expert_subject = $this->post('expert_subject');//学科分类
        $expert_position = $this->post('expert_position');//职务/职称
        $research_direction = $this->post('research_direction');//研究方向
        $company = $this->post('company');//关联单位

        if ($expert_ids != '') {
            $sub_where .= " and mid in ($expert_ids)";
        }
        if ($expert_subject != '') {
            $sub_where .= " and graduate_project ='" . $expert_subject . "'";
        }
        if ($expert_position != '') {
        }
        if ($research_direction != '') {
            $sub_where .= " and research_direction ='" . $research_direction . "'";
        }
        if ($company != '') {
        }


        $table = '`[Q]expert_info`';
        $fields = '*';
        $where = "is_expert=1  $sub_where";
        $order = 'id desc';
        $arr = $this->limitRows($table, $fields, $where, $order);
        $arr['totalCount'] = $arr['total'];
        unset($arr['sql'], $arr['total']);
        foreach ($arr['rows'] as $k =>$v){
            //扣罚次数
            $rb = m('penalty_record')->getall('uid = '.$v['mid'].' and status = 1');
            $koufaCount = count($rb);
            $arr['rows'][$k]['koufaCount'] = $koufaCount;
        }
        //echo $arr['sql'];exit;
        //if ($arr['totalCount'] == 0) exit('暂无数据');
        $this->returnjson($arr);
    }


    /**
     * 指标信息返回
     */
    public function getnormAjax()
    {
        $norm_id = $this->rock->request('norm_id');//指标id
        $norm_info = m('m_dafen')->getone('id=' . $norm_id, $fields = 'dafen_model_name as name,dafen_model_num as num,mtype');
        $this->returnjson($norm_info);
    }

    /**
     * 提交专家打分(保存草稿)
     */
    public function score_modelAjax()
    {
        $pici_id = $this->post('pici_id');//批次ID
        $mid = $this->post('mid');//项目ID
        $mtype = $this->post('mtype');//项目模块
        $pinshen_type = $this->post('type');//评审类型project_start立项评审project_end结项评审
        $datafrom = $this->post('datafrom');
        $com_status = $this->post('com_status');
        $review_opinion = $this->post('review_opinion');//立项评审意见
        $review_opinion_end = $this->post('review_opinion_end');//结项评审意见
        $level_suggest = $this->post('level_suggest');//结项评审等级建议
        $publish_suggest = $this->post('publish_suggest');//结项评审出版建议
        $uid = $this->adminid;

        $pic_message = m('m_batch')->getone('id=' . $pici_id . ' and com_status=1');//查找批次
        //echo m('m_batch')->getLastSql();exit;
        $expert = m('m_pua_relation')->getone('pici_id=' . $pici_id . ' and uid=' . $uid);//查找当前用户是否在该批次中
        //$expert_pxmdf = m('m_pxmdf')->getone('pici_id=' . $pici_id . ' and uid=' . $uid . ' and xid=' . $mid . ' and mtype=\'' . $mtype . '\'');//查找当前用户在当前项目中的打分情况
        //查询出申报项目在flow_bill表中的id字段
        $urs = $this->db->getone("xinhu_flow_bill", "mid=" . $mid . " and `table`='" . $mtype . "'");
        $expert_pxmdf = m('m_pxmdf')->getone('pici_id=' . $pici_id . ' and uid=' . $uid . ' and xid=' . $urs['id'] . '');//查找当前用户在当前项目中的打分情况

        if (empty($pic_message)) {
            //批次ID错误
            $this->returnjson(array('success' => false, 'data' => '', 'msg' => '批次ID参数不能为空', 'code' => '000001'));
        }

        switch ($com_status) {
            case 0:
                break;
            case 1:
                break;
            case 2:
                break;
            default:
                $this->returnjson(array('success' => false, 'data' => '', 'msg' => 'com_status参数错误，非法操作', 'code' => '000001'));
        }

        if (empty($mid)) {
            //项目ID错误
            $this->returnjson(array('success' => false, 'data' => '', 'msg' => '项目ID参数不能为空', 'code' => '000002'));
        }

        if (empty($mtype)) {
            //申报类型错误
            $this->returnjson(array('success' => false, 'data' => '', 'msg' => '申报类型参数不能为空', 'code' => '000003'));
        }

        if (empty($datafrom) && $com_status == 1) {
            //指标分数错误
            $this->returnjson(array('success' => false, 'data' => '', 'msg' => '指标分数不能为空', 'code' => '000004'));
        }

        if (empty($expert) || empty($expert_pxmdf)) {
            //你无权对改项目网评
            $this->returnjson(array('success' => false, 'data' => '', 'msg' => '无权对改项目网评', 'code' => '0000X1'));
        }

        if ($expert_pxmdf['model'] && $expert_pxmdf['com_status'] == 1) {
            //请勿重复提交数据
            $this->returnjson(array('success' => false, 'data' => '', 'msg' => '请勿重复提交数据', 'code' => '000005'));
        }

        if ($pic_message['pici_start_time'] > date('Y-m-d H:i:s', time())) {
            //未开始
            $this->returnjson(array('success' => false, 'data' => '', 'msg' => '网评未开始', 'code' => '000006'));
        }

        if ($pic_message['pici_end_time'] < date('Y-m-d H:i:s', time())) {
            //已结束
            $this->returnjson(array('success' => false, 'data' => '', 'msg' => '网评已结束', 'code' => '000007'));
        }

        $json_model = $pic_message['model'];
        $arr_model = json_decode($json_model, true);
        $datafrom_Arr = explode(',', $datafrom);
        $user_zongfen = 0;

        //判断专辑提交的指标和数据库指标的数字维度
        if (sizeof($arr_model['info']) == sizeof($datafrom_Arr)) {
            foreach ($arr_model['info'] as $k => $value) {
                if ($value['option_fenzhi'] >= $datafrom_Arr[$k]) {
                    $arr_model['info'][$k]['user_dafen'] = $datafrom_Arr[$k];
                    $user_zongfen = $user_zongfen + $datafrom_Arr[$k];
                } else {
                    $this->returnjson(array('success' => false, 'data' => '', 'msg' => '提交分数与指标不一致', 'code' => '000008'));
                }
            }
            $arr_model['zongfen'] = $user_zongfen;
            $arr_model['com_status'] = $com_status;
        }

        $arr_user['model'] = addslashes(json_encode($arr_model));
        $arr_user['user_zongfen'] = $user_zongfen;
        $arr_user['operating_time'] = date('Y-m-d H:i:s', time());
        $arr_user['com_status'] = $com_status;
        if ($pinshen_type == 'project_end') {
            $arr_user['review_opinion_end'] = $review_opinion_end;
            $arr_user['level_suggest'] = $level_suggest;
            $arr_user['publish_suggest'] = $publish_suggest;
        } else if ($pinshen_type == 'project_start') {
            $arr_user['review_opinion'] = $review_opinion;
        }
        $type = $urs['table'];
        $new_id = $urs['id'];
        $retuen_info = m('m_pxmdf')->update($arr_user, "pici_id=$pici_id and xid=$new_id and mtype='$type' and uid=$uid");
        if ($retuen_info) {
            if ($com_status == 2) {
                $this->returnjson(array('success' => true, 'data' => '', 'msg' => '保存成功', 'code' => '100001'));
            } else {
                $this->returnjson(array('success' => true, 'data' => '', 'msg' => '提交成功', 'code' => '100000'));
            }
        } else {
            $this->returnjson(array('success' => false, 'data' => '', 'msg' => '提交失败', 'code' => '000000'));
        }
    }

    /**
     * 批量提交专家的打分
     */
    public function batch_scoreAjax()
    {
        $project_ids = $_REQUEST['project_id'];
        $pici_id = $this->post('pici_id');
        $types = $this->post('types');//评审类型 project_start立项评审,project_end结项评审

        foreach ($project_ids as $v) {
            //$rs = m('m_pxmdf')->getone('uid=' . $this->adminid . ' and xid=' . $v.'');
            $rs = m('m_pxmdf')->getone(" uid = $this->adminid and xid = $v and pici_id = $pici_id");
            if (!$rs['model']) {
                $this->returnjson(array('success' => false, 'data' => '', 'msg' => '部分勾选的项目未评审', 'code' => '000001'));
            } else {
                $arr = array('com_status' => '1');
                $re = m('m_pxmdf')->update($arr, 'uid=' . $this->adminid . ' and xid=' . $v);
                if (!$re) {
                    $this->returnjson(array('success' => false, 'data' => '', 'msg' => '评审提交失败', 'code' => '00000'));
                } else {
                    //判断该批次是否所有专家都已提交评分
                    $rba = m('m_pxmdf')->getall("pici_id = $pici_id");
                    $counts = count($rba);
                    $alreadyCounts = 0;
                    foreach ($rba as $item) {
                        if ($item['com_status'] == 1) {
                            $alreadyCounts++;
                        }
                    }
                    //全部提交评分后进入下一环节
                    if ($alreadyCounts == $counts) {
                        if ($types == 'project_start') {
                            //将评审批次中已经进入提交结项报告环节的剔除，避免重复
                            $tp = m('flow_bill')->getone('id = ' . $v);
                            if ($tp['nowcourseid'] == 108) {
                                //根据nowcourseid获取当前流程环节的审核人以及审核人名字
                                $bill_course = m('flow_course')->getone('id=99');
                                $log_course = m('flow_course')->getone('id=108');
                                $bill_arr = array(
                                    'nstatustext' => '待' . $bill_course['checktypename'] . '处理',
                                    'nowcourseid' => '99',
                                    'nowcheckid' => $bill_course['checktypeid'],
                                    'nowcheckname' => $bill_course['checktypename'],
                                    'updt' => date('Y-m-d H:i:s'),
                                );
                                $rm = m('flow_bill')->getone('id=' . $v);
                                $log_arr = array(
                                    'table' => 'project_coursetask',
                                    'name' => $log_course['name'],
                                    'mid' => $rm['mid'],
                                    'courseid' => '108',
                                    'statusname' => '通过',
                                    'status' => '1',
                                    'optdt' => date('Y-m-d H:i:s'),
                                    'ip' => '::1',
                                    'web' => 'chrome',
                                    'checkname' => $log_course['checktypename'],
                                    'checkid' => $log_course['checktypeid'],
                                    'modeid' => $log_course['setid'],
                                    'color' => 'black',
                                    'valid' => '1',
                                    'step' => '1',
                                );
                                //评审打分提交后立项评审即通过，修改flow_bill当前项目的所处流程环节，flow_log添加一条记录
                                $rb = m('flowbill')->update($bill_arr, 'id=' . $v);
                                $rg = m('flow_log')->insert($log_arr);
                            }
                        } else if ($types == 'project_end') {
                            //将评审批次中已经进入项目归档环节的剔除，避免重复
                            $tg = m('flow_bill')->getone('id = ' . $v);
                            if ($tg['nowcourseid'] == 103) {
                                //结项评审中专家的等级建议中如果有两个或者以上不合格则默认不通过
                                $error_count = 0;
                                $flowbill_id = 0;
                                foreach ($rba as $item) {
                                    $flowbill_id = $item['xid'];
                                    if ($item['level_suggest'] == 1) {
                                        $error_count++;
                                    }
                                }
                                $nextCourse = m('flow_course')->getone('id=91');//项目归档
                                $nowCourse = m('flow_course')->getone('id=103');//结项评审
                                //获取流程模块表里的id(mid)
                                $rbill = m('flow_bill')->getone("id = $flowbill_id");
                                $arrs = array();
                                $log_arrs = array();
                                $mode_arrs = array();
                                $relation_arrs = array();
                                if ($error_count >= 2) {
                                    //审核不通过
                                    $arrs = array(
                                        'nstatustext' => '' . $nowCourse['checktypename'] . '审核不通过',
                                        'nowcourseid' => '103',
                                        'nstatus' => '5',
                                        'status' => '5',
                                        'nowcheckid' => $nowCourse['checktypeid'],
                                        'nowcheckname' => $nowCourse['checktypename'],
                                        'updt' => date('Y-m-d H:i:s'),
                                        'checksm' => '不通过'
                                    );
                                    $log_arrs = array(
                                        'table' => $rbill['table'],
                                        'name' => $nowCourse['name'],
                                        'mid' => $rbill['mid'],
                                        'courseid' => '103',
                                        'statusname' => '不通过',
                                        'status' => '3',
                                        'optdt' => date('Y-m-d H:i:s'),
                                        'explain' => '不通过',
                                        'ip' => '::1',
                                        'web' => 'chrome',
                                        'checkname' => $nowCourse['checktypename'],
                                        'checkid' => $nowCourse['checktypeid'],
                                        'modeid' => $nowCourse['setid'],
                                        'color' => 'black',
                                        'valid' => '1',
                                        'step' => '1',
                                    );
                                    $mode_arrs = array('status' => 5);
                                    $relation_arrs = array('com_status' => 4);
                                } else {
                                    //审核通过
                                    $arrs = array(
                                        'nstatustext' => '待' . $nextCourse['checktypename'] . '处理',
                                        'nowcourseid' => '91',
                                        'nowcheckid' => $nextCourse['checktypeid'],
                                        'nowcheckname' => $nextCourse['checktypename'],
                                        'updt' => date('Y-m-d H:i:s'),
                                        'checksm' => '通过'
                                    );
                                    $log_arrs = array(
                                        'table' => $rbill['table'],
                                        'name' => $nowCourse['name'],
                                        'mid' => $rbill['mid'],
                                        'courseid' => '103',
                                        'statusname' => '通过',
                                        'status' => '1',
                                        'explain' => '通过',
                                        'optdt' => date('Y-m-d H:i:s'),
                                        'ip' => '::1',
                                        'web' => 'chrome',
                                        'checkname' => $nowCourse['checktypename'],
                                        'checkid' => $nowCourse['checktypeid'],
                                        'modeid' => $nowCourse['setid'],
                                        'color' => 'black',
                                        'valid' => '1',
                                        'step' => '1',
                                    );
                                    $relation_arrs = array('com_status' => 4);
                                }
                                $tables = $rbill['table'];
                                $mids = $rbill['mid'];
                                $rs = m('flow_bill')->update($arrs, 'mid=' . $mids . ' and `table`=\'' . $tables . '\'');  //更改flow_bill表
                                $rm = m('flow_log')->insert($log_arrs);//流程日志表flow_log表新增一条流程日志
                                $rc = m('m_pxm_relation')->update($relation_arrs, "pici_id = $pici_id and xid = $flowbill_id and mtype='" . $rbill['table'] . "'");//改变m_pxmdf_relation表里com_status评审状态
                                if ($mode_arrs) {
                                    $rg = m('' . $rbill['table'])->update($mode_arrs, 'id = ' . $rbill['mid']);//改变流程模块主表单据的状态
                                    if ($rs && $rm && $rc && $rg) {
                                        echo json_encode(
                                            array('success' => true, 'code' => 200, 'msg' => '操作成功！')
                                        );
                                    } else {
                                        echo json_encode(
                                            array('success' => true, 'code' => 201, 'msg' => '操作失败！')
                                        );
                                    }
                                } else {
                                    if ($rs && $rm && $rc) {
                                        echo json_encode(
                                            array('success' => true, 'code' => 200, 'msg' => '操作成功')
                                        );
                                    } else {
                                        echo json_encode(
                                            array('success' => true, 'code' => 201, 'msg' => '操作失败')
                                        );
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }


    /**
     * 获取专家收款账户信息
     * */
    public function user_bankInfoAjax()
    {
        $urs = m('admin')->getone('id=' . $this->adminid);
        if ($urs) {
            $this->requestsuccess($urs);
        } else {
            $this->requesterror('获取失败');
        }
    }


    /*网评管理中获取项目
	 * 根据picid获取对应指标信息
	 *
	 */
    public function pici_modelAjax()
    {

        $pici_id = $this->post('pici_id');

        $pic_info = m('m_batch')->getone('id=' . $pici_id);

        $arr = $pic_info['model'];
        //	$arr='{"name":"广东科学技术职业学院立项建设评审指标体系","num":"1","info":[{"option_msg":"基地类型","option_fenzhi":"15","option_range":"","sort":"1","info":[{"option_msg":"具有专业发展和技术先进性的新建专业项目","option_fenzhi":null,"minscore":"1","maxscore":"5","option_range":["1","5"],"sort":"0"},{"option_msg":"品牌专业等重大专业建设项目以及省级实训基地、省公共实训中心、重点实验室等重点建设项目","option_fenzhi":null,"minscore":"6","maxscore":"10","option_range":["6","10"],"sort":"0"}]},{"option_msg":"建设资金及预算","option_fenzhi":"20","option_range":"","sort":"2","info":[{"option_msg":"资金投入不够明确，预算安排不够合理。","option_fenzhi":null,"minscore":"1","maxscore":"4","option_range":["1","4"],"sort":"0"},{"option_msg":"已有项目专项建设资金；资金投入明确，有详细的计划和具体的实施办法，预算安排合理。","option_fenzhi":null,"minscore":"10","maxscore":"15","option_range":["10","15"],"sort":"0"}]},{"option_msg":"建设场地需求规划","option_fenzhi":"10","option_range":"","sort":"3","info":[{"option_msg":"未有建设场地或不明确。","option_fenzhi":null,"minscore":"1","maxscore":"2","option_range":["1","2"],"sort":"0"},{"option_msg":"已有建设场地，规划合理。","option_fenzhi":null,"minscore":"3","maxscore":"5","option_range":["3","5"],"sort":"0"}]},{"option_msg":"建设依据","option_fenzhi":"10","option_range":"","sort":"4","info":[{"option_msg":"不符合品牌专业等重点专业建设发展的要求，实践教学任务不明确。没有前瞻性，与区域产业发展需求和\\r\\n\\r\\n   \\r\\n\\r\\n行业发展趋势结合度不高。","option_fenzhi":null,"minscore":"1","maxscore":"4","option_range":["1","4"],"sort":"0"},{"option_msg":"符合品牌专业等重点专业建设发展的要求，符合专业教学计划于大纲的要求，实践教学任务明确。项目建\\r\\n\\r\\n   \\r\\n\\r\\n设有一定的前瞻性，能与区域产业发展需求和行业发展趋势结合。","option_fenzhi":null,"minscore":"1","maxscore":"2","option_range":["1","2"],"sort":"0"},{"option_msg":"符合品牌专业等重点专业建设发展的要求，符合专业教学计划于大纲的要求，实践教学任务明确。项目具\\r\\n\\r\\n   \\r\\n\\r\\n有前瞻性，紧密解饿区域产业发展需求和兴业发展趋势","option_fenzhi":null,"minscore":"1","maxscore":"2","option_range":["1","2"],"sort":"0"}]},{"option_msg":"建设目标","option_fenzhi":"10","option_range":"","sort":"5","info":[{"option_msg":"二级标题","option_fenzhi":null,"minscore":"0","maxscore":"2","option_range":["0","2"],"sort":"0"},{"option_msg":"实践教学目标不明确，专业共享性和整合度不好，预期使用率低，无岗位技能培养特色。","option_fenzhi":null,"minscore":"0","maxscore":"2","option_range":["0","2"],"sort":"0"},{"option_msg":"有明确的实践教学目标，有很好的专业共享性和整合度，预期使用率高，能较好满足岗位技能培养需求","option_fenzhi":null,"minscore":"0","maxscore":"2","option_range":["0","2"],"sort":"0"}]},{"option_msg":"建设思路与措施","option_fenzhi":"10","option_range":"","sort":"6","info":[{"option_msg":"建设思路不清晰，建设要求不明确，设备选型不恰当，技术路线不合理、不可行，建设思路不明确。","option_fenzhi":null,"minscore":"0","maxscore":"2","option_range":["0","2"],"sort":"0"},{"option_msg":"建设思路清晰，建设要求比较明确，设备选型基本恰当，技术路线合理、可行，建设思想明确","option_fenzhi":null,"minscore":"0","maxscore":"2","option_range":["0","2"],"sort":"0"},{"option_msg":"建设思路清晰，建设要求明确，设备选型恰当，技术路线先进，建设思路创新","option_fenzhi":null,"minscore":"0","maxscore":"2","option_range":["0","2"],"sort":"0"}]},{"option_msg":"项目实施的预期使用成效","option_fenzhi":"15","option_range":"","sort":"7","info":[{"option_msg":"设备和资源开放共享度低。","option_fenzhi":null,"minscore":"0","maxscore":"2","option_range":["0","2"],"sort":"0"},{"option_msg":"设备和资源开放共享度高。","option_fenzhi":null,"minscore":"0","maxscore":"2","option_range":["0","2"],"sort":"0"},{"option_msg":"项目常丹科研任务不明确，培养学生创新能力不强","option_fenzhi":null,"minscore":"0","maxscore":"2","option_range":["0","2"],"sort":"0"},{"option_msg":"项目能承担一定的科研任务，并且能培养学生一定的创新能力。","option_fenzhi":null,"minscore":"0","maxscore":"2","option_range":["0","2"],"sort":"0"},{"option_msg":"不能承担专业课程实践教学任务，设备利用率低，实验实训项目开出率较低。","option_fenzhi":null,"minscore":"0","maxscore":"2","option_range":["0","2"],"sort":"0"},{"option_msg":"能基本承担专业主干课程实践教学任务，能较好利用设备，使用率达到50%以上。","option_fenzhi":null,"minscore":"0","maxscore":"2","option_range":["0","2"],"sort":"0"},{"option_msg":"能完全承担专业主干课程实践教学任务，能承担学生综合性自主实践训练，设备利用充\\r\\n\\r\\n \\r\\n\\r\\n分，使用率  达到80%以上。","option_fenzhi":null,"minscore":"0","maxscore":"2","option_range":["0","2"],"sort":"0"}]},{"option_msg":"项目实施的预期使用成效","option_fenzhi":"10","option_range":"","sort":"8","info":[{"option_msg":"设备和资源开放共享项目可实施性不强，有一定的安全或环境影响隐患","option_fenzhi":null,"minscore":"0","maxscore":"2","option_range":["0","2"],"sort":"0"},{"option_msg":"项目可实施性强，没有安全或环境影响隐患。","option_fenzhi":null,"minscore":"0","maxscore":"6","option_range":["0","6"],"sort":"0"}]}]}';

        $this->returnjson(array('success' => true, 'data' => $arr));
    }


    //获取评审中的项目
    public function project_listAjax()
    {
        $pici_id = $this->post('pici_id');//项目ids
        $mwhere = '';
        $project_name = trim($this->post('project_name'));
        $sericnum = trim($this->post('sericnum'));
        $project_type = trim($this->post('project_type'));
        if ($project_name) {
            $mwhere .= " and c.course_name like '%$project_name%'";
        }
        if ($sericnum) {
            $mwhere .= " and b.sericnum = '$sericnum'";
        }
        if ($project_type) {
            $mwhere .= " and b.modename like '%$project_type%'";
        }

        /* $table = '`[Q]m_pxm_relation` a left join `whole_projects` b on a.xid=b.id and a.mtype=b.num left join `[Q]admin` c on b.uid=c.id left join `[Q]m_batch` d on a.pici_id=d.id';
         $fields = 'a.id,a.xid,b.num,b.project_number,b.project_name,b.project_select,c.deptname,b.project_head,b.project_yushuan,b.project_apply_time,a.zongfen,a.rec_ranking,d.com_status,a.com_status as houjian';
         */
        $table = '`[Q]m_pxm_relation` a left join `[Q]flow_bill` b on b.id = a.xid left join `[Q]project_coursetask` c on b.mid=c.id left join `[Q]m_batch` d on a.pici_id=d.id';
        $fields = 'a.id,a.xid,a.pici_id,d.com_status,b.modename,b.sericnum,b.table,b.mid,c.subject_classification,c.keyword_classification,c.specific_keywords,c.course_name as project_name';
        $where = "a.pici_id=" . $pici_id . ' ' . $mwhere;
        $order = '';
        $this->getlist($table, $fields, $where, $order);
    }

    /*public function projectafter($table, $rows)
    {
        $pici_id = $this->post('pici_id');


        foreach ($rows as $k => $rs) {
            $rows[$k]['caoz'] = '';
            if ($rs['com_status'] != 2) {
                $rows[$k]['caoz'] .= '<a onclick="check_project(\'' . $rs['num'] . '\',' . $rs['xid'] . ',\'' . $rs['project_name'] . '\')">查看申报书</a>';
                $rows[$k]['caoz'] .= '<span style="padding:5px;">|</span>';

                $rows[$k]['caoz'] .= '<a onclick="project_pxm(' . $rs['xid'] . ')">完成情况</a>';

            } else {
                $rows[$k]['caoz'] .= '<a onclick="check_project(\'' . $rs['num'] . '\',' . $rs['xid'] . ',\'' . $rs['project_name'] . '\')">查看申报书</a>';
                $rows[$k]['caoz'] .= '<span style="padding:5px;">|</span>';

                $rows[$k]['caoz'] .= '<a onclick="project_pxm(' . $rs['xid'] . ')">完成情况</a>';
                if ($rs['houjian'] == 0) {
                    $rows[$k]['caoz'] .= '<span style="padding:5px;">|</span>';
                    $rows[$k]['caoz'] .= '<a onclick="setHouJian(\'' . $rs['num'] . '\',' . $rs['xid'] . ')">归入侯建库</a>';
                }


            }


            $paimin = $this->db->getall("select zongfen	from pl_m_pxm_relation  where pici_id=$pici_id GROUP BY zongfen ORDER BY zongfen desc ");

            $sum_for = 0;
            foreach ($paimin as $ki => $vi) {

                if ($rs['zongfen'] == $vi['zongfen'] && !empty($rs['zongfen'])) {
                    $sum_for = $ki + 1;
                } else if ($rs['zongfen'] == '') {
                    $sum_for = '';
                }
            }

//			$sum_data_data=count($rows);
//			$sum_for=1;
//			foreach($rows as $ki=>$rsi){
//				if($rs['zongfen']=$rsi['zongfen'] &&　$rs['id']!=$rsi['id']){
//
//						$item_zf=''
//				}
//				if($rs['zongfen']<$rsi['zongfen'] && $rs['id']!=$rsi['id'] && !empty($rs['zongfen'])){
//
//						$sum_for=$sum_for+1;
//						//echo $sum_data_data.'<br/>';
//				}else if($rs['zongfen']==''){
//						$sum_for='';
//				}
//
//			}
//
            $rows[$k]['paimin'] = $sum_for;


        }


        return $rows;

    }*/

    /*修改后*/
    public function projectafter($table, $rows)
    {
        //判断该批次是否已全部评审完
        $pici_id = '';
        foreach ($rows as $k => $rs) {
            $is_fineshed = false;
            $pici_id = $rs['pici_id'];
            $rows[$k]['caoz'] = '';
            $rows[$k]['caoz'] .= '<a onclick="check_project(\'' . $rs['table'] . '\',' . $rs['mid'] . ',\'' . $rs['project_name'] . '\')">查看申报书</a>';
            //判断评审状态
            $rows[$k]['comment_status'] = '';
            $row[$k]['comment_progress'] = '';
            $ra = m('m_pxmdf')->getall('pici_id = ' . $rows[$k]['pici_id'] . ' and xid=' . $rows[$k]['xid']);
            $i = 0;
            foreach ($ra as $item) {
                if ($item['com_status'] == 1 || $item['com_status'] == 3) {
                    $i++;
                }
            }
            if ($i == count($ra)) {
                $rows[$k]['comment_status'] = '已完成';
                $rows[$k]['comment_progress'] = '100%';
                $is_fineshed = true;
                m('m_pxm_relation')->update(array('com_status' => '1'), 'id = ' . $rs['id']);
            } else {
                $rows[$k]['comment_status'] = '未完成';
                $percent = ($i / count($ra)) * 100;
                $rows[$k]['comment_progress'] = $percent . '%';
            }
        }
        if ($is_fineshed) {
            $rb = m('m_batch')->update(array('com_status' => 2), 'id=' . $pici_id);
        }
        return $rows;
    }

    //网评管理中获取专家
    public function user_listAjax()
    {
        $pici_id = $this->post('pici_id');//项目ids

        if (empty($pici_id)) {
            //参数为空
        }
        $table = '`[Q]m_pua_relation` a left join `[Q]admin` b on a.uid=b.id';
        $fields = 'b.id,b.name,b.ranking,b.deptname';
        $where = "a.pici_id=" . $pici_id;
        $order = '';
        $this->getlist($table, $fields, $where, $order);

    }


    public function userafter($table, $rows)
    {
        $pici_id = $this->post('pici_id');//项目id

        foreach ($rows as $k => $rs) {
            $rows[$k]['caoz'] = '';
            $rows[$k]['schedule'] = '';
            $rows[$k]['c_status'] = '';
            $schedule = m('m_pxmdf')->rows('pici_id=' . $pici_id . ' and uid=' . $rs['id'] . ' and com_status=0');
            $zuofei = m('m_pxmdf')->rows('pici_id=' . $pici_id . ' and uid=' . $rs['id'] . ' and com_status=3');
            $schedule_wangc = m('m_pxmdf')->rows('pici_id=' . $pici_id . ' and uid=' . $rs['id'] . ' and com_status=1');
            $z_rows = m('m_pxm_relation')->rows('pici_id=' . $pici_id);

            if ($schedule || $zuofei) {
                $rows[$k]['c_status'] .= '未完成';
                if (!$schedule_wangc) {
                    $rows[$k]['schedule'] = "0%";
                } else {
                    $rows[$k]['schedule'] = floor(($schedule_wangc / $z_rows) * 100);
                    $rows[$k]['schedule'] = $rows[$k]['schedule'] . '%';
                }
            } else {
                $rows[$k]['c_status'] .= '已完成';
                $rows[$k]['schedule'] = "100%";
            }
//			$rows[$k]['caoz'].= '<a onclick="user_pxm(\''.'5'.'\','.'5'.',\''.'5'.'\')">查看</a>';
            $rows[$k]['caoz'] .= '<a onclick="user_pxm(\'' . $rs['id'] . '\')">查看</a>';
        }
        return $rows;
    }


    /**
     * 网评管理中获取项目专家的完成情况
     */
    public function project_pxmAjax()
    {
        $pici_id = $this->post('pici_id');//项目ids
        $xid = $this->post('mid');
        if (empty($pici_id)) {
            //参数为空
        }
        $table = '`[Q]m_pxmdf` a left join `whole_projects` b on a.xid=b.id and a.mtype=b.num left join `[Q]admin` c on a.uid=c.id';
        $fields = 'b.id,b.num,b.project_name,b.project_number,a.user_zongfen,a.com_status,c.name';
        $where = "a.pici_id=" . $pici_id . ' and a.xid=' . $xid;
        $order = '';
        $this->getlist($table, $fields, $where, $order);
    }

    public function project_pxmafter($table, $rows)
    {
        foreach ($rows as $k => $rs) {
            $rows[$k]['caoz'] = '';
            if ($rs['com_status'] == 0) {
                $rows[$k]['caoz'] .= '未提交';
            } elseif ($rs['com_status'] == 1) {
                $rows[$k]['caoz'] .= '已提交';
            } elseif ($rs['com_status'] == 3) {
                $rows[$k]['caoz'] .= '网评结束未提交，已作废';
            }

        }
        return $rows;
    }

    /**
     * 网评管理中获取专家的完成情况
     */
    public function user_pxmAjax()
    {
        $pici_id = $this->post('pici_id');//项目ids
        $uid = $this->post('uid');
        if (empty($pici_id)) {
            //参数为空
        }
        /* $table = '`[Q]m_pxmdf` a left join `whole_projects` b on a.xid=b.id and a.mtype=b.num';
         $fields = 'b.id,b.num,b.project_name,b.project_number,a.user_zongfen,a.com_status';
         $where = "a.pici_id=" . $pici_id . ' and a.uid=' . $uid;
         $order = '';
         $this->getlist($table, $fields, $where, $order);*/
        $table = '`[Q]m_pxmdf` a left join `[Q]flow_bill` b on a.xid=b.id and a.mtype=b.table';
        $fields = 'b.id,b.table,b.sericnum,a.user_zongfen,a.com_status,a.review_opinion,a.operating_time';
        $where = "a.pici_id=" . $pici_id . ' and a.uid=' . $uid;
        $order = '';
        $this->getlist($table, $fields, $where, $order);
    }

    public function user_pxmafter($table, $rows)
    {
        foreach ($rows as $k => $rs) {
            $re = m('flow_bill')->getone('id = ' . $rs['id']);
            $rb = m('' . $re['table'])->getone('id = ' . $re['mid']);
            $rows[$k]['project_name'] = $rb['course_name'];
            $rows[$k]['sericnum'] = $re['sericnum'];
            $rows[$k]['caoz'] = '';
            $rows[$k]['status'] = '';
            if ($rs['com_status'] == 0) {
                $rows[$k]['status'] .= '未提交';
            } elseif ($rs['com_status'] == 1) {
                $rows[$k]['status'] .= '已提交';
                $rows[$k]['caoz'] .= '<a onclick="user_pxm(\'' . $rs['id'] . '\')">查看</a>';
            } elseif ($rs['com_status'] == 3) {
                $rows[$k]['status'] .= '网评结束未提交，已作废';
            }
        }
        return $rows;
    }

    /*
     * 查看批次中的评审信息
     * */
    public function comment_infoAjax()
    {
        $pici_id = $this->post('pici_id');//项目ids
        $project_name = $this->post('project_name');
        $sericnum = trim($this->post('sericnum'));
        $mwhere = '';
        if (!empty($pici_id)) {
            $mwhere .= " a.pici_id=" . $pici_id;
        }
        if (!empty($project_name)) {
            $mwhere .= " and c.course_name=" . $project_name;
        }
        if (!empty($sericnum)) {
            $mwhere .= " and b.sericnum = '" . $sericnum . "'";
        }
        $table = '`[Q]m_pxm_relation` a left join `[Q]flow_bill` b on b.id = a.xid left join `[Q]project_coursetask` c on b.mid=c.id left join `[Q]m_batch` d on a.pici_id=d.id ';
        $fields = 'b.modename,b.sericnum,b.table,b.mid,c.course_name as project_name,a.pici_id,b.id as bill_id ';
        $where = '' . $mwhere;
        $order = '';
        $arr = $this->limitRows($table, $fields, $where, $order);
        $arr['totalCount'] = $arr['total'];
        unset($arr['sql'], $arr['total']);
        foreach ($arr['rows'] as $k => $rs) {
            $arr['rows'][$k]['caoz'] = '';
            $arr['rows'][$k]['jxstate'] = '';
            //判断结项是否通过
            $rm = m('m_pxmdf')->getall('pici_id = '.$rs['pici_id'].' and xid = '.$rs['bill_id'].' and mtype = \'' .$rs['table'].'\'');
            $totalCount = count($rm);
            $errorCount = 0;
            $tijaoCount = 0;
            //先判断是否都已提交 //全部提交后获取等级建议中不合格的个数
            foreach ($rm as $p){
                if ($p['com_status']==1){
                    $tijaoCount++;
                }
                if ($p['level_suggest'] ==4){
                    $errorCount++;
                }
            }
            if ($tijaoCount==$totalCount){
                if ($errorCount>=2){
                    $arr['rows'][$k]['jxstate'] ='结项不通过';
                }else{
                    $arr['rows'][$k]['jxstate'] ='结项通过';
                }
            }else{
                $arr['rows'][$k]['jxstate'] ='评审中';
            }

            $arr['rows'][$k]['caoz'] .= '<a onclick="read_comment_info(\'' . $rs['pici_id'] . '\',' . '\'' . $rs['bill_id'] . '\')">查看评审信息</a>';
        }
        $this->returnjson($arr);
    }


    /*
     * 评审信息的打分情况
     * */
    public function expert_scoreAjax()
    {
        $pici_id = $this->post('pici_id');
        $xid = $this->post('xid');
        $pingshenType = $this->post('pinshenType');
        $table = '`[Q]m_pxmdf` a left join `[Q]admin` b on a.uid = b.id  left join `[Q]flow_bill` c  on c.id = a.xid left join `[Q]m_batch` d on a.pici_id = d.id ';
        $fields = 'a.user_zongfen,a.review_opinion,a.operating_time,a.com_status,a.uid,a.pici_id,a.xid,a.uid,b.name,c.mid,c.table,d.pici_norm_id ,a.review_opinion_end,a.level_suggest,a.publish_suggest';
        $where = 'a.pici_id=' . $pici_id . ' and xid=' . $xid . ' ';
        $order = '';
        $arr = $this->limitRows($table, $fields, $where, $order);


        $arr['totalCount'] = $arr['total'];
        $level_suggest = array('1' => '优秀', '2' => '良好', '3' => '合格', '4' => '不合格');
        $publish_suggest = array('1' => '值得初版', '2' => '可出版也可不出版', '3' => '不必出版');
        foreach ($arr['rows'] as $k => $v) {
            $arr['rows'][$k]['caoz'] = '';
            $arr['rows'][$k]['status'] = '';
            if ($arr['rows'][$k]['level_suggest']) {
                $arr['rows'][$k]['level_suggest'] = $level_suggest[$arr['rows'][$k]['level_suggest']];
            }
            if ($arr['rows'][$k]['publish_suggest']) {
                $arr['rows'][$k]['publish_suggest'] = $publish_suggest[$arr['rows'][$k]['publish_suggest']];
            }
            if ($v['com_status'] == 1) {
                $arr['rows'][$k]['status'] = '已提交评分';
                if ($pingshenType == 'project_start') {
                    $arr['rows'][$k]['caoz'] .= '<a  onclick="preview_start(\'' . $v['pici_id'] . '\',' . '\'' . $v['mid'] . '\',' . '\'' . $v['table'] . '\',' . '\'' . $v['uid'] . '\')">查看</a>';
                    $arr['rows'][$k]['caoz'] .= '<span style="padding:5px;">|</span>';
                    $arr['rows'][$k]['caoz'] .= '<a onclick="penalty_start_func(\'' . $v['pici_id'] . '\',' . '\'' . $v['uid'] . '\',' . '\'' . $v['xid'] . '\')">扣罚</a>';
                } else if ($pingshenType == 'project_end') {
                    $arr['rows'][$k]['caoz'] .= '<a  onclick="preview_end(\'' . $v['pici_id'] . '\',' . '\'' . $v['mid'] . '\',' . '\'' . $v['table'] . '\',' . '\'' . $v['uid'] . '\')">查看</a>';
                    $arr['rows'][$k]['caoz'] .= '<span style="padding:5px;">|</span>';
                    $arr['rows'][$k]['caoz'] .= '<a onclick="penalty_end_func(\'' . $v['pici_id'] . '\',' . '\'' . $v['uid'] . '\',' . '\'' . $v['xid'] . '\')">扣罚</a>';
                }
            } else if ($v['com_status'] == 0 || $v['com_status'] == 2) {
                $arr['rows'][$k]['status'] = '未提交评分';
                if ($pingshenType == 'project_start') {
                    $arr['rows'][$k]['caoz'] .= '<a  onclick="look_start(\'' . $v['pici_norm_id'] . '\')">查看</a>';
                } else if ($pingshenType == 'project_end') {
                    $arr['rows'][$k]['caoz'] .= '<a  onclick="look_end(\'' . $v['pici_norm_id'] . '\')">查看</a>';
                }
            }
        }
        $this->returnjson($arr);
    }


    /**
     * 扣罚操作
     * */

    /*如扣罚完成后需要看扣罚信息，可以使m_pxmdf 与penalty 联合查询,在m_pxmdf中加一个字段判断是否已扣罚*/
    public function savepenaltyAjax()
    {
        $pici_id = $this->post('pici_id');
        $uid = $this->post('uid');
        $xid = $this->post('xid');
        $penalty_reason = $this->post('penalty_reason');

        $arr = array(
            'uid' => $uid,
            'pici_id' => $pici_id,
            'xid' => $xid,
            'penalty_reason' => $penalty_reason,
            'penalty_time' => date('Y-m-d h:m:s'),
        );
        $rd = m('penalty_record')->insert($arr);
        if ($rd) {
            echo json_encode(array('msg' => '扣罚成功', 'success' => true));
        } else {
            echo json_encode(array('msg' => '扣罚失败', 'success' => false));
        }

    }


    /**
     * 专家网评列表
     */
    public function expertlistAjax()
    {
        $mtype = $this->post('mtype');//项目类型 project_sx_apply或project_apply
        $deptname = $this->post('deptname');//申报单位
        $time_frame = $this->post('project_apply_time');//申报时间
        $com_status = $this->post('com_status');//待网评
        $pici_name = $this->post('pici_name');//批次名称
        $project_name = $this->post('project_name');//项目名称
        $project_head = $this->post('project_head');//项目负责人

        $where = '';
        $order = '';
        //每个用户只能获取自己的网评信息
        $where .= " and mp.uid=" . $this->adminid;

        //这里要做一个时间判断，专家要网评开始之后专家才能看到网评信息
        //并且时间要小于结束时间
        $where .= ' and ba.pici_start_time<="' . date('Y-m-d H:i:s', time()) . '"';

        //if(!empty($com_status))$where.=" and mp.com_status=$com_status";

        //排序和查询判断判
        //查询全部 则先看到未网评的再看到已网评的，未网评的按发起时间来排，已网评的按用户提交的操作时间来排
        //查询未网评，未网评的按发起时间来排
        //查询已网评，已网评的按用户提交的操作时间来排

        if ($com_status != '') {
            switch ((int)$com_status) {
                case 0:
                    //未网评
                    $where .= " and mp.com_status=$com_status";
                    $where .= ' and ba.pici_end_time>="' . date('Y-m-d H:i:s', time()) . '"';
                    $order = 'ba.operating_time desc';
                    break;
                case 1:
                    //已网评
                    $where .= " and mp.com_status=$com_status";
                    $order = 'mp.operating_time desc';
                    break;
                default:
                    //全部
                    $order = 'mp.com_status asc,ba.operating_time desc';
                    break;
            }
        } else {
            //全部
            $order = 'mp.com_status asc,ba.operating_time desc';
        }

        if (!empty($mtype)) $where .= " and mp.mtype='$mtype'";
        if (!empty($deptname)) $where .= " and b.deptname='$deptname'";
        if (!empty($time_frame)) {//时间范围
            list($start_time, $end_time) = explode(',', $time_frame);
            $where .= " and c.project_apply_time between '" . $start_time . "' and '" . $end_time . "'";
            unset($start_time, $end_time);
        }
        if (!empty($pici_name)) $where .= " and ba.pici_name like '%$pici_name%'";
        if (!empty($project_name)) $where .= " and c.project_name like '%$project_name%'";
        if (!empty($project_head)) $where .= " and c.project_head like '%$project_head%'";

        $table = '`[Q]m_pxmdf` mp left join `[Q]flow_bill` a on a.table=mp.mtype and a.mid=mp.xid left join `[Q]admin` b on a.uid=b.id left join `whole_projects` c on a.mid=c.id left join `[Q]m_batch` ba on ba.id=mp.pici_id';
        $fields = 'ba.id,c.num,mp.xid,mp.model,c.project_name,ba.mtype,b.deptname,c.project_head,c.project_yushuan,c.project_apply_time,ba.pici_name,mp.user_zongfen,mp.com_status,mp.pici_id';
        $where = "1=1 $where";
        $this->getlist($table, $fields, $where, $order);
    }

    /**
     * 专家网评列表操作
     */
    public function expertafter($table, $rows)
    {
        foreach ($rows as $k => $v) {
            $rows[$k]['caoz'] = '';

            if ((int)$rows[$k]['com_status'] == 0 && $rows[$k]['model'] == null) {

                $rows[$k]['caoz'] .= '<a onclick="comment(' . $v['id'] . ',\'' . $v['num'] . '\',\'' . $v['xid'] . '\')">评分</a>';

            } else if ((int)$rows[$k]['com_status'] == 0 && $rows[$k]['model'] != null) {

                $rows[$k]['caoz'] .= '<a onclick="comment_capgao(' . $v['id'] . ',\'' . $v['num'] . '\',\'' . $v['xid'] . '\')">编辑草稿</a>';

            } else if ((int)$rows[$k]['com_status'] == 1) {

                $rows[$k]['caoz'] .= '<a onclick="look(' . $v['id'] . ',\'' . $v['num'] . '\',\'' . $v['xid'] . '\')">查看</a>';

            }

        }
        unset($k, $v);
        return $rows;
    }

    /**
     * 获取专家打分信息
     */
    public function expert_dafenAjax()
    {
        $uid = $this->post('uid');
        $pici_id = $this->post('pici_id');
        //此处mid为flow中的mid
        $mid = $this->post('mid');
        $mtype = strval($this->post('mtype'));
        $urs = m('flow_bill')->getone('mid=' . $mid . ' and `table`=\'' . $mtype . '\'');
        $bill_id = $urs['id'];
        $pxmd_info = '';
        if ($uid) {
            $pxmd_info = m('m_pxmdf')->getone('pici_id=' . $pici_id . ' and xid=' . $bill_id . ' and mtype=\'' . $mtype . '\' and com_status=1 and uid=' . $uid);
        } else {
            $pxmd_info = m('m_pxmdf')->getone('pici_id=' . $pici_id . ' and xid=' . $bill_id . ' and mtype=\'' . $mtype . '\' and com_status=1 and uid=' . $this->adminid);
        }
        //$this->returnjson(array('success' => true, 'data' => $pxmd_info['model']));
        $this->returnjson(array('success' => true, 'data' => $pxmd_info));
    }

    /**
     * 判断用户是否提交的监听事件
     */
    public function getuseropenAjax()
    {
        $pici_id = $this->post('pici_id');
        $mid = $this->post('mid');
        $mtype = $this->post('mtype');
        $uid = $this->adminid;
        $urs = m('flow_bill')->getone('mid=' . $mid . ' and `table`=\'' . $mtype . '\'');
        $bill_id = $urs['id'];
        $pxmd_info = m('m_pxmdf')->getone('pici_id=' . $pici_id . ' and xid=' . $bill_id . ' and mtype=\'' . $mtype . '\' and uid=' . $uid);
        if ($pxmd_info['com_status'] == 0 && $pxmd_info['model'] == '') {
            //不关闭 数据未提交
            $openck = '0';
        } elseif ($pxmd_info['com_status'] == 0 && $pxmd_info['model'] != '') {
            //关闭窗口 返回 当前数据为草稿
            $openck = '1';
        } elseif ($pxmd_info['com_status'] == 1 && $pxmd_info['model'] != '') {
            $openck = '2';
        } //2020-11-02添加start
        elseif ($pxmd_info['com_status'] == 2 && $pxmd_info['model'] != '') {
            $openck = '2';
        }
        //end
        $this->returnjson(array('success' => true, 'data' => $openck));
    }

    public function getExcelAction()
    {
        $this->display = false;
        $pici_id = $this->get('pici_id');
        $pingshenType = $this->get('pinshenType');
        $lx = $this->get('lx');

        /*switch ($lx) {
            case 0:
                c('PHPExcelRed')->pinFenHuiZonBiao($pici_id);//分类汇总表，实训
                break;
            case 1:
                c('PHPExcelRed')->pinJiDiJianSheXiangMu($pici_id);//基地建设详情表，实训
                break;
            case 2:
                c('PHPExcelRed')->pinFeiShiXunChuSheng($pici_id);//项目申报初审预览表 非实训
                break;
            case 3:
                c('PHPExcelRed')->pinFeiShiXunZhuanJia($pici_id);//项目申报初审预览表 非实训
                break;
            case 4:
                c('PHPExcelRed')->pinFeiHuZxM();//项目申报初审预览表 非实训
                break;
        }*/
        switch ($lx) {
            case 0:
                c('PHPExcelRed')->projectStartGroupReviewExcel($pici_id);//立项评审小组评审表
                break;
            case 1:
                c('PHPExcelRed')->projectStartGroupReviewResultExcel($pici_id);//立项评审小组评审结果表
                break;
            case 2:
                c('PHPExcelRed')->projectEndGroupReviewExcel($pici_id);//结项评审小组评审表
                break;
            case 3:
                c('PHPExcelRed')->projectEndGroupReviewResultExcel($pici_id);//结项评审小组评审结果表
                break;
            case 4:
                c('PHPExcelRed')->exportExpertInfoExcel($pingshenType);//导出专家信息
                break;
        }
    }

    //归入后建库
    public function setHouJianAjax()
    {
        $pici_id = $this->post('pici_id');
        $mtype = $this->post('mtype');
        $mid = $this->post('mid');


        $arr['project_ku'] = '侯建库';

        if ($mtype == 'project_apply') {
            $info = m('project_apply')->update($arr, 'id=' . $mid);

            $data_x = m('m_pua_relation')->getall('pici_id=' . $pici_id);
            foreach ($data_x as $k => $v) {
                $x_user = m('admin')->getone('id=' . $v['uid']);

                $data_x_user['mid'] = $mid;
                $data_x_user['sort'] = $k + 1;
                $data_x_user['project_x_n'] = $x_user['name'];
                $data_x_user['project_x_d'] = $x_user['deptname'];
                $data_x_user['project_x_zw'] = $x_user['ranking'];
                $data_x_user['project_x_qm'] = $x_user['name'];
                $data_x_user['project_x_bz'] = '';
                m('m_project_x')->insert($data_x_user);
            }
            if (count($data_x) < 9) {
                for ($i = count($data_x); $i < 9; $i++) {
                    $data_x_user['mid'] = $mid;
                    $data_x_user['sort'] = $i;
                    $data_x_user['project_x_n'] = '';
                    $data_x_user['project_x_d'] = '';
                    $data_x_user['project_x_zw'] = '';
                    $data_x_user['project_x_qm'] = '';
                    $data_x_user['project_x_bz'] = '';
                    m('m_project_x')->insert($data_x_user);
                }
            }

        } else if ($mtype == 'project_sx_apply') {

            $info = m('project_sx_apply')->update($arr, 'id=' . $mid);
        }

        if ($info) {
            $status['com_status'] = 1;
            m('m_pxm_relation')->update($status, 'pici_id=' . $pici_id . ' and xid=' . $mid);
            $this->returnjson(array('success' => true, 'data' => '', 'msg' => '归入成功'));
        } else {
            $this->returnjson(array('success' => false, 'data' => '', 'msg' => '归入失败'));
        }

    }

    //待发网评项目列表
    public function awaitAjax()
    {
        /*搜索条件缺少预算
		需要修改人 @guo 	*/
        $dt = $this->rock->post('dt1');//申报时间
        $key = $this->rock->post('key');
        $mtype = $this->getmtype();//项目类型
        $zt = $this->rock->post('zt');//审核状态
        $bdt = $this->rock->post('bdt');//最近$bdt个月
        $xmfl = $this->rock->post('xmfl');//项目分类
        $sbdw = $this->rock->post('sbdw');//申报单位
        $jjcd = $this->rock->post('jjcd');//紧急程度
        $xmbh = $this->rock->post('xmbh');//项目编号
        $xmmc = $this->rock->post('xmmc');//项目名称
        $fzr = $this->rock->post('fzr');//项目负责人
        $xmys = $this->rock->post('xmys');//项目预算
        $time_frame = $this->rock->post('time_frame');//时间范围


        $where = '';

        //项目名称
        if ($xmmc != '') $where .= " and c.project_name like '%" . trim($xmmc) . "%'";
        //申报单位
        if ($sbdw != '') $where .= " and b.deptname='" . trim($sbdw) . "'";
        //项目编号
        if ($xmbh != '') $where .= " and c.project_number='" . trim($xmbh) . "'";
        //时间范围
        if ($time_frame != "") {
            list($start_time, $end_time) = explode(',', $time_frame);
            $where .= " and c.project_apply_time between '" . $start_time . "' and '" . $end_time . "'";
            unset($start_time, $end_time);
        }

        $table = '`[Q]flow_bill` a left join `[Q]admin` b on a.uid=b.id left join `whole_projects` c on a.mid=c.id left join `[Q]flow_course` fc on fc.id=a.nowcourseid';
        $fields = 'a.table as mtype,a.modeid,a.optid,a.nowcheckname,a.status as bill_status,a.allcheckid,a.nowcheckid,a.nowcourseid,a.optid,b.name,b.deptname,c.id,c.num,c.project_name,c.status as cst,c.isturn,c.optname,c.project_head,c.project_apply_time,c.project_select,c.project_xingzhi,c.project_ku,c.isturn,fc.name as flowname,c.exigence_status,c.process_state,c.project_is_guidang,c.project_number,c.project_yushuan';
        $where = "a.table='$mtype' and project_ku='预备库' and c.is_wp=0 and c.isturn=1 $where";
        $order = 'a.optdt desc';
        $arr = $this->getlist($table, $fields, $where, $order);


    }


    public function awaitafter($table, $rows)
    {
        foreach ($rows as $k => $rs) {

            $project_name = "'" . $rs['project_name'] . "'";
            $num = "'" . $rs['num'] . "'";

            $rows[$k]['caoz'] = '<a onclick="check_project(' . $num . ',' . $rs['id'] . ',' . $project_name . ')">查看</a>';


        }

        return $rows;
    }


    /**
     * 批次评分对比
     */
    public function commentContrastAjax()
    {
        $mtype = $this->getmtype();//项目类型
        $time_frame = $this->post('launch_time');//网评发起时间
        $pici_name = $this->post('pici_name');//批次名称
        $sub_where = '';

        //实训类的只能看实训类的，非实训类的只能看非实训类的
        $sub_where .= " and mtype='$mtype'";
        //只能获取当前用户的所要网评的批次,管理员则获取全部
        $is_operator = m('sjoin')->getone('(mid=6 or mid=7) and type="gu" and sid=' . $this->adminid);
        if (!$is_operator) {
            //$sub_where.=" and find_in_set(".$this->adminid.",expert_ids)";
            $sub_where .= ' and expert_ids like \'%"' . $this->adminid . '";%\'';
        }
        if ($time_frame != "") {//时间范围
            list($start_time, $end_time) = explode(',', $time_frame);
            $sub_where .= " and launch_time between '" . $start_time . "' and '" . $end_time . "'";
            unset($start_time, $end_time);
        }
        if (!empty($pici_name)) $sub_where .= " and pici_name like '%$pici_name%'";

        $table = '`[Q]m_batch`';
        $fields = '*';
        $where = "1=1 $sub_where";
        $order = 'operating_time desc';
        $arr = $this->limitRows($table, $fields, $where, $order);
        $arr['totalCount'] = $arr['total'];
        //计算项目个数和判断网评状态和操作
        foreach ($arr['rows'] as $k => $v) {
            $arr['rows'][$k]['project_num'] = count(unserialize($v['project_ids']));
            if ((int)$v['com_status'] == 0) $arr['rows'][$k]['com_status'] = '草稿';
            else if ((int)$v['com_status'] == 1) $arr['rows'][$k]['com_status'] = '进行中';
            else if ((int)$v['com_status'] == 2) $arr['rows'][$k]['com_status'] = '已完成';
            $arr['rows'][$k]['caoz'] = '<a onclick="checkpici(' . $v['id'] . ')">查看汇总信息</a>';
        }
        unset($k, $v);

        unset($arr['sql'], $arr['total']);
        //echo $arr['sql'];exit;
        if ($arr['totalCount'] == 0) exit('暂无数据');
        $this->returnjson($arr);
    }

    /**
     * 查看网评信息(未提交网评评分对比)
     */
    public function contrastDetailAjax()
    {
        $pici_id = $this->post('pici_id');//批次id
        $project_name = $this->post('project_name');//项目名称
        $is_wp = $this->post('is_wp');//是否已网评
        $user_id = $this->adminid;

        $sub_where = $where = '';
        if (!empty($project_name)) {
            $sub_where .= ' and project_name like "%' . $project_name . '%"';
        }

        //只能获取当前用户的所要网评的项目,管理员则获取全部
        $is_operator = m('sjoin')->getone('(mid=6 or mid=7) and type="gu" and sid=' . $this->adminid);
        if (!$is_operator) {
            $where .= " and uid=$user_id ";
        }

        if ($is_wp != '') {
            $where .= ' and com_status=1 ';
        }

        //获取列标题
        $th = m('m_batch')->getone("id=$pici_id", "model");
        $th_array = $td_array = array();
        $info = json_decode($th['model'], true)['info'];
        //var_dump($info);exit;
        $th_array[] = '项目名称';
        foreach ($info as $k => $val) {
            $th_array[] = $val['option_msg'];
        }
        unset($k, $val);

        $detail = m('m_pxmdf')->getall("pici_id=$pici_id $where", 'xid,mtype,model', 'operating_time desc');
        foreach ($detail as $k => $val) {
            $tmp = array();
            $project_info = m($val['mtype'])->getone('id=' . $val['xid'] . $sub_where, 'project_name');
            $d_info = $val['model'];
            if (!empty($d_info) && !empty($project_info['project_name'])) {
                $tmp[] = $project_info['project_name'];
                //var_dump(json_decode($d_info,true)['info']);
                foreach (json_decode($d_info, true)['info'] as $model_k => $model_val) {
                    //var_dump($model_val);
                    $tmp[] = $model_val['user_dafen'];
                }
                unset($model_k, $model_val);
                $td_array[] = $tmp;
            }
        }
        unset($k, $val);
        //var_dump($td_array);exit;
        $this->returnjson(array(
            'th' => $th_array,
            'td' => $td_array
        ));
    }


    /**
     * 项目复评列表
     * */

    public function expertreviewbefore()
    {
        $secrinum = $this->post('secrinum');
        $project_name = $this->post('project_name');
        $leader = $this->post('leader');
        $where = '';

        if ($secrinum) {
            $where .= " and xinhu_expert_review.secrinum like '%$secrinum%'";
        }
        if ($project_name) {
            $where .= " and xinhu_expert_review.name like '%$project_name%'";
        }
        if ($leader) {
            $where .= " and xinhu_expert_review.leader like '%$leader%'";
        }

        return array(
            'table' => "xinhu_expert_review",
            'where' => " $where",
            'fields' => 'xinhu_expert_review.*',
            'order' => 'xinhu_expert_review.id desc'
        );
    }


    /**
     * 查询出所有项目复评是否通过来判断项目申报流程环节是否通过
     * */
    public function changeBillStatusAjax()
    {
        $rs = m('expert_review')->getall('');
        //根据nowcourseid获取当前流程环节的审核人以及审核人名字
        $bill_course = m('flow_course')->getone('id=101');//提交结项成果，经费结算环节
        $log_course = m('flow_course')->getone('id=99');//项目复评环节

        //项目不存在或者已复评
        $error1 = array();
        //复评结果值不正确
        $error2 = array();

        foreach ($rs as $r) {
            if ($r['result'] == '通过') {
                //改变flow_bill表数据
                $bill_arr = array(
                    'nstatustext' => '待' . $bill_course['checktypename'] . '处理',
                    'nowcourseid' => '101',
                    'nstatus' => '0',
                    'status' => '0',
                    'nowcheckid' => $bill_course['checktypeid'],
                    'nowcheckname' => $bill_course['checktypename'],
                    'updt' => date('Y-m-d H:i:s'),
                );
                $rm = m('flow_bill')->getone('sericnum=\'' . $r['secrinum'] . '\' and nowcourseid = 99');
                if ($rm) {
                    //flow_log添加一条流程数据
                    $log_arr = array(
                        'table' => 'project_coursetask',
                        'name' => $log_course['name'],
                        'mid' => $rm['mid'],
                        'courseid' => '99',
                        'statusname' => '通过',
                        'status' => '1',
                        'optdt' => date('Y-m-d H:i:s'),
                        'ip' => '::1',
                        'web' => 'chrome',
                        'checkname' => $log_course['checktypename'],
                        'checkid' => $log_course['checktypeid'],
                        'modeid' => $log_course['setid'],
                        'color' => 'black',
                        'valid' => '1',
                        'step' => '1',
                    );
                    //项目复评文件导入成功后根据复评结果来判断是否通过这一环节，修改flow_bill当前项目的所处流程环节，flow_log添加一条记录
                    $rb = m('flow_bill')->update($bill_arr, 'sericnum=\'' . $r['secrinum'] . '\'');
                    $rg = m('flow_log')->insert($log_arr);
                    //将已复评的数据状态改变，避免重复复评
                    $ro = m('expert_review')->update(array('ps_status' => 1), 'secrinum=\'' . $r['secrinum'] . '\'');
                } else {
                    $rd = m('expert_review')->delete('secrinum=\'' . $r['secrinum'] . '\' and ps_status=0');
                    array_push($error1, $r['secrinum']);
                }
            } else if ($r['result'] == '不通过') {
                $up_arr = array(
                    'nstatus' => 5,
                    'status' => 5
                );
                $rt = m('flow_bill')->getone('sericnum=\'' . $r['secrinum'] . '\' and nowcourseid = 99 and status=0 and nstatus=0');
                if ($rt) {
                    $log_arr = array(
                        'table' => 'project_coursetask',
                        'name' => $log_course['name'],
                        'mid' => $rt['mid'],
                        'courseid' => '99',
                        'statusname' => '不通过',
                        'status' => '3',
                        'optdt' => date('Y-m-d H:i:s'),
                        'ip' => '::1',
                        'web' => 'Chrome',
                        'checkname' => $log_course['checktypename'],
                        'checkid' => $log_course['checktypeid'],
                        'modeid' => $log_course['setid'],
                        'color' => 'black',
                        'valid' => '1',
                        'step' => '1',
                    );
                    $re = m('flow_bill')->update($up_arr, 'sericnum=\'' . $r['secrinum'] . '\' and nowcourseid = 99');
                    $rg = m('flow_log')->insert($log_arr);
                    $ro = m('expert_review')->update(array('ps_status' => 1), 'secrinum=\'' . $r['secrinum'] . '\'');
                } else {
                    $rd = m('expert_review')->delete('secrinum=\'' . $r['secrinum'] . '\' and ps_status=0');
                    array_push($error1, $r['secrinum']);
                }
            } else {
                $rd = m('expert_review')->delete('secrinum=\'' . $r['secrinum'] . '\' and id=' . $r['id']);
                array_push($error2, $r['secrinum']);
            }
        }
        if (!(empty($error1)) && empty($error2)) {
            $error_msg = '';
            $error1 = array_unique($error1);
            foreach ($error1 as $e) {
                $error_msg .= $e . ',';
            }
            echo json_encode(array(
                'success' => false,
                'msg' => '登记号为' . $error_msg . '的项目不存在或者已复评'
            ));
        } else if (!(empty($error2)) && empty($error1)) {
            $error_msg = '';
            $error2 = array_unique($error2);
            foreach ($error2 as $e) {
                $error_msg .= $e . ',';
            }
            echo json_encode(array(
                'success' => false,
                'msg' => '登记号为' . $r['secrinum'] . '的复评结果值不正确(复评结果的值为通过,不通过),请重新导入该条数据'
            ));
        } else if (!(empty($error2)) && empty($error1)) {
            $error_msg1 = '';
            $error1 = array_unique($error1);
            $error2 = array_unique($error2);
            foreach ($error1 as $e) {
                $error_msg1 .= $e . ',';
            }
            $error_msg2 = '';
            foreach ($error2 as $e) {
                $error_msg2 .= $e . ',';
            }
            echo json_encode(array(
                'success' => false,
                'msg' => '登记号为' . $error_msg2 . '的复评结果值不正确(复评结果的值为通过,不通过),登记号为' . $error_msg1 . '的项目不存在或者已复评'
            ));
        }
    }

    /**
     *获取提交结项成果里的成果信息
     * */
    public function getAchievementDataAjax()
    {
        $mode_num = $this->post('mode_num');
        $mid = $this->post('mid');
        if ($mode_num && $mid) {
            //获取项目编号
            $billData = m('flow_bill')->getone("`table` = '$mode_num' and mid = $mid", "sericnum,id");
            $sericnum = $billData['sericnum'];
            $bill_id = $billData['id'];
            //获取立项编号=成果编号
            $reviewData = m('expert_review')->getone("secrinum = '$sericnum'", 'projectstart_num');
            $projectstart_num = $reviewData['projectstart_num'];
            //获取名称，作者，所在单位,成果编号，成果形式,发表刊物，摘要
            $queryData = m('achievement_query')->getone("identifier = '$projectstart_num' and status = 1");
            $queryData['bill_id'] = $bill_id;
            if ($queryData) {
                $this->returnjson(
                    array('code' => 200, 'success' => true, 'msg' => '数据获取成功', 'data' => $queryData)
                );
            } else {
                $this->returnjson(
                    array('code' => 201, 'success' => false, 'msg' => '数据为空')
                );
            }
        } else {
            $this->returnjson(
                array('code' => 201, 'success' => false, 'msg' => '参数为空数据获取失败')
            );
        }
    }

    /**
     * 删除文件
     * */
    public function delFileAjax()
    {
        $id = $this->post('id');
        $rs = m('file')->delete(" id = $id");
        if ($rs) {
            $this->returnjson(
                array('code' => 200, 'success' => true, 'msg' => '文件删除成功')
            );
        } else {
            $this->returnjson(
                array('code' => 201, 'success' => false, 'msg' => '文件删除失败')
            );
        }
    }



    /**
     * 结项报告提交
     * */
    public function jiexiangSubmitAjax()
    {
        $fileIdStr = $this->post('fileIdStr');
        $mid = $this->post('mid');
        $mode_num = $this->post('mode_num');
        $fileIdStr = array_filter(explode(',', $fileIdStr));
        $fileIdStr = implode(',', $fileIdStr);
        //结项报告里的文件与flow_bill关联(与申报书关联)
        $rs = m('file')->update(array('mid' => $mid, 'mtype' => $mode_num), "id in ($fileIdStr)");
        //判断该用户是否与高校关联
        $billData = m('flow_bill')->getone(" mid = $mid and `table`='$mode_num'");
        $uid = $billData['uid'];
        $adminData = m('admin')->getone(" id = $uid");
        $rb = null;
        $rg = null;
        if ($adminData['school_name']) {
            //根据nowcourseid获取当前流程环节的审核人以及审核人名字
            $bill_course = m('flow_course')->getone('id=102');
            $log_course = m('flow_course')->getone('id=101');
            $bill_arr = array(
                'nstatustext' => '待' . $bill_course['checktypename'] . '处理',
                'nowcourseid' => '102',
                'nowcheckid' => $bill_course['checktypeid'],
                'nowcheckname' => $bill_course['checktypename'],
                'updt' => date('Y-m-d H:i:s'),
            );
            $rm = m('flow_bill')->getone(" mid = $mid and `table`='$mode_num'");
            $log_arr = array(
                'table' => 'project_coursetask',
                'name' => $log_course['name'],
                'mid' => $rm['mid'],
                'courseid' => '101',
                'statusname' => '通过',
                'status' => '1',
                'optdt' => date('Y-m-d H:i:s'),
                'ip' => '::1',
                'web' => 'chrome',
                'checkname' => $log_course['checktypename'],
                'checkid' => $log_course['checktypeid'],
                'modeid' => $log_course['setid'],
                'color' => 'black',
                'valid' => '1',
                'step' => '1',
            );
            $rb = m('flow_bill')->update($bill_arr, " mid = $mid and `table`='$mode_num'");
            $rg = m('flow_log')->insert($log_arr);
        } else {
            //根据nowcourseid获取当前流程环节的审核人以及审核人名字
            $bill_course = m('flow_course')->getone('id=107');
            $log_course = m('flow_course')->getone('id=101');
            $bill_arr = array(
                'nstatustext' => '待' . $bill_course['checktypename'] . '处理',
                'nowcourseid' => '107',
                'nowcheckid' => $bill_course['checktypeid'],
                'nowcheckname' => $bill_course['checktypename'],
                'updt' => date('Y-m-d H:i:s'),
            );
            $rm = m('flow_bill')->getone(" mid = $mid and `table`='$mode_num'");
            $log_arr = array(
                'table' => 'project_coursetask',
                'name' => $log_course['name'],
                'mid' => $rm['mid'],
                'courseid' => '101',
                'statusname' => '通过',
                'status' => '1',
                'optdt' => date('Y-m-d H:i:s'),
                'ip' => '::1',
                'web' => 'chrome',
                'checkname' => $log_course['checktypename'],
                'checkid' => $log_course['checktypeid'],
                'modeid' => $log_course['setid'],
                'color' => 'black',
                'valid' => '1',
                'step' => '1',
            );
            $rb = m('flow_bill')->update($bill_arr, " mid = $mid and `table`='$mode_num'");
            $rg = m('flow_log')->insert($log_arr);
        }
        if ($rg && $rb) {
            echo json_encode(
                array('code' => 200, 'success' => true, 'msg' => '提交成功')
            );
        } else {
            echo json_encode(
                array('code' => 201, 'success' => false, 'msg' => '提交失败')
            );
        }
    }

    /**
     * 获取上传的文件
     * */
    public function UploadFilesDataAjax()
    {
        $mid = $this->post('mid');
        $mode_num = $this->post('mode_num');
        $rs = m('file')->getall("mid = $mid and mtype = '$mode_num'");
        if ($rs) {
            echo json_encode(
                array('success' => true, 'data' => $rs, 'code' => 200)
            );
        } else {
            echo json_encode(
                array('success' => true, 'code' => 201, 'msg' => '无数据或者获取数据失败')
            );
        }
    }

    /**
     * 结项报告审核
     * */
    public function checkJxReportAjax()
    {
        $type = $this->post('type');
        $checkResult = $this->post('checkResult');
        $checkSuggest = $this->post('checkSuggest');
        $modenum = $this->post('mode_num');
        $mid = $this->post('mid');
        $nowCourseId = $this->post('nowCourseId');
        if ($nowCourseId == 102) {
            $nextCourse = m('flow_course')->getone('id=107');
            $nowCourse = m('flow_course')->getone('id=102');
            $beforeCourse = m('flow_course')->getone('id=101');
            $arr = array();
            $log_arr = array();
            $mode_arr = array();
            if ($checkResult == 'result_success') {
                //审核通过
                $arr = array(
                    'nstatustext' => '待' . $nextCourse['checktypename'] . '处理',
                    'nowcourseid' => '107',
                    'nowcheckid' => $nextCourse['checktypeid'],
                    'nowcheckname' => $nextCourse['checktypename'],
                    'updt' => date('Y-m-d H:i:s'),
                    'checksm' => $checkSuggest
                );
                $log_arr = array(
                    'table' => $modenum,
                    'name' => $nowCourse['name'],
                    'mid' => $mid,
                    'courseid' => '102',
                    'statusname' => '通过',
                    'status' => '1',
                    'explain' => $checkSuggest,
                    'optdt' => date('Y-m-d H:i:s'),
                    'ip' => '::1',
                    'web' => 'chrome',
                    'checkname' => $nowCourse['checktypename'],
                    'checkid' => $nowCourse['checktypeid'],
                    'modeid' => $nowCourse['setid'],
                    'color' => 'black',
                    'valid' => '1',
                    'step' => '1',
                );
            } else if ($checkResult == 'result_false') {
                //审核不通过
                $arr = array(
                    'nstatustext' => '' . $nowCourse['checktypename'] . '审核不通过',
                    'nowcourseid' => '102',
                    'nstatus' => '5',
                    'status' => '5',
                    'nowcheckid' => $nowCourse['checktypeid'],
                    'nowcheckname' => $nowCourse['checktypename'],
                    'updt' => date('Y-m-d H:i:s'),
                    'checksm' => $checkSuggest
                );
                $log_arr = array(
                    'table' => $modenum,
                    'name' => $nowCourse['name'],
                    'mid' => $mid,
                    'courseid' => '102',
                    'statusname' => '不通过',
                    'status' => '3',
                    'optdt' => date('Y-m-d H:i:s'),
                    'explain' => $checkSuggest,
                    'ip' => '::1',
                    'web' => 'chrome',
                    'checkname' => $nowCourse['checktypename'],
                    'checkid' => $nowCourse['checktypeid'],
                    'modeid' => $nowCourse['setid'],
                    'color' => 'black',
                    'valid' => '1',
                    'step' => '1',
                );
                $mode_arr = array('status' => 5);
            } else if ($checkResult == 'result_return') {
                //审核退回
                $arr = array(
                    'nstatustext' => '' . $nowCourse['checktypename'] . '退回',
                    'nowcourseid' => '102',
                    'status' => '2',
                    'nstatus' => '2',
                    'nowcheckid' => $nowCourse['checktypeid'],
                    'nowcheckname' => $nowCourse['checktypename'],
                    'updt' => date('Y-m-d H:i:s'),
                    'checksm' => $checkSuggest
                );
                $log_arr = array(
                    'table' => $modenum,
                    'name' => $nowCourse['name'],
                    'mid' => $mid,
                    'courseid' => '102',
                    'statusname' => '退回修改',
                    'status' => '2',
                    'optdt' => date('Y-m-d H:i:s'),
                    'explain' => $checkSuggest,
                    'ip' => '::1',
                    'web' => 'chrome',
                    'checkname' => $nowCourse['checktypename'],
                    'checkid' => $nowCourse['checktypeid'],
                    'modeid' => $nowCourse['setid'],
                    'color' => 'black',
                    'valid' => '1',
                    'step' => '1',
                );
                $mode_arr = array('status' => 2);
            }
            $rs = m('flow_bill')->update($arr, " `table`='$modenum' and mid = $mid");
            $rb = m('flow_log')->insert($log_arr);
            if ($mode_arr) {
                $rg = m('' . $modenum)->update($mode_arr, "id = $mid");
            }
            if ($rs && $rb) {
                echo json_encode(
                    array('success' => true, 'code' => 200, 'msg' => '更新审核操作成功')
                );
            } else {
                echo json_encode(
                    array('success' => true, 'code' => 201, 'msg' => '更新审核操作失败')
                );
            }
        } else if ($nowCourseId == 107) {
            $nextCourse = m('flow_course')->getone('id=103');//结项评审
            $nowCourse = m('flow_course')->getone('id=107');
            $beforeCourse = m('flow_course')->getone('id=102');
            $arr = array();
            $log_arr = array();
            $mode_arr = array();
            if ($checkResult == 'result_success') {
                //审核通过
                $arr = array(
                    'nstatustext' => '待' . $nextCourse['checktypename'] . '处理',
                    'nowcourseid' => '103',
                    'nowcheckid' => $nextCourse['checktypeid'],
                    'nowcheckname' => $nextCourse['checktypename'],
                    'updt' => date('Y-m-d H:i:s'),
                    'checksm' => $checkSuggest
                );
                $log_arr = array(
                    'table' => $modenum,
                    'name' => $nowCourse['name'],
                    'mid' => $mid,
                    'courseid' => '107',
                    'statusname' => '通过',
                    'status' => '1',
                    'explain' => $checkSuggest,
                    'optdt' => date('Y-m-d H:i:s'),
                    'ip' => '::1',
                    'web' => 'chrome',
                    'checkname' => $nowCourse['checktypename'],
                    'checkid' => $nowCourse['checktypeid'],
                    'modeid' => $nowCourse['setid'],
                    'color' => 'black',
                    'valid' => '1',
                    'step' => '1',
                );
            } else if ($checkResult == 'result_false') {
                //审核不通过
                $arr = array(
                    'nstatustext' => '' . $nowCourse['checktypename'] . '审核不通过',
                    'nowcourseid' => '107',
                    'nstatus' => '5',
                    'status' => '5',
                    'nowcheckid' => $nowCourse['checktypeid'],
                    'nowcheckname' => $nowCourse['checktypename'],
                    'updt' => date('Y-m-d H:i:s'),
                    'checksm' => $checkSuggest
                );
                $log_arr = array(
                    'table' => $modenum,
                    'name' => $nowCourse['name'],
                    'mid' => $mid,
                    'courseid' => '107',
                    'statusname' => '不通过',
                    'status' => '3',
                    'optdt' => date('Y-m-d H:i:s'),
                    'explain' => $checkSuggest,
                    'ip' => '::1',
                    'web' => 'chrome',
                    'checkname' => $nowCourse['checktypename'],
                    'checkid' => $nowCourse['checktypeid'],
                    'modeid' => $nowCourse['setid'],
                    'color' => 'black',
                    'valid' => '1',
                    'step' => '1',
                );
                $mode_arr = array('status' => 5);
            } else if ($checkResult == 'result_return') {
                //审核退回
                $arr = array(
                    'nstatustext' => '' . $nowCourse['checktypename'] . '退回',
                    'nowcourseid' => '107',
                    'status' => '2',
                    'nstatus' => '2',
                    'nowcheckid' => $nowCourse['checktypeid'],
                    'nowcheckname' => $nowCourse['checktypename'],
                    'updt' => date('Y-m-d H:i:s'),
                    'checksm' => $checkSuggest
                );
                $log_arr = array(
                    'table' => $modenum,
                    'name' => $nowCourse['name'],
                    'mid' => $mid,
                    'courseid' => '107',
                    'statusname' => '退回修改',
                    'status' => '2',
                    'optdt' => date('Y-m-d H:i:s'),
                    'explain' => $checkSuggest,
                    'ip' => '::1',
                    'web' => 'chrome',
                    'checkname' => $nowCourse['checktypename'],
                    'checkid' => $nowCourse['checktypeid'],
                    'modeid' => $nowCourse['setid'],
                    'color' => 'black',
                    'valid' => '1',
                    'step' => '1',
                );
                $mode_arr = array('status' => 2);
            }
            $rs = m('flow_bill')->update($arr, " `table`='$modenum' and mid = $mid");
            $rb = m('flow_log')->insert($log_arr);
            if ($mode_arr) {
                $rg = m('' . $modenum)->update($mode_arr, "id = $mid");
            }
            if ($rs && $rb) {
                echo json_encode(
                    array('success' => true, 'code' => 200, 'msg' => '更新审核操作成功')
                );
            } else {
                echo json_encode(
                    array('success' => true, 'code' => 201, 'msg' => '更新审核操作失败')
                );
            }
        }
    }

    /**
     * 项目归档获取项目名称和学科分类
     * */
    public function getGdInfoAjax()
    {
        $mid = $this->post('mid');//流程模块主表单据id
        $modename = $this->post('modename');//流程模块名称
        $rs = m("$modename")->getone("id =$mid");
        if ($rs) {
            echo json_encode(
                array('success' => true, 'data' => $rs, 'code' => 200, 'msg' => '项目归档获取项目名称和学科分类成功')
            );
        } else {
            echo json_encode(
                array('success' => true, 'code' => 200, 'msg' => '项目归档获取项目名称和学科分类失败')
            );
        }
    }

    /**
     * 归档提交
     * */
    public function guidangSubmitAjax()
    {
        $fileIdStr = $this->post('fileIdStr');
        $mid = $this->post('mid');
        $mode_num = $this->post('mode_num');
        $fileIdStr = array_filter(explode(',', $fileIdStr));
        $fileIdStr = implode(',', $fileIdStr);
        //结项报告里的文件与flow_bill关联(与申报书关联)
        $rs = m('file')->update(array('mid' => $mid, 'mtype' => $mode_num), "id in ($fileIdStr)");
        $now_course = m('flow_course')->getone('id=91');
        $bill_arr = array(
            'nstatustext' => '' . $now_course['checktypename'] . '处理通过',
            'nowcourseid' => '0',
            'nowcheckid' => '',
            'nowcheckname' => '',
            'nstatus' => '1',
            'status' => '1',
            'updt' => date('Y-m-d H:i:s'),
        );
        /* var showgds = '<?=$da['arr']['flowinfor']['showgd']?>';*/
        $rm = m('flow_bill')->getone(" mid = $mid and `table`='$mode_num'");
        $log_arr = array(
            'table' => 'project_coursetask',
            'name' => $now_course['name'],
            'mid' => $rm['mid'],
            'courseid' => '91',
            'statusname' => '通过',
            'status' => '1',
            'optdt' => date('Y-m-d H:i:s'),
            'ip' => '::1',
            'web' => 'chrome',
            'checkname' => $now_course['checktypename'],
            'checkid' => $now_course['checktypeid'],
            'modeid' => $now_course['setid'],
            'color' => 'black',
            'valid' => '1',
            'step' => '1',
        );
        $mode_arr = array('status' => 1);
        $rn = m('flow_bill')->update($bill_arr, " mid = $mid and `table`='$mode_num'");
        $rv = m('flow_log')->insert($log_arr);
        $rt = m("$mode_num")->update($mode_arr, "id = $mid");

        if ($rs && $rn && $rv && $rt) {
            echo json_encode(
                array('success' => true, 'data' => $rs, 'code' => 200, 'msg' => '项目归档成功')
            );
        } else {
            echo json_encode(
                array('success' => true, 'data' => $rs, 'code' => 201, 'msg' => '项目归档失败')
            );
        }
    }

    /*
     * 评审详情获取课题设计论证活页
     * */
    public function getKtFileAjax(){
        $project_id = $this->post('project_id');
        $type = $this->post('mtype');
       $file_data = m('file')->getone("mid = $project_id and mtype = '$type' and upload_filetype = '课题设计论证(活页)'");
       if ($file_data){
           echo json_encode(array(
               'code' => 1,
               'msg' => '数据获取成功',
               'data'=> $file_data
           ));
       }else{
           echo json_encode(array(
               'code' => 0,
               'msg' => '无数据或者获取失败',
           ));
       }
    }
}//end
