<?php
// 회원가입축하 메일 (회원님께 발송)
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
?>
<!DOCTYPE html>
<html>
<head>
	<title>EMAIL VERIFICATION</title>
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
			<a href=""><img src="http://v7wallet.com/img/v7-wallet-logo.png"  alt="logo">
		</div></a>
		<h1 class="blue" style="text-align: center;color: rgb(0, 121, 211);"><i class="far fa-envelope"></i> PLEASE VERIFY YOUR EMAIL</h1>
		<p style="    font-size: 18px;    line-height: 1.3;">
			<br>
			Welcome to V7 Wallet!<br>
			<br>
			Email Authentication Code : 
			<strong><span style="color:red"><? echo $randomStr;?></span></strong>
			<br>
			<br>
			Thank you,<br>
			V7 Wallet Support
		</p>
	</div>

</body>
</html>