<?php
$sub_menu = "200650";
include_once('./_common.php');

//set_mbchild();

auth_check($auth[$sub_menu], 'r');
$qstr.='&deepone='.$deepone;

$sql_common = " FROM  g5_member where (1) ";

//$sql_searchm .= " and mb_leave_date=''"; 

if ($stx){
    $sql_searchm .= " and (  ( mb_id='$stx' ) || ( mb_name='$stx' ) )";
    }
 

if(!$stx){
	 $sql_searchm .= " and mb_child=1  ";
}


if( ($position) ){
	 $sql_searchm .= " and mb_position='$position' ";
}

if($position_day){
	 $sql_searchm .= " and date_format(mb_position_day,'%Y-%m')='$position_day' ";
}

/*
if ($is_admin != 'super')
    $sql_searchm .= " and a.mb_level <= '{$member['mb_level']}' ";
*/

if (!$sst) {
    $sst = "mb_position";
    $sod = "desc";
}

$sql_order = " order by {$sst} {$sod} ";

$sql = " select count(*) as cnt {$sql_common} {$sql_searchm}  ";

$row = sql_fetch($sql);
$total_count = $row['cnt'];



$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함


$listall = '<a href="'.$_SERVER['PHP_SELF'].'" class="ov_listall">전체목록</a>';

$g5['title'] = '직급승급,내역 및 매출리스트';
include_once('./admin.head.php');

$sql = " select * {$sql_common} {$sql_searchm} {$sql_order} limit {$from_record}, {$rows} ";
$result = sql_query($sql);

echo $sql;

$colspan = 16;

/*
 @$holiday = array("2011-01-01");
  
    	@array_push($holliday ,$row['h_day']);
  
  */  
    
$html=' ';    
$hap=0;
$cnt;



function loopdeep2($recom, $deep ,$fr_date ,$to_date){
	  $deep++;
			 if($fr_date){
					 $sql_search = " and date_format(mb_datetime,'%Y-%m-%d')>='$fr_date' and date_format(mb_datetime,'%Y-%m-%d')<='$to_date'";
			}
    $res= sql_query("select * from g5_member where mb_recommend='".$recom."' "); //and mb_leave_date='' 
    for ($j=0; $rrr=sql_fetch_array($res); $j++) { 
 			  $recom=$rrr['mb_id'];  	  

				$cnt++;
							
	    loopdeep2($recom,$deep,$fr_date ,$to_date);

	   		
	   } // for j	
	     

	    return $cnt;
	   
}   


function loopdeepod($recom,$fr_date,$to_date){
	 if($fr_date){
			 $sql_search = " and date_format(o.od_time,'%Y-%m-%d')>='$fr_date' and date_format(o.od_time,'%Y-%m-%d')<='$to_date'";
	}
    $res= sql_query("select * from g5_member where mb_recommend='".$recom."' "); // and mb_leave_date='' 
    for ($j=0; $rrr=sql_fetch_array($res); $j++) { 
 			  $recom=$rrr['mb_id'];  	
 			  $odsql= sql_fetch("select sum(od_receipt_price+od_receipt_localcard)as hap from g5_shop_order as o where mb_id='".$recom."' $sql_search");
 			  $odhap+=$odsql['hap'];
 			 $evsql= sql_fetch("SELECT c.mb_id, SUM(c.ct_price) as hap FROM g5_shop_event_item AS e, g5_shop_item AS i, g5_shop_order AS o, g5_shop_cart AS c WHERE ev_id = '1424926251' AND i.it_id  = e.it_id AND c.it_id=i.it_id AND c.od_id=o.od_id and c.mb_id='".$recom."' $sql_search GROUP BY c.mb_id");
 			  $evhap+=$evsql['hap'];
	     list($od,$ev)=loopdeepod($recom,$fr_date,$to_date);	 
	     $odhap+=$od;
	     $evhap+=$ev;  
	     		
	   } // for j	
	     
	    return array($odhap,$evhap);    
}  
    
    
    
