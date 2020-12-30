var isedit = 1;
var s = 0;

function othercheck() {}

function initbody() {

	$('body').click(function() {
		$('.menullss').hide();
	});
	$('body').keydown(c.onkeydown);
	$('#showmenu').click(function() {
		$('.menullss').toggle();
		return false;
	});
	$('.menullss li').click(function() {
		c.mencc(this);
	});
	c.initinput();
	try{
		if(form('fileid')) {
			if(typeof(FormData) == 'function') {
				f.fileobj = $.rockupload({
					autoup: false,
					fileview: 'filedivview',
					allsuccess: function() {
						check(1);
					}
				});
			} else {
				$('#filedivview').parent().html('<font color="#888888">当前浏览器不支持上传</font>');
			}
		}
	}catch(e){
		//TODO handle the exception
		//console.log('这里报错，继续执行下去！');
		//console.log(e);
	}


	//默认跳转
	switch (btntype){
		case 'gd':
			//归档
			$(document).scrollTop($('.stitle').eq(0).offset().top);
			$('#showrecord2').css('display', 'block');
			break;
		case 'shxx':
			//审核信息
			$(document).scrollTop($('.stitle').eq(0).offset().top);
			$('#showrecord0').css('display', 'block');
			break;
		case 'ztxx':
			//状态信息
			$(document).scrollTop($('.stitle').eq(1).offset().top);
			$('#showrecord1').css('display', 'block');
			break;
		default:
			//$(document).scrollTop($(document).height());
			break;
	}
}

function showchayue(opt, st) {
	alert('总查阅:' + st + '次\n最后查阅：' + opt + '');
}

function geturlact(act) {
	var url = js.getajaxurl(act, 'mode_' + modenum + '|input', 'flow');
	return url;
}

var f = {
	change: function(o1) {
		f.fileobj.change(o1);
	}
};

