<?php defined('HOST') or die('not access');?>
<script >
    var formData = null;
    var fileType = null;
$(document).ready(function(){
	{params}
	var modenum = params.modenum;
    // if(modenum == "inforresults"){
    //     $("#excel_mode").text('成果汇总表');
    //     $("#zip_mode").show();
    //     $("#zip_mode").text('成果附件');
    //     $("#upzipbtn{rand}").show();
    // }else{
    //     $("#excel_mode").text('数据文档');
    // }
    // var mobjs = $('#maincont_{rand}');
	var c={
		// headers:'',
		// yulan:function(){
		// 	var cont = mobjs.val(),s='',a,a1,i,j,oi=0;
		// 	s+='<table class="basetable" border="1">';
		// 	s+='<tr><td></td>'+this.headers+'</tr>';
		// 	a = cont.split('\n');
		// 	for(i=0;i<a.length;i++){
		// 		if(a[i]){
		// 			oi++;
		// 			a1 = a[i].split('	');
		// 			s+='<tr>';
		// 			s+='<td>'+oi+'</td>';
		// 			for(j=0;j<a1.length;j++)s+='<td>'+a1[j]+'</td>';
		// 			s+='</tr>';
		// 		}
		// 	}
		// 	s+='</table>';
		// 	$('#showview_{rand}').html(s);
		// },
		init:function(){
			var vis = 'msgview_{rand}';
			js.setmsg('初始化中...','', vis);
			js.ajax(publicmodeurl(modenum,'initdaoru'),{'modenum' : modenum},function(data){
                js.setmsg('','', vis);
				//c.initshow(data);
			},'get,json');
		},
		// initshow:function(data){
		//     this.bitian='';
		// 	this.headers='';
		// 	var i,len=data.length,d;
		// 	for(i=0;i<len;i++){
		// 		d=data[i];
		// 		this.headers +='<td>';
		// 		if(d.isbt=='1'){
		// 			this.bitian+=','+d.fields+'';
		// 			this.headers+='<font color=red>*</font>';
		// 		}
		// 		this.headers+=''+ data[i].name+'</td>';
		// 	}
		// 	this.yulan();
		// },
		// insrtss:function(){
		// 	var val = mobjs.val();
		// 	mobjs.val(val+'	');
		// 	mobjs.focus();
		// },
		saveadd:function(o1){
			// var val = mobjs.val();
			var vis = 'msgview_{rand}';
			// if(isempt(val)){
			// 	js.setmsg('没有输入任何东西','', vis);
			// 	return;
			// }
			js.setmsg('处理中...','', vis);

            if(fileType == 'zip'){
                console.log('上传zip请求');
                send_ajax(js.getajaxurl('importzip', 'input', 'flow'), formData, function (ds) {
                    if (ds.success) {
                        layer.msg('导入成功');
                        try{window['managelist'+modenum+''].reload()}catch(e){}
                        closetabs('daoru'+modenum);
                    } else {
                        layer.msg(ds.msg);
                    }
                }, 'post,json');
            }
            if(fileType == 'rar'){
                console.log('上传rar请求');
                send_ajax(js.getajaxurl('importrar', 'input', 'flow'), formData, function (ds) {
                    if (ds.success) {
                        layer.msg('导入成功');
                        try{window['managelist'+modenum+''].reload()}catch(e){}
                        closetabs('daoru'+modenum);
                    } else {
                        layer.msg(ds.msg);
                    }
                }, 'post,json');
            }

			o1.disabled=true;
			let daoruvalresults = window.sessionStorage.getItem('daoruvalresults');
			js.ajax(js.getajaxurl('daorudata','{mode}','{dir}'),{importcont:daoruvalresults,'modenum':modenum},function(data){
				if(data.success){
					js.setmsg(data.msg,'green', vis);
					try{window['managelist'+modenum+''].reload()}catch(e){}
                    closetabs('daoru'+modenum);
				}else{
					js.setmsg(data.msg+'','red', vis);
					o1.disabled=false;
				}
			},'post,json',function(s){
				js.setmsg(s,'red', vis);
				o1.disabled=false;
			});

			},
		// downxz:function(){
		// 	var url = '?m=input&a=daoruexcel&d=flow&modenum='+modenum+'';
		// 	js.open(url);
		// },
		addfile:function(){
			js.upload('_daorufile_excel{rand}',{maxup:'1','title':'选择Excel文件',uptype:'xls|xlsx','urlparams':'noasyn:yes'});
		},
		backup:function(fid){
			var o1 = get('upexcelbtn{rand}');
			o1.disabled=true;
			o1.value='文件读取中...';
            js.ajax(js.getajaxurl('readxls','{mode}','{dir}'),{'fileid':fid,'modenum':modenum},function(data){
                if(data.code == 200){
                    o1.value='读取成功';
                    let readrows = data.rows;
                    window.sessionStorage.removeItem('daoruvalresults');
                    window.sessionStorage.setItem('daoruvalresults',readrows[0]);
                }else{
                    js.msg('msg', data.msg);
                    o1.value='读取失败';
                }
                o1.disabled=false;
            },'get,json');
		},
        upload:function(){
            js.uploads('_daorufile_zip{rand}', { showid: 'fileidview','title':'选择压缩文件',uptype:'zip|rar' });
        },
        reback:function(){
            closetabs('daoru'+modenum);
        },
	};
    js.initbtn(c);
    c.init();

    _daorufile_excel{rand}=function(a,xid){
        var f = a[0];
        c.backup(f.id);
        $("#excel_{rand}").attr('href',f.filepath);
        $("#excel_{rand}").text(f.filename);
    };

    _daorufile_zip{rand}=function(a,xid){
        var f = a[0];
        $("#zip_{rand}").attr('href',f.filepath);
        $("#zip_{rand}").text(f.filename);
    };

    // mobjs.keyup(function(){
    //     c.yulan();
    // });

    js.uploads=function(call,can, glx){
        if(!call)call='';
        if(!can)can={};
        js.uploadrand	= js.now('YmdHis')+parseInt(Math.random()*999999);
        var url = 'index.php?m=information_base&d=main&callback='+call+'&upkey='+js.uploadrand+'';
        for(var a in can)url+='&'+a+'='+can[a]+'';
        if(glx=='url')return url;
        var s='',tit=can.title;if(!tit)tit='上传文件';
        js.tanbody('uploadwin',tit,450,300,{
            html:'<div style="height:260px;overflow:hidden"><iframe src="" name="winiframe" width="100%" height="100%" frameborder="0"></iframe></div>',
            bbar:'none'
        });
        winiframe.location.href=url;
        return false;
    }


});

    //上传压缩包
    function import_zip_change(target,id) {
           // var filetypes = ['.rar','.zip'];
            var filetypes = ['.zip'];
            var filepath = target.value;
            //当文件存在时
            if(filepath){
                var isnext = false;
                var fileend = filepath.substring(filepath.lastIndexOf("."));
                //检测类型
                if(filetypes && filetypes.length>0){
                    for(var i =0; i<filetypes.length;i++){
                        if(filetypes[i]==fileend){
                            isnext = true;
                            //上传zip压缩包
                            if(fileend == '.zip'){
                                var fileArray = $("#import_zip_btn")[0].files[0];
                                var fileName = $("#import_zip_btn")[0].files[0].name;
                                $('#zip_name').text(fileName);
                                fileType = 'zip';
                                formData = new FormData();
                                formData.append('file', fileArray);
                            }/*else if (fileend == '.rar'){
                                var fileArray = $("#import_zip_btn")[0].files[0];
                                var fileName = $("#import_zip_btn")[0].files[0].name;
                                $('#zip_name').text(fileName);
                                fileType = 'rar';
                                formData = new FormData();
                                formData.append('file', fileArray);
                            }*/
                        }
                    }
                }
                if(!isnext){
                    layer.msg("不接受此文件类型!");
                    target.value ="";
                    return false;
                }
            }else{
                return false;
            }
    }


    function send_ajax(url,da,fun,type,efun, tsar) {
    if (js.ajaxbool) return;
    if (!da) da = {};
    if (!type) type = 'get';
    if (!tsar) tsar = '';
    tsar = tsar.split(',');
    if (typeof (fun) != 'function') fun = function () {
    };
    if (typeof (efun) != 'function') efun = function () {
    };
    var atyp = type.split(','), dtyp = '';
    type = atyp[0], async = true;
    if (atyp[1]) dtyp = atyp[1];
    if (atyp[2]) async = atyp[2];
    js.ajaxbool = true;
    if (tsar[0]) js.msg('wait', tsar[0]);
    var ajaxcan = {
        type: type,
        data: da,
        url: url,
        processData: false,
        contentType: false,
        success: function (str) {
            js.ajaxbool = false;
            try {
                if (tsar[1]) js.msg('success', tsar[1]);
                fun(str);
            } catch (e) {
                console.log('返回数据成功!');
            }
        }, error: function (e) {
            js.ajaxbool = false;
            js.msg('msg', '处理出错:' + e.responseText + '');
            efun(e.responseText);
        }
    };
    if (dtyp) ajaxcan.dataType = dtyp;
    if (async) ajaxcan.async = async;
    $.ajax(ajaxcan);
}

