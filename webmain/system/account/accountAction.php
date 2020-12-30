<?php
class accountClassAction extends  Action
{
    public function initAction()
    {

    }

    /**
     * @return array
     * 申报账号
     */
    public function acdeclarebefore(){
        $user = $this->post('user');
        $name = $this->post('name');
        $deptname = $this->post('deptname');
        $where = " ";
        if($user){
            $where .= " and xinhu_admin.user like '%$user%'";
        }
        if($name){
            $where .= " and xinhu_admin.name like '%$name%'";
        }
        if($deptname){
            $where .= " and xinhu_admin.deptname like '%$deptname%'";
        }

        return array(
            'table' => "xinhu_admin a",
            'where' => " and account_type='申报' $where",
            'fields'=> 'a.*',
            'order' => 'a.adddt desc'
        );

    }

    public function acdeclareafter($table,$rows){
        foreach($rows as $k => $rs){
            $rows[$k]['caoz'] = '';
            $rows[$k]['caoz'] .= '<a onclick="ad_check('.$rs['id'].')">查看</a>';
            $rows[$k]['caoz'] .= '<span style="margin: 0 5px;">|</span>';
            $rows[$k]['caoz'] .= '<a onclick="ad_edit('.$rs['id'].')">编辑</a>';
            $rows[$k]['caoz'] .= '<span style="margin: 0 5px;">|</span>';
            $rows[$k]['caoz'] .= '<a onclick="ad_del('.$rs['id'].')">删除</a>';
            $date = date_create($rows[$k]['adddt']);
            $rows[$k]['adddt'] = date_format($date, 'Y-m-d');
            if($rows[$k]['status']){
                $rows[$k]['status_text'] = '正常';
            }else{
                $rows[$k]['status_text'] = '冻结';
            }
        }
        return array(
            'rows' => $rows
        );
    }


    /**
     * @return array
     * 专家账号
     */
    public function acexpertbefore(){
        $user = $this->post('user');
        $name = $this->post('name');
        $deptname = $this->post('deptname');
        $res_dir = $this->post('res_dir');
        $where = '';
        if($user){
            $where .= " and xinhu_admin.user like '%$user%'";
        }
        if($name){
            $where .= " and xinhu_admin.name like '%$name%'";
        }
        if($deptname){
            $where .= " and xinhu_admin.deptname like '%$deptname%'";
        }
        if($res_dir){
            $where .= " and xinhu_perfect_data.res_dir like '%$res_dir%'";
        }
        return array(
            'table' => "xinhu_admin a LEFT JOIN xinhu_perfect_data b ON a.id = b.adminid",
            'where' => " and account_type='专家' $where",
            'fields'=> 'a.*,b.subjectid,b.head_subject,b.res_dir',
            'order' => 'a.adddt desc'
        );

    }

    public function acexpertafter($table,$rows){
        foreach($rows as $k => $rs){
            $rows[$k]['caoz'] = '';
            $rows[$k]['caoz'] .= '<a onclick="ad_check('.$rs['id'].')">查看</a>';
            $rows[$k]['caoz'] .= '<span style="margin: 0 5px;">|</span>';
            $rows[$k]['caoz'] .= '<a onclick="ad_edit('.$rs['id'].')">编辑</a>';
            $rows[$k]['caoz'] .= '<span style="margin: 0 5px;">|</span>';
            $rows[$k]['caoz'] .= '<a onclick="ad_del('.$rs['id'].')">删除</a>';
            $date = date_create($rows[$k]['adddt']);
            $rows[$k]['adddt'] = date_format($date, 'Y-m-d');
            if($rows[$k]['status']){
                $rows[$k]['status_text'] = '正常';
            }else{
                $rows[$k]['status_text'] = '冻结';
            }
        }
        return array(
            'rows' => $rows
        );
    }


    /**
     * @return array
     * 政府账号
     */
    public function acgovernmentbefore(){
        $user = $this->post('user');
        $name = $this->post('name');
        $deptname = $this->post('deptname');
        $where = '';
        if($user){
            $where .= " and xinhu_admin.user like '%$user%'";
        }
        if($name){
            $where .= " and xinhu_admin.name like '%$name%'";
        }
        if($deptname){
            $where .= " and xinhu_admin.deptname like '%$deptname%'";
        }
        return array(
            'table' => "xinhu_admin a",
            'where' => " and account_type='政府' $where",
            'fields'=> 'a.*',
            'order' => 'a.adddt desc'
        );

    }