function loopdeep($recom, $deep ,$fr_date ,$to_date,$deepone){
	  $deep++;
	  
    $res= sql_query("select * from g5_member where mb_recommend='".$recom."'  ");  //and mb_leave_date='' 
    for ($j=0; $rrr=sql_fetch_array($res); $j++) { 
 			  $recom=$rrr['mb_id'];  
 			  	  
 			  	 if($fr_date){
								 $sql_search = " and date_format(o.od_time,'%Y-%m-%d')>='$fr_date' and date_format(o.od_time,'%Y-%m-%d')<='$to_date'";
						}
							        $odsql= sql_fetch("select sum(od_receipt_price+od_receipt_localcard)as hap from g5_shop_order as o where mb_id='".$rrr['mb_id']."' $sql_search");
 			    						
 			    						
							        $evsql= sql_fetch("SELECT c.mb_id, SUM(c.ct_price) as hap FROM g5_shop_event_item AS e, g5_shop_item AS i, g5_shop_order AS o, g5_shop_cart AS c WHERE ev_id = '1424926251' AND i.it_id  = e.it_id AND c.it_id=i.it_id AND c.od_id=o.od_id and c.mb_id='".$rrr['mb_id']."' $sql_search GROUP BY c.mb_id ");
 			    						
 			    						list($od,$ev)=loopdeepod($recom,$fr_date,$to_date);	   
										

 			    				if($deep<=$deepone){
 			    								   
									if($deep==1){	    
											$html.=" <tr class='zzb'>";
									}
										    $html.=" <td  class='td_datetime;a_left' >";
												for ($k=0; $k<=$deep+1; $k++){	$html.="&nbsp;&nbsp;&nbsp;&nbsp;";  }

												$html.="<span class='char' data-decimal='8626' data-entity='&amp;#8626;' data-id='43184' title='Downwards Arrow With Tip Leftwards'>";
												
												$html.="<a href='sales_list.php?fr_date=$fr_date&to_date=$to_date&deepone=100&sfl=mb_name&stx=".$rrr['mb_name']."'>";

												if($rrr['mb_position']==2) { $html.="[".$deep."팀장]</a>"; 
												} else {$html.="[$deep]</a>"; }
												
												//if($rrr['mb_position']==2) {  $html.="[".$deep."팀장]"; } else {$html.="[$deep]"; }

												$rcnt=loopdeep2($rrr['mb_id'], $deep ,$fr_date ,$to_date);
												if($rcnt>0){
													if($rcnt>9) {
														$html.="<a href='shop_admin/orderlist.php?sel_field=mb_id&amp;search=".$rrr['mb_id']."'>".$rrr['mb_name']."(".$rcnt.")";
													}else{ 
														$html.="<a href='shop_admin/orderlist.php?sel_field=mb_id&amp;search=".$rrr['mb_id']."'>".$rrr['mb_name']."( ".$rcnt.")";
													}
													
												}else{
													$html.="<a href='shop_admin/orderlist.php?sel_field=mb_id&amp;search=".$rrr['mb_id']."'>".$rrr['mb_name']."( 0)";
												}

												
												$html.="</a></td>";    
										       

										        if($rrr['mb_position_day'])
												{
													$html.="<td class='td_tel'>".$rrr['mb_position_day']."</td>";
												
												}else{
													$html.="<td class='td_tel'>".$rrr['mb_id']."</td>";
												}

												if($deep==1){
														$html.="<td class='zzr' align=right >".number_format($od)."</td>";
														$html.="<td class='zzl' align=right >".number_format($ev)."</td>";
												}else{
													$html.="<td align=right >".number_format($od)."</td>";
													$html.="<td align=right >".number_format($ev)."</td>";
												}
												
										        if(($od-$ev)<0){ $oe=0; }else{ $oe=($od-$ev); }
										        $html.="<td align=right >".number_format($oe)."</td>";

												if($deep==1){
														$html.="<td class='zzr' align=right >".number_format($odsql['hap'])."</td>";
														$html.="<td class='zzl' align=right  >".number_format($evsql['hap'])."</td>";
												}else{
														$html.="<td align=right >".number_format($odsql['hap'])."</td>";
														$html.="<td align=right >".number_format($evsql['hap'])."</td>";
												}
										        
										        
												if(($odsql['hap']-$evsql['hap'])<0){ $oe=0; }else{ $oe=($odsql['hap']-$evsql['hap']); }

										        $html.="<td align=right >".number_format($oe)."</td>";
										    	$html.="<td align=right >".number_format($rrr['mb_chulja'])."</td>";
										    	   
										    $html.="</tr>"; 
									

							
											$html.=loopdeep($recom,$deep,$fr_date,$to_date,$deepone);
								} 

	   		
	   } // for j	
	     

	    return $html;
	   
}    


