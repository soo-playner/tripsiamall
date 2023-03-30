<?php

$sub_menu = "600200";
include_once('./_common.php');
// $debug = 1;
include_once('./bonus_inc.php');
include_once(G5_PATH.'/util/recommend.php');

auth_check($auth[$sub_menu], 'r');



if (!$debug) {
    $dupl_check_sql = "select mb_id from rank where rank_day='" . $bonus_day . "'";
    $get_today = sql_fetch($dupl_check_sql);

    if ($get_today['mb_id']) {
        alert($bonus_day . " 해당일 승급이 이미 완료 되었습니다.");
        die;
    }

    $record_check_sql = "select mb_id from g5_member_info where date='" . $bonus_day . "'";
    $get_record = sql_fetch($record_check_sql);

    if ($get_record['mb_id']) {
        $record_delete = "DELETE FROM g5_member_info WHERE date = '{$bonus_day}' ";
        sql_query($record_delete);
    }
}

// 직급 승급
$grade_cnt = 4;
$levelup_result = bonus_pick($code);

// 직추천 회원수 
// $lvlimit_cnt = explode(',', $levelup_result['limited']);


// 구매등급기준
$lvlimit_sales_level = explode(',', $levelup_result['rate']);

$lvlimit_sales_level_val = 6000000;


// 추천산하매출기준
$lvlimit_recom = explode(',', $levelup_result['layer']);
$lvlimit_recom_val = 10000000;


//회원 리스트를 읽어 온다.
$sql_common = " FROM g5_member ";
// $sql_search=" WHERE o.mb_id=m.mb_id AND DATE_FORMAT(o.od_time,'%Y-%m-%d')='".$bonus_day."'";
$search_condition = " and mb_level > 0  ";
$sql_search = " WHERE grade < {$grade_cnt} {$search_condition} " . $pre_condition . $admin_condition;
$sql_mgroup = " GROUP BY grade ORDER BY grade asc ";

$pre_sql = "select grade, count(*) as cnt
                {$sql_common}
                {$sql_search}
                {$sql_mgroup}";

$pre_result = sql_query($pre_sql);

// 디버그 로그 
if ($debug) {
    echo "대상회원 - <code>";
    print_r($pre_sql);
    echo "</code><br>";
}
$pre_count = sql_num_rows($pre_result);
ob_start();

// 설정로그 
echo "<strong> 현재일 : " . $bonus_day;
// echo " | 지난주(week) : <span class='red'>".$week_frdate."~".$week_todate."</span>";
echo "</strong> <br>";

function grade_name($val)
{
    global $grade_cnt;
    if($val == 4){$full_name = '제타';}
    else if($val == 3){$full_name = '테라';}
    else if($val == 2){$full_name = '기가';}
    else if($val == 1){$full_name = '메가';}

    $grade_name = $val . " STAR - ".$full_name;

    return $grade_name;
}

if( !function_exists( 'array_column' ) ):
    
    function array_column( array $input, $column_key, $index_key = null ) {
    
        $result = array();
        foreach( $input as $k => $v )
            $result[ $index_key ? $v[ $index_key ] : $k ] = $v[ $column_key ];
        
        return $result;
    }
endif;


/* 승급기준 로그 출력 */
echo "<br><code>회원직급 승급 조건   |   기준조건 :" . $pre_condition . "<br>";
for ($i = 0; $i < $grade_cnt; $i++) {
    echo "<br>" . grade_name($i + 1);
    echo  " -  [ 승급기준]  본인구매기준" . " P" . ($lvlimit_sales_level[$i]) . " 이상 / 추천산하매출(3대) " . Number_format($lvlimit_recom[$i] * $lvlimit_recom_val) . " 이상<br>";
}
echo "</code><br><br><br>";

echo "<strong>현재 직급 기준 대상자</strong> : ";

if($pre_count > 0){
    while ($cnt_row = sql_fetch_array($pre_result)) {
        echo "<br><strong>" . $cnt_row['grade'] . " STAR : <span class='red'>" . $cnt_row['cnt'] . '</span> 명</strong>';
    }
}else{
    echo "<span class='red'>대상자없음</span>";
}

