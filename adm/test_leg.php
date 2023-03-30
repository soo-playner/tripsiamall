<?
	include_once('/home/sdevftv/html/common.php');

	$res= sql_query("select * from g5_member where mb_recommend='rose' ");
	$id = array();
    for ($j=0; $rrr=sql_fetch_array($res); $j++) { 
		
		 $id[$j] = $rrr['mb_id'];
	}
	echo $id[0].'<br/>';
	echo $id[1].'<br/>';
	echo $id[2].'<br/>';
	echo $id[3].'<br/>';
	echo $id[4].'<br/>';

?>
