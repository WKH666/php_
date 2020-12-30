<style>
.grid-form1 label {
	font-weight: normal;
}

.form-control {
	z-index: 0 !important;
}

.selSearch {
	padding: 0px 0;
}

.xmk-btn {
	display: inline-block;
	height: 28px;
	background-color: #244D81;
	border-radius: 5px;
	border: none;
	color: #fff;
	padding: 5px 30px;
	line-height: 14px;
}

.xmk-btn:hover {
	background-color: #337AB7;
}

.xmk-btn-grey {
	background-color: #898989;
}

.xmk-flowing-bar {
	display: block;
	width: 100%;
	position: fixed;
	margin-left: -10px;
	bottom: 0px;
	border-top: solid #ccc 2px;
	padding: 10px 0;
	background-color: #fff;
	text-align: center;
}

.xmk-flowing-bar input {
	margin: 0 20px;
}

.xmk-flowing-bar input:first-of-type {
	margin-left: -184px;
}

.serachPanel {
	display: inline-block;
	padding-left: 1%;
}

.serachPanel .searchAc1 {
	display: inline-block;
}

.serachPanel .searchAc1 ul {
	display: inline-block;
	height: 40px;
}

.serachPanel .searchAc1 ul li {
	float: left;
	height: 40px;
	line-height: 33px;
	/*padding: 0% 1%;*/
	padding-right: 10px;
}

.serachPanel .searchAPanel {
	position: relative;
	display: inline-block;
	padding-left: 10px;
	padding-top: 10px;
}

.selSearch {
	height: 32px;
	line-height: 32px;
}

.form-control {
	height: 32px;
	line-height: 32px;
}

.page__table tr>td {
	text-align: center;
}

.btn-default {
	padding: 5px 12px;
}

.list-hand tr {
	cursor: pointer;
}
</style>
<link rel="stylesheet" type="text/css" media="all" href="mode/daterangepicker/daterangepicker.css" />
<script type="text/javascript" src="mode/bootstrap3.3/js/bootstrap.min.js"></script>
<script src="js/jQuery.Hz2Py-min.js"></script>

<script type="text/javascript" src="mode/daterangepicker/moment.js"></script>
<script type="text/javascript" src="mode/daterangepicker/daterangepicker.js"></script>

<style type="text/css">
    .demo {
	position: relative;
}

.demo i {
	position: absolute;
	bottom: 10px;
	right: 24px;
	top: auto;
	cursor: pointer;
}
</style>

<script>
    //构造一个删除数组中某个元素的数组
Array.prototype.remove = function(val) {
	for(var i = 0; i < this.length; i++) {
		if(this[i] == val) {
			this.splice(i, 1);
			break;
		}
	}
};

String.prototype.trimStr=function(){
　　    return this.replace(/(^,*)|(\,$)/g, "");
};

var expertIds = ''; //选中的专家
var temp_expertIds = ''; //临时选中的专家变量
var projectIds = ''; //选中的项目
var temp_projectIds = ''; //临时选中的项目变量
var additems_project_ids = ''; //追加项目前原有的ids
var additems_expert_ids =''; //追加专家前原有的ids
var norm_ids = '';//选中的指标

