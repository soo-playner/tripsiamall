

<?php
include_once(G5_PATH.'/util/recommend.php');
// include_once(G5_LIB_PATH.'/fcm_push/push.php');


/* 상품 팩 구매 프로세스 */
function purchase_package($mb_id,$pack_id,$return = 0){

    global $g5,$debug,$now_date,$now_datetime,$orderid;

    /* 주문 상품 기록 생성 */
    $pack_sql = "SELECT * FROM g5_shop_item WHERE it_id = {$pack_id} ";
    $pack_result = sql_fetch($pack_sql);

    $pack_purpose = $pack_result['it_maker'];
    $pack_target = explode('+',$pack_purpose);

    if(count($pack_target) > 0){

        for($i=0; $i < count($pack_target); $i++){

            $pack_table = "package_".strtolower(trim($pack_target[$i]));
            $pack_table_code = substr(trim($pack_target[$i]),1,1);

            $count_colum_sql = "SELECT count(*) as cnt FROM {$pack_table}";
            $count_colum = sql_fetch($count_colum_sql);
            $count_colum_cnt = $count_colum['cnt']+1;

            $count_item_sql = "SELECT count(*) as cnt FROM {$pack_table} where mb_id = '{$mb_id}' ";
            $count_item = sql_fetch($count_item_sql);
            $count_item_cnt = $count_item['cnt']+1;
            
            $it_name = $pack_table_code."_".$mb_id."_".$count_item_cnt;

            $pack_result_sql = "INSERT {$pack_table} SET mb_id = '{$mb_id}', idx= {$count_colum_cnt},it_name='{$it_name}', nth = {$count_item_cnt}, pdate='0000-00-00', cdate = '{$now_date}', cdatetime = '{$now_datetime}', od_id = {$orderid} ";
            
            if($debug){
                $pack_insert = $pack_result_sql;
                echo "<span class=패키지 상품 생성<br>";
                echo $pack_insert."<br><br>";
            }else{
                $pack_insert = sql_query($pack_result_sql);
            }

            if($pack_insert){
                $result = true;
                $result_data = '패키지구매';

                // 추천수당
                // if($pack_id == '2020120890'){
                //     $result = direct_bonus($mb_id);
                //     $result_data .= ', 직추천수당';

                //     if($result){
                //         $result = recommend_sponsor_bonus($mb_id);
                //         $result_data .= ', 추천매칭수당';
                //     }
                // }
                
                // 승급처리
                // $proc_done = process();
                $result_data .= $proc_done = 1;

            }
        }

        if($result && $proc_done && $return == 0  ){
            echo "<br>처리완료<br><br>";
            return true;
        }else if($result && $proc_done && $return == 1){
            ob_end_clean();
            echo json_encode(array("response"=>"ok","code" => '0000', "data"=>$result_data),JSON_UNESCAPED_UNICODE);
        }else{
            echo "<br><br>처리에러<br><br>";
            ob_end_clean();
            echo json_encode(array("response"=>"failed","code" => '0001', "data"=>$result_data),JSON_UNESCAPED_UNICODE);
        }
    }
}



function direct_bonus($mb_id){
    global $debug;
    if($debug){echo "<br><br><span class='blue'>직추천수당</span><br>";}

    // 직추천 수당
    $code = "direct";
    $bonus_row = bonus_pick($code);
    $bonus_limit = $bonus_row['limited']/100;
    $bonus_rate = $bonus_row['rate'];

    $recommender_sql = "SELECT mb_recommend from g5_member WHERE mb_id = '{$mb_id}' ";
    $recommender = sql_fetch($recommender_sql)['mb_recommend'];

    $update_sql = "UPDATE g5_member set mb_balance = mb_balance + {$bonus_rate} WHERE mb_id = '{$recommender}' ";
    $rec = 'direct recommned from '.$mb_id;
    $rec_adm = '';

    if($debug){
        echo $recommender;
        echo " / ";
        echo $bonus_rate;
        echo "<br>";
        echo $update_sql;
        $result = 1;
    }else{
        $result = sql_query($update_sql);
    }

    $record = soodang_record($recommender, $code, $bonus_rate,$rec,$rec_adm);
    
    return true;
    
}

