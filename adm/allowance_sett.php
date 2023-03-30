<?php
$sub_menu = "600200";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'r');



// ************************

$token = get_token();

if ($stx) {
    $search3= " and  ";
  
            $search3 .= " ({$sfl} = '{$stx}') ";
          
    $search3 .= "  ";

	$cond.=" | ({$sfl} = '{$stx}') ";
}




/*
$total_count=mysql_num_rows($result);

$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함


$sql_order = " order by {$sst} {$sod} ";
*/

$sql_common = " FROM pinna_soodang_set";

$sql = "select *
            {$sql_common}	order by idx, allowance_name ";
//limit {$from_record}, {$rows} ";
$result = sql_query($sql);


$qstr.='&fr_date='.$fr_date.'&to_date='.$to_date.'&starter='.$starter.'&partner='.$partner.'&team='.$team.'&bonbu='.$bonbu.'&chongpan='.$chongpan;

$listall = '<a href="'.$_SERVER['PHP_SELF'].'" class="ov_listall">전체목록</a>';


$g5['title'] = '수당지급조건 설정(요청된 마케팅플랜의 범위만큼 작동됨)';
include_once ('./admin.head.php');

/*
if (strstr($sfl, "mb_id"))
    $mb_id = $stx;
else
    $mb_id = "";
    */
?>





<form name="allowance" id="allowance" method="post" action="./allowance_update.php">


