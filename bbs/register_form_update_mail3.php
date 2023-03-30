<?php
// E-mail 수정시 인증 메일 (회원님께 발송)
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
?>

<!doctype html>
<html lang="ko">
<head>
<meta charset="utf-8">
<title>Pinnacle Verification Email</title>
</head>

<body>
    <div style="margin:30px auto;width:600px;border:10px solid #f7f7f7">
        <div style="border:1px solid #dedede">
            <div style="padding:10px 30px 10px;background:#f7f7f7;text-align:left">
                <a href="<?php echo G5_URL ?>" target="_blank">
                    <img  src="<?php echo G5_URL; ?>/theme/basic/img/main_logo.png" />
                </a>
            </div>
            <p style="padding:10px 30px 30px;min-height:120px;height:auto !important;height:200px;border-bottom:1px solid #eee;word-break: break-word;">
                <?php if ($first_name) { ?>
                    Hi <b><?php echo $first_name." ".$last_name; ?> </b>
                <?php } else {?>
                    Hi <b><?php echo $mb_id ?></b>
                <?php }?>
                <br><br>
                Please click the link below to confirm your new email address<br>
                <a href="<?php echo $certify_href ?>" target="_blank" style="display:block;padding:15px 0;background-color:#ff7f00;color:#fff;text-align:center;font-size:16px;width:500px;font-weight:bold;text-decoration:none;margin:10px 0;">Confirm Email</a>
                Think you received this email by mistake? Don’t worry - just shoot us an email to support@pinnaclemining.net<br>
                Cheers, <br>
                The Pinnacle Mining Team
            </p>
        </div>
    </div>
</body>
</html>
