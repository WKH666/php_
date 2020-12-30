<?php 
class query_indexClassAction extends Action{

    public function initAction(){}

    public function getadminidAjax()
    {
        $userid = $this->post('userid');
        $_SESSION['login_adminid'] = $userid;
        $arr['success'] = true;
        $arr['userid'] = $userid;
        $this->returnjson($arr);
    }

    public function getloginlogAjax()
    {
        $arr['success'] = true;
        $arr['userid'] = $_SESSION['login_adminid'];
        $rows = m('admin')->getone('id='.$_SESSION['login_adminid'].'','user,email,mobile,name,deptname,ranking');
        $dbs  = m("perfect_data");//人员表
        foreach($rows as $v){
            $rows["rdir"] = $dbs->getall("adminid=".$_SESSION['login_adminid']."","head_subject,res_dir");
            if(is_null($rows['user'])){
                $rows['user'] = '';
            }else if(is_null($rows['email'])){
                $rows['email'] = '';
            }else if(is_null($rows['mobile'])){
                $rows['mobile'] = '';
            }else if(is_null($rows['name'])){
                $rows['name'] = '';
            }else if(is_null($rows['deptname'])){
                $rows['deptname'] = '';
            }else if(is_null($rows['ranking'])){
                $rows['ranking'] = '';
            }
        }
        $arr['rows'] = $rows;
        $this->returnjson($arr);
    }

    public function updateloginlogAjax()
    {
        $login_userid = $_SESSION['login_adminid'];
        $user = $this->post('user');
        $email = $this->post('email');
        $mobile = $this->post('mobile');
        $name = $this->post('name');
        $deptname = $this->post('deptname');
        $ranking = $this->post('ranking');
        $res_dir = $this->post('res_dir');
        $arr['user'] = $user;
        $arr['email'] = $email;
        $arr['mobile'] = $mobile;
        $arr['name'] = $name;
        $arr['deptname'] = $deptname;
        $arr['ranking'] = $ranking;
        $rows = m('admin')->update($arr,'id='.$login_userid.'');
        $parr['res_dir'] = $res_dir;
        $pdata = m('perfect_data')->update($parr,'adminid='.$login_userid.'');
        if($rows){
            if($pdata){
                echo json_encode(array(
                    'code' => '200',
                    'msg' => '资料更新成功'
                ));
            }else{
                echo json_encode(array(
                    'code' => '201',
                    'msg' => '资料更新失败'
                ));
            }
        }else{
            echo json_encode(array(
                'code' => '201',
                'msg' => '资料更新失败'
            ));
        }
    }

}