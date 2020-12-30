var objcont,tabs_title,tabsarr={},nowtabs,opentabs=[],menutabs,menuarr;
var viewwidth,viewheight,optmenudatas=[];
function resetAllInputSel(lx){
	var a = $('.tabsindex #tabs_title .accive').attr('id');//#tabs_title td[class="active"]
	var b = 'content'+a.substring(4,a.length);
	var c = $('#'+b+' select');
	$('.txtPanel').val('');	$('.form-control').val('');
	$('input[type="text"]').val('');
	for(var i = 0;i<c.length;i++){
		console.log(c);
		c.eq(i).find('option').eq(0).attr('selected','selected');
	}

}
function publicmodeurl(num,act,oans){
	if(!act)act='publicstore';
	return js.getajaxurl(act,'mode_'+num+'|input','flow',oans);
}
function publicstore(mo,dos,oans){
	if(!mo)mo='query_index';
	if(!dos)dos='';
	return js.getajaxurl('publicstore',mo,dos,oans);
}
function publicsave(mo, dos,oans){
	if(!mo)mo='query_index';
	if(!dos)dos='';
	return js.getajaxurl('publicsave',mo,dos,oans);
}

function editfacechang(xid,nems){
	js.upload('_editfacechangback',{maxup:'1',thumbnail:'150x150','title':'修改['+nems+']的头像',uptype:'image','params1':xid});	
}
function _editfacechangback(a,xid){
	var f = a[0];
	var nf= f.thumbpath+'?'+Math.random()+'';
	if(xid==adminid)get('myface').src=nf;
	if(get('faceviewabc_'+xid+''))get('faceviewabc_'+xid+'').src=nf;
	js.msg('wait','头像修改中...');
	js.ajax(js.getajaxurl('editface','admin','system'),{fid:f.id,'uid':xid},function(){
		js.msg('success','修改成功,如没显示最新头像，请清除浏览器缓存');
	});
}
function _addbodykey(){
	$('body').keydown(function(e){
		var code	= e.keyCode;
		if(code==27){
			if(get('xpbg_bodydds')){
				js.tanclose($('#xpbg_bodydds').attr('xpbody'));
			}else{
				closenowtabs();
			}
			return false;
		}
		//弹出帮助
		//if(code==113){
			//js.confirm('是否打开查看关于['+nowtabs.name+']的帮助信息？',function(jg){
			//	if(jg=='yes')window.open('http://xh829.com/view_'+nowtabs.num+'.html?title='+jm.base64encode(nowtabs.name)+'');
			///});
			//return false;
		//}
	});
}

function opennew(name,num, id,cbal){
	
	addtabs({num:'check_'+num+'_'+js.getrand()+id,url:getRootPath()+'/?a=lu&m=input&d=flow&num='+num+'&mid='+id+'',icons:'icon-bookmark-empty',name:'['+name+']'});

}

function openinput(name,num, id,cbal){
	if(!id)id=0;
	if(!cbal)cbal='';
	if(id==0){name='[新增]'+name+'';}else{name='[编辑]'+name+'';}
	var url='?a=lu&m=input&d=flow&num='+num+'&mid='+id+'';
	openxiangs(name, url,'', cbal);
	return false;
}
function openxiangs(name,num,id,cbal){
	if(!id)id=0;
	if(!cbal)cbal='';
	var url = 'task.php?a=p&num='+num+'&mid='+id+'';
	if(num.indexOf('?')>-1){url=num+'&callback='+cbal+'';}else{url+='&callback='+cbal+'';}
	js.winiframe(name,url);
	return false;
}

function openurl(openurl){


	var url = openurl;
	
	js.winiframe('21',url);
	return false;
}

function openxiang(num,id,cbal){
	var url = 'task.php?a=p&num='+num+'&mid='+id+'';
	if(cbal)url+='&callback='+cbal+'';
	js.open(url, 800,500);
}

