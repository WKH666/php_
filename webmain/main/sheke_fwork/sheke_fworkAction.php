<?php

class sheke_fworkClassAction extends Action
{
    /*
     * 项目审批中的已处理和未处理数据操作
     * */
    /**
     * 查询前置操作：组合相关sql
     * @param $table
     * @return array
     */
	public function flowbillbefore($table)
	{
		$lx 	= $this->post('atype');
        $sericnum 	= $this->post('sericnum');
        $project_name 	= $this->post('project_name');
        $apply_type 	= $this->post('apply_type');
		$uid 	= $this->adminid;
		$flowbill_sheke_model = m('flowbill_sheke');
        //获得当前登录者的职业
		$now_user_ranking = $this->getsession('adminranking');
		$before_course_id = $this->db->getall("SELECT a.id as flow_id,a.nowcourseid,b.sort,a.modeid FROM xinhu_flow_bill as a LEFT JOIN xinhu_flow_course as b on a.nowcourseid=b.id where nowcheckid!=$uid");
		$where = '';
        $new = array();
        if ($now_user_ranking == '申报者') {
            /*申报者*/
            //项目审批中的未处理
            if($lx=='weichuli') {
                //a.uid=$uid and a.nowcourseid=0 指 草稿：发布者id（uid）+ 当前步骤Id（nowcourseid）为0
                //a.nowcheckid=$uid 指 当前审核人id
                $where	.= " and ( ( ((a.uid=$uid and a.nowcourseid=0) or a.nowcheckid=$uid )";
                $where	.= " and a.status=0 )";//状态：0为处理中 2为退回修改
                $where	.= " or (a.uid=$uid and a.status=2) )";//状态：2为退回修改
            }
            //项目审批中的已处理
            if($lx=='yichuli') {
//                $where	= " and a.uid=$uid";
//                $where	.= " and a.status in (1,5) ";
               foreach ($before_course_id as $k=>$v) {
                   $rs = m('flow_course')->getall("setid = " . $v['modeid'] ." and sort<".$v['sort'].  " and checktypeid=" . $uid);
                   if($rs){
                       $arr = array('flow_id' => $v['flow_id']);
                       $new[] = $arr;
                   }
               }
               $flow_id = array_column($new,'flow_id');
               $flow_id_str = implode(",",$flow_id);
               $where	.= " and a.id in ($flow_id_str)";
            }
            //项目申报中的未处理
            if($lx=='selfweichuli'){
                $where	.= " and (a.uid=$uid";
                $where	.= " and a.status=0";//状态：0为处理中 2为退回修改
                $where	.= " or (a.uid=$uid and a.status=2))";//状态：2为退回修改
            }
            //项目申报中的已处理
            if($lx=='selfyichuli'){
                $where	.= " and a.uid=$uid";
                $where	.= " and a.status =1 ";
            }
        }else {
            //项目审批中的未处理
            if($lx=='weichuli') {
                $where	.= " and a.nowcheckid=$uid ";
                $where	.= " and a.status=0";
            }
            //项目审批中的已处理
            if($lx=='yichuli') {
                //假如当前uid = 56，allcheckid可能以 56 或 1,56,3 或 1,56 的形式存储
//                $where	= " and (a.allcheckid=$uid or a.allcheckid like '%,$uid,%' or a.allcheckid like '%,$uid')";
//                $where	.= " and a.status in (0,1,2,5) ";
                foreach ($before_course_id as $k=>$v) {
                    $rs = m('flow_course')->getall("setid = " . $v['modeid'] ." and sort<".$v['sort'].  " and checktypeid=" . $uid);
                    if($rs){
                        $arr = array('flow_id' => $v['flow_id']);
                        $new[] = $arr;
                    }
                }
                $flow_id = array_column($new,'flow_id');
                $flow_id_str = implode(",",$flow_id);
                $where	.= " and a.id in ($flow_id_str)";
            }
            //项目申报中的未处理
            if($lx=='selfweichuli') {
                $where	= " and a.uid=$uid ";
                $where	.= " and a.status=0";
            }
            //项目申报中的已处理
            if($lx=='selfyichuli') {
                //假如当前uid = 56，allcheckid可能以 56 或 1,56,3 或 1,56 的形式存储
                $where	= " and (a.uid=$uid)";
                $where	.= " and a.status in (1,2,5) ";
            }
        }


        //查询
        if ($sericnum) {
            $where .= " and a.sericnum like '%$sericnum%'";
        }
        if ($project_name) {
            $where .= " and ( d.activity_name like '%$project_name%' or e.research_base like '%$project_name%' or f.project_name like '%$project_name%' )";
        }
        if ($apply_type) {
            if ($apply_type == '常态化科普申报') {
                $apply_type = $flowbill_sheke_model::$skcth;
            } elseif ($apply_type == '研究基地申报') {
                $apply_type = $flowbill_sheke_model::$researchbase;
            } elseif ($apply_type == '普及月申报') {
                $apply_type = $flowbill_sheke_model::$skpjm;
            } elseif($apply_type == '课题申报') {
                $apply_type = $flowbill_sheke_model::$coursetask;
            }
            $where .= " and a.table='$apply_type'";
        }

        $table = '`[Q]flow_bill` a left join `[Q]admin` b on a.uid=b.id';
        $table .= " left join `[Q]flow_course` c on c.id=a.nowcourseid";
        $table .= " left join `[Q]" . $flowbill_sheke_model::$skcth . "` d on d.id=a.mid";
        $table .= " left join `[Q]" . $flowbill_sheke_model::$researchbase . "` e on e.id=a.mid";
        $table .= " left join `[Q]" . $flowbill_sheke_model::$skpjm . "` f on f.id=a.mid";
        $table .= " left join `[Q]" . $flowbill_sheke_model::$coursetask . "` g on g.id=a.mid";

		return array(
			'table' => $table,
			'where' => " and a.isdel=0 $where",
			'fields'=> 'a.*,c.name as apply_progress,d.activity_name,e.research_base,f.project_name,g.course_name',
			'order' => 'a.optdt desc'
		);
	}
    /**
     * 查询后置操作
     * @param $table
     * @param $rows
     * @return array|bool
     */
    public function flowbillafter($table, $rows)
    {
        $rows = m('flowbill_sheke')->getbilldata($rows);
        return array(
            'rows'		=> $rows,
            'flowarr' 	=> m('mode')->getmodemyarr($this->adminid)
        );
    }

