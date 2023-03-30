<?php
function setPushData2($title, $words, $fcmToken,$image = null)
{ // push에 담을 데이터
    global $config;
    
    $apiKey = $config['cf_fcm_api_key']; // 파이어베이스 push key;
    $url = "https://fcm.googleapis.com/fcm/send"; // push api url 주소

    $title = $title; // push 일림창의 제목
    $message = $words; // push 알림창의 제목 밑 메시지
    // $image = "http://www.jajusibo.com/imgdata/jajuilbo_com/201605/2016051324082244.jpg"; 

    // push 알림창의 이미지 데이터 (이미지 첨부시 push 알림창에 화살표 아이콘이 생기며 터치시 이미지 출력)
    // 보내고자하는 데이터를 배열에 담습니다
    $notification = array();
    $notification['title'] = $title;
    $notification['body'] = $message;
    $notification['image'] = $image;
    /* $data = array();
    $data['notification']['image'] = $image;
    $webpush = array();
    $webpush['headers']['image'] = $image;
    $apns = array();
    $apns['payload']['aps']['mutable-content'] = 1;
    $apns['fcm_options']['image'] = $image; */
    $tokens = $fcmToken;
    

    // 전송을 진행합니다
    $fields = array("registration_ids" => $tokens, "notification" => $notification);
    $headers = array("Authorization:key=" . $apiKey, "Content-Type: application/json");
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST,"POST"); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
    
    
    $response = curl_exec($ch); 
    //Close request 
    if ($response === FALSE) { die('FCM Send Error: ' . curl_error($ch)); } 
    curl_close($ch);
}

function setPushData($title, $words, $fcmToken,$image = null){
    global $config;
    
    $url = "https://fcm.googleapis.com/fcm/send";
    $token = $fcmToken;
    $serverKey = $config['cf_fcm_api_key'];

    $title = $title; // push 일림창의 제목
    $message = $words; // push 알림창의 제목 밑 메시지
    
    $notification = array();
    $notification['title'] = $title;
    $notification['body'] = $message;
    $notification['image'] = $image;

    // $notification = array('title' =>$title , 'text' => $body, 'sound' => 'default', 'badge' => '1'); 
    $arrayToSend = array('to' => $token, 'notification' => $notification,'priority'=>'high'); 
    $json = json_encode($arrayToSend); 
    
    $headers = array(); 
    $headers[] = 'Content-Type: application/json'; 
    $headers[] = 'Authorization: key='. $serverKey; 

    $ch = curl_init(); 
    curl_setopt($ch, CURLOPT_URL, $url); 
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST,"POST"); 
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json); 
    curl_setopt($ch, CURLOPT_HTTPHEADER,$headers); 

    //Send the request 
    $response = curl_exec($ch); 

    //Close request 
    if ($response === FALSE) { die('FCM Send Error: ' . curl_error($ch)); } 
    curl_close($ch);

}
