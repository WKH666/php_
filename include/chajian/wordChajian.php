<?php
/**
	word文档相关类库
*/

class wordChajian extends Chajian{
	
	public $config=array();  
    public $headers = array();  
    public $headers_exists=array();  
    public $files=array();  
    public $boundary;  
    public $dir_base;  
    public $page_first; 
			
	
	public function initChajian()
	{
		
	}
		
	public function testMht($num,$mid,$word_name)  
	{
			$uid=$this->adminid;
			$row= m('logintoken')->getone("`cfrom`='pc' and `online`=1 and `uid`=$uid");		
			
			//$url="http://127.0.0.1/xiangmukuv0.2/task.php?a=p&num=$num&mid=$mid&stype=word&adminid=".$row['uid']."&token=".$row['token']."&device=".$row['device'];
			//var_dump($url);
			$fh= file_get_contents(getconfig('url')."task.php?a=p&num=$num&mid=$mid&stype=word&adminid=".$row['uid']."&token=".$row['token']."&device=".$row['device']);
			
			$fileContent = $this->getWordDocument($fh,getconfig('url'));  
			
			$filesize=mb_strlen($fileContent);

			header('Content-type:application/msword');
			header('Accept-Ranges: bytes');
			header("Content-Length:".$filesize);
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header('Pragma: no-cache');
			header('Expires: 0');
			header('Content-disposition:attachment;filename='.$word_name.'.doc');
			header('Content-Transfer-Encoding: binary');
			
			echo $fileContent;
	}
	
	//基于office转化为PDF
	public function testMhtPdf($num,$mid,$word_name)  
	{
			$uid=$this->adminid;
			$row= m('logintoken')->getone("`cfrom`='pc' and `online`=1 and `uid`=$uid");		
			
			//$url="http://127.0.0.1/xiangmukuv0.2/task.php?a=p&num=$num&mid=$mid&stype=word&adminid=".$row['uid']."&token=".$row['token']."&device=".$row['device'];
//			var_dump($url);
			//"http://127.0.0.1/xiangmukuv0.2/task.php?a=p&num=$num&mid=$mid&stype=word&adminid=".$row['uid']."&token=".$row['token']."&device=".$row['device']
			$fh= file_get_contents(getconfig('url')."task.php?a=p&num=$num&mid=$mid&stype=word&adminid=".$row['uid']."&token=".$row['token']."&device=".$row['device']);
			//$fh= file_get_contents(getconfig('url')."task.php?a=p&num=$num&mid=$mid&stype=word");
			
			$fileContent = $this->getWordDocument($fh,getconfig('url'));  
			
			$name=uniqid();
			$date=date('Ymd');
			$file_path=ROOT_PATH .'/storage/'.$date.'/';
		
			

			if (!file_exists($file_path)) {
			    mkdir ($file_path);
			}
			
			$fp = fopen($file_path.$name.'.doc', 'w');
			fwrite($fp, $fileContent);
			fclose($fp);
			
			/*需要office COM组件支持 
			先生成doc=〉pdf*/
			$word = new COM("Word.application") or die("sdfs");	$word ->Visible = 1;	
			$doc = $word->Documents->Open($file_path.$name.'.doc');	
			$doc ->SaveAs2();	
			$doc ->ExportAsFixedFormat($file_path.$name.'.pdf',17);	
			$word ->Quit();
			
			
			$file=fopen($file_path.$name.'.pdf',"r");
			header("Content-Type: application/octet-stream");
			header("Accept-Ranges: bytes");
			header("Accept-Length: ".filesize($file_path.$name.'.pdf'));
			header("Content-Disposition: attachment; filename=".$word_name.".pdf");
			echo fread($file,filesize($file_path.$name.'.pdf'));
			fclose($file);
	
	}
			
	
	public function getfolderid($uid)
	{
		$num = "folder".$uid."";
		$id  = m('option')->getnumtoid($num, ''.$this->adminname.'文件夹目录', false);
		return $id;
	}
	
