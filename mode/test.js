$(document).ready(function(){
		
			
//			$('.projectName').html('55555');
//			$('.project_head').html($("input[name='project_head']").val());
//			$('.project_yushuan').html($("input[name='project_yushuan']").val());
			
			
			
			
			
			$('#test').click(function(){
	
				alert($("input[name='project_name']").val());
			});
			
			 $("input[name='project_name']").keyup(function(){
    			$('.projectName').html($(this).val());
  			});
  			
  			 $("input[name='project_yushuan']").keyup(function(){
    			$('.project_yushuan').html($(this).val());
  			});
  			
  			$("input[name='project_head']").keyup(function(){
    			$('.project_head').html($(this).val());
  			});
  			
  			//项目建设性质
  			$("select[name='project_js_xinzhi']").change(function(){
  				Select_js_xinzhi('');
  			});
  			//项目类型
      		$("select[name='project_select']").change(function(){
  				Select_select('');
  			});
      		
      		
      		
//			 $("input[name='jianshe_k_date']").keyup(function(){
//			 	console.log($(this));
//  			$('.jianshe_k_date').html($(this).val());
//			});
//			 $("input[name='jianshe_k_date']").change(function(){
//  			alert('测试2033');
// 			 });
// 			 
// 			 $("input[name='jianshe_k_date']").bind('input propertychange keyup', function() {
//					alert('测试2');
//			});
 			 
// 			 $("input[name='jianshe_k_date']").keyup(function(){
//  			alert('测试');
// 			 });
//			 $("input[name='jianshe_k_date']").bind('input propertychange', function() {
// 				$('.jianshe_k_date').html($(this).val());
//			});
	});
	
 function Select_js_xinzhi(value) {

 	
 	  var selectValue = $("select[name='project_js_xinzhi']").val();
      if(selectValue=='其他'){
      	$("select[name='project_js_xinzhi']").css("width","150px");
      	$("#div_project_js_xinzhi").append('<input class="inputs" type="text" value="'+value+'" name="project_js_xinzhi" style="width: 250px; border-bottom: 1px solid #000;">');

      }else{
      		$("select[name='project_js_xinzhi']").css("width","96%");
      		$("#div_project_js_xinzhi").find("input[name='project_js_xinzhi']").remove();
      }
 }
 
 function Select_select(value) {
 	
 	  var selectValue = $("select[name='project_select']").val();
      if(selectValue=='其他'){
      	$("select[name='project_select']").css("width","150px");
      	$("#div_project_select").append('<input class="inputs" type="text" value="'+value+'" name="project_select" style="width: 250px; border-bottom: 1px solid #000;">');

      }else{
      		$("select[name='project_select']").css("width","96%");
      		$("#div_project_select").find("input[name='project_select']").remove();
      }
 }