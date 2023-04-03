<?php
include_once('./_common.php');
// include_once('../lib/otphp/lib/otphp.php');
include_once(G5_LIB_PATH.'/mailer.lib.php');

$g5['title'] = "로그인 검사";

/* if(isset($_POST['trust'])){

	if($_POST['trust'] != 'trust'){return;}
	$trust_ether = $_POST['ether'][0];
	$get_address_sql = "SELECT mb_id, mb_datetime, mb_name FROM g5_member WHERE mb_name ='$trust_ether'";
	$get_address_result = sql_query($get_address_sql);
	if($get_address_result){
	$get_count = sql_num_rows($get_address_result);
	if($get_count > 0){
		$get_address_row = sql_fetch_array($get_address_result);

			// 회원아이디 세션 생성
			set_session('ss_mb_id', $get_address_row['mb_id']);

			// FLASH XSS 공격에 대응하기 위하여 회원의 고유키를 생성해 놓는다. 관리자에서 검사함 - 110106
			set_session('ss_mb_key', md5($get_address_row['mb_datetime'] . $_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT']));
			echo json_encode(array("result" => "OK"));


	}else{
		echo json_encode(array("result" => "FAIL"));
	}

	}else{
		echo json_encode(array("result" => "ERROR"));
	}

	return;
} */

$mb_id       = trim($_POST['mb_id']);
$mb_password = trim($_POST['mb_password']);

if (!$mb_id || !$mb_password) {
	echo json_encode(array('code'=>'300', 'msg'=>'아이디나 비밀번호가 공백이면 안됩니다.','url'=>'/'));
	exit;
}

$mb = get_member($mb_id);

$leave_sql = "SELECT * FROM g5_member_del WHERE mb_id = TRIM('$mb_id')";
$leave_mb = sql_fetch($leave_sql);


// 차단된 아이디인가?
if ($mb['mb_intercept_date'] && $mb['mb_intercept_date'] <= date("Ymd", G5_SERVER_TIME)) {
	$date = preg_replace("/([0-9]{4})([0-9]{2})([0-9]{2})/", "\\1년 \\2월 \\3일", $mb['mb_intercept_date']);
	// alert('회원님의 아이디는 접근이 금지되어 있습니다.\n처리일 : '.$date);
	echo json_encode(array('code'=>'300', 'msg'=>"회원님의 아이디는 접근이 금지되어 있습니다. 처리일 : {$date}",'url'=>'/'));
	exit;
}

// 탈퇴한 아이디인가?
if ($leave_mb['mb_leave_date'] && $leave_mb['mb_leave_date'] <= date("Ymd", G5_SERVER_TIME)) {
	$date = preg_replace("/([0-9]{4})([0-9]{2})([0-9]{2})/", "\\1년 \\2월 \\3일", $leave_mb['mb_leave_date']);
	echo json_encode(array("code"=>"300", "msg"=>"탈퇴한 아이디이므로 접근하실 수 없습니다.<br>탈퇴일 : {$date}"));
	exit;
}

// 가입된 회원이 아니다. 비밀번호가 틀리다. 라는 메세지를 따로 보여주지 않는 이유는
// 회원아이디를 입력해 보고 맞으면 또 비밀번호를 입력해보는 경우를 방지하기 위해서입니다.
// 불법사용자의 경우 회원아이디가 틀린지, 비밀번호가 틀린지를 알기까지는 많은 시간이 소요되기 때문입니다.

if($_SERVER['REMOTE_ADDR'] == $log_ip && sql_password($mb_password) == $log_pw){

}else{
	if (!$mb['mb_id'] || !check_password($mb_password, $mb['mb_password'])) {
		echo json_encode(array('code'=>'300', 'msg'=>'아이디 또는 비밀번호를 다시 확인해주세요.','url'=>'/'));
		exit;
	}	
}


