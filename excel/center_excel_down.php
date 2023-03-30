<?
include_once('./_common.php');

$excel_sql= urldecode($_GET['excel_sql']);
$excel_sql = stripslashes($excel_sql);
$target = $_GET['target'];
$today=date("Y-m-d");

header( "Content-type: application/vnd.ms-excel;charset=utf-8" ); 
header( "Content-Disposition: attachment; filename=$target-$today".".xls" ); 
header( "Content-Description: PHP4 Generated Data" ); 
print("<meta http-equiv=\"Content-Type\" content=\"application/vnd.ms-excel; charset=utf-8\">");

?>

<html> 
<body>
    
<style type="text/css">
/*br태그 alt+enter 로 치환하기*/
br{mso-data-placement:same-cell;}
.text{mso-number-format:'\@';}
.title_header{background:royalblue;color:white}
@page{ margin:2in 1in 2in 1in } 
</style>

<table cellspacing=0 cellpadding=0 border=1>
      
<?
$result = sql_query($excel_sql);

?>
<thead>
    <tr>
        <td colspan="7">
            <? echo "기간 :: ".$fr_date." ~ ".$to_date ?>
        </td>
    </tr>
</thead>
    <tr align='center' class='title_header'>
        <td>회원아이디</td>
        <td>회원가입일</td>
        <td>회원의 추천인</td>		
        <td>기간매출금액</td>	
        <td>기간PV</td>
        <td>멤버쉽결제</td>
        <td>센터수당</td>
    </tr>

    <?while($row = sql_fetch_array($result)){
        
        $order_total_sql = "SELECT sum(upstair) as upstair_total, sum(pv) as pv_total from g5_shop_order WHERE mb_id = '{$row['mb_id']}' AND od_date >= '{$_GET['fr_date']}' AND od_date <= '{$_GET['to_date']}' ";
        $order_total = sql_fetch($order_total_sql);
        $center_bonus = $order_total['pv_total']*0.02;

        $membership_sql = " SELECT * from g5_shop_order WHERE mb_id = '{$row['mb_id']}' AND od_date >= '{$fr_date}' AND od_date <= '{$to_date}' AND od_cash = 300000 ";
        $membership_yn = sql_fetch($membership_sql)['od_cash'];

        ?>
        <tr align='center'>
            <td><?=$row['mb_id']?></td>
            <td class='text'><?=$row['mb_open_date']?></td>
            <td><?=$row['mb_recommend']?></td>
            <td><?=Number_format($order_total['upstair_total'])?></td>
            <td><?=Number_format($order_total['pv_total'])?></td>
            <td><?=Number_format($membership_yn)?></td>
            <td><?=Number_format($center_bonus)?></td>
        </tr>
    <?}?>




</table>
</body> 
<script>
window.close();
</script>
</html> 
