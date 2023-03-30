<?php
$sub_menu = "600600";
include_once('./_common.php');

include_once('./inc.member.class.php');

auth_check($auth[$sub_menu], 'r');


// 직급이 최소 1스타(3) 이상이고 하부에 오늘 매출이 있는 사람만 
$sql = "SELECT mb_id,mb_name FROM g5_member";
$result = sql_query($sql);

$history_cnt=0;
for ($i=0; $row=sql_fetch_array($result); $i++) {   
		

		$comp=$row['mb_id'];
	
		$binary_firstname=$row['mb_name'];
		$binary_firstid=$comp;
		

	//echo $binary_firstname.'('.$comp.')<br>';

		while(  ($comp!='admin')  ){ 
			
			$sql = " SELECT mb_id, mb_name, mb_hp, mb_level, mb_recommend, mb_brecommend FROM g5_member WHERE mb_id= '".$comp."'";
			$recommend = sql_fetch($sql);

				$leg_success=0; // 메트릭스 성공찾기 클리어
				
				if($who=='mb_recommend'){
					$recom=$recommend['mb_recommend'];
				}else{
					$recom=$recommend['mb_brecommend'];
				}

				

				$mbid=$recommend['mb_id'];
				$mbname=$recommend['mb_name'];
				
				$mblevel=$recommend['mb_level'];

					//위로 연속 올라가는 부분 추천인으로 올라갈지 바이너리추천인으로 올라갈지 결정
					

				if(  ($recom=='') && ($recom!='admin')  ) {
				
					echo $history_cnt.'. '.$binary_firstname.'('.$binary_firstid.') 스폰서ID: ('.$recom.')<br>';
					$history_cnt++;
					break;

				}


		

			$comp=$recom;


			



		} // while

		$rec='';
		

} //for

//alert('수당계산이 완료되었습니다');

?>