</script>
<style>
    .file {
        position: relative;
        display: inline-block;
        border-radius: 4px;
        padding: 4px 12px;
        overflow: hidden;
        text-decoration: none;
        text-indent: 0;
        line-height: 20px;
    }
    .file input {
        position: absolute;
        font-size: 100px;
        right: 0;
        top: 0;
        opacity: 0;
    }
    .file:hover {
        text-decoration: none;
    }

    .header_title{
        background: #CDE3F1;
        border-radius: 5px;
    }
    .header_title p{
        padding: 5px 0;
    }
    .tips{
        text-indent: 0.8em;
        font-size: 12px;
    }
    #excel_mode,#zip_mode{
        margin: 10px;text-indent: 0;
        width: 10%;
        text-align: right;
    }
    .content_0,.content_1,.content_2{
        padding:5px 0px;display: flex;flex-direction: row;align-items: center;flex-wrap: wrap;
    }
    .content_2 button{
        margin: 0 10px;
    }
</style>
<div align="left">
<div class="header_title">
    <p>成果汇总表</p>
</div>
<!--<div>请下面表格格式在Excel中添加数据，并复制到下面文本框中，也可以手动输入，<a click="downxz" href="javascript:;">[下载Excel模版]</a>。<br>多行代表多记录，整行字段用	分开，<a click="insrtss" href="javascript:;">插入间隔符</a></div>-->
<div class="content_0" >
    <p id="excel_mode">成果汇总表:</p>
    <input type="button" id="upexcelbtn{rand}" click="addfile" class="btn btn-default" value="上传文件">
    <a id="excel_{rand}" href="" target="_blank"></a>
    <span class="tips">支持扩展名：.xlsx.xls的文件</span>
