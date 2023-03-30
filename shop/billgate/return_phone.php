<?php
header('Content-Type: text/html; charset=euc-kr');
@extract($_REQUEST);
   
//---------------------------------------
// API 클래스 Include
//---------------------------------------
include "php/config.php";
include "php/class/Message.php";
include "php/class/MessageTag.php";
include "php/class/ServiceCode.php";
include "php/class/Command.php";
include "php/class/ServiceBroker.php";

//---------------------------------------
// API 인스턴스 생성
//---------------------------------------
$reqMsg = new Message(); //요청 메시지
$resMsg = new Message(); //응답 메시지
$tag = new MessageTag(); //태그
$svcCode = new ServiceCode(); //서비스 코드
$cmd = new Command(); //Command
$broker = new ServiceBroker($COMMAND, $CONFIG_FILE); //통신 모듈

//---------------------------------------
//Header 설정
//---------------------------------------
$reqMsg->setVersion("0100"); //버전 (0100)
$reqMsg->setMerchantId($SERVICE_ID); //가맹점 아이디
$reqMsg->setServiceCode($svcCode->MOBILE); //서비스코드

if($SERVICE_TYPE == "0000") {  //일반결제인경우
	$reqMsg->setCommand($cmd->AUTH_REQUEST); //승인 요청 Command
}else if($SERVICE_TYPE == "1000") { //자동결제인 경우
	$reqMsg->setCommand($cmd->AUTO_BILL_AGREE_AUTH_REQUEST); //승인 요청 Command	 	
}	
$reqMsg->setOrderId($ORDER_ID); //주문번호
$reqMsg->setOrderDate($ORDER_DATE); //주문일시(YYYYMMDDHHMMSS)

