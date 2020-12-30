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
//1
var bgs = '<div id="mainloaddiv" style="width:'+viewwidth+'px;height:'+viewheight+'px;overflow:hidden;background:#000000;color:white;filter:Alpha(opacity=20);opacity:0.2;z-index:3;position:absolute;left:0px;line-height:'+viewheight+'px;top:0px;" align="center"><img src="images/mloading.gif"  align="absmiddle">&nbsp;加载中...</div>';
$('#indexcontent').append(bgs);
        if (params.uid){
            js.ajax(js.getajaxurl('expert_dafen', 'project_comment', 'main'), {pici_id:params.pici_id,mid:params.mid,mtype:params.num,uid:params.uid}, function(ds) {
                jsonstr=ds.data.model;
                var review_opinion = ds.data.review_opinion;
                var data = jsonstr;
                sessionStorage.setItem("normpreview", data);
                sessionStorage.setItem("review_opinion", review_opinion);
                sessionStorage.setItem("review_opinion_end", ds.data.review_opinion_end);
                sessionStorage.setItem("level_suggest", ds.data.level_suggest);
                sessionStorage.setItem("publish_suggest", ds.data.publish_suggest);
                var url = '';
                if (params.type=='project_start'){
                    url = getRootPath()+'/webmain/main/project_comment/rock_project_comment_norm_look.php?pinshentype=project_start';
                }else if(params.type=='project_end'){
                    url = getRootPath()+'/webmain/main/project_comment/rock_project_comment_norm_look.php?pinshentype=project_end';
                }
                js.open(url,900,600);
                $('#mainloaddiv').remove();
            }, 'post,json');
        }else{
            js.ajax(js.getajaxurl('expert_dafen', 'project_comment', 'main'), {pici_id:params.pici_id,mid:params.mid,mtype:params.num}, function(ds) {
                jsonstr=ds.data.model;
                var review_opinion = ds.data.review_opinion;
                var data = jsonstr;
                sessionStorage.setItem("normpreview", data);
                sessionStorage.setItem("review_opinion", review_opinion);
                sessionStorage.setItem("review_opinion_end", ds.data.review_opinion_end);
                sessionStorage.setItem("level_suggest", ds.data.level_suggest);
                sessionStorage.setItem("publish_suggest", ds.data.publish_suggest);
                var url = '';
                if (params.type=='project_start'){
                    url = getRootPath()+'/webmain/main/project_comment/rock_project_comment_norm_look.php?pinshentype=project_start';
                }else if(params.type=='project_end'){
                    url = getRootPath()+'/webmain/main/project_comment/rock_project_comment_norm_look.php?pinshentype=project_end';
                }
                js.open(url,900,600);
                $('#mainloaddiv').remove();
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
            $('#expertlook_tbody').append(html);
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


<!--<div style="width:820px;
margin:0 auto;" ><button type="button" class="btn btn-info" onclick="preview()">查看评分</button></div>-->

<div style="width:820px;margin:auto;padding-top: 20px;text-align: center;" >
<button type="button" class="btn btn-info" style="width: 160px;font-size: 17px;" onclick="preview()">查看评分</button>
</div>
<div class="checkHeader" style="margin: 40px 0 0 0;">附件资料</div>
<table id="expertlook_table" cellpadding="0" cellspacing="0" class="results_table">
    <thead>
    <tr>
        <td>附件类型</td>
        <td>文件名称</td>
        <td>上传状态</td>
        <td>操作</td>
    </tr>
    </thead>
    <tbody id="expertlook_tbody"></tbody>
</table>
<iframe id="ifrID_{rand}" src="" frameBorder="0" width="100%" scrolling="yes" style="display: none;"></iframe>