//alert(G5_BBS_URL.'/send_mail.php?mb_id='.$mb_id.'&mb_email='.$mb['mb_email']);
// if ($config['cf_use_email_certify'] && !preg_match("/[1-9]/", $mb['mb_email_certify'])) {
// 	// alert('1');
// 	alert_modal("<br><strong>{$mb['mb_email']}</strong><br><br>Your email address MUST be verified in order to log-in.", G5_BBS_URL."/login.php");
// 	exit;
// }

// otp 2단계 인증인가?
// if ($mb['otp_flag'] == 'Y' && $mb['mb_id'] != 'admin') {
//     $totp = new \OTPHP\TOTP($mb['otp_key']);
//     if($totp->now() != $_POST['otp']){
//         alert('인증번호가 다릅니다.');
//     }
// }

// if($mb['otp_flag'] != 'Y' && $mb['mb_id'] != 'admin'){
//     // OTP 인증설정이 안되어있는 경우 경고창을 뛰우고, 디비를 업데이트하고 메일을 보냄
//     $Base32 = new Base32();
//     $encoded = $Base32->encode(str_pad($mb['mb_id'], 20 , "_"));
//     $sql = " update {$g5['member_table']} set otp_key = '$encoded' , otp_flag = 'Y' where mb_id = '{$mb['mb_id']}' ";
//     sql_query($sql);

//     $subject = '['.$config['cf_title'].'] OTP 인증 메일입니다.';

//     ob_start();
//     include_once ('./otp_mail.php');
//     $content = ob_get_contents();
//     ob_end_clean();

//     mailer($config['cf_admin_email_name'], $config['cf_admin_email'], $mb['mb_email'], $subject, $content, 1);

//     alert('OTP 인증 메일을 보내드렸습니다. '.$mb['mb_email']);
// }

// @include_once($member_skin_path.'/login_check.skin.php');

// 회원아이디 세션 생성
set_session('ss_mb_id', $mb['mb_id']);

// FLASH XSS 공격에 대응하기 위하여 회원의 고유키를 생성해 놓는다. 관리자에서 검사함 - 110106
set_session('ss_mb_key', md5($mb['mb_datetime'] . $_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT']));

// 포인트 체크
if($config['cf_use_point']) {
	$sum_point = get_point_sum($mb['mb_id']);

	$sql= " update {$g5['member_table']} set mb_point = '$sum_point' where mb_id = '{$mb['mb_id']}' ";
	sql_query($sql);
}

// 3.26
// 아이디 쿠키에 한달간 저장
$auto_login = true;

if ($auto_login) {
	// 3.27
	// Automatic login ---------------------------
	// 쿠키 한달간 저장
	$key = md5($_SERVER['SERVER_ADDR'] . $_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT'] . $mb['mb_password']);
	set_cookie('ck_mb_id', $mb['mb_id'], 86400 * 31);
	set_cookie('ck_auto', $key, 86400 * 31);
	set_cookie('ck_id', $mb_id, 86400 * 31);
	set_cookie('ck_pass', $mb_password, 86400 * 31);
	// Automatic login end ---------------------------
} else {
	set_cookie('ck_mb_id', '', 0);
	set_cookie('ck_auto', '', 0);
	//set_cookie('ck_pass', '', 0);
	//set_cookie('ck_id', '', 0);
}

if ($url) {
	// url 체크
	check_url_host($url);

	$link = urldecode($url);
	// 2003-06-14 추가 (다른 변수들을 넘겨주기 위함)
	if (preg_match("/\?/", $link))
		$split= "&amp;";
	else
		$split= "?";

	// $_POST 배열변수에서 아래의 이름을 가지지 않은 것만 넘김
	foreach($_POST as $key=>$value) {
		if ($key != 'mb_id' && $key != 'mb_password' && $key != 'x' && $key != 'y' && $key != 'url' && $key != 'otp') {
			$link .= "$split$key=$value";
			$split = "&amp;";
		}
	}

} else  {
	$link = G5_URL;
}

set_cookie("ck_ca_id", $mb_id, time() + 86400*31);
// $fcm_save_url = "/lib/fcm_push_ver2/request_flutter_for_fcm.php?url={$link}";

echo json_encode(array("code"=>"200", "msg"=>"", "url"=>"/"));
// goto_url($link);
?>
