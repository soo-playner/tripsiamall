<?php
$sub_menu = "600300";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'r');

//  adm/cd_dividend_auto --> 모든 상품 '완료' 를 배당 재 지급 (이미 배당 Y를 제외)



$temparr=array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);

$ct_chk_count = count($_POST['ct_chk']);

$cnt = count($_POST['ct_id']);
$j=0;
for ($i=0; $i<$cnt; $i++)
{
    $k = $_POST['ct_chk'][$i];
   
    $ct_id = $_POST['ct_id'][$k];
	if($ct_id){
		$temparr[$j]=$ct_id;
		$j+=1;
	}
}




if($pp)$page=$pp;


$token = get_token();

$fr_date = preg_replace("/([0-9]{4})([0-9]{2})([0-9]{2})/", "\\1-\\2-\\3", $fr_date);
$to_date = preg_replace("/([0-9]{4})([0-9]{2})([0-9]{2})/", "\\1-\\2-\\3", $to_date);

$qstr.='&fr_date='.$fr_date.'&to_date='.$to_date.'&chkc='.$chkc.'&chkm='.$chkm.'&chkr='.$chkr.'&chkd='.$chkd.'&chke='.$chke.'&chki='.$chki;
$qstr.='&diviradio='.$diviradio.'&r='.$r;
$qstr.='&stx='.$stx.'&sfl='.$sfl;



$sql_common = " from dividend where (1) ";


if(!$fr_date){
	$fr_date=date("Y-m-d");
	$to_date=$fr_date;
	
}

if($fr_date) $sql_search .= " and dv_datetime>='$fr_date 00:00:00' AND dv_datetime<='$to_date 24:59:59'";

$j=0;
for ($i=0; $i<count($temparr); $i++)
{
	if($temparr[$i]){
		if($j>0){
			$sql_search.= " or ct_id='$temparr[$i]'";
		}else{
			$sql_search.= " and ( ct_id='$temparr[$i]'";
		}
		$j+=1;
	}	
}

if($j>0) $sql_search.= " ) ";

if($diviradio){
$sql_search .= " and dv_paid='$diviradio'";
}


if(($chkc) || ($chkm) || ($chkr) || ($chkd) || ($chkp) || ($chke) || ($chki) ){
	$sql_search .= " and (";
		if($chkc){
		$sql_search .= " dv_gubun='출자금'";
		}
		
		if(($chkc) &&($chkm)){
			$sql_search .= " or ";
		}
		
		if($chkm){
		$sql_search .= "  dv_gubun='M'";
		}
		
		if(( ($chkc) || ($chkm) ) &&($chkr)){
			$sql_search .= " or ";
		}
				
		if($chkr){
		$sql_search .= "  dv_gubun='R'";
		}

		if(( ($chkc) || ($chkm) || ($chkr) ) &&($chkd)){
			$sql_search .= " or ";
		}
				
		if($chkd){
		$sql_search .= "  dv_gubun='D'";
		}
		
		if(( ($chkc) || ($chkm) || ($chkr) || ($chkd) ) &&($chke)){
			$sql_search .= " or ";
		}
		
		if($chke){
		$sql_search .= " ev_yn=1";
		}

		if(( ($chkc) || ($chkm) || ($chkr) || ($chkd)  || ($chke)) &&($chki)){
			$sql_search .= " or ";
		}
		if($chki){
		$sql_search .= "  dv_gubun='I'";
		}
 $sql_search .= " )";
 
}else if($dv_gubun){
	 $sql_search .= " and dv_gubun='".$dv_gubun."'";
}





if ($stx) {
    $sql_search .= " and ( ";
	if(($sfl=='mb_id') || ($sfl=='dv_oneid')){
            $sql_search .= " ({$sfl} = '{$stx}') ";
          
	}else{
            $sql_search .= " ({$sfl} like '%{$stx}%') ";
          
    }
    $sql_search .= " ) ";
}



if ($r) {
    $sst  = $r;
}

if (!$sst) {
    $sst  = "dv_id";
    $sod = "desc";
}
$sql_order = " order by {$sst} {$sod} ";

$sql = " select count(*) as cnt
            {$sql_common}
            {$sql_search}
            {$sql_order} ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql = " select *
            {$sql_common}
            {$sql_search}
            {$sql_order}
            limit {$from_record}, {$rows} ";
