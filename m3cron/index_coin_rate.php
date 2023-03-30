<?
include_once("./_common.php");

$now_date_time = date('Y-m-d H:i:s');
$now_date = date('Y-m-d');
/*
curl --request GET \
  --url 'https://web3api.io/api/v2/market/prices/usdt/latest?timeFormat=iso' \
  --header 'x-api-key: UAK63a987b583b5a7156dfda3d4d070c6e1'
*/

define('X_API_KEY','UAK63a987b583b5a7156dfda3d4d070c6e1');

/*ETH, USDT 코인시세 가져오기*/
$url = 'https://web3api.io/api/v2/market/rankings?page=0&size=30';

if ($argc > 1){
    $url = $url.$argv[1];
}

$ch=curl_init();

$header_data = [];
$header_data = array('Accept: application/json', 'Content-Type: application/json');
$header_data[] = 'x-api-key: '.X_API_KEY;
// user credencial

curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $header_data);
curl_setopt($ch, CURLOPT_VERBOSE, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);


$response = curl_exec($ch);
curl_close($ch);

//var_dump($response);
//$header_size = 0;

//  $header_size = curl_getinfo($ch);
$header = substr($response, 0, 1);
$body = substr($response, $header_size);    

$body_json = json_decode($body, true);
$coin_data = $body_json['payload']['data'];

$coin_list = [];
$coin_config = sql_query("SELECT symbol from `{$g5['coin_price']}`");
while($row = sql_fetch_array($coin_config)){
  array_push($coin_list,strtolower($row['symbol']));
}



$i = 0;
while($i < count($coin_data)){

  if(in_array(strtolower($coin_data[$i]['symbol']),$coin_list)){

    $symbol = strtolower($coin_data[$i]['symbol']);
    $currentPrice = $coin_data[$i]['currentPrice'];
    $changeInPriceDaily = $coin_data[$i]['changeInPriceDaily'];
    $icon = $coin_data[$i]['icon'];
    $name = $coin_data[$i]['name'];

    $sql = " UPDATE {$g5['coin_price']} set name = '{$name}', current_cost = '{$currentPrice}', changepricedaily = '{$changeInPriceDaily}', update_time = '{$now_date_time}',icon = '{$icon}' WHERE symbol = '{$symbol}'" ;
    // print_R($sql);
    sql_query($sql);
  }
  $i++;
}

if($_GET['url']){
  alert('현재시세를 반영했습니다.');
  goto_url('/adm/config_price.php');
}else{
  // print_r("complete <br>".$now_date_time);
}

?>
