<?
include_once("./_common.php");

// curl 사용
$coin  = 'tbtc';

/*
$_POST['mb_id'] = "sevenTest";
$_POST['mb_email'] = "soo@willsoft.kr";
*/

$post_data["label"] = $_POST['mb_id'];
$post_data["passphrase"] = $_POST['mb_email'];
$post_data = json_encode($post_data);


$Enter = $coin."/wallet/generate";

$BITGO_EXPRESS_HOST= 'localhost:3080';
$url = "http://".$BITGO_EXPRESS_HOST."/api/v2/".$Enter;
//echo "<br>". $url ."<br><br>";


$access_token_value = "v2x81f70886ea9a3062f76e44b9f9c397911658131a0442975033313332800d3cf9";
$header_data[] = 'Authorization: Bearer '.$access_token_value;
$header_data[] = 'Content-Type:application/json';


$ch = curl_init(); //curl 초기화
curl_setopt($ch, CURLOPT_URL, $url); //URL 
curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); //요청결과 문자열 반환
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

curl_setopt($ch, CURLOPT_HEADER, true);//헤더 정보를 보내도록 함(*필수)
curl_setopt($ch, CURLOPT_HTTPHEADER, $header_data); //header 지정하기
curl_setopt ($ch, CURLOPT_POSTFIELDS, $post_data); //POST로 보낼 데이터 지정하기

curl_setopt($ch, CURLOPT_POST, 1); 

$response = curl_exec ($ch);

//$data = json_decode($response);
//print_r($response);
$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
$header = substr($response, 0, $header_size);
$bodyresult = substr($response, $header_size);    
 

$body_json = json_decode($bodyresult, true);
//print_r($body_json[receiveAddress][id]);
$walletInfo = $body_json['receiveAddress'];


$sql = "update g5_member set mb_wallet = '".$walletInfo['address']."', my_walletId = '". $walletInfo['wallet']."', bitgoId = '". $walletInfo['id']."' where mb_id = '".$_POST['mb_id']."'";
$result = sql_query($sql);
curl_close($ch);

ob_clean();

if($result){
	echo (json_encode(array("result" => "success",  "code" => "0000", "sql" => $sql)));
}
?>

