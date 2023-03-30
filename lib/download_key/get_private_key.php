<?php

/*
$mb_id = 회원아이디
$private_key = 지갑 키

이 파일 include 하고 그 위에 변수 $private_key 를 정의하고 값을 
진짜 private_key를 넣어주세요
*/

  if(strpos($_SERVER['HTTP_USER_AGENT'],'webview//1.0') !== false){ 
   echo "<script>App.getPrivateKey('$mb_id');</script>"; //디바이스에 저장되어있는 키를 안드로이드앱에서 버퍼리더로 읽기시도
  } 

echo "<script>  
var private_key;
function getPrivateKeyResult(param){ 
  private_key = param;
}

if(private_key == undefined || private_key == ''){
  private_key = '{$private_key}';
}

alert(private_key)
</script>";
?>
