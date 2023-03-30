<?php
include_once('./_common.php');

if (isset($_SESSION['ss_mb_reg']))
	$mb = get_member($_SESSION['ss_mb_reg']);

// 회원정보가 없다면 초기 페이지로 이동
if (!$mb['mb_id'])
	goto_url(G5_URL);

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="shortcut icon" href="/favicon.png">
	<title>EMAIL VERIFICATION</title>

	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.0/normalize.css">
	<link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">
	
	<style>
		* {
			box-sizing: border-box;
			font-family: 'Raleway', sans-serif;
		}

		p {
			line-height: 1.3;
		}

		.blue {
			color: rgb(0, 121, 211);
		}

		.gray {
			color: rgb(124, 124, 124);
		}

		.purple {
			color: #53407A;
		}

		.sign-up-container {
			margin: 0 auto;
			padding: 30px;
			text-align: center;
			width: 650px;
		}

		.sign-up-container p {
			font-size: 18px;
		}

		.sign-up-container p a {
			color: rgb(0, 121, 211);
			text-decoration: none;
		}

		.sign-up-container h1 {
			text-align: center;
		}

		.form-brand {
			text-align: center;
		}

		.email {
			font-weight: bold;
		}
	</style>

	<script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script>
	<script>
		$(document).ready(function(){
			$('#resend').on('click',function(e) {
				$.ajax({
					url: 'register_result.resend.php',
					type: 'GET',
					data: {
						"mb_id": "<?php echo $mb['mb_id'] ?>",
						"mb_email": "<?php echo $mb['mb_email'] ?>"
					},
					success: function(result) {
						console.log(result);
					},
					error: function(e) {
						console.log(e);
					}
				});
			});
		});
	</script>
</head>
<body>

	<div class="sign-up-container">
		<div class="form-brand">
			<a href="/" ><img src="/new/images/logo.png" width="25" alt="pinnacle logo"></a>
		</div>
		<h1 class="blue"><i class="far fa-envelope"></i> PLEASE VERIFY YOUR EMAIL</h1>
		<p>
			Thank you for signing up. We’ve sent a confirmation email to:
		</p>
		<p class="email">
			<?php echo $mb['mb_email'] ?>
		</p>
		<p>
			Click the confirmation link in that email to begin using Pinnacle Mining.
		</p>
		<p>
			Didn’t receive the email? Check your Spam folder, it may have been caught by a filter. If you still don’t see it, you can <a href="#" id="resend" >resend the confirmation email.</a>
		</p>
		<br>
		<br>
		<p><a href="/">Back to Pinnacle Mining</a></p>
	</div>
</body>
</html>