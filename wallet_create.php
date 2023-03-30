<?
include_once("./_common.php");
include_once('Bitgo.php');
include_once('wallet_config.php');

ob_clean();

if (!$_POST['mb_id'] || !$_POST['mb_email']) {
    echo json_encode(array("result" => "FAIL"));
    return;
}

// 로그인된 사용자인지 확인
if (!$is_member) {
    echo json_encode(array("error" => "Permission denied"));
    return;
}

// 로그인된 사용자와 지갑 주소를 만들고자 하는 아이디가 같은지 확인
if ($_POST['mb_id'] != $member['mb_id'] || $_POST['mb_email'] != $member['mb_email']) {
    echo json_encode(array("error" => "Allow to create own wallet"));
    return;
}

$bitgo = new Bitgo(BITGO_ACCESS_KEY, COIN, TESTNET);

// 지갑생성
$res = $bitgo->generateWallet($_POST['mb_id'], $_POST['mb_email']);
if (!$res || isset($res["error"])) {
    echo json_encode(array("error" => "Generate Wallet Failed."));
    return;
}

if (!isset($res['receiveAddress'])) {
    echo json_encode(array("error" => "Generate Wallet Address Failed."));
    return;
}

// Webhook 생성
$walletInfo = $res['receiveAddress'];
$res = $bitgo->addWalletWebhook($walletInfo['wallet'], WEBHOOK_URL, 1);
if (!$res || isset($res["error"])) {
    echo json_encode(array("error" => "Generate Wallet hook Failed."));
    return;
}

$sql = "update g5_member set mb_wallet = '" . $walletInfo['address'] . "', my_walletId = '" . $walletInfo['wallet'] .
       "', bitgoId = '" . $walletInfo['id'] . "', webhook = '" . $res['id'] . "' where mb_id = '" . $_POST['mb_id']."'";
$result = sql_query($sql);
//print_r($sql);

if($result){
    echo json_encode(array("result" => "success",  "address" => $walletInfo['address']));
} else {
    echo json_encode(array("error" => "Writing Database Failed."));
}