	public function getfoldrows($uid)
	{
		$pid 	= $this->getfolderid($uid);
		$rows 	= m('option')->gettreedata($pid);
		return $rows;
	}
	
	
	public function getWordDocument( $content , $absolutePath = "" , $isEraseLink = true )  
	{  
	    $mht =$this;  
	    if ($isEraseLink)  
	        $content = preg_replace('/<a\s*.*?\s*>(\s*.*?\s*)<\/a>/i' , '$1' , $content);   //去掉链接  
	  
	    $images = array();  
	    $files = array();  
	    $matches = array();  
	    //这个算法要求src后的属性值必须使用引号括起来  
	    //preg_match_all('/<img[.\s]*?src\s*?=\s*?[\"\'](.*?)[\"\'](.*?)>/i',$content ,$matches ) 
	    preg_match_all('<img(.*?)src\s*?=\s*?[\"\'](.*?)[\"\'](.*?)>',$content ,$matches);
	    //var_dump($matches); exit;
	    if (!empty($matches))  
	    //die("/(?<=<*img[\s\S]+src=['\"]).*(?=[\"']\s+)/");  
	    //if ( preg_match_all("/(?<=<[\s](0, 1)img[\s\S]+src=['\"]).*(?=[\"']\s)/",$content ,$matches ) )  
	    {   
	        $arrPath = $matches[2];  
	        for ( $i=0;$i<count($arrPath);$i++)  
	        {  
	            $path = $arrPath[$i];  
	            $imgPath = trim( $path );  
	            if ( $imgPath != "" )  
	            {  
	                $files[] = $imgPath;  
	                if( substr($imgPath,0,7) == 'http://')  
	                {  
	                    //绝对链接，不加前缀  
	                }  
	                else  
	                {  
	                    $imgPath = $absolutePath.$imgPath;  
	                }  
	                $images[] = $imgPath;  
	            }  
	        }  
	    }  
	    //print_r($images);die();  
	    $mht->AddContents("tmp.html",$mht->GetMimeType("tmp.html"),$content);  
	  
	    for ( $i=0;$i<count($images);$i++)  
	    {  
	        $image = $images[$i];  
	        if ( @fopen($image , 'r') )  
	        {  
	            $imgcontent = @file_get_contents( $image );  
	            if ( $content )  
	                $mht->AddContents($files[$i],$mht->GetMimeType($image),$imgcontent);  
	        }  
	        else  
	        {  
	            echo "file:".$image." not exist!<br />";  
	        }  
	    }  
	  
	    return $mht->GetFile();  
	}  
	
	public function MhtFile($config = array()){  
  
    }  
  
    public function SetHeader($header){  
        $this->headers[] = $header;  
        $key = strtolower(substr($header, 0, strpos($header, ':')));  
        $this->headers_exists[$key] = TRUE;  
    }  
  
    public function SetFrom($from){  
        $this->SetHeader("From: $from");  
    }  
  
    public function SetSubject($subject){  
        $this->SetHeader("Subject: $subject");  
    }  
  
    public function SetDate($date = NULL, $istimestamp = FALSE){  
        if ($date == NULL) {  
            $date = time();  
        }  
        if ($istimestamp == TRUE) {  
            $date = date('D, d M Y H:i:s O', $date);  
        }  
        $this->SetHeader("Date: $date");  
    }  
  
    public function SetBoundary($boundary = NULL){  
        if ($boundary == NULL) {  
            $this->boundary = '--' . strtoupper(md5(mt_rand())) . '_MULTIPART_MIXED';  
        } else {  
            $this->boundary = $boundary;  
        }  
    }  
  
    public function SetBaseDir($dir){  
        $this->dir_base = str_replace("\\", "/", realpath($dir));  
    }  
  
    public function SetFirstPage($filename){  
        $this->page_first = str_replace("\\", "/", realpath("{$this->dir_base}/$filename"));  
    }  
  
    public function AutoAddFiles(){  
        if (!isset($this->page_first)) {  
            exit ('Not set the first page.');  
        }  
        $filepath = str_replace($this->dir_base, '', $this->page_first);  
        $filepath = 'http://mhtfile' . $filepath;  
        $this->AddFile($this->page_first, $filepath, NULL);  
        $this->AddDir($this->dir_base);  
    }  
  
