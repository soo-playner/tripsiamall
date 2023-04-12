<?php

$sub_menu = "600200";
include_once('./_common.php');
// $debug = 1;
include_once('./bonus_inc.php');
include_once(G5_PATH . '/util/recommend.php');

auth_check($auth[$sub_menu], 'r');

/* 마이닝풀기록 사용 여부 */
$Mining_Solution = Mining_solution;


if (!$debug) {
    $dupl_check_sql = "select mb_id from rank where rank_day='" . $bonus_day . "'";
    $get_today = sql_fetch($dupl_check_sql);

    if ($get_today['mb_id']) {
        alert($bonus_day . " 해당일 승급이 이미 완료 되었습니다.");
        die;
    }

    if ($Mining_Solution) {
        $record_check_sql = "select mb_id from g5_member_info where date='" . $bonus_day . "'";
        $get_record = sql_fetch($record_check_sql);

        if ($get_record['mb_id']) {
            $record_delete = "DELETE FROM g5_member_info WHERE date = '{$bonus_day}' ";
            sql_query($record_delete);
        }
    }
}

// 직급 승급
$grade_cnt = 7;
$levelup_result = bonus_pick($code);

//직추천 회원 기준
$lvlimit_cnt = explode(',', $levelup_result['layer']);


// 구매등급기준
$lvlimit_sales_level = explode(',', $levelup_result['rate']);
// $lvlimit_sales_level_val = 1000;


// 추천산하매출기준
$lvlimit_recom = explode(',', $levelup_result['kind']);
$lvlimit_recom_val = 10000;



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
    $full_name = '';
    if($val == 0){$full_name = '(에이전트)';}
    /* else if($val == 3){$full_name = '테라';} */
    /* else if($val == 2){$full_name = '기가';} */
    /* else if($val == 1){$full_name = '메가';} */

    $grade_name = $val . " STAR".$full_name;

    return $grade_name;
}

function limit_conditions($val)
{
    if (preg_match("/^\{(.+)\}/", $val, $matches)) {
        $temp = explode(':', $matches[1]);
        $result = $temp[0] . '그룹|' . $temp[1] . 'star 이상';
    } else {
        $result = $val . '명 이상';
    }

    return $result;
}

function array_columns(array $rows, array $keys)
{
    foreach ($rows as $i => $row) {
        $new_row = [];
        foreach ($keys as $key) $new_row[$key] = $row[$key];
        $rows[$i] = $new_row;
    }
    return $rows;
}

/* 결과 합계 중복제거 ++ 구별값 추가 */
function array_index_cherry_pick_diff($list, $keys, $average)
{
    $sum = null;
    $count = 0;
    $cherry_pick_list = [];

    $master_key = $keys[0];
    $compare = $keys[1];

    foreach ($list as $i => $row) {
        $new_row = [];
        foreach ($keys as $key) $new_row[$key] = $row[$key];
        $rows[$i] = $new_row;

        if($new_row[$master_key] > $average ){
            array_push($cherry_pick_list,$new_row);
        }
    }

    $diff_cnt = array_count_values(array_column($cherry_pick_list, $compare));
    return array($diff_cnt,count($diff_cnt));
}

/* 
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
} */

if (!function_exists('array_column')) :

    function array_column(array $input, $column_key, $index_key = null)
    {

        $result = array();
        foreach ($input as $k => $v)
            $result[$index_key ? $v[$index_key] : $k] = $v[$column_key];

        return $result;
    }
endif;


/* 승급기준 로그 출력 */
echo "<br><code>회원직급 승급 조건   |   기준조건 :" . $pre_condition . "<br>";
for ($i = 0; $i < $grade_cnt; $i++) {
    echo "<br>" . grade_name($i + 1);
    echo  " -  [ 승급기준]  본인구매기준" . ": P" . Number_format($lvlimit_sales_level[$i]) . " 이상 ";
    echo  "/ 추천라인 산하매출" . Number_format($lvlimit_recom[$i] * $lvlimit_recom_val) . " 이상 ";
    echo  "/ 직추천 : " . limit_conditions($lvlimit_cnt[$i]) . '<br>';
}
echo "</code><br><br><br>";

echo "<strong>현재 직급 기준 대상자</strong> : ";