//提交处理
function check(lx) {

	var da = {
		'sm': form('check_explain').value,
		'tuiid': '0',
		'fileid': '',
		'mid': mid,
		'modenum': modenum,
		'zt': _getaolvw('check_status')
	};

	if(form('fileid')) da.fileid = form('fileid').value;
	if(form('check_tuiid')) da.tuiid = form('check_tuiid').value;
	if(da.zt == '') {
		js.setmsg('请选择处理动作');
		layer.closeAll();
		return;
	}
	if(da.zt == '2' && isempt(da.sm)) {
		js.setmsg('此动作必须填写说明');
		layer.closeAll();
		return;
	}
	if(form('zhuanbanname')) {
		da.zyname = form('zhuanbanname').value;
		da.zynameid = form('zhuanbannameid').value;
	}
	if(form('nextnameid') && da.zt == '1' && da.zt != '3') {
		da.nextname = form('nextname').value;
		da.nextnameid = form('nextnameid').value;
		if(da.nextnameid == '') {
			js.setmsg('请选择下一步处理人');
			layer.closeAll();
			return;
		}
	}
	if(!da.zynameid && da.zt != '2' && da.zt != '3') {

		var fobj = $('span[fieidscheck]'),
			i, fid, fiad;

		for(i = 0; i < fobj.length; i++) {
			fiad = $(fobj[i]);
			fid = fiad.attr('fieidscheck');
			if(fid == 'project_z_zhuanjia') {
				var project_z_zhuanjia = [];
				da['project_z_zhuanjia'] = 0;
				$('#project_z_zhuanjia').find('tr').each(function(n) {
					if(n != 0) {

						da['project_z_zhuanjia_' + n + '_0'] = $(this).find('td:eq(1) input').val();
						da['project_z_zhuanjia_' + n + '_1'] = $(this).find('td:eq(2) input').val();
						da['project_z_zhuanjia_' + n + '_2'] = $(this).find('td:eq(3) input').val();
						da['project_z_zhuanjia_' + n + '_3'] = $(this).find('td:eq(4) input').val();
						da['project_z_zhuanjia_' + n + '_4'] = $(this).find('td:eq(5) input').val();
						da['project_z_zhuanjia'] = da['project_z_zhuanjia'] + 1;

					}

				});

			} else if(fid == 'project_x_zhuanjia') {
				var project_x_zhuanjia = [];
				da['project_x_zhuanjia'] = 0;
				$('#project_x_zhuanjia').find('tr').each(function(n) {
					if(n != 0) {

						da['project_x_zhuanjia_' + n + '_0'] = $(this).find('td:eq(1) input').val();
						da['project_x_zhuanjia_' + n + '_1'] = $(this).find('td:eq(2) input').val();
						da['project_x_zhuanjia_' + n + '_2'] = $(this).find('td:eq(3) input').val();
						da['project_x_zhuanjia_' + n + '_3'] = $(this).find('td:eq(4) input').val();
						da['project_x_zhuanjia_' + n + '_4'] = $(this).find('td:eq(5) input').val();
						da['project_x_zhuanjia'] = da['project_x_zhuanjia'] + 1;

					}

				});

			} else {
				da['cfields_' + fid] = form(fid).value;
				if(da['cfields_' + fid] == '') {
					js.setmsg('' + fiad.text() + '不能为空');
					layer.closeAll();
					return;
				}
			}

		}
	}
	var ostr = othercheck(da);
	if(typeof(ostr) == 'string' && ostr != '') {
		js.setmsg(ostr);
		return;
	}
	if(typeof(ostr) == 'object')
		for(var csa in ostr) da[csa] = ostr[csa];

	//审核时上传文件的操作在这里
	if(s == 0) {
		if(lx == 0 && f.fileobj && f.fileobj.start()) return s++; //有上传相关文件
	}

	layer.confirm('是否确认处理？', {
		btn: ['确定', '取消'], //按钮
		shade: 0,
		skin: 'layui-layer-molv',
		closeBtn: 0
	}, function() {

		layer.closeAll();
		js.setmsg('处理中...');
		var o1 = get('check_btn');
		o1.disabled = true;
		var url = c.gurl('check');
		js.ajax(url, da, function(a) {
			if(a.success) {
				js.setmsg(a.msg, 'green');
				c.callback();
				try{
					parent.window.assessmentList.reload();
				}catch(e){
					console.log('刷新页面不成功');
				}
				//关闭页面
				parent.window.closenowtabs();
//				if(get('autocheckbox')){
//					//原关闭事件
//					if(get('autocheckbox').checked){
//
//						closetabs('chuli_project_apply_{rand}')
//
//						//c.close();
//
//					}
//
//				}
			} else {
				js.setmsg(a.msg);
				o1.disabled = false;
			}
		}, 'post,json', function(estr) {
			js.setmsg('处理失败:' + estr + '');
			o1.disabled = false;
		});

	}, function() {
		layer.closeAll();
		layer.msg('已取消');
	});

}

function _getaolvw(na) {
	var v = '',
		i, o = $("input[name='" + na + "']");
	for(i = 0; i < o.length; i++)
		if(o[i].checked) v = o[i].value;
	return v;
}

/**
 *	nae记录名称
 *	zt状态名称
 *	ztid 状态id
 *	ztcol 状态颜色
 *	ocan 其他参数
 *	las 说明字段Id默认other_explain
 */