    public function acgovernmentafter($table,$rows){
        foreach($rows as $k => $rs){
            $rows[$k]['caoz'] = '';
            $rows[$k]['caoz'] .= '<a onclick="ad_check('.$rs['id'].')">查看</a>';
            $rows[$k]['caoz'] .= '<span style="margin: 0 5px;">|</span>';
            $rows[$k]['caoz'] .= '<a onclick="ad_edit('.$rs['id'].')">编辑</a>';
            $rows[$k]['caoz'] .= '<span style="margin: 0 5px;">|</span>';
            $rows[$k]['caoz'] .= '<a onclick="ad_del('.$rs['id'].')">删除</a>';
            $date = date_create($rows[$k]['adddt']);
            $rows[$k]['adddt'] = date_format($date, 'Y-m-d');
            if($rows[$k]['status']){
                $rows[$k]['status_text'] = '正常';
            }else{
                $rows[$k]['status_text'] = '冻结';
            }
        }
        return array(
            'rows' => $rows
        );
    }


    /**
     * @return array
     * 单位账号
     */
    public function acunitbefore(){
        $user = $this->post('user');
        $name = $this->post('name');
        $deptname = $this->post('deptname');
        $res_dir = $this->post('res_dir');
        $where = '';
        if($user){
            $where .= " and xinhu_admin.user like '%$user%'";
        }
        if($name){
            $where .= " and xinhu_admin.name like '%$name%'";
        }
        if($deptname){
            $where .= " and xinhu_admin.deptname like '%$deptname%'";
        }
        if($res_dir){
            $where .= " and xinhu_perfect_data.res_dir like '%$res_dir%'";
        }
        return array(
            'table' => "xinhu_admin a LEFT JOIN xinhu_perfect_data b ON a.id = b.adminid",
            'where' => " and account_type='单位' $where",
            'fields'=> 'a.*,b.subjectid,b.head_subject,b.res_dir',
            'order' => 'a.adddt desc'
        );

    }

    public function acunitafter($table,$rows){
        foreach($rows as $k => $rs){
            $rows[$k]['caoz'] = '';
            $rows[$k]['caoz'] .= '<a onclick="ad_check('.$rs['id'].')">查看</a>';
            $rows[$k]['caoz'] .= '<span style="margin: 0 5px;">|</span>';
            $rows[$k]['caoz'] .= '<a onclick="ad_edit('.$rs['id'].')">编辑</a>';
            $rows[$k]['caoz'] .= '<span style="margin: 0 5px;">|</span>';
            $rows[$k]['caoz'] .= '<a onclick="ad_del('.$rs['id'].')">删除</a>';
            $date = date_create($rows[$k]['adddt']);
            $rows[$k]['adddt'] = date_format($date, 'Y-m-d');
            if($rows[$k]['status']){
                $rows[$k]['status_text'] = '正常';
            }else{
                $rows[$k]['status_text'] = '冻结';
            }
        }
        return array(
            'rows' => $rows
        );
    }


    /**
     * @return array
     * 审核账号
     */
    public function acdeclare_examinebefore(){
        $user = $this->post('user');
        $name = $this->post('name');
        $deptname = $this->post('deptname');
        $where = '';
        if($user){
            $where .= " and xinhu_admin.user like '%$user%'";
        }
        if($name){
            $where .= " and xinhu_admin.name like '%$name%'";
        }
        if($deptname){
            $where .= " and xinhu_admin.deptname like '%$deptname%'";
        }
        return array(
            'table' => "xinhu_admin a LEFT JOIN xinhu_examine_declare c ON a.id = c.adminid ",
            'where' => " and account_type='申报' $where",
            'fields'=> 'a.*,c.examine_status',
            'order' => 'a.adddt desc'
        );
    }

    public function acdeclare_examineafter($table,$rows){
        foreach($rows as $k => $rs){
            $rows[$k]['caoz'] = '';
            $rows[$k]['caoz'] .= '<a onclick="de_examine('.$rs['id'].')">审核</a>';
            $date = date_create($rows[$k]['adddt']);
            $rows[$k]['adddt'] = date_format($date, 'Y-m-d');
        }
        return array(
            'rows' => $rows
        );
    }



}
