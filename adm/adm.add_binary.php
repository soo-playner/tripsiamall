<?php
include_once('./_common.php');
include_once('./bonus/bonus_inc.php');

$now_datetime = date('Y-m-d H:i:s');
$func = $_POST['func'];



if(isset($_POST['mb_id'])){
    $mb_id = $_POST['mb_id'];
}

// $debug=1;
// $mb_id = 'test9';
// $func = 1;

if(isset($mb_id) && $func){
    if($func == 2){
        add_binary2($mb_id);
    }else if($func == 1){
        add_binary($mb_id);
    }else if ($func == 3){
        remove_binary($mb_id);
    }
}

function remove_binary($mb_id){
    $remove_binary_sql = "UPDATE g5_member set mb_brecommend='', mb_brecommend_type='',mb_bre_time='',mb_lr = 0 WHERE mb_id = '{$mb_id}' ";
    $result1 = sql_query($remove_binary_sql);

    $remove_binary2_sql = "DELETE FROM g5_member_binary WHERE mb_id = '{$mb_id}' ";
    $result2 = sql_query($remove_binary2_sql);

    if($result1 && $result2){
        $msg = $mb_id. " 님의 후원레그 정보가 초기화 되었습니다.";
        echo (json_encode(array("result" => "success", "code" => "0001", "msg" => $msg), JSON_UNESCAPED_UNICODE));
    }else{
        echo (json_encode(array("result" => "failed", "code" => "0002", "msg" => "처리에러"), JSON_UNESCAPED_UNICODE));
    }
}


function add_binary($mb_id){
    global $debug,$now_datetime,$config;
        
    // 추천인 산하 후원인 자리 검색
    $recomm_sql = "SELECT mb_recommend,mb_brecommend FROM g5_member WHERE mb_id = '{$mb_id}' ";
    $recomm_result = sql_fetch($recomm_sql);
    $brecomm = $recomm_result['mb_brecommend'];

    if ($brecomm == '') {
        // $recomm = $recomm_result['mb_recommend'];
        // $recomm = $config['cf_admin'];
        $recomm = 'zbzzang';

        // 직후원인
        $direct_brecom_sql  =  "SELECT COUNT(mb_id) as cnt from g5_member WHERE mb_brecommend  = '{$recomm}' ";
        $direct_brecom_result = sql_fetch($direct_brecom_sql);
        $direct_brecom = $direct_brecom_result['cnt'];

        if ($debug) {
            echo "<br><br> direct Recommend:: " . $recomm . "(" . $direct_brecom . ")";
        }

        if ($direct_brecom == 1) {
            $under_brecomme_sql = "SELECT mb_id from g5_member WHERE mb_brecommend  = '{$recomm}'  and mb_brecommend_type = 'R' ";

            $under_brecomme_result = sql_fetch($under_brecomme_sql);

            $under_brecomme_code = 'R';
            $under_brecomme = $under_brecomme_result['mb_id'];
        } else if ($direct_brecom < 1) {
            $under_brecomme_sql = "SELECT mb_id from g5_member WHERE mb_brecommend  = '{$recomm}'  and mb_brecommend_type = 'L' ";
            $under_brecomme_result = sql_fetch($under_brecomme_sql);

            $under_brecomme_code = 'L';
            $under_brecomme = $under_brecomme_result['mb_id'];
        }

        if ($under_brecomme) {
            $recomm = $under_brecomme;
        }

        if ($debug) {
            echo "<br><h1>" . $recomm . "(" . $under_brecomme . ")</h1><br>";
        }

        $brecomme = array_brecommend($recomm, 1);
        $target_key = min(array_keys($brecomme));
        $now_brecom = $brecomme[$target_key];

        if ($debug) {
            echo "<br><br> 후원자찾기 :: ";
            print_R($now_brecom);
        }

        if ($now_brecom['cnt'] == 0) {
            $now_type = 'L';
            $mb_lr = '1';
        } else {
            $now_type = 'R';
            $mb_lr = '2';
        }

        // 후원인 기록 
        $recom_update_sql = "UPDATE g5_member set mb_brecommend='{$now_brecom['id']}', mb_brecommend_type='{$now_type}',mb_bre_time='{$now_datetime}',mb_lr = {$mb_lr} WHERE mb_id = '{$mb_id}' ";

        if ($debug) {
            echo "<br><br>후원인 기록 :: ";
            print_R($recom_update_sql);
            $recom_update_result = 1;
        } else {
            $recom_update_result = sql_query($recom_update_sql);
        }

        if($recom_update_result){
            $msg = "후원레그 등록 = {$now_brecom['id']} - $now_type \n";
            $msg .= "처리가 완료되었습니다.";
        
            echo (json_encode(array("result" => "success", "code" => "0001", "msg" => $msg), JSON_UNESCAPED_UNICODE));
        }else{
            echo (json_encode(array("result" => "failed", "code" => "0002", "msg" => "처리에러"), JSON_UNESCAPED_UNICODE));
        }
    }
}