var c = ''; //方法
$(document).ready(function() {
	{params};
	var id = params.id;
	if(!id) id = 0;
	var additems = params.additems;
	if(!additems) additems = false;
	var submitfields = 'id,mtype,pstype,pici_name,pici_start_time,pici_end_time,pici_norm_id,expert_ids,project_ids,is_submit';
	//表单提交
	var h = $.bootsform({
		window: false,
		rand: '{rand}',
		tablename: 'project_comment',
		url: js.getajaxurl('savecomment', 'project_comment', 'main'),
		modenum: 'project_comment',
		submitfields: submitfields,
		success: function(da) {
			closenowtabs();
			//成功后跳转到相应的批次列表
            if (da['mtype'] =='project_end'){
                if(get('tabs_comment_list')) closetabs('pici_endlist');
                addtabs({
                    num: 'pici_endlist',
                    url: 'main,project_comment,pici_endlist,atype=project_end',
                    icons: 'icon-bookmark-empty',
                    name: '结项评审',
                });
                thechangetabs('pici_endlist');
                try {
                    assessmentList.reload();
                } catch(e) {}
            }
            else if(da['mtype'] =='project_start'){
                if(get('tabs_comment_list')) closetabs('pici_startlist');
                addtabs({
                    num: 'pici_startlist',
                    url: 'main,project_comment,pici_startlist,atype=project_start',
                    icons: 'icon-bookmark-empty',
                    name: '立项评审',
                });
                thechangetabs('pici_startlist');
                try {
                    assessmentList.reload();
                } catch(e) {}
            }

		}
	});
	h.forminit();
	//加载草稿或者编辑项目
	if(id != 0) {
	    //如果是编辑或追加项目，则获取相应的表单数据
		if(additems) {
			$('#save_{rand}').remove();
			$('#submitBtn_{rand}').removeAttr('click');
			$('#submitBtn_{rand}').val('保存');
			$('#submitBtn_{rand}').attr('onclick', 'additemsfun(' + id + ')');
		}
		//加载动画
		var bgs = '<div id="mainloaddiv" style="width:' + viewwidth + 'px;height:' + viewheight + 'px;overflow:hidden;background:#000000;color:white;filter:Alpha(opacity=20);opacity:0.2;z-index:3;position:absolute;left:0px;line-height:' + viewheight + 'px;top:0px;" align="center"><img src="images/mloading.gif"  align="absmiddle">&nbsp;加载中...</div>';
		$('#indexcontent').append(bgs);
		//h.load(js.getajaxurl('loadcomment','project_comment','main',{id:id}));
		js.ajax(js.getajaxurl('loadcomment', 'project_comment', 'main'), {
			id: id
		}, function(ds) {
			var data = ds.data;
			$("#pici_name_{rand}").val(data.pici_name);
			$("#pici_start_time_{rand}").val(data.pici_start_time);
			$("#pici_end_time_{rand}").val(data.pici_end_time);
			$("#pici_norm_id_{rand}").val(data.pici_norm_id);
			$("#expert_ids_{rand}").val(data.expert_ids);
			expertIds = data.expert_ids;
			$("#project_ids_{rand}").val(data.project_ids);
			projectIds = data.project_ids;
			$("#mtype_{rand}").val(data.mtype);
			if (data.mtype=='project_start'){
			    $('#review_type').val('立项评审');
            }else if (data.mtype=='project_end'){
                $('#review_type').val('结项评审');
            }
			$("#chooseNormName_{rand}").val(data.norm_name);
			//添加项目按钮解锁
			$("#addprojectbtn_{rand}").removeAttr('disabled');
			setexperttable(data.expert_arr);
			setprojecttable(data.project_arr);

			//追加项目
			if(additems) {
				$('#pici_name_{rand}').attr('readonly', 'true');
				$('#change_pici_start_time_{rand}').attr('disabled', 'disabled');
				$('#change_pici_end_time_{rand}').attr('disabled', 'disabled');
				$('#addnorm_{rand}').attr('disabled', 'disabled');
				$('#addexpert_{rand}').attr('disabled', 'disabled');
				$('#addotherexpert_{rand}').attr('disabled', 'disabled');
				//原有的项目ids
				additems_project_ids = data.project_ids;
				//专家表中的操作栏删除
				$.each($('table[name="expert_table_{rand}"] tr'), function(k, el) {
					$(el).children('td:last').remove();
				});
				//项目表中的原有项目的操作a标签删除
				$.each($('table[name="project_table_{rand}"] tbody tr'), function(k, el) {
					$(el).children('td:last').children('a').remove();
				});
			}

		}, 'post,json,false');
		$("#id_{rand}").val(id);
	}

	//指标列表
	var normList = $('#normListView_{rand}').bootstable({
		url: js.getajaxurl('normlist', 'project_comment', 'main', {}),
		fanye: true,
		modename: '指标列表',
		celleditor: true,
		storeafteraction: 'normlistafter',
		isshownumber: false, //是否显示序号
		columns: [{
			text: '选择',
			dataIndex: 'id',
			renderer: function(v, d) {
                    return '<input name="chooseNorm" type="radio" value="' + v + '" />';
			}
		}, {
			text: '指标名称',
			dataIndex: 'dafen_model_name'
		}, {
			text: '指标类型',
			dataIndex: 'mtype',
			renderer: function(v, d) {
				if(v == 'project_start') return '立项评审';
				else return '结项评审';
			}
		}],
		itemclick: function(da, index, e) { //单选
			$("input[name='chooseNorm'][value='" + da.id + "']").prop("checked", true);
			if (da.mtype=="project_end"){
			    $('#review_type').val('结项评审');
            }else{
                $('#review_type').val('立项评审');
            }
		}, //单击行触发
	});
	//console.log(params);
	//项目列表
	var proejctChooseIds = '';
	var projectList = $('#projectListView_{rand}').bootstable({
		url: js.getajaxurl('getprojectlist', 'project_comment', 'main',{type:'projectstart'}),
		celleditor: false,
		fanye: true,
		storeafteraction: 'project_applyafter',
		modedir: 'project_apply:main',
		isshownumber: false, //是否显示序号
		columns: [{
				text: '<label><input type="checkbox" id="selall" onclick="selall(this)"/>全选</label>',
				dataIndex: 'id',
				renderer: function(v, d) {
					//使用临时变量 temp_projectIds
					var temp_projectIdsArr = temp_projectIds.split(',');
					if(temp_projectIdsArr.length == 0 || temp_projectIdsArr == "") {
						return '<input type="checkbox" name="selproject_{rand}" value="' + v + '" />';
					} else if(temp_projectIdsArr.length > 0 || temp_projectIdsArr != "") {
						//判断 temp_projectIds 中是否含有 v
						if($.inArray(v, temp_projectIdsArr) == -1) {
							return '<input type="checkbox" name="selproject_{rand}" value="' + v + '" />';
						} else {
							return '<input type="checkbox" name="selproject_{rand}" value="' + v + '"  checked/>';
						}

					}

				}
			},
			{
				text: '项目编号',
				dataIndex: 'project_number'
			}, {
				text: '项目名称',
				dataIndex: 'project_name'
			}, {
				text: '项目类别',
				dataIndex: 'project_select'
			}, {
				text: '学科',
				dataIndex: 'subject_classification'
			}, {
				text: '关键词分类',
				dataIndex: 'keyword_classification'
			}, {
				text: '具体关键词',
				dataIndex: 'specific_keywords',
				sortable: true
			},{
                text: '申报时间',
                dataIndex: 'apply_time',
                sortable: true
            }, {
				text: '操作',
				dataIndex: 'mid',
				renderer: function(v, d) {
					return "<a onclick='openproject(\"" + d.project_name + "\",\"" + d.mtype + "\"," + v + ")'>查看</a>";
				}
			}
		],
		load: function() {
			$('#mainloaddiv').remove();
		},
		//		itemclick: function(da, index, e) {//单选
		//			var obj = $("input[name='selproject_{rand}'][value='" + da.id + "']");
		//			if(obj.is(':checked')){
		//				obj.removeAttr('checked');
		//			}else{
		//				obj.prop('checked',true);
		//			}
		//		}, //单击行触发

	});

	//专家列表
    var expertList = $('#expertListTable_{rand}').bootstable({
        url: js.getajaxurl('expertdata', 'project_comment', 'main', {}),
        celleditor: false,
        fanye: true,
        isshownumber: false, //是否显示序号
        columns: [
            {
                text: '<label><input type="checkbox" id="selall_expert" onclick="selall_expert(this)" value="0"/>全选</label>',
                dataIndex: 'mid',
                renderer: function(v, d) {
                    //使用临时变量 temp_projectIds
                    var temp_expertIdsArr = temp_expertIds.split(',');
                    if(temp_expertIdsArr.length == 0 || temp_expertIdsArr == "") {
                        return '<input type="checkbox" name="selexpert_{rand}" value="' + v + '" />';
                    } else if(temp_expertIdsArr.length > 0 || temp_expertIdsArr != "") {
                        //判断 temp_expertIdsArr 中是否含有 v
                        if($.inArray(v, temp_expertIdsArr) == -1) {
                            return '<input type="checkbox" name="selexpert_{rand}" value="' + v + '" />';
                        } else {
                            return '<input type="checkbox" name="selexpert_{rand}" value="' + v + '"  checked/>';
                        }
                    }
                }
            },
            {
                text: '专家姓名',
                dataIndex: 'name'
            }, {
                text: '职务/职称',
                dataIndex: 'position'
            }, {
                text: '毕业学科',
                dataIndex: 'graduate_project'
            }, {
                text: '研究方向',
                dataIndex: 'research_direction'
            }, {
                text: '关联单位',
                dataIndex: 'company'
            }
        ],
        load: function() {
            $('#mainloaddiv').remove();
        },
    });

	/**
	 * 获取查询条件
	 */
	//关键词分类
    $.ajax({
        url: './?a=keyword_classification&m=project_comment&d=main&ajaxbool=true',
        type: 'post',
        dataType: 'json',
        success: function (res) {
            if (res.code == '1') {
                $.each(res.rows.rows, function(k, v) {
                    $("#keywords_{rand}").append("<option value='" + v.name + "'>" + v.name + "</option>");
                });
            } else {}
        }
    });
    //学科分类
    $.ajax({
        url: './?a=subject_classification&m=project_comment&d=main&ajaxbool=true',
        type: 'post',
        dataType: 'json',
        success: function (res) {
            if (res.code == '1') {
                $.each(res.rows, function(k, v) {
                    $("#subject_{rand}").append("<option value='" + v.name + "'>" + v.name + "</option>");
                    $("#expert_subject_{rand}").append("<option value='" + v.name + "'>" + v.name + "</option>");
                });
            } else {
            }
        }
    });


	c = {
		getdist: function(o1) { //添加指标
			$("#normModal").modal("show");
            $("input[name='chooseNorm'][value='" + norm_ids + "']").prop("checked", true);
		},
        //添加参与专家
		/*addguser: function() {
			//console.log(expertIds);
			var cans = {
				type: 'usercheck',
				title: '选择人员',
				callback: function(sna, sid) {
					c.savedist(sid);
				}
			};
			js.getuser(cans);
			return false;
		},*/
        //添加校外专家
		addotherexpert: function() {
			$("#addOtherExpertModal").modal("show");
		},

		savedist: function(sid) {
			sid = uniqueIds(expertIds.toString(), sid.toString());
			if(expertIds == '') {
				expertIds = sid.toString();
			} else {
				if(sid != '') expertIds += ',' + sid;
			}
			$("#expert_ids_{rand}").val(expertIds);
			js.ajax(js.getajaxurl('getexpertlist', 'admin', 'system'), {
				expert_ids: sid
			}, function(ds) {
				if(ds.length != 0) {
					setexperttable(ds);
				}
			}, 'post,json');
		},
        //时间选择
		clickdt: function(o1, lx) {
			$(o1).rockdatepicker({
				initshow: true,
				view: 'hour',
				inputid: 'pici_' + lx + '_{rand}'
			});
		},
        //项目时间选择
		clickprojectdt: function(o1, lx) {
			$(o1).rockdatepicker({
				initshow: true,
				view: 'date',
				inputid: 'projectdt' + lx + '_{rand}'
			});
		},
        //确认添加指标
		clicknormsure: function() {
			if(normList.changeid != 0) {
				$("#mtype_{rand}").val(normList.changedata.mtype);
				$("#chooseNormName_{rand}").val(normList.changedata.dafen_model_name);
				$("#pici_norm_id_{rand}").val(normList.changeid);
				//切换指标后重置
                expertIds = ''; //选中的专家
                temp_expertIds = ''; //临时选中的专家变量
                projectIds = ''; //选中的项目
                temp_projectIds = ''; //临时选中的项目变量
                additems_project_ids = ''; //追加项目前原有的ids
                additems_expert_ids =''; //追加专家前原有的ids
                norm_ids = normList.changeid;
                $('#expertListView_{rand}').empty();
                $('#projectList_{rand}').empty();
                $("#normModal").modal("hide");
			} else {
				layer.msg('请选择指标');
			}
		},
        //指标列表查询
		normlistsearch: function() {
			normList.setparams({
				norm_name: get('normName_{rand}').value,
			}, true);
		},
        //预览指标
		normperview: function() {
			var norm_id = get('pici_norm_id_{rand}').value;
			if(norm_id != "") {
				js.ajax(js.getajaxurl('getnormdetail', 'project_comment', 'main'), {
					norm_id: norm_id
				}, function(ds) {
					sessionStorage.setItem("normpreview", ds.data);
					var url = getRootPath() + '/webmain/main/project_comment/rock_project_comment_norm_look.php?type=preview';
					js.open(url, 900, 600);
				}, 'post,json');
			} else {
				layer.msg('请先添加指标');
			}
		},
        //添加项目列表刷新(回显)
		addproject: function() {
			//临时变量 = 提交变量 projectIds 用于判断复选框的选中状态
            if ($('#review_type').val()!=''){
                temp_projectIds = projectIds;
                projectList.setparams({
                    additems_project_ids: additems_project_ids,
                    ps_status:$('#review_type').val(),
                }, true);
                projectList.reload();
                $("#projectModal").modal("show");
            }else{
                layer.msg('请先选择指标');
            }
		},
        //添加专家刷新(回显)
        addguser: function() {
            //临时变量 = 提交变量 expertIds 用于判断复选框的选中状态
            temp_expertIds = expertIds;
            expertList.setparams({
                additems_expert_ids: additems_expert_ids
            }, true);
            expertList.reload();
            $("#expertModal").modal("show");
        },
        //项目查询
		projectlistsearch: function() {
			projectList.setparams({
                keywords: get('keywords_{rand}').value, //关键词分类
                subject: get('subject_{rand}').value,//学科分类
                project_type: get('project_type_{rand}').value,//申报类型
                keywords_detail: get('keywords_detail_{rand}').value,//具体关键词
                project_num: get('project_num_{rand}').value,//项目编号
			}, true);
		},
        //专家查询
        expertlistsearch:function(){
            expertList.setparams({
                expert_subject:get('expert_subject_{rand}').value,
                expert_position:get('expert_position_{rand}').value,
                research_direction:get('research_direction_{rand}').value,
                company:get('company_{rand}').value,
            },true);
        },
        //确认添加项目
		clickprojectsure: function() {
			if($("#selall").is(':checked')){
			    if (additems_project_ids!=''){
                    projectIds = additems_project_ids+','+temp_projectIds;
                }else{
			        projectIds = temp_projectIds;
                }
			}else{
				//提交变量 = 临时变量  用于存储
				if(temp_projectIds == '') {
					projectIds = additems_project_ids; //如果没有选中则，直接等于追加项目前的ids
				} else {
					projectIds = temp_projectIds;
				}
			}
			$("#project_ids_{rand}").val(projectIds);
			$("#projectList_{rand}").empty();
			if(projectIds == ''){
				$("#projectModal").modal("hide");
				return;
			}
			js.ajax(js.getajaxurl('getprojectlist', 'project_comment', 'main'), {
				project_ids: projectIds.trimStr(),
			}, function(ds) {
				if(ds.rows.length != 0) {
				    setprojecttable(ds.rows);
				}
			}, 'post,json');

			$("#projectModal").modal("hide");
		},
        //确认添加专家
        clickexpertsure: function() {

            if($("#selall_expert").is(':checked')){
                if (additems_expert_ids!=''){
                    expertIds = additems_expert_ids+','+temp_expertIds;
                }else {
                    expertIds = temp_expertIds;
                }
            }else{
                //提交变量 = 临时变量  用于存储
                if(temp_expertIds == '') {
                    expertIds = additems_expert_ids; //如果没有选中则，直接等于追加项目前的ids
                } else {
                    expertIds = temp_expertIds;
                }
            }
            $("#expert_ids_{rand}").val(expertIds);
            $("#expertListView_{rand}").empty();
            if(expertIds == ''){
                $("#expertModal").modal("hide");
                return;
            }
            js.ajax(js.getajaxurl('expertdata', 'project_comment', 'main'), {
                expert_ids: expertIds.trimStr(),
            }, function(ds) {
                // console.log(ds);
                if(ds.rows.length != 0) {
                    setexperttable(ds.rows);
                }
            }, 'post,json');

            $("#expertModal").modal("hide");
        },
        //提交网评
		submitform: function() {
		    var expert_ids = $("#expert_ids_{rand}").val();
            var expert_arr = new Array();
            expert_arr = expert_ids.split(',');
            if (expert_arr.length==3){
                $("#is_submit_{rand}").val(1);
                $("#save_{rand}").trigger('click');
            }else{
                layer.msg('选择的专家人数为3人！');
            }
		},
        //添加校外专家保存数据
		/*saveotherexpert: function() {
			var account = get('account_{rand}').value;
			var user_name = get('username_{rand}').value;
			var pinyin = $('#username_{rand}').toPinyin().toLowerCase().replace(/\s/ig, '');
			var password = get('password_{rand}').value;
			var sex = get('sex_{rand}').value;
			var mobile = get('mobile_{rand}').value;
			var ranking = get('ranking_{rand}').value;

			var data = {
				'deptname': '校外专家',
				'deptid': 46,
				'account': account,
				'username': user_name,
				'pingyin': pinyin,
				'pass': password,
				'sex': sex,
				'mobile': mobile,
				'ranking': ranking
			};

			js.ajax(js.getajaxurl('addotherexpert', 'admin', 'system'), data, function(ds) {
				if(ds.success) {
					if(ds.rows.length != 0 && ds.id != '') {
						if(expertIds == '') {
							expertIds = ds.id;
						} else {
							expertIds += ',' + ds.id;
						}
						$("#expert_ids_{rand}").val(expertIds);
						setexperttable(ds.rows);
						$("#addOtherExpertModal").modal("hide");
					}
					//reset form
					$('#account_{rand}').val('');
					$('#username_{rand}').val('');
					$('#password_{rand}').val('');
					$('#sex_{rand}').val('');
					$('#mobile_{rand}').val('');
					$('#ranking_{rand}').val('');

				} else {
					layer.msg(ds.msg);
				}
			}, 'post,json');
		}*/
	};
	js.initbtn(c);

	//项目单选事件
	$('#projectListView_{rand}').on("change", 'input[name="selproject_{rand}"]', function() {
		if($(this).is(':checked')) {
			if(temp_projectIds == '') {
				temp_projectIds = $(this).val();
			} else {
				if(temp_projectIds != '') temp_projectIds += ',' + $(this).val();
			}
		} else {
			var temp_projectIdsArr = temp_projectIds.split(',');
			input_val = $(this).val();
			if(temp_projectIdsArr != '') {
				$.each(temp_projectIdsArr, function(j, val) {
					if(val == input_val) {
						temp_projectIdsArr.splice($.inArray(val, temp_projectIdsArr), 1);
					}
				});
			}
			temp_projectIds = temp_projectIdsArr.join(",");
		}
	});
	//专家单选事件
    $('#expertListTable_{rand}').on("change", 'input[name="selexpert_{rand}"]', function() {
        if($(this).is(':checked')) {
            if(temp_expertIds == '') {
                temp_expertIds = $(this).val();
            } else {
                if(temp_expertIds != '') temp_expertIds += ',' + $(this).val();
            }
        } else {
            var temp_expertIdsArr = temp_expertIds.split(',');
            input_val = $(this).val();
            if(temp_expertIdsArr != '') {
                $.each(temp_expertIdsArr, function(j, val) {
                    if(val == input_val) {
                        temp_expertIdsArr.splice($.inArray(val, temp_expertIdsArr), 1);
                    }
                });
            }
            temp_expertIds = temp_expertIdsArr.join(",");
        }
    });

});
//去除重复数据
function unique(arr) {
	//遍历arr，把元素分别放入tmp数组(不存在才放)
	var tmp = new Array();
	for(var i in arr) {
		//该元素在tmp内部不存在才允许追加
		if(tmp.indexOf(arr[i]) == -1) {
			tmp.push(arr[i]);
		}
	}
	return tmp;
}
//把现有的ids和新增的ids分解成数组,判断现有id数组中和新增id数组中是否重复,重复的id值去除
function uniqueIds(exist_ids, add_ids) {
	var add_arr = add_ids.split(',');
	var remove_arr = new Array();
	$.each(add_arr, function(k, add_id) {
		//if(exist_ids.indexOf(add_id.toString()) > -1) {
		//	remove_arr.push(add_id);
		//}
		$.each(exist_ids.split(','), function(kk, exist_id) {
			if(exist_id == add_id) {
				remove_arr.push(add_id);
			}
		});
	});
	$.each(remove_arr, function(k, del_id) {
		add_arr.remove(del_id);
	});
	var after = add_arr.join(',');
	//console.log('删了之后ids---'+after);
	return after;
}
//重写tabs改变事件
function thechangetabs(num) {
	$("div[temp='content']").hide();
	$("[temp='tabs']").removeClass();
	var bo = false;
	if(get('content_' + num + '')) {
		$('#content_' + num + '').show();
		$('#tabs_' + num + '').addClass('accive');
		nowtabs = tabsarr[num];
	}
	opentabs.push(num);
	_changhhhsv(num);
}
//全选按钮
function selall(el) {
	var cboxs = $("input[name='selproject_{rand}']");
	//判断全选按钮是否被选中
	if($(el).is(':checked')) {
		var tmparr = new Array();
		$.each(cboxs, function(k, v) {
			$(v).prop("checked", "checked");
			tmparr.push($(v).val());
		});
		temp_projectIds = tmparr.join(',');
	} else {
		$.each(cboxs, function(k, v) {
			$(v).removeAttr("checked");
			temp_projectIds = '';
		});
		if(additems_project_ids != ''){
			temp_projectIds = additems_project_ids;
		}
	}
}
//专家列表全选按钮
function selall_expert(el) {
        var cboxs = $("input[name='selexpert_{rand}']");
        //判断全选按钮是否被选中
        if($(el).is(':checked')) {
            var tmparr = new Array();
            $.each(cboxs, function(k, v) {
                $(v).prop("checked", "checked");
                    tmparr.push($(v).val());
            });
            temp_expertIds = tmparr.join(',');
        } else {
            $.each(cboxs, function(k, v) {
                $(v).removeAttr("checked");
                temp_expertIds = '';
            });
            if(additems_expert_ids != ''){
                temp_expertIds = additems_expert_ids;
            }
        }
    }