if ($pre_count > 0) {
    while ($cnt_row = sql_fetch_array($pre_result)) {
        echo "<br><strong>" . $cnt_row['grade'] . " STAR : <span class='red'>" . $cnt_row['cnt'] . '</span> 명</strong>';
    }
} else {
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
        if ($pre_count > 0) {
            excute();
        }



        /*추천 하부라인 */
        function return_down_tree($mb_id, $cnt = 0)
        {
            global $config, $g5, $mem_list;

            $mb_result = sql_fetch("SELECT mb_id,mb_level,grade,mb_rate,mb_save_point,rank,recom_sales from g5_member WHERE mb_id = '{$mb_id}' ");
            $result = recommend_downtrees($mb_result['mb_id'], 0, $cnt);
            return $result;
        }


        function recommend_downtrees($mb_id, $count = 0, $cnt = 0, $tree_cnt = '')
        {
            global $mem_list;

            if ($cnt == 0 || ($cnt != 0 && $count < $cnt)) {

                $recommend_tree_result = sql_query("SELECT mb_id,mb_level,grade,mb_rate,mb_save_point,rank,recom_sales from g5_member WHERE mb_recommend = '{$mb_id}' ");
                $recommend_tree_cnt = sql_num_rows($recommend_tree_result);

                if ($tree_cnt == '') {
                    $tree_cnt  = $recommend_tree_cnt + 1;
                }
                if ($recommend_tree_cnt > 0) {

                    ++$count;

                    while ($row = sql_fetch_array($recommend_tree_result)) {
                        if ($count == 1) {
                            --$tree_cnt;
                        }

                        $row['line'] = $tree_cnt;
                        array_push($mem_list, $row);
                        recommend_downtrees($row['mb_id'], $count, $cnt, $tree_cnt);
                    }
                }
            }
            return $mem_list;
        }





        function  excute()
        {

            global $g5, $search_condition, $admin_condition, $pre_condition;
            global $bonus_day, $week_frdate, $week_todate, $grade_cnt, $code, $lvlimit_cnt, $lvlimit_sales_level, $lvlimit_recom, $lvlimit_recom_val, $lvlimit_pv;
            global $debug, $mem_list;

            for ($i = $grade_cnt - 1; $i > -1; $i--) {
                $cnt_sql = "SELECT count(*) as cnt From {$g5['member_table']} WHERE grade = {$i} {$search_condition}" . $admin_condition . $pre_condition . " ORDER BY mb_no";

                $cnt_result = sql_fetch($cnt_sql);

                $sql = "SELECT * FROM {$g5['member_table']} WHERE grade = {$i} {$search_condition}" . $admin_condition . $pre_condition . " ORDER BY mb_no ";
                $result = sql_query($sql);

                $member_count  = $cnt_result['cnt'];

                echo "<br><br><span class='title block'>" .grade_name($i) ."(" . $member_count . ")</span><br>";
                echo  " -  [ 승급기준 ] 보유구매등급 : P" . ($lvlimit_sales_level[$i]) . " 이상 | 추천산하매출 : " . Number_format($lvlimit_recom[$i] * $lvlimit_recom_val) . " 이상 ";
                echo  " | 직추천 : " . limit_conditions($lvlimit_cnt[$i]) . '<br>';

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
                    // $all_hash = $row['recom_mining'] + $row['brecom_mining'] + $row['brecom2_mining'] + $row['super_mining'];

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





                        // 내 구매등급  
                        echo "<br>본인 아이템등급 : <span class='blue'>P" . Number_format($item_rank) . "</span>";

                        if ($item_rank >= $lvlimit_sales_level[$i]) {
                            $rank_cnt += 1;
                            $rank_option1 = 1;
                            echo "<span class='red'> == OK </span>";
                        }


                        // 산하 추천 매출 -  save_point 기준
                        $mem_result = return_down_tree($mb_id, 0);
                        $recom_sales = array_int_sum($mem_result, 'mb_save_point', 'int');

                        if (!$recom_sales) {
                            $recom_sales = 0;
                        }
                        $recom_id = array_index_sum($mem_result, 'mb_id', 'text');
                        $recom_sales_value = Number_format($recom_sales);


                        echo "<br>산하추천매출 : <span class='blue'>" . $recom_sales_value . "</span>";

                        if ($recom_sales >= $lvlimit_recom[$i] * $lvlimit_recom_val) {
                            $rank_cnt += 1;
                            $rank_option2 = 1;
                            echo "<span class='red'> == OK </span>";
                        }

                        $mem_list = array();
                        /*  echo "<br><span class='desc'>└ 추천산하 : ";
                        echo ($recom_id);
                        echo "</span>"; */


                        // 직추천자수 
                        if ($i == 0) {
                            $mem_cnt_sql = "SELECT count(*) as cnt FROM g5_member where mb_recommend = '{$mb_id}' ";
                            $mem_cnt_result = sql_fetch($mem_cnt_sql);
                            $mem_cnt = $mem_cnt_result['cnt'];
                            

                            echo "<br>직추천인수 : <span class='blue'>" . $mem_cnt . "</span>";
                            if ($mem_cnt >= $lvlimit_cnt[$i]) {
                                $rank_cnt += 1;
                                $rank_option3 = 1;
                                echo "<span class='red'> == OK </span>";
                            }
                        } else {
                            // 하부 직급 확인
                            $cherry_pick_array = array_index_cherry_pick_diff($mem_result, array('grade', 'line'), 0);
                            $mem_cnt = $cherry_pick_array[1];
                            
                            echo "<br>추천하부 ".$i."스타 이상 그룹수: <span class='blue'>" . $mem_cnt . "</span>";
                            if ($mem_cnt >= $lvlimit_cnt[$i]) {
                                $rank_cnt += 1;
                                $rank_option3 = 1;
                                echo "<span class='red'> == OK </span>";
                            }
                        }

                        
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
                        $update_mem_rank .= ",mb_8 = '{$mem_cnt}',mb_9= '{$rank_option3}' ";
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
                        if ($rank_cnt >= 3) {
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