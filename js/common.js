//附件类型
var type_arr = ['结项报告书', '结项成果', '结项论文资料', '结项成果资料', '申报书', '课题设计论证(活页)','归档资料'];
//附件数据对象
var uploadFileArr = {'1': '', '2': '', '3': '', '4': '', '5': '', '6': '','7':''};
//显示上传文件名称的标签id
var inputId = '';

//获取当前域名
function getRootPath() {
    //获取当前网址，如： http://localhost:8083/uimcardprj/share/meun.jsp
    var curWwwPath = window.document.location.href;
    //获取主机地址之后的目录，如： uimcardprj/share/meun.jsp
    var pathName = window.document.location.pathname;
    var pos = curWwwPath.indexOf(pathName);
    //获取主机地址，如： http://localhost:8083
    var localhostPaht = curWwwPath.substring(0, pos);
    //获取带"/"的项目名，如：/uimcardprj
    var projectName = pathName.substring(0, pathName.substr(1).indexOf('/') + 1);
    return (localhostPaht + projectName);
}

//初始化页面
function pageinit(course_id) {
    //判断是否具有审核权限
    if (ischeck) {
        if (ischeck2 != 1 || status == 5) {
            $('.courseClsCheck').css('display', 'none');
            $('#upload_table').css('display', 'none');
            $('#read_table').css('display', 'inline-table');
            $('.courseCheckDiv').css('display', 'none');
            $('.courseClsFooter').css('display', 'none');
        }
    }

    //显示归档操作页面
    if (showgds == 1){
        //项目归档获取项目名称和学科分类信息
        getGdInfo(mids,modenum);
        $('#gdupload_table').css('display','inline-table');
        $('.courseCls').css('display','none');
        $('.gdProjectDiv').css('display','none');
        $('#upload_table').css('display', 'none');
        $('#read_table').css('display', 'none');
        $('.courseCheckDiv').css('display', 'none');
        $('.courseClsFooter').css('display', 'block');
    }else {
        $('.courseCls').css('display', 'block');
        $('.courseClsCheck').css('display', 'none');
        $('#upload_table').css('display', 'none');
        $('#read_table').css('display', 'inline-table');
    }

    //101:提交结项成果、经费决算,102:   结项成果、经费决算审核(高校科研人员),107:结项成果、经费决算审核(社科管理员),91：项目归档
    if (course_id == 102 || course_id == 107) {
        if (ischeck){
            if (ischeck2 == 1 && status != 5) {
                //显示已上传文件的table
                $('.courseCls').css('display', 'none');
                $('.courseClsFooter').css('display', 'block');
                $('.courseClsCheck').css('display', 'block');
                $('.courseCheckDiv').css('display', 'block');
                $('#upload_table').css('display', 'none');
                $('#read_table').css('display', 'inline-table');
                getAchievementData();
            }
        }
    } else if (course_id == 101) {
        //显示可以实现上传功能的table
        $('.courseClsFooter').css('display', 'block');
        $('.courseCls').css('display', 'none');
        $('.courseClsCheck').css('display', 'block');
        $('#upload_table').css('display', 'inline-table');
        $('#read_table').css('display', 'none');
    }
}
//获取成果信息
function getAchievementData(course_id) {
    $.ajax({
        url: getRootPath() + "/?d=main&m=project_comment&a=getAchievementData&ajaxbool=true",
        data: {mode_num: modenum, mid: mids},
        type: 'post',
        dataType: 'json',
        success: function (res) {
            if (res.success) {
                var data = res.data;
                bill_id = data.bill_id;
                $('#achievement_num_').val(data.identifier);
                $('#achievement_type_').val(data.form);
                $('#end_time_').val(data.update_time);
                $('#project_name_').val(data.name);
                $('#project_author_').val(data.author);
                $('#company_').val(data.location_unit);
                $('#publication_').val(data.serial_title);
                $('#roundup_').val(data.abstract);
            }
        }
    });
}

//文件上传(typeNum=>附件类型数字编号，显示文件名称的input标签id,是否是上传归档资料)
function addfiles(type_num, item,isgd=0) {
    sessionStorage.setItem('99fileType', type_arr[type_num - 1]);
    sessionStorage.setItem('99fileTypeNum', type_num);
    inputId = item;
    if (isgd==0){
        js.upload('uploadCallFunc', {
            maxup: '1',
            'title': '选择word文件',
            uptype: 'doc|docx',
            'urlparams': 'noasyn:yes'
        });
    }else {
        js.upload('uploadCallFunc', {
            maxup: '1',
            'title': '选择文件',
            uptype: 'zip|rar',
            'urlparams': 'noasyn:yes'
        });
    }

}