    public function AddDir($dir){  
        $handle_dir = opendir($dir);  
        while ($filename = readdir($handle_dir)) {  
            if (($filename!='.') && ($filename!='..') && ("$dir/$filename"!=$this->page_first)) {  
                if (is_dir("$dir/$filename")) {  
                    $this->AddDir("$dir/$filename");  
                } elseif (is_file("$dir/$filename")) {  
                    $filepath = str_replace($this->dir_base, '', "$dir/$filename");  
                    $filepath = 'http://mhtfile' . $filepath;  
                    $this->AddFile("$dir/$filename", $filepath, NULL);  
                }  
            }  
        }  
        closedir($handle_dir);  
    }  
  
    public function AddFile($filename, $filepath = NULL, $encoding = NULL){  
        if ($filepath == NULL) {  
            $filepath = $filename;  
        }  
        $mimetype = $this->GetMimeType($filename);  
        $filecont = file_get_contents($filename);  
        $this->AddContents($filepath, $mimetype, $filecont, $encoding);  
    }  
  
    public function AddContents($filepath, $mimetype, $filecont, $encoding = NULL){  
        if ($encoding == NULL) {  
            $filecont = chunk_split(base64_encode($filecont), 76);  
            $encoding = 'base64';  
        }  
        $this->files[] = array('filepath' => $filepath,  
                               'mimetype' => $mimetype,  
                               'filecont' => $filecont,  
                               'encoding' => $encoding);  
    }  
  
    public function CheckHeaders(){  
        if (!array_key_exists('date', $this->headers_exists)) {  
            $this->SetDate(NULL, TRUE);  
        }  
        if ($this->boundary == NULL) {  
            $this->SetBoundary();  
        }  
    }  
  
    public function CheckFiles(){  
        if (count($this->files) == 0) {  
            return FALSE;  
        } else {  
            return TRUE;  
        }  
    }  
  
    public function GetFile(){  
        $this->CheckHeaders();  
        if (!$this->CheckFiles()) {  
            exit ('No file was added.');  
        } //www.jb51.net  
        $contents = implode("\r\n", $this->headers);  
        $contents .= "\r\n";  
        $contents .= "MIME-Version: 1.0\r\n";  
        $contents .= "Content-Type: multipart/related;\r\n";  
        $contents .= "\tboundary=\"{$this->boundary}\";\r\n";  
        $contents .= "\ttype=\"" . $this->files[0]['mimetype'] . "\"\r\n";  
        $contents .= "X-MimeOLE: Produced By Mht File Maker v1.0 beta\r\n";  
        $contents .= "\r\n";  
        $contents .= "This is a multi-part message in MIME format.\r\n";  
        $contents .= "\r\n";  
        foreach ($this->files as $file) {  
            $contents .= "--{$this->boundary}\r\n";  
            $contents .= "Content-Type: $file[mimetype]\r\n";  
            $contents .= "Content-Transfer-Encoding: $file[encoding]\r\n";  
            $contents .= "Content-Location: $file[filepath]\r\n";  
            $contents .= "\r\n";  
            $contents .= $file['filecont'];  
            $contents .= "\r\n";  
        }  
        $contents .= "--{$this->boundary}--\r\n";  
        return $contents;  
    }  
  
    public function MakeFile($filename){  
        $contents = $this->GetFile();  
        $fp = fopen($filename, 'w');  
        fwrite($fp, $contents);  
        fclose($fp);  
    }  
  
    public function GetMimeType($filename){  
        $pathinfo = pathinfo($filename);  
        switch ($pathinfo['extension']) {  
            case 'htm': $mimetype = 'text/html'; break;  
            case 'html': $mimetype = 'text/html'; break;  
            case 'txt': $mimetype = 'text/plain'; break;  
            case 'cgi': $mimetype = 'text/plain'; break;  
            case 'php': $mimetype = 'text/plain'; break;  
            case 'css': $mimetype = 'text/css'; break;  
            case 'jpg': $mimetype = 'image/jpeg'; break;  
            case 'jpeg': $mimetype = 'image/jpeg'; break;  
            case 'jpe': $mimetype = 'image/jpeg'; break;  
            case 'gif': $mimetype = 'image/gif'; break;  
            case 'png': $mimetype = 'image/png'; break;  
            default: $mimetype = 'application/octet-stream'; break;  
        }  
        return $mimetype;  
    }  
}
