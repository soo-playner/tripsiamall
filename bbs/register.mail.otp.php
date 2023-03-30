<?
include_once('./_common.php');
include_once(G5_LIB_PATH.'/mailer.lib.php');

// 인증키를 메일로 보내고 json 으로 단방향 암호화된 key 리턴 

header('Content-Type: application/json');

/*function generateRandomString($length = 10) {
	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$charactersLength = strlen($characters);
	$randomString = '';
	for ($i = 0; $i < $length; $i++) {
		$randomString .= $characters[rand(0, $charactersLength - 1)];
	}
	return $randomString;
}*/
$randomStr = generateRandomString(6);

// 메일 보내기. 
$subject = '['.$config['cf_title'].'] Verify OTP auth.';
ob_start();
include_once ('./register_form_otp_mail.php');
$content = ob_get_contents();
ob_end_clean();
mailer($config['cf_admin_email_name'], $config['cf_admin_email'], $_GET['mb_email'], $subject, $content, 1);

print json_encode(array("key" => hash("sha256", $randomStr)));

?>