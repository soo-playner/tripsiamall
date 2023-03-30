<?php
include_once('./_common.php');
include_once(G5_LIB_PATH.'/mailer.lib.php');

// 조직도 관련 데이터 업데이트
sql_query("update g5_member a, g5_member b set b.mb_recommend_no = a.mb_no where a.mb_id = b.mb_recommend");
sql_query("call setdepth()");

// 바로 위 추천인에게 메일 전송 
$mb_no = $argv[1];

$sql = "select a.mb_no , a.mb_id, a.mb_email, a.depth, b.mb_no as p_mb_no, b.mb_id as p_mb_id, b.mb_email as p_mb_email, b.depth as p_depth from g5_member a inner join g5_member b on a.mb_recommend_no = b.mb_no where a.mb_no = {$mb_no}";

$srow = sql_fetch($sql);

$content = "Congratulations <span style='color:red;'>{추천인아이디}</span>!<br>
<br>
Your referred member <strong style='color:red;'>{가입자아이디}</strong> successfully signed up at Pinnacle Mining. <br>
<br>
Best,<br>
Pinnacle Mining";
$content = preg_replace("/{추천인아이디}/", $srow['p_mb_id'], $content);
$content = preg_replace("/{가입자아이디}/", $srow['mb_id'], $content);

mailer('pinnaclemining', 'noreply@pinnaclemining.net', $srow['p_mb_email'], 'Referral Sign Up', $content, 1);

// 간접 추천 메일 발송 위로 2~10단계 
$cnt=9;
$rmb_no = $srow['p_mb_no'];
$level = $srow['depth'];
$mb_id = $srow['mb_id'];
while(--$cnt){
	if($rmb_no == 0) break; // 관리자의 부모는 없으므로 break

	$sql = "select a.mb_no , a.mb_id, a.mb_email, a.depth, b.mb_no as p_mb_no, b.mb_id as p_mb_id, b.mb_email as p_mb_email, b.depth as p_depth from g5_member a inner join g5_member b on a.mb_recommend_no = b.mb_no where a.mb_no = {$rmb_no}";

	$srow = sql_fetch($sql);

	$content = "Congratulations <span style='color:red;'>{추천인아이디}</span>!<br>
	<br>
	<strong style='color:red;'>{가입자아이디}</strong> (level <span style='color:red;'>{레벨}</span> downline) successfully signed up at Pinnacle Mining.<br>
	<br>
	Best,<br>
	Pinnacle Mining";

	$content = preg_replace("/{추천인아이디}/", $srow['p_mb_id'], $content);
	$content = preg_replace("/{가입자아이디}/", $mb_id, $content);
	$content = preg_replace("/{레벨}/", ($level - $srow['p_depth'] ) , $content);

	mailer('pinnaclemining', 'noreply@pinnaclemining.net', $srow['p_mb_email'], 'Downline Sign Up', $content, 1);

	$rmb_no = $srow['p_mb_no']; 
}

?>
