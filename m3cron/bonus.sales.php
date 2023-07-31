<?php
ob_start();
$debug = false;

function bonus_pick($val){    
    global $conn;
    $pick_sql = "select * from wallet_bonus_config where code = '{$val}' ";
    $pick_result = mysqli_query($conn, $pick_sql);
    $list = mysqli_fetch_array($pick_result);
    return $list;
}

function bonus_condition_tx($bonus_condition){
    $bonus_condition_tx = "";
    if($bonus_condition == 1){
        $bonus_condition_tx = '추천 계보';
    }else if($bonus_condition == 2){
        $bonus_condition_tx = '후원(바이너리) 계보';
    }else if($bonus_condition == 3){
        $bonus_condition_tx='후원2(바이너리) 계보';
    }
    return $bonus_condition_tx;
}

function bonus_layer_tx($bonus_layer){
    $bonus_layer_tx = $bonus_layer.'단계까지 지급';
    if($bonus_layer == '' || $bonus_layer == '0'){
        $bonus_layer_tx = '전체지급';
    }
    return $bonus_layer_tx;
}

function clean_coin_format($val, $decimal = 8){
	$_num = (int)str_pad("1",$decimal+1,"0",STR_PAD_RIGHT);
	return floor($val*$_num)/$_num;
}

function clean_number_format($val, $decimal = 2){
	$_decimal = $decimal <= 0 ? 1 : $decimal;
	$_num = number_format(clean_coin_format($val,$decimal), $_decimal);
    $_num = rtrim($_num, 0);
    $_num= rtrim($_num, '.');

    return $_num;
}

function soodang_record($mb_id, $code, $bonus_val,$rec,$rec_adm,$bonus_day,$mb_no='',$mb_level = ''){
    global $debug,$now_datetime,$conn;

    $soodang_sql = " insert soodang_pay set day='".$bonus_day."'";
    $soodang_sql .= " ,mb_id			= '".$mb_id."'";
    $soodang_sql .= " ,allowance_name	= '".$code."'";
    $soodang_sql .= " ,benefit		=  ".$bonus_val;	
    $soodang_sql .= " ,rec			= '".$rec."'";
    $soodang_sql .= " ,rec_adm		= '".$rec_adm."'";
    $soodang_sql .= " ,datetime		= '".$now_datetime."'";

    if($mb_no != ''){
        $soodang_sql .= " ,mb_no		= '".$mb_no."'";
    }
    if($mb_level != ''){
        $soodang_sql .= " ,mb_level		= '".$mb_level."'";
    }

    // 수당 푸시 메시지 설정
    /* $mb_push_data = sql_fetch("SELECT fcm_token,mb_sms from g5_member WHERE mb_id = '{$mb_id}' ");
    $push_agree = $mb_push_data['mb_sms'];
    $push_token = $mb_push_data['fcm_token'];

    $push_images = G5_URL.'/img/marker.png';
    if($push_token != '' && $push_agree == 1){
        setPushData("[DFINE] - ".$mb_id." 수당 지급 ", $code.' =  +'.$bonus_val.' ETH', $push_token,$push_images);
    } */
    
    if($debug){
        echo "<code>";
        print_r($soodang_sql);
        echo "</code>";
        return true;
    }else{
        return mysqli_query($conn, $soodang_sql);
    }
}

$code = "sales";

$host_name = 'localhost';
$user_name = 'root';
$user_pwd = 'wizclass.inc@gmail.com';
$database = 'hwajo';
$conn = mysqli_connect($host_name,$user_name,$user_pwd,$database);


$bonus_day = date('Y-m-d');
$timestr        = strtotime($bonus_day);
$yesterday = date('Y-m-d', $timestr);

$dupl_check_sql = "select count(mb_id) as cnt from soodang_pay where day='{$bonus_day}' and allowance_name = '{$code}' ";
$dupl_check_result = mysqli_query($conn, $dupl_check_sql);
$get_today = mysqli_fetch_array($dupl_check_result)['cnt'];
if($get_today > 0){
	echo "{$bonus_day} {$code} 수당은 이미 지급되었습니다.";
	die;
}

// 직급 수당
$bonus_row = bonus_pick($code);
$bonus_limit = $bonus_row['limited']/100;

// 직급 사용 단계
$bonus_rate_array_cnt = 7;


// $bonus_rate = explode(',',$bonus_row['rate']);

$bonus_condition = $bonus_row['bonus_condition'];
$bonus_condition_tx = bonus_condition_tx($bonus_condition);


$bonus_layer = $bonus_row['layer'];
$bonus_rate = $bonus_layer;
$bonus_layer_tx = bonus_layer_tx($bonus_layer);
$bonus_limited = $bonus_row['limited'];

$company_sales = $bonus_row['layer'];

//어제 매출 합계 
$total_order_query = "SELECT SUM(od_cash) AS hap FROM g5_shop_order WHERE od_date = '{$yesterday}'";
$total_order_reult = mysqli_query($conn, $total_order_query);
$total_order_row = mysqli_fetch_array($total_order_reult);
$total_order = $total_order_row['hap'];

$sales_order = ($total_order * ($company_sales * 0.01));


// 디버그 로그 
if($debug){
	echo "매출 합계 - <code>";
    print_r($total_order_query);
	echo "</code><br>";
}


function rate_txt($val){
    $list = explode(',',$val);
    $i =0;

    while($i < count($list)){
        echo $list[$i]."% ";
        $i++;
    }
}

