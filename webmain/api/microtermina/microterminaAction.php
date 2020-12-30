<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/10/14 0014
 * Time: 9:57
 */

include_once('webmain/api/microtermina/weixinAction.php');
class microterminaClassAction extends weixinAction
{
    /**
     * 首页（高校/社科/申报者）
     */
    public function homepageAjax()
    {
        $user_id= $this->post('user_id');
        $page = $this->post('page');
        $size = $this->post('size');
        $where ="uid=$user_id and status=0";
        $field ="modename,createdt,nstatustext";
        $result = m('flowbill')->getall("$where", "$field","id","$page,$size");
        if ($result){
            $this->showreturn($result);
        }else{
            $this->showreturn('', $result, 201);
        }
    }

    /**
     * 基本信息（申报初审/申报结审/查看项目）
     */
    public function preliminary_trialAjax()
    {
        $project_id= $this->post('project_id');
        $table = "[Q]flow_bill as a left join [Q]admin as b on a.uid=b.id";
        $fields = 'sericnum,modename,b.deptallname';
        $where = "a.id=$project_id";
        $result = $this->limitRows("$table","$fields","$where");
        if ($result){
            $this->showreturn($result);
        }else{
            $this->showreturn('', $result, 201);
        }
    }

    /**
     * 申请进度
     */
    public function check_projectAjax()
    {
        $project_id = $this->post('project_id');
        $table = "[Q]flow_course as a left join [Q]flow_bill as b on a.setid=b.modeid";
        $fields = 'name,b.nowcourseid,a.id';
        $where = "b.id=$project_id";
        $result = $this->limitRows("$table", "$fields", "$where", "sort");
        $name = array_column($result['rows'], "name");
        $course_id = array_column($result['rows'], "id");
        $nowcouserid = $result['rows'][0]['nowcourseid'];
        $new = array();
        foreach ($name as $k=>$v){
                $arr = array('progress' => $v,'status'=>'通过');
            if (intval($course_id[$k]) == intval($nowcouserid)) {
                $arr = array('progress' => $v,'status'=>'进行中');
                $new[] = $arr;
                break;
            }
            $new[] = $arr;
        }
        if ($new){
            $this->showreturn($new);
        }else{
            $this->showreturn('', $new, 201);
        }
    }

//    /**
//     * 通知
//     */
//    public function advise_listAjax()
//    {
//        $user_id = $this->post('user_id');
//        $keyword = $this->post('keyword');
//        $page = $this->post('page');
//        $size = $this->post('size');
//    }
//
    /**
     * 通知详情(站内信)
     */
    public function advise_detailAjax()
    {
        $mail_id= $this->post('mail_id');
        $where ="id=$mail_id";
        $fields ="send_title,send_remark";
        $result = m('mail')->getone("$where","$fields");
        if ($result){
            $this->showreturn($result);
        }else{
            $this->showreturn('', $result, 201);
        }
    }

    /**
     * 个人信息
     */
    public function mineAjax()
    {
        $user_id= $this->post('user_id');
        $where ="id=$user_id";
        $fields ="name,tel,email,deptname";
        $result = m('admin')->getone("$where","$fields");
        if ($result){
            $this->showreturn($result);
        }else{
            $this->showreturn('', $result, 201);
        }
    }

    /**
     * 修改密码
     */
    public function update_passAjax()
    {
        $user_id= $this->post('user_id');
        $new_pass = md5($this->post('new_pass'));
        $arr["pass"] = $new_pass;
        $where = "id=$user_id";
        $result = m('admin')->update($arr,"$where");
        if ($result){
            $this->showreturn($result);
        }else{
            $this->showreturn('', $result, 201);
        }
    }
}