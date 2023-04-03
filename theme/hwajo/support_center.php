<?php
include_once('./_common.php');
include_once(G5_THEME_PATH.'/_include/head.php');
include_once(G5_THEME_PATH.'/_include/wallet.php');
include_once(G5_THEME_PATH.'/_include/gnb.php');

if($is_admin){
	header('Location: /page.php?id=support_center.admin');
}
?>


	<script>

		var $idx='<?=$_GET['idx']?>';
		var topicOption = {
			0 : 'General',
			1 : 'Hacking',
			2 : 'Bonus',
			3 : 'Wallet',
			4 : 'Account'
		};
		var $selected;

		var $msg='<?=$_GET['msg']?>';
		if($msg){
			commonModal('Alert','<i class="fas fa-exclamation-triangle red"><br>'+$msg);
		}

		$(function() {
			$(document).on('click','.btn.send' ,function(e) {
				$('#ticketChildForm [name=idx]').val($(this).attr('idx'));
				$('#ticketChildForm [name=content]').val($(this).parents('.chat-input').find('.message').val());
				$('#ticketChildForm').append($(this).parents('.chat-input').find('.messageFile').clone());
				$('#ticketChildForm').submit();
			});

			$(document).on('keypress', function(event){
				if (event.which == '13') {
					event.preventDefault();
				}
			});

			// 댓글 펼치기
			$(document).on('click','.ticket-header' ,function(e) {
				$selected = $(this).next();
				$(this).toggleClass('active');


				$selected.toggleClass('active');
				getComment($(this).attr('idx'));

			});


			// 탭클릭
			$('.support-panels .support-tabs li').on('click', function(e) {
				$('.support-panels .support-tabs li').removeClass('active');
				$('.support-panels .panel').removeClass('active').hide();

				$(this).addClass('active');
				$('#' + $(this).attr('rel')).addClass('active').fadeIn(300);

				if($(this).attr('rel') == 'active-tickets'){
					// 액티브 티켓 선택
					$.get( "/util/support_center.ticket.php",{
						is_closed : 0
					}).done(function( data ) {
						// console.log(data);
						var vHtml = $('<div>');
						$.each(data, function( index, ticket ) {
							var row = $('#dup').clone();
							row.find('.ticket-header').attr('idx', ticket.idx);
							row.find('.topic').append('['+ topicOption[ticket.topic] +'] ');
							row.find('.subject').append(ticket.subject);
							row.find('.create_date').text(ticket.create_date);
							row.find('.btn.send').attr('idx', ticket.idx);
							row.find('.btn.cl').attr('idx', ticket.idx);
							vHtml.append(row.html());
						});
						$('#active-tickets').html(vHtml.html());

						if($idx){
							$('.ticket-header[idx='+$idx+']').trigger('click');
						}

					}).fail(function(e) {
						console.log( e );
					});
				}else if($(this).attr('rel') == 'closed-tickets'){
					// 클로즈드 티켓 선택
					$.get( "/util/support_center.ticket.php",{
						is_closed : 1
					}).done(function( data ) {
						var vHtml = $('<div>');
						$.each(data, function( index, ticket ) {
							var row = $('#dup').clone();
							row.find('.ticket-header').attr('idx', ticket.idx);
							row.find('.topic').append('['+ topicOption[ticket.topic] +'] ');
							row.find('.subject').append(ticket.subject);
							row.find('.create_date').text(ticket.create_date);
							row.find('.chat-input').remove();
							vHtml.append(row.html());
						});
						$('#closed-tickets').html(vHtml.html());
					}).fail(function(e) {
						console.log( e );
					});
				}
			});

			// submit ticket
			$('#ticket').on('click', function(e) {

				if($('#subject').val() != '' && $('#content').val() != ''){

					$('#ticketForm [name=lang]').val($('#lang').val());
					$('#ticketForm').submit();

				}else{
					commonModal('Alert','<i class="fas fa-exclamation-triangle red"><h4><br>Please fill in the details.</h4>',200);
				}
			});

			$(document).on('keydown','.message' ,function(e) {
				if(e.which == 13) {
					e.preventDefault();
					$(this).next().find('.send').trigger('click');
				}
			});

			// 티켓종료
			$(document).on('click','.btn.cl' ,function(e) {
				console.log("closed");
				$.ajax({
					url: '/util/support_center.ticket.php',
					type: 'PUT',
					data: {
						idx : $(this).attr('idx')
					},
					success: function(result) {
						$('.support-panels .support-tabs li[rel=active-tickets]').trigger('click');
						commonModal("Ticket Closed","Ticket Move Closed","80");

						$('#commonModal #closeModal').click(function () {
							location.reload();
						});


					}
				});
			});

			if($idx){
				$('.support-panels .support-tabs li[rel=active-tickets]').trigger('click');
			}
		});

		// 댓글 내용 가져오기
		function getComment(paramIdx){
			$selected.find('.chat').empty();
			$.get( "/util/support_center.ticket.child.php",{
				idx : paramIdx
			}).done(function( data ) {
				var vHtml = $('<div>');

				$.each(data.list , function( index, obj ) {
					var row = $('#dup2').clone();
					if(obj.mb_no == 1){ // 관리자
						row.find('.message').addClass('support-message');
						row.find('.name').text('FIJI Support');
					}else{
						row.find('.message').addClass('member-message');
						row.find('.name').text(obj.mb_id);
					}
					row.find('.content').text(obj.content);
					row.find('.time').text(obj.create_date);

					if(obj.bf_source){
						var btn = $('<a>');
						btn.attr('href','<?=G5_URL?>/bbs/download.php?bo_table=supportCenterChild&wr_id=' + obj.wr_id + '&no=' + obj.bf_no);
						btn.text(obj.bf_source);
						row.find('.message').append(btn);
					}
					vHtml.append(row.html());
				});

				if(data.file){
					// console.log(data.file);
					var btn = $('<a class="file_addon">');
					btn.attr('href','<?=G5_URL?>/bbs/download.php?bo_table=supportCenter&wr_id=' + data.file.wr_id + '&no=' + data.file.bf_no);
					btn.text(data.file.bf_source);
					vHtml.find('.message.member-message').last().append(btn);
				}

				$selected.find('.chat').append(vHtml.html());
				//$selected.css('height', $selected.prop('scrollHeight') + 'px');

			}).fail(function(e) {
				console.log( e );
			});
		}

		function FileSizeChk(param) {
			var File_Size = document.getElementById(param).files[0].size;

			if( Number(File_Size) >= 5242880){
				alert("File above than 5MB. try send support email : <?=$config['cf_admin_email']?>");
				$("#"+param).val("");
			}

		}

		function LoadImg(value) {
			if(value.files && value.files[0]) {
				var reader = new FileReader();
					reader.onload = function (e) {
						$('#LoadImg').attr('src', e.target.result);
					}
				reader.readAsDataURL(value.files[0]);
			}
		}


	</script>
