<?php

?>


<script type="text/javascript">

    $(function () {
        {params}
        var bgs = '<div id="mainloaddiv" style="width:' + viewwidth + 'px;height:' + viewheight + 'px;overflow:hidden;background:#000000;color:white;filter:Alpha(opacity=20);opacity:0.2;z-index:3;position:absolute;left:0px;line-height:' + viewheight + 'px;top:0px;" align="center"><img src="images/mloading.gif"  align="absmiddle">&nbsp;加载中...</div>';
        $('#indexcontent').append(bgs);
        iframeSrc(params.num, params.mid);
        $("#ifrID_{rand}").load(function () {
            var mainheight = $(this).contents().find("body").height() + 30;
            $(this).height(mainheight);
            $('#mainloaddiv').remove();
        });
    });


    function preview() {
        {params}
       /* var bgs = '<div id="mainloaddiv" style="width:' + viewwidth + 'px;height:' + viewheight + 'px;overflow:hidden;background:#000000;color:white;filter:Alpha(opacity=20);opacity:0.2;z-index:3;position:absolute;left:0px;line-height:' + viewheight + 'px;top:0px;" align="center"><img src="images/mloading.gif"  align="absmiddle">&nbsp;加载中...</div>';
        $('#indexcontent').append(bgs);*/
        js.ajax(js.getajaxurl('getuserpxdfmodel', 'project_comment', 'main'), {
            pici_id: params.pici_id,
            mid: params.mid,
            mtype: params.num
        }, function (ds) {
            sessionStorage.clear();
            sessionStorage.setItem("normpreview", ds.data.model);
            sessionStorage.setItem("review_opinion", ds.data.review_opinion);
            sessionStorage.setItem("review_opinion_end", ds.data.review_opinion_end);
            sessionStorage.setItem("level_suggest", ds.data.level_suggest);
            sessionStorage.setItem("publish_suggest", ds.data.publish_suggest);
             //var url = getRootPath() + '/webmain/main/project_comment/rock_project_comment_submit_wp.php?pici_id=' + params.pici_id + '&mid=' + params.mid + '&mtype=' + params.num;
            var url='';
            if (params.type){
                url = getRootPath()+'/webmain/main/project_comment/rock_project_comment_submit_wp.php?pici_id='+params.pici_id+'&mid='+params.mid+'&mtype='+params.num+'&type='+params.type;
            }else{
                url = getRootPath()+'/webmain/main/project_comment/rock_project_comment_submit_wp.php?pici_id='+params.pici_id+'&mid='+params.mid+'&mtype='+params.num;
            }
            //转向网页的地址;
            var name = '评分';                          //网页名称，可为空;
            var iWidth = 900;                          //弹出窗口的宽度;
            var iHeight = 600;                         //弹出窗口的高度;
            //获得窗口的垂直位置
            var iTop = (window.screen.availHeight - 30 - iHeight) / 2;
            //获得窗口的水平位置
            var iLeft = (window.screen.availWidth - 10 - iWidth) / 2;
            myWindow = window.open(url, name, 'height=' + iHeight + ',,innerHeight=' + iHeight + ',width=' + iWidth + ',innerWidth=' + iWidth + ',top=' + iTop + ',left=' + iLeft + ',status=no,toolbar=no,menubar=no,location=no,resizable=no,scrollbars=0,titlebar=no');
            intHand = setInterval("checkWin()", 30);
            $('#mainloaddiv').remove();
            //2020.10.19修改，将弹窗窗口改为标签页
          //  addtabs({num:'info',url:'main,project_comment,submit_wp,pici_id='+params.pici_id+',mid='+params.mid+',mtype='+params.num+'',icons:'icon-bookmark-empty',name:'项目评分'});
        }, 'post,json');
    }

    function checkWin() {
        if (myWindow != null && myWindow.closed) {
            {params}
            js.ajax(js.getajaxurl('getuseropen', 'project_comment', 'main'), {
                pici_id: params.pici_id,
                mid: params.mid,
                mtype: params.num
            }, function (ds) {
                openck = ds.data;
                if (openck == 0) {

                } else if (openck == 2) {
                    setTimeout(function () {
                        closetabs('edit_norm');
                        expert_list.reload();
                    }, 300);
                }
                clearInterval(intHand);
                intHand = null;
                myWindow = null;
            }, 'post,json');
        }
    }

    function iframeSrc(num, mid) {
        $("#ifrID_{rand}").attr("src", "task.php?a=p&num=" + num + "&mid=" + mid + "&pinshen=word");
    }

</script>


<!--<div style="width:820px;
margin:0 auto;" ><button type="button" class="btn btn-info" onclick="preview()">网评</button></div>-->

<div style="width:820px;margin:auto;padding-top: 20px;text-align: center;">
    <button type="button" class="btn btn-info" style="width: 160px;font-size: 17px;" onclick="preview()">网评</button>
</div>
<iframe id="ifrID_{rand}" src="" frameBorder="0" width="100%" scrolling="yes"></iframe>