function _submitother(nae, zt, ztid, ztcol, ocan, las) {
	if(!las) las = 'other_explain';
	if(!nae || !get(las)) {
		js.setmsg('sorry;不允许操作', '', 'msgview_spage');
		return;
	}
	var sm = $('#' + las + '').val();
	if(!ztcol) ztcol = '';
	if(!zt) zt = '';
	if(!ocan) ocan = {};
	if(!ztid) {
		js.setmsg('没有选择状态', '', 'msgview_spage');
		return;
	}
	if(!sm) {
		js.setmsg('没有输入备注/说明', '', 'msgview_spage');
		return;
	}
	var da = js.apply({
		'name': nae,
		'mid': mid,
		'modenum': modenum,
		'ztcolor': ztcol,
		'zt': zt,
		'ztid': ztid,
		'sm': sm
	}, ocan);
	js.setmsg('处理中...', '', 'msgview_spage');
	js.ajax(c.gurl('addlog'), da, function(s) {
		js.setmsg('处理成功', 'green', 'msgview_spage');
		$('#spage_btn').hide();
	}, 'post', function(s) {
		js.setmsg(s, '', 'msgview_spage');
	});
	return false;
}
var c = {
	callback: function(cs) {
		var calb = js.request('callback');
		if(!calb) return;
		try {
			parent[calb](cs);
		} catch(e) {}
		try {
			opener[calb](cs);
		} catch(e) {}
		try {
			parent.js.tanclose('openinput');
		} catch(e) {}
	},
	gurl: function(a) {
		var url = js.getajaxurl(a, 'flowopt', 'flow');
		return url;
	},
	showtx: function(msg) {
		js.setmsg(msg);
		if(ismobile == 1) js.msg('msg', msg);
	},
	close: function() {
		window.close();
		try {
			parent.js.tanclose('winiframe');
		} catch(e) {}
	},
	other: function(nae, las) {
		_submitother(nae, '', '1', '', las);
	},
	others: function(nae, zt, ztid, ztcol, ocan, las) {
		_submitother(nae, zt, ztid, ztcol, ocan, las);
	},
	mencc: function(o1) {
		var lx = $(o1).attr('lx');
		//		if(lx=='2')c.delss();
		//		if(lx=='3')c.close();
		//		if(lx=='4')location.reload();
		//		if(lx=='0')c.clickprint(false);
		//		if(lx=='6')c.clickprint(true);
		//		if(lx=='5')c.daochuword();
		//		if(lx=='1'){
		//			var url='index.php?a=lu&m=input&d=flow&num='+modenum+'&mid='+mid+'';
		//			js.location(url);
		//		}
		if(lx == 'shcl') {
			//this.foucsshcl();
			$(document).scrollTop($('#checktablediv').offset().top);
		} //审核处理
		if(lx == 'dy') c.clickprint(true); //打印
		if(lx == 'shxx') {
			$(document).scrollTop($('.stitle').eq(0).offset().top);
			$('#showrecord0').css('display', 'block');
		} //审核信息
		if(lx == 'gd') {
			$(document).scrollTop($('.stitle').eq(0).offset().top);
			$('#showrecord2').css('display', 'block');
		} //归档
		if(lx == 'ztxx') {
			$(document).scrollTop($('.stitle').eq(1).offset().top);
			$('#showrecord1').css('display', 'block');
		} //状态信息
		if(lx == 'dcw') c.daochuword(); //导出word文档
        if(lx == 'dcp') c.daochupdf(); //导出pdf文件
	},
	clickprint: function(bo) {
		c.hideoth();
		if(bo) {
			$('#recordss').remove();
			$('#checktablediv').remove();
			$('#recordsss').remove();
		}
		window.print();
	},
	daochuword: function() {
		//var url = 'task.php?a=p&num=' + modenum + '&mid=' + mid + '&stype=word';
		//var url ='api.php?a=word&m=openproject&num=' + modenum + '&mid=' + mid ;
		var url ='api.php?a=word&m=word&num=' + modenum + '&mid=' + mid ;
		js.location(url);
	},
    daochupdf: function() {
        layer.confirm('导出pdf文件时间漫长，是否继续？', {
            btn: ['确认', '取消'] //按钮
        }, function(){
            layer.msg('请耐心等待！');
            var url ='api.php?a=pdf&m=word&num=' + modenum + '&mid=' + mid ;
            // var index = layer.load(0, {shade: false});
            js.location(url);
            //layer.close(index);
        }, function(){

        });
    },
	hideoth: function() {
		$('.menulls').hide();
		$('.menullss').hide();
		$('a[temp]').remove();
	},
	delss: function() {
		js.confirm('删除将不能恢复，确定要<font color=red>删除</font>吗？', function(lx) {
			if(lx == 'yes') c.delsss();
		});
	},
	delsss: function() {
		var da = {
			'mid': mid,
			'modenum': modenum,
			'sm': ''
		};
		js.ajax(c.gurl('delflow'), da, function(a) {
			js.msg('success', '单据已删除,3秒后自动关闭页面,<a href="javascript:;" onclick="c.close()">[关闭]</a>');
			c.callback();
			setTimeout('c.close()', 3000);
		}, 'post');
	},
	onkeydown: function(e) {
		var code = event.keyCode;
		if(code == 27) {
			c.close();
			return false;
		}
		if(event.altKey) {
			if(code == 67) {
				c.close();
				return false;
			}
		}
	},
	changeshow: function(lx) {
		$('#showrecord' + lx + '').toggle();
	},
	showviews: function(o1) {
		$.imgview({
			'url': o1.src,
			'ismobile': ismobile == 1
		});
	},
	//初始上传框
	initinput: function() {
		var o, o1, sna, i, tsye, tdata, uptp, far;
		var o = $('div[id^="filed_"]');
		if(isedit == 1) o.show();
		for(i = 0; i < o.length; i++) {
			o1 = o[i];
			sna = $(o1).attr('tnam');
			tsye = $(o1).attr('tsye');
			tdata = $(o1).attr('tdata');
			if(isedit == 1) {
				uptp = 'image';
				if(tsye == 'file') {
					uptp = '*';
					if(!isempt(tdata)) uptp = tdata;
				}
				$.rockupload({
					'inputfile': '' + o1.id + '_inp',
					'initremove': false,
					'uptype': uptp,
					'oparams': {
						sname: sna,
						snape: tsye
					},
					'onsuccess': function(f, gstr) {
						var sna = f.sname,
							tsye = f.snape,
							d = js.decode(gstr);
						if(tsye == 'img') {
							get('imgview_' + sna + '').src = d.filepath;
							form(sna).value = d.filepath;
						} else if(tsye == 'file') {
							$('#fileview_' + sna + '').html(c.showfilestr(d));
							form(sna).value = d.id;
						}
					}
				});
			}
		}
	},
	showfilestr: function(d) {
		var flx = js.filelxext(d.fileext);
		var s = '<img src="web/images/fileicons/' + flx + '.gif" align="absmiddle" height=16 width=16> <a href="javascript:;" onclick="js.downshow(' + d.id + ')">' + d.filename + '</a> (' + d.filesizecn + ')';
		return s;
	},
	//撤回操作
	chehui: function() {
		js.prompt('确定撤回吗？', '要撤回上一步处理结果说明(选填)', function(jg, txt) {
			if(jg == 'yes') c.chehuito(txt);
		});
	},
	chehuito: function(sm) {
		js.msg('wait', '撤回中...');
		js.ajax(c.gurl('chehui'), {
			'mid': mid,
			'modenum': modenum,
			'sm': sm
		}, function(a) {
			if(a.success) {
				js.msg('success', '撤回成功');
				location.reload();
			} else {
				js.msg('msg', a.msg);
			}
		}, 'post,json', function(s) {
			js.msg('msg', '操作失败');
		});
	},

	//预览文件
	downshow: function(id, ext, pts) {
		var url = 'index.php?m=public&a=fileviewer&id=' + id + '&wintype=max';
		if(pts != '' && js.isimg(ext)) {
			$.imgview({
				'url': pts,
				'ismobile': ismobile == 1
			});
			return false;
		}
		if(ismobile == 1) {
			if(appobj1('openfile', id)) return;
			js.location(url);
		} else {
			js.winiframe('文件预览', url);
		}
		return false;
	   },
	changecheck_status: function(o1) {
		var zt = _getaolvw('check_status');
		//		if(zt=='2'){//不通过
		//			$('#tuihuidiv').show();//显示退回表单
		//			$('#degreediv').hide();//隐藏紧急程度表单
		//			$('#nexthandlediv').hide();//隐藏下一步处理人表单
		//			$('#relatedfile').hide();//隐藏相关文件表单
		//		}else{
		//			$('#tuihuidiv').hide();//隐藏退回表单
		//			$('#degreediv').show();//显示紧急程度表单
		//			$('#nexthandlediv').show();//显示下一步处理人表单
		//			$('#relatedfile').show();//显示相关文件表单
		//		}
		//		if(zt=='1'){//通过
		//			$('#zhuangdiv').show();//显示转办
		//		}else{
		//			$('#zhuangdiv').hide();//隐藏转办
		//		}
		//		if(zt=='3'){//拒绝
		//			$('#tuihuidiv').hide();//隐藏退回表单
		//			$('#degreediv').hide();//隐藏紧急程度表单
		//			$('#nexthandlediv').hide();//隐藏下一步处理人表单
		//			$('#relatedfile').hide();//隐藏相关文件表单
		//		}else{
		//			$('#tuihuidiv').show();//显示退回表单
		//			$('#degreediv').show();//显示紧急程度表单
		//			$('#nexthandlediv').show();//显示下一步处理人表单
		//			$('#relatedfile').show();//显示相关文件表单
		//		}
		switch(zt) {
			case '1': //通过
				$('#tuihuidiv').hide(); //隐藏退回表单
				$('#zhuangdiv').show(); //显示转办
				$('#degreediv').show(); //显示紧急程度表单
				$('#nexthandlediv').show(); //显示下一步处理人表单
				$('#relatedfile').show(); //显示相关文件表单
				break;
			case '2': //不通过
				$('#tuihuidiv').show(); //显示退回表单
				$('#zhuangdiv').hide(); //隐藏转办
				$('#degreediv').hide(); //隐藏紧急程度表单
				$('#nexthandlediv').hide(); //隐藏下一步处理人表单
				$('#relatedfile').hide(); //隐藏相关文件表单
				break;
			case '3': //拒绝
				$('#tuihuidiv').hide(); //隐藏退回表单
				$('#zhuangdiv').hide(); //隐藏转办
				$('#degreediv').hide(); //隐藏紧急程度表单
				$('#nexthandlediv').hide(); //隐藏下一步处理人表单
				$('#relatedfile').hide(); //隐藏相关文件表单
				break;
			default: //其它情况
				$('#tuihuidiv').hide(); //隐藏退回表单
				$('#zhuangdiv').hide(); //隐藏转办
				$('#degreediv').hide(); //隐藏紧急程度表单
				$('#nexthandlediv').hide(); //隐藏下一步处理人表单
				$('#relatedfile').hide(); //隐藏相关文件表单
				break;
		}
	},
	//单个文件下载
	downloadone: function(file_id) {
		location.href = js.getajaxurl('downloadone', 'archives', 'main', {
			ajaxbool: true,
			file_id: file_id
		});
	},
	//归档保存
	gdsave: function() {
		//获取归档表单中的年月日和归档人和归档备注
		var gdate = $("#gyear").val() + '-' + $("#gmonth").val() + '-' + $("#gday").val();
		var gpeople = $("#gpeople").val();
		var gremark = $("#gremark").val();
		if(gdate == '') js.msg('msg', '归档日期不能为空');
		if(gpeople == '') js.msg('msg', '归档人不能为空');
		if(gremark == '') js.msg('msg', '归档说明不能为空');
		js.msg('wait', '归档中...');
		js.ajax(js.getajaxurl('filesave', 'project_archive', 'main'), {
			'mid': mid,
			'mtype': modenum,
			'gdate': gdate,
			'gpeople': gpeople,
			'gremark': gremark
		}, function(a) {
			//console.log(a);
			if(a.success) {
				js.msg('msg', '归档成功,稍后跳转回项目库列表界面');
				setTimeout(function(){
					parent.window.project_archive.reload();
					parent.window.closenowtabs();
				},1000);
			} else {
				js.msg('msg', a.msg);
			}
		}, 'post,json', function(s) {
			js.msg('msg', '归档失败');
		});
	}
};
