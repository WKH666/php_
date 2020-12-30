<?php

?>

<script>
    var pinshenType = '';
	$(function () {
		{params}
        pinshenType = params.pinshentype;
		$('#myTab_{rand} li:eq(0) a').tab('show');

		$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        //		if($(e.target).attr('href')=='#zhibiao'){
//
//		        target ="http://127.0.0.1/qq.html"; // 找到链接a中的targer的值
//		        $.get(target,function(data){
//		            $("#iframeContent").html(data);
//		         });
//		}
		});

		 js.ajax(js.getajaxurl('pici_model', 'project_comment', 'main'), {pici_id:params.pici_id}, function(ds) {
			jsonstr=ds.data;

			formForm(jsonstr);

		}, 'post,json');



		/*a = $('#project_{rand}').bootstable({
			tablename:'m_pxm_relation',celleditor:true,
			url:js.getajaxurl('project_list','project_comment','main', {pici_id:params.pici_id}),
			fanye:true,modename:'网评列表',
			celleditor:true,storeafteraction:'projectafter',
	//		storebeforeaction:'dataauthbefore',
			columns:[{
				text:'编号',dataIndex:'project_number'
			},{
				text:'名称',dataIndex:'project_name'
			},{
				text:'类别',dataIndex:'project_select'
			},{
				text:'申报单位',dataIndex:'deptname'
			},{
				text:'负责人',dataIndex:'project_head'
			},{
				text:'预算金额(万元)',dataIndex:'project_yushuan',sortable: true,renderer:function(v,d){
					return  d.project_yushuan/10000;

				}
			},{
				text:'申报时间',dataIndex:'project_apply_time',sortable: true
			},{
				text:'网评分数',dataIndex:'zongfen',sortable: true,
			},{
				text:'网评排名',dataIndex:'paimin',
			},{
				text:'推荐排名',dataIndex:'rec_ranking',sortable: true,editor:true
			},{
				text:'操作',dataIndex:'caoz',width:'180px'
			}],
		});*/
        //项目信息
        a = $('#project_{rand}').bootstable({
            tablename:'m_pxm_relation',celleditor:true,
            url:js.getajaxurl('project_list','project_comment','main', {pici_id:params.pici_id}),
            fanye:true,modename:'网评列表',
            celleditor:true,storeafteraction:'projectafter',
            //storebeforeaction:'dataauthbefore',
            columns:[{
                text:'登记号',dataIndex:'sericnum'
            },{
                text:'项目名称',dataIndex:'project_name'
            },{
                text:'申报类型',dataIndex:'modename'
            },{
                text:'学科',dataIndex:'subject_classification'
            },{
                text:'关键词分类',dataIndex:'keyword_classification'
            },{
                text:'关键词详情',dataIndex:'specific_keywords'
            },{
                text:'评审状态',dataIndex:'comment_status'
            },{
                text:'评审进度',dataIndex:'comment_progress'
            },{
                text:'操作',dataIndex:'caoz',width:'180px'
            }],
        });

        /*11月2日修改*/
		/*//专家信息
		b = $('#user_{rand}').bootstable({
			url:js.getajaxurl('user_list','project_comment','main',{pici_id:params.pici_id}),
			fanye:true,modename:'网评列表',
			celleditor:true,storeafteraction:'userafter',
	//		storebeforeaction:'dataauthbefore',
			columns:[{
				text:'姓名',dataIndex:'name'
			},{
				text:'职务/职称',dataIndex:'ranking'
			},{
				text:'所在单位',dataIndex:'deptname'
			},{
				text:'完成状态',dataIndex:'c_status'
			},{
				text:'网评进度',dataIndex:'schedule'
			},{
				text:'操作',dataIndex:'caoz',width:'180px'
			}],
		});*/
        //评审信息
        b = $('#user_{rand}').bootstable({
            url:js.getajaxurl('comment_info','project_comment','main',{pici_id:params.pici_id}),
            fanye:true,modename:'网评列表',
            celleditor:true,storeafteraction:'comment_infoafter',
            //storebeforeaction:'dataauthbefore',
            columns:[{
                text:'项目编号',dataIndex:'sericnum'
            },{
                text:'项目名称',dataIndex:'project_name'
            },{
                text:'操作',dataIndex:'caoz'
            }],
        });

	});



	//该函数接受一段json字符串为参数，并生成表格
	function formForm(jsonstr) {
		var jsonobj = $.parseJSON(jsonstr);
		$('.xmk-panel-title').html(jsonobj.name);
		var tr = '';
		for(var i = 0; i < jsonobj.info.length; i++) { //循环最外层，一共有多少项总指标
			var td = '';
			var mainTarget = '';
			var itr = '';
			for(var j = 0; j < jsonobj.info[i].info.length; j++) { //连同里层一起循环，一次性把所有行都画出来

				var subTarget = []; //subTarget.length=0;
				var sortRange = []; //sortRange.length=0;
				var mainTarget = []; //mainTarget.length=0;
				var inputScore = []; //inputScore.length=0;
				mainTarget[0] = '<td rowspan="' + jsonobj.info[i].info.length + '">' + jsonobj.info[i].option_msg + '<br />(' + '<span>' + jsonobj.info[i].option_fenzhi + '</span>' + '分)</td>'; //主项标题，需要合并
				subTarget[j] = jsonobj.info[i].info[j].option_msg; //子项标题
				sortRange[j] = jsonobj.info[i].info[j].minscore + '-' + jsonobj.info[i].info[j].maxscore; //子项分值区间
				//inputScore[0] = '<td rowspan="' + jsonobj.info[i].info.length + '"><input name="option" type="text" placeholder="填写分数"></input></td>'; //主项分值填写，需要合并
				//inputScore[0] = '<td rowspan="' + jsonobj.info[i].info.length + '"></td>'; //主项分值填写，需要合并
				itr += '<tr>' + mainTarget[j] + '<td style="text-align: left;">' + subTarget[j] + '</td><td>' + sortRange[j] + '</td>' + inputScore[j] + '</tr>';
			}
			tr += itr;

		}
		$('#mytable tbody').append(tr);
	}

	//num:申报编号,对应flow_bill表的table字段
	//mid:申报编号,对应flow_bill表的mid字段
	//project_name:申报项目名称
	function check_project(num,mid,project_name){

	addtabs({num:'check_'+num+'_{rand}'+mid,url:getRootPath()+'/task.php?a=p&num='+num+'&mid='+mid+'&btnstyle=1',icons:'icon-bookmark-empty',name:'['+project_name+']详情'});
	}

	function project_pxm(xid){
			{params}
			pici_id=params.pici_id;
		c = $('#project_pxm_{rand}').bootstable({
			url:js.getajaxurl('project_pxm','project_comment','main',{pici_id:pici_id,mid:xid}),
			fanye:true,modename:'网评列表',
			celleditor:true,storeafteraction:'project_pxmafter',
	        //storebeforeaction:'dataauthbefore',
			columns:[{
				text:'专家姓名',dataIndex:'name'
			},{
				text:'网评分数',dataIndex:'user_zongfen'
			},{
				text:'完成状态',dataIndex:'caoz',width:'180px'
			}],
		});
		$("#project_pxmModal").modal("show");
	}

	function user_pxm(id){
			{params}
			pici_id=params.pici_id;
		d = $('#user_pxm_{rand}').bootstable({
			url:js.getajaxurl('user_pxm','project_comment','main',{pici_id:pici_id,uid:id}),
			fanye:true,modename:'网评列表',
			celleditor:true,storeafteraction:'user_pxmafter',
	//		storebeforeaction:'dataauthbefore',
			columns:[{
				text:'编号',dataIndex:'sericnum'
			},{
				text:'名称',dataIndex:'project_name'
			},{
				text:'指标评分',dataIndex:'user_zongfen'
			},{
                text:'评审意见',dataIndex:'review_opinion',width:'180px'
            },{
                text:'评审时间',dataIndex:'operating_time',width:'180px'
            },{
				text:'完成状态',dataIndex:'status',width:'100px'
			},{
				text:'操作',dataIndex:'caoz',width:'100px'
			}],
		});

		$("#user_pxmModal").modal("show");
	}

	function getUrlParam(name) {
            var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)"); //构造一个含有目标参数的正则表达式对象
            var r = window.location.search.substr(1).match(reg);  //匹配目标参数
            if (r != null) return unescape(r[2]); return null; //返回参数值
    }

    function setHouJian(num,id) {
    		{params}
			pici_id=params.pici_id;
            layer.confirm('请确认归入侯建库', {
			  btn: ['确认','取消'] //按钮
			}, function(){

			 	//执行更新列表方法和ajax方法
			 	 js.ajax(js.getajaxurl('setHouJian', 'project_comment', 'main'), {pici_id:params.pici_id,mtype:num,mid:id}, function(ds) {
					jsonstr=ds.msg;

					layer.msg(jsonstr);
					if(ds.success=true){
						a.reload();

					}
				}, 'post,json');
			}, function(){

			});
    }

    //评审信息表格查询
    function search_comment_info() {
        b.setparams({
            project_name:$('#project_name_{rand}').val(),
            sericnum:$('#sericnum_{rand}').val(),
        },true);
    }
    //评审信息表格重置
    function reset_comment_info() {
        $('#project_name_{rand}').val('');
        $('#sericnum_{rand}').val('');
        b.setparams({
            project_name:'',
            sericnum:'',
        },true);
    }
    //查看评审信息
    function read_comment_info(pici_id,xid) {
        if(get('read_comment_info')) closetabs('read_comment_info');
        addtabs({num:'read_comment_info',url:'main,project_comment,readinfo,pici_id='+pici_id+',xid='+xid+',pinshentype='+pinshenType,icons:'icon-bookmark-empty',name:'评审信息'});
    }
    //项目信息表格查询
    function search_project_info() {
        a.setparams({
            project_name:$('#project_names_{rand}').val(),
            sericnum:$('#sericnums_{rand}').val(),
            project_type:$('#project_type_{rand}').val(),
        },true);
    }
    //项目信息表格重置
    function reset_project_info() {
        $('#project_names_{rand}').val('');
        $('#sericnums_{rand}').val('');
        $('#project_type_{rand}').val('');
        a.setparams({
            project_name:'',
            sericnum:'',
            project_type:'',
        },true);
    }

