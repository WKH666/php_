<?php defined('HOST') or die('not access'); ?>
<script>
    var b_project =null;
    $(document).ready(function () {
        {params}
        let pici_id = params.pici_id;
        let user_id = params.user_id;
        b_project = $('#view_{rand}').bootstable({
            url: js.getajaxurl('project_info', 'expert_manage', 'main', {}),
            params: {'pici_id': pici_id,'user_id':user_id},
            storeafteraction :"project_infoafter",
            tablename: 'm_pxmdf', fanye: true,
            columns: [{
                text: '项目名称', dataIndex: 'course_name'
            }, {
                text: '申报类型', dataIndex: 'modename'
            }, {
                text: '负责人', dataIndex: 'u_name'
            }, {
                text: '所属批次', dataIndex: 'pici_name'
            }, {
                text: '评审类型', dataIndex: 'mtype'
            }, {
                text: '评审分数', dataIndex: 'user_zongfen'
            }, {
                text: '操作', dataIndex: 'caoz'
            }],
        });

        // var c = {
        //     reload: function () {
        //         a.reload();
        //     },
        //     search: function () {
        //         a.setparams({
        //             //需搜索的内容
        //             award_time: get('award_time').value,
        //             winning_unit: get('winning_unit').value,
        //             prize: get('prize').value,
        //         }, true);
        //     },
        // };
        // js.initbtn(c);
    });

    function project_search() {
        b_project.setparams({
            //需搜索的内容
            pici_num: get('pici_num').value,
            course_name: get('course_name').value,
            modename: get('modename').value,
        }, true);
    }



    //查看
    //piciid:m_batch表id
    //num:项目的申报类型（例：‘课题申报’=》‘project_coursetask）
    //mid:项目的id对应flow_bill的mid
    function look(pici_id,num,mid,mtype,uid){
        if(get('tabs_look_norm')) closetabs('look_norm');
        addtabs({num:'look_norm',url:'main,project_comment,expert_look,pici_id='+pici_id+',num='+num+',mid='+mid+',type='+mtype+',uid='+uid,icons:'icon-bookmark-empty',name:'项目详情'});
        thechangetabs('look_norm');
    }

</script>

<style>
    .cross-form {
        background: #F7F7F7;
    }

    .cross-form form {
        display: flex;
        flex-direction: row;
        flex-wrap: wrap;
        padding-top: 10px;
    }

    .form-group {
        display: flex;
        flex-direction: row;
        /*margin-right: 15px;*/
    }

    .form-group:nth-last-child(2) {
        margin-right: 20px;
    }

    .form-group label {
        width: 15rem;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .form-group button {
        margin: 0 10px;
    }

    #search, #downout {
        background: #108EE9;
        color: #fff;
        border-color: #108EE9;
    }

    .tips {
        text-indent: 8em;
    }

    .modal-backdrop {
        z-index: 0;
        display: none;
    }

    .modal-header {
        border-bottom: 0px;
    }

    .modal-footer {
        border-top: 0px;
    }
</style>
<div class="cross-form">
    <form>
        <div class="form-group">
            <label>登记号:</label>
            <input type="text" class="form-control" id="pici_num" placeholder="请输入">
        </div>
        <div class="form-group">
            <label>项目名称:</label>
            <input type="text" class="form-control" id="course_name" placeholder="请输入">
        </div>
        <div class="form-group">
            <label>申报类型:</label>
            <input type="text" class="form-control" id="modename" placeholder="请输入">
        </div>
        <div class="form-group">
            <button type="button" class="btn btn-default" id="search" onclick="project_search()">搜索</button>
            <button type="reset" class="btn btn-default" id="reset">重置</button>
        </div>
    </form>
</div>
<div class="blank10"></div>
<div id="view_{rand}"></div>
