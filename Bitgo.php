<?php

/*
 * @brief Bitgo API Class
 * Bitgo API를 사용하기 위한 Class입니다.
 * "https://www.bitgo.com/api/v2/"를 참고하였습니다.
 */
class Bitgo
{
    /*
     * @brief Bitgo API의 Baseurl 설정
     */
    const BITGO_MAINNET_API_BASEURL = 'https://www.bitgo.com/api/v2/';
    const BITGO_TESTNET_API_BASEURL = 'https://test.bitgo.com/api/v2/';
    const BITGO_EXPRESS_API_BASEURL = 'http://localhost:3080/api/v2/';

    private $accessToken = "";
    private $allowedCoins = array('btc', "tbtc");
    private $params = array();

    /*
     * @brief 생성자
     *
     * @param $accessToken Bitgo에서 발급받은 API AccessToken
     * @param $coin        사용할 코인의 종류
     * @param $testNet     테스트넷 여부
     */
    public function __construct($accessToken, $coin, $testNet = true) {
        $this->accessToken = $accessToken;
        $this->coin = $coin;
        $this->testNet = $testNet;
        $this->APIEndpoint = (!$this->testNet) ? self::BITGO_MAINNET_API_BASEURL : self::BITGO_TESTNET_API_BASEURL;
        $this->ExpressEndpoint = self::BITGO_EXPRESS_API_BASEURL;
        if (!in_array($this->coin, $this->allowedCoins)) {
            throw new Exception('You are trying to use an invalid coin');
        }
    }

    /*
     * @brief Express API가 살아있는지 확인
     */
    public function ping() {
        $this->url = $this->ExpressEndpoint . 'pingexpress';
        $this->params = array();
        return $this->_execute('GET');
    }

    /*
     * @brief 지갑 생성
     *
     * @label      지갑의 레이블
     * @passphrase 지갑 비밀번호
     */
    public function generateWallet($label, $passphrase) {
        $this->url = $this->ExpressEndpoint . $this->coin . '/wallet/generate';
        $this->params = array(
            'label' => $label,
            'passphrase' => $passphrase,
        );
        return $this->_execute('POST');
    }

    /*
     * @brief Transfer 정보를 읽어온다
     *
     * @param $walletId   Bitgo의 지갑 ID
     * @param $transferId Bitgo의 Transfer ID
     */
    public function getTransfer($walletId, $transferId) {
        $this->url = $this->APIEndpoint . $this->coin . '/wallet/' . $walletId . '/transfer/' . $transferId;
        $this->params = array();
        return $this->_execute('GET');
    }

    /*
     * @brief 지갑의 정보를 읽어온다
     *
     * @param $walletId Bitgo의 지갑 ID
     * @param $allToken Ethereum일 경우에 Token들에 대한 정보도 읽어옴.
     */
    public function getWallet($walletId, $allTokens = false) {
        $this->url = $this->APIEndpoint . $this->coin . '/wallet/' . $walletId;
        $this->params = array();
        return $this->_execute('GET');
    }

    /*
     * @brief 지갑정보를 주소로 가져온다.
     *
     * @param @address 지갑의 정보를 가져올 주소
     */
    public function getWalletByAddress($address) {
        $this->url = $this->APIEndpoint . $this->coin . '/wallet/address/' . $address;
        $this->params = array();
        return $this->_execute('GET');
    }

    /*
     * @brief 지갑의 주소가 올바른지 확인한다.
     *
     * @param 확인할 지갑의 주소
     */
    public function verifyAddress($address) {
        $this->url = $this->ExpressEndpoint . $this->coin . '/verifyaddress';
        $this->params = array(
            'address' => $address,
        );
        return $this->_execute('POST');
    }

    /*
     * @brief 이전블록의 최적의 Fee Rate(바이트당 수수료)를 예측한다.
     *
     * @param $numBlocks 계산할 수수료의 이전 블록 갯수
     */
    public function feeEstimate($numBlocks = null) {
        $this->url = $this->APIEndpoint . $this->coin . '/tx/fee';
        $this->params = array(
            'numBlocks' => $numBlocks,
        );
        return $this->_execute('GET');
    }

