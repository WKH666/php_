var ismobile = 0,
	firstrs = {},
	alldata = {};

function initbodys() {};

function savesuccess() {};

function eventaddsubrows() {}

function eventdelsubrows() {}

function loadingDataAfter(str) {}

function geturlact(act, cns) {
	var url = js.getajaxurl(act, 'mode_' + moders.num + '|input', 'flow', cns);
	return url;
}


function initbody() {

	$('body').keydown(function(et) {
		var code = event.keyCode;
		if(code == 27) {
			c.close();
			return false;
		}
		if(event.altKey) {
			if(code == 83) {
				get('AltS').click();
				return false;
			}
		}
	});
	var len = arr.length,
		i, fid, nfid = '',
		flx;
	for(i = 0; i < len; i++) {
		fid = arr[i].fields;
		flx = arr[i].fieldstype;
		if(arr[i].islu == '1' && arr[i].iszb == '0') {
			if(flx == 'checkboxall') fid += '[]';
			if(fid.indexOf('temp_') != 0 && !form(fid)) {
				nfid += '\n(' + fid + '.' + arr[i].name + ')';
			}
			if(flx == 'htmlediter') c.htmlediter(arr[i].fields);
		}
	}
	c.initsubtable();
	if(nfid == '') {
		c.showdata();
	} else {
		alert('录入页面缺少必要的字段：' + nfid + '');
	}

	if(ismobile == 1) f.fileobj = $.rockupload({
		autoup: false,
		fileview: 'filedivview',
		allsuccess: function() {
			c.saveken();
		}
	});

}

function changesubmit(d) {};

function changesubmitbefore() {};

var f = {
	change: function(o1) {
		f.fileobj.change(o1);
	}
};

js.apiurl = function(m, a, cans) {
	var url = '' + apiurl + 'api.php?m=' + m + '&a=' + a + '&adminid=' + adminid + '';
	var cfrom = 'mweb';
	url += '&device=' + device + '';
	url += '&cfrom=' + cfrom + '';
	url += '&token=' + token + '';
	if(!cans) cans = {};
	for(var i in cans) url += '&' + i + '=' + cans[i] + '';
	return url;
}