<main>
	<section class="con90_wrap support_center">

	<div class="main-container dash_contents">
		<div id="body-wrapper" >

			<div class="support-container">

				<div class="support-panels">
					<ul class="support-tabs content-box">
						<li rel="open-new-ticket" class="active" >새 티켓 열기</li>
						<li rel="active-tickets" >활성화 티켓</li>
						<li rel="closed-tickets" >비활성화 티켓</li>
					</ul>

					<div id="open-new-ticket" class="container panel active">

						<form id="ticketForm" action ="/util/support_center.ticket.php" method="post" enctype="multipart/form-data" >
							<input type="hidden" name="lang" >
							<div class="input-group mb-3">
							  <div class="input-group-prepend">
								<label class="input-group-text" for="topic">주제 선택</label>
							  </div>
							  <select class="custom-select" name="topic" id="topic">
								<option value="0" selected >일반</option>
								<option value="1" >해킹</option>
								<option value="2" >보너스</option>
								<option value="3" >지갑</option>
								<option value="4" >계좌</option>
							  </select>
							</div>
							<div class="input-group mb-3">
							  <div class="input-group-prepend">
								<span class="input-group-text" id="basic-addon1"> 제목</span>
							  </div>
							  <input type="text" class="form-control" aria-label="Subject" aria-describedby="basic-addon1" name="subject" id="subject" placeholder="문의 제목 입력">
							</div>
							<div class="input-group mb-3">
								<div class="input-group-prepend">
									<span class="input-group-text" >무엇을 도와 드릴까요</span>
								</div>
								<textarea class="form-control" aria-label="With textarea" name="content" id="content" ></textarea>
							</div>
							<span style='font-size:12px;'>5MB 미만 jpg, png, pdf 파일만 첨부 가능합니다</span>
							<div class="input-group ">
								<input type="file" multiple class="form-control-file" onChange="FileSizeChk('addFile');"  id="addFile" name="bf_file[]" accept=".jpg, .png, .pdf" accept="image/*;capture=camera">
							</div>

							<div class="submit-button">
								<div class="btn wd blue font_white" id="ticket"> 티켓 제출</div>
							</div>
							<div class="email">
								<span> 파일 크기 5MB 이상 파일은 아래 이메일로 보내주세요.</span>
								<br> <a href="mailto:<?=$config['cf_admin_email']?>"><?=$config['cf_admin_email']?></a>
							</div>
						</form>
					</div>
					<div id="active-tickets" class="panel"></div>
					<div id="closed-tickets" class="panel"></div>
				</div>
			</div>
		</div>
	</div>

	<div style="display:none;" id="dup">
		<div class="ticket-header">
			<strong class="topic"></strong>
			<span class="ticket-title subject" ></span>
			<span class="ticket-time create_date"></span>
		</div>

		<div class="chat-box">
			<div class="chat">

			</div>

			<div class="chat-input">
				<div class="input-group mb-3">
					<input type="text" class="form-control message" placeholder="Message" aria-label="Message" aria-describedby="basic-addon2" data-i18n="[placeholder]support.메시지">
					<div class="input-group-append">
						<button class="btn btn-primary send" type="button" data-i18n='support.보내기'>보내기</button>
						<button class="btn btn-danger cl" type="button" data-i18n='support.닫기'>닫기</button>
					</div>
				</div>
				<div class="custom-file">
					<input type="file" class="custom-file-input messageFile"  multiplename="bf_file[]" onChange="FileSizeChk('add_messageFile');" id="add_messageFile" accept=".jpg, .png, .pdf" accept="image/*;capture=camera">
					<label class="custom-file-label" for="customFile" data-i18n="support.파일 선택 5MB 미만 jpg, png, pdf">Choose file ( 5MB limit, .jpg, .png, .pdf )</label>
				</div>
			</div>
		</div>
	</div>

	<div style="display:none;" id="dup2" >
		<div class="message">
			<span class="content"> </span><br>
			<p class="writer"><span class="name">V7Wallet Support</span> | <span class="time" >12:40 PM</span></p>
		</div>
	</div>

	<div style="display:none;" >
		<form id="ticketChildForm" action ="/util/support_center.ticket.child.php" method="post" enctype="multipart/form-data" >
			<input type="hidden" name="idx" >
			<input type="hidden" name="content" >
		</form>
	</div>


	<div class="gnb_dim"></div>

	</section>
</main>


	<script>
		$(function(){
			$(".top_title h3").html("<span >1:1문의사항</span>")
		});
	</script>

<? include_once(G5_THEME_PATH.'/_include/tail.php'); ?>
