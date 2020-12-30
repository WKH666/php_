<?php if(!defined('HOST'))die('not access');?>

<style>
    .fabu-div{
        width: 200px;
        height: 128px;
        display: flex;
        align-items: center;
        justify-content: center;
        float: left;
        margin: 0px 15px 10px 0px;
        color:black;
        font-size: 13px;
        background-image:url(./images/fabu_biankuang.png);
        background-size:cover
    }
</style>
<div style="margin-top: 10px" id="fabu_notice">
    <a href='#' class='fabu' data-fabu='kt_lx'><div class="fabu-div">课题申报立项通知书</div></a>
    <a href='#' class='fabu' data-fabu='kt_jx'><div class="fabu-div">课题申报结项通知书</div></a>
    <a href='#' class='fabu' data-fabu='kt_bzyq'><div class="fabu-div">课题申报成果编制要求</div></a>
    <a href='#' class='fabu' data-fabu='pjy_rx'><div class="fabu-div">普及月申报入选通知书</div></a>
    <a href='#' class='fabu' data-fabu='cth_rx'><div class="fabu-div">常态化申报入选通知书</div></a>
    <a href='#' class='fabu' data-fabu='yjjd_lx'><div class="fabu-div">研究基地立项通知书</div></a>
    <a href='#' class='fabu' data-fabu='hqrd_jx'><div class="fabu-div">后期认定结项通知书</div></a>
</div>

<script>
    //点击加载发布页面  注意：notice是后台菜单编辑的编号
    $('#fabu_notice').on('click', '.fabu', function () {
        var type = $(this).data('fabu');
        var typename = $(this).text();
        /*11/05修改 start*/
       /* $.ajax({
            url:'?m=index&a=getshtml&surl='+jm.base64encode('main/notice/rock_notice_fabu')+'',
            type:'get',
            success: function(da){
                $('#mainloaddiv').remove();
                var s = da;
                /!*s = s.replace(/\{rand\}/gi, rand);
                s = s.replace(/\{adminid\}/gi, adminid);
                s = s.replace(/\{adminname\}/gi, adminname);
                s = s.replace(/\{mode\}/gi, mode);
                s = s.replace(/\{dir\}/gi, dir);
                s = s.replace(/\{params\}/gi, "var params={"+urlpms+"};");*!/
                s = s.replace(/\{type\}/gi, type);
                var obja = $('#content_notice');
                obja.html(s);
            },
            error:function(){
                $('#mainloaddiv').remove();
                var s = 'Error:加载出错喽,'+url+'';
                $('#content_'+num+'').html(s);
            }
        });*/
        addtabs({num: 'fabu_p', url: 'main,notice,fabu,type='+type, icons: 'icon-bookmark-empty', name: '发布/编辑'+typename});
        /*end*/
    })
</script>
