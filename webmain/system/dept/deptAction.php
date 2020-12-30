<?php
class deptClassAction extends Action
{
	
	
	public function dataAjax()
	{
		$this->rows	= array();
		$this->getdept(0, 1);
		
		echo json_encode(array(
			'totalCount'=> 0,
			'rows'		=> $this->rows
		));
	}
	
	private function getdept($pid, $oi)
	{
		$db		= m('dept');
		$menu	= $db->getall("`pid`='$pid' order by `sort`",'*');
		foreach($menu as $k=>$rs){
			$sid			= $rs['id'];
			$rs['level']	= $oi;
			$rs['stotal']	= $db->rows("`pid`='$sid'");
			$this->rows[] = $rs;
			
			$this->getdept($sid, $oi+1);
		}
	}
	
	public function publicbeforesave($table, $cans, $id)
	{
		$pid = (int)$cans['pid'];
		if($pid==0 && $id != 1)return '上级ID不能为0';
		if($pid!=0 && $id == 1)return '顶级禁止修改上级ID';
		if($pid>0 && m($table)->rows($pid)==0)return '上级ID不存在';
		return '';
	}
	
	public function publicaftersave($table, $cans, $id)
	{
		$name 	= $cans['name'];
		m('dept_check')->update("THMC='$name'", "`THMC_ID`=$id");
		$db 	= m('admin');
		$db->update("deptname='$name'", "`deptid`=$id");
		$db->updateinfo();
	}
	
	public function deptuserdataAjax()
	{
		$type 	= $this->request('changetype');
		$val 	= $this->request('value');
		$pid	= 0;
		$rows	= $this->getdeptmain($pid, $type, ','.$val.',');
		echo json_encode($rows);
	}
	
	private function getdeptmain($pid, $type, $val)
	{
		$sql	= $this->stringformat('select `id`,`name` from `?0` where `pid`=?1 order by `sort`', array($this->T('dept'), $pid));
		$arr	= $this->db->getall($sql);
		$rows	= array();
		foreach($arr as $k=>$rs){
			$children		= $this->getdeptmain($rs['id'], $type, $val);
			$uchek			= $this->contain($type, 'check');
			$expanded		= false;
			if($this->contain($type, 'user')){
				$sql	= $this->stringformat('select `id`,`name`,`sex`,`ranking`,`deptname` from `?0` where `deptid`=?1 and `status`=1 order by `sort`', array($this->T('admin'), $rs['id']));			
				$usarr	= $this->db->getall($sql);
				foreach($usarr as $k1=>$urs){
					$usarr[$k1]['leaf'] = true;
					$usarr[$k1]['uid']  = $urs['id'];
					$usarr[$k1]['id']   = 'u'.$urs['id'];
					$usarr[$k1]['type'] = 'u';
					$usarr[$k1]['icons'] = 'user';
					if($uchek){
						$bo = false;
						if($this->contain($type, 'dept')){
							$bo = $this->contain($val, $usarr[$k1]['id']);
						}else{
							$bo = $this->contain($val, $usarr[$k1]['uid']);
						}
						$usarr[$k1]['checked']=$bo;
						if(!$expanded)$expanded = $bo;
					}	
				}
				$children= array_merge($children, $usarr);
			}
			if($pid==0)$expanded = true;
			$ars['children']= $children;
			$ars['name'] 	= $rs['name'];
			$ars['id'] 		= 'd'.$rs['id'];
			$ars['did'] 	= $rs['id'];
			$ars['type'] 	= 'd';
			$ars['expanded'] = $expanded;
			
			if($this->contain($type, 'dept')){
				if($uchek){
					$bo = false;
					if($this->contain($type, 'user')){
						$bo = $this->contain($val, $ars['id']);
					}else{
						$bo = $this->contain($val, $ars['did']);
					}
					$ars['checked']=$bo;
				}	
			}
			$rows[]	= $ars;
		}
		return $rows;
	}
	
	public function deptuserjsonAjax()
	{
		$deptarr 	= m('dept')->getdata();
		$userarr 	= m('admin')->getuser(1);
		$arr['deptjson']	= json_encode($deptarr);
		$arr['userjson']	= json_encode($userarr);
		$this->showreturn($arr);
	}

    /**
     * 组织架构页面
     */
    public function deptAction(){}

    /**
     * 组织架构异步加载
     */
    public function request_deptAjax(){
        $rows = $this->db->getall("select id,pid,name,mobile,controller,num from xinhu_dept");
        foreach($rows as $k => $v){
            if($rows[$k]['pid'] == 0){
                $rows[$k]['isParent'] = "true";
                foreach($rows as $n => $m){
                    if($rows[$k]['id'] == $rows[$n]['pid']) {
                        $rows[$n]['isParent'] = "true";
                    }
                }
            }
        }
        if($rows){
            $this->returnjson($rows);
        }else{
            echo '数据加载失败';
        }

    }

