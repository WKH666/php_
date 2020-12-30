<?php


class informationClassAction extends Action
{

    public function initAction(){}

    public function informationlistAction(){}

    public function informationbefore($table)
    {
        $sericnum 	= $this->post('sericnum');
        $project_name 	= $this->post('project_name');
        $modename = $this->post('modename');
        $where='';
        $uid 	= $this->adminid;
        $informationmodel = m('information');

        //获得当前登录者的职业
        $now_user_ranking = $this->getsession('adminranking');

//        //本地：申报者
//        if ($now_user_ranking == '申报者') {
//            /*申报者*/
//            //a.uid = $uid and a.nowcourseid <> 74 指 当前不包括到社科管理员审核节点的项目信息
//            $where .= " and a.uid = $uid and a.nowcourseid <> 74 and a.nowcourseid <> 81 and a.nowcourseid <> 84";
//        }
//        else if($now_user_ranking == '社科管理员'){
//            /*社科管理员*/
//            //假如当前uid = 56，allcheckid可能以 56 或 1,56,3 或 1,56 或 56,1的形式存储
//            $where	= " and (a.allcheckid = $uid or a.allcheckid like '%,$uid,%' or a.allcheckid like '%,$uid' or a.allcheckid like '$uid,%')";
//        }

        //测试站：申报者
        if ($now_user_ranking == '申报者') {
            /*申报者*/
            //a.uid = $uid and a.nowcourseid <> 74 指 当前不包括到社科管理员审核节点的项目信息
            //$where .= " and a.uid = $uid and a.nowcourseid <> 85 and a.nowcourseid <> 89 and a.nowcourseid <> 79 and a.status = 1";
            /*1027日修改后*/
            $where .= " and a.uid = $uid ";
        }
        else if($now_user_ranking == '社科管理员'){
            /*社科管理员*/
            //假如当前uid = 56，allcheckid可能以 56 或 1,56,3 或 1,56 或 56,1的形式存储
            $where	= " and (a.allcheckid = $uid or a.allcheckid like '%,$uid,%' or a.allcheckid like '%,$uid' or a.allcheckid like '$uid,%') and a.status = 1";
        }

        //查询
        if ($sericnum) {
            $where .= " and a.sericnum like '%$sericnum%'";
        }
        if ($project_name) {
            $where .= " and ( d.activity_name like '%$project_name%' or e.research_base like '%$project_name%' or f.project_name like '%$project_name%' )";
        }
        if ($modename){
            $where .= " and a.modename like '%$modename%'";
        }

        $table = "`[Q]flow_bill` a  left join `[Q]flow_course` c on c.id=a.nowcourseid";
        $table .= " left join `[Q]" . $informationmodel::$skcth . "` d on d.id=a.mid";
        $table .= " left join `[Q]" . $informationmodel::$researchbase . "` e on e.id=a.mid";
        $table .= " left join `[Q]" . $informationmodel::$skpjm . "` f on f.id=a.mid";
        $table .= " left join `[Q]" . $informationmodel::$coursetask . "` g on g.id=a.mid";

        return array(
            'table' => $table,
            'where' => " and a.isdel=0 $where",
            'fields'=> 'a.*,c.name,d.activity_name,e.research_base,f.project_name,g.course_name',
            'order' => 'a.optdt desc'
        );


    }

    public function informationafter($table,$rows){
        //单据数据
        $rows = m('information')->getbilldata($rows);
        return array(
            'rows'  => $rows
        );
    }

    /**
     * 申报者开题报告列表、管理员开题报告列表
     */
    public function report_listAjax(){
        $id=$this->adminid;
        $ranking=m('admin')->getone("id=$this->adminid","ranking");
        if ($ranking['ranking']=="申报者"){
            $table = 'xinhu_file as a left join xinhu_admin as b on a.optid=b.id';
            $fields = 'a.id,a.optid,a.nd_year,a.filename,a.upload_status,a.optname,b.deptname,a.adddt,a.fileext,a.filepath';
            $where = "a.upload_filetype='开题报告'and a.optid=$id";
            $order = 'id DESC';
            $this->getlist($table, $fields, $where, $order);
        }else{
            $table = 'xinhu_file as a left join xinhu_admin as b on a.optid=b.id';
            $fields = 'a.id,a.nd_year,a.filename,a.upload_status,a.optname,b.deptname,a.adddt,a.fileext,a.filepath';
            $where = "a.upload_filetype='开题报告' and a.upload_status=1";
            $order = 'id DESC';
            $this->getlist($table, $fields, $where, $order);
        }

    }
    /**
     * 公共的列表获取方法
     */
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
    /**
     * 上传和查看开题报告搜索功能
     */
    public function reportlistbefore(){
        $nd_year =trim($this->post('nd_year'));
        $filename =trim($this->post('filename'));
        $optname =trim($this->post('optname'));
        $deptname =trim($this->post('deptname'));
        $where=' ';
        //查询
        if ($nd_year) {
            $where .= "and a.nd_year like '%$nd_year%'";
        }
        if ($filename) {
            $where .= "and a.filename like '%$filename%'";
        }
        if ($optname) {
            $where .= "and a.optname like '%$optname%'";
        }
        if ($deptname) {
            $where .= "and b.deptname like '%$deptname%'";
        }
        return $where;
    }
    /**
     * 上传和查看开题报告操作获取
     */
    public function reportlistafter($table,$rows){
        $ranking=m('admin')->getone("id=$this->adminid",'ranking');
        if ($ranking['ranking']=='申报者') {
            foreach ($rows as $k => $rs) {
                $rows[$k]['caoz'] = '';
                if ($rs['upload_status'] == 0) {
                    $rows[$k]['upload_status'] = '草稿';
                    $rows[$k]['caoz'] .= '<a onclick="report_edit(' . $rs['id'] . ')">编辑</a>';
                    $rows[$k]['caoz'] .= '<span style="padding:5px;">|</span>';
                    $rows[$k]['caoz'] .= '<a onclick="report_del(' . $rs['id'] . ')">删除</a>';
                } else {
                    $rows[$k]['upload_status'] = '已提交';
                    $rows[$k]['caoz'] .= '<a  href="javascript:;" onclick="report_download(' . $rs['id'] . ',\'' . $rs['fileext'] . '\',\'' . $rs['filepath'] . '\')">下载</a>';
                }
            }
        } else {
            foreach ($rows as $k => $rs) {
                $rows[$k]['caoz'] = '';
                $rows[$k]['upload_status'] = '已提交';
                $rows[$k]['caoz'] .= '<a  href="javascript:;" onclick="report_download(' . $rs['id'] . ',\'' . $rs['fileext'] . '\',\'' . $rs['filepath'] . '\')">下载</a>';
                $rows[$k]['caoz'] .= '<span style="padding:5px;">|</span>';
                $rows[$k]['caoz'] .= '<a onclick="report_del(' . $rs['id'] . ')">删除</a>';
            }
        }
        return $rows;
    }
    /**
     * 开题报告上传按钮保存操作
     */
    public function change_statusAjax(){
        $id = $_POST['id'];
        $nd = $_POST['nd'];
        $upload_status = $_POST['upload_status'];
        $arr=array(
            'upload_status'=> $upload_status,
            'nd_year'=> $nd
        );
        $bools = m("xfile")->update($arr,"id=".$id."");
        $this->returnjson($bools);
    }


}
