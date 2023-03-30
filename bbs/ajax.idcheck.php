<?include_once('./_common.php');

$mb_id = trim($_POST['reg_mb_id']);

$strsql = "SELECT count(*) as cnt FROM g5_member WHERE mb_id = '$mb_id'";
$cnt = sql_fetch($strsql);

echo  $cnt ['cnt'];
?>