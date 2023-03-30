<?php
$sub_menu = "800200";
include_once('./_common.php');
include_once(G5_THEME_PATH . '/_include/wallet.php');

auth_check($auth[$sub_menu], 'r');

$g5['title'] = '메세지 템플릿 관리';
include_once('../admin.head.php');

$list = sql_query("SELECT * from app_msg");

?>

<style>
    .frm_input{width:100%;min-width:100px;}
    .frm_input.content{min-height:40px;}
    .local_ov a{color:green;font-weight:600;}
    .local_ov .small{font-size:11px;}
    .local_ov .strong{font-weight:600}
    .local_ov .blue{color:blue}

    .pollding_btn{padding:5px 10px;border-radius:0 !important;}
</style>

<link rel="stylesheet" href="<?= G5_THEME_URL ?>/css/scss/custom.css">
<link rel="stylesheet" href="../css/scss/admin_custom.css">

<div class="local_ov01 local_ov">
    <?php echo $listall ?>
    <p class='guide'>
        <!-- -이모지 : 📊 📈 📉 🎁 <br> -->
        <span class='small strong'>딥링크 : 안드로이드폰에서 링크 클릭시 앱설치화면으로 이동 / 이미앱설치된경우 해당 앱 바로가기 </span><br>
        - 회원가입 딥링크 : <a href="https://zetabyte.page.link/enroll">https://zetabyte.page.link/enroll</a><br>
        - 마이풀 딥링크 : <a href="https://zetabyte.page.link/mypool">https://zetabyte.page.link/mypool</a> (로그인 세션 살아있는경우만 페이지까지 도달)
	</p>
    <button type='button' class='btn pollding_btn'>접어두기</button>
</div>

<form name="msg_form" id="msg_form" action="./fcm_msg_proc.php" onsubmit="return fmemberlist_submit(this);" method="post">
<div class="tbl_head02 tbl_wrap">
    <table >
    <caption><?php echo $g5['title']; ?> 목록</caption>
    <thead>
	<p> 메세지 템플릿 </p>
    <tr>
        <th scope="col" width="30px" id="mb_list_chk">
            <label for="chkall" class="sound_only">전체선택</label>
            <input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all(this.form)">
        </th>
        <th scope="col" width="100px">메세지명</th>
        <th scope="col" width="200px">타이틀</th>	
        <th scope="col" width="250px">내용</th>
        <th scope="col" width="80px">이미지첨부</th>
        <th scope="col" width="30px">사용유무</th>
    </tr>
    </thead>

    <tbody>
    <?for($i=0; $row=sql_fetch_array($list); $i++){?>
    
    <tr class='<?if($i == 0){echo 'first';}?>'>
   
    <td style="text-align:center">
        <input type="hidden" name="no[]" value="<?=$row['no']?>">
        <input type="checkbox" name="chk[]" value="<?= $i ?>" id="chk_<?= $i ?>">
    </td>
    <td style="text-align:center"><input class='frm_input' name="name[]"  value="<?=$row['name']?>"></input></td>
    <td style="text-align:center;width:250px;"><input class='frm_input' name="title[]"  value="<?=$row['title']?>"></input></td>
    <td style="text-align:center"><textarea class='frm_input content' name="contents[]" ><?=$row['contents']?></textarea></td>
    <td style="text-align:center"><input class='frm_input' name="image[]"  value="<?=$row['images']?>"></input></td>
    <td style="text-align:center"><input type='checkbox' class='checkbox' name='used[]' <?php echo $row['used'] > 0 ?'checked':''; ?>></td>
    </tr>
    <?}?>
    </tbody>
    
</table>

    <div style='margin-top:20px;'>
        <input style="align:center;padding:15px 30px;background:cornflowerblue;" type="submit" name='func' class="btn btn_confirm btn_submit" value="저장하기" id="com_send"></input>
        <input style="align:center;padding:15px 30px;" type="submit" name='func' class="btn btn_confirm btn_submit" value="삭제하기" id="com_send"></input>
        <input style="align:center;padding:15px 30px;background:#FECE00;color:black" type="button" class="btn btn_confirm" value="새 템플릿 등록" id="msg_regist"></input>
    </div>
</form>
</div>

<script>
    $('.pollding_btn').on('click',function(){
        $(this).parent().find('.guide').toggle();    
    });

    $('#msg_regist').on('click',function(){
        $.ajax({
			type: "POST",
			url: "./fcm_msg_proc.php",
            data: {
                func : 'w'
            },
			cache: false,
            dataType: 'json',
            
			success: function(res) {
				if(res.result == 'success'){
                    location.reload();
                }
			}
		});

    });
    
</script>
<?php
include_once('../admin.tail.php');
?>
