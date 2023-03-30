<?

include_once(G5_PATH.'/util/recommend.php');
if($_GET['debug']) $is_debug = 1;


// 회사지갑 설정
define('BONUS_CURENCY','ETH');
define('BALANCE_CURENCY','$');

define('ETH_ADDRESS','0xfAd6dB131138dA1B8FF8092337b0067805af60C2');


$bonus_sql = "select * from {$g5['bonus_config']} order by idx";
$list = sql_query($bonus_sql);
$pre_setting = sql_fetch($bonus_sql);
$limited = $pre_setting['limited'];
if($limited > 0){
	$limited_per = 100/$limited*100;
}else{
	$limited_per =0;
}

$mbm_price = coin_price('mbm');
$eth_price = coin_price('eth');
$usd_price = coin_price('usd');

function coin_price($income){
	global $g5;

	$currency_sql = " SELECT * from {$g5['coin_price']} where symbol = '{$income}' ";
	$result = sql_fetch($currency_sql);

	if($result['manual_use'] == 1){
		return $result['manual_cost'];
	}else{
		return $result['current_cost'];
	}
}


function shift_price($income,$val = 1, $outcome){
	$in_price = coin_price($income);
	$out_price = coin_price($outcome);
	
	return $in_price * $val / $out_price;
}


// 예치금/수당 퍼센트
function bonus_state($mb_id){
	global $limited_per;

	$math_percent_sql = "select ROUND(sum(mb_balance / mb_deposit_point) * {$limited_per}, 2) as percent from g5_member where mb_id ='{$mb_id}' ";
    $math_percent = sql_fetch($math_percent_sql)['percent'];
    if($is_debug) echo "BONUS PERCENT :".$math_percent;

    if($math_percent > 0){
        $math_percent = $math_percent;
    }else{
        $math_percent = 0;
    }
    
	return $math_percent;
}


/*레퍼러 하부매출*/
function refferer_habu_sales($mb_id){

	$referrer_sql = "select day,noo from noo2 where mb_id ='{$mb_id}' ORDER BY day desc limit 1";
	$referrer_result = sql_fetch($referrer_sql);
	$referrer_sales = $referrer_result['noo'];

	if($referrer_sales > 0){
		$referrer_sales = Number_format($referrer_sales);
	}else{
		$referrer_sales = 0;
	}
	return $referrer_sales;
}



/*스폰서 하부매출*/
function sponsor_habu_sales($mb_id){
	$b_recomm_sql = "select mb_id as b_recomm from g5_member where mb_brecommend='".$mb_id."' and mb_brecommend_type='L'";
	$b_recomm_res = sql_fetch($b_recomm_sql);
	$b_recomm = $b_recomm_res['b_recomm'];
	if($b_recomm){
		$left_noo_sql = "select noo from bnoo2 where mb_id ='{$b_recomm}' order by day desc limit 0 ,1";
		$left_noo_result = sql_fetch($left_noo_sql);
		$left_noo = $left_noo_result['noo'];
	}


	$b_recomm2_sql = "select mb_id as b_recomm2 from g5_member where mb_brecommend='".$mb_id."' and mb_brecommend_type='R'";
	$b_recomm2_res = sql_fetch($b_recomm2_sql );
	$b_recomm2 = $b_recomm2_res['b_recomm2'];
	if($b_recomm2){
		$right_noo_sql = "select noo from bnoo2 where mb_id ='{$b_recomm2}' order by day desc limit 0 ,1";
		$right_noo_result = sql_fetch($right_noo_sql);
		$right_noo = $right_noo_result['noo'];
	}

	$sponsor_sales_sum = $left_noo + $right_noo;

	if($sponsor_sales_sum > 0){
		$sponsor_sales = Number_format($sponsor_sales_sum);
	}else{
		$sponsor_sales = 0;
	}

	return $sponsor_sales;
}

