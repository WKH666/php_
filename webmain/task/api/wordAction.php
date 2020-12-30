<?php 
/**
*	【文档】应用的接口
*/
class wordClassAction extends apiAction
{
	
	public function createfolderAction()
	{
		$name 	= $this->post('name');
		$typeid = (int)$this->post('typeid');
		if($typeid==0)$typeid = $this->getfolderid();
		$arr['name'] = $name;
		$arr['pid']  = $typeid;
		$arr['optid']  = $this->adminid;
		$arr['optdt']  = $this->now;
		$arr['id'] 	   = $this->option->insert($arr);
		$this->showreturn($arr);
	}
	
	private function getfolderid()
	{
		$id  = m('word')->getfolderid($this->adminid);
		return $id;
	}
	
	
	public function getfileAction()
	{
		$typeid = (int)$this->post('typeid','0');
		$pid	= $typeid;
		if($pid==0)$pid 	= $this->getfolderid();
		$slx 	= $this->post('slx');
		$rows	= array();
		if($slx=='')$rows 	= $this->option->getall("`pid`='$pid' and `valid`=1 order by `sort`,`id`");
		if($typeid==0){
			$where 	= " and a.optid=".$this->adminid." and a.typeid in($typeid, $pid)";
		}else{
			$where 	= " and a.optid=".$this->adminid." and a.typeid='$typeid'";
		}
		if($slx=='wfx')$where 	= " and a.optid=".$this->adminid." and a.shate is not null";
		if($slx=='fxgw'){
			$where 	= m('admin')->getjoinstrs('a.shateid', $this->adminid, 1);
		}
		
		$arr 	= $this->db->getall("select a.fileid,a.shate,a.typeid,b.filepath,a.optname,a.optid,a.optdt,b.filename,b.fileext,b.filesizecn from `[Q]word` a left join `[Q]file` b on a.fileid=b.id where b.id is not null $where order by a.`id` desc");
		$rows 	= array_merge($rows, $arr);			
		$this->showreturn($rows);
	}
	
	public function savefileAction()
	{
		$fileid = (int)$this->post('fileid');
		$typeid = (int)$this->post('typeid');
		$frs 	= m('file')->getone($fileid);
		$mid 	= m('word')->insert(array(
			'optid' 	=> $this->adminid,
			'optname' 	=> $this->adminname,
			'fileid' 	=> $fileid,
			'optdt' 	=> $frs['adddt'],
			'typeid' 	=> $typeid
		));
		m('file')->addfile($fileid,'word', $mid);
		$this->showreturn($frs);
	}
	
	public function renameAction()
	{
		$id 	= (int)$this->post('id');
		$name 	= $this->getvals('name');
		$type 	= $this->post('type');
		if($type=='folder'){
			$this->option->update("`name`='$name'", $id);
		}else{
			m('file')->update("`filename`='$name'", $id);
		}
		$this->showreturn('');
	}
	
	public function movefileAction()
	{
		$id 	= (int)$this->post('id');
		$tid 	= (int)$this->post('tid');
		$type 	= $this->post('type');
		if($type=='folder'){
			
		}else{
			m('word')->update("`typeid`=$tid", "`fileid`='$id' and `optid`='$this->adminid'");
		}
		$this->showreturn('');
	}
	public function delfileAction()
	{
		$id 	= (int)$this->post('id');
		$type 	= $this->post('type');
		if($type=='folder'){
			$delbo	= true;
			if($delbo)if($this->option->rows("`pid`='$id'")>0)$delbo=false;
			if(!$delbo)$this->showreturn('','有下级文件夹不允许删除',201);
			$this->option->delete($id);
			m('word')->update('`typeid`=0', "`typeid`='$id'");
		}else{
			m('file')->delfile($id);
			m('word')->delete("`fileid`='$id' and `optid`='$this->adminid'");
		}
		$this->showreturn('');
	}
	public function shatefileAction()
	{
		$id 	= (int)$this->post('id');
		$type 	= $this->post('type');
		$shate 	= $this->getvals('shate');
		$shateid= $this->post('shateid');
		if($type=='folder'){
			
		}else{
			m('word')->update("`shate`='$shate',`shateid`='$shateid'","`fileid`='$id' and `optid`='$this->adminid'");
		}
		$this->showreturn('');
	}
	
	
	//word生成api
	public function wordAction()
	{
		$num=$this->get('num');
		$mid=$this->get('mid');
		
//		$admin_info=m('admin')->getone('id='.$this->adminid);

//		if($num=='project_apply'){
//				$project_apply=m($num)->getone('id='.$mid);
//					$word_name=$project_apply['project_name'].'-申报书';
//				}else{
//					$word_name=date('YmdHi');
//			}
		$word_name=date('YmdHi');

        c('word')->testMht($num,$mid,$word_name);
//		if($admin_info['is_admin']==1){
//
//			c('word')->testMht($num,$mid,$word_name);
//
//		}else{
//
//			c('word')->testMhtPdf($num,$mid,$word_name);
//
//		}
		
		
	}


	//pdf生成api
    public function pdfAction(){
        $num=$this->get('num');
        $mid=$this->get('mid');
        $word_name=date('YmdHi');
        c('word')->testMhtPdf($num,$mid,$word_name);
    }
}