$result = sql_query($sql);


$listall = '<a href="'.$_SERVER['PHP_SELF'].'" class="ov_listall">전체목록</a>';

$mb = array();
if ($sfl == 'mb_id' && $stx)
    $mb = get_member($stx);

$g5['title'] = '지급금관리';
include_once ('./admin.head.php');
include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');
$colspan = 16;

/*
if (strstr($sfl, "mb_id"))
    $mb_id = $stx;
else
    $mb_id = "";
    */
    
 ?>   
 

<script>
	
function dividend()
{
    if (confirm('포인트 정리를 하시면 최근 50건 이전의 포인트 부여 내역을 삭제하므로 포인트 부여 내역을 필요로 할때 찾지 못할 수도 있습니다. 그래도 진행하시겠습니까?'))
    {
        document.location.href = "./dividend_clear.php?ok=1";
    }
}
</script>

<div class="local_ov01 local_ov">
    <?php echo $listall ?>
    전체 <?php echo number_format($total_count) ?> 건
    <?php
    if (isset($mb['mb_id']) && $mb['mb_id']) {
    	  $row2 = sql_fetch(" select sum(dv_money) as sum_dividend_money ,  sum(dv_tax) as sum_dividend_tax,  sum(dv_money-dv_tax) as sum_dividend from dividend where dv_paid<>'R' and dv_paid<>'S' and mb_id='{$mb['mb_id']}' {$sql_search} ");
         echo '&nbsp;전체 합계:  총액('.number_format($row2['sum_dividend_money']).'원)  / 세금('.number_format($row2['sum_dividend_tax']).'원)  / 실 지급금('.number_format($row2['sum_dividend']).'원)';
    } else {
        $row2 = sql_fetch(" select sum(dv_money) as sum_dividend_money,  sum(dv_tax) as sum_dividend_tax,  sum(dv_money-dv_tax) as sum_dividend from dividend where dv_paid<>'R' and dv_paid<>'S' {$sql_search}");
        
        echo '&nbsp;전체 합계:  총액('.number_format($row2['sum_dividend_money']).'원)  / 세금('.number_format($row2['sum_dividend_tax']).'원)  / 실 지급금('.number_format($row2['sum_dividend']).'원)';
    }
    ?>
    <?php if ($is_admin == 'super') { ?><!-- <a href="javascript:chulja_clear();">포인트정리</a> --><?php } ?>
</div>

<form name="fsearch" id="fsearch" class="local_sch01 local_sch" method="get">
<label for="sfl" class="sound_only">검색대상</label>
<select name="sfl" id="sfl">
    <option value="mb_name"<?php echo get_selected($_GET['sfl'], "mb_name"); ?>>회원이름</option>
    <option value="mb_id"<?php echo get_selected($_GET['sfl'], "mb_id"); ?>>회원아이디</option>>
    <option value="dv_oneid"<?php echo get_selected($_GET['sfl'], "dv_oneid"); ?>>주문번호</option>
    <option value="dv_count"<?php echo get_selected($_GET['sfl'], "dv_count"); ?>>지급횟수</option>

