<?php
if (!defined('_GNUBOARD_')) exit;

include_once(G5_PHPMAILER_PATH.'/PHPMailerAutoload.php');

// 메일 보내기 (파일 여러개 첨부 가능)
// type : text=0, html=1, text+html=2
// function mailer($fname, $fmail, $to, $subject, $content, $type=0, $file="", $cc="", $bcc="")
// {
//     global $config;
//     global $g5;

//     // 메일발송 사용을 하지 않는다면
//     if (!$config['cf_email_use']) return;

//     if ($type != 1)
//         $content = nl2br($content);

//     $mail = new PHPMailer(); // defaults to using php "mail()"
//     if (defined('G5_SMTP') && G5_SMTP) {
//         $mail->IsSMTP(); // telling the class to use SMTP
//         $mail->Host = G5_SMTP; // SMTP server
//         if(defined('G5_SMTP_PORT') && G5_SMTP_PORT)
//             $mail->Port = G5_SMTP_PORT;
//     }

//     $mail->isSMTP(); 
//     $mail->SMTPAuth = true; 
//     // $mail->SMTPSecure = "ssl"; 
//     // $mail->Host = "smtp.1and1.com"; 
//     $mail->Port = 587; 
//     // $mail->Username = "ticket@pinnaclemining.net"; 
//     // $mail->Password = "725Norton!@"; 
//     $mail->Host = "mail.willsoft.kr"; 
//     // $mail->Port = 25; 
//     $mail->Username = "khpark@willsoft.kr"; 
//     $mail->Password = "asdfdg93!"; 
//     $mail->CharSet = 'UTF-8';
//     $mail->From = $fmail;
//     $mail->FromName = $fname;
//     $mail->Subject = $subject;
//     $mail->AltBody = ""; // optional, comment out and test
//     $mail->msgHTML($content);
//     $mail->addAddress($to);
//     if ($cc)
//         $mail->addCC($cc);
//     if ($bcc)
//         $mail->addBCC($bcc);
//     //print_r2($file); exit;
//     if ($file != "") {
//         foreach ($file as $f) {
//             $mail->addAttachment($f['path'], $f['name']);
//         }
//     }
//     return $mail->send();
// }

/*
* Method to convert an associative array of parameters into the HTML body string
*/
function getBody($fields) {
    $content = '';
    foreach ($fields as $FORM_FIELD => $value) {
        $content .= '--' . MULTIPART_BOUNDARY . EOL;
        $content .= 'Content-Disposition: form-data; name="' . $FORM_FIELD . '"' . EOL;
        $content .= EOL . $value . EOL;
    }
    return $content . '--' . MULTIPART_BOUNDARY . '--'; // Email body should end with "--"
}

/*
* Method to get the headers for a basic authentication with username and passowrd
*/
function getHeader($username, $password){
    // basic Authentication
    $auth = base64_encode("$username:$password");

    // Define the header
    return array("Authorization:Basic $auth", 'Content-Type: multipart/form-data ; boundary=' . MULTIPART_BOUNDARY );
}

function mailer($fname, $fmail, $to, $subject, $content, $type=0, $file="", $cc="", $bcc="")
{

    define('MULTIPART_BOUNDARY','-----------------------'.md5(time()));
    define('EOL',"\r\n");// PHP_EOL cannot be used for emails we need the CRFL '\r\n'

    // URL to the API that sends the email.
    $url = 'https://wdyg1.api.infobip.com/email/1/send';
    // $url = 'http://api-hk2.infobip.com';

    if ($type != 1){
        $content = nl2br($content);
    }
    // Associate Array of the post parameters to be sent to the API
    // mail.1eth.net
    $postData = array(
        'from' => $fname.' <noreply@worlds-connected.co>',
        'to' => $to,
        'subject' => $subject,
        'html' => $content
    );

    // Create the stream context.
    $context = stream_context_create(array(
        'http' => array(
            'method' => 'POST',
            'header' => getHeader('HAZGLOBAL', 'Willsoft0780!@'),
            'content' =>  getBody($postData),
        )
    ));

    // Read the response using the Stream Context.
    $response = file_get_contents($url, false, $context);
    // if($http_response_header){
    //     print json_encode($http_response_header);
    // }
}

// 파일을 첨부함
function attach_file($filename, $tmp_name)
{
    // 서버에 업로드 되는 파일은 확장자를 주지 않는다. (보안 취약점)
    $dest_file = G5_DATA_PATH.'/tmp/'.str_replace('/', '_', $tmp_name);
    move_uploaded_file($tmp_name, $dest_file);
    $tmpfile = array("name" => $filename, "path" => $dest_file);
    return $tmpfile;
}
?>