?>






<form id="fsearch" name="fsearch" class="local_sch01 local_sch" method="get">
	
<div class="local_ov01 local_ov">
	매출 공통조건 |
    
        <label for="fr_date" class="sound_only">기간 시작일</label>
        <input type="text" name="fr_date" value="<?php if($fr_date){echo $fr_date; }else{echo date("Y-01-01");} ?>" id="fr_date"  class="required frm_input" size="13" maxlength="10">
        ~
        <label for="to_date" class="sound_only">기간 종료일</label>
        <input type="text" name="to_date" value="<?php if($to_date){echo $to_date; }else{echo date("Y-m-d");} ?>" id="to_date"  class="required frm_input" size="13" maxlength="10">

          * 매출 공통조건은 매출기간에 대한 '검색조건' 및 '승급조건' 입니다. 
    
</div>
	
	검색조건 |
<label for="sfl" class="sound_only">검색대상</label>
<select name="sfl" id="sfl">
	  <option value="mb_5"<?php echo get_selected($_GET['sfl'], "mb_5"); ?>>이름</option>
    <option value="mb_recommend"<?php echo get_selected($_GET['sfl'], "mb_recommend"); ?>>아이디</option>
    <option value="mb_hp"<?php echo get_selected($_GET['sfl'], "mb_hp"); ?>>휴대폰번호</option>

</select>



<label for="stx" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
<input type="text" name="stx" value="<?php echo $stx ?>" id="stx"  >
|직&nbsp;&nbsp;&nbsp;&nbsp;급 

 
       


<select name="position" id="position">
		<option value="0" <?  if($position=='0'){echo  'selected'; } ?>>조합원</option>
	  <option value="2" <?  if($position=='2'){echo  'selected'; } ?>>팀장</option>
    <option value="3" <?  if($position=='3'){echo  'selected'; } ?>>국장</option>
</select>
|승급 월
 <input type="text" name="position_day" value="<?=$position_day?>" id="position_day"  size="9" maxlength="7">
|Deep
 <input type="text" name="deepone" value="<?=$deepone?>" id="deepone"  size="4" maxlength="10">     

<input type="submit" class="btn_submit" value="검색">
 [<input type="radio" name="ec" id="ec1" value="ec1" checked="true"> 엑셀-페이지별로 보기
<input type="radio" name="ec" id="ec2" value="ec2"> 엑셀-전체보기
<input type="button" class="btn_submit" value="엑셀로 검색결과 보기"  onclick="xls_submit(this)">]
<input type="button" class="btn_submit" value="조직도보기 및 프린트 "  onclick="fpdf_submit(this)">

</form>





<form name="fmemberlist" id="fmemberlist" action="./member_list_update.php" onsubmit="return fmemberlist_submit(this);" method="post">
<input type="hidden" name="sst" value="<?php echo $sst ?>">
<input type="hidden" name="sod" value="<?php echo $sod ?>">
<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
<input type="hidden" name="stx" value="<?php echo $stx ?>">
<input type="hidden" name="page" value="<?php echo $page ?>">

<div class="local_ov01 local_ov">
	승급조건
|하부인원 <input type="text" name="subnumber" id="subnumber" value="<?php echo $subnumber ?>"   >
|하부매출(기획상품제외) <input type="text" name="subsales" id="subsales"   value="<?php echo $subsales ?>" > 
|직&nbsp;&nbsp;&nbsp;&nbsp;급 <select name="positionc" id="positionc">
	  <option value="0" <?  if($position=='0'){echo  'selected'; } ?>>조합원</option>
	  <option value="2" <?  if($position=='2'){echo  'selected'; } ?>>팀장</option>
    <option value="3" <?  if($position=='3'){echo  'selected'; } ?>>국장</option>
