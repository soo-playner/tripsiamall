<?php
include_once('./_common.php');

?>
<!doctype html>
<html lang="ko">
<head>
	<style>
	.top-nav-one{display:none;}
	.top-nav-two{display:none;}
	#side-menu{display:none;}
	</style>

	<script>
		var targetUrl = '<?=$targetUrl?>';
		$(function() {
			commonModal('Alert','<i class="fas fa-exclamation-triangle red"><h4><?=$msg?></h4>');
			//commonModal('zxc','<i class="far fa-check-circle blue"><h4>Ticket Submission Succeeded</h4>',400);
			$('#commonModal .modal-footer .btn, #commonModal .close').on('click', function(e) {
				if(targetUrl != ''){
					location.href = targetUrl;
				}else{
					history.back();
				}
			});
		});

		function commonModal(title, htmlBody, bodyHeight){
			$('#commonModal').modal('show');
			$('#commonModal .modal-header .modal-title').html(title);
			$('#commonModal .modal-body').html(htmlBody);
			if(bodyHeight){
				$('#commonModal .modal-body').css('height',bodyHeight+'px');
			} 
		}
	</script>
</head>
<body>
	<div class="modal fade" id="commonModal" tabindex="-1" role="dialog" data-backdrop="false" aria-labelledby="saveSettingsCenterTitle" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" ></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body"></div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary " data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</body>
</html>
