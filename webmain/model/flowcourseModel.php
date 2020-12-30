<?php
class flowcourseClassModel extends Model
{
	//获取流程名字
	public function getname($id){
		
		$flowcourse_info=$this->db->getone("flow_course", "id=".$id);
		
		return $flowcourse_info['name'];
		
	}
}