<?
use neto737\BitGoSDK\BitGoExpress;
use neto737\BitGoSDK\Enum\CurrencyCode;

$hostname = 'localhost'; //THE HOSTNAME YOU INSTALLED THE BITGOEXPRESS (1st step)
$port = 3080; //THE PORT YOU SET IN YOUR BITGOEXPRESS INSTALLATION
$coin = CurrencyCode::BITCOIN_TESTNET; //THE COIN YOU WANT TO USE

$bitgoExpress = new BitGoExpress($hostname, $port, $coin);
$bitgoExpress->accessToken = 'YOUR_API_KEY_HERE'; //YOUR API KEY (you can get it using 'login' function of this SDK)
$bitgoExpress->walletId = 'YOUR_WALLET_ID_HERE';
?>