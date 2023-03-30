$(function(){
	
	$('.menu01').mouseenter(function() {$(this).find('img').attr('src','img/adm/icon01_on.png') });
	$('.menu01').mouseleave(function() {$(this).find('img').attr('src','img/adm/icon01.png') });	
	
	$('.menu02').mouseenter(function() {$(this).find('img').attr('src','img/adm/icon02_on.png') });
	$('.menu02').mouseleave(function() {$(this).find('img').attr('src','img/adm/icon02.png') });	
	
	$('.menu03').mouseenter(function() {$(this).find('img').attr('src','img/adm/icon03_on.png') });
	$('.menu03').mouseleave(function() {$(this).find('img').attr('src','img/adm/icon03.png') });	
	
	$('.menu04').mouseenter(function() {$(this).find('img').attr('src','img/adm/icon04_on.png') });
	$('.menu04').mouseleave(function() {$(this).find('img').attr('src','img/adm/icon04.png') });	
	
	$('.menu05').mouseenter(function() {$(this).find('img').attr('src','img/adm/icon05_on.png') });
	$('.menu05').mouseleave(function() {$(this).find('img').attr('src','img/adm/icon05.png') });	
	
	$('.menu06').mouseenter(function() {$(this).find('img').attr('src','img/adm/icon06_on.png') });
	$('.menu06').mouseleave(function() {$(this).find('img').attr('src','img/adm/icon06.png') });	
	
	$('.menu07').mouseenter(function() {$(this).find('img').attr('src','img/adm/icon07_on.png') });
	$('.menu07').mouseleave(function() {$(this).find('img').attr('src','img/adm/icon07.png') });	
	
	$('.menu08').mouseenter(function() {$(this).find('img').attr('src','img/adm/icon08_on.png') });
	$('.menu08').mouseleave(function() {$(this).find('img').attr('src','img/adm/icon08.png') });	
	
	$('.menu09').mouseenter(function() {$(this).find('img').attr('src','img/adm/icon09_on.png') });
	$('.menu09').mouseleave(function() {$(this).find('img').attr('src','img/adm/icon09.png') });	
	
	$('.menu10').mouseenter(function() {$(this).find('img').attr('src','img/adm/icon10_on.png') });
	$('.menu10').mouseleave(function() {$(this).find('img').attr('src','img/adm/icon10.png') });	
	
	//$(".side-bar .drop_menu li i").on("click", function() {
			//if ($(this).parent().hasClass("active")) {
			 // $(this).parent().removeClass("active");
			 // $(this).parent().next(".side-bar .drop_menu ul").slideUp(300);
			//} else { $(".side-bar .drop_menu li i").removeClass("active");
		 // $(".side-bar .drop_menu li").removeClass("active");
		 // $(this).parent().addClass("active");
		 // $(".side-bar .drop_menu ul").slideUp(300);
		 // $(this).parent().next(".side-bar .drop_menu ul").slideDown(300);
		//}
  	//});
  	
  	$(".side-bar .drop_menu li").on("click", function() {
			if ($(this).hasClass("active")) {
			  $(this).removeClass("active");
			  $(this).next(".side-bar .drop_menu ul").slideUp(300);
			} else { $(".side-bar .drop_menu li").removeClass("active");
		  //$(".side-bar .drop_menu li").removeClass("active");
		  $(this).addClass("active");
		  $(".side-bar .drop_menu ul").slideUp(300);
		  $(this).next(".side-bar .drop_menu ul").slideDown(300);
		}
  	});
	
});