//添加专家表数据
function setexperttable(ds) {
	var lastid = $("#expertListView_{rand}").children('tr:last').children('td:first').html();
	if(lastid == undefined) lastid = 0;
	$.each(ds, function(k, v) {
		$("#expertListView_{rand}").append("<tr><td>" + (parseInt(lastid) + k + 1) + "</b></td><td>" + v.name + "</td><td>" + v.position + "</td><td>" + v.graduate_project + "</td><td>" + v.koufaCount + "</td><td><a onclick='delexpert(this," + v.mid + ")'>删除</a></td></tr>");
	});
}
//添加项目表数据
function setprojecttable(ds) {
        var lastid = $("#projectList_{rand}").children('tr:last').children('td:first').html();
        if(lastid == undefined) lastid = 0;
        $.each(ds, function(k, v) {
            $("#projectList_{rand}").append("<tr data_id=" + v.id + "><td>" + (parseInt(lastid) + k + 1) + "</td><td>" + v.project_number + "</td><td>" + v.project_name + "</td><td>" + v.subject_classification + "</td><td>" + v.keyword_classification + "</td><td>" + v.specific_keywords + "</td><td>" + v.apply_time + "</td><td><a onclick='delproject(this," + v.id + ")'>删除</a></td></tr>");
        });
    }