var c = {
	callback: function(cs) {
		var calb = js.request('callback');
		if(!calb) {
			try {
				if(ismobile == 0) {
					parent.bootstableobj[moders.num].reload();
					parent.js.msg('success', '处理成功');
					parent.js.tanclose('winiframe');
				}
			} catch(e) {}
			return;
		}
		try { parent[calb](cs); } catch(e) {}
		try { opener[calb](cs); } catch(e) {}
		try { parent.js.tanclose('winiframe'); } catch(e) {}
	},
	save: function() {
		var d = c.savesss();
		if(!d) return;
		else {
			str_d='是否确认提交';
			if($("input[name='isturn']").val()==0){
				str_d='是否保存为草稿';
			}
			layer.confirm(str_d, {
				btn: ['确定', '取消'], //按钮
				shade: 0,
				skin: 'layui-layer-molv',
				closeBtn:0
			}, function() {
				if(ismobile == 1) {
					js.msg('wait', '保存中...');
					get('AltS').disabled = true;
					get('DraftS').disabled = true;
					f.fileobj.start();
					//console.info($("input[name='isturn']"));
					$("input[name='isturn']").remove();
					layer.closeAll();
					return true;
				} else {
					c.saveken();
					layer.closeAll();
					return true;
				}
			}, function() {
				$("input[name='isturn']").remove();
				console.info($("input[name='isturn']"));
				layer.msg('已取消');
			});
		}

	},
	saveken: function() {
		//console.log('saveken');
		var d = this.savesss();
		if(!d) return;
		get('AltS').disabled = true;
		try{
			get('DraftS').disabled = true;
		}catch(e){}
		this.saveok(d);
	},
	showtx: function(msg) {
		js.setmsg(msg);
		if(ismobile == 1) js.msg('msg', msg);
	},
	selectdatadata: {},
	selectdata: function(s1, ced, fid, tit) {
		if(isedit == 0) return;
		if(!tit) tit = '请选择...';
		var a1 = s1.split(',');
		$.selectdata({
			data: this.selectdatadata[fid],
			title: tit,
			url: geturlact('getselectdata', { act: a1[0] }),
			checked: ced,
			nameobj: form(fid),
			idobj: form(a1[1]),
			onloaddata: function(a) {
				c.selectdatadata[fid] = a;
			}
		});
	},
	savesss: function() {
		//console.log('savesss');
		layer.closeAll();
		var d = this.getsubdata(0);
		//console.info(arr);
		if(js.ajaxbool || isedit == 0) return false;
		var len = arr.length,
			i, val, fid, flx, nas;
		changesubmitbefore();
		var d = js.getformdata();
		//修改文件上传（申报书提交后文件与flow_bill表产生关联）start 2020-11-26
		var fileId = '';
		if(!d.hasOwnProperty('fileid')){
			var data = JSON.parse(sessionStorage.getItem('uploadFileData'));
			for (let keys in data){
				if (data[keys]){
					fileId += data[keys]['id']+',';
				}
			}
		}
		if(fileId){
			fileId = fileId.slice(0,fileId.length-1);
			d['fileid'] = fileId;
			sessionStorage.removeItem('99fileTypeNum');
			sessionStorage.removeItem('99fileType');
			sessionStorage.removeItem('uploadFileData');
		}
		//修改文件上传（申报书提交后文件与flow_bill表产生关联）end
		for(i = 0; i < len; i++) {
			if(arr[i].iszb != '0') continue;
			fid = arr[i].fields;
			flx = arr[i].fieldstype;
			nas = arr[i].name;
			if(ismobile == 0 && arr[i].islu == '1' && flx == 'htmlediter') {
				d[fid] = this.editorobj[fid].html();
			}
			val = d[fid];
			if(arr[i].isbt == '1') {
				if(flx == 'uploadfile' && val == '0') {
					layer.closeAll();
					this.showtx('请选择' + nas + '');
					return false;
				}
				if(isempt(val)) {
					layer.closeAll();
					if(form(fid)){
						try{
							form(fid).focus();
						}catch(err){
							$("input[name='"+fid+"']").focus();
						}
					}
					this.showtx('' + nas + '不能为空');
					return false;
				}
			}
			if(val && flx == 'email') {
				if(!js.email(val)) {
					layer.closeAll();
					this.showtx('' + nas + '格式不对');
					form(fid).focus();
					return false;
				}
			}
			if(val && fid == 'project_head_phone') {

				if(checkInit.init('phone', val) == false) {
					layer.closeAll();
					this.showtx('' + nas + '格式不对');
					form(fid).focus();
					return false;
				}
			}
			if(val && flx == 'htmlediter') {
				//每个富文本框的字数限制
				switch (fid){
					//非实训类的
					case 'project_details_one':
						if(fnGetCpmisWords(val)>800) {
							layer.closeAll();
							this.showtx('' + nas + '文字超过800字，请删减到800字以下');
							form(fid).focus();
							return false;
						}
						break;
					case 'project_details_two':
						if(fnGetCpmisWords(val)>1500) {
							layer.closeAll();
							this.showtx('' + nas + '文字超过1500字，请删减到1500字以下');
							form(fid).focus();
							return false;
						}
						break;
					case 'project_details_three':
						if(fnGetCpmisWords(val)>1000) {
							layer.closeAll();
							this.showtx('' + nas + '文字超过1000字，请删减到1000字以下');
							form(fid).focus();
							return false;
						}
						break;
					default:
						break;
				}
			}
		}
		if(firstrs.isbt == 1) {
			if(!d.sysnextoptid && form('sysnextopt')) {
				layer.closeAll();
				this.showtx('请指定[' + firstrs.name + ']处理人');
				form('sysnextopt').focus();
				return false;
			}
		}
		var s = changesubmit(d);
		if(typeof(s) == 'string' && s != '') {
			layer.closeAll();
			this.showtx(s);
			return false;
		}
		if(typeof(s) == 'object') d = js.apply(d, s);
		d.sysmodeid = moders.id;
		d.sysmodenum = moders.num;
		return d;
	},
	saveok: function(d) {
		//console.log('saveok');
		js.setmsg('保存中...');
		get('AltS').disabled = true;
		js.ajax(geturlact('save'), d, function(str) {
			var a = js.decode(str);
			c.backsave(a, str);
		}, 'post', function() {
			get('AltS').disabled = false;
			js.setmsg('error:内部错误,可F12调试');
		});
	},
	backsave: function(a, str) {
		//console.log('backsave');
		var msg = a.msg;
		if(a.success) {
			js.setmsg(msg, 'green');
			js.msg('success','操作成功');
			//js.msg('wait', '保存成功,页面正在跳转');
			this.formdisabled();
			$('#AltS').hide();
			$('#DraftS').hide();
			$('#ControlRow').hide();
			form('id').value = a.data;
			isedit = 0;
			this.callback(a.data);
			//发送提示信息
			try {
//				js.sendevent('reload', 'yingyong_mode_' + moders.num + '');
				js.backla();
			} catch(e) {}
			savesuccess();
			var reload=sessionStorage.getItem('wcl_reload');
			//console.log(reload); 存储一个变量在未处理和提出申报不同操作
			if (reload==1||reload==2){
				$('#winiframe_spancancel', window.parent.document).click();
				parent.assessmentList.reload();
			}else{
				//parent.window.closenowtabs();
				parent.window.addtabs({ num: 'projectmanage', url: 'main,sheke_fwork,selflist', icons: 'icon-bookmark-tasks', name: '未处理' });
			}
			sessionStorage.removeItem('wcl_reload');
			//根据htmlbacklx判断返回跳转的页面类型
			/*if(htmlbacklx=='projectmanage'){
				//保存成功则跳转会项目库列表页面
				parent.window.closetabs('projectmanage');
				parent.window.closetabs('edit_project');
				arr=$('.accive',parent.document).attr('id').split('tabs_');
				parent.window.addtabs({ num: 'projectmanage', url: 'main,project_manage,list', icons: 'icon-bookmark-tasks', name: '项目库列表' });
				parent.window.closetabs(arr[1]);
			}else{
				//保存成功则跳转会申报进程页面\
				/!*parent.window.closetabs('applyall');
				arr=$('.accive',parent.document).attr('id').split('tabs_');
				parent.window.closetabs('addapply');
				parent.window.addtabs({ num: 'applyall', url: 'main,project_apply,list,atype=my', icons: 'icon-bookmark-tasks', name: '申报进程' });
				parent.window.closetabs(arr[1]);*!/


				//关闭标签页
				/!*parent.window.closetabs('daiturn');
				arr=$('.accive',parent.document).attr('id').split('tabs_');
				parent.window.addtabs({ num: 'daiturn', url: 'main,fwork,bill,atype=my', icons: 'icon-bookmark-tasks', name: '申报进程' });
				parent.window.closetabs(arr[1]);*!/

			}*/
		} else {
			if(typeof(msg) == 'undefined') msg = str;
			get('AltS').disabled = false;
			get('DraftS').disabled = false;
			js.setmsg(msg);
			js.msg('msg', msg);
		}
	},
	showdata: function() {
		var smid = form('id').value;
		if(smid == '0' || smid == '') {
			isedit = 1;
			$('#AltS').show();
			c.initdatelx();
			c.initinput();
			initbodys(smid);
		} else {
			js.setmsg('加载数据中...');
			js.ajax(geturlact('getdata'), { mid: smid, flownum: moders.num }, function(str) {
				c.showdataback(js.decode(str));
				loadingDataAfter(js.decode(str));
			}, 'post', function() {
				js.setmsg('error:内部错误,可F12调试');
			});
		}
	},
	//初始上传框
	initinput: function() {
		var o, o1, sna, i, tsye, uptp, tdata, farr = alldata.filearr,
			far;
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
					'oparams': { sname: sna, snape: tsye },
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
			var val = form(sna).value;
			if(tsye == 'img') {
				if(val) get('imgview_' + sna + '').src = val;
			}
			if(tsye == 'file' && val && val > 0) {
				far = farr['f' + val];
				if(far) {
					$('#fileview_' + sna + '').html(c.showfilestr(far));
				} else {
					form(sna).value = '0';
				}
			}
		}
	},
	showfilestr: function(d) {
		var flx = js.filelxext(d.fileext);
		var s = '<img src="web/images/fileicons/' + flx + '.gif" align="absmiddle" height=16 width=16> <a href="javascript:;" onclick="js.downshow(' + d.id + ')">' + d.filename + '</a> (' + d.filesizecn + ')';
		return s;
	},
	showviews: function(o1) {
		$.imgview({ 'url': o1.src, 'ismobile': ismobile == 1 });
	},
	initdatelx: function() {

	},
	showdataback: function(a) {
		if(a.success) {
			var da = a.data;
			alldata = da;
			js.setmsg();
			var len = arr.length,
				i, fid, val, flx, ojb, j;
			data = da.data;
			for(i = 0; i < len; i++) {
				fid = arr[i].fields;
				flx = arr[i].fieldstype;
				if(arr[i].islu == '1' && arr[i].iszb == '0' && fid.indexOf('temp_') != 0) {
					val = da.data[fid];
					if(val == null) val = '';
					if(flx == 'checkboxall') {

						ojb = $("input[name='" + fid + "[]']");
						val = ',' + val + ',';
						for(j = 0; j < ojb.length; j++) {
							if(val.indexOf(',' + ojb[j].value + ',') > -1) ojb[j].checked = true;
						}
					} else if(flx == 'checkbox') {
						form(fid).checked = (val == '1');
					} else if(flx == 'htmlediter' && ismobile == 0) {
						this.editorobj[fid].html(val);
					} else if(flx.substr(0, 6) == 'change') {
						if(form(fid)) form(fid).value = val;
						fid = arr[i].data;
						if(!isempt(fid) && form(fid)) form(fid).value = da.data[fid];
					} else {
						if(form(fid)) form(fid).value = val;
					}
				}
			}
			isedit = da.isedit;
			if(form('base_name')) form('base_name').value = da.user.name;
			if(form('base_deptname')) form('base_deptname').value = da.user.deptname;
			js.downupshow(da.filers, 'fileidview', '', (isedit == 0));
			var subd = da.subdata,
				subds;
			//原来值3导致表格不能输出
			for(j = 0; j < 6; j++) {
				subds = subd['subdata' + j + ''];
				if(subds)
					for(i = 0; i < subds.length; i++) {
						subds[i].sid = subds[i].id;
						if(form('xuhao' + j + '_' + i + '')) {
							c.adddatarow(j, i, subds[i]);
						} else {
							c.insertrow(j, subds[i], true);
						}
					}
			}
			c.initinput();
			initbodys(form('id').value);

			if(isedit == 0) {
				this.formdisabled();
				js.setmsg('无权编辑');
			} else {
				$('#AltS').show();
				c.initdatelx();
			}
			if(da.isflow == 1) {
				$('.status').css({ 'color': da.statuscolor, 'border-color': da.statuscolor }).show().html(da.statustext);
			}
		} else {
			get('AltS').disabled = true;
			this.formdisabled();
			js.setmsg(a.msg);
			js.msg('msg', a.msg);
		}
	},
	date: function(o1, lx) {
		$(o1).rockdatepicker({ view: lx, initshow: true });
	},
	close: function() {
		window.close();

	},
	formdisabled: function() {
		$('form').find('*').attr('disabled', true);
		$('#fileupaddbtn').remove();
	},
	upload: function() {
		js.upload('', { showid: 'fileidview' });
	},
	changeuser: function(na, lx) {
		js.changeuser(na, lx);
	},
	changeclear: function(na) {
		js.changeclear(na);
	},
	editorobj: {},
	htmlediter: function(fid) {
		if(ismobile == 1) return;
		var cans = {
			resizeType: 0,
			allowPreviewEmoticons: false,
			allowImageUpload: true,
			formatUploadUrl: false,
			allowFileManager: true,
			uploadJson: '?m=upload&a=upimg&d=public',
			minWidth: '300px',
			height: '250',
			filterMode : true, //不会过滤HTML代码
			pasteType : 1,
			items: [
				'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
				'removeformat', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',
				'insertunorderedlist', '|' , 'table', '|', 'image', '|', 'source', 'clearhtml', 'fullscreen'
			]
		};
		this.editorobj[fid] = KindEditor.create("[name='" + fid + "']", cans);
	},
	subtablefields: [],
	initsubtable: function() {

		var i, oba, j, o, nas, nle, nasa, fname;
		for(i = 0; i <= 6; i++) {

			if(get('tablesub' + i + '')) {
				fname = [];
				o = $('#tablesub' + i + '');

				form('sub_totals' + i + '').value = o.find('tr').length - 1;
				this.repaixuhao(i);
				oba = o.find('tr:eq(1)').find('[name]');
				for(j = 0; j < oba.length; j++) {

					nas = oba[j].name;
					nasa = nas.split('_');
					nle = nasa.length;
					nna = nasa[0];
					if(nle > 2) nna += '_' + nasa[1] + '';
					if(nle > 3) nna += '_' + nasa[2] + '';

					fname.push(nna.substr(0, nna.length - 1));
				}

				this.subtablefields[i] = fname;
			}
		}
	},
	getsubdata: function(i) {

		var d = [];
		if(!get('tablesub' + i + '')) return d;
		var len = parseFloat(form('sub_totals' + i + '').value);
		var i1, ji, i2, far = this.subtablefields[i],
			lens = far.length,
			fna;
		for(i1 = 0; i1 < len; i1++) {

			var a = {};
			i2 = 0;
			for(j1 = 0; j1 < lens; j1++) {
				fna = '' + far[j1] + '' + i + '_' + i1 + '';

				if(form(fna)) {
					a[far[j1]] = form(fna).value;
					i2++;
				}
			}
			if(i2 > 0) d.push(a);
		}
		return d;
	},
	delrow: function(o, xu) {

		layer.confirm('确认删除吗？', {
			btn: ['确定', '取消'], //按钮
			shade: 0,
			skin: 'layui-layer-molv',
			closeBtn: 0
		}, function() {
			layer.closeAll();
			if(isedit == 0) {
				$(o).remove();
				return;
			}
			var o1 = $('#tablesub' + xu + '').find('tr');
			if(o1.length <= 6) {
				layer.msg('至少保留5行');
				return;
			}
			$(o).parent().parent().remove();
			this.repaixuhao(xu);
			eventdelsubrows(xu);

		}, function() {
			layer.msg('已取消');
			return false;
		});

	},
	repaixuhao: function(xu) {

		var o = $('#tablesub' + xu + '').find("input[temp='xuhao']");
		for(var i = 0; i < o.length; i++) {
			o[i].value = (i + 1);
		}
	},
	insertrow: function(xu, d, isad) {
		if(!get('tablesub' + xu + '')) {
			alert('error=201：表单设计有误');
			return;
		}
		console.log(this.subtablefields[xu])
		var o = $('#tablesub' + xu + '');
		var o1 = o.find('tr'),
			oi = o1.length - 1,
			i, str, oba, nas, oj, nna, ax2, d1;
		str = o.find('tr:eq(' + oi + ')').html();
		oba = o.find('tr:eq(' + oi + ')').find('[name]');
		oj = parseFloat(form('sub_totals' + xu + '').value);
		var narrs = [],
			fasr = this.subtablefields[xu],
			wux = '' + xu + '_' + oj + '';
		for(i = 0; i < oba.length; i++) {
			nas = oba[i].name;
			nna = fasr[i] + '' + wux + '';
			str = str.replace(nas, nna);
			str = str.replace(nas, nna);
			narrs.push(nna);
		}
		console.info(oi);
		form('sub_totals' + xu + '').value = (oj + 1);
		str = str.replace('rockdatepickerbool="true"', '');
		if((oi+1)>10){
			layer.msg('不能添加超过10行');
			return false;
		}else{
			o.append('<tr>' + str + '</tr>');
		}
		d = js.apply({ sid: '0' }, d);
		for(d1 in d) {
			ax2 = d1 + wux;
			if(form(ax2)) form(ax2).value = d[d1];
		}
		this.repaixuhao(xu);
		this.initdatelx();
		if(!isad) eventaddsubrows(xu);
	},
	adddatarow: function(xu, oj, d) {
		d = js.apply({ sid: '0' }, d);
		var fasr = this.subtablefields[xu],
			ans;

		//console.log(this.subtablefields[xu]);
		for(var i = 0; i < fasr.length; i++) {

			ans = fasr[i] + '' + xu + '_' + oj + '';

			if(form(ans) && d[fasr[i]]) form(ans).value = d[fasr[i]];
		}
	},
	setrowdata: function(xu, oj, d) {
		var ans;
		for(var i in d) {
			ans = i + '' + xu + '_' + oj + '';
			if(form(ans)) form(ans).value = d[i];
		}
	},
	addrow: function(o, xu) {
		if(isedit == 0) {
			$(o).remove();
			return;
		}
		this.insertrow(xu);
	},
	getsubtabledata: function() {

	},
	_getsubtabledatas: function(xu) {
		var oxut = form('sub_totals' + xu + '');
		if(!oxut) return false;
		var da = {},
			fasr, len = parseFloat(oxut.value),
			j, f, na;
		da['sub_totals' + xu + ''] = oxut.value;
		fasr = this.subtablefields[xu];
		for(j = 1; j <= len; j++) {
			for(f = 0; j < fasr.length; j++) {
				na = fasr[f] + '' + xu + '_' + j + '';
				if(form(na)) da[na] = form(na).value;
			}
		};
		return da;
	},
	getsubtotals: function(fid, xu) {
		var oi = 0;
		if(!xu) xu = '0';
		var oxut = form('sub_totals' + xu + '');
		if(!oxut) return oi;
		var len = parseFloat(oxut.value),
			j, na, val;
		for(j = 0; j < len; j++) {
			na = fid + '' + xu + '_' + j + '';
			if(form(na)) {
				val = form(na).value;
			}
		}
		return oi;
	},
	getselobj: function(fv) {
		var o = form(fv);
		if(!o) return;
		var o1 = o.options[o.selectedIndex];
		return o1;
	},
	getseltext: function(fv) {
		var o1 = this.getselobj(fv);
		if(!o1) return '';
		return o1.text;
	},
	getselattr: function(fv, art) {
		var o1 = this.getselobj(fv);
		if(!o1) return '';
		return $(o1).attr(art);
	}
};

//用word方式计算正文字数
function fnGetCpmisWords(str){
	sLen = 0;
	try{
		//先将回车换行符做特殊处理
		str = str.replace(/(\r\n+|\s+|　+)/g,"龘");
		//处理英文字符数字，连续字母、数字、英文符号视为一个单词
		str = str.replace(/[\x00-\xff]/g,"m");
		//合并字符m，连续字母、数字、英文符号视为一个单词
		str = str.replace(/m+/g,"*");
		//去掉回车换行符
		str = str.replace(/龘+/g,"");
		//返回字数
		sLen = str.length;
	}catch(e){

	}
	return sLen;
}
