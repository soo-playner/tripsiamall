$(function(){
	$(document).ready(function(){
	  $(".mobie_menu").click(function(){
	    $(this).toggleClass("is-active");
	    $('header .menu').slideToggle();
	  });
	});	
	
	//backtop
  $(document).ready(function(){ 

	//$('#back-top').hide(); 

	$(function () { 
	   $(window).scroll(function () { 
		   if ($(this).scrollTop() > 100) { 

		   $('.backtop').addClass("show");  
		 } else { 
			 $('.backtop').removeClass("show"); 
			 
		} }); 

		$('#backtop').click(function () { $('body,html').animate({ scrollTop: 0 }, 800); return false; }); }); 
	});
	
});