//专家列表删除数据
//删除专辑列表中的某个专家
function delexpert(ol, id) {
	var expertIdsArr = expertIds.split(',');
	expertIdsArr.remove(id); //删除id这个元素
	expertIds = expertIdsArr.join(',');
	$("#expert_ids_{rand}").val(expertIds);
	$(ol).parent().parent().remove();

	//序号重新排列
	$.each($("#expertListView_{rand}").children('tr'), function(k, el) {
		$(el).children('td:first').html(k + 1);
	});
}
//项目列表删除数据
//删除项目列表中的某个项目
function delproject(ol, id) {
	var projectIdsArr = projectIds.split(',');
	projectIdsArr.remove(id); //删除id这个元素
	projectIds = projectIdsArr.join(',');
	temp_projectIds = projectIds;
	$("#project_ids_{rand}").val(projectIds);
	$(ol).parent().parent().remove();
 /*   //编辑时删除上次编辑已选中的项目，该项目变为未网评
    $.ajax({
        url:getRootPath()+ "/?d=main&m=project_comment&a=changeWpStatus&ajaxbool=true",
        type:'post',
        data:{'bill_id':id},
        dataType:'json',
        success:function (res) {
            console.log(res);
        }
    });*/
	//序号重新排列
	$.each($("#projectList_{rand}").children('tr'), function(k, el) {
		$(el).children('td:first').html(k + 1);
	});
}
//追加项目
function additemsfun(id) {
	js.ajax(js.getajaxurl('additems', 'project_comment', 'main'), {
		id: id,
		project_ids: projectIds
	}, function(ds) {
		if(ds.success) {
			layer.msg(ds.msg);
			if(get('tabs_comment_list')) closetabs('comment_list');
			addtabs({
				num: 'comment_list',
				url: 'main,project_comment,list',
				icons: 'icon-bookmark-empty',
				name: '网评项目'
			});
			thechangetabs('comment_list');
			try {
				assessmentList.reload();
			} catch(e) {}
		} else {
			layer.msg(ds.msg);
		}
	}, 'post,json');
}
//查看项目
function openproject(project_name, modenum, mid) {
	//openxiangs(project_name, modenum, mid);
	var url = getRootPath() + '/task.php?a=p&num=' + modenum + '&mid=' + mid + '&pinshen=word';
	js.open(url, 900, 600);
}
//当前时间
Date.prototype.Format = function (fmt) { //author: meizz
    var o = {
        "M+": this.getMonth() + 1, //月份
        "d+": this.getDate(), //日
        "h+": this.getHours(), //小时
        "m+": this.getMinutes(), //分
        "s+": this.getSeconds(), //秒
        "q+": Math.floor((this.getMonth() + 3) / 3), //季度
        "S": this.getMilliseconds() //毫秒
    };
    if (/(y+)/.test(fmt)) fmt = fmt.replace(RegExp.$1, (this.getFullYear() + "").substr(4 - RegExp.$1.length));
    for (var k in o)
    if (new RegExp("(" + k + ")").test(fmt)) fmt = fmt.replace(RegExp.$1, (RegExp.$1.length == 1) ? (o[k]) : (("00" + o[k]).substr(("" + o[k]).length)));
    return fmt;
}
//时间选择插件
function daterp_start() {

	if($('#pici_start_time_{rand}').val()!=''){
			datetime=$('#pici_start_time_{rand}').val();
		}else{
			datetime=new Date().Format("yyyy-MM-dd hh:mm:ss");
			minDate=new Date().Format("yyyy-MM-dd");
	}

	if($('#pici_end_time_{rand}').val()!=''){
			maxDate=$('#pici_end_time_{rand}').val();
			maxDate=maxDate.split(" ");
			maxDate=maxDate[0];
		}else{
			maxDate='';
	}

	$('#pici_start_time_{rand}').daterangepicker({
		startDate:datetime,
		showDropdowns: true,
		singleDatePicker: true,
		showWeekNumbers: false, //是否显示第几周
		timePicker: true, //是否显示小时和分钟
		timePickerIncrement: 60, //时间的增量，单位为分钟
		timePicker24Hour: true,
		minDate: minDate,
		maxDate:maxDate,
		locale: {
			format: 'YYYY-MM-DD HH:mm:ss',
			separator: ' 到  ',
			applyLabel: '确定',
			cancelLabel: '取消',
			fromLabel: '起始时间',
			toLabel: '结束时间',
			customRangeLabel: '自定义',
			daysOfWeek: ['日', '一', '二', '三', '四', '五', '六'],
			monthNames: ['一月', '二月', '三月', '四月', '五月', '六月',
				'七月', '八月', '九月', '十月', '十一月', '十二月'
			],
			firstDay: 1
		},
	});
	$('#pici_start_time_{rand}').data('daterangepicker').show();
}
//时间选择插件
function daterp_end() {

	if($('#pici_end_time_{rand}').val()!=''){
			datetime=$('#pici_end_time_{rand}').val();
		}else{
			datetime=new Date().Format("yyyy-MM-dd hh:mm:ss");
			minDate=new Date().Format("yyyy-MM-dd");
	}

	if($('#pici_start_time_{rand}').val()!=''){
			minDate=$('#pici_start_time_{rand}').val();
			minDate=minDate.split(" ");
			minDate=minDate[0];
		}else{
	}
    //选择批次时间
	$('#pici_end_time_{rand}').daterangepicker({
		startDate:datetime,
		showDropdowns: true,
		singleDatePicker: true,
		showWeekNumbers: false, //是否显示第几周
		timePicker: true, //是否显示小时和分钟
		timePickerIncrement: 60, //时间的增量，单位为分钟
		timePicker24Hour: true,
		minDate:minDate,

		locale: {
			format: 'YYYY-MM-DD HH:mm:ss',
			separator: ' 到  ',
			applyLabel: '确定',
			cancelLabel: '取消',
			fromLabel: '起始时间',
			toLabel: '结束时间',
			customRangeLabel: '自定义',
			daysOfWeek: ['日', '一', '二', '三', '四', '五', '六'],
			monthNames: ['一月', '二月', '三月', '四月', '五月', '六月',
				'七月', '八月', '九月', '十月', '十一月', '十二月'
			],
			firstDay: 1
		},
	});
	$('#pici_end_time_{rand}').data('daterangepicker').show();
}

