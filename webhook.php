<?
/*
 * Bitgo에서 어떠한 주소로 입금이 완료되거나 송금이 완료되면 호출하게 됩니다.
 * 호출한 지갑의 정보의 사용자의 주소의 코인을 회사 주소로 송금하고 송금이 완료되면 Token으로 변환하게 됩니다.
 * 등록되어 있는 지갑의 정상적인 입금이라면 입금 완료시 한번 호출되고 송금이 완료시 한번 더 호출됩니다.
 *
 * 요청시 보내어지는 정보에는 지갑과 송금 정보만 있기때문에 이미 DB에 등록되어있는 사용자가 아니면 처리하지 않습니다.
 * 이는 악의적인 외부의 불필요한 요청에도 내부의 로직을 실행하지 않도록 하여 문제가 발생하지 않도록 합니다.
 */

include_once("./_common.php");
include_once('./Bitgo.php');
include_once('./wallet_config.php');

/*
 * @brief Webhook 요청을 파일에 기록합니다.
 * Webhook은 외부에서 호출하는 것이므로 어떤 요청이 왔는지 알기 어렵고 오류발생시 오류를 파악하기도 어렵습니다.
 * 따라서 File에 Log를 남기어 요청되어진 Webhook의 정보를 알도록 합니다.
 *
 * @param $messge 기록한 message
 * @param $error  Error Log
 */
function writeLog($message, $error=false) {
    $filename = ($error) ? "data/webhook.err.log":"data/webhook.log";
    $file = fopen($filename, "a");
    $currTime = date("Y-m-d H:i:s");
    $msg = "[{$currTime}] ";
    $msg .= json_encode($message)."\n";
    fwrite($file, $msg);
    fclose($file);
}



$bitgo = new Bitgo(BITGO_ACCESS_KEY, COIN, TESTNET);

//ob_clean();

$payload = json_decode(file_get_contents('php://input'), true);
if (!isset($payload)) {
    writeLog(json_encode(array("error"=>"No request data")), true);
    return;
}
// Webhook으로 부터 온 요청에 대한 정보를 Log에 기록합니다.
// Webserver Log에는 요청여부만 알 수 있고 Webhook 처리시 오류 발생시 로그를 기록합니다.
writeLog(json_encode($payload));

// Webhook의 요청이 "transfer" 타입만 처리하도록 합니다. "transaction" 타입은 처리하지 않습니다.
if ($payload['type'] != 'transfer') {
    writeLog(json_encode(array("error"=>"Only transfer type will be processed", "payload"=>$payload)), true);
    return;
}

// 지갑의 정보를 가져옵니다. 만약 요청한 지갑의 정보가 Bitgo에 있지 않으면 오류를 발생시킵니다.
$wallet = $bitgo->getWallet($payload['wallet']);
if (!isset($wallet) || isset($wallet['error'])) {
    writeLog(json_encode(array("error"=>"Can't find wallet", "payload"=>$payload)), true);
    return;
}
// 해당 지갑의 사용가능한 코인의 갯수
$spendableBalance = $wallet['spendableBalance'];

// 송금정보를 가져옵니다.
$transfer = $bitgo->getTransfer($wallet['id'], $payload['transfer']);
if (!isset($transfer) || isset($transfer['error'])) {
    writeLog(json_encode(array("error"=>"Can't find transfer", "payload"=>$payload)), true);
    return;
}
// 송금할 코인의 갯수
$transferBalance = $transfer['value'];

// 회사의 지갑에서 Webhook을 호출했을 경우에 처리부분입니다.
// 별도의 처리를 하지 않아도 문제는 발생하지 않습니다.
if ($wallet['id'] == COMPANY_WALLET) {
    return;
}

// 요청한 지갑의 등록된 사용자 정보를 가져옵니다.
$user = sql_fetch(" select mb_id, mb_email from g5_member where my_walletId='{$wallet['id']}'");
if(!$user) {
    writeLog(json_encode(array("error"=>"Can't find user.", "payload"=>$payload)), true);
    return;
}

// Webhook의 정보를 DB에 기록
$sql = "insert into wallet_income (mb_id, hash, transfer, transfer_value, coin, type, state, wallet, wallet_value, createdAt)" .
    "values ('".$user['mb_id'] . "', '" . $payload['hash'] . "', '" . $payload['transfer'] . "', '" . $transferBalance .
    "', '" . $payload['coin'] . "', '" . $payload['type'] . "', '" . $payload['state'] . "', '" . $payload['wallet'] .
    "', '" . $spendableBalance . "', NOW())";
$res = sql_query($sql);
if (!$res) {
    writeLog(json_encode(array("error"=>"Failed to write into the database.", "payload"=>$payload)), true);
    return;
}

