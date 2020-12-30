<?php

class groupClassAction extends Action
{
    public function groupusershow($table)
    {
        $s = 'and 1=2';
        $gid = $this->post('gid', '0');
        if ($gid > 0) {
            $s = " and ( id in( select `sid` from `[Q]sjoin` where `type`='gu' and `mid`='$gid') or id in( select `mid` from `[Q]sjoin` where `type`='ug' and `sid`='$gid') )";
        }
        return array(
            'where' => $s,
            'fields' => 'id,user,name,deptname'
        );
    }

    public function groupafter($table, $rows)
    {

        foreach ($rows as $k => $rs) {
            $gid = $rs['id'];
            $s = "( id in( select `sid` from `[Q]sjoin` where `type`='gu' and `mid`='$gid') or id in( select `mid` from `[Q]sjoin` where `type`='ug' and `sid`='$gid') )";
            $rows[$k]['utotal'] = $this->db->rows('[Q]admin', $s);
        }

        //更新用户的角色组信息
        $rm = m('admin')->getall(' 1=1 ', ' id,groupname ');
        $group_ids = array();
        foreach ($rows as $n) {
            array_push($group_ids,$n['id']);
        }
        foreach ($rm as $m) {
            $group_arr = explode(',',$m['groupname']);
            $newGroupIds = '';
            foreach ($group_arr as $k){
                if (in_array($k,$group_ids)){
                    $newGroupIds.=''.$k.',';
                }
            }
            m('admin')->update(array('groupname'=>$newGroupIds),"id = ".$m['id']);
        }
        return array('rows' => $rows);
    }


    public function saveuserAjax()
    {
        $gid = $this->post('gid', '0'); //角色组id
        $sid = $this->post('sid', '0');//用户id
        $dbs = m('sjoin');
        $dbs->delete("`mid`='$gid' and `type`='gu' and `sid` in($sid)");
        $this->db->insert('[Q]sjoin', '`type`,`mid`,`sid`', "select 'gu','$gid',`id` from `[Q]admin` where `id` in($sid)", true);
        //2020-12-17添加(将角色组id更新到admin表中)
        m('admin')->updateinfo('and a.`id` in(' . $sid . ')');
        echo 'success';
    }

    public function deluserAjax()
    {
        $gid = $this->post('gid', '0');
        $sid = $this->post('sid', '0');
        $dbs = m('sjoin');
        $dbs->delete("`mid`='$gid' and `type`='gu' and `sid`='$sid'");
        $dbs->delete("`sid`='$gid' and `type`='ug' and `mid`='$sid'");
        //2020-12-17添加(将角色组id更新到admin表中)
        m('admin')->updateinfo('and a.`id` in(' . $sid . ')');
        echo 'success';
    }
}
