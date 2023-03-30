<?
        //최종결제요청 결과 성공 DB처리
$tno             = $_POST['TRANSACTION_ID'];    // 거래번호
$amount          = $_POST['AUTH_AMOUNT'];       // 결제금액
$app_time        = $_POST['ORDER_DATE'];        // 거래일시
$bank_name       = '';
$depositor       = '';
$account         = '';
$commid          = '';
$mobile_no       = '';
$app_no          = '';
$card_name       = '';
$pay_type        = $_POST['PAY_METHOD'];
$escw_yn         = 'N';
?>