    public function review_listAjax(){
        $table = 'xinhu_flow_bill as a left join xinhu_admin as b on a.uid=b.id';
        $table .= " left join xinhu_flow_course as c on c.id=a.nowcourseid";
        $table .= " left join xinhu_project_skcth as d on d.id=a.mid";
        $table .= " left join xinhu_project_researchbase as e on e.id=a.mid";
        $table .= " left join xinhu_project_skpjm as f on f.id=a.mid";
        $table .= " left join xinhu_project_coursetask as g on g.id=a.mid";
        $fields = 'a.id,a.mid,a.sericnum,a.table,a.character_status,a.change_type,a.optdt,c.name as apply_progress,d.activity_name,e.research_base,f.project_name,g.course_name';
        $where = 'a.isdel=0 and a.is_change=1 and change_course=2';
        $order = 'a.optdt desc';
        $this->getlist($table, $fields, $where, $order);
    }
    public function getlist($table,$fields,$where,$order,$childtable=''){
        $beforea = $this->request('storebeforeaction');//数据权限处理函数
        $aftera = $this->request('storeafteraction');//操作权限处理函数
        if($beforea != ''){//数据权限处理
            if(method_exists($this, $beforea)){
                $where .= $this->$beforea();
            }
        }
        $arr = $this->limitRows($table,$fields,$where,$order);
        $arr['totalCount'] = $arr['total'];
        unset($arr['sql'],$arr['total']);
        //echo $arr['sql'];exit;
        //if($arr['totalCount'] == 0) exit('暂无数据');
        if(method_exists($this, $aftera)){//操作菜单权限处理
            $narr	= $this->$aftera($childtable,$arr['rows']);
            if(is_array($narr)){
                foreach($narr as $kv=>$vv)$arr['rows'][$kv]=$vv;
            }
        }

        $this->returnjson($arr);
    }
    public function review_after($table, $rows)
    {
        foreach($rows as $k=>$rs){
            $rows[$k]['zt']='';
            $rows[$k]['caoz']='';
            $rows[$k]['ct']='';
            $rows[$k]['pn']='';
            if ($rs['table']=='project_skcth'){
//                getone("user='$v'",'id');
                //"id=" .$v. ""
                $a=m('project_skcth')->getone("id=".$rs['mid']."",'activity_name');
                $rows[$k]['pn'] = $a['activity_name'];
            }else if($rs['table']=='project_researchbase'){
                $b=m('project_researchbase')->getone("id=".$rs['mid']."",'research_base');
                $rows[$k]['pn'] = $b['research_base'];
            }
            else if($rs['table']=='project_coursetask'){
                $c=m('project_coursetask')->getone("id=".$rs['mid']."",'course_name');
                $rows[$k]['pn'] = $c['course_name'];
            }else{
                $d=m('project_skpjm')->getone("id=".$rs['mid']."",'project_name');
                $rows[$k]['pn'] = $d['project_name'];
            }
            if ($rs['character_status']==0){
                    $rows[$k]['zt']='待审核';
                    $rows[$k]['caoz'].= '<a onclick="reviewcaoz('.$rs['id'].')">操作</a>';
            }else if ($rs['character_status']==1){
                    $rows[$k]['zt']='通过';
                    $rows[$k]['caoz'].= '<a onclick="reviewcheck('.$rs['id'].')">查看</a>';
            }else{
                    $rows[$k]['zt']='未通过';
                    $rows[$k]['caoz'].= '<a onclick="reviewcheck('.$rs['id'].')">查看</a>';
            }
            switch ($rs['change_type']){
                case 0:
                    $rows[$k]['ct']='变更项目负责人';
                    break;
                case 1:
                    $rows[$k]['ct']='变更或增加课题组成员';
                    break;
                case 2:
                    $rows[$k]['ct']='变更项目管理单位';
                    break;
                case 3:
                    $rows[$k]['ct']='改变成果形式';
                    break;
                case 4:
                    $rows[$k]['ct']='改变项目名称';
                    break;
                case 5:
                    $rows[$k]['ct']='研究内容有重大调整';
                    break;
                case 6:
                    $rows[$k]['ct']='延期';
                    break;
                case 7:
                    $rows[$k]['ct']='撤项';
                    break;
                default:
                    $rows[$k]['ct']='其他';
            }

        }
        return $rows;
    }
    public function review_before()
    {
        $sericnum 	= $this->post('sericnum');
        $project_name 	= trim($this->post('project_name'));
        $change_type =$this->post('change_type');
        $where = '';
        //查询
        if ($sericnum) {
            $where .= " and a.sericnum like '%$sericnum%'";
        }
        if ($project_name) {
            $where .= " and ( d.activity_name like '%$project_name%' or e.research_base like '%$project_name%' or f.project_name like '%$project_name%' )";
        }
        if (isset($change_type)){
            $where .= " and a.change_type like '%$change_type%'";
        }
//        if ($change_type==0){
//            $where .= " and a.change_type like '%$change_type%'";
//        }
        return $where;
    }



