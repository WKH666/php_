<?php
/**
*	此文件是流程模块【project_skpjm.社科普及月项目申报】对应接口文件。
*	可在页面上创建更多方法如：public funciton testactAjax()，用js.getajaxurl('testact','mode_project_skpjm|input','flow')调用到对应方法
*/
class mode_project_skpjmClassAction extends inputAction{

	/**
	*	重写函数：保存前处理，主要用于判断是否可以保存
	*	$table String 对应表名
	*	$arr Array 表单参数
	*	$id Int 对应表上记录Id 0添加时，大于0修改时
	*	$addbo Boolean 是否添加时
	*	return array('msg'=>'错误提示内容','rows'=> array()) 可返回空字符串，或者数组 rows 是可同时保存到数据库上数组
	*/
	protected function savebefore($table, $arr, $id, $addbo){

	}

	/**
	*	重写函数：保存后处理，主要保存其他表数据
	*	$table String 对应表名
	*	$arr Array 表单参数
	*	$id Int 对应表上记录Id
	*	$addbo Boolean 是否添加时
	*/
	protected function saveafter($table, $arr, $id, $addbo){

	}

    /*表单的活动形式*/
    public function activity_type_func(){
        $arr[] = array(
            "value" => '报告讲座',
            "name" => '报告讲座',
        );
        $arr[] = array(
            "value" => '展览展示展演',
            "name" => '展览展示展演',
        );
        $arr[] = array(
            "value" => '广场咨询服务',
            "name" => '广场咨询服务',
        );
        $arr[] = array(
            "value" => '知识竞赛',
            "name" => '知识竞赛',
        );
        $arr[] = array(
            "value" => '社科普及读物或教材',
            "name" => '社科普及读物或教材',
        );
        $arr[] = array(
            "value" => '社科进基层',
            "name" => '社科进基层',
        );
        $arr[] = array(
            "value" => '微社科',
            "name" => '微社科',
        );
        $arr[] = array(
            "value" => '其他',
            "name" => '其他',
        );
        return $arr;
    }
}