<div class="tbl_head02 tbl_wrap">
    <table>
    <caption><?php echo $g5['title']; ?> 목록</caption>
    <thead>
    <tr>
        <th scope="col"  id="mb_list_chk">
           적용기준선택
			<select name="price_kind" id="price_kind" class="frm_input">
				<!--<option value="">없음</option>-->
				<option value="PV" <? if(($price_kind=='PV') ) echo "selected"; ?>>PV</option>
				<option value="position" <? if(($price_kind=='position') ) ?>>승급조건</option>
				<!--<option value="BV" <? if($price_kind=='BV') echo "selected"; ?>BV</option>
				<option value="price" <? if($price_kind=='price') echo "selected"; ?>>판매가</option>-->
			</select>
			<br/>
			 계산필드
			<select name="recom_kind" id="recom_kind" class="frm_input">
				<option value="mb_recommend" <? if(($recom_kind=='mb_recommend') ) echo "selected"; ?>>추천인(mb_recommend)</option>
				<option value="mb_brecommend" <? if(($recom_kind=='mb_brecommend') ) ?>>바이너리(mb_Brecommend)</option>
			
			</select>

			

        </th>
        <th scope="col" colspan="4" id="mb_list_id">
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;실적조건<input type="text" name="source_in1" value="<?=$source_in1?>" id="source_in1" class="frm_input" size=11>

				<label for="sfl" class="sound_only">조건</label>
				<select name="source_cond1" id="source_cond1" class="frm_input">
 					<option value="">없음</option>
					<option value="=="<? if($source_cond1=='==') echo "selected"; ?> >==</option>
					<option value=">="<? if($source_cond1=='>=') echo "selected"; ?> >>=</option>
				
				</select>

				<select name="source" id="source" class="frm_input">
 				    <option value="">없음</option>
 					<option value="누적 대실적"<? if($source=='누적 대실적') echo "selected";?> >누적 대실적</option>
					<option value="누적 소실적"<? if($source=='누적 소실적') echo "selected";?> >누적 소실적</option>
					<option value="누적 하부실적"<? if($source=='누적 하부실적') echo "selected";?> >누적 하부실적</option>
					<option value="누적 자기실적"<? if($source=='누적 자기실적') echo "selected";?> >누적 자기실적</option>				
					<option value="누적 하부실적+자기실적"<? if($source=='누적 하부실적+자기실적') echo "selected";?> >누적 하부실적+자기실적</option>
					<option value="">-----------------------</option>
 					<option value="금월 대실적"<? if($source=='금월 대실적') echo "selected";?> >금월 대실적</option>
					<option value="금월 소실적"<? if($source=='금월 소실적') echo "selected";?> >금월 소실적</option>
					<option value="금월 하부실적"<? if($source=='금월 하부실적') echo "selected";?> >금월 하부실적</option>
					<option value="금월 자기실적"<? if($source=='금월 자기실적') echo "selected";?> >금월 자기실적</option>
					<option value="금월 하부실적+자기실적"<? if($source=='금월 하부실적+자기실적') echo "selected";?> >금월 하부실적+자기실적</option>
					<option value="">-----------------------</option>
 					<option value="금일 대실적"<? if($source=='금일 대실적') echo "selected";?> >금일 대실적</option>
					<option value="금일 소실적"<? if($source=='금일 소실적') echo "selected";?> >금일 소실적</option>
					<option value="금일 하부실적"<? if($source=='금일 하부실적') echo "selected";?> >금일 하부실적</option>
					<option value="금일 자기실적"<? if($source=='금일 자기실적') echo "selected";?> >금일 자기실적</option>
					<option value="금일 하부실적+자기실적"<? if($source=='금일 하부실적+자기실적') echo "selected";?> >금일 하부실적+자기실적</option>
					<option value="금일 하부실적+자기실적"<? if($source=='금일 하부실적+자기실적') echo "selected";?> >금일 하부실적+자기실적</option>

				</select>
				<select name="source_cond2" id="source_cond2" class="frm_input">
 					<option value="">없음</option>
					<option value="<="<? if($source_cond12=='<=') echo "selected"; ?> ><=</option>
				
				</select>

				<input type="text" name="source_in2" value="<?=$source_in2?>" id="source_in2" class="frm_input" size=11>

				<br/>

				AND 실적조건<input type="text" name="source_in11" value="<?=$source_in11?>" id="source_in11" class="frm_input" size=11>

				<label for="sfl" class="sound_only">조건</label>
				<select name="source_cond11" id="source_cond11" class="frm_input">
 					<option value="">없음</option>
					<option value="=="<? if($source_cond11=='==') echo "selected"; ?> >==</option>
					<option value=">="<? if($source_cond11=='>=') echo "selected"; ?> >>=</option>
				
				</select>

				<select name="source11" id="source11" class="frm_input">
 				    <option value="">없음</option>
 					<option value="누적 대실적"<? if($source11=='누적 대실적') echo "selected";?> >누적 대실적</option>
					<option value="누적 소실적"<? if($source11=='누적 소실적') echo "selected";?> >누적 소실적</option>
					<option value="누적 하부실적"<? if($source11=='누적 하부실적') echo "selected";?> >누적 하부실적</option>
					<option value="누적 자기실적"<? if($source11=='누적 자기실적') echo "selected";?> >누적 자기실적</option>				
					<option value="누적 하부실적+자기실적"<? if($source11=='누적 하부실적+자기실적') echo "selected";?> >누적 하부실적+자기실적</option>
					<option value="">-----------------------</option>
 					<option value="금월 대실적"<? if($source11=='금월 대실적') echo "selected";?> >금월 대실적</option>
					<option value="금월 소실적"<? if($source11=='금월 소실적') echo "selected";?> >금월 소실적</option>
					<option value="금월 하부실적"<? if($source11=='금월 하부실적') echo "selected";?> >금월 하부실적</option>
					<option value="금월 자기실적"<? if($source11=='금월 자기실적') echo "selected";?> >금월 자기실적</option>
					<option value="금월 하부실적+자기실적"<? if($source11=='금월 하부실적+자기실적') echo "selected";?> >금월 하부실적+자기실적
					<option value="">-----------------------</option>
 					<option value="금일 대실적"<? if($source11=='금일 대실적') echo "selected";?> >금일 대실적</option>
					<option value="금일 소실적"<? if($source11=='금일 소실적') echo "selected";?> >금일 소실적</option>
					<option value="금일 하부실적"<? if($source11=='금일 하부실적') echo "selected";?> >금일 하부실적</option>
					<option value="금일 자기실적"<? if($source11=='금일 자기실적') echo "selected";?> >금일 자기실적</option>
					<option value="금일 하부실적+자기실적"<? if($source11=='금일 하부실적+자기실적') echo "selected";?> >금일 하부실적+자기실적</option></option>
				</select>



				<label for="sfl" class="sound_only">조건2</label>
				<select name="source_cond12" id="source_cond12" class="frm_input">
					<option value="" >없음</option>
					<option value="<=" <? if($source_cond12=='<=') echo "selected"; ?>><=</option>
				</select>

				<input type="text" name="source_in12" value="<?=$source_in2?>" id="source_in12" class="frm_input" size=10>	
				
				
		</th>


		<th scope="col" id="">

			본인 직급조건
   			
			  <?php echo get_member_level_select("mb_level_in1",  0, $member['mb_level'], $mb_level_in1) ?>

				<label for="sfl" class="sound_only">조건</label>
				<select name="mb_level_cond1" id="mb_level_cond1" class="frm_input">
					<option value="">없음</option>
					<option value="==" <? if($mb_level_cond1=='==') echo "selected"; ?>>==</option>
					<option value=">=" <? if($mb_level_cond1=='>=') echo "selected"; ?>>>=</option>
					
				</select>
				
				<?php echo get_member_level_select("mb_level_in2",  0 , $member['mb_level'], $mb_level_in2) ?>

				<label for="sfl" class="sound_only">조건</label>
				<select name="mb_level_cond2" id="mb_level_cond2" class="frm_input">
					<option value="">없음</option>
					
					<option value="<=" <? if($mb_level_cond2=='<=') echo "selected"; ?>><=</option>
				</select>

			<br/>하부 직급조건
   			
			  <?php echo get_member_level_select("mb_level_in11",  0, $member['mb_level'], $mb_level_in11) ?>

				<label for="sfl" class="sound_only">조건</label>
				<select name="mb_level_cond11" id="mb_level_cond11" class="frm_input">
					<option value="">없음</option>
					<option value="Private" <? if($mb_level_cond11=='0') echo "selected"; ?>>Private</option>
					<option value="Cadet" <? if($mb_level_cond11=='1') echo "selected"; ?>>Cadet</option>
				</select>
				인원수<input type="text" name="mb_level_cond12" value="<?=$mb_level_cond12?>" id="mb_level_cond12" class="frm_input" size=5>
				

			  
		</th>
	</tr>
	<tr>
		<th scope="col" rowspan="2" id="">

			세트조건<br>
   			  레그 수<input type="text" name="partner_cnt" value="<?=$partner_cnt?>" id="partner_cnt" class="frm_input" size=5>
			  단계<input type="text" name="partner_cont" value="<?=$partner_cont?>"  id="partner_cont" class="frm_input" size=5>

		</th>


		<th scope="col" rowspan="2" id="">
			
			대수 조건<br>
   			  <input type="text" name="history_in1" value="<?=$history_in1?>" id="history_in1" class="frm_input" size=5>

				<label for="sfl" class="sound_only">조건</label>
				<select name="history_cond1" id="history_cond1" class="frm_input" >
					<option value="">없음</option>
					<option value="=="<? if($history_cond1=='==') echo "selected"; ?>>==</option>
					<option value=">="<? if($history_cond1=='>=') echo "selected"; ?>>>=</option>
					
				</select>
				
				기준대수

				
				<select name="history_cond2" id="history_cond2" class="frm_input">
					<option value="">없음</option>
					
					<option value="<="<? if($history_cond2=='<=') echo "selected"; ?>><=</option>
				</select>

				<input type="text" name="history_in2" value="<?=$history_in2?>" id="history_in2" class="frm_input" size=5>

		</th>


		<th rowspan="3" colspan="3" id="">
		계산할 수당

				<select name="base_source" id="base_source" class="frm_input">
 				    <option value="">없음</option>
 					<option value="누적 대실적"<? if($base_source=='누적 대실적') echo "selected";?> >누적 대실적</option>
					<option value="누적 소실적"<? if($base_source=='누적 소실적') echo "selected";?> >누적 소실적</option>
					<option value="누적 하부실적"<? if($base_source=='누적 하부실적') echo "selected";?> >누적 하부실적</option>
					<option value="누적 자기실적"<? if($base_source=='누적 자기실적') echo "selected";?> >누적 자기실적</option>		
					<option value="누적 하부실적+자기실적"<? if($base_source=='누적 하부실적+자기실적') echo "selected";?> >누적 하부실적+자기실적</option>
					<option value="">-----------------------</option>
 					<option value="금월 대실적"<? if($base_source=='금월 대실적') echo "selected";?> >금월 대실적</option>
					<option value="금월 소실적"<? if($base_source=='금월 소실적') echo "selected";?> >금월 소실적</option>
					<option value="금월 하부실적"<? if($base_source=='금월 하부실적') echo "selected";?> >금월 하부실적</option>
					<option value="금월 자기실적"<? if($base_source=='금월 자기실적') echo "selected";?> >금월 자기실적</option>
					<option value="금월 하부실적+자기실적"<? if($base_source=='금월 하부실적+자기실적') echo "selected";?> >금월 하부실적+자기실적</option>
					<option value="">-----------------------</option>
 					<option value="금일 대실적"<? if($base_source=='금일 대실적') echo "selected";?> >금일 대실적</option>
					<option value="금일 소실적"<? if($base_source=='금일 소실적') echo "selected";?> >금일 소실적</option>
					<option value="금일 하부실적"<? if($base_source=='금일 하부실적') echo "selected";?> >금일 하부실적</option>
					<option value="금일 자기실적"<? if($base_source=='금일 자기실적') echo "selected";?> >금일 자기실적</option>
					<option value="금일 하부실적+자기실적"<? if($base_source=='금일 하부실적+자기실적') echo "selected";?> >금일 하부실적+자기실적</option>
					<option value="">-----------------------</option>
					<option value="추천수당"<? if($base_source=='추천수당') echo "selected";?> >추천수당</option>
					<option value="">-----------------------</option>
					<option value="Cycle"<? if($base_source=='Cycle') echo "selected";?> >per Cycle</option>
					<option value="바이너리소실적"<? if($base_source=='바이너리소실적') echo "selected";?> >바이너리소실적</option>
				</select>

				극점매출<input type="text" name="sales_reset" value="<?=$sales_reset?>" id="sales_reset" class="frm_input" size=11>
				

				<br/>극점시 잔여매출<select name="max_reset1" id="max_reset1" class="frm_input">
					<option value="" >없음</option>
					<option value="대.소실적 모두이월"<? if($max_reset1=='대.소실적 모두이월') echo "selected";?>>대.소실적 모두이월</option>
					<option value="대실적만 이월" <? if($max_reset1=='대실적만 이월') echo "selected";?>>대실적만 이월</option>
					<option value="소실적만 이월" <? if($max_reset1=='소실적만 이월') echo "selected";?>>소실적만 이월</option>
				</select>
				극점이하시<select name="max_reset2" id="max_reset2" class="frm_input">
					<option value="" >없음</option>
					<option value="대.소실적 모두이월" <? if($max_reset2=='대.소실적 모두이월') echo "selected";?>>대.소실적 모두이월</option>
					<option value="대실적만 이월"<? if($max_reset2=='대실적만 이월') echo "selected";?>>대실적만 이월</option>
					<option value="소실적만 이월"<? if($max_reset2=='소실적만 이월') echo "selected";?>>소실적만 이월</option>
				</select>
				<br/>1Cycle금액<input type="text" name="cycle" value="<?=$cycle?>" id="cycle" class="frm_input" size=11>실적 이월함&nbsp;<input type="checkbox" name="iwolyn"  id="iwolyn" <? if($iwolyn=='1'){ echo "checked value='1'"; } ?> ><br/>피추천인(Chain) 매출보다 크거나 혹은 같아야 함 &nbsp;<input type="checkbox" name="benefit_limit1"  id="benefit_limit1" <? if($benefit_limit1=='1'){ echo "checked value='1'"; } ?>   >
				

		</th>

		
	

		<th scope="col" id="">
			
			
			지급율<input type="text" name="per"  id="per" value="<?=$per?>" class="frm_input"  size=5>%
			계산시점
				<select name="immediate" id="immediate" class="frm_input">
					<option value="1" <? if($immediate=='1') echo "selected";?>>1. 즉시</option>
					<option value="2" <? if($immediate=='2') echo "selected";?>>2. 바이너리보너스 발생시</option>
				</select>
				
		
		</tD>


	</tr>
	<tr>
		<td scope="col" colspan="1" id="">
			<br/>&nbsp;&nbsp;&nbsp;Save Lable 
  			 <input type=button onClick="ccc()"  value=" Clear " class="frm_input">&nbsp;&nbsp;<input type="text" name="allowance_name"  id="allowance_name" value="<?=$allowance_name?>" class="frm_input" >&nbsp;
				<input type="submit" name="act_button" value="Save Plan" class="frm_input" > 
				<input type="hidden" name="edit_no"  id="edit_no" value="<?=$no?>" size =2 class="frm_input" >
			
		</tD>

	</tr>
		<tr>
		<tH  colspan="2">
			
				수당분할지급 &nbsp;<input type="text" name="andor"  id="andor" size ="50" value="<? if($andor){echo $andor; }else{ echo '0'; } ?>"class="frm_input" >

			
		</tD>
		<td  colspan="3">
	
				&nbsp;&nbsp;&nbsp;Delete LabelID &nbsp;<input type="text" name="no"  id="no" size =2 class="frm_input" >
				&nbsp;&nbsp;<input type="submit" name="act_button" value="Delete Plan" class="frm_input"> 
			
		</tD>

	</tr>
	</table>

	
