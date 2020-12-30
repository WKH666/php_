<?php if(!defined('HOST'))die('not access');?>
<script>
    $(document).ready(function(){
        {params}
        var atype=params.atype,zt=params.zt;
        if(!zt)zt='';
        var a = $('#view_{rand}').bootstable({
            tablename:'expert_info',params:{'atype':atype,'zt':zt},fanye:true,
            // url:publicstore('{mode}','{dir}'),
            url:js.getajaxurl('expertinfo','expert_manage','main', {}),
            storeafteraction:'expertinfoafter',
            columns:[{
                text:'账号',dataIndex:'mobile'
            },{
                text:'专家姓名',dataIndex:'name'
            },{
                text:'研究方向',dataIndex:'research_direction'
            },{
                text:'职务/职称',dataIndex:'position'
            },{
                text:'单位',dataIndex:'company'
            },{
                text:'添加时间',dataIndex:'add_time',sortable:true
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
                    name:$('#name').val(),
                    research_direction:$('#research_direction').val(),
                    position:$('#position').val(),
                    company:$('#company').val(),
                },true);
            },
            reset:function(){
                $("#name").val('');
                $("#research_direction").val('');
                $("#position").val('');
                $("#company").val('');
                a.setparams({
                    //需搜索的内容
                    name:'',
                    research_direction:'',
                    position:'',
                    company:'',
                },true);
            },
            // daochu:function(){
            //     console.log(nowtabs.name);
            //     a.exceldown(nowtabs.name);
            // },
            exceldown:function(){
                //a.exceldown();
                var das = a._loaddata(1, true);
                //console.log(das);return;
                das.limit = 2000;
                das.execldown 	= 'true';
                das.exceltitle	= jm.encrypt('专家信息');
                excelfields = ',mobile,name,research_direction,position,company,add_time';
                excelheader = ',账号,专家姓名,研究方向,职务/职称,单位,添加时间';
                das.excelfields = jm.encrypt(excelfields.substr(1));
                das.excelheader = jm.encrypt(excelheader.substr(1));
                $.ajax({
                    url:'index.php?a=publicstore&m=expert_manage&d=main&ajaxbool=true&rnd=0.08724030972968988',type:'POST',data:das,dataType:'json',
                    success:function(a1){
                        js.msg('success', '处理成功，共有记录'+a1.totalCount+'条/导出'+a1.downCount+'条，点我直接<a class="a" href="'+a1.url+'" target="_blank">[下载]</a>', 60);
                    },
                    error:function(e){
                        js.msg('msg','err:'+e.responseText);
                    }
                });
            },
        };
        js.initbtn(c);
        expertinfocheck = function(expert_id){
            var results_url = 'flow,input,expert_check,modenum=expertresults,expert_id=' + expert_id;
            addtabs({
                num:'expertresults',
                url:results_url,
                icons:'',
                name:'查看专家库信息'
            });
            return false;
        };
        expertinfoedit = function(expert_id){
            var results_url = 'flow,input,expert_edit,modenum=expertresults,expert_id=' + expert_id;
            addtabs({
                num:'expertresults',
                url:results_url,
                icons:'',
                name:'编辑专家库信息'
            });
            return false;
        }
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
            <label>专家姓名:</label>
            <input type="text" class="form-control" id="name"  placeholder="请输入">
        </div>
        <div class="form-group">
            <label>研究方向:</label>
            <input type="text" class="form-control" id="research_direction"  placeholder="请输入">
        </div>
        <div class="form-group">
            <label>职务/职称:</label>
            <input type="text" class="form-control" id="position"  placeholder="请输入">
        </div>
        <div class="form-group">
            <label>单位:</label>
            <input type="text" class="form-control" id="company"  placeholder="请输入">
        </div>
        <div class="form-group">
            <button type="button" class="btn btn-default" id="search" click="search">搜索</button>
            <button type="reset" class="btn btn-default" id="reset">重置</button>
            <button type="button" class="btn btn-default" id="downout" click="exceldown">导出</button>
        </div>
    </form>
</div>
<div class="blank10"></div>
<div id="view_{rand}"></div>