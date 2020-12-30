<?php

class information_baseClassAction extends Action
{
    public function initAction(){}


    public function inforresultsbefore(){
        $identifier = $this->post('identifier');
        $form 	= $this->post('form');
        $name = $this->post('name');
        $author = $this->post('author');
        $location_unit = $this->post('location_unit');
        $update_time = $this->post('update_time');
        $keywords = $this->post('keywords');
        $where='';
        //查询
        if ($identifier) {
            $where .= " and xinhu_achievement_query.identifier like '%$identifier%'";
        }
        if ($form) {
            $where .= " and xinhu_achievement_query.form like '%$form%'";
        }
        if ($name){
            $where .= " and xinhu_achievement_query.name like '%$name%'";
        }
        if ($author){
            $where .= " and xinhu_achievement_query.author like '%$author%'";
        }
        if ($location_unit){
            $where .= " and xinhu_achievement_query.location_unit like '%$location_unit%'";
        }
        if ($update_time){
            $where .= " and xinhu_achievement_query.update_time like '%$update_time%'";
        }
        if ($keywords){
            $where .= " and xinhu_achievement_query.keywords like '%$keywords%'";
        }
        return array(
            'table' => "xinhu_achievement_query",
            'where' => " $where",
            'fields'=> 'xinhu_achievement_query.*',
            'order' => 'xinhu_achievement_query.update_time desc'
        );

    }
    public function inforresultsafter($table,$rows){
        foreach($rows as $k=>$rs){
            $rows[$k]['caoz']='';
//            $rows[$k]['caoz'].= '<a onclick="inforresultscheck(this,'.$rs['id'].')">查看</a>';
            $rows[$k]['caoz'].= '<a onclick="inforresultscheck('.$rs['id'].')">查看</a>';
            $rows[$k]['caoz'].= '<span style="padding:5px;">|</span>';
            $rows[$k]['caoz'].= '<a onclick="inforresultsdel(this,'.$rs['id'].')">删除</a>';
            if($rows[$k]['status']){
                $rows[$k]['status_text'] = '是';
            }else{
                $rows[$k]['status_text'] = '否';
            }
        }
        return array(
            'rows' => $rows
        );
    }


    public function inforreportbefore(){
        $year = $this->post('year');
        $location_unit 	= $this->post('location_unit');
        $title = $this->post('title');
        $serial_title = $this->post('serial_title');
        $author = $this->post('author');
        $where='';
        //查询
        if ($year) {
            $where .= " and xinhu_thesis_query.year like '%$year%'";
        }
        if ($location_unit) {
            $where .= " and xinhu_thesis_query.location_unit like '%$location_unit%'";
        }
        if ($title){
            $where .= " and xinhu_thesis_query.title like '%$title%'";
        }
        if ($serial_title){
            $where .= " and xinhu_thesis_query.serial_title like '%$serial_title%'";
        }
        if ($author){
            $where .= " and xinhu_thesis_query.author like '%$author%'";
        }
        return array(
            'table' => "xinhu_thesis_query",
            'where' => " $where",
            'fields'=> 'xinhu_thesis_query.*',
            'order' => 'xinhu_thesis_query.year desc'
        );
    }
    public function inforreportafter($table,$rows){
        foreach($rows as $k=>$rs){
            $rows[$k]['caoz']='';
            $rows[$k]['caoz'].= '<a onclick="inforreportdel(this,'.$rs['id'].')">删除</a>';
        }
        return array(
            'rows' => $rows
        );
    }


    public function inforprizebefore(){
        $award_time = $this->post('award_time');
        $winner = $this->post('winner');
        $winning_unit = $this->post('winning_unit');
        $prize = $this->post('prize');
        $prize_content = $this->post('prize_content');
        $where='';
        //查询
        if ($award_time) {
            $where .= " and xinhu_award_query.award_time like '%$award_time%'";
        }
        if ($winner) {
            $where .= " and xinhu_award_query.winner like '%$winner%'";
        }
        if ($winning_unit){
            $where .= " and xinhu_award_query.winning_unit like '%$winning_unit%'";
        }
        if ($prize){
            $where .= " and xinhu_award_query.prize like '%$prize%'";
        }
        if ($prize_content){
            $where .= " and xinhu_award_query.prize_content like '%$prize_content%'";
        }
        return array(
            'table' => "xinhu_award_query",
            'where' => " $where",
            'fields'=> 'xinhu_award_query.*',
            'order' => 'xinhu_award_query.award_time desc'
        );
    }
    public function inforprizeafter($table,$rows){
        foreach($rows as $k=>$rs){
            $rows[$k]['caoz']='';
            $rows[$k]['caoz'].= '<a onclick="inforprizedel(this,'.$rs['id'].')">删除</a>';
        }
        return array(
            'rows' => $rows
        );
    }