</script>
<form name="form_{rand}" style="width: 100%;">
	<input type="hidden" name="id" id="id_{rand}" value='0'/>
	<input type="hidden" name="is_submit" id="is_submit_{rand}" value='0' />
	<div class="grid-form1" style="
	border: 0px !important;
	margin-bottom: 1em;
	padding: 1em;
	border-radius: 4px;
	/*-webkit-border-radius: 4px;*/
	-o-border-radius: 4px;
	-moz-border-radius: 4px;
	-ms-border-radius: 4px;
	/*-webkit-box-shadow: 0 1px 1px rgba(0,0,0,.05);*/
	/*box-shadow: 0 1px 1px rgba(0,0,0,.05);*/
	position:relative;
	padding-top: 20px;
	">
		<div class="row">
            <!--网评批次名称-->
			<div class='col-md-12'>
				<label class="col-md-2 col-sm-3" style="padding-left: 15px;padding-top: 15px; margin-bottom: 0;text-align: right;">网评批次名称</label>
				<label class="col-xs-12 col-sm-9 col-md-10" style="padding-top: 7px; margin-bottom: 0;">
				<input name="pici_name" id="pici_name_{rand}" class="form-control" />
				</label>
			</div>
            <!--网评时间-->
			<div class='col-md-12'>
				<label  class="col-md-2 col-sm-3" style="padding-left: 15px;padding-top: 15px; margin-bottom: 0;text-align: right;">网评时间</label>
				<label  class="col-xs-12 col-sm-9 col-md-10" style="padding-top: 7px; margin-bottom: -5px;">

				<div style="display: inline-block;">
					<div  class="input-group txtPanelDate">
						<input placeholder="开始" readonly class="form-control" name="pici_start_time" id="pici_start_time_{rand}"  style="background: #FFF;">
						<span class="input-group-btn">
						<button class="btn btn-default" id="change_pici_start_time_{rand}" onclick="daterp_start()" type="button">
						<i class="icon-calendar"></i>
						</button> </span>
					</div>
				</div>
				<div style="display: inline-block;">
					<div  class="input-group txtPanelDate">
						<input placeholder="结束" readonly class="form-control" name="pici_end_time" id="pici_end_time_{rand}"  style="background: #FFF;">
						<span class="input-group-btn">
						<button class="btn btn-default" id="change_pici_end_time_{rand}" onclick="daterp_end()" type="button">
						<i class="icon-calendar"></i>
						</button> </span>
					</div>
				</div>

				<!--<div class="input-group" style="width:100%">
					<input type="text" id="config-demo" class="form-control" readonly="true">
					<span class="input-group-btn">
					<button class="btn btn-default" onclick="daterp()" type="button">
					选择时间
					</button> </span>
				</div><input type="text" id="config-demo" class="form-control" readonly="true">--> </label>
			</div>
            <!--选择指标-->
			<div class='col-md-12'>
				<label class="col-md-2 col-sm-3" style="padding-left: 15px;padding-top: 15px; margin-bottom: 0;text-align: right;">选择指标</label>
				<label class="col-xs-12 col-sm-9 col-md-10" style="padding-top: 7px; margin-bottom: 0;">
				<div class="input-group" style="width:100%">
					<input readonly class="form-control" id="chooseNormName_{rand}" name="norm_name" />
					<input type="hidden" id="pici_norm_id_{rand}" name="pici_norm_id" value="">
					<input type="hidden" class="form-control" id="mtype_{rand}" name="mtype" />
					<span class="input-group-btn">
					<button class="btn btn-default" id="addnorm_{rand}" click="getdist" type="button">
					<i class="icon-search"></i>添加
					</button>
					<button class="btn btn-default" click="normperview" type="button">
					预览指标
					</button> </span>
				</div> </label>

			</div>
            <!--评审分类-->
            <div class='col-md-12'>
                <label class="col-md-2 col-sm-3" style="padding-left: 15px;padding-top: 15px; margin-bottom: 0;text-align: right;">评审分类</label>
                <label class="col-xs-12 col-sm-9 col-md-10" style="padding-top: 7px; margin-bottom: 0;">
                    <input name="review_type" id="review_type" class="form-control" readonly/>
                </label>
            </div>
            <!--添加专家-->
			<div class='col-md-12'>
				<label  class="col-md-2 col-sm-3" style="padding-left: 15px;padding-top: 15px; margin-bottom: 0;text-align: right;">添加专家</label>
				<label  class="col-xs-12 col-sm-9 col-md-10" style="padding-top: 7px; margin-bottom: 0;">
				<a id="addexpert_{rand}" class="btn btn-default" click="addguser,0">
					添加专家
				</a>
				<!--<a id="addotherexpert_{rand}" click="addotherexpert" class="btn btn-default">
					添加校外专家
				</a>--> </label>
			</div>
            <!--选择的专家列表-->
			<div class='col-md-12'>
				<input type="hidden" name="expert_ids" id="expert_ids_{rand}" />
				<label  class="col-md-2 col-sm-3" style="padding-left: 15px;padding-top: 15px; margin-bottom: 0;"></label>
				<label  class="col-xs-12 col-sm-9 col-md-10" style="padding-top: 7px; margin-bottom: 0;">
				<table name="expert_table_{rand}" class="table table-bordered table-striped table-hover page__table" style="background: rgba(255, 255, 255, 0.72);">
					<thead style="background: #EFEFEF;">
						<tr>
							<td>序号</td>
							<td>名称</td>
							<td>职务/职称</td>
							<td>毕业学科</td>
							<td>扣罚次数</td>
							<td>操作</td>
						</tr>
					</thead>
					<tbody id="expertListView_{rand}">
						<!--<tr>
						<td>1</b></td>
						<td>李伟锋</td>
						<td>计算机学院</td>
						<td>企业教师</td>
						<td>删除</td>
						</tr>-->
					</tbody>
				</table> </label>
			</div>
            <!--选择的评审项目-->
			<div class='col-md-12'>
				<label  class="col-md-2 col-sm-3" style="padding-left: 15px;padding-top: 15px; margin-bottom: 0;text-align: right;">评审项目</label>
				<label  class="col-xs-12 col-sm-9 col-md-10" style="padding-top: 7px; margin-bottom: 0;">
				<a click="addproject" id="addprojectbtn_{rand}" class="btn btn-default">
					添加项目
				</a> <!--<strong>*请先选择指标再添加网评项目</strong>--> </label>
			</div>
            <!--评审项目列表-->
			<div class='col-md-12'>
				<input type="hidden" name="project_ids" id="project_ids_{rand}" />
				<label  class="col-md-2 col-sm-3" style="padding-left: 15px;padding-top: 15px; margin-bottom: 0;"></label>
				<label  class="col-xs-12 col-sm-9 col-md-10" style="padding-top: 7px; margin-bottom: 20px;">
				<table name="project_table_{rand}" class="table table-bordered table-striped table-hover page__table" style="background: rgba(255, 255, 255, 0.72);">
					<thead style="background: #EFEFEF;">
						<tr>
							<td>序号</td>
							<td>编号</td>
							<td>项目名称</td>
							<td>学科</td>
							<td>关键词分类</td>
							<td>具体关键词</td>
							<td>申报时间</td>
							<td>操作</td>
						</tr>
					</thead>
					<tbody id="projectList_{rand}">
						<!--<tr>
						<td>1</td>
						<td>K6500000</td>
						<td>校园wifi覆盖部署</td>
						<td>基础建设</td>
						<td>计算机学院</td>
						<td>张三</td>
						<td>2017-6-5</td>
						<td>删除</td>
						</tr>-->
					</tbody>
				</table> </label>
			</div>
		</div>
	</div>

	<div class="xmk-flowing-bar">
		<input class="xmk-btn xmk-btn-grey" id="save_{rand}" type="button" value="保存为草稿"  style="visibility: hidden"/>
		<input class="xmk-btn" id="submitBtn_{rand}" type="button" value="提交" click="submitform" oninput="OnInput(event)">
		<!--<input class="xmk-btn" type="reset" value="取消" />-->
	</div>