//文件上传回调函数
uploadCallFunc = function (a, xid) {
    var typeNum = sessionStorage.getItem('99fileTypeNum');
    //检测缓存中是否已存有文件上传的数据
    var data = JSON.parse(sessionStorage.getItem('uploadFileData'));
    if (data) {
        //替换文件
        if (data[typeNum]) {
            delUploadFile(0, data[typeNum]['id']);
        }
        data[typeNum] = a[0];
        sessionStorage.setItem('uploadFileData', JSON.stringify(data));
    } else {
        uploadFileArr[typeNum] = a[0];
        sessionStorage.setItem('uploadFileData', JSON.stringify(uploadFileArr));
    }
    $('#' + inputId).val(a[0].filename);
    checkUploadStatus(inputId);
};

//检测上传状态
function checkUploadStatus(inputId) {
    if ($('#' + inputId).val()) {
        $('#' + inputId + 'status').text('上传成功');
    } else {
        $('#' + inputId + 'status').text('未上传')
    }
}

//删除已经上传的文件(typeNum=>附件类型数字编号，上传的文件id，显示文件名称的input标签id)
function delUploadFile(typeNum, file_id = 0, newInputId = '') {
    //typenum=0=>替换文件typenum!=0删除文件
    if (typeNum != 0) {
        var data = JSON.parse(sessionStorage.getItem('uploadFileData'));
        var fileData = data[typeNum];
        $.ajax({
            url: getRootPath() + "/?d=main&m=project_comment&a=delFile&ajaxbool=true",
            data: {id: fileData['id']},
            type: 'post',
            dataType: 'json',
            success: function (res) {
                if (res.success) {
                    $('#' + newInputId).val('');
                    data[typeNum] = '';
                    sessionStorage.setItem('uploadFileData', JSON.stringify(data));
                    checkUploadStatus(newInputId);
                }
            }
        });
    } else {
        $.ajax({
            url: getRootPath() + "/?d=main&m=project_comment&a=delFile&ajaxbool=true",
            data: {id: file_id},
            type: 'post',
            dataType: 'json',
            success: function (res) {
                console.log(res);
                if (res.success) {
                    console.log('替换成功');
                }
            }
        });
    }
}

//关闭页面
function closePage() {
    $('#winiframe_spancancel', window.parent.document).click();
}

//结项报告环节，归档环节提交
function jiexiangSubmit() {
    if (nowCourseId == 101) {
        submitJxReport();
    } else if (nowCourseId == 102) {
        checkJxReport();
    } else if (nowCourseId == 107) {
        checkJxReport();
    }
    if (showgds==1){
        guidangSubmit();
    }

}


//获取上传的文件资料
function getUploadFilesData(mid, mode_num) {
    $.ajax({
        url: getRootPath() + "/?d=main&m=project_comment&a=UploadFilesData&ajaxbool=true",
        data: {mid: mid, mode_num: mode_num},
        type: 'post',
        dataType: 'json',
        success: function (res) {
            if (res.success) {
                //动态渲染文件表格
                fileTableFunc(mode_num, res.data);
            }
        }
    });
}

//文件下载
function downFile(fileId, fileType, filePath) {
    if (js.isimg(fileType)) {
        $.imgview({url: filePath});
    } else {
        js.downshow(fileId)
    }
}