    public function inforcrossbefore(){
        $type = $this->post('type');
        $all_year = $this->post('all_year');
        $project_controller = $this->post('project_controller');
        $location_unit = $this->post('location_unit');
        $pile_sorts = $this->post('pile_sorts');
        $project_name = $this->post('project_name');
        $expected_time = $this->post('expected_time');
        $where='';
        //查询
        if ($type) {
            $where .= " and xinhu_item_query.type like '%$type%'";
        }
        if ($all_year) {
            $where .= " and xinhu_item_query.all_year like '%$all_year%'";
        }
        if ($project_controller){
            $where .= " and xinhu_item_query.project_controller like '%$project_controller%'";
        }
        if ($location_unit){
            $where .= " and xinhu_item_query.location_unit like '%$location_unit%'";
        }
        if ($pile_sorts){
            $where .= " and xinhu_item_query.pile_sorts like '%$pile_sorts%'";
        }
        if ($project_name){
            $where .= " and xinhu_item_query.project_name like '%$project_name%'";
        }
        if ($expected_time){
            $where .= " and xinhu_item_query.expected_time like '%$expected_time%'";
        }

        return array(
            'table' => "xinhu_item_query",
            'where' => " $where",
            'fields'=> 'xinhu_item_query.*',
            'order' => 'xinhu_item_query.actual_time desc'
        );
    }
    public function inforcrossafter($table,$rows){
        foreach($rows as $k=>$rs){
            $rows[$k]['caoz'] = '';
            $rows[$k]['caoz'] .= '<a onclick="inforcrossdel(this,'.$rs['id'].')">删除</a>';
        }
        return array(
            'rows' => $rows
        );
    }


    public function getresultsAjax(){
        $log = m('query_login') -> getsavesession();
        $results_id = $_POST['results_id'];
        $rows = m("achievement_query")->getone("id=".$results_id."");
        $lists = $this->db->getall("SELECT achievement_filename,achievement_filepath FROM xinhu_achievement_file WHERE uid = ".$log['adminid']." and achievement_filename LIKE '%$rows[identifier]%'");
        $rows['file'] = $lists;
        $this->returnjson($rows);
    }


    public function delresultsAjax(){
        $id = $_POST['current_index'];
        $bool = m("achievement_query")->delete("id=".$id."");
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

    public function delcrossAjax(){
        $id = $_POST['current_index'];
        $bool = m("item_query")->delete("id=".$id."");
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

    public function delreportAjax(){
        $id = $_POST['current_index'];
        $bool = m("thesis_query")->delete("id=".$id."");
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

    public function delprizeAjax(){
        $id = $_POST['current_index'];
        $bool = m("award_query")->delete("id=".$id."");
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


    public function staresultsAjax(){
        $group_id = $_POST['status_arr'];
        $arr['status'] = 1;
        foreach($group_id as $k => $v){
            $rows= m("achievement_query")->update($arr, "id=".$group_id[$k]."");
        }
        if($rows){
            echo json_encode(array(
                'code' => '200',
                'msg' => '更新'.count($group_id).'条记录'
            )) ;
        }else{
            echo json_encode(array(
                'code' => '201',
                'msg' => '更新失败'
            ));
        }
    }




    /**
     *	上传文件页面
     */
    public function defaultAction()
    {
        $callback	= $this->get('callback');
        $callbacka	= explode('|', $callback);

        $params['callback'] 	= $callbacka[0];
        $params['changeback'] 	= arrvalue($callbacka,1);
        $params['maxup'] 		= $this->get('maxup','0');
        $params['thumbnail'] 	= $this->get('thumbnail');
        $params['maxwidth'] 	= $this->get('maxwidth','0');
        $params['showid'] 		= $this->get('showid');
        $params['upkey'] 		= $this->get('upkey');
        $params['uptype'] 		= $this->get('uptype','*');
        $params['thumbtype'] 	= $this->get('thumbtype','0');
        $params['maxsize'] 		= (int)$this->get('maxsize', c('upfile')->getmaxzhao());

        $urlparams				= '{}';
        $urlcan	 = $this->get('urlparams');//格式:a=b,c=d
        if(!isempt($urlcan)){
            $cans1 = explode(',', $urlcan);
            $urlparams = array();
            foreach($cans1 as $cans2){
                $cans3 = explode(':', $cans2);
                $urlparams[$cans3[0]]=$cans3[1];
            }
            $urlparams = json_encode($urlparams);
        }
        $params['urlparams'] 	= $urlparams;

        $this->title 			= $this->get('title','文件上传');
        $this->assign('params', $params);
        $this->assign('callback', $params['callback']);
    }

    public function upfileAjax()
    {
        if(!$_FILES)exit('sorry!');
        $upimg	= c('upfile');
        $maxsize= (int)$this->get('maxsize', 5);
        $uptype	= $this->get('uptype', '*');
        $thumbnail	= $this->get('thumbnail');
        $upimg->initupfile($uptype, 'upload|'.date('Y-m').'', $maxsize);//zip|rar,upload|2020-06,100
        $upses	= $upimg->up('file');
		$arr 	= $this->uploadback($upses, $thumbnail);
        $this->returnjson($arr);
    }

    public function uploadback($upses, $thumbnail='')
    {
        $log = m('query_login') -> getsavesession();
        if($thumbnail=='')$thumbnail='150x150';
        $data 		= array();
        if(is_array($upses)){
            $arrs	= array(
                'uid' => $log['adminid'],
                'achievement_filename'	=> $upses['oldfilename'],
                'achievement_filepath'	=> str_replace('../','',$upses['allfilename'])
            );
            $this->db->record('[Q]achievement_file',$arrs);
            $id	= $this->db->insert_id();
            $arrs['id'] = $id;
            $data= $arrs;
        }else{
            $data['msg'] = $upses;
        }
        return $data;
    }


}
