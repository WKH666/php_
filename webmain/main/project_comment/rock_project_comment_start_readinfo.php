<?php if(!defined('HOST'))die('not access');?>
<script >
    var a = '';
    var pinshenType = '';
    $(document).ready(function(){
        {params}
        var pici_id = params.pici_id;
        var xid = params.xid;
              pinshenType = params.pinshentype;//评审类型:project_start:立项评审,project_end:结项评审
            a = $('#view_{rand}').bootstable({
                url:js.getajaxurl('expert_score','project_comment','main',{pici_id:pici_id,xid:xid,pinshenType:'project_start'}),
                fanye:true,modename:'网评列表',
                celleditor:true,
                columns:[{
                    text:'专家',dataIndex:'name'
                },{
                    text:'指标评分',dataIndex:'user_zongfen'
                },{
                    text:'评审意见',dataIndex:'review_opinion',width:'280px'
                },{
                    text:'评审时间',dataIndex:'operating_time'
                },{
                    text:'状态',dataIndex:'status'
                },{
                    text:'操作',dataIndex:'caoz',width:'100px'
                }],
            });

        var c = {
            del:function(){
                a.del();
            },
            reload:function(){
                a.reload();
            },
            search:function(){
                a.setparams({
                    //mtype:get('mtype_{rand}').value,
                },true);
            },
            looknorm:function(){
                addtabs({num:'look_norm',url:'main,project_comment,norm_look',icons:'icon-bookmark-empty',name:'查看'});
            }
        };
        js.initbtn(c);
    });

    var pici_ids = '';
    var uids ='';
    var xids = '';
    //扣罚弹窗
    function penalty_start_func(pici_id,uid,xid) {
        $('#penaltyModal').modal('show');
        pici_ids = pici_id;
        uids = uid;
        xids= xid;
    }

    //发送扣罚请求
    function send_penalty_start() {
            var penalty_reason  = $('#textareas').val();
            js.ajax(js.getajaxurl('savepenalty', 'project_comment', 'main'), {pici_id:pici_ids,uid:uids,xid:xids,penalty_reason:penalty_reason}, function(ds) {
            if(ds.success==true){
                $('#penaltyModal').modal('hide');
                    layer.msg(ds.msg);
            }else {
                layer.msg(ds.msg);
            }
        }, 'post,json');
    }

    //已提交人的指标信息
    function preview_start(pici_id,mid,mtype,uid) {
        js.ajax(js.getajaxurl('expert_dafen', 'project_comment', 'main'), {pici_id:pici_id,mid:mid,mtype:mtype,uid:uid}, function(ds) {
            sessionStorage.setItem("normpreview", ds.data.model);
            sessionStorage.setItem("review_opinion", ds.data.review_opinion);
            sessionStorage.setItem("review_opinion_end", ds.data.review_opinion_end);
            sessionStorage.setItem("level_suggest", ds.data.level_suggest);
            sessionStorage.setItem("publish_suggest", ds.data.publish_suggest);
            var url = getRootPath()+'/webmain/main/project_comment/rock_project_comment_norm_look.php?pinshentype=project_start';
           js.open(url,900,600);
        }, 'post,json');
    }
    //未提交人的指标信息
    function look_start(norm_id) {
        var url= getRootPath()+'/webmain/main/project_comment/rock_project_comment_norm_look.php?pinshentype=project_start&norm_id='+norm_id;
        js.open(url,900,600);
    }

    //重写tabs改变事件
    function thechangetabs(num){
        $("div[temp='content']").hide();
        $("[temp='tabs']").removeClass();
        var bo = false;
        if(get('content_'+num+'')){
            $('#content_'+num+'').show();
            $('#tabs_'+num+'').addClass('accive');
            nowtabs = tabsarr[num];
        }
        opentabs.push(num);
        _changhhhsv(num);
    }

</script>

<style type="text/css">
    .serachPanel{
        display: block;
        padding-left: 1%;
        /*min-width: 700px;*/
    }
    .serachPanel .searchAc1{
        display: inline-block;
        /*width: 17%;
        min-width: 271px;*/
    }
    .serachPanel .searchAc1 ul li{
        float: left;
        height: 40px;
        line-height: 33px;
        /*padding: 0% 1%;*/
        padding-right: 10px;

    }

    .serachPanel .searchAPanel{
        position: relative;
    }
    .searchAc{
        text-align: center;
        margin-top: 5px;
        margin-bottom: 10px;
    }
    .searchAc a{
        font-size: 12px;
        color: #555555;
    }
    .selSearch {
        height: 32px;
        line-height: 32px;
    }
    .form-control{
        height: 32px;
        line-height: 32px;
    }
    .btn-default{
        padding: 5px 12px;
    }
    .tTabc ul li{
        width: 100%;
        text-align: center;
        border: 1px solid #eee;
        padding-bottom: 2%;
        margin-top: 2%;
    }
    .tTabc ul li span{
        display: block;
        text-align: center;
        font-size: 20px;
        /*margin: 1% 0%;*/
    }
    .searchAc1  .stateContent{
        display: inline-block;
        /*width: 90px;
        text-indent: 20%;*/
    }



    /*流程*/
    .processDe{
        background-color: #419af1;
        color: #fff;
        display: inline-block;
        font-size: 20px;
        padding: 3% 0%;
        width: 12%;
        text-align: center;
        border-radius: 5px;
    }
    .bgG{
        background-color: #848484 !important;
    }
    .processImg{
        color: #fff;
        display: inline-block;
        font-size: 20px;
        padding: 2% 4%;
        width: 10%;
        text-align: center;
    }
    .processImg img{
        text-align: center;
        width: auto;
        max-width: 100%;
    }
    #layerhtml{
        font-size: 24px;
        display: table-cell;
        vertical-align: middle;
    }
    .layui-layer-content{
        display: table;
        width: 100%;
        padding: 0% 5%;
    }
    a[name="shenbao"]{
        color: #428bca !important;
        cursor: pointer;
    }

    /*返回上一级a标签的样式*/
    .callbackone{
        position: absolute;
        display: block;
        right: 5.8%;
        bottom: 5%;
        font-size: 15px;
    }
</style>


<div id="view_{rand}" style="margin-top: 20px"></div>

<!--扣罚弹窗-->
<div class="modal fade" id="penaltyModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog  modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">扣罚操作</h4>
            </div>
            <div class="modal-body">
                <textarea placeholder="请输入扣罚原因" style="height: 150px;width: 100%" id="textareas"></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="send_penalty_start()">保存</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
            </div>
        </div>
    </div>
</div>

