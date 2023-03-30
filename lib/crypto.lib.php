<?php
if (!defined('_GNUBOARD_')) exit;

function echo_json($data){
	$json = json_encode($data);
	echo $json;
	exit;
}

function exit_response($data){
	http_response_code($data['code']);
	echo json_encode($json['data']);
	exit;
}

class Crypto{
	static public $config;
	static public $prices;
	
	static public function Encrypt($text){
		$password = 'password string';
		$password = substr(hash('sha256', $password, true), 0, 32);
		$iv = chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0);

		return base64_encode(openssl_encrypt($text, 'aes-256-cbc', $password, OPENSSL_RAW_DATA, $iv));	
	}
		
	static public function Decrypt($text){
		$password = 'password string';
		$password = substr(hash('sha256', $password, true), 0, 32);
		$iv = chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0);

		return openssl_decrypt(base64_decode($text), 'aes-256-cbc', $password, OPENSSL_RAW_DATA, $iv);		
	}
	
	static public function CreateWebhook($callback,$symbol,$address){
		$webhook = self::GetClient('webhook');
		
		$blocksdk_res = $webhook->create([
			"callback" => $callback,
			"category" => $symbol,
			"address"  => $address
		]);
		
		return $blocksdk_res;
	}
	static public function GetConfig(){
		if(empty(self::$config) == false){
			return self::$config;
		}
		
		$sql = "select * from blocksdk_conf";
		self::$config = sql_fetch($sql);
		
		return self::$config;
	}		
	
	static public function GetMemberEthAddress($address){
		$sql = "select * from blocksdk_member_eth_addresses where address='{$address}' limit 0,1";
		return sql_fetch($sql);
	}		
	
	static public function GetReceivingAddress(){
		$sql = "select * from blocksdk_receiving_address limit 0,1";
		return sql_fetch($sql);
	}		
	
	static public function InsertReceiveTx($data){
		$sql = "
			insert into
			blocksdk_receive_txs(mb_no,tx_hash,symbol,address,value,create_at)
			values({$data['mb_no']},'{$data['tx_hash']}','{$data['symbol']}','{$data['address']}',{$data['value']},{$data['create_at']})
		";
		sql_query($sql);
	}	
	
	static public function GetToken(){
		$config = self::GetConfig();

		return self::Decrypt($config['blocksdk_token']);
	}
	
	static public function GetClient($symbol){
		$api_token = self::GetToken();
		
		if(empty($api_token) == true){
			return false;
		}
		
		$blocksdk = new BlockSDK($api_token);
		
		switch($symbol){
			case "btc":
				return $blocksdk->createBitcoin();
			break;
			case "bch":
				return $blocksdk->createBitcoinCash();
			break;
			case "ltc":
				return $blocksdk->createLitecoin();
			break;
			case "dash":
				return $blocksdk->createDash();
			break;
			case "eth":
				return $blocksdk->createEthereum();			
			case "xmr":
				return $blocksdk->createMonero();	
			case "webhook":
				return $blocksdk->createWebHook();
			break;
		}

		return false;
	}
	
	static public function GetSeefWif($symbol){
		$config = self::GetConfig();
		
		switch($symbol){
			case "btc":
				return self::Decrypt($config['btc_seed_wif']);
			break;
			case "bch":
				return self::Decrypt($config['bch_seed_wif']);
			break;
			case "ltc":
				return self::Decrypt($config['ltc_seed_wif']);
			break;
			case "dash":
				return self::Decrypt($config['dash_seed_wif']);
			break;
		}
		
		return false;
	}
	
	
	static public function IsReceiveTx($tx_hash){
		$sql = "select count(*) as cun from blocksdk_receive_txs where tx_hash='{$tx_hash}'";
		$data = sql_fetch($sql);
		
		return $data['cun'];
	}
	
	static public function SetPrice($symbol){
		$config = self::GetConfig();
		$symbol = strtoupper($symbol);
		$url = "https://pro-api.coinmarketcap.com/v1/tools/price-conversion";
		$data = [
			'CMC_PRO_API_KEY' => $config['coinmarketcap_token'],
			'amount' => 1,
			'symbol' => $symbol,
			'convert' => 'KRW',
		];
		if (!empty($data)) {
			$url .= '?'.http_build_query($data);
		}
		
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$result = curl_exec($ch);
		
		$result_array = json_decode($result, true);
		curl_close($ch);
		
		self::$prices[$symbol] = (int)$result_array['data']['quote']['KRW']['price'];
	}
	
	static public function GetPrice($symbol,$amount){
		$symbol = strtoupper($symbol);
		
		if(empty(self::$prices[$symbol])){
			self::SetPrice($symbol);
		}

		$percent = $amount / self::$prices[$symbol] * 100;
		return round(1 * $percent / 100,6);
	}	
	
	static public function GetCoinToPrice($symbol,$amount){
		$symbol = strtoupper($symbol);
		
		if(empty(self::$prices[$symbol])){
			self::SetPrice($symbol);
		}

		$percent = $amount / 1 * 100;
		return (int)(self::$prices[$symbol] * $percent / 100);
	}
}
?>