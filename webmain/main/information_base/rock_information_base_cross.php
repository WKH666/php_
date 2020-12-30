<?php if(!defined('HOST'))die('not access');?>
<script>
    $(document).ready(function(){
        {params}
        var atype=params.atype,zt=params.zt;
        if(!zt)zt='';
        var a = $('#view_{rand}').bootstable({
            tablename:'item_query',params:{'atype':atype,'zt':zt},fanye:true,
            url:publicstore('{mode}','{dir}'),
            storeafteraction:'inforcrossafter',storebeforeaction:'inforcrossbefore',
            columns:[{
                text:'类型',dataIndex:'type'
            },{
                text:'年度',dataIndex:'all_year',sortable:true
            },{
                text:'专家账号',dataIndex:'u_mobile'
            },{
                text:'项目负责人',dataIndex:'project_controller'
            },{
                text:'所在单位',dataIndex:'location_unit'
            },{
                text:'项目类别',dataIndex:'pile_sorts'
            },{
                text:'项目名称',dataIndex:'project_name'
            },{
                text:'经费/万元',dataIndex:'money'
            },{
                text:'预计完成时间',dataIndex:'expected_time',sortable:true
            },{
                text:'操作',dataIndex:'caoz'
            }],
        });

        var c = {
            reload:function(){
                a.reload();
            },
            search:function(){
                a.setparams({
                    //需搜索的内容
                    type:get('type').value,
                    all_year:get('year').value,
                    project_controller:get('project_head').value,
                    location_unit:get('unit').value,
                    pile_sorts:get('project_type').value,
                    project_name:get('project_name').value,
                    expected_time:get('completion_time').value,
                },true);
            },
            daoru:function(){
                // managelistinforcross = a;
                // addtabs({num:'daoruinforcross',url:'flow,input,daoru,modenum=inforcross',icons:'plus',name:'导入纵/横项目信息'});
            },
            init:function(){
                js.ajax(publicmodeurl('inforcross','initdaoru'),{'modenum' : 'inforcross'},function(data){
                },'get,json');
            },
            saveadd:function(o1){
                let daoruvalcross = window.sessionStorage.getItem('daoruvalcross');
                js.ajax(js.getajaxurl('daorudata','input','flow'),{importcont:daoruvalcross,'modenum':'inforcross'},function(data){
                    if(data.success){
                        try{window['managelist'+'inforcross'+''].reload()}catch(e){}
                        $('#exampleModal').modal('hide');
                        c.reload();
                    }
                },'post,json');
            },
            addfile:function(){
                js.upload('_daorufile_excel{rand}',{maxup:'1','title':'选择Excel文件',uptype:'xls|xlsx','urlparams':'noasyn:yes'});
            },
            backup:function(fid){
                var o1 = get('upexcelbtn{rand}');
                o1.value='文件读取中...';
                js.ajax(js.getajaxurl('readxls','input','flow'),{'fileid':fid,'modenum':'inforcross'},function(data){
                    if(data.code == 200){
                        o1.value='读取成功';
                        let readrows = data.rows;
                        o1.disabled=false;
                        window.sessionStorage.removeItem('daoruvalcross');
                        window.sessionStorage.setItem('daoruvalcross',readrows[0]);
                    }else{
                        o1.value='读取失败';
                    }
                },'get,json');
            },
        };
        js.initbtn(c);
        _daorufile_excel{rand}=function(a,xid){
            console.log(a);
            $('#exampleModal').modal('show');
            var f = a[0];
            c.backup(f.id);
            $("#excel_{rand}").attr('href',f.filepath);
            $("#excel_{rand}").text(f.filename);
        }
        inforcrossdel = function(current_row,current_index){
            js.ajax(js.getajaxurl('delcross','information_base','main'),{current_index : current_index},function(data){
                if(data.code == 200){
                    layer.msg(data.msg);
                    c.reload();
                }else{
                    layer.msg(data.msg);
                }
            },'post,json');
        }
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
    .cross-form{
        background:#F7F7F7;
    }
    .cross-form form{
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
<div class="cross-form">
    <form>
        <div class="form-group">
            <label>类型:</label>
            <input type="text" class="form-control" id="type" name="type" placeholder="请输入" autocomplete="off">
        </div>
        <div class="form-group">
            <label>年度:</label>
            <input type="text" class="form-control" id="year" name="year" placeholder="请输入" autocomplete="off">
        </div>
        <div class="form-group">
            <label>所在单位:</label>
            <input type="text" class="form-control" id="unit" name="unit" placeholder="请输入" autocomplete="off">
        </div>
        <div class="form-group">
            <label>项目负责人:</label>
            <input type="text" class="form-control" id="project_head" name="project_head" placeholder="请输入" autocomplete="off">
        </div>
        <div class="form-group">
            <label>项目类别:</label>
            <input type="text" class="form-control" id="project_type" name="project_type" placeholder="请输入" autocomplete="off">
        </div>
        <div class="form-group">
            <label>项目名称:</label>
            <input type="text" class="form-control" id="project_name" name="project_name" placeholder="请输入" autocomplete="off">
        </div>
        <div class="form-group">
            <label>预计完成时间:</label>
            <input type="text" class="form-control" id="completion_time" name="completion_time" placeholder="请输入" autocomplete="off">
        </div>
        <div class="form-group">
            <button type="button" class="btn btn-default" id="search" click="search">搜索</button>
            <button type="reset" class="btn btn-default" id="reset">重置</button>
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
                        <input type="button" id="upexcelbtn{rand}" click="addfile" class="btn btn-default" value="上传文件">&nbsp;&nbsp;
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