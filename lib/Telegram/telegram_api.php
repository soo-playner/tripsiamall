<?php
// if (!defined('_GNUBOARD_')) exit;

/* if($_GET['debug']){
    echo "TEST";
    $api_code = "1897463083:AAF_xiQeS06nEFj0Eqt9jL4KiL1zrCYT45U";
    $chat_id = "-1001413702347";
    $text = "[KHAN][입금요청]test1(입금테스트)님의 1,000,000입금요청이 있습니다.";

    $ch = curl_init();

    $curl_url = "https://api.telegram.org/bot{$api_code}/sendMessage?chat_id={$chat_id}&text={$text}";
    print_R($curl_url);

    @curl_setopt($ch, CURLOPT_URL, $curl_url);
    @curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);     
    @curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);      
    @curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);   
    $exec = curl_exec($ch);
}
 */

function curl_tele_sent($text,$where = 1){
    
 if(!$text){
        exit("No Data!!");
    } // end

    if($where == 2){
        $row = sql_fetch(" select bot_api_code, bot_chat_id from telegram_setting where idx = 2");
    }else{
        $row = sql_fetch(" select bot_api_code, bot_chat_id from telegram_setting where idx = 1 ");
    }

    if(!$row['bot_api_code'] && !$row['bot_chat_id']){
        exit();
    }
    
    $ch = curl_init();
    $api_code = $row['bot_api_code']; // 900~
    $chat_id = $row['bot_chat_id']; // 822~~

    $curl_url = "https://api.telegram.org/bot{$api_code}/sendMessage?chat_id={$chat_id}&text={$text}";
    // print_R($curl_url);

    @curl_setopt($ch, CURLOPT_URL, $curl_url);
    @curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);     
    @curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);      
    @curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);   
    $exec = curl_exec($ch);
    
} // curl_tele_sent end 

?>