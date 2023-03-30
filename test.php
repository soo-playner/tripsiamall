<?php
include_once('./common.php');
include_once(G5_THEME_PATH.'/_include/wallet.php');


?>


<?
/* include_once(G5_LIB_PATH.'/Telegram/telegram_api.php');

$mb_id = 'admin';
curl_tele_sent('[ khan-deposit ] '.$mb_id.' 님의 입급요청이 있습니다.'); */
?>

<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>

<!-- 가스테스트 -->
<!-- <script>
$.ajax({
    type: "GET",
      url: "https://api.etherscan.io/api?module=gastracker&action=gasoracle",
      cache: false,
      dataType: "json",
      data:  {
        apikey : "V3G3VI316K8BCTGDFQG6QGUAZ4MM1GN9WJ"
      },
      success : function(res){
          console.log(res.result.FastGasPrice)
          console.log(res.result.ProposeGasPrice)
          console.log(res.result.SafeGasPrice)
      }
});
</script> -->