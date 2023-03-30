<style>
    body{font-size:14px;line-height:18px;letter-spacing:0px;}
    .red{color:red;font-weight:600;}
    .blue{color:blue;font-weight:600;}
    .title{font-weight:800;color:red;}
</style>

<?php
$sub_menu = "200100";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'r');

$g5['title'] = '멤버 등급 수동 갱신';

$sql = "select * from g5_member";
$result = sql_query($sql);

$member_grade0 = 0;
$member_grade1 = 0;
$member_grade2 = 0;
$member_grade3 = 0;
$member_grade4 = 0;

while( $row = sql_fetch_array($result) ){
	
	if($row['mb_deposit_point'] <= 300){
		$grade = 0;
		$member_grade0 += 1;
	}else if($row['mb_deposit_point'] > 499 && $row['mb_deposit_point'] < 3000){
		$grade = 1;
		$member_grade1 += 1;
	}else if($row['mb_deposit_point'] > 2999 && $row['mb_deposit_point'] < 10000){
		$grade = 2;
		$member_grade2 += 1;
	}else{
		$grade = 3;
		$member_grade3 += 1;
	}

	$grade_sql = "update g5_member set grade = '".$grade."' where mb_id != 'coolrunning' and mb_no ='".$row['mb_no']."'";
	//echo $row['mb_deposit_point']." / ".$grade_sql."<br>";
	sql_query($grade_sql);
}


update_grade();

function update_grade(){
	global $member_grade4;

    echo "<br><span class='title'> 등급 업데이트 - GRADE ====================================== </span><br>";

    $mem_sql = "select mb_id, grade, mb_recommend from g5_member order by mb_no";
    $mem_list = sql_query($mem_sql);

    while($m_row = sql_fetch_array($mem_list)){

        $grade = $m_row['grade'];

        echo "<br>**<br><strong>".$m_row['mb_id']."  |  현재등급 ".$m_row['grade']."</strong><br>";

        //회원의 직 추천인 수를 구한다. 
        $recom_cont = sql_fetch( "select count(mb_id) as r_count from g5_member where  mb_recommend = '".$m_row['mb_id']."' AND grade >= 3 ");
        $recom_cnt=$recom_cont['r_count'];

        if($grade == 3 &&  $recom_cnt >= 3){
            echo "<br> <span class='blue'>▶ 직추천 그린 등급 이상 : ".$recom_cnt."명";
            echo "<br> <span class='red'>▶▶ GREEN2 등급 업데이트 대상</span>";
            $update_grade_sql = "update g5_member set grade = 4 where mb_id = '{$m_row['mb_id']}'";
			sql_query($update_grade_sql);
			$member_grade4 += 1;
        }
    
	}
	
	alert("Black : ".$member_grade0." 명 <br> Red : ".$member_grade1." 명 <br> Yellow : ".$member_grade2." 명 <br> Green : ".$member_grade3." 명 <br> Green2 :".$member_grade4." 명 <br>의 회원 등급이 변경되었습니다." );
	goto_url('./member_list.php?'.$qstr);

}


?>



<?php
include_once('./admin.tail.php');
?>