</script>
<style>

/*指标表个样式*/
.xmk-panel-title{
	background: #244d81;
    color: white;
    height: 50px;
    line-height: 50px;
    font-size: 20px;
    display: block;

    text-align: center;
}
.xmk-table{
	width: 100%;
	border: none;
	border-collapse: collapse;
	text-align: center;
}
.xmk-table{
	width: 100%;
	border: none;
	border-collapse: collapse;
	text-align: center;
}
.xmk-table a{
	text-decoration: none;
}
.xmk-table a:visited{
	color: #000;
}
.xmk-table td{
	border: solid #D6DED3 1px;
	height: 36px;
	padding: 0 10px;
}
.xmk-table td {
	border: solid #797979 1px;
	line-height: 30px;
	padding:3px 10px;
}
.xmk-table thead td {
	background-color: #fff;
	font-size: 18px;
	line-height: 60px;
}
.xmk-table thead{
	background-color: #F1F9EC;

}
.xmk-table tfoot td {
	background-color: #fff;
	font-size: 18px;
	line-height: 60px;
}
.xmk-table td input {
	border: none;
	width: 100%;
	height: 100%;
	text-align: center;
	outline:medium;
}
.xmk-table td input:focus {
	outline: none;
	box-shadow: none;
}

</style>
<ul id="myTab_{rand}" class="nav nav-tabs">
	<li class="active">
		<a href="#home" data-toggle="tab">项目信息</a>
	</li>
	<li><a href="#ios" data-toggle="tab">评审信息</a></li>
	<li><a href="#zhibiao" data-toggle="tab">指标信息</a></li>