</form>

<!--选择指标的模态框-->
<!-- Button trigger modal -->
<div class="modal fade" id="normModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title" id="myModalLabel">选择指标</h4>
			</div>
			<div class="modal-body">
				<section class="serachPanel selBackGround" style="overflow: hidden;width: 560px;min-width: 560px;">
					<div class="searchAPanel" style="overflow: hidden;">
						<div class="searchAc1">
							<ul>
								<li>
									<span class="reviewContent stateContent">指标名称</span>
								</li>
								<li>
									<input type="text" id="normName_{rand}"class="form-control txtPanel">
								</li>
							</ul>
						</div>
						<div class="searchAc1">
							<ul>
								<li>
									<input class="btn_ marH1" type="button" click="normlistsearch" value="查询" />
								</li>
								<li>
									<button click="clicknormsure" class="btn_ marH1">
									确认
									</button>
								</li>
							</ul>
						</div>

					</div>
				</section>
				<div class="blank10"></div>
				<div id="normListView_{rand}" class="list-hand"></div>
			</div>
		</div>
	</div>
</div>

<!--选择项目的模态框-->
<!-- Button trigger modal -->
<div class="modal fade" id="projectModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title" id="myModalLabel">选择项目</h4>
			</div>
			<div class="modal-body">
                <form>
				    <section class="serachPanel selBackGround">
					<div class="searchAPanel">
                        <!--关键词分类-->
                        <div class="searchAc1">
                            <ul>
                                <li>
                                    <span class="reviewContent stateContent">关键词分类：</span>
                                </li>
                                <li>
                                    <select id="keywords_{rand}" name="keywords_select"
                                            class="selSearch selAuditState">
                                        <option value="">请选择</option>
                                    </select>
                                </li>
                            </ul>
                        </div>
                        <!--学科分类-->
                        <div class="searchAc1">
                            <ul>
                                <li>
                                    <span class="reviewContent stateContent">学科分类：</span>
                                </li>
                                <li>
                                    <select id="subject_{rand}" name="subject_select"
                                            class="selSearch selAuditState">
                                        <option value="">请选择</option>
                                    </select>
                                </li>
                            </ul>
                        </div>
                        <!--申报类型-->
                        <div class="searchAc1">
                            <ul>
                                <li>
                                    <span class="reviewContent stateContent">申报类型：</span>
                                </li>
                                <li>
                                    <select id="project_type_{rand}" name="project_type_select"
                                            class="selSearch selAuditState">
                                        <option value="">请选择</option>
                                        <option value="project_coursetask">课题申报</option>
                                    </select>
                                </li>
                            </ul>
                        </div>
                        <!--关键词详情-->
						<div class="searchAc1">
							<ul>
								<li >
									<span class="reviewContent stateContent">具体关键词：</span>
								</li>
								<li>
									<input type="text" id="keywords_detail_{rand}" name="keywords_detail" class="form-control txtPanel">
								</li>
							</ul>
						</div>
                        <!--项目编号-->
                        <div class="searchAc1">
                            <ul>
                                <li >
                                    <span class="reviewContent stateContent">项目编号：</span>
                                </li>
                                <li>
                                    <input type="text" id="project_num_{rand}" name="project_num" class="form-control txtPanel">
                                </li>
                            </ul>
                        </div>
                        <!--确认,查询-->
						<div class="searchAc1">
							<ul>
								<li>
									<input class="btn_ marH1" type="button" click="projectlistsearch" value="查询" />
								</li>
								<li>
									<button click="clickprojectsure" class="btn_ marH1">
									确认
									</button>
								</li>
                                <li>
                                    <button type="reset" class="btn_ marH1">
                                        重置
                                    </button>
                                </li>
							</ul>
						</div>
					</div>
				</section>
                </form>
				<div class="blank10"></div>
				<div id="projectListView_{rand}" class="list-hand"></div>
			</div>
		</div>
	</div>
