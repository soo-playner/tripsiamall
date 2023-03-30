<?php
include_once('./_common.php');
include_once(G5_CAPTCHA_PATH.'/captcha.lib.php');
include_once(G5_LIB_PATH.'/mailer.lib.php');

if ($is_member) {
	alert('이미 로그인중입니다.');
}

// if (!chk_captcha()) {
//     alert('자동등록방지 숫자가 틀렸습니다.');
// }



$email = trim($_POST['mb_email']);
//$email = "arcthan@naver.com";

if (!$email)
	// alert_close('It is not a valid email address.');
	alert('It is not a valid email address.');

$sql = " select count(*) as cnt from {$g5['member_table']} where mb_email = '$email' ";
$row = sql_fetch($sql);
if ($row['cnt'] > 1)
	alert('동일한 메일주소가 2개 이상 존재합니다.\\n\\n관리자에게 문의하여 주십시오.');

$sql = " select mb_no, mb_id, mb_name, mb_nick, mb_email, mb_datetime from {$g5['member_table']} where mb_email = '$email' ";
$mb = sql_fetch($sql);
if (!$mb['mb_id'])
	alert("No one has the email you entered.<br>{$email}",'/bbs/login.php');
else if (is_admin($mb['mb_id']))
	alert('관리자 아이디는 접근 불가합니다.');

// 임시비밀번호 발급
$change_password = rand(100000, 999999);
$mb_lost_certify = get_encrypt_string($change_password);

// 어떠한 회원정보도 포함되지 않은 일회용 난수를 생성하여 인증에 사용
$mb_nonce = md5(pack('V*', rand(), rand(), rand(), rand()));

// 임시비밀번호와 난수를 mb_lost_certify 필드에 저장
$sql = " update {$g5['member_table']} set mb_lost_certify = '".$mb_nonce.$mb_lost_certify."' where mb_id = '{$mb['mb_id']}' ";
sql_query($sql);

// 인증 링크 생성
//$href = G5_BBS_URL.'/password_lost_certify.php?mb_no='.$mb['mb_no'].'&amp;mb_nonce='.$mb_nonce.'&amp;mb_id='.$mb['mb_id'];
$href = G5_URL.'/new/change_password.php?mb_no='.$mb['mb_no'].'&mb_nonce='.$mb_nonce.'&amp;mb_id='.$mb['mb_id'];
$subject = "Password Reset Request";

