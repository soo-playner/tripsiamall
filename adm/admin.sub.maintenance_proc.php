<?

include_once('./_common.php');

// $_POST['useFn'] = 'Y';
// $_POST['nw_subject'] = '임시점검';
// $_POST['nw_content'] = '<p>임시점검</p>';

//print_r($_POST);

if(!$_POST['useFn']){
	$_POST['useFn'] = 'N';
}


$sql_common = " 
                nw_use = '{$_POST['useFn']}',
				nw_subject = '{$_POST['nw_subject']}',
                nw_contents_html = '{$_POST['nw_content']}' ";

$sql = " update maintenance set $sql_common ";

// print_r("<br>".$sql);
sql_query($sql);


alert('등록되었습니다.',0);
goto_url("./admin.sub.maintenance.php");

?>