</select>
<label for="stx" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
<input type="text" name="stx" value="<?php echo $stx ?>" id="stx" class=" frm_input">
<input type="hidden" name="csv" value="dividend">
        <label for="fr_date" class="sound_only">기간 시작일</label>
        <input type="text" name="fr_date" value="<?php if($fr_date){echo $fr_date; }else{echo date("Ymd");} ?>" id="fr_date" required class="required frm_input" size="13" maxlength="10">
        ~
        <label for="to_date" class="sound_only">기간 종료일</label>
        <input type="text" name="to_date" value="<?php if($to_date){echo $to_date; }else{echo date("Ymd");} ?>" id="to_date" required class="required frm_input" size="13" maxlength="10">
		<!--

                [<input type="checkbox" name="chkc" id="chkc" value="출자금" <? if($chkc=='출자금'){?> checked="true" <?}?> onclick="rcheck(rday);">출자금
                <input type="checkbox" name="chkm" id="chkm" value="M"  <? if($chkm=='M'){?> checked="true" <?}?> onclick="rcheck(rday);" >M
                <input type="checkbox" name="chkr" id="chkr"  value="R" <? if($chkr=='R'){?> checked="true" <?}?>  onclick="rcheck(rday);" >R
                <input type="checkbox" name="chkd" id="chkd"  value="D" <? if($chkd=='D'){?> checked="true" <?}?>  onclick="rcheck(rday);" >D
                <input type="checkbox" name="chke" id="chke"  value="1" <? if($chke=='1'){?> checked="true" <?}?>  onclick="rcheck(rday);" >기획상품  
				<input type="checkbox" name="chki" id="chki"  value="I" <? if($chki=='I'){?> checked="true" <?}?>  onclick="rcheck(rday);" >인센티브 ] 
				-->
                
                 
                 [<input type="radio" name="diviradio" id="diviy" value="Y" <? if($diviradio=='Y'){?> checked="true" <?}?> onclick="rcheck(rday);">지급 함
                <input type="radio" name="diviradio" id="divin" value="N" <? if($diviradio=='N'){?> checked="true" <?}?> onclick="rcheck(rday);" >지급 안함
                <input type="radio" name="diviradio" id="divin" value="S" <? if($diviradio=='S'){?> checked="true" <?}?> onclick="rcheck(rday);" >정지
                <input type="radio" name="diviradio" id="divin" value="R" <? if($diviradio=='R'){?> checked="true" <?}?> onclick="rcheck(rday);" >환불
                <input type="radio" name="diviradio" id="divin" value="" <? if($diviradio==''){?> checked="true" <?}?> onclick="rcheck(rday);">All] 
                
         &nbsp;&nbsp;&nbsp;&nbsp;정렬조건[<input type="radio" name="r" id="rday" value="dv_datetime" onclick="rcheck(rday);" <? if($r=='dv_datetime'){?> checked="true" <?}?> >날짜
		 <input type="radio" name="r" id="rname" value="mb_name" <? if($r=='mb_name'){?> checked="true" <?}?> onclick="rcheck(rname);">이름별
        <input type="radio" name="r" id="rcount" value="dv_count" <? if($r=='dv_count'){?> checked="true" <?}?> onclick="rcheck(rcount);">지급횟수
        <input type="radio" name="r"  id="rgubun" value="dv_gubun" <? if($r=='dv_gubun'){?> checked="true" <?}?> onclick="rcheck(rgubun);">구분번호]

<input type="submit" class="btn_submit" value="검색">
<!--<input type="button" class="btn_submit" value="엑셀로 검색결과 보기"  onclick="xls_submit(this)">-->

</form>