function recommend_sponsor_bonus($mb_id){
    global $debug;

    // 추천매칭수당
    if($debug){echo "<br><br><span class='blue'>추천매칭수당</span>";}
    $code = "sponsor";
    $bonus_row = bonus_pick($code);
    $bonus_limit = $bonus_row['limited']/100;
    
    $bonus_rate = explode(',',$bonus_row['rate']);
    $cnt = count($bonus_rate);
    $rec = 'recommned matching from '.$mb_id;

    for($i=0; $i < $cnt; $i++){
        $j = $i+1;
        $rec_adm = '추천매칭'.$mb_id.' - '.$j.'대';
        $recommender[$i] = return_up_manager($mb_id,$j+1);
        $update_sql[$i] = "UPDATE g5_member set mb_balance = mb_balance + {$bonus_rate[$i]} WHERE mb_id = '{$recommender[$i]}' ";


        if($debug){
            echo "<br>".$j."대 : ".$recommender[$i].' | '.$bonus_rate[$i];
            echo "<br>".$update_sql[$i];
            echo "<br>";
        }else{
            $result = sql_query($update_sql[$i]);
        }
        $record = soodang_record($recommender[$i], $code, $bonus_rate[$i],$rec,$rec_adm);
    }

   
    return true;
    
}


function soodang_record($mb_id, $code, $bonus_val,$rec,$rec_adm){
    global $g5,$debug,$now_date,$now_datetime;

    $soodang_sql = " insert `{$g5['bonus']}` set day='".$now_date."'";
    $soodang_sql .= " ,mb_id			= '".$mb_id."'";
    $soodang_sql .= " ,allowance_name	= '".$code."'";
    $soodang_sql .= " ,benefit		=  ".$bonus_val;	
    $soodang_sql .= " ,rec			= '".$rec."'";
    $soodang_sql .= " ,rec_adm		= '".$rec_adm."'";
    $soodang_sql .= " ,datetime		= '".$now_datetime."'";

    // 수당 푸시 메시지 설정
    $mb_push_data = sql_fetch("SELECT fcm_token,mb_sms from g5_member WHERE mb_id = '{$mb_id}' ");
    $push_agree = $mb_push_data['mb_sms'];
    $push_token = $mb_push_data['fcm_token'];

    $push_images = G5_URL.'/img/marker.png';
    if($push_token != '' && $push_agree == 1){
        setPushData("[DFINE] - ".$mb_id." 수당 지급 ", $code.' =  +'.$bonus_val.' ETH', $push_token,$push_images);
    }
    
    if($debug){
        echo "<code>";
        print_r($soodang_sql);
        echo "</code>";
        return true;
    }else{
        return sql_query($soodang_sql);
    }
}




function bonus_pick($val){    
    global $g5;
    $pick_sql = "select * from {$g5['bonus_config']} where code = '{$val}' ";
    $list = sql_fetch($pick_sql);
    return $list;
}


function bonus_condition_tx($bonus_condition){
    if($bonus_condition == 1){
        $bonus_condition_tx = '추천 계보';
    }else if($bonus_condition == 2){
        $bonus_condition_tx = '후원(바이너리) 계보';
    }else{
        $bonus_condition_tx='';
    }
    return $bonus_condition_tx;
}

function bonus_layer_tx($bonus_layer){
    if($bonus_layer == ''){
        $bonus_layer_tx = '전체지급';
    }else{
        $bonus_layer_tx = $bonus_layer.'단계까지 지급';
    }
    return $bonus_layer_tx;
}


function process(){
    global $debug;

    if($debug){
        print_R("<br><div class='box'><span class='blue'>승급 프로세스</span>");
    }

    for($z=0; $z < 4; $z++){
        $proc_key += rankup($z);
    }
    if($debug){
        echo "</div>";
    }

    if($proc_key > 2){
        return '승급수당, 승급추천매칭수당';
    }
    if($proc_key > 100){
        return '승급수당, 승급추천매칭수당, 졸업';
    }
    
}

