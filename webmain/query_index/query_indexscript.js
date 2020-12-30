function initbody(){
	objcont = $('#content_allmainview');
	clickhome();
	if(show_key!='')jm.setJmstr(jm.base64decode(show_key));
	$('.topmenubg').on('click','span',function(){
		for(var i = 0; i <$("span").length; i++){
			$("span")[i].className = '';
		}
		$(this)[0].className = 'spanactive';
	});
	 if(typeof(applicationCache)=='undefined'){
	// 	js.msg('msg','您的浏览器太低了无法达到想要的预览效果<br>建议使用IE10+，Firefox，Chrome等高级点的',2);
	 }
	var ddsata=[
	{
		name:'<i class="icon-user"></i> 帐号('+adminuser+')',num:'user'
	}];
	if(js.request('afrom')=='')ddsata.push({name:'<i class="icon-signout"></i> 退出',num:'exit'});
	$('#indexuserl').rockmenu({
		width:170,top:50,
		data:ddsata,
		itemsclick:function(d){
			if(d.num=='exit'){
				js.confirm('确定要退出系统吗？',function(bn){
					if(bn=='yes')js.location('?m=query_login&a=exit');
				});
				return;
			}
			if(d.num=='user'){
				js.ajax(js.getajaxurl('getadminid','query_index','home'),{userid:adminid},function(res){
					if(res.success){
						addtabs({num:'personal',url:'home,query_index,personal',name:'个人中心',hideclose:true});
						return;
					}
				},'post,json');
			}
			// addtabs({num:d.num,url:d.url,name:d.names});
		}
	});

	function _loadjsurl(){
		js.importjs('web/res/mode/echarts/echarts.common.min.js');
		js.importjs('web/res/js/jquery-imgview.js');
	}
	setTimeout(_loadjsurl,1000);
}

function openresults(){
	addtabs({num:'results',url:'main,query_information_base,results',name:'成果信息',hideclose:true});
	return false;
}
function opencross(){
	addtabs({num:'cross',url:'main,query_information_base,cross',name:'纵/横项目信息',hideclose:true});
	return false;
}
function openreport(){
	addtabs({num:'report',url:'main,query_information_base,report',name:'论文发表信息',hideclose:true});
	return false;
}
function openprize(){
	addtabs({num:'prize',url:'main,query_information_base,prize',name:'获奖信息',hideclose:true});
	return false;
}

function clickhome(){
	var ad = {num:'home',url:'home,query_index',icons:'home',name:'信息库首页',hideclose:true};
	if(homeurl!='')ad.url= homeurl;
	if(homename!='')ad.name= homename;
	addtabs(ad);
	return false;
}

//检查是否存在，存在就削除再加载
function _checkInit(o){
	var c = objtabs.find('#tabs_'+o.num);
	var d = objcont.find('#content_'+o.num);
	if(c.length>=1){
		c.remove();
		d.remove();
	}
		addtabs(o);
}

var coloebool = false;
function closetabs(num){
	tabsarr[num] = false;
	$('#content_'+num+'').remove();
	$('#tabs_'+num+'').remove();
	if(num == nowtabs.num){
		var now ='home',i,noux;
		for(i=opentabs.length-1;i>=0;i--){
			noux= opentabs[i];
			if(get('content_'+noux+'')){
				now = noux;
				break;
			}
		}
		changetabs(now);
	}
	coloebool = true;
	// _pdleftirng();
	setTimeout('coloebool=false',10);
}

function closenowtabs(){

	var nu=nowtabs.num;
	//console.log('是否有调用关闭当前页面的公共方法');
	if(nu=='home')return;
	closetabs(nu);
}

