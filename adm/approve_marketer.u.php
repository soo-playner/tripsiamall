<?php
include_once('./_common.php');
include_once(G5_LIB_PATH.'/mailer.lib.php');

$sql = " update marketer set ";
$sql .= " status = '".$_POST[status]."', ";
$sql .= " comment = '".$_POST[comment]."', ";
$sql .= " commenter = '".$member['mb_id']."', ";
if($_POST[status] == 'Y'){ // 승인
    $sql .= " update_dt = now(), "; 
}
$sql .= " comment_dt = now() ";
$sql .= " where idx = '".$_POST[idx]."' ";

$obj = new stdClass();
$obj->result = sql_query($sql);
$obj->status = $_POST[status];


if($_POST[status] == 'Y'){ // 승인
    $sql = " update g5_member set is_marketer = 'Y' where mb_id = (select writer from marketer where idx = '".$_POST[idx]."' ) "; // 신청글 작성자를 is_marketer = 'Y' 로 변경
    sql_query($sql,true);
}

$subject = "[".$config['cf_title']."] MP Request Result";
$recipient  = sql_fetch("select * from g5_member where mb_id = (select writer from marketer where idx = '".$_POST[idx]."' )",true);
$status =  $_POST['status'];

ob_start();
include_once ('../new/mp_status_mail.php');
$content = ob_get_contents();
ob_end_clean();

// // mailer(발신자 이름, 발신자 메일, 수신자 메일, 메일 제목, 내용, 1);
mailer($config['cf_admin_email_name'], $config['cf_admin_email'], $recipient['mb_email'], $subject, $content, 1);

echo json_encode($obj);

?>
