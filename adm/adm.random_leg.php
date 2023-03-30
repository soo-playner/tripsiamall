<?php
include_once('./_common.php');
$now_datetime = date('Y-m-d H:i:s');
$now_date = date('Y-m-d');
if ($_GET['debug']) $debug = 1;

$reset_recommend_sql = "UPDATE g5_member_binary set mb_brecommend = '' , mb_brecommend_type = '' WHERE mb_id NOT IN ('admin','atm') ";
$reset_done = sql_query($reset_recommend_sql);

if($reset_done){
$sql = "SELECT mb_id from g5_member_binary WHERE mb_id NOT IN ('admin','atm')";
$sql_result = sql_query($sql);

$member_list = array();
while($binary_member = sql_fetch_array($sql_result)){
    array_push($member_list,$binary_member['mb_id']);
}

if($debug){
print_R($member_list);
echo "<br><br><br>";
}

$member_lists_pre = shuffle_assoc($member_list);
$member_lists =  array_values($member_lists_pre);
$member_count = count($member_lists);

if($debug){
print_R($member_lists);
echo "<br><br><br>";
}


$recomm = 'atm';
$i=0;
    while( $i < $member_count){

        $mb_id = $member_lists[$i];
        if($debug)echo "<br><br><strong>".$mb_id."</strong><br>";

        $brcomm_arr = [];
        $brecomme = array_brecommend($recomm, 1);
        $target_key = min(array_keys($brecomme));
        $now_brecom = $brecomme[$target_key];
        
        if ($debug) {echo "<br> 후원자찾기 :: ";print_R($now_brecom);}

        if($now_brecom['cnt'] == 0){
            $now_type = 'L';
            $mb_lr = '1';
        }else{
            $now_type = 'R';
            $mb_lr = '2';
        }

    $update_sql = "UPDATE g5_member_binary set mb_brecommend = '{$now_brecom['id']}' , mb_brecommend_type = '{$now_type}'  WHERE mb_id = '{$mb_id}' ";
    if($debug)echo $update_sql;
    $update_result = sql_query($update_sql);
    $i++;
    }

    alert('레그를 재생성했습니다.','/adm/member_random_org.php');
}
        




// 후원인 빈자리 찾기
function array_brecommend($recom_id, $count)
{
	global $brcomm_arr,$debug;

    
	$b_recom_sql = "SELECT mb_id from g5_member_binary WHERE mb_brecommend='{$recom_id}' ";
	$b_recom_result = sql_query($b_recom_sql);
	$cnt = sql_num_rows($b_recom_result);

	if ($cnt < 2) {
		if($debug){
			print_R($count.' :: '.$recom_id.' :: '.$cnt);
			echo "<br><br>";
        }
        
		if(!$brcomm_arr[$count]){
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



function shuffle_assoc($list) { 
    if (!is_array($list)) return $list; 
  
    $keys = array_keys($list); 
    shuffle($keys); 
    $random = array(); 
    foreach ($keys as $key) { 
      $random[$key] = $list[$key]; 
    }
    return $random; 
  } 
?>