</ul>
<div id="myTabContent" class="tab-content">
	<div class="tab-pane fade in active" id="home">
        <form>
            <section class="serachPanel selBackGround">
                <div class="searchAPanel">
                    <div class="searchAc1">
                        <ul>
                            <li>
                                <span class="reviewContent stateContent">登记号：</span>
                            </li>
                            <li>
                                <input type="text" id="sericnums_{rand}" name="sericnums" class="form-control txtPanel">
                            </li>
                        </ul>
                    </div>
                    <div class="searchAc1">
                        <ul>
                            <li>
                                <span class="reviewContent stateContent">项目名称：</span>
                            </li>
                            <li>
                                <input type="text" id="project_names_{rand}" name="projects_names" class="form-control txtPanel">
                            </li>
                        </ul>
                    </div>
                    <div class="searchAc1">
                        <ul>
                            <li>
                                <span class="reviewContent stateContent">申报类型：</span>
                            </li>
                            <li>
                                <input type="text" id="project_type_{rand}" name="projects_type" class="form-control txtPanel">
                            </li>
                        </ul>
                    </div>
                    <div class="searchAc1" >
                        <ul>
                            <li>
                                <input class="btn_ marH1" type="button" onclick="search_project_info()" value="查询" />
                            </li>
                            <li>
                                <button class="btn_ marH1" type="reset" onclick="reset_project_info()">重置</button>
                            </li>
                        </ul>
                    </div>
                </div>
            </section>
        </form>
		<p>
		<div id="project_{rand}"></div>
          </p>
	</div>
	<div class="tab-pane fade" id="ios">
        <form>
            <section class="serachPanel selBackGround">
                <div class="searchAPanel">
                    <div class="searchAc1">
                        <ul>
                            <li>
                                <span class="reviewContent stateContent">登记号：</span>
                            </li>
                            <li>
                                <input type="text" id="sericnum_{rand}" name="sericnum" class="form-control txtPanel">
                            </li>
                        </ul>
                    </div>
                    <div class="searchAc1">
                        <ul>
                            <li>
                                <span class="reviewContent stateContent">项目名称：</span>
                            </li>
                            <li>
                                <input type="text" id="project_name_{rand}" name="project_name" class="form-control txtPanel">
                            </li>
                        </ul>
                    </div>
                    <div class="searchAc1" >
                        <ul>
                            <li>
                                <input class="btn_ marH1" type="button" onclick="search_comment_info()" value="查询" />
                            </li>
                            <li>
                                <button class="btn_ marH1" type="reset" onclick="reset_comment_info()">重置</button>
                            </li>
                        </ul>
                    </div>
                </div>
            </section>
        </form>
		<p>
		<div id="user_{rand}"></div>
		</p>
	</div>
	<div class="tab-pane fade" id="zhibiao">
		<p>
				<div align="center">
					<span class="xmk-panel-title"></span>
					<table class="xmk-table" id="mytable" border="1px">
						<thead>
							<tr>
								<td style="width: 15%;min-width: 150px;"> 评审内容 </td>
								<td style="width: 55%;min-width: 450px;"> 内容分类及标准 </td>
								<td style="width: 15%;min-width: 100px;"> 赋分范围 </td>
								<!--<td style="width: 15%;min-width: 100px;"> 分项分值 </td>-->
							</tr>
						</thead>
						<tbody>
							<!-- <tr>
							<td rowspan="2">基地类型（10分）</td>
							<td>品牌专业等重大专业建设项目以及省级实训基地、省公共实训中心、重点实验室等重点建设项目</td>
							<td>6-10</td>
							<td rowspan="2"><input type="text" placeholder="填写分数"></input></td>
							</tr>
							<tr>
							<td>品牌专业等重大专业建设项目以及省级实训基地、省公共实训中心、重点实验室等重点建设项目</td>
							<td>6-10</td>
							</tr> -->
						</tbody>
						<tfoot>
							<tr>
								<td colspan="2">总计得分</td>
								<td>100</td>
								<!--<td id="totalscore"></td>-->
							</tr>
						</tfoot>
					</table>
				</div>
		</p>
	</div>
</div>


<!--查看项目完成情况-->
<div class="modal fade" id="project_pxmModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title" id="myModalLabel">查看项目完成情况</h4>
			</div>
			<div class="modal-body">

				<div id="project_pxm_{rand}"></div>
			</div>
		</div>
	</div>
</div>


<!--查看专家对项目网评情况-->
<div class="modal fade" id="user_pxmModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title" id="myModalLabel">查看专家完成情况</h4>
			</div>
			<div class="modal-body">

				<div id="user_pxm_{rand}"></div>
			</div>
		</div>
	</div>
</div>


