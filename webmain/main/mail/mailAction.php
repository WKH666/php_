<?php
/**
 * Created by PhpStorm.
 * User: Mi
 * Date: 2020/10/31
 * Time: 19:28
 */
class mailClassAction extends Action
{
    public function getRankAjax(){
        $id=$this->adminid;
        $ranking=m('admin')->getone("id=$id","ranking");
        if ($ranking['ranking']=='社科管理员'||$ranking['ranking']=='系统顶级管理员') {
             $data="管理员";
        }else{
            $data="其他角色";
        }
        $this->returnjson($data);
    }
    public function maillistAjax(){
        $id=$this->adminid;
        $ranking=m('admin')->getone("id=$id","ranking");
        if ($ranking['ranking']=='社科管理员'||$ranking['ranking']=='系统顶级管理员') {
            $table = '[Q]mail';
            $fields = 'id,send_title,send_type,send_status,send_time';
            $where = "1=1";
            $order = 'id DESC';
            $this->getlist($table, $fields, $where, $order);
        }else{
            $table = '[Q]mail';
            $fields = 'id,send_title,send_type,send_time';
            $where = "send_status=1";
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
     * 专站内信列表操作获取  判断登录者身份
     */
    public function maillistafter($table,$rows){
        $id=$this->adminid;
        $sf=m('admin')->getone("id='$id'",'ranking');
        if ($sf['ranking']=='社科管理员'||$sf['ranking']=='系统顶级管理员') {
            foreach ($rows as $k => $rs) {
                $rows[$k]['caoz'] = '';
                if ($rs['send_status'] == 0) {
                    $rows[$k]['send_status'] = '草稿';
                    $rows[$k]['caoz'] .= '<a onclick="mailresultscheck(' . $rs['id'] . ')">查看</a>';
                    $rows[$k]['caoz'] .= '<span style="padding:5px;">|</span>';
                    $rows[$k]['caoz'] .= '<a onclick="mailresultedit(' . $rs['id'] . ')">编辑</a>';
                    $rows[$k]['caoz'] .= '<span style="padding:5px;">|</span>';
                    $rows[$k]['caoz'] .= '<a onclick="mailresultdel(' . $rs['id'] . ')">删除</a>';
                } else {
                    $rows[$k]['send_status'] = '已发布';
                    $rows[$k]['caoz'] .= '<a onclick="mailresultscheck(' . $rs['id'] . ')">查看</a>';
                    $rows[$k]['caoz'] .= '<span style="padding:5px;">|</span>';
                    $rows[$k]['caoz'] .= '<a onclick="mailresultdel(' . $rs['id'] . ')">删除</a>';
                }
            }
            }else{
            foreach ($rows as $k => $rs) {
                $rows[$k]['caoz'] = '';
                $rows[$k]['send_status'] = '已发布';
                $rows[$k]['caoz'] .= '<a onclick="mailresultscheck2(' . $rs['id'] . ')">查看</a>';
            }
            }
        return $rows;
    }
    /**
     * 站内信操作搜索
     */
    public function maillistbefore(){
        $send_title =trim($this->post('send_title'));
        $send_time =trim($this->post('send_time'));
        $where=' ';
        //查询
        if ($send_title) {
            $where .= "and xinhu_mail.send_title like '%$send_title%'";
        }
        if ($send_time) {
            $where .= "and xinhu_mail.send_time like '%$send_time%'";
        }
        return $where;
    }
    /**
     * 站内信删除
     */
    public function delresultAjax(){
        $id = $_POST['id'];
        $bool = m("mail")->delete("id=".$id."");
        if($bool){
            echo json_encode(array(
                'code' => '200',
                'msg' => '删除了1条记录'
            )) ;
        }else{
            echo json_encode(array(
                'code' => '201',
                'msg' => '删除记录失败'
            ));
        }
    }
    /**
     * 站内信查看
     **/
    public function getchecksAjax(){
        $results_id = $_POST['results_id'];
        $rows = m("mail")->getone("id=".$results_id."");
        $sss=explode(',',$rows['receive_id']);
        foreach ($sss as $v){
            $username=m("admin")->getone("id=".$v."",'user');
            $c[]=$username['user'];
        }
        $d=implode(',', $c);
        //$username=m("admin")->getone("id=".$rows['receive_id']."");
        $rows['receive_id']=$d;
        $this->returnjson($rows);
    }
    /**
     * 档案管理 上传按钮保存操作
     */
    public function baocunAjax(){
        $id = $_POST['id'];
        $nd = $_POST['nd'];
        $arr=array(
            'nd_year'=>$nd,
            'upload_status'=>1
        );
        $bools = m("xfile")->update($arr,"id=".$id."");
        if($bools){
            echo json_encode(array(
                'code' => '200',
            )) ;
        }else{
            echo json_encode(array(
                'code' => '201',
            ));
        }
    }
    public function admin_listAjax(){
        $table = '[Q]admin';
        $fields = '*';
        $where = '1=1';
        $order = '';
        $this->getlist($table, $fields, $where, $order);
    }
    /**
     * 编辑操作搜索
     */
    public function admin_before(){
        $admin_name =trim($this->post('admin_name'));
        $deptname =trim($this->post('deptname'));
        $ranking =trim($this->post('ranking'));
        $where=' ';
        //查询
        if ($admin_name) {
            $where .= "and xinhu_admin.name like '%$admin_name%'";
        }
        if ($deptname) {
            $where .= "and xinhu_admin.deptname like '%$deptname%'";
        }
        if ($ranking) {
            $where .= "and xinhu_admin.ranking like '%$ranking%'";
        }
        return $where;
    }

    /**
     * 站内信 选择用户列表
     */
    public function vis_resultsAjax(){
        $group_id = $_POST['status_arr1'];
        foreach($group_id as $k => $v){
            if ($v=="on") {
                break;
            }else{
                $rows = m("admin")->getone("id=" .$v. "", 'user');
                $user[] = $rows['user'];
            }
        }
        if($rows){
            echo json_encode(array(
                'ids'=>$group_id,
                'user' => $user,
                'code' => 200,
            )) ;
        }else{
            echo json_encode(array(
                'code' => '201',
                'msg' => '失败'
            ));
        }
    }
    /**
     * 档案管理 上传按钮保存操作
     */
    public function got_dataAjax(){
        $id = $_POST['id'];
        $send_type = $_POST['send_type'];
        $receive_id = trim($_POST['receive_id']);
        $rece_id=explode(",",$receive_id);
        //$s=m('admin')->getone('user='.$rece_id[0],'id');
        foreach ($rece_id as $v){
            $s=m('admin')->getone("user='$v'",'id');//列表
            $c[]=$s['id'];
        }
        $d=implode(',', $c);//explode();
        $remark = $_POST['remark'];
        $titles = $_POST['titles'];
        $is_send = $_POST['is_send'];
        $send_status = $_POST['send_status'];
        $arr=array(
            'receive_id'=>$d,
            'send_title'=> $titles,
            'send_type'=> $send_type,
            'send_time'=>date('Y-m-d'),
            'send_remark'=> $remark,
            'is_send' => $is_send,
            'send_status'=> $send_status
        );
        $bools = m("mail")->update($arr,"id=".$id."");
        $this->returnjson($bools);
    }
    /**
     * 档案管理 上传按钮保存操作
     */
    public function get_dataAjax(){
        $id = $_POST['id'];
        $res_id = $_POST['results_id'];
        $send_type = $_POST['send_type'];
        $receive_id = trim($_POST['receive_id']);
        $rece_id=explode(",",$receive_id);
        $res=m('xfile')->getone("id='$res_id'",'fileext,filepath,filename');
        $mail_arr=array(
            'fileext'=>$res['fileext'],
            'filepath'=>$res['filepath'],
            'filename'=>$res['filename'],
        );
        m('mail')->update($mail_arr,"id='$id'");
        foreach ($rece_id as $v){
            $s=m('admin')->getone("user='$v'",'id');//列表
            $c[]=$s['id'];
        }
        $d=implode(',', $c);//explode();
        $remark = $_POST['remark'];
        $titles = $_POST['titles'];
        $is_send = $_POST['is_send'];
        $send_status = $_POST['send_status'];
        $arr=array(
            'receive_id'=>$d,
            'send_title'=> $titles,
            'send_type'=> $send_type,
            'send_time'=>date('Y-m-d'),
            'send_remark'=> $remark,
            'is_send' => $is_send,
            'send_status'=> $send_status
        );
        $bools = m("mail")->update($arr,"id=".$id."");
        $this->returnjson($bools);
    }
    /**
     * 档案管理 上传按钮草稿操作
     */
    public function got_saveAjax(){
        $id = $_POST['id'];
        $send_type = $_POST['send_type'];
        $receive_id = trim($_POST['receive_id']);
        $rece_id=explode(",",$receive_id);
        //$s=m('admin')->getone('user='.$rece_id[0],'id');
        foreach ($rece_id as $v){
            $s=m('admin')->getone("user='$v'",'id');//列表
            $c[]=$s['id'];
        }
        $d=implode(',', $c);//explode();
        $remark = $_POST['remark'];
        $titles = $_POST['titles'];
        $is_send = $_POST['is_send'];
        $send_status = $_POST['send_status'];
        $arr=array(
            'receive_id'=>$d,
            'send_title'=> $titles,
            'send_type'=> $send_type,
            'send_time'=>date('Y-m-d'),
            'send_remark'=> $remark,
            'is_send' => $is_send,
            'send_status'=> $send_status
        );
        $bools = m("mail")->update($arr,"id=".$id."");
        $this->returnjson($bools);
    }
    /**
     * 档案管理 上传按钮草稿操作
     */
    public function get_saveAjax(){
        $id = $_POST['id'];
        $res_id = $_POST['results_id'];
        $send_type = $_POST['send_type'];
        $receive_id = trim($_POST['receive_id']);
        $rece_id=explode(",",$receive_id);
        $res=m('xfile')->getone("id='$res_id'",'fileext,filepath,filename');
        $mail_arr=array(
            'fileext'=>$res['fileext'],
            'filepath'=>$res['filepath'],
            'filename'=>$res['filename'],
        );
        m('mail')->update($mail_arr,"id='$id'");
        foreach ($rece_id as $v){
            $s=m('admin')->getone("user='$v'",'id');//列表
            $c[]=$s['id'];
        }
        $d=implode(',', $c);//explode();
        $remark = $_POST['remark'];
        $titles = $_POST['titles'];
        $is_send = $_POST['is_send'];
        $send_status = $_POST['send_status'];
        $arr=array(
            'receive_id'=>$d,
            'send_title'=> $titles,
            'send_type'=> $send_type,
            'send_time'=>date('Y-m-d'),
            'send_remark'=> $remark,
            'is_send' => $is_send,
            'send_status'=> $send_status
        );
        $bools = m("mail")->update($arr,"id=".$id."");
        $this->returnjson($bools);
    }

    /**
     * 站内信 选择角色列表
     */
    public function qd_resultsAjax(){
        $group_id =$_POST['status_arr2'];
        $e=m('admin')->getall("1=1");
        foreach ($group_id as $k => $v){
            foreach($e as $key=>$value){
                if($value['ranking']==$group_id[$k]){
                    $s=m('admin')->getone("id='".$value['id']."'",'user');
                    $user[]=$s['user'];
                }
            }
        }
        $d=implode(',', $user);
        if($s){
            echo json_encode(array(
                'ids'=>$group_id,
                'user' => $d,
                'code' => 200,
            )) ;
        }else{
            echo json_encode(array(
                'code' => '201',
                'msg' => '失败'
            ));
        }
    }
    /**
     * 档案管理 发布按钮保存操作
     */
    public function rec_dataAjax(){
        $res_id = $_POST['results_id'];
        $send_type = $_POST['send_type'];
        $receive_id = trim($_POST['receive_id']);
        $rece_id=explode(",",$receive_id);//将多个接受者id用逗号分隔 转换成数组
        $res=m('xfile')->getone("id='$res_id'",'fileext,filepath,filename');
//        $mail_arr=array(
//            'fileext'=>$res['fileext'],
//            'filepath'=>$res['filepath'],
//            'filename'=>$res['filename'],
//        );
        foreach ($rece_id as $v){
            $s=m('admin')->getone("user='$v'",'id');//列表
            $c[]=$s['id'];
        }
        $d=implode(',', $c);//explode();
        $remark = $_POST['remark'];
        $titles = $_POST['titles'];
        $is_send = $_POST['is_send'];
        $send_status = $_POST['send_status'];
        $arr=array(
            'receive_id'=>$d,
            'send_title'=> $titles,
            'send_type'=> $send_type,
            'send_time'=>date('Y-m-d'),
            'send_remark'=> $remark,
            'is_send' => $is_send,
            'fileext'=>$res['fileext'],
            'filepath'=>$res['filepath'],
            'filename'=>$res['filename'],
            'send_status'=> $send_status
        );
        $bools = m("mail")->insert($arr);
        $this->returnjson($bools);
    }
    /**
     * 档案管理 发布按钮草稿操作
     */
    public function dra_dataAjax(){
        $res_id = $_POST['results_id'];
        $send_type = $_POST['send_type'];
        $receive_id = trim($_POST['receive_id']);
        $rece_id=explode(",",$receive_id);//将多个接受者id用逗号分隔 转换成数组
        $res=m('xfile')->getone("id='$res_id'",'fileext,filepath,filename');
        foreach ($rece_id as $v){
            $s=m('admin')->getone("user='$v'",'id');//列表
            $c[]=$s['id'];
        }
        $d=implode(',', $c);//explode();
        $remark = $_POST['remark'];
        $titles = $_POST['titles'];
        $is_send = $_POST['is_send'];
        $send_status = $_POST['send_status'];
        $arr=array(
            'receive_id'=>$d,
            'send_title'=> $titles,
            'send_type'=> $send_type,
            'send_time'=>date('Y-m-d'),
            'send_remark'=> $remark,
            'is_send' => $is_send,
            'fileext'=>$res['fileext'],
            'filepath'=>$res['filepath'],
            'filename'=>$res['filename'],
            'send_status'=> $send_status
        );
        $bools = m("mail")->insert($arr);
        $this->returnjson($bools);
    }

}