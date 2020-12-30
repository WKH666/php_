<?php

?>
<style>
    .checkHeader {
        width: 100%;
        height: 35px;
        line-height: 35px;
        background-color: #CDE3F1;
        font-size: 14px;
        border-radius: 5px;
        padding-left: 5px;
    }

    .results_table{
        width:100%;
        margin-bottom: 30px;
    }
    .results_table thead tr td{
        text-align: center;
        background: #F2F2F2;
        line-height: 30px;
    }
    .results_table tbody tr td{
        text-align: center;
        border: 1px solid #d1d1d1;
        box-sizing: border-box;
    }
    .results_table tbody tr td:nth-child(2){
        text-align: left;
    }
    .results_table tbody tr td:nth-child(2) input{
        width: 95%;
        border: none;
    }
    .results_table tbody tr td:nth-child(2) span{
        font-size: 20px;
        cursor: pointer;
    }

    .results_table tbody tr td:nth-child(3){
        color: #ffc552;
    }

    .results_table tbody tr td:nth-of-type(1){
        text-align: center;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
    }
</style>

<script type="text/javascript">

$(function(){
	{params}

var bgs = '<div id="mainloaddiv" style="width:'+viewwidth+'px;height:'+viewheight+'px;overflow:hidden;background:#000000;color:white;filter:Alpha(opacity=20);opacity:0.2;z-index:3;position:absolute;left:0px;line-height:'+viewheight+'px;top:0px;" align="center"><img src="images/mloading.gif"  align="absmiddle">&nbsp;加载中...</div>';
		$('#indexcontent').append(bgs);

iframeSrc(params.num,params.mid);
$("#ifrID_{rand}").load(function () {
    var mainheight = $(this).contents().find("body").height() + 30;
    $(this).height(mainheight);
    $('#mainloaddiv').remove();
});
    getKtFile(params.mid,params.num);
});


function preview(){
	{params}
//	if(checkFilling()) {
//		layer.msg("你的信息尚未填写完整！");
//	} else if(checkSubScore()) {
//		layer.msg("子项目最大分值超过了父项目或最低分超过最高分，请修改！");
//	} else if(checkTotalScore()) {
//		layer.msg("所有指标分数之和没有等于100分，请修改！");
//	} else {
//		var data = JSON.stringify(getData());
//		sessionStorage.setItem("normpreview", data);
//		var url = getRootPath()+'/webmain/main/project_comment/rock_project_comment_norm_look.php';
//		js.open(url,800,500);
//	}
//
    /*var bgs = '<div id="mainloaddiv" style="width:'+viewwidth+'px;height:'+viewheight+'px;overflow:hidden;background:#000000;color:white;filter:Alpha(opacity=20);opacity:0.2;z-index:3;position:absolute;left:0px;line-height:'+viewheight+'px;top:0px;" align="center"><img src="images/mloading.gif"  align="absmiddle">&nbsp;加载中...</div>';
    $('#indexcontent').append(bgs);*/
		js.ajax(js.getajaxurl('getpicimodel', 'project_comment', 'main'), {pici_id:params.pici_id}, function(ds) {
		    jsonstr=ds.data;
			var data = jsonstr;
			sessionStorage.clear();
			sessionStorage.setItem("normpreview", data);
            var url = '';
            if (params.type){
                url = getRootPath()+'/webmain/main/project_comment/rock_project_comment_submit_wp.php?pici_id='+params.pici_id+'&mid='+params.mid+'&mtype='+params.num+'&type='+params.type;
            }else{
                url = getRootPath()+'/webmain/main/project_comment/rock_project_comment_submit_wp.php?pici_id='+params.pici_id+'&mid='+params.mid+'&mtype='+params.num;
            }
             var name='评分';                            //网页名称，可为空;
             var iWidth=900;                          //弹出窗口的宽度;
             var iHeight=600;                         //弹出窗口的高度;
             //获得窗口的垂直位置
             var iTop = (window.screen.availHeight - 30 - iHeight) / 2;
             //获得窗口的水平位置
             var iLeft = (window.screen.availWidth - 10 - iWidth) / 2;

             myWindow=window.open(url, name, 'height=' + iHeight + ',,innerHeight=' + iHeight + ',width=' + iWidth + ',innerWidth=' + iWidth + ',top=' + iTop + ',left=' + iLeft + ',status=no,toolbar=no,menubar=no,location=no,resizable=no,scrollbars=0,titlebar=no');

              intHand=setInterval("checkWin()",30);
                $('#mainloaddiv').remove();
            //2020.10.19修改，将弹窗窗口改为标签页
           // addtabs({num:'info',url:'main,project_comment,submit_wp,pici_id='+params.pici_id+',mid='+params.mid+',mtype='+params.num+'',icons:'icon-bookmark-empty',name:'项目评分'});
        }, 'post,json');
}


function checkWin(){
       if(myWindow!=null && myWindow.closed){
			{params}
        	js.ajax(js.getajaxurl('getuseropen', 'project_comment', 'main'), {pici_id:params.pici_id,mid:params.mid,mtype:params.num}, function(ds) {

        		openck=ds.data;
        		if(openck==0){

        		}else if(openck==1 || openck==2){
		           	setTimeout(function(){
		           		closetabs('edit_norm');
		            	expert_list.reload();
		           	},300);

        		}
        		clearInterval(intHand);
		        intHand=null;
		        myWindow=null;

			}, 'post,json');


        }
 }


function iframeSrc(num,mid){
	$("#ifrID_{rand}").attr("src", "task.php?a=p&num="+num+"&mid="+mid+"&pinshen=word");
}
//获取课题设计论证活页
function getKtFile(project_id,type) {
    js.ajax(js.getajaxurl('getKtFile', 'project_comment', 'main'), {project_id:project_id,mtype:type}, function(ds) {
        if (ds.code==1){
            var html = '<tr>\n' +
                '                    <td>课题设计论证(活页)</td>\n' +
                '                    <td><input  readonly value=' +  ds.data.filename + '></td>\n' +
                '                    <td>未查看</td>\n' +
                '                    <td>\n' +
                '                        <a href="javascript:;"  style="color: #3D8EDB;text-decoration: none" >查看</a>\n' +
                '                        <a href="javascript:;" onclick="downFile(' + ds.data.id + ',\'' + ds.data.fileext + '\',\'' + ds.data.filepath + '\'' + ')" style="color: #3D8EDB;text-decoration: none;" >下载</a>\n' +
                '                    </td>\n' +
                '                </tr>';
            $('#expertcomment_tbody').append(html);
        }
       }, 'post,json');
}
//文件下载
function downFile(fileId, fileType, filePath) {
    if (js.isimg(fileType)) {
        $.imgview({url: filePath});
    } else {
        js.downshow(fileId)
    }
}
</script>
<div style="width:820px;margin:auto;padding-top: 20px;text-align: center;" >
<button type="button" class="btn btn-info" style="width: 160px;font-size: 17px;" onclick="preview()">网评</button>
</div>
<div class="checkHeader" style="margin: 40px 0 0 0;">附件资料</div>
<table id="expertcomment_table" cellpadding="0" cellspacing="0" class="results_table">
    <thead>
    <tr>
        <td>附件类型</td>
        <td>文件名称</td>
        <td>上传状态</td>
        <td>操作</td>
    </tr>
    </thead>
    <tbody id="expertcomment_tbody"></tbody>
</table>
<iframe id="ifrID_{rand}" src="" frameBorder="0" width="100%" scrolling="yes" style="display: none;" ></iframe>


