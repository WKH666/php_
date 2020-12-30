//流程模块【project_sx_apply.实训中心申报】下录入页面自定义js页面,初始函数
function initbodys() {
	if(htmlbacklx!='projectmanage'){
			if(isturn==0){
		$('#AltS').before('<input id="DraftS" type="button" style="background:#888888" onclick="return initshuju()" value="保存为草稿" class="webbtn">&nbsp; &nbsp;');
			}
	}
	
}

function initshuju() {

		$("input[name='isturn']").remove();
		$("form[name='myform']").append('<input name="isturn" type="hidden" value="0">');
		this.c.save();
		
		

}

//数据加载完后回调函数
function loadingDataAfter(str){
	$('.projectName').html($('input[name="project_name"]').val());
	$('.project_yushuan').html($("input[name='project_yushuan']").val());
	$('.project_head').html($("input[name='project_head']").val());
	$('.jianshe_k_date').html($("input[name='jianshe_k_date']").val());
	$('.jianshe_s_date').html($("input[name='jianshe_s_date']").val());
	
	if($("select[name='project_js_xinzhi']").find("option:selected").text()==''){
		$("select[name='project_js_xinzhi']").find("option[value='其他']").attr("selected",true);

		Select_js_xinzhi(str.data.data.project_js_xinzhi);
	}
	
	if($("select[name='project_select']").find("option:selected").text()==''){
		$("select[name='project_select']").find("option[value='其他']").attr("selected",true);

		Select_js_xinzhi(str.data.data.project_select);
	}
	
}

var checkInit = {
	checkPhone: function(p) {
		var obj = /^1[34578]\d{9}$/;
		var results = obj.test(p);
		if(results) {
			return true;
		}
		return false;
	},
	checkEmail: function(p) {
		var obj = /^\w+@(\w+)\.com(\.cn)?$/i;
		var results = obj.test(p);
		return results;
	},
	checkTextareaLength: function(t) {
		if(t >= 801) {
			return false;
		}
	},
	init: function(n, k) {
		k = k || {};
		switch(n) {
			case "phone":
				return this.checkPhone(k);
				break;
			case "email":
				return this.checkEmail(k);
				break;
			case "textArea":
				return this.checkTextareaLength(k);
				break;
		}
	},
}