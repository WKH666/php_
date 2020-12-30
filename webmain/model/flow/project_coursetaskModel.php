<?php
class flow_project_coursetaskClassModel extends flowModel{

    /*
     * 注释：方法是继承flow.php中的方法进行重写
     * */
    //当初始化模块时调用
    //判断是否与高校关联，有关联则开启高校审核步骤
    protected function flowinit(){
        $uid = $this -> adminid;
        $admin_u_info = m('admin') -> getone('id=' . $uid, 'school_name,ranking');
        if ($admin_u_info['school_name']!=''&& $admin_u_info['ranking'] == '申报者'){
            $arr['status'] = 1;
            $bool= m("flow_course")->update($arr, "id = '97'");
        }else if($admin_u_info['school_name'] ==''&& $admin_u_info['ranking'] != '高校科研人员'){
            $arr['status'] = 0;
            $bool= m("flow_course")->update($arr, "id = '97'");
        }else if ($admin_u_info['ranking'] == '高校科研人员'){
            $arr['status'] = 1;
            $bool= m("flow_course")->update($arr, "id = '97'");
        }
    }

    /*
     * 注释：方法是继承flow.php中的方法进行重写
     * */
    protected function flowdatalog($arr){
        if(isset($arr['flowinfor']['ischeck'])){
            if($arr['status']!=5 && $arr['flowinfor']['ischeck']==1 && $arr['flowinfor']['nowcourse']['id'] == 91) {
                //显示归档操作页面
                $arr['flowinfor']['showgd'] = 1;
            }else{
                $arr['flowinfor']['showgd'] = 0;
            }
     }else{
            $arr['flowinfor']['showgd'] = 0;
        }
        return $arr;
    }




}