function optmenuclass(o1,num,id,obj,mname,oi, cola){
	this.modenum = num;
	this.modename= mname;
	this.id 	 = id;
	this.mid 	 = id;
	this.tableobj=obj;
	this.oi 	= oi;
	this.obj 	= o1;
	this.columns= cola;
	var me 		= this;
	this._init=function(){
		if(typeof(optmenuobj)=='object')optmenuobj.remove();
		this.callbackstr = '';
		if(this.columns.callback)this.callbackstr=this.columns.callback;
		optmenuobj=$.rockmenu({
			data:[],
			itemsclick:function(d){me.showmenuclick(d);},
			width:150
		});
		var da = [/*{name:'详情',lx:998,nbo:false},*/{name:'查看',lx:998,nbo:true}];
		var off=$(this.obj).offset();
		var subdata = optmenudatas[''+this.modenum+'_'+this.id+''];
		if(!subdata){
			
			da.push({name:'<img src="images/loadings.gif" align="absmiddle"> 加载菜单中...',lx:999});
			this.loadoptnum();
		}else{
			for(i=0;i<subdata.length;i++)da.push(subdata[i]);
		}
		
		optmenuobj.setData(da);
		optmenuobj.showAt(off.left,off.top+20);
	};
	this.xiang=function(oi,nbo){
		var mnem=this.modename;
		if(!nbo){
			if(!mnem)mnem='详情';

			openxiangs(mnem,this.modenum,this.mid, this.callbackstr);
		}else{
			//	check_project(this.mid,oi);
		openxiang(this.modenum,this.mid, this.callbackstr);
		}
	};
	this.openedit=function(){
		openinput(this.modename,this.modenum,this.mid);
	};
	this.getupgurl=function(str){
		if(str.substr(0,4)=='http' || str.indexOf('|')==-1)return str;
		var a1 = str.split('|'),lx = a1[0],mk = a1[1],cs=a1[2];
		var url= '';
		if(lx=='add')url='?a=lu&m=input&d=flow&num='+mk+'';
		if(lx=='xiang')url='task.php?a=p&num='+mk+'';
		if(cs)url+='&'+cs;
		console.log(url);
		return url;
	};
	this.showmenuclick=function(d){
		d.num=this.modenum;d.mid=this.id;
		d.modenum = this.modenum;
		var lx = d.lx;if(!lx)lx=0;
		if(lx==999)return;
		if(lx==998){this.xiang(d.oi, d.nbo);return;}
		if(lx==997){this.printexcel(d.oi);return;}
		if(lx==996){this.xiang(d.oi, d.nbo);return;}
		if(lx==11){this.openedit();return;}
		this.changdatsss = d;
		if(lx==2 || lx==3){
			var clx='user';if(lx==3)clx='usercheck';
			js.getuser({type:clx,title:d.name,callback:function(na,nid){me.changeuser(na,nid);}});
			return;
		}
		//打开新窗口
		if(lx==5){
			var upg = d.upgcont;
			if(isempt(upg)){
				js.msg('msg','没有设置打开的操作地址');
			}else{
				var url = this.getupgurl(upg);
				openxiangs(d.name, url,'', this.callbackstr);
			}
			return;
		}
		var nwsh = 'showfielsv_'+js.getrand()+'';
		var uostr= '<div align="left" style="padding:10px"><div id="'+nwsh+'" style="height:60px;overflow:auto" class="input"></div><input style="width:180px" id="'+nwsh+'_input" type="file"></div>';
		var bts = (d.issm==1)?'必填':'选填';
		if(lx==1 || lx==9 || lx==10){
			if(lx==9)uostr='';
			js.prompt(d.name,'请输入['+d.name+']说明('+bts+')：',function(index, text){
				if(index=='yes'){
					if(!text && d.issm==1){
						js.msg('msg','没有输入['+d.name+']说明');
					}else{
						me.okchangevalue(d, text);
					}
					return true;
				}
			},'','', uostr);
			this._uosschange(nwsh);
			return;
		}
		if(lx==4){
			js.prompt(d.name, '说明('+bts+')：', function(index, text){
				if(index=='yes'){
					var ad=js.getformdata('myformsbc');
					for(var i in ad)d['fields_'+i+'']=ad[i];
					me.okchangevalue(d, text);
					return true;
				}
			},'','<div align="left" id="showmenusss" style="padding:10px">加载中...</div>', uostr);
			var url='index.php?a=lus&m=input&d=flow&num='+d.modenum+'&menuid='+d.optmenuid+'&mid='+d.mid+'';
			$.get(url, function(s){
				var s='<form name="myformsbc">'+s+'</form>';
				$('#showmenusss').html(s);
				js.tanoffset('confirm');
			});
			this._uosschange(nwsh);
			return;
		}
		this.showmenuclicks(d,'');
	};
	this._uosschange=function(nwsh){
		this.fupobj = $.rockupload({
			autoup:false,
			fileview:nwsh,
			allsuccess:function(a,sid){
				me.upsuccessla(sid);
			}
		});
		$('#'+nwsh+'_input').change(function(){
			me.fupobj.change(this);
		});
	};
	this.upsuccessla=function(sid){
		var d = this.changdatsss;
		d.logfileid = sid;
		this.showmenuclicks(d, this.inputexplain);
		js.tanclose('confirm');
	};
	this.okchangevalue=function(d,text){
		this.changdatsss	= d;
		this.inputexplain 	= text;
		this.fupobj.start();
	};
	this.changeuser=function(nas,sid){
		if(!sid)return;
		var d = this.changdatsss,sm='';
		d.changename 	= nas; 
		d.changenameid  = sid; 
		this.showmenuclicks(d,sm);
	};
	this.showmenuclicks=function(d,sm){
		if(!sm)sm='';
		d.sm = sm;
		for(var i in d)if(d[i]==null)d[i]='';
		js.msg('wait','处理中...');
		js.ajax(js.getajaxurl('yyoptmenu','flowopt','flow'),d,function(ret){
			if(ret.code==200){
				optmenudatas[''+d.modenum+'_'+d.mid+'']=false;
				me.tableobj.reload();
				js.msg('success','处理成功');
			}else{
				js.msg('msg',ret.msg);
			}
		},'post,json');	
	};
	this.loadoptnum=function(){
		js.ajax(js.getajaxurl('getoptnum','flowopt','flow'),{num:this.modenum,mid:this.id},function(ret){
			if(ret.code == 200){
				optmenudatas[''+me.modenum+'_'+me.id+''] = ret.data;
				me._init();
			}else{
				js.msg('msg',ret.msg);
			}
		},'get,json');
	};
	this._init();
}
js.getuser = function(cans){
	var can = js.apply({title:'读取人员',idobj:false,nameobj:false,value:'',type:'deptusercheck',callback:function(){}}, cans);
	can.onselect=can.callback;
	js.changeuser(false, can.type, can.title, can);
}