</select> 
<input type="radio" name="eq" id="eq1" value="eq1" checked="true"> 동일
<input type="radio" name="eq" id="eq2" value="eq2"> 이상
</br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
|직&nbsp;&nbsp;&nbsp;&nbsp;급 <select name="positionup" id="positionup">
	  <option value="2" <?  if($position=='2'){echo  'selected'; } ?>>팀장</option>
    <option value="3" <?  if($position=='3'){echo  'selected'; } ?>>국장</option>
</select>
으로 <input type="button" class="btn_submit" value="승급시키기" onclick="positionupf(this)">
 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;하부조합원 >
</div>


<div class="tbl_head02 tbl_wrap">
    <table>
	
    <caption><?php echo $g5['title']; ?> 목록</caption>
    <thead>
    <tr>
			
        <th scope="col"  id="mb_list_id"><?php echo subject_sort_link('mb_id') ?>라인</a></th>
        	
        
			


        
        <th scope="col" id="mb_list_id">승급 월</th>		
        
        <th scope="col"  id="mb_team_sales">하부매출</th>
        <th scope="col"  id="mb_my_sales">하부기획매출</th>
        <th scope="col"  id="mb_my_sales">하부 실매출</th>
        <th scope="col"  id="mb_my_sales">자기매출</th>
        <th scope="col"  id="mb_my_sales">자기 기획상품</th>
        <th scope="col"  id="mb_my_sales">자기 실매출</th>


    	  <th scope="col" id="mb_list_id">출자금</th>		
    	
    </tr>
    </thead>
    <tbody>
    <?php


    for ($i=0; $row=sql_fetch_array($result); $i++) { 
							$bg = 'bg'.($i%2);
							
									list($od,$ev)=loopdeepod($row['mb_id'],$fr_date,$to_date);	  
									
									if($fr_date){
											 $sql_search = " and date_format(o.od_time,'%Y-%m-%d')>='$fr_date' and date_format(o.od_time,'%Y-%m-%d')<='$to_date'";
									} 	
										
					    		$html.="<tr class='<?php echo $bg; ?>'>";
								 $html.="<td class='td_odrnum2;a_left' ><a href='sales_list.php?fr_date=$fr_date&to_date=$to_date&deepone=100&sfl=mb_id&stx=".$row['mb_id']."'>";
					       	if($row['mb_position']==2) { $html.="[".$deep."팀장]</a>"; } else {$html.="[0]</a>"; }
							$html.="<a href='shop_admin/orderlist.php?sel_field=mb_id&amp;search=".$row['mb_id']."'>";
							
							

							$html.="".$row['mb_name']."(".loopdeep2($row['mb_id'], $deep ,$fr_date ,$to_date).")</a></td>";
					        
							   $odsql= sql_fetch("select sum(od_receipt_price+od_receipt_localcard)as hap from g5_shop_order as o where mb_id='".$row['mb_id']."' $sql_search");
								$evsql= sql_fetch("SELECT c.mb_id, SUM(c.ct_price) as hap FROM g5_shop_event_item AS e, g5_shop_item AS i, g5_shop_order AS o, g5_shop_cart AS c WHERE ev_id = '1424926251' AND i.it_id  = e.it_id AND c.it_id=i.it_id AND c.od_id=o.od_id and c.mb_id='".$row['mb_id']."' $sql_search GROUP BY c.mb_id ");	
						   
					        //$html.="<td headers='mb_list_homepage'  class='td_name sv_use'>".$row['mb_homepage']."</td>";
										        if($row['mb_position_day'])
												{
													$html.="<td class='td_tel'>".$row['mb_position_day']."</td>";
												
												}else{
													$html.="<td class='td_tel'>".$row['mb_id']."</td>";
												}
					        $html.="<td headers='loopdeepod'  class='td_numbig' >".number_format($od)."</td>";
					        $html.="<td headers='loopdeepev'  class='td_num'>".number_format($ev)."</td>";

							if(($od-$ev)<0){ $oe=0; }else{ $oe=($od-$ev); }		
					        $html.="<td headers='loopdeepev'  class='td_date'>".number_format($oe)."</td>";
					              
					        $html.="<td headers='mb_list_mng'  class='td_num'>".number_format($odsql['hap'])."</td>";
					        		 
 			    				$html.="<td headers='mb_list_mng'  class='td_num'>".number_format($evsql['hap'])."</td>";	

								if(($odsql['hap']-$evsql['hap'])<0){ $oe=0; }else{ $oe=($odsql['hap']-$evsql['hap']); }
 			    				 $html.="<td headers='mb_list_mng'  class='td_num'>".number_format($oe)."</td>";
					    		$html.="<td headers='mb_list_tel' class='td_num'>".$row['mb_chulja']."</td>";
					    		$html.="</tr>";
										  	
								// 재귀함수출 호
								$html.=loopdeep($row['mb_id'] ,0 ,$fr_date ,$to_date,$deepone);

	   }  

    if ($i == 0)
        $html.="<tr><td colspan=\"".$colspan."\" class=\"empty_table\">자료가 없습니다.</td></tr>";
        
		echo $html;       

		
    ?>
    </tbody>
    </table>
