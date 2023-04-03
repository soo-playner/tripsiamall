<?php
// include_once('./_common.php');

$server_key = get_fcm_server_key();

// send_notification($token,"개인메시지","개인에게 보내는 메시지입니다.","https://cdn.searchenginejournal.com/wp-content/uploads/2022/06/image-search-1600-x-840-px-62c6dc4ff1eee-sej-760x400.png");
// send_notification_multi("전체메시지","모든유저에게 보내는 메시지입니다.","https://cdn.searchenginejournal.com/wp-content/uploads/2022/06/image-search-1600-x-840-px-62c6dc4ff1eee-sej-760x400.png");
function send_notification($token, $title, $body, $image = null){
    global $server_key;
    $url = FCM_API_URL;
    $notification = get_notification($title , $body , $image);
    $message = array('token' => $token, "notification"=>$notification);
    $fcm_data = array("message"=>$message);
    $json = json_encode($fcm_data);

    send_curl($server_key,$url,$json);
}

function send_notification_multi($title, $body, $image=null){
    global $server_key;
    $url = FCM_API_URL;
    $notification = get_notification($title , $body , $image);
    $message = array("topic"=>"event", "notification"=>$notification);
    $fcm_data = array("message"=>$message);
    $json = json_encode($fcm_data);

    send_curl($server_key,$url,$json);
}

function send_notification_all($title, $body, $image=null){
    global $server_key;
    $url = FCM_API_URL;
    $notification = get_notification($title , $body , $image);
    $message = array("topic"=>"all", "notification"=>$notification);
    $fcm_data = array("message"=>$message);
    $json = json_encode($fcm_data);

    send_curl($server_key,$url,$json);
}

function get_notification($title, $body, $image){
    return array('title' =>$title , 'body' => $body , 'image'=>$image);
}

function send_curl($server_key,$url,$json){
    $headers = array();
    $headers[] = 'Content-Type: application/json';
    $headers[] = 'Authorization: Bearer '. $server_key;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST,"POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
    curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);
    $response = curl_exec($ch);
    if ($response === FALSE) die('FCM Send Error: ' . curl_error($ch));
    curl_close($ch);
}

function get_fcm_server_key(){
    $url = "http://localhost:5000/fcm-token";
    $ch = curl_init();                            
    curl_setopt($ch, CURLOPT_URL, $url);             
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);   
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);     
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);   
    
    $_response = curl_exec($ch);
    curl_close($ch);
    $response = json_decode($_response, true);
    return $response['contents']['token'];
}

?>

<!-- <script>
        window.addEventListener('flutterInAppWebViewPlatformReady', function(event) {
            window.flutter_inappwebview.callHandler('subscribe_topic', 'true');  //푸시 동의
            window.flutter_inappwebview.callHandler('subscribe_topic', 'false'); //푸시 비동의
        }); 
</script> -->