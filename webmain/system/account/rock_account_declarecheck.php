<?php if(!defined('HOST'))die('not access');?>
<script>

</script>
<style>
    .header_title{
        background: #CDE3F1;
        border-radius: 5px;
    }
    .header_title p{
        padding: 5px 0;
    }
    form[name='checkform']{
        display: flex;
        flex-direction: row;
        align-items: center;
        flex-wrap: wrap;
    }
    form[name='checkform'] .form-group{
        display: flex;
        flex-direction: row;
        align-items: center;
        margin: 10px;
    }
    form[name='checkform'] .form-group label{
        display: flex;
        flex-direction: row;
        justify-content: center;
        align-items: center;
        margin: 0;
        width: 10rem;
    }
    form[name='checkform2']{
        display: flex;
        flex-direction: row;
        flex-wrap: wrap;
    }
    form[name='checkform2'] .form-group input[type='button']{
        margin: 10px;
    }
</style>
<div>
    <div class="header_title">
        <p>账号信息</p>
    </div>
    <div class="declarecheck_form">
        <form name="checkform">
            <div class="form-group">
                <label><font color="red">*</font>账号：</label>
                <input type="text" value="" class="form-control">
            </div>
            <div class="form-group">
                <label><font color="red">*</font>密码：</label>
                <input type="text" value="" class="form-control">
            </div>
            <div class="form-group">
                <label><font color="red">*</font>单位：</label>
                <input type="text" value="" class="form-control">
            </div>
            <div class="form-group">
                <label><font color="red">*</font>账号：</label>
                <input type="text" value="" class="form-control">
            </div>
            <div class="form-group">
                <label><font color="red">*</font>姓名：</label>
                <input type="text" value="" class="form-control">
            </div>
            <div class="form-group">
                <label>性别：</label>
                <input type="checkbox" value="" class="">
                <input type="text" value="" class="form-control" style="visibility: hidden;">
            </div>
            <div class="form-group">
                <label>职务/职称：</label>
                <input type="text" value="" class="form-control">
            </div>
            <div class="form-group">
                <label><font color="red">*</font>联系电话：</label>
                <input type="text" value="" class="form-control">
            </div>
            <div class="form-group">
                <label><font color="red">*</font>电子邮箱：</label>
                <input type="text" value="" class="form-control">
            </div>
            <div class="form-group">
                <label>负责学科：</label>
                <input type="text" value="" class="form-control">
            </div>
            <div class="form-group">
                <label>研究方向：</label>
                <input type="text" value="" class="form-control">
            </div>
            <div class="form-group">
                <label>账号状态：</label>
                <input type="checkbox" value="" class="">
                <input type="text" value="" class="form-control" style="visibility: hidden;">
            </div>
        </form>
    </div>
    <div class="declarecheck_form2">
        <form name="checkform2">
            <div class="form-group">
                <input type="button" class="btn btn-default" value="返回">
            </div>
        </form>
    </div>
</div>
