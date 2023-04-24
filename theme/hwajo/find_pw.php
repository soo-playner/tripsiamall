<?php
$menubar = 1;
include_once('./_common.php');
$title = '비밀번호 재설정';

include_once(G5_THEME_PATH . '/_include/head.php');
include_once(G5_THEME_PATH . '/_include/gnb.php');
$lang_visible = 1;
include_once(G5_THEME_PATH . '/_include/lang.php');


$rand_num = sprintf("%06d", rand(000000, 999999));
// echo $rand_num;
?>

<html>




<body class="bf-login">

  <div class="container mt-5 pt20">
    <div class="hp_form" id="hp_form">
      <input type="text" id="mb_id" class="b_radius_10 mb-2 border" placeholder="아이디를 입력해주세요">
      <input type="text" id="mb_email" class="b_radius_10 mb-2 border" placeholder="이메일을 입력해주세요">
      <input type="button" class="btn btn_wd btn--gray b_radius_10" id="hp_button" value="인증번호 받기">
    </div>

    <div class="notice-red" id="notice_phone" style="display:none;"></div>

    <div id="timer_auth" class="position-relative mt-4 mb-5">
      <h5 class="sub_title">
        <div>인증번호 입력</div>
        <div class="timer_down_wrap">
          <div class='timer-down' id='timer_down'>남은 시간 05:00</div>
        </div>
      </h5>

      <div class="auth_wrap mt-2">
        <input type="text" id='auth_number' class="b_radius_10 border" placeholder="이메일로 전송 받은 인증번호 입력" maxlength="6">
        <button class="btn input_btn input_btn2 main_btn" id="auth_number_confirm">확인</button>
      </div>
    </div>

    <div id="pw_form" style="margin-top: 24px">
      <input type="password" id='auth_pw' class="b_radius_10 border" placeholder='비밀번호 재설정' maxlength="12">
      <input type="password" id='re_auth_pw' class="b_radius_10 border" placeholder='비밀번호 재설정 확인' maxlength="12">
      <input type="button" class="btn btn_wd btn-agree btn--blue b_radius_10" id='confirm_pw' value="확인">
    </div>

    <div class="gnb_dim"></div>
  </div>

</body>

<style>
  .notice-red {
    color: red;
  }

  .top_title h3 {
    line-height: 20px;
    display: inline-block;
    width: auto;
    margin: 0 auto;
    padding-right: 13px;
    font-size: 15px !important;
  }

  .top_title {
    color: #000;
    text-align: center;
    box-sizing: border-box;
    padding: 15px 20px;
    /* box-shadow:0 1px 0px rgba(0,0,0,0.25) */
  }

  .sub_title {
    display: flex;
    width: 80%;
    justify-content: space-between
  }

  .timer_down_wrap .timer-down:after {
    content: "";
    -webkit-box-flex: 0.2;
    -ms-flex: 0.2;
    flex: 0.2;
    margin: 0 auto 0 -2px;
    padding: 0.375rem 0.75rem;
  }

  .auth_wrap {
    display: flex;
  }

  .auth_wrap #auth_number {
    flex: 0.8;
  }

  .auth_wrap #auth_number_confirm {
    margin: 0 auto 0 5px;
    font-size: 0.7rem !important;
    -webkit-box-flex: 0.2;
    -ms-flex: 0.2;
    flex: 0.2;
    height: 44px;
    background-color: #E6ECF3;
  }
</style>

</html>
<script>
  $(document).ready(function() {
    if ($('#wrapper').parent().hasClass('bf-login') == true) {
      $('#wrapper').css('margin-left', '0px').css('color', '#000');

    }
  });
