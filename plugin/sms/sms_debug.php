<?
include "./config.php";
include "./sms_api.php";
include "../common.php";

$strTelList     = $_POST["strTelList"];    /* 수신번호 : 01000000001;0100000002; */

if($_POST["strCallBack"]){
	$strCallBack    = $_POST["strCallBack"];  /* 발신번호 : 0317281281 */
}else{
	$strCallBack = $default_callback;
}

$strSubject     = $_POST["strSubject"];    /* LMS제목  : LMS발송에 이용되는 제목( component.php 60라인을 참고 바랍니다. */
$strData        = $_POST["strData"];        /* 메세지 : 발송하실 문자 메세지 */

$chkSendFlag    = $_POST["chkSendFlag"];  /* 예약 구분자 : 0 즉시전송, 1 예약발송 */
$R_YEAR         = $_POST["R_YEAR"];         /* 예약 : 년(4자리) 2016 */
$R_MONTH        = $_POST["R_MONTH"];        /* 예약 : 월(2자리) 01 */
$R_DAY          = $_POST["R_DAY"];          /* 예약 : 일(2자리) 31 */
$R_HOUR         = $_POST["R_HOUR"];         /* 예약 : 시(2자리) 02 */
$R_MIN          = $_POST["R_MIN"];          /* 예약 : 분(2자리) 59 */

$strTelList  = explode(";",$strTelList);
$now = date("Y-m-d H:i:s",time());

// 예약설정을 합니다.
if ($chkSendFlag) $strDate = $R_YEAR.$R_MONTH.$R_DAY.$R_HOUR.$R_MIN;
else $strDate = "";

$row = sql_fetch("select max(wr_no) as wr_no from sms5_write");

if ($row)
	$wr_no = $row['wr_no'] + 1;
else
	$wr_no = 1;

$sms_write ="insert into sms5_write set wr_no='{$wr_no}', wr_renum=0, wr_reply='{$strCallBack}', wr_message='{$_POST['strData']}', wr_booking='0000-00-00 00:00:00', wr_total='1', wr_datetime='".$now."'";

print_r($sms_write);	

//sql_query($sms_write);
	
/*
$sms_history = "insert into sms5_history set wr_no='$wr_no', wr_renum=0, bg_no='0', mb_id='{$_POST['mb_id']}', bk_no='0', hs_name='', hs_hp='{$strTelList}', hs_datetime='".$now."', hs_flag='1', hs_code='', hs_memo='".addslashes($strTelList)."로 전송했습니다.', hs_log='01144 ".addslashes($_POST['strData'])."'";
echo "<br>";
//sql_query($sms_history, false);
print_r($sms_history);
*/

//ob_clean();
echo (json_encode(array("result" => "success",  "code" => "0000", "sql" => $code.'번호로 인증번호가 발송되었습니다.')));
?>