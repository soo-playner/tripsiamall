<?
/* 추천인 트리 */
$mem_list = [];

/* 추천상위매니저 검색 */
function return_up_manager($mb_id,$cnt=0){
	global $config;
	$origin = $mb_id;
	$manager_list = [];
	$i = 0;
    
    if($mb_id != 'admin' && $mb_id != $config['cf_admin']){
		
		if($cnt == 0){
			do{
				$manager = recommend_uptree($mb_id);
				$mb_id = $manager;
				array_push($manager_list,$manager);
			}while( 
				$manager != 'khan'
			);
		
			if(count($manager_list) < 2){
				return $origin;
			}else{
				return $manager_list[count($manager_list)-2];
			}
		}else{
			do{
				$i++;
				$manager = recommend_uptree($mb_id);
				$mb_id = $manager;
				array_push($manager_list,$manager);
			}while( $i < $cnt );

			return $manager_list[$cnt-1];
		}
    }else{
        return $mb_id;
    }
}

function recommend_uptree($mb_id){
    $result = sql_fetch("SELECT mb_recommend,mb_level from g5_member WHERE mb_id = '{$mb_id}' ");
    return $result['mb_recommend'];
}


/* 추천하부매니저 검색 */
function return_down_manager($mb_no,$cnt=0){
	global $config,$g5,$mem_list;

	$mb_result = sql_fetch("SELECT mb_id,mb_level,grade,mb_rate,rank,recom_sales from g5_member WHERE mb_no = '{$mb_no}' ");
	$list = [];
	$list['mb_id'] = $mb_result['mb_id'];
	$list['mb_level'] = $mb_result['mb_level'];
	$list['grade'] = $mb_result['grade'];
	$list['depth'] = 0;
	$list['mb_rate'] = $mb_result['mb_rate'];
	$list['recom_sales'] = $mb_result['recom_sales'];
	$list['rank'] = $mb_result['rank'];
	
	$mb_add = sql_fetch("SELECT COUNT(mb_id) as cnt,IFNULL( (SELECT noo  from  recom_bonus_noo WHERE mb_id = '{$mb_result['mb_id']}' ) ,0) AS noo FROM g5_member WHERE mb_recommend = '{$mb_result['mb_id']}' ");
	
	$list['cnt'] = $mb_add['cnt'];
	$list['noo'] = $mb_add['noo'];

	$mem_list = [$list];
	$result = recommend_downtree($mb_result['mb_id'],1,$cnt);
	// print_R(arr_sort($result,'count'));
	// prinT_R($result);
	return $result;
}


function recommend_downtree($mb_id,$count=0,$cnt = 0){
	global $mem_list;

	if($cnt == 0 || ($cnt !=0 && $count < $cnt)){
		
		$recommend_tree_result = sql_query("SELECT mb_id,mb_level,grade,mb_rate,rank,recom_sales from g5_member WHERE mb_recommend = '{$mb_id}' ");
		$recommend_tree_cnt = sql_num_rows($recommend_tree_result);
		if($recommend_tree_cnt > 0 ){
			++$count;

			while($row = sql_fetch_array($recommend_tree_result)){
				$list['mb_id'] = $row['mb_id'];
				$list['mb_level'] = $row['mb_level'];
				$list['grade'] = $row['grade'];
				$list['mb_rate'] = $row['mb_rate'];
				$list['recom_sales'] = $row['recom_sales'];
				$list['rank'] = $row['rank'];
				
				$mb_add = sql_fetch("SELECT COUNT(mb_id) as cnt,IFNULL( (SELECT noo  from  recom_bonus_noo WHERE mb_id = '{$row['mb_id']}' ) ,0) AS noo FROM g5_member WHERE mb_recommend = '{$row['mb_id']}' ");
	
				$list['cnt'] = $mb_add['cnt'];
				$list['noo'] = $mb_add['noo'];
				$list['depth'] = $count;
				array_push($mem_list,$list);
				recommend_downtree($row['mb_id'],$count,$cnt);
			}
		}
	}
	return $mem_list;
}




$brcomm_arr = [];
// 후원인 하부 회원 
function return_brecommend($mb_id,$limit,$binding = false,$where = 1 ){
	global $config, $brcomm_arr, $debug;
	$origin = $mb_id;

	list($leg_list, $cnt) = brecommend_direct($mb_id,$where);

	$L_member = $leg_list[0]['mb_id'];
	$R_member = $leg_list[1]['mb_id'];

	// echo "L : ".	$L_member;
	// echo "R : ".	$R_member;
	
	if($L_member){
		$brcomm_arr_L = array();
		array_push($brcomm_arr_L, $leg_list[0]);
		$manager_list_L = brecommend_array($L_member, 1 , $limit,$where);
		$brcomm_arr_L = array_merge($brcomm_arr_L,arr_sort($manager_list_L,'count'));
	}else{
		$brcomm_arr_L = [];
	}
	$brcomm_arr  = array();
	
	if($R_member){
		$brcomm_arr_R = array();
		array_push($brcomm_arr_R, $leg_list[1]);
		$manager_list_R = brecommend_array($R_member, 1 , $limit);
		$brcomm_arr_R = array_merge($brcomm_arr_R,arr_sort($manager_list_R,'count'));
	}else{
		$brcomm_arr_R = [];
	}

	$brcomm_arr  = array();
	
	if(!$binding){
		return array($brcomm_arr_L,$brcomm_arr_R); 
	}else{
		return array_merge($brcomm_arr_L,$brcomm_arr_R);
	}
	
}

