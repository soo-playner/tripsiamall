<?php
include_once('./_common.php');
include_once(G5_LIB_PATH.'/mailer.lib.php');

$mb_id = $_GET['mb_id'];
$mb_email = $_GET['mb_email'];

$subject = '['.$config['cf_title'].'] Membership Email.';

$mb_md5 = md5($mb_id.$mb_email.G5_TIME_YMDHIS);

sql_query(" update {$g5['member_table']} set mb_email_certify2 = '$mb_md5' where mb_id = '$mb_id' ");

$certify_href = G5_BBS_URL.'/email_certify.php?mb_id='.$mb_id.'&amp;mb_md5='.$mb_md5;
$toadmin_href = G5_BBS_URL.'/email_toadmin.php?mb_id='.$mb_id.'&amp;mb_email='.$mb_email;

ob_start();
include_once ('./register_form_update_mail1.php');
$content = ob_get_contents();
ob_end_clean();

mailer($config['cf_admin_email_name'], $config['cf_admin_email'], $mb_email, $subject, $content, 1);

header('Content-Type: application/json');
print json_encode(array('email' => $mb_email));
?>