</div>

<div class="header_title">
    <p>成果附件</p>
</div>
<div class="content_1">
    <p id="zip_mode">成果附件:</p>
    <a href="javascript:;" class="file btn btn-default">上传附件
        <input type="file" id="import_zip_btn" onchange="import_zip_change(this)">
    </a>
    <span class="tips" >支持扩展名：.zip压缩文件</span>
   <!-- <a id="zip_{rand}" href="" target="_blank"></a>-->
</div>
    <p id="zip_name" style="margin-left: 3em"></p>

<!--<div><textarea style="height:250px;" id="maincont_{rand}" class="form-control"></textarea></div>-->

<!--<div id="showview_{rand}"></div>-->
<div class="blank40"></div>
<div class="content_2">
<!--    <a click="yulan" href="javascript:;">[预览]</a>&nbsp; &nbsp; -->
    <button class="btn btn-primary" click="saveadd" type="button">保存</button>
<!--    &nbsp; <span id="msgview_{rand}"></span>-->
    <button class="btn btn-default" click="reback" type="button">取消</button>
<!--<div class="tishi">请严格按照规定格式添加，否则数据将错乱哦，导入的字段可到[流程模块→表单元素管理]下设置，更多可查看<a href="--><?//=URLY?><!--view_daoru.html" target="_blank">[帮助]</a>。</div>-->
</div>
