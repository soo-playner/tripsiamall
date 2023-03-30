<?php
include_once('./_common.php');
include_once(G5_THEME_PATH.'/_include/head.php');
include_once(G5_THEME_PATH.'/_include/gnb.php');

if(!$is_admin){
	header('Location: /page.php?id=support_center');
}
?>

	<script>
		var topicOption = {
			0 : 'General',
			1 : 'Hacking',
			2 : 'Bonus',
			3 : 'Wallet',
			4 : 'Account'
		};

		$(function() {

			// 댓글 펼치기
			$(document).on('click','.ticket-header' ,function(e) {
				$selected = $(this).next();
				$(this).toggleClass('active');


				$selected.toggleClass('active');
				getComment($(this).attr('idx'));

			});

			// 코멘트 달기
			$(document).on('click','.btn.send' ,function(e) {

				$('#ticketChildForm [name=idx]').val($(this).attr('idx'));
				$('#ticketChildForm [name=content]').val($(this).parents('.chat-input').find('.message').val());
				$('#ticketChildForm').append($(this).parents('.chat-input').find('.messageFile').clone());
				$('#ticketChildForm').submit();

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

			$('.support-panels .support-tabs li').on('click', function(e) {
				$('.support-panels .support-tabs li').removeClass('active');
				$('.support-panels .panel').removeClass('active').hide();

				$(this).addClass('active');
				$('#' + $(this).attr('rel')).addClass('active').fadeIn(300);

				if($(this).attr('rel') == 'active-tickets'){
					$.get( "/util/support_center.ticket.php",{
						is_closed : 0
					}).done(function( data ) {
						// console.log(data);
						makeList('#active-tickets',data);
					});
				}else if($(this).attr('rel') == 'closed-tickets'){
					$.get( "/util/support_center.ticket.php",{
						is_closed : 1
					}).done(function( data ) {
						makeList('#closed-tickets',data);
					});
				}else if($(this).attr('rel') == 'answered-tickets'){
					$.get( "/util/support_center.ticket.php",{
						is_closed : 1,
						is_answer : 1
					}).done(function( data ) {
						makeList('#answered-tickets > .list',data);
					});
				}
			});

			$('.support-panels .support-tabs li[rel=active-tickets]').trigger('click');
		});

		function makeList(tabId, data){
			var vHtml = $('<div>');
			$.each(data, function( index, ticket ) {
				var row = $('#dup').clone();
				row.find('.ticket-header').attr('idx', ticket.idx);
				row.find('.topic').append('['+ topicOption[ticket.topic] +'] ');
				row.find('.subject').append(ticket.subject);
				row.find('.create_date').text(ticket.create_date);

				if(Number(ticket.is_closed)){
					row.find('.ticket-header').addClass('closed');
					row.find('.chat-input').remove();
				}else{
					row.find('.btn.send').attr('idx', ticket.idx);
					row.find('.btn.cl').attr('idx', ticket.idx);
				}

				vHtml.append(row.html());
			});
			$(tabId).html(vHtml.html());
		}

		function getComment(paramIdx){
			$selected.find('.chat').empty();
			$selected.find('.chat-input .message').val('');
			$.get( "/util/support_center.ticket.child.php",{
				idx : paramIdx
			}).done(function( data ) {
				// console.log(data);
				var vHtml = $('<div>');
				$.each(data.list, function( index, obj ) {
					var row = $('#dup2').clone();
					if(obj.mb_no == 1){ // 관리자
						row.find('.message').addClass('support-message');
						row.find('.name').text('Support Admin');
					}else{
						row.find('.message').addClass('member-message');
						row.find('.name').text(obj.mb_id );
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
			}).fail(function(e) {
				console.log( e );
			});
		}

    </script>

<main>
	<section class="con90_wrap">

		<div class="main-container dash_contents">
			<div id="body-wrapper" >

				<div class="support-container">
					<div class="support-panels">
						<ul class="support-tabs content-box">
						<li rel="active-tickets" data-i18n='support.활성화 티켓'>Active Tickets</li>
							<li rel="answered-tickets" data-i18n='support.답변 티켓'>Answered Tickets</li>
							<li rel="closed-tickets" data-i18n='support.비활성화 티켓'>Closed Tickets</li>
						</ul>
						<div id="active-tickets" class="container panel active"></div>
						<div id="answered-tickets" class="container panel">
							<div class="list"></div>
						</div>
						<div id="closed-tickets" class="container panel"></div>
					</div>
				</div>
			</div>
		</div>

		<div style="display:none;" id="dup">
			<div class="ticket-header">
				<div>
					<strong class="topic"></strong>
					<span class="ticket-title subject" ></span>
				</div>
				<span class="ticket-time create_date">12:34 PM</span>
			</div>

			<div class="chat-box">
				<div class="chat">

				</div>

				<div class="chat-input">
					<div class="input-group mb-3">
						<input type="text" class="form-control message" placeholder="Message" aria-label="Message" aria-describedby="basic-addon2" >
						<div class="input-group-append">
							<button class="btn btn-primary send" type="button">Send</button>
							<button class="btn btn-danger cl" type="button">close</button>
						</div>
					</div>
					<div class="custom-file">
						<input type="file" class="custom-file-input messageFile" name="bf_file[]" accept=".jpg, .png, .pdf" >
						<label style="font-size: 12px;" class="custom-file-label" for="customFile">Choose file ( 5MB limit, .jpg, .png, .pdf )</label>
					</div>
				</div>
			</div>
		</div>

		<div style="display:none;" id="dup2" >
			<div class="message">
				<span class="content"> </span><br>
				<p class="writer"><span class="name">Support</span> | <span class="time" >12:40 PM</span></p>
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