function changetabs(num,lx){
	if(coloebool)return;
	if(!lx)lx=0;
	$("div[temp='content']").hide();
	$("[temp='tabs']").removeClass();
	var bo = false;
	if(get('content_'+num+'')){
		$('#content_'+num+'').show();
		$('#tabs_'+num+'').addClass('accive');
		nowtabs = tabsarr[num];
		bo = true;
	}
	opentabs.push(num);
	if(lx==0)_changhhhsv(num);
	return bo;
}
function _changhhhsv(num){
	var o=$("[temp='tabs']"),i,w1=0;
	for(i=0;i<o.length;i++){
		if(o[i].id=='tabs_'+num+'')break;
		w1=w1+o[i].scrollWidth;
	}
	$('#tabsindexm').animate({scrollLeft:w1});
}
function _changesrcool(lx){
	var l = $('#tabsindexm').scrollLeft();
	var w = l+200*lx;
	$('#tabsindexm').animate({scrollLeft:w});
}
// function _pdleftirng(){
// 	var mw=get('tabs_title').scrollWidth;
// 	if(mw>viewwidth){$('.jtcls').show();}else{$('.jtcls').hide();}
// }

function addiframe(a){
	a.url = 'index,iframe,url='+jm.base64encode(a.url)+'';
	addtabs(a);
}

function addtabs(a){
	var url = a.url,
		num	= a.num;
	if(isempt(url))return false;
	if(url.indexOf('add,')==0){openinput(a.name,url.substr(4));return;}
	if(url.indexOf('open:')==0){window.open(url.substr(5));return;}
	if(url.indexOf('http')==0 || url.substr(0,1)=='?'){addiframe(a);return;}
	nowtabs = a;
	if(changetabs(num))return true;

	// var s = '<td temp="tabs" nowrap onclick="changetabs(\''+num+'\',1)" id="tabs_'+num+'" class="accive">';
	// if(a.icons)s+='<i class="icon-'+a.icons+'"></i>  ';
	// s+=a.name;
	// if(!a.hideclose)s+='<span onclick="closetabs(\''+num+'\')" class="icon-remove"></span>';
	// s+='</td>';
	// objtabs.append(s);
	_changhhhsv(num);
	// _pdleftirng();

	var rand = js.getrand(),i,oi=2,
		ura	= url.split(','),
		dir	= ura[0],
		mode= ura[1];
	url =''+dir+'/'+mode+'/rock_'+mode+'';
	if(ura[2]){
		if(ura[2].indexOf('=')<0){
			oi=3;
			url+='_'+ura[2]+'';
		}
	}
	var urlpms= '';
	for(i=oi;i<ura.length;i++){
		var nus	= ura[i].split('=');
		urlpms += ",'"+nus[0]+"':'"+nus[1]+"'";
	}
	if(urlpms!='')urlpms = urlpms.substr(1);
	var bgs = '<div id="mainloaddiv" style="width:'+viewwidth+'px;height:'+viewheight+'px;overflow:hidden;background:#000000;color:white;filter:Alpha(opacity=20);opacity:0.2;z-index:3;position:absolute;left:0px;line-height:'+viewheight+'px;top:0px;" align="center"><img src="images/mloading.gif"  align="absmiddle">&nbsp;加载中...</div>';
	$('#indexcontent').append(bgs);

	objcont.append('<div temp="content" id="content_'+num+'" style="height: 100%;width: 100%;"></div>');
	$.ajax({
		url:'?m=query_index&a=getshtml&surl='+jm.base64encode(url)+'',
		type:'get',
		success: function(da){
			$('#mainloaddiv').remove();
			var s = da;
				s = s.replace(/\{rand\}/gi, rand);
				s = s.replace(/\{adminid\}/gi, adminid);
				s = s.replace(/\{adminname\}/gi, adminname);
				s = s.replace(/\{mode\}/gi, mode);
				s = s.replace(/\{dir\}/gi, dir);
				s = s.replace(/\{params\}/gi, "var params={"+urlpms+"};");
			var obja = $('#content_'+num+'');
			obja.html(s);
		},
		error:function(){
			$('#mainloaddiv').remove();
			var s = 'Error:加载出错喽,'+url+'';
			$('#content_'+num+'').html(s);
		}
	});
	tabsarr[num] = a;
	return false;
}
