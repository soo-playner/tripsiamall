<?
/*
 * @brief 회사 계정의 주소에서 외부의 주소로 송금합니다.
 *
 * @param $_POST['to']         송금할 주소
 * @param $_POST['value']      송금할 코인의 갯수(Satoshi)
 *                             값이 'all'일 경우에는 모든 코인을 송금함.
 * @param $_POST['passphrase'] 지갑의 비밀번호
 */

include_once('../Bitgo.php');
include_once('../wallet_config.php');

$bitgo = new Bitgo(BITGO_ACCESS_KEY, COIN, TESTNET);

// 회사 계정에 등록된 주소의 지갑 정보를 가져옴
$wallet = $bitgo->getWalletByAddress(COMPANY_ADDRESS);

print_r("총잔고 : ".$wallet['balance']);

?>