    /**
     * 组织架构搜索栏
     */
    public function search_deptsortAjax(){
        $name = $this->rock->post('name');
        $rows = $this->db->getall("select * from xinhu_dept where name like '%$name%'");
        foreach($rows as $k => $v){
            if($rows[$k]['pid'] == 0){
                $rows[$k]['isParent'] = "true";
                $rows[$k]['open'] = "true";
                foreach($rows as $n => $m){
                    if($rows[$k]['id'] == $rows[$n]['pid']) {
                        $rows[$n]['isParent'] = "true";
                        $rows[$n]['open'] = "true";
                    }
                }
            }
        }
        if($rows){
            $this->returnjson($rows);
        }else{
            echo '数据加载失败';
        }

    }

    /**
     * 组织架构树形添加
     */
    public function addicon_deptsort_uploadAjax(){
        $pid = $this->rock->post('pid');
        $num = $this->rock->post('num');
        $name = $this->rock->post('name');
//        $controller = $this->rock->post('controller');
//        $mobile = $this->rock->post('mobile');
        $arr['pid'] = $pid;
        $arr["num"] = $num;
        $arr["name"] = $name;
//        $arr["controller"] = $controller;
//        $arr["mobile"] = $mobile;
        $row = m("dept")->insert($arr);
        if($row){
            $rows = $this->db->getall("select id,pid,name,mobile,controller,num from xinhu_dept");
            foreach($rows as $k => $v){
                if($rows[$k]['pid'] == 0){
                    $rows[$k]['isParent'] = "true";
                    foreach($rows as $n => $m){
                        if($rows[$k]['id'] == $rows[$n]['pid']) {
                            $rows[$n]['isParent'] = "true";
                        }
                    }
                }
            }
            $this->returnjson($rows);
        }else{
            echo '数据添加失败';
        }

    }

    /**
     * 异步传输组织名称
     */
    public function request_personAjax(){
        $name = $this->rock->post('name');
        $_SESSION['name'] = $name;
    }

    /**
     * 根据组织名称进行组织人员表格异步加载
     */
    public function gettableAjax(){
        $name = $_SESSION['name'];
        $rows = $this->db->getall("select user,email,adddt,name,(case status when 1 then '正常' when 0 then '冻结' end )status from xinhu_admin WHERE deptallname LIKE '%$name%'");
        if($rows){
            $this->returnjson($rows);
        }else{
            echo '表格数据加载失败';
        }

    }

    /**
     * 组织架构树形查看
     */
    public function seeicon_deptsortAjax(){
        $id = $this->rock->post('id');
        $rows = m("dept")->getone("id=".$id."");
        if($rows){
            $this->returnjson($rows);
        }else{
            echo '数据加载失败';
        }

    }

    /**
     * 组织架构树形查看保存上传
     */
    public function seeicon_deptsort_uploadAjax(){
        $id = $this->rock->post('id');
        $num = $this->rock->post('num');
        $name = $this->rock->post('name');
//        $controller = $this->rock->post('controller');
//        $mobile = $this->rock->post('mobile');
        $arr["num"] = $num;
        $arr["name"] = $name;
//        $arr["controller"] = $controller;
//        $arr["mobile"] = $mobile;
        $row = m("dept")->update($arr, "id=".$id."");
        if($row){
            $rows = $this->db->getall("select id,pid,name,mobile,controller,num from xinhu_dept");
            foreach($rows as $k => $v){
                if($rows[$k]['pid'] == 0){
                    $rows[$k]['isParent'] = "true";
                    foreach($rows as $n => $m){
                        if($rows[$k]['id'] == $rows[$n]['pid']) {
                            $rows[$n]['isParent'] = "true";
                        }
                    }
                }
            }
            $this->returnjson($rows);
        }else{
            echo '数据上传失败';
        }
    }

    /**
     * 组织架构树形编辑
     */
    public function editicon_deptsortAjax(){
        $id = $this->rock->post('id');
        $name = $this->rock->post('name');
        $row = m('dept')->update(array(
            'name' => $name
        ),"id=".$id."");
        if($row){
            $rows = $this->db->getall("select id,pid,name,num,mobile,controller from xinhu_dept");
            foreach($rows as $k => $v){
                if($rows[$k]['pid'] == 0){
                    $rows[$k]['isParent'] = "true";
                    foreach($rows as $n => $m){
                        if($rows[$k]['id'] == $rows[$n]['pid']) {
                            $rows[$n]['isParent'] = "true";
                        }
                    }
                }
            }
            $this->returnjson($rows);
        }else{
            echo '数据更新失败';
        }
    }


}
