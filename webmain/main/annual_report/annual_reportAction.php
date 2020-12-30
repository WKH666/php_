<?php
/**
 * Created by PhpStorm.
 * User: Mi
 * Date: 2020/10/22
 * Time: 21:08
 */

class annual_reportClassAction extends Action
{
    public function initAction()
    {

    }

    /**
     * 年度报告列表
     */
    public function reportlistAjax(){
        $id=$this->adminid;
        $ranking=m('admin')->getone("id=$id","ranking");
        if ($ranking['ranking']=="申报者"){
            $table = '`[Q]file`';
            $fields = 'id,filename,upload_status,optname,adddt,fileext,filepath';
            $where = "valid=1 and optid=$id and upload_filetype='年度报告'";
            $order = 'id DESC';
            $this->getlist($table, $fields, $where, $order);
        }else {
            $table = '`[Q]file`';
            $fields = 'id,filename,upload_status,optname,adddt,fileext,filepath';
            $where = "valid=1 and upload_filetype='年度报告'";
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
     * 年度报告操作获取
     */
    public function reportlistafter($table,$rows){
        $id=$this->adminid;
        $ranking=m('admin')->getone("id=$id",'ranking');
        if ($ranking['ranking']=="申报者") {
            foreach ($rows as $k => $rs) {
                $rows[$k]['caoz'] = '';
                if ($rs['upload_status'] == 0) {
                    $rows[$k]['upload_status'] = '草稿';
                    $rows[$k]['caoz'] .= '<a onclick="annualreportedit(' . $rs['id'] . ')">编辑</a>';
                    $rows[$k]['caoz'] .= '<span style="padding:5px;">|</span>';
                    $rows[$k]['caoz'] .= '<a onclick="annualreportdel(' . $rs['id'] . ')">删除</a>';
                } else {
                    $rows[$k]['upload_status'] = '已提交';
                    $rows[$k]['caoz'] .= '<a  href="javascript:;" onclick="annualreportdownload(' . $rs['id'] . ',\'' . $rs['fileext'] . '\',\'' . $rs['filepath'] . '\')">下载</a>';
                }
            }
        }else {
            foreach ($rows as $k => $rs) {
                $rows[$k]['caoz'] = '';
                $rows[$k]['caoz'] .= '<a  href="javascript:;" onclick="annualreportdownload(' . $rs['id'] . ',\'' . $rs['fileext'] . '\',\'' . $rs['filepath'] . '\')">下载</a>';
                $rows[$k]['caoz'] .= '<span style="padding:5px;">|</span>';
                $rows[$k]['caoz'] .= '<a onclick="annualreportdel(' . $rs['id'] . ')">删除</a>';
                if ($rs['upload_status'] == 0) {
                    $rows[$k]['upload_status'] = '草稿';
                } else {
                    $rows[$k]['upload_status'] = '已提交';
                }

            }
        }

        return $rows;
    }
    /**
     * 年度报告搜索功能
     */
    public function reportlistbefore(){
        $filename =trim($this->post('filename'));
        $optname =trim($this->post('optname'));
        $where=' ';
        //查询
        if ($filename) {
            $where .= "and xinhu_file.filename like '%$filename%'";
        }
        if ($optname) {
            $where .= "and xinhu_file.optname like '%$optname%'";
        }
        return $where;
    }
    /**
     * 年度报告删除功能
     */
    public function delresultsAjax(){
        $id = $_POST['annualreport_id'];
        $bool = m("xfile")->delete("id=".$id."");
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
     * 年度报告列表操作上的上传功能
     */
    public function getsaveAjax(){
        $results_id=$_POST['results_id'];
        $del_id=$_POST['del_id'];
        $upload_status=$_POST['upload_status'];
        if ($results_id!=''||$del_id!=''||$upload_status!='') {
           $del=m("xfile")->delete("id=" . $del_id . "");
           $upda=m("xfile")->update("upload_status=" . $upload_status . "", "id=" . $results_id . "");
            $this->returnjson($del,$upda);
        }else{
            return false;
        }
    }
    /**
     * 年度报告搜索框上的上传功能
     */
    public function getnewsaveAjax(){
        $results_id=$_POST['results_id'];
        $upload_status=$_POST['upload_status'];
        if ($results_id!=''||$upload_status!='') {
            $upda=m("xfile")->update("upload_status=" . $upload_status . "", "id=" . $results_id . "");
            $this->returnjson($upda);
        }else{
            return false;
        }
    }
    public function get_editAjax(){
        $results_id=$_POST['results_id'];
        $rows=m('xfile')->getone("id=$results_id","upload_filetype,filename");
        $this->returnjson($rows);
    }
}