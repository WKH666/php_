<?php if(!defined('HOST'))die('not access');?>
<script>
    $(document).ready(function(){
        {params}
        var atype=params.atype,zt=params.zt;
        if(!zt)zt='';
        var a = $('#view_{rand}').bootstable({
            tablename:'award_query',params:{'atype':atype,'zt':zt},fanye:true,
            url:publicstore('{mode}','{dir}'),
            storeafteraction:'inforprizeafter',storebeforeaction:'inforprizebefore',
            columns:[{
                text:'时间',dataIndex:'award_time',sortable:true
            },{
                text:'专家账号',dataIndex:'u_mobile'
            },{
                text:'获奖者',dataIndex:'winner'
            },{
                text:'获奖单位',dataIndex:'winning_unit'
            },{
                text:'奖项',dataIndex:'prize'
            },{
                text:'奖项内容',dataIndex:'prize_content'
            },{
                text:'颁发机构',dataIndex:'issuing_authority'
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
                    award_time:get('datetime').value,
                    winner:get('winner').value,
                    winning_unit:get('unit').value,
                    prize:get('award').value,
                    prize_content:get('award_content').value,
                },true);
            },
            daoru:function(){
                // managelistinforprize = a;
                // addtabs({num:'daoruinforprize',url:'flow,input,daoru,modenum=inforprize',icons:'plus',name:'导入获奖信息'});
            },
            init:function(){
                js.ajax(publicmodeurl('inforprize','initdaoru'),{'modenum' : 'inforprize'},function(data){
                },'get,json');
            },
            saveadd:function(o1){
                let daoruvalprize = window.sessionStorage.getItem('daoruvalprize');
                js.ajax(js.getajaxurl('daorudata','input','flow'),{importcont:daoruvalprize,'modenum':'inforprize'},function(data){
                    if(data.success){
                        try{window['managelist'+'inforprize'+''].reload()}catch(e){}
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
                js.ajax(js.getajaxurl('readxls','input','flow'),{'fileid':fid,'modenum':'inforprize'},function(data){
                    if(data.code == 200){
                        o1.value='读取成功';
                        let readrows = data.rows;
                        o1.disabled=false;
                        window.sessionStorage.removeItem('daoruvalprize');
                        window.sessionStorage.setItem('daoruvalprize',readrows[0]);
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
        }
        inforprizedel = function(current_row,current_index){
            js.ajax(js.getajaxurl('delprize','information_base','main'),{current_index : current_index},function(data){
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
    .prize-form{
        background:#F7F7F7;
    }
    .prize-form form{
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
    #search,#downru{
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
<div class="prize-form">
    <form>
        <div class="form-group">
            <label>时间:</label>
            <input type="text" class="form-control" id="datetime" name="datetime" placeholder="请输入" autocomplete="off">
        </div>
        <div class="form-group">
            <label>所在单位:</label>
            <input type="text" class="form-control" id="unit" name="unit" placeholder="请输入" autocomplete="off">
        </div>
        <div class="form-group">
            <label>获奖者:</label>
            <input type="text" class="form-control" id="winner" name="winner" placeholder="请输入" autocomplete="off">
        </div>
        <div class="form-group">
            <label>奖项:</label>
            <input type="text" class="form-control" id="award" name="award" placeholder="请输入" autocomplete="off">
        </div>
        <div class="form-group">
            <label>获奖内容:</label>
            <input type="text" class="form-control" id="award_content" name="award_content" placeholder="请输入" autocomplete="off">
        </div>
        <div class="form-group">
            <button type="button" class="btn btn-default" id="search">搜索</button>
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