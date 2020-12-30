<?php

Class process_supervisionClassAction extends Action
{
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
     * 申报监管列表
     */
    public function apply_supAjax()
    {
        $table = trim($this->post('table'));
        $name = trim($this->post('name'));
        $where = '';
        //查询
        if ($table) {
            $where .= " and a.table LIKE '%$table%'";
        }
        if ($name) {
            $where .= " and b.name like '%$name%'";
        }
        $row = array_column($this->db->getall("SELECT id FROM xinhu_admin"), "id");
        $uid = implode(",", $row);
        $row2 = $this->db->getall("SELECT a.id,a.uid,b.name,b.deptname,date_format(a.createdt,'%Y')as year FROM xinhu_flow_bill as a left join xinhu_admin as b on a.uid = b.id WHERE a.table='project_coursetask' and  uid in ($uid)");
        $temp = [];
        $rows = array();
        foreach ($row2 as $val) {
            $temp[$val['name'] . '|' . $val['year']][] = $val;
        }
        $result = array_values(array_filter($temp, function ($item) {
            return count($item) >= 2;
        }));
        foreach ($result as $v) {
            $flow_id = array_column($v, "id");
            $count = count($flow_id);
            for ($i = 0; $i < count($v) - 1; $i++) {
                $source = $v[$i];
                foreach ($v as $k => $value) {
                    if ($source['name'] == $value['name'] && $source['year'] == $value['year'] && $k != $i) {
                        unset($v[$k]);
                    }
                }
            }
            $year = implode(",",array_column($v, "year"));
            $name = implode(",",array_column($v, "name"));
            $deptname = implode(array_column($v, "deptname"));
            $uid = implode(",",array_column($v, "uid"));
            $arr = array(
                'year' => $year,
                'table' => "课题申报",
                'name' => $name,
                'deptname' => $deptname,
                'count' => $count,
                'caoz' => '<a onclick="readsupDetail('.$uid.  ')">查看</a>',
            );
            array_push($rows, $arr);
        }
        $datas = array('rows' => array_reverse($rows), 'totalCount' => count($rows));
        $this->returnjson($datas);
    }

    /**
     * 申报监管详情
     */
    public function apply_sup_detailAjax()
    {
        $uid =$_POST['uid'];
        $row = $this->db->getall("SELECT a.id FROM xinhu_flow_bill as a WHERE a.table='project_coursetask' and  a.uid=$uid");
        $flow_id = implode(",",array_column($row, "id"));
        $table = '[Q]flow_bill as a 
                  left join [Q]project_coursetask as b on a.mid=b.id    
                  left join [Q]admin as c on a.uid=c.id  ';
        $fields = 'a.table,b.course_name,a.createdt as day,c.name,c.deptname,date_format(a.createdt,"%Y")as year';
        $where = "a.id in ($flow_id)";
        $order = '';
        $this->getlist($table, $fields, $where,$order);
    }
    public function apply_sup_detailafter($table,$rows){
      foreach ($rows as $k=>$rs){
          if ($rs['table']=="project_coursetask"){
              $rows[$k]['table'] = '课题申报';
          }
          $rows[$k]['day'] = date("Y-m-d",strtotime($rs['day']));
          $rows[$k]['caoz']='';
          $rows[$k]['caoz'] .= '<a>查看</a>';
      }
        return $rows;
    }
}