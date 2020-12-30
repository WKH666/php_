<?php

class query_information_baseClassAction extends Action
{
    public function initAction(){}

    /**
     * @return array
     * 成果信息查询
     */
    public function inforresultsbefore(){
        $update_time = $this->post('update_time');
        $data_type = $this->post('data_type');
        $search_content = $this->post('search_content');
        $where = '';
        $log = m('query_login') -> getsavesession();
        if($log['adminranking'] == '专家' || $log['adminranking'] == '申报者'){
            $where .= ' and xinhu_achievement_query.uid = '.$log['adminid'].'';
        }else if($log['adminranking'] == '政府'){
            $where .= '';
        }
        //查询
        if ($update_time) {
            $where .= " and xinhu_achievement_query.update_time like '%$update_time%'";
        }
        if($data_type && $search_content){
            if($data_type = '作者'){
                $where .= " and xinhu_achievement_query.author like '%$search_content%'";
            }else if($data_type = '所在单位'){
                $where .= " and xinhu_achievement_query.location_unit like '%$search_content%'";
            }else if($data_type = '名称'){
                $where .= " and xinhu_achievement_query.name like '%$search_content%'";
            }else if($data_type = '摘要'){
                $where .= " and xinhu_achievement_query.abstract like '%$search_content%'";
            }
        }
        return array(
            'table' => "xinhu_achievement_query",
            'where' => " $where",
            'fields'=> 'xinhu_achievement_query.*',
            'order' => 'xinhu_achievement_query.update_time desc'
        );
    }

    public function inforresultsafter($table,$rows){
        foreach($rows as $k => $value){
            $rows[$k]['name'] = '<a>'.$rows[$k]['name'] .'</a>';
        }
        return array(
            'rows' => $rows
        );
    }

    /**
     * @return array
     * 纵横项目信息查询
     */
    public function inforcrossbefore(){
        $all_year = $this->post('all_year');
        $data_type = $this->post('data_type');
        $search_content = $this->post('search_content');
        $where = '';
        $log = m('query_login') -> getsavesession();
        if($log['adminranking'] == '专家' || $log['adminranking'] == '申报者'){
            $where .= ' and xinhu_achievement_query.uid = '.$log['adminid'].'';
        }else if($log['adminranking'] == '政府'){
            $where .= '';
        }
        //查询
        if ($all_year) {
            $where .= " and xinhu_item_query.all_year like '%$all_year%'";
        }
        if($data_type && $search_content){
            if($data_type = '项目负责人'){
                $where .= " and xinhu_item_query.project_controller like '%$search_content%'";
            }else if($data_type = '所在单位'){
                $where .= " and xinhu_item_query.location_unit like '%$search_content%'";
            }else if($data_type = '项目名称'){
                $where .= " and xinhu_item_query.project_name like '%$search_content%'";
            }
        }
        return array(
            'table' => "xinhu_item_query",
            'where' => " $where",
            'fields'=> 'xinhu_item_query.*',
            'order' => 'xinhu_item_query.actual_time desc'
        );
    }

    public function inforcrossafter($table,$rows){
        return array(
            'rows' => $rows
        );
    }

    /**
     * @return array
     * 论文发表信息查询
     */
    public function inforreportbefore(){
        $year = $this->post('year');
        $data_type = $this->post('data_type');
        $search_content = $this->post('search_content');
        $where = '';
        $log = m('query_login') -> getsavesession();
        if($log['adminranking'] == '专家' || $log['adminranking'] == '申报者'){
            $where .= ' and xinhu_achievement_query.uid = '.$log['adminid'].'';
        }else if($log['adminranking'] == '政府'){
            $where .= '';
        }
        //查询
        if ($year) {
            $where .= " and xinhu_thesis_query.year like '%$year%'";
        }
        if($data_type && $search_content){
            if($data_type = '作者'){
                $where .= " and xinhu_thesis_query.author like '%$search_content%'";;
            }else if($data_type = '所在单位'){
                $where .= " and xinhu_thesis_query.location_unit like '%$search_content%'";
            }else if($data_type = '题名'){
                $where .= " and xinhu_thesis_query.title like '%$search_content%'";
            }else if($data_type = '刊名'){
                $where .= " and xinhu_thesis_query.serial_title like '%$search_content%'";
            }
        }
        return array(
            'table' => "xinhu_thesis_query",
            'where' => " $where",
            'fields'=> 'xinhu_thesis_query.*',
            'order' => 'xinhu_thesis_query.year desc'
        );
    }

    public function inforrepostafter($table,$rows){
        return array(
            'rows' => $rows
        );
    }

    /**
     * @return array
     * 获奖信息查询
     */
    public function inforprizebefore(){
        $award_time = $this->post('award_time');
        $data_type = $this->post('data_type');
        $search_content = $this->post('search_content');
        $where = '';
        $log = m('query_login') -> getsavesession();
        if($log['adminranking'] == '专家' || $log['adminranking'] == '申报者'){
            $where .= ' and xinhu_achievement_query.uid = '.$log['adminid'].'';
        }else if($log['adminranking'] == '政府'){
            $where .= '';
        }
        //查询
        if ($award_time) {
            $where .= " and xinhu_award_query.award_time like '%$award_time%'";
        }
        if($data_type && $search_content){
            if($data_type = '获奖者'){
                $where .= " and xinhu_award_query.winner like '%$search_content%'";
            }else if($data_type = '获奖单位'){
                $where .= " and xinhu_award_query.winning_unit like '%$search_content%'";
            }else if($data_type = '奖项内容'){
                $where .= " and xinhu_award_query.prize_content like '%$search_content%'";
            }else if($data_type = '颁发机构'){
                $where .= " and xinhu_award_query.issuing_authority like '%$search_content%'";
            }
        }
        return array(
            'table' => "xinhu_award_query",
            'where' => " $where",
            'fields'=> 'xinhu_award_query.*',
            'order' => 'xinhu_award_query.award_time desc'
        );
    }

    public function inforprizeafter($table,$rows){
        return array(
            'rows' => $rows
        );
    }

}
