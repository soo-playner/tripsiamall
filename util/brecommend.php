<?
// 후원 바이너리 관련 
$brcomm_arr = [];

/* function brecom_list($mb_id){
    global $config,$brcomm_arr,$debug;
	$origin = $mb_id;

    // 후원 하부 L,R 구분
    list($leg_list,$cnt) = brecommend_direct($mb_id);

    $L_member = $leg_list[0]['mb_id'];
    $R_member = $leg_list[1]['mb_id'];
    

    $brcomm_arr = [];
    array_push($brcomm_arr, $leg_list[0]);
    $manager_list_L = brecommend_array($L_member, 0);

    $brcomm_arr = [];
    array_push($brcomm_arr, $leg_list[1]);
    $manager_list_R = brecommend_array($R_member, 0);
        
    return array($manager_list_L,$manager_list_R);
        
} */

function return_brecommend($mb_id,$limit,$binding = false){
    global $config, $brcomm_arr, $debug;
    $origin = $mb_id;

    list($leg_list, $cnt) = brecommend_direct($mb_id);

    $L_member = $leg_list[0]['mb_id'];
    $R_member = $leg_list[1]['mb_id'];
    
    $brcomm_arr = [];
    $brcomm_arr_L = [];
    $manager_list_L = [];

    array_push($brcomm_arr_L, $leg_list[0]);
    $manager_list_L = brecommend_array($L_member, 1 , $limit);
    $brcomm_arr_L = array_merge($brcomm_arr_L,arr_sort($manager_list_L,'count'));

    $brcomm_arr = [];
    $brcomm_arr_R = [];
    $manager_list_R =[];

    array_push($brcomm_arr_R, $leg_list[1]);
    $manager_list_R = brecommend_array($R_member, 1 , $limit);
    $brcomm_arr_R = array_merge($brcomm_arr_R,arr_sort($manager_list_R,'count'));
   
    if(!$binding){
        return array($brcomm_arr_L,$brcomm_arr_R); 
    }else{
        return array_merge($brcomm_arr_L,$brcomm_arr_R);
    }
    
}


// 후원인 하부 회원 
function brecommend_array($brecom_id, $count, $limit =0)
{
    global $brcomm_arr;

    // $new_arr = array();
    $b_recom_sql = "SELECT mb_id,grade,mb_rate,mb_save_point,mb_brecommend_type, {$count} as count from g5_member WHERE mb_brecommend='{$brecom_id}' ORDER BY mb_brecommend_type ASC ";
    $b_recom_result = sql_query($b_recom_sql);
    $cnt = sql_num_rows($b_recom_result);
    
    if($limit != 0 && $count >= $limit){
        
    }else{
        if ($cnt < 1) {
            // 마지막
        } else {
            ++$count;

            while ($row = sql_fetch_array($b_recom_result)) {
                brecommend_array($row['mb_id'], $count, $limit);
                // print_R($count.' :: '.$row['mb_id'].' | type ::'.$row['grade']);
                // $brcomm_arr[$count]['count'] = $count;
                array_push($brcomm_arr, $row);
            }
            
        }
    }
    
    return $brcomm_arr;
}



function brecommend_direct($mb_id)
{

    $down_leg = array();
    $sql = "SELECT mb_id,grade,mb_rate,mb_save_point,mb_brecommend_type, 1 AS count FROM g5_member where mb_brecommend = '{$mb_id}' AND mb_brecommend != '' ORDER BY mb_brecommend_type ASC ";
    $sql_result = sql_query($sql);
    $cnt = sql_num_rows($sql_result);

    while ($result = sql_fetch_array($sql_result)) {
        array_push($down_leg, $result);
    }
    return array($down_leg, $cnt);
}

?>