<form name="fdividendlist" id="fdividendlist" method="post" action="./dividend_list_delete.php?fr_date=<?=$fr_date?>&to_date=<?=$to_date?>" onsubmit="return fdividendlist_submit(this);">
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
        <th scope="col">
            <label for="chkall" class="sound_only">지급금 내역 전체</label>
            <input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all(this.form)">
        </th>
        <th scope="col"><?php echo subject_sort_link('mb_id') ?>회원아이디</a></th>
        <th scope="col">이름</th>
        <th scope="col">연락처</th>
        <th scope="col">구분</a></th>
        <!--<th scope="col">구분번호</a></th>-->
        <th scope="col">지급금</a></th>
        <th scope="col">세금</a></th>
        <th scope="col">실 지급금</a></th>
        <th scope="col">회차</a></th>		
        <th scope="col">은행</a></th>		
        <th scope="col">계좌번호</a></th>				
        <th scope="col">종류</a></th>
        <th scope="col">지급금내용</a></th>
        <th scope="col">일시</a></th>
        <th scope="col">지급</a></th>	
		<th scope="col">회원메모</a></th>
    </tr>
    </thead>
    <tbody>
    <?php
    for ($i=0; $row=sql_fetch_array($result); $i++) {
        
				$sql2 = " select * from g5_member where mb_id='{$row['mb_id']}' ";
				$row2 = sql_fetch($sql2);

        $mb_nick = get_sideview($row['mb_id'], $row2['mb_nick'], $row2['mb_email'], $row2['mb_homepage']);

        $link1 = $link2 = '';

				
        $expr = '';


        $bg = 'bg'.($i%2);
    ?>

    <tr class="<?php echo $bg; ?>">
        <td class="td_chk">
            <input type="hidden" name="mb_id[<?php echo $i ?>]" value="<?php echo $row['mb_id'] ?>" id="mb_id_<?php echo $i ?>">
            <input type="hidden" name="dv_id[<?php echo $i ?>]" value="<?php echo $row['dv_id'] ?>" id="dv_id_<?php echo $i ?>">
            <label for="chk_<?php echo $i; ?>" class="sound_only"><?php echo $row['dv_content'] ?> 내역</label>
            <input type="checkbox" name="chk[]" value="<?php echo $i ?>" id="chk_<?php echo $i ?>">
        </td>
        <td class="td_num"><a href="?sfl=<? if($sfl){echo $sfl;}else{ echo 'mb_id'; }?>&amp;what=u&amp;fr_date=<?=$fr_date?>&amp;to_date=<?=$to_date?>&amp;mb_id=<?php echo $row['mb_id'] ?>&amp;stx=<?php echo $stx ?>&amp;mb_name=<?php echo $row2['mb_name']?>&amp;dv_money=<?php echo $row['dv_money']?>&amp;dv_content=<?php echo $row['dv_content'] ?>&amp;dv_datetime=<?php echo $row['dv_datetime'] ?>&amp;dv_id=<?php echo $row['dv_id']?>&amp;dv_oneid=<?php echo $row['dv_oneid']?>&amp;dv_paid=<?php echo $row['dv_paid']?>&amp;dv_gubun=<?php echo $row['dv_gubun']?>&amp;page=<?=$page?>&amp;dv_count=<?php echo $row['dv_count']?>"><?php echo $row['mb_id'] ?></a></td>
        <td class="td_num"><?php echo get_text($row['mb_name']); ?></td>
        <td class="td_name sv_use"><div><?php echo get_text($row['mb_hp']); ?></div></td>
        
        <td class="td_name"><?php echo ($row['dv_gubun']) ?></td>

        <!--<td class="td_datetime">
		<? 
			if($row['dv_gubun']=='출자금'){ ?>
				<a href="chulja_list.php?sfl=ch_id&stx=<?=$row['dv_oneid']?>"><?php echo ($row['dv_oneid']) ?></a>(<?=$row['ct_id']?>)
			<? }else if($row['dv_gubun']=='I') { ?>
				<a href="team_incentive.php?"><?php echo ($row['dv_oneid']) ?></a>(<?=$row['ct_id']?>)
			<? }else{ ?>
				<a href="shop_admin/orderlist.php?sel_field=o.od_id&search=<?=$row['dv_oneid']?>"><?php echo ($row['dv_oneid']) ?></a>(<?=$row['ct_id']?>)
			<? } ?>


		

		
		</td>-->

        <td class="td_num td_pt"><?php echo number_format($row['dv_money']) ?></td>
        <td class="td_chk"><?php echo number_format($row['dv_tax']) ?></td>
        <td class="td_num td_pt"><?php echo number_format($row['dv_money']-$row['dv_tax']) ?></td>
        
        <td class="td_chk"><?php echo number_format($row['dv_count']) ?></td>
        <td class="td_num td_pt"><?php echo ($row2['mb_3']) ?></td>
        <td class="td_name sv_use"><?php echo ($row2['mb_homepage']) ?></td>
        <td class="td_chk"><?php if($row['ev_yn']==1){ echo '기획';}else{ echo '일반'; }  ?></td>
        <td class="td_pt_log"><?php echo $link1 ?><?php echo $row['dv_content'] ?><?php echo $link2 ?></td>
        <td class="td_datetime"><?php echo $row['dv_datetime'] ?></td>
        <td class="td_chk"><?php echo $row['dv_paid'] ?></td>
        <td class="td_name sv_use"><?php echo $row2['mb_memo'] ?></td>
		
    </tr>

    <?php
    }

    if ($i == 0)
        echo '<tr><td colspan="'.$colspan.'" class="empty_table">자료가 없습니다.</td></tr>';
    ?>
    </tbody>
    </table>
</div>

<div class="local_ov01 local_ov">
	 * 하단의 버튼들은 무조건 강제적으로 해당 기능으로 변경합니다. 즉, 어떤 규칙도 고려하지 않고 단순히 해당 기능으로 변경하는 역활입니다.
