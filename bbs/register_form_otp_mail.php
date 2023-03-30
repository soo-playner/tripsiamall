<?php
// 회원가입축하 메일 (회원님께 발송)
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
?>
<!DOCTYPE html>
<html>
<head>
	<title>OTP Auth-Code VERIFICATION</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.0/normalize.css">
	<link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">
	
</head>
<body style="">

	<div class="sign-up-container" style="padding: 30px;text-align: center;width: 650px;box-sizing: border-box;font-family: 'Raleway', sans-serif">
		<div class="form-brand" style="text-align: center;">
			<a href="http://goldentreeglobal.io"><img src="http://goldentreeglobal.io/ETBC/images/logo_164_34.png"  alt="ETBC logo">
		</div></a>
		<h1 class="blue" style="text-align: center;color: rgb(0, 121, 211);"><i class="far fa-envelope"></i> WITHDRAWAL EOS OTP CODE</h1>
		<p style="    font-size: 18px;    line-height: 1.3;">
			<br>
			Please Check and verification below code<br>
			<br>
			EOS Withdrawal OTP Authentication Code : 
			<strong><span style="color:red"><? echo $randomStr;?></span></strong>
			<br>
			<br>
			Thank you,<br>
			EOS TEAM BLOCK CHAIN Support
		</p>
	</div>

</body>
</html>