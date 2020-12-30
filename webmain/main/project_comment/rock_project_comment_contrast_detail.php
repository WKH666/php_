<?php if(!defined('HOST'))die('not access');?>
<script >$(document).ready(function() {
	{params}
	var pici_id = params.pici_id;
	InitHtml(pici_id);
	
	$("#search").click(function(){
		InitHtml(pici_id);
	});
});

function InitHtml(pici_id){
	var project_name = $("#project_name_{rand}").val();//项目名称
	var is_wp = $("#is_wp_{rand}").val();//是否已网评
	js.ajax(js.getajaxurl('contrastDetail', 'project_comment', 'main'), {
		pici_id: pici_id,
		project_name: project_name,
		is_wp: is_wp
	}, function(rs) {
		//console.info(rs);
		var th = rs.th;
		var td = rs.td;
		var thstr=tdstr='';
		thstr+='<tr>';
		$.each(th, function(k,el) {
			thstr+='<th>'+el+'</th>';
		});
		thstr+='</tr>';
		$.each(td, function(k,el) {
			tdstr+='<tr>';
			$.each(el, function(sub_k,sub_el) {
				tdstr+='<td>'+sub_el+'</td>';
			});
			tdstr+='</tr>';
		});
		$("#thead_{rand}").html(thstr);
		$("#tbody_{rand}").html(tdstr);
	}, 'post,json');
}

</script>

<style type="text/css">
	.serachPanel{
		display: block;
   		padding-left: 1%;
   		/*min-width: 700px;*/
	}
	.serachPanel .searchAc1{
		display: inline-block;
		/*width: 17%;
		min-width: 271px;*/
	}
	.serachPanel .searchAc1 ul li{
	    float: left;
	    height: 40px;
	    line-height: 33px;
	    /*padding: 0% 1%;*/
	   padding-right: 10px;
	   
	}

	.serachPanel .searchAPanel{
		position: relative;
	}
	.searchAc{
		text-align: center;
		margin-top: 5px;
		margin-bottom: 10px;
	}
	.searchAc a{
		font-size: 12px;
		color: #555555;
	}
	.selSearch {
		height: 32px;
    	line-height: 32px;
	}
	.form-control{
		height: 32px;
    	line-height: 32px;
	}
	.btn-default{
		padding: 5px 12px;
	}
	.tTabc ul li{
		width: 100%;
		text-align: center;
		    border: 1px solid #eee;
    padding-bottom: 2%;
    margin-top: 2%;
	}
	.tTabc ul li span{
		display: block;	
		text-align: center;
		font-size: 20px;
		/*margin: 1% 0%;*/
	}	
	.searchAc1  .stateContent{
		display: inline-block;
  		/*width: 90px;
  		text-indent: 20%;*/
	}
	
	

	/*流程*/
	.processDe{
	    background-color: #419af1;
	    color: #fff;
	    display: inline-block;
	    font-size: 20px;
	    padding: 3% 0%;
	    width: 12%;
	    text-align: center;
	    border-radius: 5px;
	}
	.bgG{
		background-color: #848484 !important;
	}
	.processImg{
	    color: #fff;
	    display: inline-block;
	    font-size: 20px;
	    padding: 2% 4%;
	    width: 10%;
	    text-align: center;
	}
	.processImg img{
	    text-align: center;
		width: auto;
		max-width: 100%;
	}
	#layerhtml{
		font-size: 24px;
		display: table-cell;
		vertical-align: middle;
	}
	.layui-layer-content{
	    display: table;
	    width: 100%;
	    padding: 0% 5%;
	}
	a[name="shenbao"]{
	    color: #428bca !important;
	    cursor: pointer;
	}
	
	/*返回上一级a标签的样式*/
	.callbackone{
		position: absolute;
	    display: block;
	    right: 5.8%;
	    bottom: 5%;
	    font-size: 15px;
	}
</style>
<section class="serachPanel selBackGround">
	<div class="searchAPanel">
		<div class="searchAc1">
			<ul>
				<li>
					<span class="reviewContent stateContent">项目名称</span>
				</li>
				<li>
					<input type="text" id="project_name_{rand}" name="project_name" class="form-control txtPanel">
				</li>
			</ul>
		</div>
		<div class="searchAc1">
			<ul>
				<li >
					<span class="reviewContent stateContent">网评状态</span>
				</li>
				<li>
					<select id="is_wp_{rand}" name="is_wp" class="selSearch selAuditState">
						<option value="">全部</option>
						<option value="0">待网评</option>
						<option value="1">已网评</option>
					</select>
				</li>
			</ul>
		</div>
		<div class="searchAc1" >
			<ul>
				<li>
					<input class="btn_ marH1" type="button" id="search" click="search" value="查询" />
				</li>
			</ul>
		</div>
	</div>
</section>
<div id="view_{rand}">
	<div style="position:relative;" id="tablebody_{rand}">
		<table id="tablemain_1502028893610_8869" class="table table-striped table-bordered table-hover" style="margin:0px">
			<thead id="thead_{rand}">
			</thead>
			<tbody id="tbody_{rand}">
			</tbody>
		</table>
	</div>
</div>
