<?

include_once('./_common.php');

//print_r($_POST);

if(!$_POST['nw_with']){
	$nw_with = 'N';
}else{
	$nw_with = $_POST['nw_with'];
}


if(!$_POST['nw_upstair']){
	$nw_upstair = 'N';
}else{
	$nw_upstair = $_POST['nw_upstair'];
}

if(!$_POST['nw_change']){
	$nw_change = 'N';
}else{
	$nw_change = $_POST['nw_change'];
}

if(!$_POST['nw_purchase']){
	$nw_purchase = 'N';
}else{
	$nw_purchase = $_POST['nw_purchase'];
}

if(!$_POST['nw_enroll']){
	$nw_enroll = 'N';
}else{
	$nw_enroll = $_POST['nw_enroll'];
}

$sql_common = " 
                nw_with = '{$nw_with}',
				nw_upstair = '{$nw_upstair}',
				nw_change = '{$nw_change}',
				nw_purchase = '{$nw_purchase}',
				nw_enroll = '{$nw_enroll}'";

$sql = "update maintenance set $sql_common ";

//print_r("<br>".$sql);
sql_query($sql);


alert('등록/변경 되었습니다.',0);
goto_url("./admin.sub.maintenance.php");

?>