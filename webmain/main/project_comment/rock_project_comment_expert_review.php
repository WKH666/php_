<?php if(!defined('HOST'))die('not access');?>
<script>
    $(document).ready(function(){
        {params}
        var atype=params.atype,zt=params.zt;
        if(!zt)zt='';
        var a = $('#view_{rand}').bootstable({
            tablename:'expert_review',params:{'atype':atype,'zt':zt},fanye:true,
            url:publicstore('{mode}','{dir}'),
            storebeforeaction:'expertreviewbefore',
            columns:[{
                text:'登记号',dataIndex:'secrinum'
            },{
                text:'立项编号',dataIndex:'projectstart_num'
            },{
                text:'课题类型',dataIndex:'keti_type'
            },{
                text:'名称',dataIndex:'name'
            },{
                text:'负责人',dataIndex:'leader'
            },{
                text:'职称',dataIndex:'position'
            },{
                text:'单位',dataIndex:'company'
            },{
                text:'复评结果',dataIndex:'result'
            },{
                text:'成果形式',dataIndex:'achievement_type'
            },{
                text:'资助经费',dataIndex:'funding'
            }],
        });

        var c = {
            reload:function(){
                a.reload();
            },
            search:function(){
                a.setparams({
                    //需搜索的内容
                    secrinum:get('secrinum').value,
                    project_name:get('project_name').value,
                    leader:get('leader').value,
                },true);
            },
            reset:function(){
                a.setparams({
                    //需搜索的内容
                    secrinum:'',
                    project_name:'',
                    leader:'',
                },true);
                get('secrinum').value = '';
                get('project_name').value = '';
                get('leader').value = '';
            },
            init:function(){
                js.ajax(publicmodeurl('projectreview','initdaoru'),{'modenum' : 'projectreview'},function(data){
                },'get,json');
            },

            //保存导入的数据
            saveadd:function(o1){
                let daoruvalreport = window.sessionStorage.getItem('daoruvalreport');
                js.ajax(js.getajaxurl('daorudata','input','flow'),{importcont:daoruvalreport,'modenum':'projectreview'},function(data){
                    if(data.success){
                        try{window['managelist'+'projectreview'+''].reload()}catch(e){}
                        $('#exampleModal').modal('hide');
                        c.reload();
                        sessionStorage.setItem('daoruvalreport','');
                        //查询出所有项目复评是否通过来判断项目申报流程环节是否通过
                        js.ajax(js.getajaxurl('changeBillStatus','project_comment','main'),{},function (data) {
                            if (data.success){
                                  layer.msg(data.msg);
                                     c.reload();
                            }else {
                                layer.msg(data.msg);
                                c.reload();
                            }
                        },'post,json');
                    }
                },'post,json');
            },

            addfile:function(){
                js.upload('_daorufile_excel{rand}',{maxup:'1','title':'选择Excel文件',uptype:'xls|xlsx','urlparams':'noasyn:yes'});
            },

            backup:function(fid){
                var o1 = get('upexcelbtn{rand}');
                o1.value='文件读取中...';
                js.ajax(js.getajaxurl('readxls','input','flow'),{'fileid':fid,'modenum':'projectreview'},function(data){
                    if(data.code == 200){
                        o1.value='读取成功';
                        let readrows = data.rows;
                        o1.disabled=false;
                        window.sessionStorage.removeItem('daoruvalreport');
                        window.sessionStorage.setItem('daoruvalreport',readrows[0]);
                    }else{
                        o1.value='读取失败';
                    }
                },'get,json');
            },
        };

        js.initbtn(c);

        _daorufile_excel{rand}=function(a,xid){
            $('#exampleModal').modal('show');
            var f = a[0];
            c.backup(f.id);
            $("#excel_{rand}").attr('href',f.filepath);
            $("#excel_{rand}").text(f.filename);
        };

        $("#downout").on('click',function(){
            $("#exampleModal").on("show.bs.modal", function() {
                $("#upexcelbtn{rand}").val('上传文件');
                $("#excel_{rand}").text('');
                $("#excel_{rand}").attr('href','');
            });
        });
    });
</script>
<style>
    .report-form{
        background:#F7F7F7;
    }
    .report-form form{
        display: flex;
        flex-direction: row;
        flex-wrap: wrap;
        padding-top: 10px;
    }
    .form-group{
        display: flex;
        flex-direction: row;
        /*margin-right: 15px;*/
    }
    .form-group:nth-last-child(2){
        margin-right: 20px;
    }
    .form-group label{
        width:15rem;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .form-group button{
        margin: 0 10px;
    }
    #search,#downout{
        background: #108EE9;
        color: #fff;
        border-color: #108EE9;
    }
    .tips{
        text-indent: 8em;
    }
    .modal-backdrop{
        z-index:0;
        display: none;
    }
    .modal-header{
        border-bottom: 0px;
    }
    .modal-footer{
        border-top: 0px;
    }
</style>
<div class="report-form">
    <form>
        <div class="form-group">
            <label>登记号:</label>
            <input type="text" class="form-control" id="secrinum" name="secrinum" placeholder="请输入" autocomplete="off">
        </div>
        <div class="form-group">
            <label>项目名称:</label>
            <input type="text" class="form-control" id="project_name" name="project_name" placeholder="请输入" autocomplete="off">
        </div>
        <div class="form-group">
            <label>负责人:</label>
            <input type="text" class="form-control" id="leader" name="leader" placeholder="请输入" autocomplete="off">
        </div>
        <div class="form-group">
            <button type="button" class="btn btn-default" id="search" click="search">搜索</button>
            <button type="reset" class="btn btn-default" id="reset" click="reset">重置</button>
            <button type="button" class="btn btn-default" id="downout" data-toggle="modal" data-target="#exampleModal">导入</button>
        </div>
    </form>
</div>
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="exampleModalLabel" style="text-align: center;">导入数据</h4>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group" style="display: flex;flex-direction: row;flex-wrap: wrap;align-items: center;">
                        <label for="recipient-name" class="control-label">数据文档:</label>
                        <input type="button" id="upexcelbtn{rand}" click="addfile" class="btn btn-default" value="上传文件"/>&nbsp;&nbsp;
                        <a id="excel_{rand}" href="" target="_blank"></a>
                    </div>
                    <p class="tips">支持扩展名：.xlsx.xls的文件</p>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" click="saveadd">保存</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
            </div>
        </div>
    </div>
</div>
<div class="blank10"></div>
<div id="view_{rand}"></div>