$content = "";
/*
$content .= '<div style="margin:30px auto;width:600px;border:10px solid #f7f7f7">';
$content .= '<div style="border:1px solid #dedede">';
$content .= '<h1 style="padding:30px 30px 0;background:#f7f7f7;color:#555;font-size:1.4em">';
$content .= 'member information';
$content .= '</h1>';
$content .= '<span style="display:block;padding:10px 30px 30px;background:#f7f7f7;text-align:right">';
$content .= '<a href="'.G5_URL.'" target="_blank">'.$config['cf_title'].'</a>';
$content .= '</span>';
$content .= '<p style="margin:20px 0 0;padding:30px 30px 30px;border-bottom:1px solid #eee;line-height:1.7em">Attention Pinnacle Member:<br><br>';
$content .= addslashes($mb['mb_name'])." (".addslashes($mb['mb_nick']).")"." , or someone using this email address has requested a new temporary password at ".G5_TIME_YMDHIS.". ";
$content .= 'If this was not you please delete this email and open a ticket to for help.<br>';
$content .= 'Because your password is encrypted for security reason, nobody can see your password. A new password has to be created for log-in.<br>';
$content .= 'Save the new password and click on the password reset link below. <span style="color:#ff3061"><strong>New password</strong> </span><br>';
$content .= 'Once the password change is approved, login to your backoffice with new password.<br>';
$content .= 'After you can log-in with this new password, it is recommended to set up your own password in Manage Profile.';
$content .= '</p>';
$content .= '<p style="margin:0;padding:30px 30px 30px;border-bottom:1px solid #eee;line-height:1.7em">';
$content .= '<span style="display:inline-block;width:100px">Username</span> '.$mb['mb_id'].'<br>';
$content .= '<span style="display:inline-block;width:100px">New password</span> <strong style="color:#ff3061">'.$change_password.'</strong>';
$content .= '</p>';
$content .= '<a href="'.$href.'" target="_blank" style="display:block;padding:30px 0;background:#484848;color:#fff;font-size:1.4em;text-decoration:none;text-align:center">Click here for new password!!</a>';
$content .= '</div>';
$content .= '</div>';
*/
$content ='<b id="docs-internal-guid-75f9279d-7fff-5332-63b6-2dca9d33b3c3" style="font-weight: normal;"><p style="line-height: 1.38; margin-top: 0pt; margin-bottom: 0pt;" dir="ltr"><span style="color: rgb(0, 0, 0); font-family: Raleway; font-size: 13pt; font-style: normal; font-variant: normal; font-weight: 400; text-decoration: none; vertical-align: baseline; white-space: pre-wrap; background-color: transparent;">Attention Pinnacle Mining Member: </span></p><br><p style="line-height: 1.38; margin-top: 0pt; margin-bottom: 0pt;" dir="ltr"><span style="color: rgb(0, 0, 0); font-family: Raleway; font-size: 13pt; font-style: normal; font-variant: normal; font-weight: 400; text-decoration: none; vertical-align: baseline; white-space: pre-wrap; background-color: transparent;">You or someone using this email address has requested a password reset. If this was not you please delete this email and do not click on the URL provided below.</span></p><br><p style="line-height: 1.38; margin-top: 0pt; margin-bottom: 0pt;" dir="ltr"><span style="color: rgb(0, 0, 0); font-family: Raleway; font-size: 13pt; font-style: normal; font-variant: normal; font-weight: 400; text-decoration: none; vertical-align: baseline; white-space: pre-wrap; background-color: transparent;">If you did make this request click the URL below to reset your password. </span></p><br><p style="line-height: 1.38; margin-top: 0pt; margin-bottom: 0pt;" dir="ltr"><span style="color: rgb(0, 0, 0); font-family: Raleway; font-size: 13pt; font-style: normal; font-variant: normal; font-weight: 400; text-decoration: none; vertical-align: baseline; white-space: pre-wrap; background-color: transparent;">Password Reset: </span><a style="text-decoration: none;" href="'.$href.'"><span style="color: rgb(17, 85, 204); font-family: Raleway; font-size: 13pt; font-style: normal; font-variant: normal; font-weight: 400; text-decoration: underline; vertical-align: baseline; white-space: pre-wrap; background-color: transparent; -webkit-text-decoration-skip: none; text-decoration-skip-ink: none;">'.$href.'</span></a></p><br><p style="line-height: 1.38; margin-top: 0pt; margin-bottom: 0pt;" dir="ltr"><span style="color: rgb(0, 0, 0); font-family: Raleway; font-size: 13pt; font-style: normal; font-variant: normal; font-weight: 400; text-decoration: none; vertical-align: baseline; white-space: pre-wrap; background-color: transparent;">TIP: We recommend you enable OTP authentication for enhanced security. Click on Manage Profile for details on setting this up, it`s easy and security is very important when dealing with anything bitcoin related.</span></p><br><p style="line-height: 1.38; margin-top: 0pt; margin-bottom: 0pt;" dir="ltr"><span style="color: rgb(0, 0, 0); font-family: Raleway; font-size: 13pt; font-style: normal; font-variant: normal; font-weight: 400; text-decoration: none; vertical-align: baseline; white-space: pre-wrap; background-color: transparent;">Sincerely,</span></p><p style="line-height: 1.38; margin-top: 0pt; margin-bottom: 0pt;" dir="ltr"><span style="color: rgb(0, 0, 0); font-family: Raleway; font-size: 13pt; font-style: normal; font-variant: normal; font-weight: 400; text-decoration: none; vertical-align: baseline; white-space: pre-wrap; background-color: transparent;">Pinnacle Mining Support</span></p></b><br class="Apple-interchange-newline">';

mailer('HAZ password', 'soo@willsoft.kr', $email , $subject, $content, 1);

// alert_close($email.' Password and username verification email has been sent.\\n\\nCheck your email. If you do not receive it check your spam folder first and then open a support ticket for more help.');
alert("Password and username verification email has been sent.<br><br>Check your email. If you do not receive it check your spam folder first and then open a support ticket for more help.<br><br>{$email}",'/bbs/login.php');

function getBrowser() { $broswerList = array('MSIE', 'Chrome', 'Firefox', 'iPhone', 'iPad', 'Android', 'PPC', 'Safari', 'none'); $browserName = 'none'; foreach ($broswerList as				
$userBrowser){ if($userBrowser === 'none') break; if(strpos($_SERVER['HTTP_USER_AGENT'], $userBrowser)) { $browserName = $userBrowser; break;}} 
return $browserName; 
}
?>