</br>
</br>
<div class="btn_list01 btn_list">
    
    <input type="submit" name="act_button" value="선택 배당금지급 함" onclick="document.pressed=this.value">
    <input type="submit" name="act_button" value="선택 배당금지급 안함" onclick="document.pressed=this.value">
    <input type="submit" name="act_button" value="선택 배당정지" onclick="document.pressed=this.value">
    <input type="submit" name="act_button" value="현재조건 전체 배당금지급 함" onclick="document.pressed=this.value">
    <input type="submit" name="act_button" value="현재조건 전체 배당금지급 안함" onclick="document.pressed=this.value">      
    <input type="submit" name="act_button" value="선택삭제" onclick="document.pressed=this.value">  
</div>
</div>
</form>

<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, "{$_SERVER['PHP_SELF']}?$qstr&amp;page="); ?>

<section id="chulja_mng">
    <h2 class="h2_frm">개별회원 지급금 등록/수정</h2>  지급이 정지된(S) 조합원은 개발회원 지급금 등록/수정에서 상태를 Y,N으로 병경가능합니다.

    <form name="fdividendlist2" method="post" id="fdividendlist2" action="./dividend_update.php?qstr=<?=$qstr?>" autocomplete="off">
    <input type="hidden" name="sfl" value="<?php echo $sfl ?>">
    <input type="hidden" name="what" id="what" value="<?php echo $what ?>" >
    <input type="hidden" name="ch_id" id="ch_id" value="<?php echo $ch_id ?>" >
    <input type="hidden" name="stx" value="<?php echo $stx ?>">
    <input type="hidden" name="sst" value="<?php echo $sst ?>">
    <input type="hidden" name="sod" value="<?php echo $sod ?>">
    <input type="hidden" name="page" value="<?php echo $page ?>">
    <input type="hidden" name="token" value="<?php echo $token ?>">
		<input type="hidden" name="dv_id" value="<?php echo $dv_id ?>">
    <div class="tbl_frm01 tbl_wrap">
        <table>
        <colgroup>
            <col class="grid_4">
            <col>
        </colgroup>
        <tbody>
        <tr>
        	<th scope="row"><label for="mb_id">회원이름<strong class="sound_only">필수</strong></label>
           <td> <input type="text" name="mb_name" value="<?php echo $mb_name ?>" id="mb_name" class="required frm_input" required>
            <input type=button onClick="kim()"  value="조합원찾기">
          </td>
        </tr>	
        <tr>
            <th scope="row"><label for="mb_id">회원아이디<strong class="sound_only">필수</strong></label></th>
            <td><input type="text" name="mb_id" value="<?php {echo $mb_id; } ?>" id="mb_id"  class="required frm_input" required> 회원아이디는 조합원찾기 

