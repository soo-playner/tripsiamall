
<?php
include "./config.php";
include "./sms_api.php";

/**
 * 발신번호 사전등록제 (전기통신사업법 제84조)
 *  거짓으로 표시된 전화번호를 인한 이용자 피해 예방을 위하여 문자 전송시 
 *  사전 인증된 발신번호로만 사용할 수 있도록 등록하는 제도입니다.
 *  발신번호등록은 아이코드 사이트 로그인 후 상단 발신번호 등록를 참고 하시기 바랍니다.
*/

$SMS = new SMS;    /* SMS 모듈 클래스 생성 */
$SMS->SMS_con($socket_host,$socket_port,$icode_key);    /* 아이코드 서버 접속 */

/**
 * 문자발송 Form을 사용하지 않고 자동 발송의 경우 수신번호가 1개일 경우 번호 마지막에 ";"를 붙인다 
 * ex) $strTelList = "0100000001;";
*/

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

// 예약설정을 합니다.
if ($chkSendFlag) $strDate = $R_YEAR.$R_MONTH.$R_DAY.$R_HOUR.$R_MIN;
else $strDate = "";

// 문자 발송에 필요한 항목을 배열에 추가
$result = $SMS->Add($strTelList, $strCallBack, $strData, $strSubject, $strDate);

// 패킷 정의의 결과에 따라 발송여부를 결정합니다.
if ($result) {
  echo "일반메시지 입력 성공<br />";
  echo "<hr>";

  // 패킷이 정상적이라면 발송에 시도합니다.
  $result = $SMS->Send();

  if ($result) {
    echo "서버에 접속했습니다.<br /><br />";
    $success = $fail = 0;
    $isStop = 0;
    foreach($SMS->Result as $result) {

      list($phone,$code)=explode(":",$result);

      if (substr($code,0,5)=="Error") {
        echo $phone.' 발송에러('.substr($code,6,2).'): ';
        switch (substr($code,6,2)) {
          case '23':   // "23:데이터오류, 전송날짜오류, 발신번호미등록"
            echo "데이터를 다시 확인해 주시기바랍니다.<br>";
            break;

          // 아래의 사유들은 발송진행이 중단됨.
          case '85':   // "85:발송번호 미등록"
            echo "등록되지 않는 발송번호 입니다.<br />";
            break;
          case '87':   // "87:인증실패"
            echo "(정액제-계약확인)인증 받지 못하였습니다.<br />";
            break;
          case '88':   // "88:연동모듈 발송불가"
            echo "연동모듈 사용이 불가능합니다. 아이코드로 문의하세요.<br />";
            break;

          case '96':   // "96:토큰 검사 실패"
            echo "사용할 수 없는 토큰키입니다.<br />";
            break;
          case '97':   // "97:잔여코인부족"
            echo "잔여코인이 부족합니다.<br />";
            break;
          case '98':   // "98:사용기간만료"
            echo "사용기간이 만료되었습니다.<br />";
            break;
          case '99':   // "99:인증실패"
            echo "서비스 사용이 불가능합니다. 아이코드로 문의하세요.<br />";
            break;
          default:   // "미 확인 오류"
            echo "알 수 없는 오류로 전송이 실패하었습니다.<br />";
            break;
        }
        $fail++;
      } else {
        $resultString = '';
        switch (substr($code,0,2)) {
          case '17':   // "17: 접수(발송)대기 처리. 지연해소시 발송됨."
            echo "접수(발송)대기처리 되었습니다.";
            break;
          default:   // "00: 전송완료."
            echo "전송되었습니다.<br />";
            break;
        }
        echo $phone.'로 '.$resultString.' (msg seq : '.$code.')<br />';
        $success++;
      }
    }
    echo '<br />'.$success."건을 전송했으며 ".$fail."건을 보내지 못했습니다.<br />";
    $SMS->Init(); // 보관하고 있던 결과값을 지웁니다.

    /*
    if($success > 0){
      $row = sql_fetch("select max(wr_no) as wr_no from sms5_write");

      if ($row)
        $wr_no = $row['wr_no'] + 1;
      else
        $wr_no = 1;

      $sms_write ="insert into sms5_write set wr_no='{$wr_no}', wr_renum=0, wr_reply='{$strCallBack}', wr_message='{$_POST['strData']}', wr_booking='0000-00-00 00:00:00', wr_total='1', wr_datetime='".$now."'";
      print_r($sms_write);	
      sql_query($sms_write);
        
    
      $sms_history = "insert into sms5_history set wr_no='$wr_no', wr_renum=0, bg_no='0', mb_id='{$_POST['mb_id']}', bk_no='0', hs_name='', hs_hp='{$strTelList}', hs_datetime='".$now."', hs_flag='1', hs_code='', hs_memo='".addslashes($strTelList)."로 전송했습니다.', hs_log='01144 ".addslashes($_POST['strData'])."'";
      echo "<br>";
      sql_query($sms_history, false);
      print_r($sms_history);
      
    }
    */
  

	ob_clean();
	echo (json_encode(array("result" => "success",  "code" => "0000", "sql" => $code.'번호로 인증번호가 발송되었습니다.')));
  }
  else echo "에러: SMS 서버와 통신이 불안정합니다.<br />";
}
?>
