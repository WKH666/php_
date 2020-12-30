<?php defined('HOST') or die('not access');?>
<script >
$(document).ready(function(){
	{params}
	let request_id = params.request_id;
	var c={
		init:function(){
            js.ajax(js.getajaxurl('base_info','fwork','main'),{request_id : request_id},function (data) {
                if (data['rows'][0].change_type==0){
                    data['rows'][0].change_type='变更项目负责人';
                }if (data['rows'][0].change_type==1){
                    data['rows'][0].change_type= '变更或增加课题组成员';
                }if (data['rows'][0].change_type==2){
                    data['rows'][0].change_type='变更项目管理单位';
                }if (data['rows'][0].change_type==3){
                    data['rows'][0].change_type='改变成果形式  ';
                }if (data['rows'][0].change_type==4){
                    data['rows'][0].change_type='改变项目名称';
                }if (data['rows'][0].change_type==5){
                    data['rows'][0].change_type='研究内容有重大调整';
                }if (data['rows'][0].change_type==6){
                    data['rows'][0].change_type='延期';
                }if (data['rows'][0].change_type==7){
                    data['rows'][0].change_type='撤项';
                }if (data['rows'][0].change_type==8){
                    data['rows'][0].change_type='其他';
                }
                c.initshow(data);

            },'post,json');
		},
		initshow:function(data){
            $("#course_name").val(data['rows'][0].course_name);
            $("#change_type").val(data['rows'][0].change_type);
            $("#change_remark").val(data['rows'][0].change_remark);
		}
	};
    js.initbtn(c);
    c.init();

    $('#attached_info').bootstable({
        url: js.getajaxurl('attached_info', 'fwork', 'main', {}),
        params: {request_id : request_id},
        storeafteraction: 'attached_infoafter',
        isshownumber:false,
        columns: [{
            text: '附件类型', dataIndex: 'upload_filetype'
        }, {
            text: '文件名称', dataIndex: 'filename'
        }, {
            text: '上传状态', dataIndex: 'upload_status'
        }, {
            text: '操作', dataIndex: 'caoz'
        }],
    });
    /*下载文件*/
    filedownload = function(file_id,l,p){
        if(js.isimg(l)){
            $.imgview({url:p});
        }else{
            js.downshow(file_id)
        }
    };

    $('#addit_records').bootstable({
        params: {request_id : request_id},
        url: js.getajaxurl('addit_records', 'fwork', 'main', {}),
        storeafteraction: 'addit_recordsafter',
        isshownumber:false,
        columns: [{
            text: '审核人', dataIndex: 'name'
        }, {
            text: '审核状态', dataIndex: 'audit_result'
        }, {
            text: '审核意见', dataIndex: 'audit_opinion'
        }, {
            text: '审核时间', dataIndex: 'audit_time'
        }],
    });
});
function check_book() {
    {params}
    let request_id = params.request_id;
    js.ajax(js.getajaxurl('base_info','fwork','main'),{request_id : request_id},function (data) {
    var table=data['rows'][0].table;
    var mid=data['rows'][0].mid;
    var tit = '查看申报书';
    var url1='http://www.sheke.com/task.php?a=p&num=';
    var num1=url1+table;
    var num2='&mid='+mid;
    var url=num1+num2;
    //var url = 'http://www.sheke.com/?a=lu&m=input&d=flow&num=project_researchbase&mid=11';
    var mxw= 900;
    var hm = winHb()-150;if(hm>800)hm=800;if(hm<400)hm=400;
    if(url.indexOf('wintype=max')>0){
        mxw= 1100;
        hm=winHb()-45;
    }
    var wi = winWb()-150;if(wi>mxw)wi=mxw;if(wi<700)wi=700;
    js.tanbody('winiframe',tit,wi,410,{
        html:'<div style="height:'+hm+'px;overflow:hidden"><iframe src="" name="openinputiframe" width="100%" height="100%" frameborder="0"></iframe></div>',
        bbar:'none'
    });
    openinputiframe.location.href=url;
    return false;
},'post,json');
}
</script>

<style>
    .three_columns{
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
    }
    .form-group{
        display: flex;
        flex-direction: row;
    }
    .form-group label{
        display: flex;
        justify-content: center;
        align-items: center;
        margin: 0;
        padding: 0;
    }
    .three_columns .form-group label{
        width: 13rem;
    }
    .one_columns .form-group label{
        width: 11rem;
    }
    .one_columns .form-group textarea{
        height: 10rem;
    }
    .header_title{
        background: #CDE3F1;
        border-radius: 5px;
    }
    .header_title p{
        padding: 5px 0;
    }
    .header_title:nth-of-type(2) p{
        margin: 0;
    }
    #results_table{
        width:100%;
    }
    #results_table thead,tbody tr td{
        height: 30px;
    }
    #results_table thead tr td{
        text-align: center;
        background: #F2F2F2;
    }
    #results_table tbody{}
    #results_table tbody tr td{
        text-align: center;
        border: 1px solid #d1d1d1;
        box-sizing: border-box;
    }
    #results_table tbody tr td:nth-of-type(1){
        text-align: left;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
    }
</style>
<div>
    <div class="header_title">
        <p>基础信息</p>
    </div>
    <form class="one_columns">
        <div class="form-group">
            <label>选择项目:</label>
            <input type="text" class="form-control" readonly id="course_name">
            <button type="button" class="btn btn-default" onclick="check_book()">查看申报书</button>
        </div>
    </form>
    <form class="one_columns">
        <div class="form-group">
            <label>变更类型:</label>
            <input type="text" class="form-control" readonly id="change_type">
        </div>
    </form>
    <form class="one_columns">
        <div class="form-group">
            <label>变更说明:</label>
            <textarea class="form-control" readonly id="change_remark"></textarea>
        </div>
    </form>
    <div class="header_title">
        <p>附件资料</p>
        <div id="attached_info"></div>
    </div>


    <div class="header_title">
        <p>审核记录</p>
        <div id="addit_records"></div>
    </div>

</div>
