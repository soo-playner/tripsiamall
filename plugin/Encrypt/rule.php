<?
if (!defined('_GNUBOARD_')) exit;
error_reporting(0);

$secret_iv = "#@$%^&*()_+=-";


function Encrypt($str, $secret_key='', $secret_iv='') { 
    $key = hash('sha256', $secret_key); 
    $iv = substr(hash('sha256', $secret_iv), 0, 32) ; 
    return str_replace(":", "", base64_encode( openssl_encrypt($str, "AES-256-CBC", $key, 0, $iv)) );
} 

function Decrypt($str, $secret_key='', $secret_iv='') { 
    $key = hash('sha256', $secret_key); 
    $iv = substr(hash('sha256', $secret_iv), 0, 32);
    return openssl_decrypt( base64_decode($str), "AES-256-CBC", $key, 0, $iv ); 
}

function person_key($person,$person_key,$value = ''){
    global $secret_key,$secret_iv;
     
    if($person_key == '1') {
        $birth_day = substr(Decrypt($value,$secret_key,$secret_iv),0,6);
        $result = "인증회원".' - <span class=person_info>'.$person.' / '.$birth_day."</span>";
    }else if($person_key == '2'){
        $result = "미승인 / 재등록 요망";
    }else{
        $result = "등록대기중";
    }
    return $result;
}

?>