</div>





<input type="hidden" name="sst" value="<?php echo $sst ?>">

<input type="hidden" name="sod" value="<?php echo $sod ?>">
<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
<input type="hidden" name="stx" value="<?php echo $stx ?>">
<input type="hidden" name="page" value="<?php echo $page ?>">
<input type="hidden" name="token" value="<?php echo $token ?>">

<div class="tbl_head01 tbl_wrap">
    <table>
    <caption><?php echo $g5['title']; ?> 목록</caption>
    <thead>
    <tr>
      
        <th scope="col" >No</th>
        
		<!--<th scope="col" rowspan="2">생성일자</th>-->
        <th scope="col" width="250">수당 이름</a></th>	  
	<!--<th scope="col" rowspan="2">적용시점</th>-->
		
		<th scope="col">계산시점</th>
		<th scope="col" colspan="5">실적조건1</th>
		<th scope="col" colspan="5">실적조건2</th>
        <th scope="col" colspan="4">본인직급</th>
		<th scope="col" colspan="3">하부직급</th>
        <th scope="col" colspan="2">메티럭스조건</th>
		
		<th scope="col" colspan="4">대수조건</th> 
		
		<th scope="col">극점도달</th>   
		  

		<th scope="col" >계산할 수당</th>
		<th scope="col"> % </th>
		<th scope="col" rowspan="2">이월</th>
		
		

    </tr>
    <tr>
		<th scope="col" >1Cycle</th>
        <th scope="col"  > 분할지급 </th>
		<th scope="col"  > 계산필드 </th>
        <th scope="col">값1</th>
        <th scope="col">From</th>
		<th scope="col">기준조건</th>
        <th scope="col">To</th>	      
		<th scope="col">값2</th>	   
		
		<th scope="col">값1</th>
        <th scope="col">From</th>
		<th scope="col">기준조건</th>
        <th scope="col">To</th>	      
		<th scope="col">값2</th>
		

		<th scope="col">값1</th>
        <th scope="col">From</th>
        <th scope="col">To</th>	      
		<th scope="col">값2</th>

		<th scope="col">값1</th>
        <th scope="col">Child</th>
        <th scope="col">갯수</th>	   

        <th scope="col">레그</th>
		<th scope="col">연속</th>

        <th scope="col">값1</th>
        <th scope="col">From</th>

        <th scope="col">To</th>	      
		<th scope="col">값2</th>

		<th scope="col">극점이하</th>

		<th scope="col">극점매출</th>
		<th scope="col"  > 수당제한 </th>

    </tr>
    </thead>
    <tbody>
    <?php
    for ($i=0; $row=sql_fetch_array($result); $i++) {   	
       
        $bg = 'bg'.($i%2);
    ?>

    <tr class="<?php echo $bg; ?>">
        
        <td class="td_chk"><?php echo $row['idx']?></td>
        
		 <td class="td_mbid"><a href="allowance_sett.php?what=u&amp;&amp;no=<?=$row['no'] ?>&allowance_name=<?=$row['allowance_name']?>&price_kind=<?=$row['price_cond']?>&immediate=<?=$row['immediate']?>&source=<?=$row['source']?>&source_in1=<?=$row['source_in1']?>&source_in2=<?=$row['source_in2']?>&source_cond1=<?=$row['source_cond1']?>&source_cond2=<?=$row['source_cond2']?>&mb_level_in1=<?=$row['mb_level_in1']?>&mb_level_in2=<?=$row['mb_level_in2']?>&mb_level_cond1=<?=$row['mb_level_cond1']?>&mb_level_cond2=<?=$row['mb_level_cond2']?>&partner_cnt=<?=$row['partner_cnt']?>&partner_cont=<?=$row['partner_cont']?>&history_in1=<?=$row['history_in1']?>&history_in2=<?=$row['history_in2']?>&history_cond1=<?=$row['history_cond1']?>&history_cond2=<?=$row['history_cond2']?>&per=<?=$row['per']?>&base_source=<?=$row['base_source']?>&edit_no=<?=$row['no']?>&andor=<?=$row['andor']?>&benefit_limit1=<?=$row['benefit_limit1']?>&sales_reset=<?=$row['sales_reset']?>&iwolyn=<?=$row['iwolyn']?>&max_reset1=<?=$row['max_reset1']?>&max_reset2=<?=$row['max_reset2']?>&source11=<?=$row['source11']?>&source_cond11=<?=$row['source_cond11']?>&source_cond12=<?=$row['source_cond12']?>&source_in11=<?=$row['source_in11']?>&source_in12=<?=$row['source_in12']?>&mb_level_in11=<?=$row['mb_level_in11']?>&mb_level_cond11=<?=$row['mb_level_cond11']?>&mb_level_cond12=<?=$row['mb_level_cond12']?>&cycle=<?=$row['cycle']?>&recom_kind=<?=$row['recom_kind']?>"><?=$row['allowance_name']?></a></td>

		<!-- <td class="td_mbid"><?php echo $row['chk']?></td>-->

		<td class="td_chk"><?php echo $row['immediate'] ?></td>
		<td rowspan=2 class="td_mbid"><?php echo ($row['source_in1']) ?></td>
		<td rowspan=2 class="td_chk"><?php echo ($row['source_cond1']) ?></td>
		<td rowspan=2 class="td_mbid"><?php echo ($row['source']) ?></td>
		<td rowspan=2 class="td_chk"><?php echo ($row['source_cond2']) ?></td>
		<td rowspan=2 class="td_mbid"><?php echo ($row['source_in2']) ?></td>


		<td rowspan=2 class="td_mbid"><?php echo ($row['source_in11']) ?></td>
		<td rowspan=2 class="td_chk"><?php echo ($row['source_cond11']) ?></td>
		<td rowspan=2 class="td_mbid"><?php echo ($row['source11']) ?></td>
		<td rowspan=2 class="td_chk"><?php echo ($row['source_cond12']) ?></td>
		<td rowspan=2 class="td_mbid"><?php echo ($row['source_in12']) ?></td>

		<td rowspan=2 class="td_mbid"><?php echo ($row['mb_level_in1']) ?></td>
		<td rowspan=2 class="td_chk"><?php echo ($row['mb_level_cond1']) ?></td>

		<td rowspan=2 class="td_chk"><?php echo ($row['mb_level_cond2']) ?></td>
		<td rowspan=2 class="td_mbid"><?php echo ($row['mb_level_in2']) ?></td>

		<td rowspan=2 class="td_mbid"><?php echo ($row['mb_level_in11']) ?></td>
		<td rowspan=2 class="td_chk"><?php echo ($row['mb_level_cond11']) ?></td>
		<td rowspan=2 class="td_mbid"><?php echo ($row['mb_level_cond12']) ?></td>

		

		<td rowspan=2 class="td_mbid"><?php echo ($row['partner_cnt']) ?></td>
		<td rowspan=2 class="td_mbid"><?php echo ($row['partner_cont']) ?></td>

		<td rowspan=2 class="td_mbid"><?php echo ($row['history_in1']) ?></td>
		<td rowspan=2 class="td_chk"><?php echo ($row['history_cond1']) ?></td>
		<td rowspan=2 class="td_chk"><?php echo ($row['history_cond2']) ?></td>
		<td rowspan=2 class="td_mbid"><?php echo ($row['history_in2']) ?></td>

		<td class="td_mbid"><?php echo ($row['max_reset1']) ?></td>

		<td class="td_name sv_use"><?php echo ($row['base_source']) ?></td>

		


		<td class="td_chk"><?php echo ($row['per']) ?></td>
		<td rowspan=2 class="td_chk"><?php echo ($row['iwolyn']) ?></td>
		

    </tr>
	<tr class="<?php echo $bg; ?>">
		<td class="td_mbid"><?php echo $row['cycle']?></td>
		<td  width="200" ><?php echo ($row['andor']) ?></td>
		<td  width="200" ><?php echo ($row['recom_kind']) ?></td>
		<td  width="200"><?php echo ($row['max_reset2']) ?></td>
		<td  width="200"><?php echo ($row['sales_reset']) ?></td>
		<td  width="70" ><?php if( $row['benefit_limit1']=='1'){echo "유보";}else{echo "정상";} ?></td>
	</tr>

    <?php
    }

    if ($i == 0)
        echo '<tr><td colspan="'.$colspan.'" class="empty_table">자료가 없습니다.</td></tr>';
    ?>
    </tbody>
    </table>
