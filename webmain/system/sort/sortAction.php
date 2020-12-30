<?php

//继承ActionNot这个不需要登录就可以访问
//继承Action这个需要登录才可以访问
//Ajax只能用于ajax访问
//Action允许用浏览器直接打开，和Ajax接入
class sortClassAction extends Action
{
    public function initAction(){}

    /**
     * 学科分类页面
     */
    public function subjectsortAction(){}

    /**
     * 学科分类树形异步加载
     */
    public function request_subjectAjax(){
        $rows = $this->db->getall("select id,pid,name,add_time,upload_time,del_status from xinhu_subject_sort WHERE del_status = '0'");
        foreach($rows as $k => $v){
            foreach($rows as $n => $m){
                if($rows[$k]['id'] == $rows[$n]['pid']){
                    $rows[$k]['isParent'] = "true";
                }else if($rows[$k]['pid'] == 0){
                    $rows[$k]['isParent'] = "true";
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
     * 学科分类搜索栏
     */
    public function search_subjectsortAjax(){
        $name = $this->rock->post('name');
        $rows = $this->db->getall("select * from xinhu_subject_sort WHERE name LIKE '%$name%' and  del_status = '0'");
        foreach($rows as $k => $v){
            foreach($rows as $n => $m){
                if($rows[$k]['id'] == $rows[$n]['pid']){
                    $rows[$k]['isParent'] = "true";
                    $rows[$k]['open'] = "true";
                }else if($rows[$k]['pid'] == 0){
                    $rows[$k]['isParent'] = "true";
                    $rows[$k]['open'] = "true";
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
     * 学科分类树形添加
     */
    public function addicon_subjectsortAjax(){
        $pid = $this->rock->post('pid');
        $name = $this->rock->post('name');
        $format="%Y-%m-%d %H:%M:%S";
        $add_time = strftime($format);
        $upload_time = strftime($format);
        $row = m('subject_sort')->insert(array(
            'pid' => $pid,
            'name' => $name,
            'add_time' => $add_time,
            'upload_time' =>$upload_time
        ));
        if($row){
            $rows = $this->db->getall("select id,pid,name,add_time,upload_time,del_status from xinhu_subject_sort WHERE del_status = '0'");
            foreach($rows as $k => $v){
                foreach($rows as $n => $m){
                    if($rows[$k]['id'] == $rows[$n]['pid']){
                        $rows[$k]['isParent'] = "true";
                    }else if($rows[$k]['pid'] == 0){
                        $rows[$k]['isParent'] = "true";
                    }
                }
            }
            $this->returnjson($rows);
        }else{
            echo '数据添加失败';
        }

    }

    /**
     * 学科分类添加
     */
    public function addbtn_subjectsortAjax(){
        $upload_subjectsort = $this->rock->post('upload_subjectsort');
        $format="%Y-%m-%d %H:%M:%S";
        $add_time = strftime($format);
        $upload_time = strftime($format);
        $row = m('subject_sort')->insert(array(
            'name' => $upload_subjectsort,
            'add_time' => $add_time,
            'upload_time' => $upload_time
        ));
        if($row){
            $rows = $this->db->getall("select id,pid,name,add_time,upload_time,del_status from xinhu_subject_sort WHERE del_status = '0'");
            foreach($rows as $k => $v){
                foreach($rows as $n => $m){
                    if($rows[$k]['id'] == $rows[$n]['pid']){
                        $rows[$k]['isParent'] = "true";
                    }else if($rows[$k]['pid'] == 0){
                        $rows[$k]['isParent'] = "true";
                    }
                }
            }
            $this->returnjson($rows);
        }else{
            echo '数据添加失败';
        }


    }

    /**
     * 学科分类树形删除
     */
    public function delicon_subjectsortAjax(){
        $id = $this->rock->post('id');
        $format="%Y-%m-%d %H:%M:%S";
        $upload_time = strftime($format);
        $row = m('subject_sort')->update(array(
            'del_status' => '1',
            'upload_time' => $upload_time
        ),"id=".$id."");
        if($row){
            $rows = $this->db->getall("select id,pid,name,add_time,upload_time,del_status from xinhu_subject_sort WHERE del_status = '0'");
            foreach($rows as $k => $v){
                foreach($rows as $n => $m){
                    if($rows[$k]['id'] == $rows[$n]['pid']){
                        $rows[$k]['isParent'] = "true";
                    }else if($rows[$k]['pid'] == 0){
                        $rows[$k]['isParent'] = "true";
                    }
                }
            }
            $this->returnjson($rows);
        }else{
            echo '数据更新失败';
        }


    }

    /**
     * 学科分类树形编辑
     */
    public function editicon_subjectsortAjax(){
        $id = $this->rock->post('id');
        $name = $this->rock->post('name');
        $format="%Y-%m-%d %H:%M:%S";
        $upload_time = strftime($format);
        $row = m('subject_sort')->update(array(
            'name' => $name,
            'upload_time' => $upload_time
        ),"id=".$id."");
        if($row){
            $rows = $this->db->getall("select id,pid,name,add_time,upload_time,del_status from xinhu_subject_sort WHERE del_status = '0'");
            foreach($rows as $k => $v){
                foreach($rows as $n => $m){
                    if($rows[$k]['id'] == $rows[$n]['pid']){
                        $rows[$k]['isParent'] = "true";
                    }else if($rows[$k]['pid'] == 0){
                        $rows[$k]['isParent'] = "true";
                    }
                }
            }
            $this->returnjson($rows);
        }else{
            echo '数据更新失败';
        }


    }

}