function rankup($val, $mb_id,$orderid){

    global $g5, $debug, $now_date,$code;

    $rank_target = "package_m".$val;
    $next_rank_target = "package_m".($val+1);
    
    // $rank_cnt_sql = "SELECT * FROM {$rank_target}";
    // $rank_cnt_result = sql_query($rank_cnt_sql);
    // $rank_total_cnt = sql_num_rows($rank_cnt_result);

    // if($val == 0){
    //     $rank_dumy_sql = " SELECT count(*) as cnt FROM package_r ";
    //     $rank_dumy_result = sql_fetch($rank_dumy_sql);
    //     $rank_dumy_count = $rank_dumy_result['cnt'];
    //     $rank_total_cnt = $rank_total_cnt + $rank_dumy_count;
    // }
    // $ranked_sql = "SELECT * FROM {$rank_target} where promote = 1 ";
    // $ranked_result = sql_query($ranked_sql);
    // $ranked_cnt = sql_num_rows($ranked_result);

    // $promote_num = floor( (($rank_total_cnt-1)-$ranked_cnt*3)/3);
    // echo "Promote Num::".$promote_num;

    // $rank_sql = "SELECT * FROM {$rank_target} where promote = 0 ";
    // $rank_result = sql_query($rank_sql);
    // $rank_cnt = sql_num_rows($rank_result);

    // if($debug){
    //     echo "<br><br><div class='block'>";
    //     echo "<strong>R{$val}</strong> :: ";
    //     echo "R 합계 : <strong>".$rank_total_cnt."</strong>";
    //     if($val == 0){echo "(dummy:".$rank_dumy_count.")";}
    //     echo "</span> / ";
    //     echo "R승급 소모 :<span class='red'>-".$ranked_cnt."(".($ranked_cnt*3).")</span> / ";
    //     echo "R상품 : <span>".$rank_cnt."</span>개 / ";
    //     echo "승급 가능 : <span class='red'>".$promote_num."</span>개 </div><br>";
    // }
    

    // if($promote_num > 0)
    // {
    //     for($i=0;$row=sql_fetch_array($rank_result); $i++) {
            
            // if($i < $promote_num){
            $row = sql_fetch("SELECT * FROM {$rank_target} WHERE mb_id = '{$mb_id}' AND od_id='{$orderid}'");

                $no = $row['no'];
                $idx = $row['idx'];
                $target_id = $row['mb_id'];
                $nth = $row['nth'];
                $it_name = $row['it_name'];
                $cdate = $row['cdate'];
                $cdatetime = $row['cdatetime'];
                $od_id = $row['od_id'];
                
                if($val+1 < 7){$uprank = $val+1;}else{$uprank = 'ERROR';}

                /* 승급 기록*/
                echo "<br><span class='title'>".$no." / ".$target_id.'_'.$nth.".$orderid.";
                echo " -  <span class='red'>M".($uprank)." 상품승급</span>";
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
                    
                    if($val < 6){
                        
                        // IDX 구하기
                        $count_colum_sql = "SELECT count(*) as cnt FROM {$next_rank_target}";
                        $count_colum = sql_fetch($count_colum_sql);
                        $count_colum_cnt = $count_colum['cnt']+1;

                        // nth 구하기
                        $count_item_sql = "SELECT count(*) as cnt FROM {$next_rank_target} where mb_id = '{$target_id}' ";
                        $count_item = sql_fetch($count_item_sql);
                        $count_item_cnt = $count_item['cnt']+1;

                        // 원 아이템 이름 생성
                        if($it_name == ''){
                            $it_name = $val."_".$target_id."_".$nth;
                        }
                        
                        $insert_item = "INSERT {$next_rank_target} SET 
                        mb_id = '{$target_id}',
                        it_name = '{$it_name}',
                        idx = {$count_colum_cnt},
                        nth = {$count_item_cnt}, 
                        cdate = '{$cdate}', 
                        cdatetime = '{$cdatetime}', 
                        pdate = '{$now_date}',
                        od_id = {$od_id} ";

                        if($debug){
                            $insert_item_result = 1;
                            echo "<code>";
                            print_R($insert_item);
                            echo "</code>";
                        }else{
                            $insert_item_result = sql_query($insert_item);
                        }
                        
                    }
                    // else{
                    //     // 졸업 - 아바타, 기부코드 생성
                    //     $avata_Num = 1;
                    //     $angel_Num = 2;

                    //     echo " <br>▶▶ 졸업대상 ";
                    //     echo " <br>▶▶▶ 아바타 생성 : ".$avata_Num." :: ";
                    //         $avata_result = avata_create($avata_Num,$target_id,$od_id);
                            
                    //     echo " <br>▶▶▶▶ 기부코드 생성 : ".$angel_Num." :: ";
                    //         $angelcode_result = angelcode_create($angel_Num,$target_id,$od_id);
                        
                    //     if($avata_result && $angelcode_result){
                    //         $insert_item_result = 1;
                    //     }

                    // }
                }else{ echo "<span class='red'>업데이트 에러</span>";}


                //상품승급 로그 기록 생성
                if($insert_item_result){
                    if($debug){
                        echo " <br>▶ 승급처리 : ".$val." >>> ".($uprank);
                    }
                    $package_log = "INSERT package_log SET 
                        idx = {$no},
                        mb_id = '{$target_id}',
                        it_name = '{$it_name}',
                        origin_rank = {$val},
                        change_rank = '{$uprank}',
                        l_date = '{$now_date}'"; 
                        
                    if($debug){
                        $package_log_result = 1;
                        echo "<code> LOG :: ";
                            print_R($package_log);
                        echo "</code>";
                    }else{
                        $package_log_result = sql_query($package_log);
                    }
                }else{ echo "<span class='red'>승급처리 에러</span>";}

                // 졸업이 아닌경우 승급수당 지급
                // if($val < 4){

                //     // 승급 수당 지급
                //     if($package_log_result){
                //         $rankup_bonus = rankup_bonus($target_id,$val,$uprank);
                //     }else{ echo "<span class='red'>승급 기록 에러</span>";return false;}

                //     // 승급 추천 수당 지급
                //     if($rankup_bonus){
                //         $rankup_matching_bonus = rankup_recommend_bonus($target_id,$val,$uprank);
                //         $result_key = 2;
                //     }else{ echo "<span class='red'>승급 수당 에러</span>";return false;}

                // }else{
                //     $result_key = 100;
                // }

            // }
        // }
        
    // }else{ echo "<span class='red'>승급 대상 없음</span>"; $result_key = 1;}

    return $package_log_result;

} // rankup