ob_start();

// 설정로그 
echo "<strong>세일즈 수당 지급비율 : ";
print_R(rate_txt($company_sales));
echo "   </strong> |    지급조건 : <span class='blue big'>".$bonus_condition."</span><br>";
echo "<br><strong> 현재일 : ".$bonus_day." |  매출산정기준 : <span class='red'>".$yesterday."</span> | PV 합계 : <span class='blue big'>".Number_format($total_order)." usdt </span>  </strong><br>";
echo "<br> 세일즈수당 대상금액 : <span class='blue big'>".$company_sales."% = ".Number_format($sales_order)." usdt</span>";
echo "<br><br>";
echo "<div class='btn' onclick='bonus_url();'>돌아가기</div>";
?>

<html><body>
<header>정산시작</header>    
<div>

<?
if($sales_order > 0){
    excute();
}else{
    echo "<span class='red'>해당 기간 기준매출 없음</span>";
}

function  excute(){

    global $admin_condition,$pre_condition,$conn;
    global $bonus_day, $bonus_condition, $bonus_rate_array_cnt, $code, $bonus_rate,$bonus_limit,$total_order,$Khan_order,$sales_order,$cnt_arr,$cnt_arr2;
    global $debug,$prev_m,$yesterday;

    
        $sql = "SELECT * FROM g5_member WHERE mb_id in ('{$bonus_condition}') " ;
        $result = mysqli_query($conn, $sql);

        $star_rate = $bonus_rate*0.01;
        $star_rate_tx = $bonus_rate."%";


        // echo "<br><br><span class='title block'>".$grade_name." (".$member_count.") - ".$star_rate_tx."</span><br>";

        // 디버그 로그 
        if($debug){
            echo "<code>";
            echo($sql);
            echo "</code><br>";
        }
       
        while($row = mysqli_fetch_array($result)){
        
            $mb_no=$row['mb_no'];
            $mb_id=$row['mb_id'];
            $mb_name=$row['mb_name'];
            $mb_level=$row['mb_level'];
            $mb_deposit=$row['mb_deposit_point'];
            $mb_balance=$row['mb_balance'];
            $grade=$row['grade'];
            

            echo "<br><br><span class='title' >".$row['mb_id']."</span> <br>";

                
                $benefit = ( ($total_order*$star_rate) );
                
                $benefit_tx = ' '.$total_order.' * '.$star_rate.' = '.$benefit; 
                $benefit_limit = $benefit;

                
                echo $benefit_tx;
                
                $rec= $code.' Bonus from '.$yesterday .' PV';
                $rec_adm= $yesterday." | ".$benefit_tx;
                
                $benefit = clean_number_format($benefit);

                echo "<span class=blue> ▶▶ 수당 지급 : ".$benefit."</span><br>";
        

                if($benefit > 0 && $benefit_limit > 0){

                    $record_result = soodang_record($mb_id, $code, $benefit_limit,$rec,$rec_adm,$bonus_day);
    
                    if($record_result){
                        
                        $balance_up = "update g5_member set mb_balance = mb_balance + {$benefit_limit}, mb_balance_ignore = mb_balance_ignore + {$benefit_limit}  where mb_id = '".$mb_id."'";

                        // 디버그 로그
                        if($debug){
                            echo "<code>";
                            print_R($balance_up);
                            echo "</code>";
                        }else{
                            mysqli_query($conn, $balance_up);
                        }
                    }
                    
                }

            
 
            } // if else
        } //while
        $rec='';
?>


</div>
<footer > 정산 완료</footer>

<div class='btn' onclick="bonus_url('<?=$category?>');">돌아가기</div>

<body>
</html>

<style>
	body{font-size:14px;line-height:18px;letter-spacing:0px;}
	code{color:green;display:block;margin-bottom:5px;font-size:11px;}
    .red{color:red;font-weight:600;}
    .blue{color:blue;font-weight:600;}
	.big {font-size:16px;font-weight:600;}
	.title{font-weight:800;color:black;font-size:16px;display:block;}
	.box{background:ghostwhite;margin-top:30px;border-bottom:1px solid #eee;padding-left:5px;width:100%;display:block;}
	.block{font-size:26px; background: turquoise;display: block;height: 30px;line-height: 30px;}
	.block.coral{background:lightcoral}
	.indent{text-indent:20px;display: inline-block;}
	.btn{background:black; padding:5px 20px; display:inline-block;color:white;font-weight:600;cursor:pointer;margin-bottom:20px;}
	footer,header{margin:20px 0; background:black;color:white;text-align:center}
	.error{display:block;width:100%;text-align:center;height:150px;line-height:150px}
	.hidden{display:none;}
	.desc{font-size:11px;color:#777;}
	.subtitle{font-size:20px;}
	.sys_log{margin-bottom:30px;}
</style>


<script>
 function bonus_url($val){
	 if($val == 'mining'){
		location.href = '/adm/bonus/bonus_mining.php?to_date=<?=$bonus_day?>';
	 }else{
		location.href = '/adm/bonus/bonus_list.php?to_date=<?=$bonus_day?>';
	 }
     
 }
</script>




<?
if($debug){}else{
    $html = ob_get_contents();
    //ob_end_flush();
    $logfile = '/var/www/html/hwajo/data/log/'.$code.'/'.$code.'_'.$bonus_day.'.html';
    fopen($logfile, "w");
    file_put_contents($logfile, ob_get_contents());
}
?>