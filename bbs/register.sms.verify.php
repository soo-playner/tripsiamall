<?
include_once('./_common.php');

header('Content-Type: application/json');

/*function generateRandomString($length = 10) {
	$characters = '0123456789';
	$charactersLength = strlen($characters);
	$randomString = '';
	for ($i = 0; $i < $length; $i++) {
		$randomString .= $characters[rand(0, $charactersLength - 1)];
	}
	return $randomString;
}*/
$randomStr = generateRandomString(5);

function getHeader($username, $password){
	// Define the header
	return base64_encode("$username:$password");
}

$base_url = 'https://wdyg1.api.infobip.com';

$curl = curl_init();
// $nation_no = $_POST['nation_no'];
$mobile = $_POST['nation_no'].$_POST['mb_hp'];

curl_setopt_array($curl, array(
  CURLOPT_URL => "{$base_url}/sms/2/text/single",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => "{ \"from\":\"PINNACLE\", \"to\":\"{$mobile}\", \"text\":\"[PINNACLE] Verification Code: {$randomStr}\" }",
  CURLOPT_HTTPHEADER => array(
    "accept: application/json",
    "authorization: Basic " . getHeader('HAZGLOBAL', 'Willsoft0780!@'),
    "content-type: application/json"
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
	echo "cURL Error #:" . $err;
} else {
	echo json_encode(array("key" => hash("sha256", $randomStr)));
}
?>