<?
/*
 * @brief 회사 계정의 주소에서 외부의 주소로 송금합니다.
 *
 * @param $_POST['to']         송금할 주소
 * @param $_POST['value']      송금할 코인의 갯수(Satoshi)
 *                             값이 'all'일 경우에는 모든 코인을 송금함.
 * @param $_POST['passphrase'] 지갑의 비밀번호
 */

include_once("./_common.php");
include_once('./Bitgo.php');
include_once('./wallet_config.php');

ob_clean();



function response($message, $err=false) {
    echo json_encode(array(($err) ? "error":"result" => $message));
    return;
}
if (!$_POST['to'] || !$_POST['value'] || !$_POST['passphrase']) {
    return response("Wrong form data.", true);
}

// 로그인된 사용자인지 확인
if (!$is_member) {
    return response("Permission denied");
}

$bitgo = new Bitgo(BITGO_ACCESS_KEY, COIN, TESTNET);

// 보내고자 하는 주소가 유효한지 확인. (포맷만 확인)
$res = $bitgo->verifyAddress($_POST['to']);
if (!$res || !$res['isValid']) {
    return response("Address is invalid.", true);
}

// 회사 계정에 등록된 주소의 지갑 정보를 가져옴
$wallet = $bitgo->getWalletByAddress(COMPANY_ADDRESS);
if (!$wallet || isset($wallet["error"])) {
    return response("Get Wallet Failed.", true);
}

$spendableBalance = $wallet['spendableBalance'];
// value가 all일 경우에는 모든 잔액을 송금
$sendAmount = ($_POST['value'] == 'all') ? $spendableBalance:$_POST['value'];
if ($spendableBalance < $sendAmount) {
    return response("Not enough balances.", true);
}

// 회사계정 주소의 지갑을 소유하고 있는 사용자 정보
$user = sql_fetch(" select mb_id, mb_email from g5_member where my_walletId='{$wallet['id']}'");
if(!$user) {
    return response("Can't find user.", true);
}



// 회사계정 소유의 아이디와 로그인된 아이디가 같은지 확인
if ($user['mb_id'] != $member['mb_id']) {
    return response("Allow to send from own wallet", true);
}

// 지갑의 비밀번호는 직접 입력을 받는다.
// 이미 저장되어 있는 지갑의 비밀번호를 사용하려면 $user['mb_email'] 이용하면 되지만 위험함.
$res = $bitgo->sendTransaction($wallet['id'], $_POST['to'], $sendAmount, $_POST['passphrase']);
if(!$res) {
    return response("No response from bitgo", true);
}

if ($res['error']) {
    // balance 부족으로 송금이 안되면 최대 보낼 수 있는 금액을 송금함.
    if ($res['name'] === 'InsufficientBalance') {
        $sendAmount = $res['balance'] - ($res['sendAmount'] - $res['balance']);
        $res = $bitgo->sendTransaction($wallet['id'], $_POST['to'], $sendAmount, $_POST['passphrase']);
        if (!$res || $res['error']) {
            return response($res['error'], true);
        }
    } else {
        // 'InsufficientBalance' 오류 외에 다른 오류는 에러로그에 기록합니다.
        return response($res['error'], true);
    }
}

$transfer = $res['transfer'];

$sql = "insert into wallet_send (mb_id, coin, wallet, txid, transfer, value, from_address, to_address, createdAt)" .
    "values ('".$user['mb_id'] . "', '" . $transfer['coin'] . "', '" . $transfer['wallet'] . "', '" . $transfer['txid'] .
    "', '" . $transfer['id'] . "', '" . $transfer['value'] . "', '" . COMPANY_ADDRESS . "', '" . $_POST['to'] . "', NOW())";
$res = sql_query($sql);
if (!$res) {
    return response("Failed to write transfer into the database.", true);
}

return response($transfer);
