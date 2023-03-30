<?php
include_once("../common.php");

$to_email = $_POST['user_email'];

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require "./PHPMailer.php";
require "./SMTP.php";
require "./Exception.php";

$mail = new PHPMailer(true);

try {

    // 서버세팅
    $mail -> SMTPDebug = 2;    // 디버깅 설정
    $mail -> isSMTP();        // SMTP 사용 설정

    $mail -> Host = "smtp.gmail.com";                // email 보낼때 사용할 서버를 지정
    $mail -> SMTPAuth = true;                        // SMTP 인증을 사용함
    $mail -> Username = CONFIG_MAIL_ACCOUNT;    // 메일 계정
    $mail -> Password = CONFIG_MAIL_PW;                // 메일 비밀번호
    // $mail -> Username = "redonemart.01";    // 메일 계정
    // $mail -> Password = "redonemart.77";                // 메일 비밀번호

    $mail -> SMTPSecure = "ssl";                    // SSL을 사용함
    $mail -> Port = 465;                            // email 보낼때 사용할 포트를 지정
    $mail -> CharSet = "utf-8";                        // 문자셋 인코딩

    // 보내는 메일
    $mail -> setFrom(CONFIG_MAIL_ADDR, CONFIG_TITLE);

    // 받는 메일
    $mail -> addAddress($to_email, $to_id);

    //인증해시값
    $dateTime = new DateTime("now", new DateTimeZone("Asia/Seoul"));
    $date_time = $dateTime->format("Y-m-d H:i:s");
    $auth_md5 = hash("sha256", $date_time.$to_email);
    

    // 메일 내용
    $mail -> isHTML(true);                                               // HTML 태그 사용 여부
    $mail -> Subject = "[".CONFIG_TITLE."] MEMBER RESTRATION CERTIFICATION";              // 메일 제목
    // $mail -> Body = $auth_md5;    // 메일 내용

    $hostname=$_SERVER["HTTP_HOST"];

    // 본문 이미지 첨부 및 내용
    $image = G5_THEME_PATH.'/img/email_top_logo.png';
    $mail->AddEmbeddedImage($image, "keyImage");
    // $mail->MsgHTML("<div><p><img src='cid:keyImage'
    // style='max-width:80%;
    // margin: 0 auto 20px;
    // display:block;
    // margin-top: 20px;'></p><br />
    // <div
    // style='width: 50%;
    // text-align:center;
    // margin: 0 auto 20px;
    // display: block;
    // margin-top: 20px;'>Click<a href='https://$hostname/mail/auth_mail.php?hash=$auth_md5'> HERE </a>to complete authentication</div></div>");

    $mail->MsgHTML("<div>
    <img src='cid:keyImage'
    style='margin: 0 auto 20px;
    display:block;
    margin-top: 20px;'>
    <p 
    style='color:black;font-size: 32px;font-weight: bold;line-height:initial;width: 500px;margin: 0 auto;margin-top: 50px;'>
    회원가입 메일인증 안내입니다.
    </p>
    <p
    style='color:black;line-height: 22px;width: 500px;margin: 0 auto;margin-top: 40px;'>
    안녕하세요.<br>
    [ ".CONFIG_TITLE." ]을 이용해 주셔서 진심으로 감사드립니다.<br>
    아래 <span style='font-weight: bold;'>'메일 인증'</span>버튼을 클릭하여 이메일 인증을 완료해주세요.<br>
    감사합니다.
    </p>
    <div 
    style='width: 100px;text-align: center;padding: 13px 20px;font-size: 13px;background: #282828;margin: 0 auto;margin-top: 50px;'>
    <a href='".G5_URL."/mail/auth_mail.php?hash=$auth_md5' 
    style='color: #fff;text-decoration: none;font-weight: bold;'>
    메일 인증
    </a>
    </div>
    <div 
    style='color: #969696;font-size: 11px;margin-top: 40px;text-align:center;'>
    본 메일은 발신 전용입니다. 회신하실 경우 답변되지 않습니다.
    </div>
    </div>");


    // Gmail로 메일을 발송하기 위해서는 CA인증이 필요하다.
    // CA 인증을 받지 못한 경우에는 아래 설정하여 인증체크를 해지하여야 한다.
    $mail -> SMTPOptions = array(
        "ssl" => array(
              "verify_peer" => false
            , "verify_peer_name" => false
            , "allow_self_signed" => true
        )
    );

    // 메일 전송
    $mail -> send();

    $sql_select = "SELECT * FROM auth_email WHERE email = '$to_email' ORDER BY auth_start_date DESC LIMIT 0,1";
    $result_select = sql_query($sql_select);
    $count = sql_num_rows($result_select);
    
    if($count > 0){
        $row_select = sql_fetch_array($result_select);
        $sql_update = "UPDATE auth_email SET auth_check = '2' WHERE id ='{$row_select['id']}'";
        sql_query($sql_update);
    }

    // DB 저장
    $sql_insert = "INSERT INTO auth_email(email, auth_md5, auth_start_date) VALUES('$to_email','$auth_md5','$date_time')";
    $result_insert = sql_query($sql_insert);

} catch (Exception $e) {echo $e;}

?>
