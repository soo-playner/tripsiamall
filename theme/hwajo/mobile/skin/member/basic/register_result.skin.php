<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$member_skin_url.'/style.css">', 0);
?>

<div id="reg_result" class="mbskin">

    <p>
      Congratulations  <strong><?php echo get_text($mb['mb_name']); ?></strong>, You just signed up in PinnacleMining.net<br>
    </p>

    <?php if ($config['cf_use_email_certify']) { ?>
    <p>
        A verification email has been sent to the email address on your account.<br>
        Check the email and verify the email to use the backoffice.
    </p>
    <div id="result_email">
        <span>아이디</span>
        <strong><?php echo $mb['mb_id'] ?></strong><br>
        <span>이메일 주소</span>
        <strong><?php echo $mb['mb_email'] ?></strong>
    </div>
    <p>
        If you entered a wrong email address, open a support ticket for a new email.
    </p>
    <?php } ?>

    <p>
        회원님의 비밀번호는 아무도 알 수 없는 암호화 코드로 저장되므로 안심하셔도 좋습니다.<br>
        When you forget username / password, you can recover them using your email.
    </p>

    <p>
        회원 탈퇴는 언제든지 가능하며 일정기간이 지난 후, 회원님의 정보는 삭제하고 있습니다.<br>
        감사합니다.
    </p>

    <div class="btn_confirm">
        <a href="<?php echo G5_URL ?>/" class="btn02">메인으로</a>
    </div>

</div>