function brecommend_array($brecom_id, $count, $limit =0,$where =1)
{
	global $brcomm_arr;

	// $new_arr = array();
	if($where == 2){
		$where = 2;
		$b_recom_sql = "SELECT A.mb_id,A.mb_brecommend_type,B.grade,B.mb_rate,B.mb_save_point, {$count}  AS count FROM g5_member_binary A LEFT JOIN g5_member B ON A.mb_id = B.mb_id WHERE A.mb_brecommend = '{$brecom_id}' AND A.mb_brecommend != '' ORDER BY mb_brecommend_type ASC";
	}else{
		$b_recom_sql = "SELECT mb_id,grade,mb_rate,mb_save_point,mb_brecommend_type, {$count} as count from g5_member WHERE mb_brecommend='{$brecom_id}' ORDER BY mb_brecommend_type ASC ";
	}
	$b_recom_result = sql_query($b_recom_sql);
	$cnt = sql_num_rows($b_recom_result);
	
	if($limit != 0 && $count >= $limit){
		
	}else{
		if ($cnt < 1) {
			// 마지막
		} else {
			++$count;

			while ($row = sql_fetch_array($b_recom_result)) {
				brecommend_array($row['mb_id'], $count, $limit,$where);
				// print_R($count.' :: '.$row['mb_id'].' | type ::'.$row['grade']);
				// $brcomm_arr[$count]['count'] = $count;
				array_push($brcomm_arr, $row);
			}
			
		}
	}
	
	return $brcomm_arr;
}


function brecommend_direct($mb_id,$where = 1)
{

	$down_leg = array();
	if($where == 2){
		$sql = "SELECT A.mb_id,A.mb_brecommend_type,B.grade,B.mb_rate,B.mb_save_point, 1 AS count FROM g5_member_binary A LEFT JOIN g5_member B ON A.mb_id = B.mb_id WHERE A.mb_brecommend = '{$mb_id}' AND A.mb_brecommend != '' ORDER BY mb_brecommend_type ASC";
	}else{
		$sql = "SELECT mb_id,grade,mb_rate,mb_save_point,mb_brecommend_type, 1 AS count FROM g5_member where mb_brecommend = '{$mb_id}' AND mb_brecommend != '' ORDER BY mb_brecommend_type ASC ";
	}
	$sql_result = sql_query($sql);
	$cnt = sql_num_rows($sql_result);

	while ($result = sql_fetch_array($sql_result)) {
		array_push($down_leg, $result);
	}
	return array($down_leg, $cnt);
}



// 배열정렬 + 지정값 이상 카운팅
function array_index_sort($list, $key, $average)
{
	$count = 0;
	$a = array_count_values(array_column($list, $key));

	foreach ($a as $key => $value) {

		if ($key >= $average) {
			$count += intval($value);
		}
	}
	return array($a, $count);
}

// php 버전 대응 패치
if( !function_exists( 'array_column' ) ):
    
    function array_column( array $input, $column_key, $index_key = null ) {
    
        $result = array();
        foreach( $input as $k => $v )
            $result[ $index_key ? $v[ $index_key ] : $k ] = $v[ $column_key ];
        
        return $result;
    }
endif;


// 배열정렬 
function arr_sort($array, $key, $sort='asc') {
	$keys = array();
	$vals = array();

	foreach ($array as $k=>$v) {
		$i = $v[$key].'.'.$k;
		$vals[$i] = $v;
		array_push($keys, $k);
	}

	unset($array);

	if ($sort=='asc') {
		ksort($vals);
	} else {
		krsort($vals);
	}

	$ret = array_combine($keys, $vals);
	unset($keys);
	unset($vals);

	return $ret;
}

/* 결과 합계 중복제거*/
function array_index_sum($list, $key,$category)
{
	$sum = null;
	$count = 0;
	$a = array_count_values(array_column($list, $key));
	

	foreach ($a as $key => $value) {
		
		if($category == 'int'){
			// echo $key." ";
			$sum += $key; 
			// echo "= ".$sum."<br>";
		}else if ($category == 'text'){
			$sum .= $key.' | '; 
		}
	}
	return $sum;
}

/* 결과 합계 */
function array_int_sum($list, $key){
	return array_sum(array_column($list, $key));
}
?>