혹은 위 목록에서 클릭하여 적용하세요
            	 </td>
        </tr>
        <tr>
            <th scope="row"><label for="dv_datetime">날짜<strong class="sound_only">필수</strong></label></th>
            <td><input type="text" name="dv_datetime"  value="<?php if($dv_datetime) { echo $dv_datetime; }else{ echo G5_TIME_YMDHIS; } ?>"  id="dv_datetime" required class="required frm_input"> 날짜를 "년-월-일 24시:분:초" 형태로 입력하시면 적용됩니다.</td> 
        </tr>        
        <!--<tr>
		<tr>
            <th scope="row"><label for="dv_gubun">지급구분<strong class="sound_only">필수</strong></label></th>
            <td>
							<!--<select name='dv_gubun' id="dv_gubun" required class="frm_input <?php echo $required ?>">
								<option value=" ">선택하세요</option>
								<option value="출자금"<? if($dv_gubun=='출자금') echo "selected"; ?>>출자금</option>
								<option value="M"<? if($dv_gubun=='M') echo "selected"; ?>>M배당</option>
								<option value="R"<? if($dv_gubun=='R') echo "selected"; ?>>R배당</option>
								<option value="D"<? if($dv_gubun=='D') echo "selected"; ?>>D배당</option>
								<option value="I"<? if($dv_gubun=='I') echo "selected"; ?>>I인센티브</option>
								<option value="PV"<? if($dv_gubun=='PV') echo "selected"; ?>>PV배당</option
							</select>
						</td>
        </tr>
        
            <th scope="row"><label for="dv_oneid">주문번호<strong class="sound_only">필수</strong></label></th>
            <td><input type="text" name="dv_oneid"  value="<?php echo $dv_oneid ?>"  id="dv_oneid" required class=" frm_input"></td>

        </tr>-->
        <tr>
            <th scope="row"><label for="dv_count">배당횟수<strong class="sound_only">필수</strong></label></th>
            <td><input type="text" name="dv_count"  value="<?php echo $dv_count ?>"  id="dv_count" required class="required frm_input"></td>

        </tr>
        <tr>
            <th scope="row"><label for="dv_money">배당금<strong class="sound_only">필수</strong></label></th>
            <td><input type="text" name="dv_money"  value="<?php echo $dv_money ?>"  id="dv_money" required class="required frm_input"></td>

        </tr>
        <tr>
            <th scope="row"><label for="dv_paid">배당여부<strong class="sound_only">필수</strong></label></th>
							<td>
							<select name='dv_paid' id="dv_paid" required class="frm_input <?php echo $required ?>">
								<option value="Y"<? if($dv_paid=='Y') echo "selected"; ?>>Y</option>
								<option value="N"<? if($dv_paid=='N') echo "selected"; ?>>N</option>
								<option value="S"<? if($dv_paid=='S') echo "selected"; ?>>S</option>
								<option value="R"<? if($dv_paid=='R') echo "selected"; ?>>R</option>
							</select>
							</td>
        </tr>
        <!--
        <tr>
            <th scope="row"><label for="dv_paid">상품구분<strong class="sound_only">필수</strong></label></th>
							<td>
							<select name='ev_yn' id="ev_yn" required class="frm_input <?php echo $required ?>">
								<option value="1"<? if($ev_yn==1) echo "selected"; ?>>기획</option>
								<option value="0"<? if($ev_yn==0) echo "selected"; ?>>일반</option>
							</select>
							</td>
        </tr>
		-->
                
        <tr>
            <th scope="row"><label for="dv_content">내용<strong class="sound_only">필수</strong></label></th>
            <td><input type="text" name="dv_content"  value="<?php echo $dv_content ?>" id="dv_content" class="required frm_input" size="80">
			
			</td>
            
        </tr>

     
        </tbody>
        </table>
    </div>

    <div class="btn_confirm01 btn_confirm">
    	<? if($what=='u') { ?>  <input type="submit" id="submit" value="수정" class="btn_submit"> <? } else{  ?> <input type="submit" id="submit" value="등록" class="btn_submit">   <? } ?>
    </div>

    </form>

</section>


<script type="text/javascript" src="../js/prototype.js"></script>
<script type="text/javascript" src="../js/common.js"></script>


<script>
	
$(function(){
    $("#fr_date, #to_date").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yymmdd", showButtonPanel: true, yearRange: "c-99:c+99", maxDate: "+0d" });
});


function xls_submit(f)
{
	  var f=document.getElementById('fsearch');
    f.action = "../adm/shop_admin/orderprintresult.php?csv=dividend";
    f.submit();
}
  
  
    	
function fdividendlist_submit(f)
{

   
}


function kim(){
		var rr="hwsearch.php?hwname="+document.getElementById('mb_name').value;
		window.open(rr , 900, 400, 0, 0,0);
		//new Ajax.Request(rr,{method:'get',onComplete:hwresult.bind(this)});
}


function rcheck(){
		if(document.getElementById("rday").checked==true){
			document.getElementById("sst").value='dv_datetime';
		}else if(document.getElementById("rcount").checked==true){
			document.getElementById("sst").value='dv_count';
		}else if(document.getElementById("rgubun").checked==true){
			document.getElementById("sst").value='dv_gubun';
		}else if(document.getElementById("diviy").checked==true){
			document.getElementById("sst").value='dv_gubun';
		}else if(document.getElementById("divin").checked==true){
			document.getElementById("sst").value='dv_gubun';
		}else if(document.getElementById("rname").checked==true){
			document.getElementById("sst").value='mb_name';
		}
}


function FineZip(id,name){
document.getElementById("mb_id").value=id;
document.getElementById("mb_name").value=name;	
document.getElementById("dv_money").value='';	
document.getElementById("dv_gubun").value='';	
document.getElementById("dv_paid").value='';	
document.getElementById("dv_count").value='';	
document.getElementById("dv_content").value='';	
document.getElementById("what").value='w';	
		  document.getElementById("submit").value='등록';	
document.getElementById("dv_datetime").value='<?=G5_TIME_YMDHIS  ?>';	
}




		
</script>

<?php
include_once ('./admin.tail.php');
?>
