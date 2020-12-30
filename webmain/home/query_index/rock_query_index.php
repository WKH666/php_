<?php if(!defined('HOST'))die('not access');?>
<script>
$(document).ready(function(){

});
function openresults(){
    addtabs({num:'results',url:'main,query_information_base,results',name:'成果信息',hideclose:true});
    return false;
}
function opencross(){
    addtabs({num:'cross',url:'main,query_information_base,cross',name:'纵/横项目信息',hideclose:true});
    return false;
}
function openreport(){
    addtabs({num:'report',url:'main,query_information_base,report',name:'论文发表信息',hideclose:true});
    return false;
}
function openprize(){
    addtabs({num:'prize',url:'main,query_information_base,prize',name:'获奖信息',hideclose:true});
    return false;
}
$("#query_index_btngroup").on('click','.query_index_btnitem',function(){
    var target_id = $(this)[0].id;
    for(var i = 0; i <$(".topmenubg span").length; i++){
        $("span")[i].className = '';
    }
    switch (target_id){
        case 'div_chengguo':
            $("#span_chengguo")[0].className = 'spanactive';
            break;
        case 'div_zongheng':
            $("#span_zongheng")[0].className = 'spanactive';
            break;
        case 'div_lunwen':
            $("#span_lunwen")[0].className = 'spanactive';
            break;
        case 'div_huojiang':
            $("#span_huojiang")[0].className = 'spanactive';
            break;
    }
});
$("#span_xinxiku")[0].className = 'spanactive';
</script>
<style>
    #query_index{
        display: flex;
        justify-content: center;
        align-items: center;
        flex-wrap: wrap;
        text-align: center;
        height: 100%;
    }
    #query_index p{
        text-indent: 0;
    }
    #query_index_title{
        font-size: 60px;
        margin: 0;
    }
    #query_index_btngroup{
        display: flex;
        flex-direction: row;
        justify-content: center;
        align-items: center;
        flex-wrap: wrap;
        font-size: 24px;
    }
    .query_index_btnitem{
        margin: 20px;
        padding: 30px;
        background: #d1d1d1;
        width: 350px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }
    .query_index_btnitem i{
        color:#14ADC4;
    }
    .query_index_btnitem i:before{
        font-size: 80px;
    }
</style>
<div id="query_index">
    <p id="query_index_title">欢迎使用珠海社科网<br/>信息库查询系统</p>
    <div id="query_index_btngroup">
        <div class="query_index_btnitem" id="div_chengguo" onclick="openresults()"><i class="glyphicon glyphicon-search"></i><p>成果信息查询</p></div>
        <div class="query_index_btnitem" id="div_zongheng" onclick="opencross()"><i class="glyphicon glyphicon-search"></i><p>纵横项目查询</p></div>
        <div class="query_index_btnitem" id="div_lunwen" onclick="openreport()"><i class="glyphicon glyphicon-search"></i><p>论文发表查询</p></div>
        <div class="query_index_btnitem" id="div_huojiang" onclick="openprize()"><i class="glyphicon glyphicon-search"></i><p>获奖信息查询</p></div>
    </div>
</div>


