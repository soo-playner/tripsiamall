<?php
@extract($_REQUEST);

//---------------------------------------
// Include
//---------------------------------------
include G5_SHOP_PATH."/billgate/php/config.php";
include G5_SHOP_PATH."/billgate/php/class/Message.php";
include G5_SHOP_PATH."/billgate/php/class/MessageTag.php";
include G5_SHOP_PATH."/billgate/php/class/ServiceCode.php";
include G5_SHOP_PATH."/billgate/php/class/Command.php";
include G5_SHOP_PATH."/billgate/php/class/ServiceBroker.php";


$today	= mktime(); 
$today_time = date('YmdHis', $today);

//parameter
$orderDate 	= $today_time;
$orderId = $od_id;
$transactionId = $od_tno;	//취소건의 거래번호
$amount = $amount;    //취소건의 금액

//---------------------------------------
//Create Instance
//---------------------------------------
$reqMsg = new Message(); 
$resMsg = new Message(); 
$tag = new MessageTag();
$svcCode = new ServiceCode(); 
$cmd = new Command(); 
$broker = new ServiceBroker($COMMAND, $CONFIG_FILE);

//---------------------------------------
//Header 
//---------------------------------------
$reqMsg->setVersion("0100"); 
$reqMsg->setMerchantId($serviceId); 
$reqMsg->setServiceCode($_POST['SERVICE_CODE']);
if ($_POST['SERVICE_CODE'] == "0900") {
    $reqMsg->setCommand($cmd->CANCEL_SMS_REQUEST); 
} else {
    $reqMsg->setCommand($cmd->CANCEL_REQUEST); //승인 취소 요청 Command
}
$reqMsg->setOrderId($orderId); 
$reqMsg->setOrderDate($orderDate);

//---------------------------------------
//Body 
//---------------------------------------
if($transactionId != NULL) 
	$reqMsg->put($tag->TRANSACTION_ID, $transactionId);                              
if($amount != NULL) 
	$reqMsg->put($tag->DEAL_AMOUNT, $amount);   

//---------------------------------------
//Request
//---------------------------------------
$broker->setReqMsg($reqMsg); 
$broker->invoke($_POST['SERVICE_CODE']); 
$resMsg = $broker->getResMsg();

//---------------------------------------
//Response 
//---------------------------------------
$msg = $resMsg->get($tag->RESPONSE_MESSAGE); 

$RESPONSE_CODE = $resMsg->get($tag->RESPONSE_CODE);
$RESPONSE_MESSAGE = $resMsg->get($tag->RESPONSE_MESSAGE);
$DETAIL_RESPONSE_CODE = $resMsg->get($tag->DETAIL_RESPONSE_CODE);
$DETAIL_RESPONSE_MESSAGE = $resMsg->get($tag->DETAIL_RESPONSE_MESSAGE);
?>