//print_r($_POST);
//---------------------------------------
//Check RESPONSE_CODE
//---------------------------------------
$isSuccess = false;
if(!strcmp($RESPONSE_CODE, "0000")) { // 인증 성공인 경우 결제(승인)요청
	//---------------------------------------
	//Check Sum
	//---------------------------------------
	if($CHECK_SUM){
		$temp = $SERVICE_ID.$ORDER_ID.$TRANSACTION_ID;
		//$temp = $SERVICE_ID.$ORDER_ID.$ORDER_DATE;
		$cmd = sprintf("%s \"%s\" \"%s\" \"%s\"", $COM_CHECK_SUM, "DIFF", $CHECK_SUM, $temp);
        //echo $temp;
        //echo $cmd;
        //exit;
		//$checkSum = exec($cmd) or die("ERROR:899900");	

		if($checkSum != 'SUC'){

			//---------------------------------------
			//Set Body
			//---------------------------------------
			if($TRANSACTION_ID != NULL) //거래번호
			        $reqMsg->put($tag->TRANSACTION_ID, $TRANSACTION_ID);
			if($CERT_NUMBER != NULL) //인증번호
			        $reqMsg->put($tag->CERT_NUMBER, $CERT_NUMBER);
			if($USER_EMAIL != NULL) //고객이메일
			        $reqMsg->put($tag->USER_EMAIL, $USER_EMAIL);			
			//---------------------------------------
			// Request
			//---------------------------------------
			$broker->setReqMsg($reqMsg); //요청 메시지 설정
			$broker->invoke($svcCode->MOBILE); //응답 요청
			$resMsg = $broker->getResMsg(); //응답 메시지 확인
			
			//---------------------------------------
			// Response 
			//---------------------------------------
			$RESPONSE_CODE = $resMsg->get($tag->RESPONSE_CODE);
			$RESPONSE_MESSAGE = $resMsg->get($tag->RESPONSE_MESSAGE);
			$DETAIL_RESPONSE_CODE = $resMsg->get($tag->DETAIL_RESPONSE_CODE);
			$DETAIL_RESPONSE_MESSAGE = $resMsg->get($tag->DETAIL_RESPONSE_MESSAGE);
			

			if(!strcmp($resMsg->get($tag->RESPONSE_CODE), "0000")) {
			  $AUTH_AMOUNT = $resMsg->get($tag->AUTH_AMOUNT);
			  $SESSION_KEY = $resMsg->get($tag->SESSION_KEY);	
              $PROTOCOL_NUMBER = $resMsg->get($tag->PROTOCOL_NUMBER);				
			  
			  $isSuccess = true;
			}
		}
	}

}
if($isSuccess == true) {
	//---------------------------------------
	// 가맹점 수정 - 성공 시 가맹점 결과 처리 
	// 1. 가맹점 주문번호 및 갤럭시아 거래번호로 성공 결과 DB 저장
	// 2. 결제 성공 페이지 호출
	//---------------------------------------
?>
<html>
<head>
<script type="text/javascript">
<!--
function setBillgateResult() {
    try {
        opener.payment_return('<?=$TRANSACTION_ID?>', '<?=$AUTH_AMOUNT?>', '<?=$SERVICE_CODE?>');
        self.close();
    } catch (e) {
        alert(e.message);
    }
}

setBillgateResult();
//-->
</script>
</head>
</body>
</html>
<?php
}else {
	//---------------------------------------
	// 가맹점 수정 - 실패 시 가맹점 결과 처리 
	// 1. 가맹점 주문번호 및 갤럭시아 거래번호로 실패 결과 DB 저장
	// 2. 결제 실패 페이지 호출
	//---------------------------------------
?>
<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=euc-kr">
<link href="css/css_admin.css" rel="stylesheet" type="text/css">
<link href="css/css_01.css" rel="stylesheet" type="text/css">
<head>
<!-- 키 방어 코드 -->
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">	
<table width="500" border="0" cellpadding="0"	cellspacing="0">
	<tr> 
	  <td height="25" style="padding-left:10px" class="title01"> 
		# 현재위치 &gt;&gt; 휴대폰 &gt; <b>가맹점 Return Url</b></td>
	</tr>
	<!--히스토리-->
	<!--title-->
	<tr>
		<td height="54" background="images/manager_title01.gif"
			style="padding-left: 65px; padding-top: 18px"><font size="3"><strong>가맹점 Return Url</strong></font></td>
	</tr>
	<!--title-->
	<tr>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td align="center"><!--본문테이블 시작--->
		<table width="450" border="0" cellpadding="4" cellspacing="1" bgcolor="#B0B0B0">	
			<tr>
				<td width="100" align="center" bgcolor="#F6F6F6"><b>가맹점 아이디</b></td>
				<td width="200" align="left" bgcolor="#FFFFFF">&nbsp;<b><?php echo $SERVICE_ID ?></b>
				</td>								
			</tr>
				<tr>
				<td width="100" align="center" bgcolor="#F6F6F6"><b>주문번호</b></td>
				<td width="200" align="left" bgcolor="#FFFFFF">&nbsp;<b><?php echo $ORDER_ID ?></b>
				</td>								
			</tr>
			<tr>
				<td width="100" align="center" bgcolor="#F6F6F6"><b>주문일시</b></td>
				<td width="200" align="left" bgcolor="#FFFFFF">&nbsp;<b><?php echo $ORDER_DATE ?></b>
				</td>								
			</tr>
			<tr>
				<td width="100" align="center" bgcolor="#F6F6F6"><b>거래번호</b></td>
				<td width="200" align="left" bgcolor="#FFFFFF">&nbsp;<b><?php echo $TRANSACTION_ID ?></b>
				</td>								
			</tr>
			<tr>
				<td width="100" align="center" bgcolor="#F6F6F6"><b>응답코드</b></td>
				<td width="200" align="left" bgcolor="#FFFFFF">&nbsp;<b><?php echo $RESPONSE_CODE ?></b>
				</td>								
			</tr>
			<tr>
				<td width="100" align="center" bgcolor="#F6F6F6"><b>응답메시지</b></td>
				<td width="200" align="left" bgcolor="#FFFFFF">&nbsp;<b><?php echo $RESPONSE_MESSAGE ?></b>
				</td>								
			</tr>
			<tr>
				<td width="100" align="center" bgcolor="#F6F6F6"><b>상세응답코드</b></td>
				<td width="200" align="left" bgcolor="#FFFFFF">&nbsp;<b><?php echo $DETAIL_RESPONSE_CODE ?></b>
				</td>								
			</tr>
			<tr>
				<td width="100" align="center" bgcolor="#F6F6F6"><b>상세응답메시지</b></td>
				<td width="200" align="left" bgcolor="#FFFFFF">&nbsp;<b><?php echo $DETAIL_RESPONSE_MESSAGE ?></b>
				</td>								
			</tr>
		</table>
		</td>
	</tr>
</table>
</body>
</html>
<?php
}
?>