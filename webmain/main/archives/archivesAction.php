<?php
/**
 * 开发者gzj
 */
 
class archivesClassAction extends Action
{
	
	/**
	 * 获取当前项目的文件
	 */
	public function getthisfilesAjax(){
		$mid = $this->request('mid');//项目id
		$mtype = $this->request('mtype');//项目模块
		$tab_id = $this->request('tab_id');//数据库名-id
		$file_name = $this->request('file_name');//文件名称
		$where='';//查询条件
		if(!empty($file_name))$where.=" and filename like '%$file_name%'";
		
		//如果传入文件tab_id,则根据tab_id获取文件
		if($tab_id){
			list($tab,$id) = explode('-',$tab_id);
			$fids = m('mf_'.$tab)->getone($tab.'_id='.$id,'file_ids')['file_ids'];
			//先判断是否为空
			if($fids){
				$files = array();
				foreach (explode(',', $fids) as $k => $v) {
					$files[$k] = m('file')->getone('id='.$v.$where);
				}
				unset($k,$v);
				$arr['rows'] = $files;
			}else{
				$arr['rows'] = array();
			}
		}else{
			if(empty($mid))exit('当前项目id不存在');
			$arr['rows'] = m('file')->getfilewhere("$mtype",$mid,$where);
			//var_dump($arr['rows']);exit;
			
			//根据流程id获取当前项目的审核时上传的文件
			$flowidarr = m('flow_log')->getall("`table`=$mtype and `mid`=$mid",'id');
			$flowids = '';
			foreach($flowidarr as $k=>$rs){
				$flowids.=','.$rs['id'].'';
			}
			unset($k,$rs);
			$farr = m('file')->getfile('flow_log', substr($flowids,1));
			foreach ($farr as $k => $v) {
				if($log_file=m('file')->getone('id='.$v['id'].$where,'*')){
					array_push($arr['rows'],$log_file);
				}
			}
			unset($k,$v);
		}
		$arr['totalCount'] = count($arr['rows']);
		if($arr['totalCount'] == 0){
			exit('暂无数据');
		}
		$this->returnjson($arr);
	}
	
	/**
	 * 多文件打包下载
	 */
	public function packAllToZipAjax(){
		$project_id = $this->request('mid');
		$file_ids = $this->request('file_ids');
		//var_dump($file_ids);exit;
		if($file_ids == '' || $file_ids == 'undefine'){
			exit('请选择要下载的文件');
		}
		//获取要生成的文件名
		$project_name = m('project_apply')->getone('`id`='.$project_id, 'project_name');
		$file_id_arr = explode(',', $file_ids);
		$datalist = array();//文件数组
		foreach ($file_id_arr as $k => $v) {
			$datalist[] = m('file')->getone('`id`='.$v, 'filename,filepath');
		}
		unset($k, $v);
		//把文件复制到统一的文件夹中
		if(!@mkdir($project_name['project_name'])){
			@mkdir(iconv('utf-8', 'gbk', $project_name['project_name']));
		}
		$dlUrl = array();
		foreach ($datalist as $k => $v) {
			$dlUrl[] = $this->file2dir($v['filepath'], iconv('utf-8', 'gbk', $project_name['project_name']), iconv('utf-8', 'gbk', $v['filename']));
		}
		$this->download(iconv('utf-8', 'gbk', $project_name['project_name']), $dlUrl);
	}
	
	/**
	 * 把指定的文件复制到指定的文件夹中(并重命名文件名)
	 * $sourcefile原文件路径
	 * $dir指定的文件夹名
	 * $newname重命名后的文件名
	 * return 返回复制后的文件地址
	 */
	private function file2dir($sourcefile, $dir, $newname){
	    if(!file_exists($sourcefile)){
	        return false;
	    }
		copy($sourcefile, $dir .'/'. $newname);
	    return $dir .'/'. $newname;
	}
	
	
	private function download($project_name, $datalist){
//		var_dump($datalist);exit;
		$tmpdir = $project_name;//临时下载文件夹
		$filename = $project_name.'.zip';
	    $zip = new ZipArchive();//使用本类，linux需开启zlib，windows需取消php_zip.dll前的注释   
	    if ($zip->open($filename, ZIPARCHIVE::CREATE)!==TRUE) {   
	        exit('无法打开文件，或者文件创建失败');
	    }
	    foreach($datalist as $val){
	        if(file_exists($val)){
	            $zip->addFile($val);//第二个参数是放在压缩包中的文件名称，如果文件可能会有重复，就需要注意一下   
	        }   
	    }
		unset($val);
	    $zip->close();//关闭   
		//清空（擦除）缓冲区并关闭输出缓冲
		ob_end_clean();
		//下载建好的.zip压缩包
		header("Content-Type: application/force-download");//告诉浏览器强制下载
		header("Content-Transfer-Encoding: binary");//声明一个下载的文件
		header('Content-Type: application/zip');//设置文件内容类型为zip
		header('Content-Disposition: attachment; filename='.$filename);//声明文件名
		header('Content-Length: '.filesize($filename));//声明文件大小
		error_reporting(0);
		//将欲下载的zip文件写入到输出缓冲
		readfile($filename);
		//将缓冲区的内容立即发送到浏览器，输出
		flush();
		$this->delDirAndFile($tmpdir);
		@unlink($filename);
	}

	//循环删除目录和文件函数
	private function delDirAndFile($dirName){
		if($handle = opendir("$dirName")){
			while(false !== ($item = readdir($handle))){
				if($item != "." && $item != ".."){
					if(is_dir("$dirName/$item") ) {
						delDirAndFile( "$dirName/$item" );
					}else{
						unlink("$dirName/$item");
					}
				}
			}
			closedir($handle);
			rmdir($dirName);
		}
	}
	
	/**
	 * 单个文件下载
	 */
	public function downloadoneAjax(){
		header("Content-type: text/html; charset=utf-8");
		$file_id = $this->request('file_id');//要下载的文件id
		$file_info = m('file')->getone('`id`='.$file_id, 'filename,filepath');
		$filepath = $file_info['filepath'];//文件路径
		$newname = $file_info['filename'];//文件名
		if(!file_exists($file_info['filepath'])){
	        exit('文件不存在');
	    }
		$file = fopen($filepath, "r");//读取文件数据
		header( "Content-type:  application/octet-stream ");
		header( "Accept-Ranges:  bytes ");
		header( "Content-Disposition:  attachment;  filename={$newname}");
		header( "Accept-Length: ".filesize($filepath));
		echo fread($file, filesize($filepath));
    	fclose($file);
	}
	
	/**
	 * 上传文件到对应的项目
	 */
	public function savefileAjax(){
		$mid = (int)$this->post('mid',0);//对应的项目id
		$sid 	= $this->post('sid');
		$sadid	= explode(',', $sid);
		
		$arr['optid'] 	= $this->adminid;
		$arr['optname'] = $this->adminname;
		$arr['adddt'] 	= $this->now;
		$arr['mid'] 	= $mid;
		$file = m('file');
		foreach($sadid as $fid){
			$arr['fileid'] = $fid;
			$file->addfile($fid, 'project_apply', $mid);
		}
		echo 'ok';
	}
	
	
	
	//end
	
}