</div>


</form>

<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, '?'.$qstr.'&amp;page='); ?>


<style type="text/css"> 
.zzr{color:red;background-color:yellow;} 
.zzb{color:black;background-color:yellow;} 
.zzl{color:blue;background-color:yellow;} 
</style> 


<script>
	

function positionupf(f){

	if( (document.getElementById('subnumber').value=='') && (document.getElementById('subsales').value=='')){
		alert('조건이 없습니다.');
	}else{

			 if(confirm("정말 승급하시겠습니까?")==true){
				  var f=document.getElementById('fmemberlist');
				  if(document.getElementById('eq1').checked){
						eq=0;	
				  }else{
						eq=1;
				  }
			    f.action = "positionup.php?fr_date="+document.getElementById('fr_date').value+"&to_date="+document.getElementById('to_date').value+"&eq="+eq+"&position="+document.getElementById('positionc').value;
			    f.submit();	
			 }
  }
}




function xls_submit(f)
{
	  var f=document.getElementById('fmemberlist');
    str="shop_admin/orderprintresult.php?sst=<?=$sst?>&sfl=<?$sfl?>&csv=sales&fr_date="+document.getElementById('fr_date').value+"&to_date="+document.getElementById('to_date').value+"&position="+document.getElementById('position').value+"&position_day="+document.getElementById('position_day').value+"&deepone="+document.getElementById('deepone').value;

	if(document.getElementById('ec2').checked){
		str+="&ec=ec1";
	}else{
		str+="&ec=ec2";
	}
	f.action =str;
    f.submit();
}


function fpdf_submit(f)
{
	  var f=document.getElementById('fsearch');
    str="sales_list_fpdf.php?sst=<?=$sst?>&csv=sales&fr_date="+document.getElementById('fr_date').value+"&to_date="+document.getElementById('to_date').value+"&position="+document.getElementById('position').value+"&position_day="+document.getElementById('position_day').value+"&deepone="+document.getElementById('deepone').value+"&sfl="+document.getElementById('sfl').value+"&stx="+document.getElementById('stx').value

	if(document.getElementById('ec2').checked){
		str+="&ec=ec1";
	}else{
		str+="&ec=ec2";
	}
	f.action =str;
    f.submit();
}
	
	
function fmemberlist_submit(f)
{
    if (!is_checked("chk[]")) {
        alert(document.pressed+" 하실 항목을 하나 이상 선택하세요.");
        return false;
    }

    if(document.pressed == "선택삭제") {
        if(!confirm("선택한 자료를 정말 삭제하시겠습니까?")) {
            return false;
        }
    }

    return true;
}
</script>

<?php
include_once ('./admin.tail.php');
?>