// 사용자의 지갑에서 회사계정으로 송금을 하고 완료되었을때 Webhook이 다시 호출됩니다.
// 이 호출을 통해서 사용자의 지갑에서 송금이 정상적으로 되었는지를 알게되고 Token으로 변환하게 됩니다.
if ($transfer['type'] == 'send') {
    // Webhook의 호출이 사용하는 코인이 아닐경우에 오류처리
    if ($transfer['coin'] != COIN) {
        writeLog(json_encode(array("error"=>"Wrong Coin({$transfer['coin']})", "payload"=>$payload)), true);
        return;
    }

    // 악의적인 요청일 수 있으므로 요청한 정보를 그대로 사용하는 것이 아니라 이미 입금시 기록했던 정보를 가져옵니다.
    $send_transfer = sql_fetch(
        " select mb_id, value, token from wallet_income_transfer" .
        " where wallet='{$wallet['id']}' and transfer = '".$transfer['id']."'");
    if(!$send_transfer) {
        writeLog(json_encode(array("error"=>"Can't find transfer information.", "payload"=>$payload)), true);
        return;
    }

    // 요청한 정보와 DB에 있는 정보가 다를 경우에는 오류를 발생합니다.
    // mb_id를 알아내기 위해서 이미 여러가지로 검증을 했으므로 ID만 비교해서 처리합니다.
    if ($user['mb_id'] != $send_transfer['mb_id']) {
        writeLog(json_encode(array("error"=>"Different request user.", "payload"=>$payload)), true);
        return;
    }

    // DB에 있는 기록에 이미 Token으로 변환한 것인지 체크합니다.
    if ($send_transfer['token'] != 0) {
        writeLog(json_encode(array("error"=>"Already convert to token.", "payload"=>$payload)), true);
        return;
    }

    // 환전할 Token의 갯수를 계산합니다.
    $sevenToken = abs($send_transfer['value']) * SATOSHI_PER_SEVEN;

    // 사용자 테이블의 토큰 갯수를 업데이트합니다.
    $sql = "update g5_member set mb_btc_account = mb_btc_account + " . ($sevenToken/1000000000) .
           " where mb_id = '".$user['mb_id']."'";
    $res = sql_query($sql);
    if (!$res) {
        writeLog(json_encode(array("error"=>"Failed to update token amount.", "payload"=>$payload)), true);
        return;
    }

    // 출금기록에 환전 정보를 남기고 종료합니다.
    $sql = "update wallet_income_transfer set token = '".$sevenToken."', exchangedAt = NOW() " .
           "where wallet = '".$transfer['wallet']."' and transfer = '".$transfer['id']."'";
    $res = sql_query($sql);
    if (!$res) {
        writeLog(json_encode(array("error"=>"Failed to exchange bitcoin to token.", "payload"=>$payload)), true);
        return;
    }

    // 송금 완료 처리는 이미 완료되었으므로 다음 로직을 타지 않도록 종료합니다.
    return;
}

// Token으로 환전할 최소한의 입금금액을 체크합니다.
if ($spendableBalance < MINIMUM_BALANCE) {
    writeLog(json_encode(array("error"=>"Not enough balances", "payload"=>$payload)), true);
    return;
}

// 회사계정으로 코인을 송금합니다.
// 가장 효과적인 Transaction의 수수료를 계산하는 방법은 일부로 오류를 내는 방법입니다.
// 수수료 계산을 하지 않고 지갑의 모든 잔액을 송금하였을 경우에 오류메시지를 통해서 Bitgo 내부적으로 자동 계산한 수수료를 알 수가 있습니다.
// 결국 첫번째 오류 요청을 통해 수수료를 얻어오게되고 해당 수수료로 계산하여 정상 요청을 실행합니다.
$res = $bitgo->sendTransaction($wallet['id'], COMPANY_ADDRESS, $spendableBalance, $user['mb_email'], $user['mb_id']);
if(!$res) {
    writeLog(json_encode(array("error"=>"No response from bitgo(1)", "payload"=>$payload)), true);
    return;
}

// 첫번째 요청이 오류가 떨어지지 않게되면 별도의 수수료 계산없이 그대로 진행됩니다.
if ($res['error']) {
    if ($res['name'] === 'InsufficientBalance') {
        // 의도한 오류일 경우에는 오류메시지를 통하여 수수료를 계산하여 송금할 코인의 갯수를 찾아냅니다.
        $sendAmount = $res['balance'] - ($res['sendAmount'] - $res['balance']);
        $res = $bitgo->sendTransaction($wallet['id'], COMPANY_ADDRESS, $sendAmount, $user['mb_email'], $user['mb_id']);
        if (!$res || $res['error']) {
            writeLog(json_encode(array("error" => $res['error'], "payload"=>$payload)), true);
            return;
        }
    } else {
        // 'InsufficientBalance' 오류 외에 다른 오류는 에러로그에 기록합니다.
        writeLog(json_encode(array("error" => $res['error'], "payload"=>$payload)), true);
        return;
    }
}

// 송금이 완료되면 완료된 송금정보를 기록합니다. 정상적으로 기록되지 않으면 Token으로 환전이 불가능합니다.
$transfer = $res['transfer'];

$sql = "insert into wallet_income_transfer (mb_id, coin, wallet, txid, transfer, value, address, createdAt)" .
       "values ('".$user['mb_id'] . "', '" . $transfer['coin'] . "', '" . $transfer['wallet'] . "', '" . $transfer['txid'] .
       "', '" . $transfer['id'] . "', '" . $transfer['value'] . "', '" . COMPANY_ADDRESS . "', NOW())";
$res = sql_query($sql);


if (!$res) {
    writeLog(json_encode(array("error"=>"Failed to write transfer into the database.", "payload"=>$payload)), true);
    return;
}

echo json_encode($transfer);
