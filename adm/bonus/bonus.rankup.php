<?php

$sub_menu = "600200";
include_once('./_common.php');
include_once('./bonus_inc.php');

auth_check($auth[$sub_menu], 'r');

$debug=1;

// 승급수당
$bonus_row = bonus_pick($code);
$bonus_rate = explode(',',$bonus_row['rate']);
$avata = explode(',',$bonus_row['limited']);
$bonus_token = explode(',',$bonus_row['layer']);

ob_start();

// 설정로그 
echo "<strong> 승급기준일 : ".$bonus_day." <br>";

/* if($debug){
    echo "<br><code>회원직급 승급 조건   |   기준조건 :".$pre_condition."<br>";
    for($i=0; $i< $grade_cnt; $i++){
        echo  " <br>    -  [ 승급기준]  추천인". $lvlimit_cnt[$i]."명 이상 |  본인매출 : ". ($lvlimit_sales[$i]*$lvlimit_sales_val)." 이상  |  하부매출 : ".($lvlimit_recom[$i]*$lvlimit_recom_val)."이상 " ;   
    }echo "</code><br>";
} */

echo "<strong>승급 수당/설정</strong> : ";

for( $i=0; $i < count($bonus_rate); $i++ ){
    echo "<br><strong>G".$i." level : ";
    echo "승급수당 :<span class='red' style='display:inline-block;width:80px;text-align:right;'>".Number_format($bonus_rate[$i])." $ </span>";
    echo " | 아바타생성 : <span class='black' style='display:inline-block;width:80px;text-align:right;'>".$avata[$i]."개 </span>";
    echo " | 토큰수당 : <span class='blue' style='display:inline-block;width:80px;text-align:right;'>".Number_format($bonus_token[$i])." KFUL </span>"; 
    echo "</strong>";
}
echo "</span><br><br>";
echo "<div class='btn' onclick='bonus_url();'>돌아가기</div>";
?>

<html><body>
<header>상품승급시작</header>    
<div>

<?
for($z=1; $z < 9; $z++){
    rankup($z);
}

// rankup(2);
// rankup(3);

