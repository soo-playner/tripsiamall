<?php
$sub_menu = "600100";
include_once('./_common.php');
$g5['title'] = '수당 설정/관리';

include_once('../admin.head.php');
include_once('./bonus_inc.php');

auth_check($auth[$sub_menu], 'r');
$token = get_token();

?>

<link href="<?=G5_ADMIN_URL?>/css/scss/bonus/bonus_config2.css" rel="stylesheet">
<form name="allowance" id="allowance" method="post" action="./bonus_config_update.php" onsubmit="return frmconfig_check(this);" >

<div class="local_desc01 local_desc">
    <p>
        - 마케팅수당설정 - 관리자외 설정금지<br>
        - 수당한계 : 0 또는 값이 없으면 제한없음.<br>
        - 데일리 : ( 수당비율 * 회원별 보유상품수익률) 지급 <strong>[ <?=strtoupper($minings[$now_mining_coin])?> ] </strong><br>
        - 부스터 : (직추천수 = 지급대수) 일때 대상의 (데일리보너스의 50% * 지급율)
        - 승급 및 직급 보너스: 산하매출 & 직급유지 & 조건 달성시 승급
	</p>
</div>

<div class="tbl_head02 tbl_wrap">
    <table >
    <caption><?php echo $g5['title']; ?> 목록</caption>
    <thead>
	<p> 수당 설정 </p>
    <tr>
        <th scope="col" width="30px">No</th>
        <th scope="col" width="40px">사용설정</th>
        <th scope="col" width="120px">수당명</th>	
        <th scope="col" width="60px">수당코드</th>
        <th scope="col" width="80px">수당지급수단</th>
        <th scope="col" width="40px">수당한계</th>
		<th scope="col" width="120px">수당비율 (%)<br>( 콤마로 구분)</th>
		<th scope="col" width="120px">지급한계(대수/%)<br>( 콤마로 구분)</th>
        <th scope="col" width="80px">수당지급방식</th>
        <th scope="col" width="80px">수당조건</th>
        <th scope="col" width="280px">수당설명</th>
    </tr>
    </thead>

    <tbody>
    <?for($i=0; $row=sql_fetch_array($list); $i++){?>
    
    <tr class='<?if($i == 0){echo 'first';}?>'>
   
    <td style="text-align:center"><input type="hidden" name="idx[]" value="<?=$row['idx']?>"><?=$row['idx']?></td>
    <td style="text-align:center"><input type='checkbox' class='checkbox' name='check' <?php echo $row['used'] > 0?'checked':''; ?>>
        <input type="hidden" name="used[]" class='used' value="<?=$row['used']?>">
    </td>
    <td style="text-align:center"><input class='bonus_input' name="name[]"  value="<?=$row['name']?>"></input></td>
    <td style="text-align:center"><input class='bonus_input' name="code[]"  value="<?=$row['code']?>"></input></td>
    
    <td style="text-align:center"><input class='bonus_input' name="kind[]"  value="<?=$row['kind']?>"></input></td>
	<td style="text-align:center"><input class='bonus_input' name="limited[]"  value="<?=$row['limited']?>"></input></td>
	<td style="text-align:center"><input class='bonus_input' name="rate[]"  value="<?=$row['rate']?>"></input></td>
    <td style="text-align:center"><input class='bonus_input' name="layer[]"  value="<?=$row['layer']?>"></input></td>
    <td style="text-align:center">
        <select id="bonus_source" class='bonus_source' name="source[]">
            <?php echo option_selected(0, $row['source'], "ALL"); ?>
            <?php echo option_selected(1, $row['source'], "추천인[tree]"); ?>
            <?php echo option_selected(2, $row['source'], "바이너리[binary]"); ?>
        </select>
    </td>
    <td style="text-align:center"><input class='bonus_input' name="bonus_condition[]"  value="<?=$row['bonus_condition']?>"></input></td>
    <td style="text-align:center"><input class='bonus_input' name="memo[]"  value="<?=$row['memo']?>"></input></td>
    </tr>
    <?}?>
    </tbody>
    
    <tfoot>
        <td colspan=12 height="100px" style="padding:20px 0px" class="btn_ly">
            <input  style="align:center;padding:15px 50px;background:cornflowerblue;" type="submit" class="btn btn_confirm btn_submit" value="저장하기" id="com_send"></input>
        </td>
    </tfoot>
</table>

</div>
</form>


<style>
    #mining_log{width:600px;margin: 20px;}
    #mining_log .head{border:1px solid #eee;background:orange;display: flex;width:inherit}
    #mining_log .body{border:1px solid #eee;display: flex;width:inherit}
    #mining_log dt,#mining_log dd{display:block;padding:5px 10px;text-align: center;width:inherit;margin:0;}
    #mining_log dd{border-left:1px solid #eee;}
</style>

<div id='mining_log'>
    데일리 지급량 기록 (최근 10일)
    <div class='head'>
        <dt>지급일</dt>
        <dd>데일리보너스지급량</dd>
        <dd class="blue" style='color:white'>데일리보너스지급총량<br>(<?=$minings[$now_mining_coin]?>)</dd>
    </div>

    <?
        $mining_rate_result = sql_query("SELECT day,rate from soodang_mining WHERE allowance_name = 'mining' group by day order by day desc limit 0,10");

        while($row = sql_fetch_array($mining_rate_result)){
            $mining_bonus_exc_sql = "SELECT SUM(mining) as mining_total FROM soodang_mining WHERE day = '{$row['day']}' AND allowance_name != 'coin swap' ";
            $mining_bonus_exc = sql_fetch($mining_bonus_exc_sql);

            $coin_swap_exc_sql = "SELECT SUM(mining) as swap_total FROM soodang_mining WHERE day = '{$row['day']}' AND allowance_name = 'coin swap' ";
            $coin_swap_exc = sql_fetch($coin_swap_exc_sql);
    ?>
    <div class='body'>
        <dt><?=$row['day']?></dt>
        <dd><?=$row['rate']?></dd>
        <dd><?=shift_auto($mining_bonus_exc['mining_total'],8)?></dd>
        <dd><?=shift_auto($coin_swap_exc['swap_total'],4)?></dd>
    </div>
    <?}?>
</div>

<script>

    function frmconfig_check(f){
        
    }

    $(document).ready(function(){

        $(".checkbox" ).on( "click",function(){
            if($("input:checkbox[name='check']").is(":checked") == true){
                console.log( $(this).next().val() );
                $(this).next().val(1);
            }else{
                $(this).next().val(0);
            }
        });
        
    });

</script>
</div>

<?php
include_once ('../admin.tail.php');
?>