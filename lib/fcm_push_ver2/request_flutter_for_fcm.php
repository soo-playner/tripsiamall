<?php
    include_once('./_common.php');

    $url = isset($_GET['url']) ? urldecode($_GET['url']) : false;

    if(!$url) exit;

    if($_SERVER['HTTP_X_REQUESTED_WITH'] == "com.wallet.esg"){

        $mb_no = $member['mb_no'];
        $fcm_token = $member['fcm_token'] ? $member['fcm_token'] : "null";
?>
    <script>
        window.addEventListener('flutterInAppWebViewPlatformReady', function(event) {
            window.flutter_inappwebview.callHandler('save_fcm_token', '<?=$mb_no?>','<?=$fcm_token?>');            
            location.href= '<?=$url?>'; 
        }); 
    </script>
  <?php  }else{ ?>
        <script>location.href='<?=$url?>';</script>;
<?php } ?>
