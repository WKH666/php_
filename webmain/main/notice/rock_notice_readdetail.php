<?php if (!defined('HOST')) die('not access'); ?>

<style>
    .enterprise_info_head {
        background-color: #B0DAF8;
        height: 35px;
        border-radius: 5px;
        margin-bottom: 30px;
        line-height: 35px;
        margin-top: 20px;
    }

    .enterprise_info_head p {
        margin: 0px;
        font-weight: unset;
    }

    .flow_fileDiv {
        height: 35px;
        margin-bottom: 30px;
        line-height: 35px;
        margin-top: 20px;
    }

    .flow_fileDiv a {
        background-color: #e6f3fc;
        padding: 5px 0px 5px 10px;
        font-size: 14px;
    }

    .flow_fileDiv a:nth-child(1) {
        text-decoration: none;
    }

    .flow_fileDiv a:nth-child(2) {
        padding-right: 60px;
    }


</style>

<form id="add-form" class="form-horizontal nice-validator n-default n-bootstrap" role="form" data-toggle="validator"
      method="POST" action="" novalidate="novalidate">
    <input type="hidden" id="type_" name="type" value="">
    <div class="col-sm-12 enterprise_info_head">
        <p>基础信息</p>
    </div>

    <div class="enterprise_info col-sm-12">
        <div class="form-group col-sm-6">
            <label class="control-label col-xs-12 col-sm-4">发送标题:</label>
            <div class="col-xs-12 col-sm-8">
                <input data-rule="required" id="title_{rand}" class="form-control  gray_color" name="title" type="text"
                       value="" readonly>
            </div>
        </div>
    </div>
    <div class="enterprise_info col-sm-12">
        <div class="form-group col-sm-6">
            <label class="control-label col-xs-12 col-sm-4">发送说明:</label>
            <div class="col-xs-12 col-sm-8">
                <textarea class="form-control" id="remark_{rand}" name="remark" rows="3" readonly></textarea>
            </div>
        </div>
    </div>
    <div class="enterprise_info col-sm-12">
        <div class="form-group col-sm-6">
            <label class="control-label col-xs-12 col-sm-4">同步邮件:</label>
            <div class="col-xs-12 col-sm-8">
                <label class="radio-inline">
                    <input type="radio" name="is_mail" id="open" value="1" checked> <span id="radio_value">是</span>
                </label>
            </div>
        </div>
    </div>

    <div class="col-sm-12 enterprise_info_head">
        <p>流程附件</p>
    </div>
    <div class="flow_fileDiv col-sm-12">
        <a><span id="flow_fileName">流程附件</span><a href="" id="down">下载</a></a>
    </div>

    <div class="lxtongzhiDiv">
        <div class="col-sm-12 enterprise_info_head">
            <p>通知书</p>
        </div>
    </div>

    <div class="lxtongzhiDiv" style="width: 100%;display: flex;justify-content: center">
        <div style="width: 70%">
            <p style="font-size:22pt; line-height:150%; margin:0pt; orphans:0; text-align:center; widows:0"><span
                        style="font-family:'Times New Roman'; font-size:22pt; font-weight:bold">&#xa0;</span></p>
            <p style="font-size:16pt; line-height:150%; margin:0pt; orphans:0; text-align:center; widows:0"><span
                        style="font-family:仿宋; font-size:16pt">珠社科规划办通〔</span><span
                        style="background-color:#008080; font-family:仿宋; font-size:16pt"
                        id="notice_num">     </span><span
                        style="font-family:仿宋; font-size:16pt">〕</span><span
                        style="background-color:#008080; font-family:仿宋; font-size:16pt"
                        id="notice_order">    </span><span
                        style="font-family:仿宋; font-size:16pt">号 </span></p>
            <p style="font-size:22pt; line-height:150%; margin:0pt; orphans:0; text-align:center; widows:0"><span
                        style="font-family:'Times New Roman'; font-size:22pt; font-weight:bold">&#xa0;</span></p>
            <p style="margin:0pt; orphans:0; text-align:center; widows:0"><span
                        style="font-family:方正小标宋简体; font-size:22pt; font-weight:normal">课题立项通知书</span></p>
            <p style="margin:0pt; orphans:0; text-align:center; widows:0"><span
                        style="font-family:'Times New Roman'; font-size:22pt; font-weight:bold">&#xa0;</span></p>
            <p style="line-height:23pt; margin:0pt; orphans:0; text-align:justify; widows:0"><span
                        style="font-family:仿宋; font-size:16pt">课题负责人</span><span
                        style="font-family:仿宋; font-size:16pt; text-decoration:underline" id="leader">      </span><span
                        style="font-family:仿宋; font-size:16pt">:</span></p>
            <p style="line-height:23pt; margin:0pt; orphans:0; text-align:justify; text-indent:32.25pt; widows:0"><span
                        style="font-family:仿宋; font-size:16pt">你所申报的珠海市</span><span
                        style="background-color:#008080; font-family:仿宋; font-size:16pt" id="nd_year">      </span><span
                        style="font-family:仿宋; font-size:16pt">年度哲学社科规划课题，经社科专家评审，市社科规划领导小组研究同意予以立项。</span></p>
            <p style="line-height:23pt; margin:0pt; orphans:0; text-align:justify; text-indent:32.25pt; widows:0"><span
                        style="font-family:仿宋; font-size:16pt">项目名称：</span><span
                        style="font-family:仿宋; font-size:16pt; text-decoration:underline">《</span><span
                        style="font-family:仿宋; font-size:16pt; text-decoration:underline"
                        id="project_name">            </span><span
                        style="font-family:仿宋; font-size:16pt; text-decoration:underline">》</span></p>
            <p style="line-height:23pt; margin:0pt; orphans:0; text-align:justify; text-indent:32.25pt; widows:0"><span
                        style="font-family:仿宋; font-size:16pt; text-decoration:underline">（中标单位：</span><span
                        style="font-family:仿宋; font-size:16pt; text-decoration:underline"> </span><span
                        style="font-family:仿宋; font-size:16pt; text-decoration:underline"
                        id="company">             </span><span
                        style="font-family:仿宋; font-size:16pt; text-decoration:underline">）</span></p>
            <p style="line-height:23pt; margin:0pt; orphans:0; text-align:justify; text-indent:32.25pt; widows:0"><span
                        style="font-family:仿宋; font-size:16pt">课题</span><span
                        style="font-family:仿宋; font-size:16pt">类型：</span><span
                        style="font-family:仿宋; font-size:16pt; text-decoration:underline"
                        id="keti_type">            </span></p>
            <p style="line-height:23pt; margin:0pt; orphans:0; text-align:justify; text-indent:32.25pt; widows:0"><span
                        style="font-family:仿宋; font-size:16pt; text-decoration:none">成果形式：</span><span
                        style="font-family:仿宋; font-size:16pt; text-decoration:underline" id="achievement_type">            </span>
            </p>
            <p style="line-height:23pt; margin:0pt; orphans:0; text-align:justify; text-indent:32.25pt; widows:0"><span
                        style="font-family:仿宋; font-size:16pt">立项编号：</span><span
                        style="font-family:仿宋; font-size:16pt; text-decoration:underline" id="projectstart_num">            </span>
            </p>
            <p style="line-height:23pt; margin:0pt; orphans:0; text-align:justify; text-indent:32.25pt; widows:0"><span
                        style="font-family:仿宋; font-size:16pt">资助经费：</span><span
                        style="font-family:仿宋; font-size:16pt; text-decoration:underline" id="fund">            </span>
            </p>
            <p style="line-height:23pt; margin:0pt; orphans:0; text-align:justify; text-indent:32.25pt; widows:0"><span
                        style="font-family:仿宋; font-size:16pt">完成时限：</span><span
                        style="font-family:仿宋; font-size:16pt; text-decoration:underline"
                        id="finish_time">            </span></p>
            <p style="line-height:23pt; margin:0pt; orphans:0; text-align:justify; text-indent:32.25pt; widows:0"><span
                        style="font-family:仿宋; font-size:16pt">课题</span><span style="font-family:仿宋; font-size:16pt">参与人：</span><span
                        style="font-family:仿宋; font-size:16pt; text-decoration:underline" id="menber_name">                 </span>
            </p>
            <p style="line-height:23pt; margin:0pt; orphans:0; text-align:justify; text-indent:32.25pt; widows:0"><span
                        style="font-family:仿宋; font-size:16pt">请按有关要求办理相关手续，开展课题研究工作。</span></p>
            <p style="line-height:23pt; margin:0pt; orphans:0; text-align:justify; text-indent:32.25pt; widows:0"><span
                        style="font-family:仿宋; font-size:16pt">特此通知。</span></p>
            <p style="line-height:23pt; margin:0pt; orphans:0; text-align:right; widows:0"><span
                        style="font-family:仿宋; font-size:16pt">&#xa0;</span></p>
            <p style="line-height:23pt; margin:0pt; orphans:0; text-align:right; widows:0"><span
                        style="font-family:仿宋; font-size:16pt">珠海市哲学社会科学规划领导小组办公室</span></p>
            <p style="line-height:23pt; margin:0pt 32pt 0pt 0pt; orphans:0; text-align:center; widows:0"><span
                        style="font-family:仿宋; font-size:16pt">                       </span><span
                        style="background-color:#008080; font-family:仿宋; font-size:16pt" id="send_year">   </span><span
                        style="font-family:仿宋; font-size:16pt">年</span><span
                        style="font-family:仿宋; font-size:16pt"> </span><span
                        style="background-color:#008080; font-family:仿宋; font-size:16pt" id="send_month">  </span><span
                        style="font-family:仿宋; font-size:16pt">月</span><span
                        style="background-color:#008080; font-family:仿宋; font-size:16pt" id="send_day">  </span><span
                        style="font-family:仿宋; font-size:16pt">日</span></p>
            <p style="line-height:23pt; margin:0pt; orphans:0; text-align:justify; widows:0"><span
                        style="font-family:仿宋; font-size:16pt; font-weight:bold; text-decoration:underline">                      </span><span
                        style="font-family:仿宋; font-size:16pt; font-weight:bold; text-decoration:underline">                              </span>
            </p>
            <p style="line-height:23pt; margin:0pt 32pt 0pt 0pt; orphans:0; text-align:justify; widows:0"><span
                        style="font-family:仿宋; font-size:16pt">抄送：</span><span style="font-family:仿宋; font-size:16pt">课题负责人所在单位 </span><span
                        style="font-family:仿宋; font-size:16pt">                                                </span>
            </p></div>
    </div>

    <div class="jxtongzhiDiv" style="width: 100%;display: flex;justify-content: center">
        <div>
            <p style="font-size:22pt; line-height:150%; margin:0pt; orphans:0; text-align:center; widows:0"><span
                        style="font-family:'Times New Roman'; font-size:22pt; font-weight:bold">&#xa0;</span></p>
            <p style="font-size:16pt; line-height:150%; margin:0pt; orphans:0; text-align:center; widows:0"><span
                        style="font-family:仿宋; font-size:16pt">珠社科规划办通〔</span><span
                        style="background-color:#008080; font-family:仿宋; font-size:16pt" id="notice_num2"></span><span
                        style="font-family:仿宋; font-size:16pt">〕</span><span
                        style="background-color:#008080; font-family:仿宋; font-size:16pt" id="notice_order2"></span><span
                        style="font-family:仿宋; font-size:16pt">号 </span></p>
            <p style="font-size:22pt; line-height:150%; margin:0pt; orphans:0; text-align:center; widows:0"><span
                        style="font-family:'Times New Roman'; font-size:22pt; font-weight:bold">&#xa0;</span></p>
            <p style="line-height:26pt; margin:0pt; orphans:0; text-align:center; widows:0"><span
                        style="font-family:方正小标宋简体; font-size:22pt; font-weight:normal">珠海市哲学社会科学规划课题</span></p>
            <p style="line-height:26pt; margin:0pt; orphans:0; text-align:center; widows:0"><span
                        style="font-family:方正小标宋简体; font-size:22pt; font-weight:normal">结项通知书</span></p>
            <p style="line-height:26pt; margin:0pt; orphans:0; text-align:justify; widows:0"><span
                        style="font-family:仿宋; font-size:16pt">&#xa0;</span></p>
            <p style="line-height:26pt; margin:0pt; orphans:0; text-align:justify; widows:0"><span
                        style="font-family:仿宋; font-size:16pt">课题负责人</span><span
                        style="font-family:仿宋; font-size:16pt; text-decoration:underline" id="leader2"></span><span
                        style="font-family:仿宋; font-size:16pt">:</span></p>
            <p style="line-height:28pt; margin:0pt; orphans:0; text-align:justify; widows:0"><span
                        style="font-family:仿宋; font-size:16pt">    您所承担的珠海市</span><span
                        style="background-color:#008080; font-family:仿宋; font-size:16pt" id="nd_year2"></span><span
                        style="font-family:仿宋; font-size:16pt">年度哲学社科规划课题:</span></p>
            <p style="line-height:26pt; margin:0pt; orphans:0; text-align:justify; text-indent:32.25pt; widows:0"><span
                        style="font-family:仿宋; font-size:16pt">课题批准号：</span><span
                        style="font-family:仿宋; font-size:16pt; text-decoration:underline" id="kt_num">    </span></p>
            <p style="line-height:26pt; margin:0pt; orphans:0; text-align:justify; text-indent:32.25pt; widows:0"><span
                        style="font-family:仿宋; font-size:16pt">课题名称：</span><span
                        style="font-family:仿宋; font-size:16pt; text-decoration:underline">《</span><span
                        style="font-family:仿宋; font-size:16pt; text-decoration:underline" id="project_name2"> </span><span
                        style="font-family:仿宋; font-size:16pt; text-decoration:underline">》</span></p>
            <p style="line-height:26pt; margin:0pt; orphans:0; text-align:justify; text-indent:32.25pt; widows:0"><span
                        style="font-family:仿宋; font-size:16pt">课题类型：</span><span
                        style="font-family:仿宋; font-size:16pt; text-decoration:underline" id="keti_type2"> </span></p>
            <p style="line-height:26pt; margin:0pt; orphans:0; text-align:justify; text-indent:32.25pt; widows:0"><span
                        style="font-family:仿宋; font-size:16pt; text-decoration:none">成果形式：</span><span
                        style="font-family:仿宋; font-size:16pt; text-decoration:underline" id="achievement_type2"></span></p>
            <p style="line-height:26pt; margin:0pt; orphans:0; text-align:justify; text-indent:32.25pt; widows:0"><span
                        style="font-family:仿宋; font-size:16pt">课题</span><span
                        style="font-family:仿宋; font-size:16pt">参与人：</span><span
                        style="font-family:仿宋; font-size:16pt; text-decoration:underline" id="menber_name2"></span></p>
            <p style="line-height:28pt; margin:0pt; orphans:0; text-align:justify; text-indent:21pt; widows:0"><span
                        style="font-family:仿宋; font-size:16pt">鉴定等级：</span><span
                        style="font-family:仿宋; font-size:16pt; text-decoration:underline" id="appraisal_grade"></span></p>
            <p style="line-height:28pt; margin:0pt; orphans:0; text-align:justify; text-indent:21pt; widows:0"><span
                        style="font-family:仿宋; font-size:16pt">根据《珠海市哲学社会科学规划项目管理办法》有关规定，予以</span><span
                        style="font-family:仿宋; font-size:16pt">结项。</span></p>
            <p style="line-height:28pt; margin:0pt; orphans:0; text-align:justify; text-indent:21pt; widows:0"><span
                        style="font-family:仿宋; font-size:16pt">特此通知。</span></p>
            <p style="line-height:28pt; margin:0pt; orphans:0; text-align:justify; widows:0"><span
                        style="font-family:仿宋; font-size:16pt">               </span><span
                        style="font-family:仿宋_GB2312; font-size:16pt">珠海市哲学社会科学</span><span
                        style="font-family:仿宋_GB2312; font-size:16pt">规划</span><span
                        style="font-family:仿宋_GB2312; font-size:16pt">领导小组办公室</span>
            </p>
            <p style="line-height:28pt; margin:0pt; orphans:0; text-align:justify; widows:0"><span
                        style="font-family:仿宋; font-size:16pt">                           </span><span
                        style="background-color:#008080; font-family:仿宋; font-size:16pt" id="send_year2"></span><span
                        style="font-family:仿宋; font-size:16pt">年</span><span
                        style="background-color:#008080; font-family:仿宋; font-size:16pt" id="send_month2"></span><span
                        style="font-family:仿宋; font-size:16pt">月</span><span
                        style="background-color:#008080; font-family:仿宋; font-size:16pt" id="send_day2"></span><span
                        style="font-family:仿宋; font-size:16pt">日</span></p>
            <p style="line-height:28pt; margin:0pt; orphans:0; text-align:justify; widows:0"><span
                        style="font-family:仿宋; font-size:16pt; font-weight:bold; text-decoration:underline">                      </span><span
                        style="font-family:仿宋; font-size:16pt; font-weight:bold; text-decoration:underline">                              </span>
            </p>
            <p style="line-height:28pt; margin:0pt 32pt 0pt 0pt; orphans:0; text-align:justify; widows:0"><span
                        style="font-family:仿宋; font-size:16pt">抄送：</span><span
                        style="font-family:仿宋; font-size:16pt">课题负责人所在单位 </span><span
                        style="font-family:仿宋; font-size:16pt">                                                </span><span
                        style="font-family:仿宋_GB2312; font-size:16pt"> </span></p></div>
    </div>

    <div class="tongzhiBtn" style="width: 100%;display:flex;justify-content: center;">
        <button type="button" class="btn btn-default" style="margin-top: 20px" onclick="downtzs()">下载</button>
    </div>
    <button type="button" class="btn btn-default" style="margin-top: 100px" onclick="closenowtabs()">返回</button>