</div>

<div class="btn_list01 btn_list">
		1. 새로운 수당체계 만들기<br/>   수당지급조건 설정에서 원하는 값들을 넣고 '수당체계 저장'을 누르면 만들어지고 하단목록에 표시됩니다.<br/><br/>
		    
		2. 수당체계 수정방법<br/>   수당이름을 클릭하시면 상단의 '수당지급조건 설정' 안으로 해당 값들이 들어갑니다. 수정할 내용을 수정하고 '수당체계 저장'을 누르면 수정되고 하단 목록에 표시됩니다.<br/><br/>

		3. 수당체계 삭제방법<br/>   하단 목록에 No 라는 부분의 숫자를 '지울수당번호' 에 넣고 '수당체계 삭제'를 누르시면 삭제됩니다.
</div>


</form>

<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, "{$_SERVER['PHP_SELF']}?$qstr&amp;page="); ?>

<?
if($member['mb_level']>9){
?>


<?
}
?>

<div class="btn_list01 btn_list">
 &nbsp;
</div>	



<script type="text/javascript" src="../js/prototype.js"></script>
<script type="text/javascript" src="../js/common.js"></script>

<script>


function ccc(){

		location.replace("allowance_sett.php");
		
}


</script>

<?php
include_once ('./admin.tail.php');
?>