</div>

<!--选择专家的模态框-->
<div class="modal fade" id="expertModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">选择专家</h4>
            </div>
            <div class="modal-body">
                <form>
                 <section class="serachPanel selBackGround">
                    <div class="searchAPanel">
                        <!--学科分类-->
                        <div class="searchAc1">
                            <ul>
                                <li>
                                    <span class="reviewContent stateContent">学科分类：</span>
                                </li>
                                <li>
                                    <select id="expert_subject_{rand}" name="expert_subject_select"
                                            class="selSearch selAuditState">
                                        <option value="">请选择</option>
                                    </select>
                                </li>
                            </ul>
                        </div>
                        <!--职务/职称-->
                        <div class="searchAc1">
                            <ul>
                                <li>
									<span class="reviewContent stateContent">职务/职称：</span>
                                </li>
                                <li>
                                    <select id="expert_position_{rand}" name="expert_position" class="selSearch selAuditState">
                                        <option value="">请选择</option>
                                    </select>
                                </li>
                            </ul>
                        </div>
                        <!--研究方向-->
                        <div class="searchAc1">
                            <ul>
                                <li>
                                    <span class="reviewContent stateContent">研究方向：</span>
                                </li>
                                <li>
                                    <input type="text" id="research_direction_{rand}" name="research_direction" class="form-control txtPanel" placeholder="请输入">
                                </li>
                            </ul>
                        </div>
                        <!--关联单位-->
                        <div class="searchAc1">
                            <ul>
                                <li>
									<span class="reviewContent stateContent" >关联单位：</span>
                                </li>
                                <li>
                                    <select id="company_{rand}" name="company" class="selSearch selAuditState">
                                        <option value="">请选择</option>
                                    </select>
                                </li>
                            </ul>
                        </div>
                        <!--查询，确认-->
                        <div class="searchAc1" >
                            <ul>
                                <li>
                                    <input class="btn_ marH1" type="button" click="expertlistsearch" value="查询" />
                                </li>
                                <li>
                                    <button click="clickexpertsure" class="btn_ marH1">
                                        确认
                                    </button>
                                </li>
                                <li>
                                    <button type="reset" class="btn_ marH1">
                                        重置
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </div>
                </section>
                </form>
                <div class="blank10"></div>
                <div id="expertListTable_{rand}" class="list-hand"></div>
            </div>
        </div>
    </div>