</script>
<script>
  $(".top_title h3").html("<span>비밀번호 재설정</span>");

  $('#timer_auth').hide();
  $('#pw_form').hide();

  let count_down = (condition = "") => {
    let time = 299;
    let min = "";
    let sec = "";

    if (condition == "readonly") $("#type").attr("readonly", true);

    $("#mb_hp").attr("readonly", true);
    $('#hp_button').attr('disabled', true);
    $('#auth_wrap').show();
    $('#timer_down').css('color', 'red');

    let _count_down = setInterval(function() {
      min = parseInt(time / 60);
      sec = time % 60;
      if (sec < 10) {
        sec_temp = "0";
      } else {
        sec_temp = "";
      }
      document.getElementById('timer_down').innerHTML = "남은 시간 : 0" + min + ":" + sec_temp + sec;
      time--;

      if (time < 0) {
        clearInterval(_count_down);
        dialogModal("이메일 인증", "인증 시간이 초과되었습니다.", "failed");

        $('#modal_return_back').on('click', function() {
          location.reload();
        });

      }
    }, 1000);

    return _count_down;
  }

  $('#hp_button').click(function() {

    if ($('#mb_id').val() == "" || $('#mb_email').val() == "") {
      dialogModal("", "아이디 혹은 이메일을 입력해주세요.", "find_warning");
      return false;
    }

    $.ajax({

      url: "/mail/find_pw_mail.php",
      type: "POST",
      dataType: "json",
      cache: false,
      data: {
        mb_id: $('#mb_id').val(),
        mb_email: $('#mb_email').val()
      },
      complete: function(res) {
        var check = res.hasOwnProperty("responseJSON");

        if (check) {
          dialogModal("", "입력하신 정보에 해당되는 회원이 없습니다.", "find_warning");
          return false;
        } else {
          $("#mb_id").attr("readonly", true);
          $("#mb_email").attr("readonly", true);
          $('#hp_button').attr('disabled', true);
          $('#timer_auth').show();
          var count = count_down();
          dialogModal("", "입력하신 이메일로 인증번호가 전송되었습니다. <br>이메일 확인 후 인증번호를 입력해주세요.", "find_success");

          $('#auth_number_confirm').on('click', function() {

            let auth_number = $('#auth_number').val();
            if (auth_number.length == 6) {

              $.ajax({
                url: '/util/find_pw_proc.php',
                type: "POST",
                dataType: "json",
                cache: false,
                data: {
                  type : "auth_number_check",
                  mb_id: $('#mb_id').val(),
                  mb_email: $('#mb_email').val(),
                  auth_number: auth_number
                },
                success: (res) => {
                  if (res.code == "200") {
                    clearInterval(count);
                    dialogModal('', res.msg, 'success');
                    $('#modal_return_url').click(function() {
                      $('#timer_auth').hide();
                      $('#auth_number_confirm').attr('disabled', true);
                      $('#auth_number').attr("readonly", true);
                      $('#pw_form').show();
                    })
                  } else {
                    dialogModal('', res.msg, 'find_warning');
                  }
                }
              })
            } else {
              dialogModal("", "인증번호를 정확히 입력해주세요.", "find_warning");
              return false;
            }
          });
        }
      }
    });
  });


  $('#confirm_pw').click(function() {
    var auth_pw = $('#auth_pw').val();
    var re_auth_pw = $('#re_auth_pw').val();
    var blank = /[\s]/g;
    var pattern = /^(?=.*[A-Za-z])(?=.*\d)(?=.*[$@$!%*#?&])[A-Za-z\d$@$!%*#?&]{4,12}$/;
    let auth_number = $('#auth_number').val();

    if (auth_pw == "" || re_auth_pw == "") {
      dialogModal('','비밀번호를 다시 입력해주세요.','find_warning');
      return false;
    }

    if ((auth_pw.length < 4 || auth_pw.length > 12 || !pattern.test(auth_pw))) {
      dialogModal('','영문+숫자+특수문자 조합을 사용하여 최소 4자 이상 12자 이하 입력해주세요.','find_warning');
      return false;
    }

    if (auth_pw != re_auth_pw) {
      dialogModal('','비밀번호를 한번 더 입력해주세요.','find_warning');
      return false;
    }

    if (blank.test(auth_pw) == true) {
      dialogModal('','비밀번호에 공백이 포함되어 있습니다.','find_warning');
      return false;
    }

    $.ajax({
      url: "/util/find_pw_proc.php",
      type: "POST",
      dataType: "json",
      cache: false,
      data: {
        type: "change_password",
        auth_pw: auth_pw,
        mb_email: $('#mb_email').val(),
        mb_id: $('#mb_id').val(),
        auth_number: auth_number
      },
      success: function(res) {
        if (res.code == "200") {
          dialogModal('비밀번호 재설정', '비밀번호가 재설정되었습니다.', 'success');
          $('#modal_return_url').click(function() {
            location.replace('/bbs/login_pw.php');
          })
        } else {
          dialogModal('', res.msg, 'find_warning');
        }
      }
    })


  })
</script>