function rankup($val){
    global $g5, $debug, $bonus_day,$code;
    global $bonus_rate,$avata, $bonus_token;

    $rank_target = "package_g".$val;
    $next_rank_target = "package_g".($val+1);
    
    $rank_cnt_sql = "SELECT * FROM {$rank_target}";
    $rank_cnt_result = sql_query($rank_cnt_sql);
    $rank_total_cnt = sql_num_rows($rank_cnt_result);

    if($val == 1){
        $rank_dumy_sql = " SELECT count(*) as cnt FROM package_g0 ";
        $rank_dumy_result = sql_fetch($rank_dumy_sql);
        $rank_dumy_count = $rank_dumy_result['cnt'];
        $rank_total_cnt = $rank_total_cnt + $rank_dumy_count;
    }
    $ranked_sql = "SELECT * FROM {$rank_target} where promote = 1 ";
    $ranked_result = sql_query($ranked_sql);
    $ranked_cnt = sql_num_rows($ranked_result);

    $promote_num = floor(($rank_total_cnt-$ranked_cnt*5)/5);
    $rank_sql = "SELECT * FROM {$rank_target} where promote = 0 ";
    $rank_result = sql_query($rank_sql);
    $rank_cnt = sql_num_rows($rank_result);


    echo "<br><br><div class='block'>";
    echo "상품 랭크 - G{$val} :: ";
    echo "G 합계 : <span class='blue'>".$rank_total_cnt;
    if($val == 1){echo "(G0:".$rank_dumy_count.")";}
    echo "</span> / ";
    echo "G승급 소모 :<span class='red'>-".$ranked_cnt."(".($ranked_cnt*5).")</span> / ";
    echo "G상품 : <span>".$rank_cnt."</span>개 / ";
    echo "승급 가능 : <span class='red'>".$promote_num."</span>개 </div><br>";
    
    

    if($promote_num > 0)
    {
        for($i=0;$row=sql_fetch_array($rank_result); $i++) {
            
            if($i < $promote_num){

                $no = $row['no'];
                $idx = $row['idx'];
                $mb_id = $row['mb_id'];
                $nth = $row['nth'];
                $it_name = $row['it_name'];
                $cdate = $row['cdate'];
                $cdatetime = $row['cdatetime'];
                $od_id = $row['od_id'];
                
                /* 승급 기록*/
                echo "<br><br><br><span class='title'>".$no." / ".$mb_id.'_'.$nth."";
                echo " -  <span class='red'>G".($val+1)." 상품승급</span>";
                echo "</span>";

                /* 상품 업데이트*/
                $update_item = "UPDATE {$rank_target} SET promote = 1 WHERE no = {$no} ";
                if($debug){
                    $update_item_result =1;
                    echo "<code>";
                    print_R($update_item);
                    echo "</code>";
                }else{
                    $update_item_result = sql_query($update_item);
                }

                /* 상품 승급*/
                if($update_item_result){
                    
                    // IDX 구하기
                    $count_colum_sql = "SELECT count(*) as cnt FROM {$next_rank_target}";
                    $count_colum = sql_fetch($count_colum_sql);
                    $count_colum_cnt = $count_colum['cnt']+1;

                    // nth 구하기
                    $count_item_sql = "SELECT count(*) as cnt FROM {$next_rank_target} where mb_id = '{$mb_id}' ";
                    $count_item = sql_fetch($count_item_sql);
                    $count_item_cnt = $count_item['cnt']+1;

                    // 원 아이템 이름 생성
                    if($it_name == ''){
                        $it_name = $val."_".$mb_id."_".$nth;
                    }
 
                    $insert_item = "INSERT {$next_rank_target} SET 
                    mb_id = '{$mb_id}',
                    it_name = '{$it_name}',
                    idx = {$count_colum_cnt},
                    nth = {$count_item_cnt}, 
                    cdate = '{$cdate}', 
                    cdatetime = '{$cdatetime}', 
                    pdate = '{$bonus_day}',
                    od_id = {$od_id} ";

                    if($debug){
                        $insert_item_result = 1;
                        echo "<code>";
                        print_R($insert_item);
                        echo "</code>";
                    }else{
                        $insert_item_result = sql_query($insert_item);
                    }
                }else{ echo "<span class='red'>업데이트 에러</span>";}

                //상품승급 로그 기록 생성
                if($insert_item_result){
                    echo " <br>▶ 승급처리 : ".$val." >>> ".($val+1);
                    
                    $package_log = "INSERT package_log SET 
                        idx = {$no},
                        mb_id = '{$mb_id}',
                        it_name = '{$it_name}',
                        origin_rank = {$val},
                        change_rank = {$val}+1,
                        l_date = '{$bonus_day}'"; 
                    if($debug){
                        $package_log_result = 1;
                        echo "<code> LOG :: ";
                            print_R($package_log);
                        echo "</code>";
                    }else{
                        $package_log_result = sql_query($package_log);
                    }
                }else{ echo "<span class='red'>승급처리 에러</span>";}


                // 아바타 생성
                if($insert_item_result && $package_log_result){
                    $avata_Num = $avata[$val + 1];
                    echo " <br>▶▶ 아바타 생성 : ".$avata_Num." :: ";
                }else{ echo "<span class='red'>로그기록  에러</span>";}


                // 수당지급 
                if(avata_create($avata_Num,$mb_id,$od_id)){
                    $bonus_val = $bonus_rate[$val];
                    echo " <br>▶▶▶ 수당지급 : <span class='blue'> ".$bonus_val." $ </span>";
                    $bonus_sql = "UPDATE {$g5['member_table']} SET mb_balance = (mb_balance + $bonus_val) WHERE mb_id = '{$mb_id}' ";

                    if($debug){
                        $bonus_result = 1;
                        echo "<code> BONUS :: ";
                            print_R($bonus_sql);
                        echo "</code>";
                    }else{
                        $bonus_result = sql_query($bonus_sql);
                    }

                    //수당지급 내역 생성 
                    $rec ="G".($val+1)." 상품승급";
                    $rec_adm = "G".($val+1)." 상품승급";

                    $soodang_sql = " insert `{$g5['bonus']}` set day='".$bonus_day."'";
                    $soodang_sql .= " ,mb_id			= '".$mb_id."'";
                    $soodang_sql .= " ,allowance_name	= '".$code."'";
                    $soodang_sql .= " ,benefit		=  ".$bonus_val;	
                    $soodang_sql .= " ,rec			= '".$rec."'";
                    $soodang_sql .= " ,rec_adm		= '".$rec_adm."'";
                    $soodang_sql .= " ,datetime		= '".date("Y-m-d H:i:s")."'";


                    // 디버그 로그
                    if($debug){
                        echo "<br><code>";
                        print_R($soodang_sql);
                        echo "</code>";
                    }else{
                        sql_query($soodang_sql);
                    }

                }else{ echo "<span class='red'>아바타 생성 에러</span>";}

                // 코인 수당 지급
                if( $bonus_result ){
                    $bonus_token_val = $bonus_token[$val];
                    echo " <br>▶▶▶▶ 코인 지급 : <span class='blue'> ".$bonus_token_val." KFUL </span>";

                    if($bonus_token_val > 0){
                        $bonus_token_sql = "UPDATE {$g5['member_table']} SET mb_point = (mb_point + $bonus_token_val) WHERE mb_id = '{$mb_id}' ";
                        if($debug){
                            $bonus_token_result = 1;
                            echo "<code> TOKEN :: ";
                                print_R($bonus_token_sql);
                            echo "</code>";
                        }else{
                            $bonus_token_result = sql_query($bonus_token_sql);
                        }
                    }
                }else{ echo "<span class='red'>수당 지급 에러</span>";}
            }
        }
       
    }else{ echo "<span class='red'>승급 대상 없음</span>";}

} // rankup