//动态渲染文件表格
function fileTableFunc(mode_num, existFileData) {
    if (mode_num == 'project_coursetask') {
        var typeArr = ['申报书', '课题设计论证(活页)', '开题报告', '结项报告书', '结项成果', '结项论文资料', '结项成果资料'];
        var html = '';
        for (var i = 0; i < existFileData.length; i++) {
            var fileType = existFileData[i]['upload_filetype'];
            var fileName = existFileData[i]['filename'];
            var filePath = existFileData[i]['filepath'];
            var filePdfPath = existFileData[i]['pdfpath'];
            var fileId = existFileData[i]['id'];
            var fileExt = existFileData[i]['fileext'];
            html = '<tr>\n' +
                '                    <td>' + fileType + '</td>\n' +
                '                    <td><input  readonly value=' + fileName + '></td>\n' +
                '                    <td>未查看</td>\n' +
                '                    <td>\n' +
                '                        <a href="javascript:;"  style="color: #3D8EDB;text-decoration: none" >查看</a>\n' +
                '                        <a href="javascript:;" onclick="downFile(' + fileId + ',\'' + fileExt + '\',\'' + filePath + '\'' + ')" style="color: #3D8EDB;text-decoration: none" >下载</a>\n' +
                '                    </td>\n' +
                '                </tr>';
            $('#results_tbody').append(html);
            //将未上传的文件类型选出
            typeArr.splice(typeArr.indexOf(fileType), 1);
        }
        for (var j = 0; j < typeArr.length; j++) {
            html = '<tr>\n' +
                '                    <td>' + typeArr[j] + '</td>\n' +
                '                    <td><input  readonly></td>\n' +
                '                    <td>文件未上传</td>\n' +
                '                    <td>\n' +
                '                        <a href="javascript:;"  style="color: #3D8EDB;text-decoration: none" >查看</a>\n' +
                '                        <a href="javascript:;"  style="color: #3D8EDB;text-decoration: none" >下载</a>\n' +
                '                    </td>\n' +
                '                </tr>';
            $('#results_tbody').append(html);
        }
    }
}

//申报者提交结项报告
function submitJxReport() {
    var fileIdStr = '';
    var fileIdStorage = JSON.parse(sessionStorage.getItem('uploadFileData'));
    for (let keys in fileIdStorage) {
        if (fileIdStorage[keys]) {
            fileIdStr += fileIdStorage[keys]['id'] + ',';
        }
    }
    var data = {
        'fileIdStr': fileIdStr,
        'mid': mid,
        'mode_num': modenum,
    };
    $.ajax({
        url: getRootPath() + "/?d=main&m=project_comment&a=jiexiangSubmit&ajaxbool=true",
        data: data,
        type: 'post',
        dataType: 'json',
        success: function (res) {
            if (res.success) {
                sessionStorage.removeItem('uploadFileData');
                sessionStorage.removeItem('99fileTypeNum');
                sessionStorage.removeItem('99fileType');
                parent.layer.msg(res.msg);
                parent.assessmentList.reload();
                closePage();
            }
        }
    });
}

//审核结项报告 type:gx=>高校角色,skgl=>社科管理角色
function checkJxReport(type = '') {
    var checkResult = $("input[name='check_result']:checked").val();
    var checkSuggest = $("textarea[name='result_suggest']:checked").val();
    var data = {
        'type': type,
        'checkResult': checkResult,
        'checkSuggest': checkSuggest,
        'mode_num': modenum,
        'mid': mid,
        'nowCourseId': nowCourseId,
    };
    $.ajax({
        url: getRootPath() + "/?d=main&m=project_comment&a=checkJxReport&ajaxbool=true",
        type: 'post',
        data: data,
        dataType: 'json',
        success: function (res) {
            if (res.code == 200) {
                parent.layer.msg(res.msg);
                parent.assessmentList.reload();
                closePage();
            } else {
                parent. layer.msg(res.msg);
            }
        },
        error: function (err) {
            parent.layer.msg('网络请求超时')
        }
    });
}

// 项目归档获取项目名称和学科分类
function getGdInfo(mid,modename) {
    $.ajax({
        url: getRootPath() + "/?d=main&m=project_comment&a=getGdInfo&ajaxbool=true",
        type:'post',
        data:{mid:mid,modename:modename},
        dataType:'json',
        success:function (res) {
            $('#gdproject_name').val(res.data.course_name);
            $('#gdclassic_type').val(res.data.subject_classification);
        }
    })
}

//归档提交
function guidangSubmit() {
    //将上传的归档资料与该单据建立起联系
    var fileIdStr = '';
    var fileIdStorage = JSON.parse(sessionStorage.getItem('uploadFileData'));
    for (let keys in fileIdStorage) {
        if (fileIdStorage[keys]) {
            fileIdStr += fileIdStorage[keys]['id'] + ',';
        }
    }
    var data = {
        'fileIdStr': fileIdStr,
        'mid': mid,
        'mode_num': modenum,
    };

    $.ajax({
        url: getRootPath() + "/?d=main&m=project_comment&a=guidangSubmit&ajaxbool=true",
        type:'post',
        data:data,
        dataType:'json',
        success:function (res) {
            parent.layer.msg(res.msg);
            parent.assessmentList.reload();
            sessionStorage.removeItem('uploadFileData');
            sessionStorage.removeItem('99fileTypeNum');
            sessionStorage.removeItem('99fileType');
            closePage();
        }
    })
}