</div>

<!--添加校外专家-->
<!-- Button trigger modal -->
<div class="modal fade" id="addOtherExpertModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title" id="myModalLabel">添加校外专家</h4>
			</div>
			<div class="modal-body">
				<div style="margin: auto;">
					<form name="adminform_{rand}">
						<table cellspacing="0" border="0" width="100%" align="center" cellpadding="0">
							<tr>
								<td align="right"><font color="red">*</font> 用户名：</td>
								<td class="tdinput">
									<input id="account_{rand}" name="user" placeholder="工号" onblur="js.replacecn(this)" maxlength="20" class="form-control">
								</td>
								<td align="right" width="15%"><font color="red">*</font> 姓名：</td>
								<td class="tdinput" width="35%">
									<input id="username_{rand}" name="name" maxlength="10" class="form-control">
								</td>
							</tr>

							<tr>
								<td align="right"><font color="red">*</font> 密码：</td>
								<td class="tdinput">
									<input id="password_{rand}" name="pass" value="123456" maxlength="20" class="form-control">
								</td>
								<td align="right">性别：</td>
								<td class="tdinput">
									<select id="sex_{rand}" name="sex" class="form-control">
										<option value="男">男</option><option value="女">女</option>
									</select>
                                </td>
							</tr>

							<tr>
								<td align="right">手机号：</td>
								<td class="tdinput">
									<input id="mobile_{rand}" name="mobile"  maxlength="11"  onblur="js.replacecn(this)" class="form-control">
								</td>
								<td align="right"><font color="red">*</font> 职称\职位：</td>
								<td class="tdinput">
									<input id="ranking_{rand}" name="ranking" maxlength="20" class="form-control">
                                </td>
							</tr>

							<!--单位信息-->
							<!--<input type="hidden" name="deptname" value="校外专家"/>-->
							<!--<input type="hidden" name="deptid" value="46"/>-->
							<!--名称的拼音-->
							<!--<input type="hidden" id="pinyin_{rand}" name="pingyin" />-->

							<tr>
								<td colspan="4" align="center">
									<button class="btn btn-success" click="saveotherexpert" type="button">
									<i class="icon-save"></i>&nbsp;保存
									</button></td>
							</tr>

						</table>
					</form>
				</div>

			</div>
		</div>
	</div>
</div>