// 후원레그2 후원인 기록
function add_binary2($mb_id){
    global $debug,$now_datetime;

    $origin_number_sql = "SELECT mb_recommend from g5_member WHERE mb_id = '{$mb_id}' ";
    $origin_number_result = sql_fetch($origin_number_sql);
    $origin_recom = $origin_number_result['mb_recommend'];

    $brecomme2 = array_brecommend_binary($origin_recom, 1);
    $target_key2 = min(array_keys($brecomme2));
    $now_brecom2 = $brecomme2[$target_key2];

    if ($now_brecom2['cnt'] == 0) {
        $now_type2 = 'L';
    } else {
        $now_type2 = 'R';
    }

    if ($debug) {
        echo "<br><br> 슈퍼레그 후원자찾기 :: ";
        print_R($now_brecom2);
    }

    $random_recom_update_sql = "INSERT g5_member_binary set mb_id = '{$mb_id}',mb_recommend='{$origin_recom}', mb_brecommend='{$now_brecom2['id']}',mb_bre_time ='{$now_datetime}', mb_brecommend_type='{$now_type2}'";

    //중복아이디 없을때만
    /* $dup_check_sql = "SELECT count(*) as cnt from g5_member_binary WHERE mb_id = '{$mb_id}' ";
    $dup_check_result = sql_fetch($dup_check_sql);
    $dup_check = $dup_check_result['cnt']; */

    if ($debug) {
        echo "<br><br>슈퍼레그 후원인 기록 :: ";
        print_R($random_recom_update_sql);
        $random_recom_update_result = 1;
    } else {
        $random_recom_update_result = sql_query($random_recom_update_sql);
    }

    if($random_recom_update_result){
        $msg = "후원2 레그 등록 = {$now_brecom2['id']} - $now_type2 \n";
        $msg .= "처리가 완료되었습니다.";
    
        echo (json_encode(array("result" => "success", "code" => "0001", "msg" => $msg), JSON_UNESCAPED_UNICODE));
    }else{
        echo (json_encode(array("result" => "failed", "code" => "0002", "msg" => "처리에러"), JSON_UNESCAPED_UNICODE));
    }
     
}


$brcomm_arr = [];
// 후원인 빈자리 찾기
function array_brecommend($recom_id, $count)
{
	global $brcomm_arr, $debug;


	// $new_arr = array();
	$b_recom_sql = "SELECT mb_id from g5_member WHERE mb_brecommend='{$recom_id}' ORDER BY mb_brecommend_type ";
	$b_recom_result = sql_query($b_recom_sql);
	$cnt = sql_num_rows($b_recom_result);

	if ($cnt < 2) {
		if ($debug) {
			
			print_R($count . ' :: ' . $recom_id . ' :: ' . $cnt);
			echo "<br><br>";
		}
		if (!$brcomm_arr[$count]) {
			$brcomm_arr[$count]['id'] = $recom_id;
			$brcomm_arr[$count]['cnt'] = $cnt;
		}
	} else {
		++$count;
		while ($row = sql_fetch_array($b_recom_result)) {
			array_brecommend($row['mb_id'], $count);
		}
	}
	return $brcomm_arr;
}



$brcomm_arr2 = [];
// 후원인2 빈자리 찾기
function array_brecommend_binary($recom_id, $count)
{
	global $brcomm_arr2, $debug;


	// $new_arr = array();
	$b_recom_sql = "SELECT mb_id from g5_member_binary WHERE mb_brecommend='{$recom_id}' ";
	$b_recom_result = sql_query($b_recom_sql);
	$cnt = sql_num_rows($b_recom_result);

	if ($cnt < 2) {
		if ($debug) {
			echo "<br><br><br><br>";
			print_R($count . ' :: ' . $recom_id . ' :: ' . $cnt);
		}
		if (!$brcomm_arr2[$count]) {
			$brcomm_arr2[$count]['id'] = $recom_id;
			$brcomm_arr2[$count]['cnt'] = $cnt;
		}
	} else {
		++$count;
		while ($row = sql_fetch_array($b_recom_result)) {
			array_brecommend_binary($row['mb_id'], $count);
		}
	}
	return $brcomm_arr2;
}

?>