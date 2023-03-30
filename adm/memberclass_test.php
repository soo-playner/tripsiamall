<?
	
	$class_name     = "g5_member_class";
	$recommend_name = "mb_recommend";



		$sql  = " select ".$recommend_name.",(select mb_name from g5_member where mb_id=m.".$recommend_name.") as recomm_name from {$g5['member_table']} as m where mb_id='{$m_id}' and mb_leave_date = ''";
	
		echo $sql;
	
?>