js.getxmu = function(cans){
	var can = js.apply({title:'项目列表',idobj:false,nameobj:false,value:'',type:'deptusercheck',callback:function(){}}, cans);
	

	can.onselect=can.callback;
	
	js.changeuser(false, can.type, can.title, can);
}

//自定义导出
publicdaochuobjfarr = {};
function publicdaochuobj(options){
	var me 		= this;
	var cans 	= js.apply({'oncallback':function(){},'modenum':'','modenames':'',modename:'',objtable:false,fieldsarr:[],btnobj:false,notdingyue:false}, options);
	for(var a in cans)this[a]=cans[a];
	this._init=function(){
		if(!this.btnobj || !this.objtable)return;

		if(!this.daochuobj)this.daochuobj=$.rockmenu({
			width:120,top:35,donghua:false,data:[],
			itemsclick:function(d, i){
				me.daonchuclick(d);
			}
		});
		var d = [{name:'导出全部',lx:0},{name:'导出当前页',lx:1},{name:'自定义列导出',lx:3}];
		if(!this.notdingyue)d.push({name:'订阅此列表',lx:2});
		this.daochuobj.setData(d);
		var lef = $(this.btnobj).offset();
		this.daochuobj.showAt(lef.left, lef.top+35);
	};
	this.daonchuclick=function(d){
		if(d.lx==0)this.objtable.exceldown();
		if(d.lx==1)this.objtable.exceldownnow();
		if(d.lx==2)this.subscribelist();
		if(d.lx==3)this.excelautoinit();
	}
	this.subscribelist=function(){
		var name = nowtabs.name;
		if(this.modename!='')name=''+this.modename+'('+name+')';
		js.subscribe({
			title:name,
			cont:''+name+'的列表的',
			explain:'订阅['+name+']的列表',
			objtable:this.objtable
		});
	}
	this.excelautoinit=function(){
		if(this.fieldsarr.length==0){
			if(this.modenum!=''){
				if(publicdaochuobjfarr[this.modenum]){
					this.loadfarrshow(publicdaochuobjfarr[this.modenum]);
				}else{
					js.loading('读取字段中...');
					js.ajax(js.getajaxurl('getfields','flowopt','flow'),{'modenum':this.modenum}, function(ret){
						js.unloading();
						me.loadfarrshow(ret);
					},'get,json', function(st){
						js.msgerror(st);
					});
				}
			}else{
				var farr = this.objtable.getcolumns(),i,fars=[];
				for(i=0;i<farr.length;i++){
					if(!farr[i].notexcel)fars.push({
						'fields':farr[i].dataIndex,
						'name':farr[i].text,
						'islb':'1'
					});
				}
				this.fieldsarr=fars;
				this.excelauto();
			}
		}else{
			this.excelauto();
		}
	}
	this.loadfarrshow=function(ret){
		var farr = ret.fieldsarr;
		publicdaochuobjfarr[this.modenum]=ret;
		this.fieldsarr=farr;
		this.isflow = ret.isflow;
		this.modenames = ret.modenames;
		this.excelauto();
	}
	this.excelauto=function(){
		if(this.fieldsarr.length==0){
			js.msg('msg','没有设置字段数据');
			return;
		}
		var dar=[],i,sdar;
		for(i in this.fieldsarr)dar.push(this.fieldsarr[i]);
		if(!isempt(this.modenames)){
			sdar = this.modenames.split(',');
			for(i in sdar)dar.push({'fields' : 'sub_table_'+i+'','name' : sdar[i]});
		}
		this.nowfieldsarr = dar;
		var str='<table width="100%"><tr>',len=dar.length,d1,sel,oi=0;
		for(i=0;i<len;i++){
			d1 = dar[i];
			if(this.isflow==0){
				if(d1.fields=='base_name' || d1.fields=='base_deptname' || d1.fields=='sericnum')continue;
			}
			oi++;
			sel = '';
			if(d1.islb==1)sel='checked';
			str+='<td width="25%" align="left"><label><input name="daochufields" value="'+i+'" '+sel+' type="checkbox">'+d1.name+'</label></td>';
			if(oi%4==0)str+='</tr><tr>';
		}
		str+='</tr></table>';
		str+='<div><label><input type="checkbox" onclick="js.selall(this,\'daochufields\')">全选</label>&nbsp;&nbsp;&nbsp;导出前&nbsp;<input type="number" class="form-control" id="daolimit" style="width:100px" min="1" value="1000">&nbsp;条记录</div>';
		js.tanbody('autoexceldao',''+this.modename+'自定义列导出',520,410,{
			html:'<div>'+str+'</div>',
			bodystyle:'padding:10px',
			btn:[{text:'确定'}]
		});
		$('#autoexceldao_btn0').click(function(){
			me.okdaochu();
		});
	}
	this.okdaochu=function(){
		var did = js.getchecked('daochufields');
		if(did==''){
			js.msg('msg','至少要选择一个列');
			return;
		}
		var dida = did.split(','),i,d1,str1='',str2='',str3='';
		for(i=0;i<dida.length;i++){
			d1 = this.nowfieldsarr[dida[i]];
			str1+=','+d1.name+'';
			str2+=','+d1.fields+'';
			if(d1.fields.indexOf('sub_table_')==0)str3+=','+d1.fields.substr(10)+'';
		}
		str1 = str1.substr(1);
		str2 = str2.substr(1);
		if(str3!='')str3 = str3.substr(1);
		this.objtable.exceldown('',2, {
			'page':1,
			'limit':get('daolimit').value,
			'excelfields':str2,
			'excelheader':str1,
			'excelsubtab':str3
		});
		js.tanclose('autoexceldao');
	}
	this._init();
}