/* 예치금에 따른 레벨 - 사용안함 */
/* function member_level_proc($point){
    if($point >= 30000) $level = 9;
    if($point < 30000) $level = 8;
    if($point < 12000) $level = 7;  
    if($point < 9000) $level = 6;
    if($point < 6000) $level = 5;  
    if($point < 3000) $level = 4;  
    if($point < 2100) $level = 3;    
    if($point < 1500) $level = 2; 
    if($point < 900) $level = 1; 
    if($point < 300) $level = 0; 

    return $level;
} */


/*국가코드*/
$nation_name=array('Japan'=>81,'Republic of Korea'=>82,'Vietnam'=>84,'China'=>86,'Indonesia'=>62,'Philippines'=>63,'Thailand'=>66);

	// 회원가입시
function get_member_nation_select($name, $selected=0, $key="")
{
    global $g5,$nation_name;

	$str = "\n<select id=\"{$name}\" name=\"{$name}\"";
	$str .= ">\n";

	foreach($nation_name as $key => $value){
		$str .= '<option value="'.$value.'"';
		echo $value." | ".$selected."<br>";
        if ($value == $selected)
            $str .= ' selected="selected"';
        $str .= ">0".$value." - {$key}</option>\n";
	}
	$str .= "</select>\n";
	return $str;
}

// 프로필
function get_member_nation($value)
{
    global $g5,$nation_name;
	$key = array_search($value, $nation_name);
	return $key;
}


// 원 표시
function shift_kor($val){
	return Number_format($val, 0);
}

// 달러 표시
function shift_doller($val){
	return Number_format($val, 2);
}

// 코인 표시
function shift_coin($val){
	return Number_format($val, COIN_NUMBER_POINT);
}

// 달러 , ETH 코인 표시
function shift_auto($val,$coin = '원'){
	if($coin == '$'){
		return shift_doller($val);
	}else if($coin == '원'){
		return shift_kor($val);
	}else{
		return shift_coin($val);
	}
}

/*숫자표시*/
function shift_number($val){
	return preg_replace("/[^0-9].*/s","",$val);
}

/*콤마제거숫자표시*/
function conv_number($val) {
	$number = (int)str_replace(',', '', $val);
	return $number;
}


/*날짜형식 변환*/
function timeshift($time){
	$today_year = date("Y-m-d");
	$target_year = date("Y-m-d",strtotime($time));
	
	if($today_year == $target_year){
		return date("H:i:s",strtotime($time));	
	}else{
		return date("Y-m-d",strtotime($time));
	}
}

/*날짜형식 변환 2*/
function timeshift2($time,$func=1){
	
	if($func == 1){
		return date("Y-m-d",strtotime($time));	
	}else{
		return date("Y-m-d H:i:s",strtotime($time));
	}
}



function nav_active($val){
		global $stx;
		if($val == $stx) echo "active";
		if(!$stx && $val='all') echo "active";
}

function string_explode($val ){
	$stringArray = explode("member",$val);
	$string1= "<span class='tx1'>".$stringArray[0]." member</span>";
	$string2 = "<span class='tx2'>".$stringArray[1]."</span>";
	return $string1.$string2;
}

function Number_explode($val ){
	$stringArray = explode(".",$val);
	$string1= $stringArray[0].".";
	$string2 = "<string class='demical'>".$stringArray[1]."</string>";
	return $string1.$string2;
}

function string_shift_code($val){
	switch ($val) {
		case "0" :
		echo "Request Checking ..";
		break;
		case "2" :
		echo "Complete";
		break;
		case "1" :
		echo "Processing";
		break;
		case "3" :
		echo "Reject";
		break;
		default :
		echo "Processing";
	}
}

   function get_shop_item(){
        $array = array();
        $sql = "SELECT * FROM g5_shop_item";
        $result = sql_query($sql);
    
        while($row = sql_fetch_array($result)){
            array_push($array,$row);
        }
    
        return $array;
    }

?>
