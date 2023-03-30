<?php
$sub_menu = '900110';
include_once('./_common.php');
include_once(G5_EDITOR_LIB);
auth_check($auth[$sub_menu], "r");


$g5['title'] = '서비스 점검 사용 관리';
include_once (G5_ADMIN_PATH.'/admin.head.php');


$sql = " select * from maintenance";
$nw = sql_fetch($sql);

/*
$nw['use'] = 'both';
$nw['title'] = 24;
$nw['contents']   = 10;
*/

include_once (G5_ADMIN_PATH.'/admin.head.php');
?>

<form name="frmnewwin" action="./admin.sub.maintenance_proc.php" onsubmit="return frmnewwin_check(this);" method="post">
<input type="hidden" name="w" value="<?php echo $w; ?>">

<div class="local_desc01 local_desc">
    <p>초기화면 접속 시 자동으로 점검 팝업을 설정합니다.<br>
		설정 사용시 초기화면 로그인/회원가입은 작동하지 않으며 관리자를 제외한 전 회원이 로그아웃됩니다.<br>
		점검중일때 관리자 강제접속 주소 :
		 접속가능합니다.
	</p>
</div>

<style>
@import url('https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css');

[type="checkbox"]:not(:checked),
[type="checkbox"]:checked {
  position: absolute; 
  left: -9999px;
}
[type="checkbox"]:not(:checked) + label,
[type="checkbox"]:checked + label {
  position: relative;
  padding-left: 95px;
  cursor: pointer;
  display:inline-block;
  height:32px;
}
[type="checkbox"]:not(:checked) + label:before,
[type="checkbox"]:checked + label:before,
[type="checkbox"]:not(:checked) + label:after,
[type="checkbox"]:checked + label:after {
  content: '';
  position: absolute;
}
[type="checkbox"]:not(:checked) + label:before,
[type="checkbox"]:checked + label:before {
  left: 0; top: 0;
  width: 80px; height: 30px;
  background: #DDDDDD;
  border-radius: 6px;
  transition: background-color .2s;
}
[type="checkbox"]:not(:checked) + label:after,
[type="checkbox"]:checked + label:after {
  width: 30px; height: 30px;
  transition: all .2s;
  border-radius: 6px 0 0 6px;
  background: #7F8C9A;
  top: 0; left: 0;
}

/* on checked */
[type="checkbox"]:checked + label:before {
  background:#34495E; 
}
[type="checkbox"]:checked + label:after {
  background: #39D2B4;
  top: 0; left: 51px;
  border-radius: 0 6px 6px 0;
}

[type="checkbox"]:checked + label .ui,
[type="checkbox"]:not(:checked) + label .ui:before,
[type="checkbox"]:checked + label .ui:after {
  position: absolute;
  left: 6px;
  width: 65px;
  border-radius: 15px;
  font-size: 14px;
  font-weight: bold;
  line-height: 22px;
  transition: all .2s;
}

[type="checkbox"]:not(:checked) + label .ui:before {
  font-family: 'FontAwesome';
  content: "\f00d";
  left: 46px;
  margin-top: 3px;
}
[type="checkbox"]:checked + label .ui:after {
  font-family: 'FontAwesome';
  content: "\f00c";
  color: #39D2B4;
  margin-top: 3px;
  left: 12px;
}
[type="checkbox"]:focus + label:before {
  border: 0; outline: 0;
  box-sizing: border-box;
}
</style>


<div class="tbl_frm01 tbl_wrap">
    <table>
    <caption><?php echo $g5['title']; ?></caption>
    <colgroup>
        <col class="grid_4">
        <col>
    </colgroup>
    <tbody>
    
	<tr>
        <th scope="row"><label for="useFn">임시 점검 사용<strong class="sound_only"> 필수</strong></label></th>
        <td>
            <p style="padding:0;">
			<input type="checkbox" id="useFn" name="useFn" <?if($nw['nw_use'] == 'Y') {echo "checked";}?>>
				<label for="useFn" style=""><span class="ui"></span><span class="txt">사용설정</span></label>
			</p>
        </td>
    </tr>

    <tr>
        <th scope="row"><label for="nw_subject">점검 제목<strong class="sound_only"> 필수</strong></label></th>
        <td >
            <input type="text" name="nw_subject" value="<?php echo stripslashes($nw['nw_subject']) ?>" id="nw_subject" required class="frm_input required" size="100">
        </td>
    </tr>

    <tr>
        <th scope="row"><label for="nw_content">내용</label></th>
        <td><?php echo editor_html('nw_content', get_text($nw['nw_contents_html'], 0)); ?></td>
    </tr>
    </tbody>
    </table>
</div>

<div class="btn_confirm01 btn_confirm">
    <input type="submit" value="확인" class="btn_submit" accesskey="s">

</div>
</form>

<script>

$(document).ready(function(){
	$('#useFn').on('click',function(){

		if($('#useFn').is(":checked")){
			$('.txt').html('사용함');
		}else{
			$('.txt').html('사용안함');
		}

	});
});

function frmnewwin_check(f)
{
		
    errmsg = "";
    errfld = "";

    <?php echo get_editor_js('nw_content'); ?>

    check_field(f.nw_subject, "제목을 입력하세요.");


	if ($('#useFn').is(":checked")) {
		$('#useFn').val('Y');
	} else {
		$('#useFn').val('N');
	}
	
	//console.log($('#useFn').is(":checked"));
	f.useFn = $('#useFn').val();


    if (errmsg != "") {
        alert(errmsg);
        errfld.focus();
        return false;
    }
    return true;
}
</script>

<?php
include_once (G5_ADMIN_PATH.'/admin.tail.php');
?>