//重写js.tanbody
js.winiframemax = 120;
js.tanbody=function(act,title,w,h,can1){
	this.tanbodyindex++;
	var can	= js.applyIf(can1,{html:'',msg:'',showfun:function(){},bodystyle:'',guanact:'',titlecls:'',btn:[]});
	var l=(winWb()-w-50)*0.5,t=(winHb()-h-50)*0.5;
	var s	= '';
	var mid	= ''+act+'_main';
	$('#'+mid+'').remove();
	var heis='';
	if(can.bodyheight)heis='height:'+can.bodyheight+';overflow:auto;';
	var s = '<div class="modal" id="'+mid+'" tabindex="-1" role="dialog" style="left:3px;top:'+t+'px" aria-labelledby="myModalLabel">';
	s+='<div id="xpbg_bodydds" xpbody="'+act+'" class="modal-dialog" style="width:'+w+'px;margin:0px auto" role="document">';
	s+=' 	<div class="modal-content">';
	s+=' 		<div class="modal-header" >';
	s+='			<button id="'+act+'_spancancel" type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
	s+='			<h4 onmousedown="js.move(\''+mid+'\')" class="modal-title">'+title+'</h4>';
	s+='		</div>';
	s+='		<div class="modal-body" style="padding:0px;'+heis+';'+can.bodystyle+'" id="'+act+'_body">'+can.html+'</div>';

	s+='	<div id="'+act+'_bbar" class="modal-footer" align="right"><span id="msgview_'+act+'">'+can.msg+'</span>&nbsp;';
	for(var i=0; i<can.btn.length; i++){
		var a	= can.btn[i];
		s+='<button class="btn btn-success" id="'+act+'_btn'+i+'" onclick="return false">';
		if(!isempt(a.icons))s+='<i class="icon-'+a.icons+'"></i>&nbsp; ';
		s+=''+a.text+'</button>&nbsp; ';
	}
	s+='	<button class="btn btn-default" id="'+act+'_cancel" onclick="return js.tanclose(\''+act+'\',\''+can.guanact+'\')">取消</button>';
	s+='	</div>';

	s+='  </div>';
	s+=' </div>';
	s+='</div>';
	$('body').append(s);

	if(can.closed=='none'){
		$('#'+act+'_bbar').remove();
		$('#'+act+'_spancancel').remove();
	}
	if(can.bbar=='none')$('#'+act+'_bbar').remove();
	this.modalobj = $('#'+mid+'').modal({'keyboard':false,'show':true,'backdrop':'static'});
	this.modalobj.on('hidden.bs.modal',function(){
		$('#'+mid+'').remove();
	});
	this.tanoffset(act);
	can.showfun(act);
}

js.tanclose=function(act, guan){
	$('#'+act+'_main').remove();
	$($('.modal-backdrop')[0]).remove();
	js.xpbody(act,'none');
	return false;
}

js.tanoffset=function(act){
	var mid=''+act+'_main';
	var lh=$('#'+mid+'').find('div[xpbody]').height(),l,t;
	t=(winHb()-lh-20)*0.5;
	if(t<0)t=1;
	$('#'+mid+'').css({'top':''+t+'px'});
}



/**
 * 获取当前项目的域名和项目名
 */
function getRootPath(){
    //获取当前网址，如： http://localhost:8083/uimcardprj/share/meun.jsp
    var curWwwPath=window.document.location.href;
    //获取主机地址之后的目录，如： uimcardprj/share/meun.jsp
    var pathName=window.document.location.pathname;
    var pos=curWwwPath.indexOf(pathName);
    //获取主机地址，如： http://localhost:8083
    var localhostPaht=curWwwPath.substring(0,pos);
    //获取带"/"的项目名，如：/uimcardprj
    var projectName=pathName.substring(0,pathName.substr(1).indexOf('/')+1);
    return(localhostPaht+projectName);
}


