/*$(document).ready(function(){
	
	$("tr[name='lucen']").each(function(){
		
		$dongzuo=$(this).find('td').eq(1).text();
		$head=$(this).find('td').eq(2).text();
		$tf_d=$(this).find('td').eq(3).text();
		$comm=$(this).find('td').eq(4).text();
		
		$ldap='1';
		$xban='1';
		$yewu='1';
		
		if($dongzuo=='上级领导审核' && $ldap=='1'){
			if($tf_d=='通过' || $tf_d=='不通过'){
					$ldap='0';
					$('#dept_comm').html($comm);
					$('#dept_head').html($head);
					
				}
			
			}
		if($dongzuo=='校项目办公室审核' && $xban=='1'){
			
			if($tf_d=='通过'){
					 $xban='0';
					$('#x_tuihui').html($comm);
					$('#xmb_head').html($head);
					
				}
			if($tf_d=='不通过'){
					 $xban='0';
					$('#x_tongyi').html($comm);
					$('#xmb_head').html($head);
					
				}
			}
			
		});
		
		
	
	 });*/