    /*
     * @brief Transaction의 최적의 Fee를 예측한다.
     *
     * @param $txsize Transaction의 사이즈
     */
    public function getEstimateTxFee($txsize) {
        $fee = $this->feeEstimate();
        return array(
            "txsize" => $txsize,
            "feePerKb" => $fee['feePerKb'],
            "estimateTxFee" => round($txsize * ($fee['feePerKb']/1024)),
        );
    }

    /*
     * @brief Transaction당 수수료를 계산한다.
     *
     * @param $feeRate
     * @param $nOutputs         TX의 Output 갯수
     * @param $nP2shInputs      TX의 P2SH Input 갯수
     * @param $nP2pkhInputs     TX의 P2PKH Input 갯수
     * @param $nP2shP2wshInputs TX의 P2WSH Input 갯수
     */
    public function calculateMiningFee($feeRate = 1000, $nOutputs = 2, $nP2shInputs = 1, $nP2pkhInputs = 1, $nP2shP2wshInputs = 1) {
        $this->url = $this->ExpressEndpoint . 'calculateminerfeeinfo';
        $this->params = array(
            'feeRate' => $feeRate,
            'nOutputs' => $nOutputs,
            'nP2shInputs' => $nP2shInputs,
            'nP2pkhInputs' => $nP2pkhInputs,
            'nP2shP2wshInputs' => $nP2shP2wshInputs,
        );
        return $this->_execute('POST');
    }

    /*
     * @brief Transaction을 전송한다.
     * 지갑에 들어있는 코인을 다른 주소로 송금한다.
     *
     * @param $walletId         송금할 지갑의 ID
     * @param $address          수신할 주소
     * @param $amount           송금할 코인의 갯수
     * @param $walletPassphrase 지갑 비밀번호
     * @param $comment          송금시 기록할 Comment
     */
    public function sendTransaction($walletId, $address, $amount, $walletPassphrase, $comment="") {
        $this->url = stripslashes($this->ExpressEndpoint . $this->coin . '/wallet/' . $walletId . '/sendcoins');
        $this->params = array(
            'address' => $address,
            'amount' => $amount,
            'walletPassphrase' => $walletPassphrase,
            'comment' => $comment,
        );
        return $this->_execute('POST');
    }

    /*
     * @brief Webhook을 추가한다.
     *
     * @param $walletId         Webhook을 추가할 지갑 ID
     * @param $webhook_url      추가할 Webhook의 주소
     * @param @numConfirmations Webhook을 발생시킬 Confirm의 갯수
     */
    public function addWalletWebhook($walletId, $webhook_url, $numConfirmations = 2) {
        $this->url =  $this->APIEndpoint . $this->coin . '/wallet/' . $walletId . '/webhooks';
        $this->params = array(
            'url' => $webhook_url,
            'type' => 'transfer',
            'numConfirmations' => $numConfirmations,
        );
        return $this->_execute('POST');
    }

    /*
     * @brief 외부의 HTTP URL을 호출하고 그 결과값을 반환한다.
     *
     * @param $requestType HTTP Method Type
     * @param $array       반환할 객체의 array 여부
     */
    private function _execute($requestType = 'POST', $array = true) {
        $ch = curl_init($this->url);

        if ($requestType === 'GET') {
            curl_setopt($ch, CURLOPT_URL, $this->url . '?' . http_build_query(array_filter($this->params)));
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        } elseif ($requestType === 'PUT') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(array_filter($this->params)));
        } elseif ($requestType === 'DELETE') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(array_filter($this->params)));
        } elseif ($requestType === 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(array_filter($this->params)));
        } elseif ($requestType === 'PUT') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(array_filter($this->params)));
        }
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->accessToken
        ));
        if (defined('CURLOPT_IPRESOLVE') && defined('CURL_IPRESOLVE_V4')){
            curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        }
        $response = curl_exec($ch);
        curl_close($ch);
        return json_decode($response, $array);
    }
}
