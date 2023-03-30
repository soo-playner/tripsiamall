<?
include_once('./_common.php');
include_once(G5_LIB_PATH.'/mailer.lib.php');

$n = 100;
while(--$n){
	mailer('pinnaclemining', 'noreply@pinnaclemining.net', 'keehyun2@naver.com', 'Support Ticket Confirmation', 'tsettset', 1);
}


function sendMail($idx, $member){
	$content = "Dear {이름},<br>
	<br>
	Thanks for contacting us!<br>
	<br>
	Your new support ticket has been created and someone will respond shortly.<br>
	<br>
	Ticket ID# {티켓번호}<br>
	Date: {월일년}<br>
	<br>
	Please login and click on “Support Center” to manage this ticket.<br>
	<br>
	Sincerely,<br>
	Pinnacle Support";
	$content = preg_replace("/{이름}/", $member['mb_id'], $content);
	$content = preg_replace("/{티켓번호}/", $idx, $content);
	$content = preg_replace("/{월일년}/", (new \DateTime())->format('d-m-Y'), $content);
	mailer('pinnaclemining', 'noreply@pinnaclemining.net', $member['mb_email'], 'Support Ticket Confirmation', $content, 1);
}
?>