// 승급수당
function rankup_bonus($target_id,$val,$uprank){
    global $g5,$debug;

    $code = 'rankup';
    $bonus_row = bonus_pick($code);
    $bonus_rate = explode(',',$bonus_row['rate']);
    
    $bonus_val = $bonus_rate[$val];
    $bonus_sql = "UPDATE {$g5['member_table']} SET mb_balance = (mb_balance + $bonus_val) WHERE mb_id = '{$target_id}' ";
    
    
    if($debug){
        echo " <br>▶▶▶ 승급 수당 지급 : <span class='blue'> ".$bonus_val." ETH </span> >> ".$target_id;
        echo "<code> BONUS :: ";
            print_R($bonus_sql);
        echo "</code>";
        $rankup_bonus_result = 1;
    }else{
        $rankup_bonus_result = sql_query($bonus_sql);
    }

    // 승급 수당지급 내역 생성 
    if($rankup_bonus_result){
        $rec ="R".($uprank)." 상품승급";
        $rec_adm = "R".($uprank)." 상품승급";
        $rankup_record = soodang_record($target_id, $code, $bonus_val,$rec,$rec_adm);

        if($rankup_record) return true;
    }

}

// 승급 추천 수당
function rankup_recommend_bonus($target_id,$val,$uprank){
    global $g5,$debug;

    $code = 'rankmatching';
    $bonus_row = bonus_pick($code);
    $bonus_rate = explode(',',$bonus_row['rate']);
    $sponsor_id = return_up_manager($target_id,1);

    $bonus_val = $bonus_rate[$val];
    $bonus_sql = "UPDATE {$g5['member_table']} SET mb_balance = (mb_balance + $bonus_val) WHERE mb_id = '{$sponsor_id}' ";

    if($debug){
        $bonus_result = 1;
        echo " <br>▶▶▶ 승급 추천 매칭 수당 지급 : <span class='blue'> ".$bonus_val." ETH </span> >> ".$sponsor_id;
        echo "<code> BONUS :: ";
            print_R($bonus_sql);
        echo "</code>";
        $rankup_matching = 1;
    }else{
        $rankup_matching = sql_query($bonus_sql);
    }

    if($rankup_matching){
        $rec ="R".($uprank)."승급 추천 매칭";
        $rec_adm = "R".($uprank)." 승급 추천 매칭 -".$target_id;
        $rankup_record = soodang_record($sponsor_id, $code, $bonus_val,$rec,$rec_adm);

        if($rankup_record) return true;
    }
    
}