</form>

<script>
    var id = 0;
    var notice_id = 0;
    var type = 0;

    $(document).ready(function () {
        {params}
        id = params.id;
        notice_id = params.notice_id;
        type = params.type;
        var types = params.type;
        if (types == 3 || types == 4 || types == 5 || types == 6) {
            $('.lxtongzhiDiv').css('display', 'none');
            $('.jxtongzhiDiv').css('display', 'none');
            $('.tongzhiBtn').css('display', 'none');
        } else if (types == 1) {
            $('.jxtongzhiDiv').css('display', 'none');
            $.ajax({
                type: "POST",
                url: getRootPath() + "/?d=main&m=notice&a=gettzsdetail&ajaxbool=true",
                data: {
                    'id': params.id,
                    'notice_id': params.notice_id,
                    'type': params.type
                },
                dataType: 'json',
                success: function (res) {
                    if (res.success == true) {
                        console.log(res);
                        if (params.type==1){
                            $('#notice_num').text(res.data.notice_num);
                            $('#notice_order').text(res.data.notice_order);
                            $('#leader').text(res.data.leader);
                            $('#nd_year').text(res.data.nd_year);
                            $('#project_name').text(res.data.project_name);
                            $('#company').text(res.data.company);
                            $('#keti_type').text(res.data.keti_type);
                            $('#achievement_type').text(res.data.achievement_type);
                            $('#projectstart_num').text(res.data.projectstart_num);
                            $('#fund').text(res.data.fund);
                            $('#finish_time').text(res.data.finish_time);
                            $('#menber_name').text(res.data.menber_name);
                            $('#send_year').text(res.data.send_time[0]);
                            $('#send_month').text(res.data.send_time[1]);
                            $('#send_day').text(res.data.send_time[2]);
                        }
                    } else{
                        layer.msg('数据获取失败!');
                    }
                }
            });
        } else if (types == 2) {
            $('.lxtongzhiDiv').css('display', 'none');
            $.ajax({
                type: "POST",
                url: getRootPath() + "/?d=main&m=notice&a=gettzsdetail&ajaxbool=true",
                data: {
                    'id': params.id,
                    'notice_id': params.notice_id,
                    'type': params.type
                },
                dataType: 'json',
                success: function (res) {
                    if (res.success == true) {
                            $('#notice_num2').text(res.data.notice_num);
                            $('#notice_order2').text(res.data.notice_order);
                            $('#leader2').text(res.data.leader);
                            $('#nd_year2').text(res.data.nd_year);
                            $('#kt_num').text(res.data.kt_num);
                            $('#project_name2').text(res.data.project_name);
                            $('#company2').text(res.data.company);
                            $('#keti_type2').text(res.data.keti_type);
                            $('#achievement_type2').text(res.data.achievement_type);
                            $('#menber_name2').text(res.data.menber_name);
                            $('#appraisal_grade').text(res.data.appraisal_grade);
                            $('#send_year2').text(res.data.send_time[0]);
                            $('#send_month2').text(res.data.send_time[1]);
                            $('#send_day2').text(res.data.send_time[2]);
                    } else{
                        layer.msg('数据获取失败!');
                    }
                }
            });
        }
        js.ajax(js.getajaxurl('getdetaildata', 'notice', 'main'), {
            'id': params.id,
            'notice_id': params.notice_id,
            'type': params.type
        }, function (res) {
            if (res.success) {
                var data = res.data.rows[0];
                $('#title_{rand}').val(data.title);
                $('#remark_{rand}').val(data.remark);
                if (data.is_mail == 0) {
                    $('#radio_value').text('否')
                } else if (data.is_mail == 1) {
                    $('#radio_value').text('是')
                }
                if (data.flow_files) {
                    $('#flow_fileName').text(data.flow_files);
                    var href = '' + data.files;
                    $('#down').attr('href', href);
                } else {
                    $('.flow_fileDiv').css('display', 'none');
                }

            }
        }, 'post,json');


    });

    function downtzs() {
        window.open(getRootPath() + "/?d=main&m=notice&a=downtzs&id=" + id + "&notice_id=" + notice_id + "&type=" + type, '_self')
    }

</script>
