<?php
class basic_manageClassAction extends Action
{
	
	private static $insertsuccessnum=0;//添加成功条数
	private $insertsuccesslastid = '';//插入成功的lastid
	
	public function initAction()
	{
		
	}

	/**
	 * 关键词分类列表
	 */
	public function wordlistAjax(){
		$table = '[Q]key_word';
		$fields = '*';
		$where = "del_status=0";
		$order = 'add_time desc';
		$this->getlist($table, $fields, $where, $order);
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
	 * 关键词列表操作获取
	 */
	public function wordlistafter($table,$rows){
		foreach($rows as $k=>$rs){
			$rows[$k]['caoz']='';
			$rows[$k]['caoz'].= '<a onclick="word_edit('.$rs['id'].')">编辑</a>';
			$rows[$k]['caoz'].= '<span style="padding:5px;">|</span>';
			$rows[$k]['caoz'].= '<a onclick="word_del('.$rs['id'].')">删除</a>';
		}
		return $rows;
	}

    /**
     * 删除关键词分类
     */
    public function worddelAjax(){
        $word_id = $this->rock->request('word_id');//指标id
        $delword = m('key_word')->update(array('del_status'=>1),"id=$word_id");
        if($delword)$this->returnjson(array('id'=>$delword,'success'=>true,'msg'=>'删除成功'));
        else $this->returnjson(array('id'=>$delword,'success'=>false,'msg'=>'删除失败'));
    }


    /**
     * 新增关键词分类
     */
    public function wordaddAjax(){
        $word_name = $this->rock->post('name');//关键词分类名称
        $requestTime = date('Y-m-d H:i:s',time());//获取接口请求时间
        $insert_data = m('key_word')->insert( array(
            'name'=>$word_name,
            'add_time'=>$requestTime
        ));

        if($this->insertsuccesslastid=$insert_data){
            self::$insertsuccessnum++;
            $reinfo = array('插入成功条数:'.self::$insertsuccessnum,'插入成功的lastid:'.$this->insertsuccesslastid);
            $this->returnjson(array('id' => $reinfo,'success' => true,'msg' => '新增成功'));
        }else{
            $this->returnjson(array('id' => '','success' => false,'msg' => '新增失败'));
        }

    }


    /**
     * 关键词分类详情返回
     */
    public function getworddetailAjax(){
        $word_id = $this->rock->request('word_id');//指标id
        //var_dump($this->getnormdetail($norm_id));exit;
        $this->returnjson(array('success'=>true,'data'=>$this->getworddetail($word_id)));
    }

    /**
     * 获取关键词分类详情
     */
    public function getworddetail($word_id){
        $word_info = m('key_word')->getone('id='.$word_id, $fields='name as name');
        //var_dump($norm_info);
        return json_encode($word_info);
    }


    /**
     * 编辑关键词分类
     */
    public function wordeditAjax(){
        $word_id = $this->rock->post('word_id');//关键词id
        $word_name = $this->rock->post('name');//关键词名

        //更新指标表
        $update_word = m('key_word')->update(array(
            'name'=>$word_name,
            'update_time'=>date('Y-m-d H:i:s')
        ),"id=$word_id");

        if($update_word)$this->returnjson(array('id'=>$update_word,'success'=>true,'msg'=>'编辑成功'));
        else $this->returnjson(array('id'=>$update_word,'success'=>false,'msg'=>'编辑失败'));

    }

    /**
     * 关键词分类搜索
     */
    public function wordsearchAjax(){
        $name = $this->rock->request('name');
        $table = '[Q]key_word';
        $fields = '*';
        $where = "del_status=0 and name like'%".$name."%'";
        $order = 'add_time desc';
        $this->getlist($table, $fields, $where, $order);
    }

    /**
     * 档案管理
     */
    public function fileslistAjax(){
        $table = '[Q]file';
        $fields = '*';
        $where = 'valid=1';
        $order = 'id desc';
        $this->getlist($table, $fields, $where, $order);
    }
    /**
     * 档案管理操作获取
     */
    public function filelistafter($table,$rows){

        foreach($rows as $k=>$rs){
            $rows[$k]['caoz']='';
            $rows[$k]['caoz'].= '<a  href="javascript:;" onclick="filesdownload('.$rs['id'].',\''.$rs['fileext'].'\',\''.$rs['filepath'].'\')">下载</a>';
            $rows[$k]['caoz'].= '<span style="padding:5px;">|</span>';
            $rows[$k]['caoz'].= '<a onclick="filesreportdel('.$rs['id'].')">删除</a>';
        }

        return $rows;
    }
    /**
     * 档案管理操作搜索
     */
    public function filelistbefore(){
        $nd_year =trim($this->post('nd_year'));
        $filename =trim($this->post('filename'));
        $where=' ';
        //查询
        if ($filename) {
            $where .= "and xinhu_file.filename like '%$filename%'";
        }
        if ($nd_year) {
            $where .= "and xinhu_file.nd_year like '%$nd_year%'";
        }
        return $where;
    }
    /**
     * 档案管理操作删除
     */
    public function delfilesAjax(){
        $id = $_POST['id2'];
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
     * 档案管理 上传按钮保存操作
     */
    public function baocunAjax(){
        $id = $_POST['id'];
        $nd = $_POST['nd'];
        $arr=array(
            'upload_status'=> '1',
            'nd_year'=> $nd
        );
        $bools = m("xfile")->update($arr,"id=".$id."");
        if($bools){
            echo json_encode(array(
                'code' => '200',
            )) ;
        }else{
            echo json_encode(array(
                'code' => '201',
            ));
        }
    }
    /**
     * 档案管理 上传按钮取消操作
     */
    public function cancelAjax(){
        $id = $_POST['id'];
        $bools = m("xfile")->delete("id=".$id."");
        if($bools){
            echo json_encode(array(
                'code' => '200',
            )) ;
        }else{
            echo json_encode(array(
                'code' => '201',
            ));
        }
    }
    /**
     * 档案管理 批量下载
     */
    public function staresultsAjax(){
        $group_id = $_POST['status_arr'];
//        var_dump($group_id);die();
        foreach($group_id as $k => $v){
            $rows= m("xfile")->getone("id=".$group_id[$k]."",'fileext,filepath' );
            $fileext[]=$rows['fileext'];
            $filepath[]=$rows['filepath'];
        }
        if($rows){
            echo json_encode(array(
                'fileext' => $fileext,
                'filepath' => $filepath,
                'ids' =>$group_id
            )) ;
        }else{
            echo json_encode(array(
                'code' => '201',
                'msg' => '更新失败'
            ));
        }
    }


}//end