    public function get_czAjax(){
        $id = $_POST['id'];
        $rows = m("flowbill_sheke")->getone("id=".$id."");
        $bg_book=m("file")->getone("flow_id=$id and upload_filetype='项目变更书'");
        $kt_book=m("file")->getone("flow_id=$id and upload_filetype='变更后课题申报书'");
        if ($rows['table']=='project_skcth'){
            $a=m('project_skcth')->getone("id=".$rows['mid']."",'activity_name');
            $project_name=$a['activity_name'];
        }else if($rows['table']=='project_researchbase'){
            $b=m('project_researchbase')->getone("id=".$rows['mid']."",'research_base');
            $project_name=$b['research_base'];
        }
        else if($rows['table']=='project_coursetask'){
            $c=m('project_coursetask')->getone("id=".$rows['mid']."",'course_name');
            $project_name=$c['course_name'];
        }else{
            $d=m('project_skpjm')->getone("id=".$rows['mid']."",'project_name');
            $project_name=$d['project_name'];
        }
        $table_name=$rows['table'];
        $mid=$rows['mid'];
        $uid=m("$table_name")->getone("id=$mid","uid");
        $nstatus=$rows['nstatus'];
        array_push($rows,$project_name);
        array_push($rows,$bg_book);
        array_push($rows,$kt_book);
        array_push($rows,$nstatus);
        array_push($rows,$uid['uid']);
        m("$table_name")->update("uid=1","id=$mid");
        m("flowbill_sheke")->update("nstatus=0","id=$id");

        $this->returnjson($rows);
    }
    public function project_course_taskAjax(){
        $id = $_POST['id'];
        $rows=m('project_coursetask')->getone("id=".$id."");
        $this->returnjson($rows);
    }
    public function savecaozAjax(){
        $id = $_POST['id'];
        $change_type = $_POST['change_type'];
        $change_remark = $_POST['change_remark'];
        $audit_opinion = $_POST['audit_opinion'];
        $is_delay = $_POST['is_delay'];
        $audit_result = $_POST['audit_result'];
        $delay_day = $_POST['delay_day'];
        $nstatus = $_POST['nstatus'];
        $uid=$_POST['uid'];
        $mid=$_POST['mid'];
        $table=$_POST['table'];
        m("$table")->update("uid=$uid","id=$mid");
        m("flowbill_sheke")->update("nstatus=$nstatus","id=$id");
        if ($audit_result==0){
            $s=2;
        }else{
            $s=1;
        }
        $rows=array(
          'change_type' => $change_type,
          'change_remark' => $change_remark,
          'is_delay' =>  $is_delay,
          'delay_day' => $delay_day,
          'admin_status' => $s
        );
        $lines=array(
            'mid' => $id,
            'audit_opinion' => $audit_opinion,
            'audit_result' => $audit_result,
            'audit_time' => date("Y-m-d"),
            'uid' => $this->adminid
        );
        if ($rows){
            m('flowbill_sheke')->update($rows,"id=".$id."");
            m('flowbill_record1')->insert($lines);
            return $this->returnjson($rows);
        }else{
            return false;
        }

    }
    public function audit_recordAjax(){
        $id = $_POST['id'];
        $table = 'xinhu_flow_bill_record1 as a left join xinhu_admin as b on a.uid=b.id';
        $fields = 'a.*,b.ranking';
        $where = "a.mid=$id";
        $order = 'a.id desc';
        $this->getlist($table, $fields, $where, $order);
    }
    public function qxcaozAjax(){
        $id = $_POST['id'];
        $uid = $_POST['uid'];
        $mid = $_POST['mid'];
        $table = $_POST['table'];
        $nstatus = $_POST['nstatus'];
        m("$table")->update("uid=$uid","id=$mid");
        $rows=m("flowbill_sheke")->update("nstatus=$nstatus","id=$id");
        $this->returnjson($rows);
    }
    public function audit_record_after($table, $rows)
    {
        foreach($rows as $k=>$rs) {
            $rows[$k]['zt'] = '';
            $rows[$k]['name']='';
            $rows[$k]['name']=$rs['ranking'];
            if ($rs['audit_result'] == 0) {
                $rows[$k]['zt'] = '未通过';
            }else{
            $rows[$k]['zt'] = '通过';
            }
        }
            return $rows;
        }




}