function avata_create($val,$mb_id,$od_id){
    global $bonus_day,$debug;
    $pack_table = "package_g0";
    
    for($i=0; $i < $val; $i++){

        // IDX 구하기
        $count_colum_sql = "SELECT count(*) as cnt FROM {$pack_table}";
        $count_colum = sql_fetch($count_colum_sql);
        $count_colum_cnt = $count_colum['cnt'];

        // nth 구하기
        $count_item_sql = "SELECT count(*) as cnt FROM {$pack_table} where mb_id = '{$mb_id}' ";
        $count_item = sql_fetch($count_item_sql);
        $count_item_cnt = $count_item['cnt'];
        

        $insert_g0_item = "INSERT {$pack_table} SET 
        mb_id = '{$mb_id}', 
        idx = {$count_colum_cnt},
        nth = {$count_item_cnt}+1, 
        cdate = '{$bonus_day}', 
        
        od_id = '{$od_id}' ";

        echo $mb_id."_".($count_item_cnt+1);
        
        if($debug){
            $insert_g0_item_result = 1;

            echo "<code>";
            echo $insert_g0_item;
            echo "</code>";
        }else{
            $insert_g0_item_result = sql_query($insert_g0_item);
        }

        if($insert_g0_item_result){
            return true;
        }
    }
}
?>

<?include_once('./bonus_footer.php');?>

<?
if($debug){}else{
    $html = ob_get_contents();
    //ob_end_flush();
    $logfile = G5_PATH.'/data/log/'.$code.'/'.$code.'_'.$bonus_day.'.html';
    fopen($logfile, "w");
    file_put_contents($logfile, ob_get_contents());
}
?>