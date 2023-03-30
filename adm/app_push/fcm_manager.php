<?php
$sub_menu = '100630';

include_once('./_common.php');
include_once(G5_LIB_PATH.'/fcm_push/push.php');

// setPushData("푸시연동테스트5", "푸시연동테스트6", 'evJZexK4xMo:APA91bErxxD6ksMw-7Q7825-pmonZ0WYN1zHZKwv_Qc1yBC1FrT_QKoCinBQnB4shsJsK3R7tTxIJOhC-pd6c3auoqt27ZoBjgWrYp_nH68BwDT0hd2u3XMUJxvcRU6BqGocR2WlbklZ','https://origin.dfineglobal.com/img/marker.png');

$g5['title'] = "푸시(FCM) 연동 ";

include_once(G5_ADMIN_PATH.'/admin.head.php');

// 컬럼추가
$has_fcm_token = sql_query("SELECT fcm_token from g5_member");

if(!$has_fcm_token){
    $add_fcm_token = "ALTER TABLE g5_member ADD `fcm_token` varchar(255) NOT NULL";
    $add_result = sql_query($add_fcm_token);

    if($add_result){
        $add_fcm_config = "ALTER TABLE g5_config ADD `cf_fcm_api_key` varchar(255) NOT NULL";
        $add_fcm_config_result = sql_query($add_fcm_config);
    }
}
?>

<style>
    .frm_input{width:100%;height:60px}
    .subrow{margin:20px 0}
</style>
<div class="pop_bg"></div>

<!-- 여기 아래부터 모든 HTML 요소 구성 시작 -->
<div class="first-container">
<div class="tbl_head01 tbl_wrap">
		<table>
			<thead>
				<tr class="bg0">
					<th scope="col">FIREBASE API KEY</th>
					<th scope="col">KEY</th>
					<th scope="col">관리</th>
				</tr>
			</thead>

			<tbody>
				<tr class="bg0">
					<td class="td_left" style="width: 200px;">FIREBASE API</td>
					<td><textArea class="required frm_input" id="fcm_api_key" name="fcm_api_key" type="text" placeholder="API Key Input."><?=$config['cf_fcm_api_key']?></textArea></td>
					<td class="td_mng td_mng_s"><button type="submit" name="button" class="btn_03 btn" onclick="api_key_save();">저장</button></td>
				</tr>
			</tbody>
		</table>

		<!-- <div class="subrow">
			<a href="https://firebase.google.com/" target="_blank"><u class="sdklink">https://firebase.google.com/</u> API 토큰 값을 받으세요!</a>
		</div> -->
	</div>
</div>
<!-- 여기 아래부터 모든 HTML 요소 구성 끝 -->

<?php
include_once(G5_ADMIN_PATH.'/admin.tail.php');
?>

<script>

function api_key_save(){	
	$.ajax({
		type : 'POST',
		url : '/adm/app_push/fcm_save.php',
		data : {
			fcm_api_key : $('#fcm_api_key').val()
		},
		dataType : 'json',
		success : function(data){
			if(data.code == '0000'){
				alert('저장되었습니다.');
			}else{
                alert('상태이상');
				return;
            }
			
		},
		error: function(error){
			alert('error');
		}
	});
}
$('.pop_close,.pop_bg').click(function(){
	location.reload(true);
});
</script>