function avata_create($val,$mb_id,$od_id){
    global $now_date, $now_datetime, $debug;
    $pack_table = "package_r0";
    
    for($i=0; $i < $val; $i++){

        // IDX 구하기
        $count_colum_sql = "SELECT count(*) as cnt FROM {$pack_table}";
        $count_colum = sql_fetch($count_colum_sql);
        $count_colum_cnt = $count_colum['cnt'];

        // nth 구하기
        $count_item_sql = "SELECT count(*) as cnt FROM {$pack_table} where mb_id = '{$mb_id}' ";
        $count_item = sql_fetch($count_item_sql);
        $count_item_cnt = $count_item['cnt'];
        
        $avata_name = "f_".$mb_id."_".($count_item_cnt+1);

        if($debug){echo $avata_name;}

        $insert_r0_item = "INSERT {$pack_table} SET 
        mb_id = '{$mb_id}',
        it_name = '{$avata_name}' ,
        idx = {$count_colum_cnt},
        nth = {$count_item_cnt}+1, 
        cdate = '{$now_date}', 
        cdatetime = '{$now_datetime}',
        od_id = '{$od_id}' ";
        
        if($debug){
            $insert_r0_item_result = 1;
            echo "<code>";
            echo $insert_r0_item;
            echo "</code>";
        }else{
            $insert_r0_item_result = sql_query($insert_r0_item);
        }
    }
    if($insert_r0_item_result){
        return true;
    }
}


function angelcode_create($val,$mb_id,$od_id){
    global $now_date, $now_datetime, $debug;
    $pack_table = "package_r";
    
    for($i=0; $i < $val; $i++){

        // IDX 구하기
        $count_colum_sql = "SELECT count(*) as cnt FROM {$pack_table}";
        $count_colum = sql_fetch($count_colum_sql);
        $count_colum_cnt = $count_colum['cnt'];

        // nth 구하기
        $count_item_sql = "SELECT count(*) as cnt FROM {$pack_table} where mb_id = '{$mb_id}' ";
        $count_item = sql_fetch($count_item_sql);
        $count_item_cnt = $count_item['cnt'];
        
        $angel_name = $mb_id."_".($count_item_cnt+1);

        if($debug){echo $angel_name;}

        $insert_r_item = "INSERT {$pack_table} SET 
        mb_id = '{$mb_id}',
        it_name = '{$angel_name}' ,
        idx = {$count_colum_cnt},
        nth = {$count_item_cnt}+1, 
        cdate = '{$now_date}', 
        cdatetime = '{$now_datetime}',
        od_id = '{$od_id}' ";
        
        if($debug){
            $insert_r_item_result = 1;
            echo "<code>";
            echo $insert_r_item;
            echo "</code>";
        }else{
            $insert_r_item_result = sql_query($insert_r_item);
        }
    }

    if($insert_r_item_result){
        return true;
    }
}
?>