echo "</span><br><br>";
echo "<div class='btn' onclick='bonus_url();'>돌아가기</div>";

?>

<html>

<body>
    <header>승급시작</header>
    <div>

        <?
        $mem_list = array();
        if($pre_count > 0){
            excute();
        }

         /* $mem_result = return_down_tree('one0780',10);
        $recom_info_sales = array_int_sum($mem_result,'mb_save_point','int');
        $recom_info_hash = array_int_sum($mem_result,'mb_rate','int');

        echo "<code>";
        echo "<br>////////// 추천 10대<br>";
        echo "SALES :".$recom_info_sales;
        echo "<br>";
        echo "hash :".$recom_info_hash;
        echo "</code>"; */

        // 산하 후원 10대 매출, 해쉬기록
        /* list($mem_result_l,$mem_result_r) = return_brecommend('one0780',10,false);

        $brecom_info_l_hash = array_int_sum($mem_result_l,'mb_rate','int');
        $brecom_info_l_sales = array_int_sum($mem_result_l,'mb_save_point','int');

        $brecom_info_r_hash = array_int_sum($mem_result_r,'mb_rate','int');
        $brecom_info_r_sales = array_int_sum($mem_result_r,'mb_save_point','int');

        echo "<br><br>";
        echo "<code>";
        print_R($mem_result_l);
        echo "<br<br>////////// 후원 10대 L- ".count($mem_result_l);
        echo "<br>";
        echo "SALES :".$brecom_info_l_sales;
        echo "<br>";
        echo "hash :".$brecom_info_l_hash;

        echo "<br><br>";
        print_R($mem_result_r);
        echo "<br<br>>////////// 후원 10대 R- ".count($mem_result_r);
        echo "<br>";
        echo "SALES :".$brecom_info_r_sales;
        echo "<br>";
        echo "hash :".$brecom_info_r_hash;
        echo "</code>"; */

        //직하부매출
        /* function recom_sales($mb_id){
            $mem_recom_sql = "SELECT * FROM g5_member where mb_recommend = '{$mb_id}' ";
            $mem_recom_result = sql_query($mem_recom_sql);
            $recom_sales = [];
            
            while($row = sql_fetch_array($mem_recom_result)){
                $recom = $row['mb_id'];
                $sql = "SELECT mb_id, mb_save_point FROM g5_member where mb_id ='{$recom}' ";
                $result = sql_fetch($sql);

                if($result){
                    $recom_sale = $result['mb_save_point'];

                    echo $mb_id."<br>";
                    echo $recom_sale."<br>";

                    if(!$recom_sale){
                        $recom_sale = 0;
                    }
                    array_push($recom_sales,$recom_sale);
                }else{
                    array_push($recom_sales,0);
                }
            }
            return $recom_sales;
        } */

    

        /*추천 하부라인 */
        function return_down_tree($mb_id,$cnt=0){
            global $config,$g5,$mem_list;

            $mb_result = sql_fetch("SELECT mb_id,mb_level,grade,mb_rate,mb_save_point,rank,recom_sales from g5_member WHERE mb_id = '{$mb_id}' ");
            $result = recommend_downtrees($mb_result['mb_id'],0,$cnt);
            return $result;
        }

        
        function recommend_downtrees($mb_id,$count=0,$cnt = 0){
            global $mem_list;

            if($cnt == 0 || ($cnt !=0 && $count < $cnt)){
                
                $recommend_tree_result = sql_query("SELECT mb_id,mb_level,grade,mb_rate,mb_save_point,rank,recom_sales from g5_member WHERE mb_recommend = '{$mb_id}' ");
                $recommend_tree_cnt = sql_num_rows($recommend_tree_result);
        
                if($recommend_tree_cnt > 0 ){
                    ++$count;
                    while($row = sql_fetch_array($recommend_tree_result)){
                        
                        array_push($mem_list,$row);
                        recommend_downtrees($row['mb_id'],$count,$cnt);
                    }
                }
            }
            return $mem_list;
        }
    
        /* 후원 조건 검사 */
        /* function brecom_grade($mb_id, $grade_condition = 1, $sales_condition = 0)
        {
            global $config, $brcomm_arr, $debug;
            $origin = $mb_id;

            // 후원 하부 L,R 구분
            list($leg_list, $cnt) = brecommend_direct($mb_id);

            if ($cnt == 2) {

                $L_member = $leg_list[0]['mb_id'];
                $R_member = $leg_list[1]['mb_id'];


                $brcomm_arr = [];
                array_push($brcomm_arr, $leg_list[0]);
                $manager_list_L = brecommend_array($L_member, 0);
            

                $brcomm_arr = [];
                array_push($brcomm_arr, $leg_list[1]);
                $manager_list_R = brecommend_array($R_member, 0);
                

                
                // echo "<br><span> 직급 :: ";
                list($L_grade_array, $L_grade_count) = array_index_sort($manager_list_L, 'grade', $grade_condition);
                list($R_grade_array, $R_grade_count) = array_index_sort($manager_list_R, 'grade', $grade_condition);
                // if($debug){echo "<code>";print_R($L_grade_array);echo "<br>";print_R($R_grade_array);echo "</code>";}
                // echo "L : ".$L_grade_count.' / R :'.$R_grade_count;
                echo "</span>";

                
                if ($sales_condition > 0) {
                    list($L_sales_array, $L_sales_count) = array_index_sort($manager_list_L, 'mb_rate', $sales_condition);
                    list($R_sales_array, $R_sales_count) = array_index_sort($manager_list_R, 'mb_rate', $sales_condition);
                    
                    echo "</span>";
                    return array($L_member, $L_grade_array, $L_grade_count, $R_member, $R_grade_array, $R_grade_count, $L_sales_count, $R_sales_count);
                } else {
                    return array($L_member, $L_grade_array, $L_grade_count, $R_member, $R_grade_array, $R_grade_count);
                }
            } else if ($cnt < 2) {
                echo "<span>후원 L,R 라인 없음</span>";
            } else {

                echo "<span>후원인 초과</span>";
            }
        } */


       


        function  excute()
        {

            global $g5, $search_condition, $admin_condition, $pre_condition;
            global $bonus_day, $week_frdate, $week_todate, $grade_cnt, $code, $lvlimit_cnt, $lvlimit_sales_level, $lvlimit_recom, $lvlimit_recom_val, $lvlimit_pv;
            global $debug,$mem_list;

            for ($i = $grade_cnt-1; $i > -1; $i--) {
                $cnt_sql = "SELECT count(*) as cnt From {$g5['member_table']} WHERE grade = {$i} {$search_condition}" . $admin_condition . $pre_condition . " ORDER BY mb_no";
                
                $cnt_result = sql_fetch($cnt_sql);

                $sql = "SELECT * FROM {$g5['member_table']} WHERE grade = {$i} {$search_condition}" . $admin_condition . $pre_condition . " ORDER BY mb_no ";
                $result = sql_query($sql);

                $member_count  = $cnt_result['cnt'];

                echo "<br><br><span class='title block'>" . $i . " STAR (" . $member_count . ")</span><br>";
                echo  " -  [ 승급기준 ] 보유구매등급 : P" . ($lvlimit_sales_level[$i]) . " 이상 | 추천산하매출(3대) : " . Number_format($lvlimit_recom[$i]*$lvlimit_recom_val) . " 이상 ";

                // 1STAR 예외
                /* $lvlimit_recom_pv = 0;
                if ($i == 0) {
                    echo "| 하부 PV L,R 500 만원 이상| 본인 PV : 500 만원 이상";
                    $lvlimit_recom_pv = $lvlimit_pv;
                } */


                // 디버그 로그 
                if ($debug) {
                    echo "<code>";
                    echo ($sql);
                    echo "</code><br>";
                }

                while ($row = sql_fetch_array($result)) {

                    $mb_no = $row['mb_no'];
                    $mb_id = $row['mb_id'];
                    $mb_name = $row['mb_name'];
                    $mb_level = $row['mb_level'];
                    $mb_deposit = $row['mb_deposit_point'];
                    $mb_balance = $row['mb_balance'];
                    $mb_save_point = $row['mb_save_point'];
                    $mb_rate = $row['mb_rate'];
                    $grade = $row['grade'];
                    $item_rank = $row['rank'];
                    $all_hash = $row['recom_mining']+$row['brecom_mining']+$row['brecom2_mining']+$row['super_mining'];

                    // $star_rate = $bonus_rate[$i-1]*0.01;

                    $rank_option1 = 0;
                    $rank_option2 = 0;
                    $rank_option3 = 0;
                    $rank_grade = '';
                    $rank_cnt = 0;
                    echo "<br><br><br><span class='title' >[ " . $row['mb_id'] . " ] </span>";

                    // 관리자 제외
                    if ($mb_level > 9) {
                        break;
                    }

                    if ($member_count != 0) {

                        /* // 직추천자수 
                        $mem_cnt_sql = "SELECT count(*) as cnt FROM g5_member where mb_recommend = '{$mb_id}' ";
                        $mem_cnt_result = sql_fetch($mem_cnt_sql);
                        $mem_cnt = $mem_cnt_result['cnt'];

                        echo "<br>직추천인수 : <span class='blue'>" . $mem_cnt . "</span>";
                        if ($mem_cnt >= $lvlimit_cnt[$i]) {
                            $rank_cnt += 1;
                            $rank_option1 = 1;
                            echo "<span class='red'> == OK </span>";
                        } */


                        /* // 내 매출 
                        $mem_pv = $mb_rate;
                        echo "<br>본인 PV : <span class='blue'>" . Number_format($mem_pv) . "</span>";
                        if ($mem_pv >= $lvlimit_pv) {
                            $rank_cnt += 1;
                            $rank_option2 = 1;
                            echo "<span class='red'> == OK </span>";
                        } */


                        // 하부 지난주 매출 - 사용안함
                        // $recom_week_sales = recom_sales($mb_id);
                        // echo "<br>지난주 하부 매출 - "; */
                        //print_R($recom_week_sales);
                        /* if($recom_week_sales){
                            $sum_sale = array_sum($recom_week_sales);
                            $max_sale = max($recom_week_sales);
                            
                            echo  "하부매출(". $sum_sale .") - 대실적(". $max_sale .") = 계산실적( <span class='blue'>".($sum_sale - $max_sale)."</span> )";
                            // if($mem_sales >= $lvlimit_recom[$i]*$lvlimit_recom_val){$rank_cnt += 1;}
                            if($mem_sales >= $lvlimit_recom[$i]*1){$rank_cnt += 1; echo "<span class='red'> == OK </span>";}
                            if($debug)  echo "<code>"; print_R($recom_week_sales);echo "</code>";
                        }
                        */


                        // 하부 직급 확인
                        /* echo "<br>후원하부 : ";
                        list($L_member, $L_grade_array, $L_grade_count, $R_member, $R_grade_array, $R_grade_count, $L_sales_count, $R_sales_count) = brecom_grade($mb_id, $lvlimit_sales_level[$i], $lvlimit_recom_pv);
                        if ($L_member) {
                            echo "L - <strong>" . $L_member . "</strong>";
                        }
                        if ($R_member) {
                            echo " | R - <strong>" . $R_member . "</strong>";
                        }
                        echo "<br>";


                        if ($i == 0) {
                            // 1STAR 매출
                            if ($L_sales_count >= 1 && $R_sales_count >= 1) {
                                echo "└ 매출기준 : <span class=blue>L : " . $L_sales_count . '명 / R :' . $R_sales_count . "명</span>";
                                $rank_cnt += 1;
                                $rank_option3 = 1;
                                echo "<span class='red'> == OK </span>";
                            } else {
                                if ($L_sales_count == '') {
                                    $L_sales_count = 0;
                                }
                                if ($R_sales_count == '') {
                                    $R_sales_count = 0;
                                }
                                echo "└ 매출기준 : L : " . $L_sales_count . '명 / R :' . $R_sales_count . "명";
                                // echo "<span class='red'> == X </span>";
                            }
                            $rank_grade = $L_sales_count . ',' . $R_sales_count;
                        } else {
                            // 1STAR 직급
                            if ($L_member) {
                                if ($L_grade_count >= $lvlimit_recom[$i] && $R_grade_count >= $lvlimit_recom[$i]) {
                                    echo "└ 직급기준 : <span class=blue>L : " . $L_grade_count . '명 / R :' . $R_grade_count . "명</span>";
                                    $rank_cnt += 1;
                                    $rank_option3 = 1;
                                    echo "<span class='red'> == OK </span>";
                                } else {
                                    echo "└ 직급기준 :  L : " . $L_grade_count . '명 / R :' . $R_grade_count . "명";

                                    // echo "<span class='red'> == X </span>";
                                }
                            }
                            $rank_grade = $L_grade_count . ',' . $R_grade_count;
                        } */

                        // 내 구매등급  
                        echo "<br>본인 아이템등급 : <span class='blue'>P" . Number_format($item_rank) . "</span>";
                        
                        if ($item_rank >= $lvlimit_sales_level[$i]) {
                            $rank_cnt += 1;
                            $rank_option1 = 1;
                            echo "<span class='red'> == OK </span>";
                        }

                        // 산하 추천 3대 매출 -  save_point 기준
                        $mem_result = return_down_tree($mb_id,3);
                        $recom_sales = array_int_sum($mem_result,'mb_save_point','int');

                        if(!$recom_sales){
                            $recom_sales = 0;
                        }
                        $recom_id = array_index_sum($mem_result,'mb_id','text');
                        $recom_sales_value = Number_format($recom_sales);
                        

                        echo "<br>산하추천(3대)매출 : <span class='blue'>" .$recom_sales_value. "</span>";
                        
                        if( $recom_sales >= $lvlimit_recom[$i]*$lvlimit_recom_val){
                            $rank_cnt += 1;
                            $rank_option2 = 1;
                            echo "<span class='red'> == OK </span>";
                        }

                        $mem_list = array();
                        // echo "<br><span class='desc'>└ 추천하부3대 : ";
                        // echo ($recom_id);
                        // echo "</span>";




                        // 기록용 
                        // 산하 추천 10대 매출, 해쉬기록 
                        $mem_result_10 = array();
                        $mem_result_10 = return_down_tree($mb_id,10);
                        $recom_info_sales = array_int_sum($mem_result_10,'mb_save_point','int');
                        $recom_info_hash = array_int_sum($mem_result_10,'mb_rate','int');
                        $recom_cnt = count($mem_result_10);

                        if($debug){
                        echo "<code>";
                        echo "<br>////////// 추천 10대<br>";
                        echo "SALES :".$recom_info_sales;
                        echo "<br>";
                        echo "hash :".$recom_info_hash;
                        echo "</code>";
                        }
                        $mem_list = array();
                        
                        
                        // 산하 후원 10대 매출, 해쉬기록
                        list($mem_result_l,$mem_result_r) = return_brecommend($mb_id,10,false);

                        $cnt_l =count($mem_result_l);
                        $brecom_info_l_hash = array_int_sum($mem_result_l,'mb_rate','int');
                        $brecom_info_l_sales = array_int_sum($mem_result_l,'mb_save_point','int');

                        $cnt_r =count($mem_result_r);
                        $brecom_info_r_hash = array_int_sum($mem_result_r,'mb_rate','int');
                        $brecom_info_r_sales = array_int_sum($mem_result_r,'mb_save_point','int');

                        $brecom_info_lr_hash = $brecom_info_l_hash + $brecom_info_r_hash;
                        $brecom_info_lr_sales = $brecom_info_l_sales + $brecom_info_r_sales;
                        $brecom_cnt = $cnt_l + $cnt_r;


                        list($mem2_result_l,$mem2_result_r) = return_brecommend($mb_id,10,false,2);

                        $cnt2_l =count($mem2_result_l);
                        $brecom2_info_l_hash = array_int_sum($mem2_result_l,'mb_rate','int');
                        $brecom2_info_l_sales = array_int_sum($mem2_result_l,'mb_save_point','int');

                        $cnt2_r =count($mem2_result_r);
                        $brecom2_info_r_hash = array_int_sum($mem2_result_r,'mb_rate','int');
                        $brecom2_info_r_sales = array_int_sum($mem2_result_r,'mb_save_point','int');

                        $brecom2_info_lr_hash = $brecom2_info_l_hash + $brecom2_info_r_hash;
                        $brecom2_info_lr_sales = $brecom2_info_l_sales + $brecom2_info_r_sales;
                        $brecom2_cnt = $cnt2_l + $cnt2_r;

                        $mining_total_sql = "SELECT format(SUM(mining),8) as mining from soodang_mining WHERE mb_id = '{$mb_id}' AND day = '{$bonus_day}' ";
                        $mining_total = sql_fetch($mining_total_sql)['mining'];

                        if($debug){
                            echo "<code>";
                            // print_R($mem_result_l);
                            echo "<br>////////// 후원 10대<br>";
                            echo "SALES L:".$brecom_info_l_sales;
                            echo "<br>";
                            echo "hash L:".$brecom_info_l_hash;
                            echo "<br>";
                            // print_R($mem_result_r);
                            echo "<br>";
                            echo "SALES R:".$brecom_info_r_sales;
                            echo "<br>";
                            echo "hash R:".$brecom_info_r_hash;
                            echo "<br>";
                            echo "mining_total : ".$mining_total;
                            echo "</code>"; 
                        }
                        
                        
                            /* echo "<code>";
                            echo "<br>해쉬 : ".$row['mb_rate'];
                            echo "<br>메가풀 : ".$row['recom_mining'];
                            echo "<br>제타풀 : ".$row['brecom_mining'];
                            echo "<br>제타+ : ".$row['brecom2_mining'];
                            echo "<br>슈퍼 :".$row['super_mining'];
                            echo "<br>총보너스 :".($row['recom_mining']+$row['brecom_mining']+$row['brecom2_mining']+$row['super_mining']);
                            echo "<br>승급대상 :".$row['recom_sales'];
                            echo "<br>";
                            echo "</code>"; */

                            $recom_info_data = "INSERT into g5_member_info(mb_id,date, recom_info,brecom_info,brecom2_info,hash_info) values('{$mb_id}', '{$bonus_day}',json_object(
                                'hash_10', $recom_info_hash, 
                                'sales_10', $recom_info_sales, 
                                'sales_3', {$row['recom_sales']},
                                'cnt', $recom_cnt
                            ), json_object(
                                'hash_10', $brecom_info_lr_hash, 
                                'sales_10', $brecom_info_lr_sales,
                                'cnt', $brecom_cnt,
                                'LEFT', json_object(
                                    'cnt', $cnt_l,
                                    'hash', $brecom_info_l_hash,
                                    'sales', $brecom_info_l_sales
                                    ),
                                'RIGHT',json_object(
                                    'cnt', $cnt_r,
                                    'hash', $brecom_info_r_hash,
                                    'sales', $brecom_info_r_sales
                                )
                            ),json_object(
                                'hash_10', $brecom2_info_lr_hash, 
                                'sales_10', $brecom2_info_lr_sales,
                                'cnt', $brecom2_cnt,
                                'LEFT', json_object(
                                    'cnt', $cnt2_l,
                                    'hash', $brecom2_info_l_hash,
                                    'sales', $brecom2_info_l_sales
                                    ),
                                'RIGHT',json_object(
                                    'cnt', $cnt2_r,
                                    'hash', $brecom2_info_r_hash,
                                    'sales', $brecom2_info_r_sales
                                )
                            ), json_object(
                                'hash', {$row['mb_rate']}, 
                                'mega', {$row['recom_mining']}, 
                                'zeta', {$row['brecom_mining']},
                                'zetaplus', {$row['brecom2_mining']},
                                'super', {$row['super_mining']},
                                'all', {$all_hash},
                                'mining_total' , {$mining_total}
                            ))";

                            if($debug){
                                print_R($recom_info_data);
                            }else{
                                sql_query( $recom_info_data );
                            }
                            
                        
                        
                        /* if($recom_week_sales){
                            $sum_sale = array_sum($recom_week_sales);
                            $max_sale = max($recom_week_sales);
                            
                            echo  "하부매출(". $sum_sale .") - 대실적(". $max_sale .") = 계산실적( <span class='blue'>".($sum_sale - $max_sale)."</span> )";
                            // if($mem_sales >= $lvlimit_recom[$i]*$lvlimit_recom_val){$rank_cnt += 1;}
                            if($mem_sales >= $lvlimit_recom[$i]*1){$rank_cnt += 1; echo "<span class='red'> == OK </span>";}
                            if($debug)  echo "<code>"; print_R($recom_week_sales);echo "</code>";
                        }
                        /* if ($item_rank >= $lvlimit_sales_level[$i]) {
                            $rank_cnt += 1;
                            $rank_option2 = 1;
                            echo "<span class='red'> == OK </span>";
                        } */


                        // 디버그 로그
                        if ($debug) {
                            echo "<code> Total Rank count :: ";
                            echo $rank_cnt;
                            echo "</code><br>";
                        }

                        // 승급조건 기록

                        /* $rank_record_sql = "INSERT INTO (mb_id,rank,option1,option1_result,option2,option2_result,option3,option3_result) VALUE ";
                        $rank_record_mem_sql .= "('{$row['mb_id']}',{$i},'{$mem_cnt}',{$rank_option1},'{$mem_pv}',{$rank_option2},'{$rank_grade}',{$rank_option3})"; */

                        $update_mem_rank = "UPDATE g5_member SET recom_sales = {$recom_sales} ";
                        $update_mem_rank .= ",mb_4 = '{$item_rank}',mb_5= '{$rank_option1}' ";
                        $update_mem_rank .= ",mb_6 = '{$recom_sales}',mb_7= '{$rank_option2}' ";
                        $update_mem_rank .= "WHERE mb_id = '{$row['mb_id']}' ";

                        if ($debug) {
                            echo "<code>";
                            print_R($update_mem_rank);
                            echo "</code>";
                            // sql_query($update_mem_rank);
                        } else {
                            sql_query($update_mem_rank);
                        }

                        // 승급로그
                        if ($rank_cnt >= 2) {
                            $upgrade = ($grade + 1);
                            echo "<br><span class='red'> ▶▶ 직급 승급 => " . $upgrade . " STAR </span><br> ";
                            $rec = $code . ' Update to ' . ($grade + 1) . ' STAR IN ' . $bonus_day;


                            //**** 수당이 있다면 함께 DB에 저장 한다.
                            $bonus_sql = " insert rank set rank_day='" . $bonus_day . "'";
                            $bonus_sql .= " ,mb_id			= '" . $mb_id . "'";
                            $bonus_sql .= " ,old_level		= '" . $grade . "'";
                            $bonus_sql .= " ,rank      = " . $upgrade;
                            $bonus_sql .= " ,rank_note	= '" . $rec . "'";
                            "'";


                            // 디버그 로그
                            if ($debug) {
                                echo "<br><code>";
                                print_R($bonus_sql);
                                echo "</code>";
                            } else {
                                sql_query($bonus_sql);
                            }

                            $balance_up = "update g5_member set grade = {$upgrade} where mb_id = '" . $mb_id . "'";

                            // 디버그 로그
                            if ($debug) {
                                echo "<code>";
                                print_R($balance_up);
                                echo "</code>";
                            } else {
                                sql_query($balance_up);
                            }
                        } // if $rank_cnt

                        $mem_list = array();
                        $mem_result_l = array();
                        $mem_result_r = array();
                    } // if else
                } //while


                $rec = '';
                
            } //for
        } //function
        ?>

        <? include_once('./bonus_footer.php'); ?>

        <?
        if ($debug) {
        } else {
            $html = ob_get_contents();
            //ob_end_flush();
            $logfile = G5_PATH . '/data/log/' . $code . '/' . $code . '_' . $bonus_day . '.html';
            fopen($logfile, "w");
            file_put_contents($logfile, ob_get_contents());
        }
        ?>