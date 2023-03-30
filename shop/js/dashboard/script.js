var menu_dropdown = document.getElementsByClassName('menu-dropdown');

for (var i = 0; i < menu_dropdown.length; i++) {
	menu_dropdown[i].onclick = function() {
		this.classList.toggle('is_open');

		var menu_item = this.nextElementSibling;

		if (menu_item.style.maxHeight) {
			menu_item.style.maxHeight = null;
		} else {
			menu_item.style.maxHeight = menu_item.scrollHeight + "px";
		}
	}
}

jQuery(document).ready(function($) {
  var alterClass = function() {
    var ww = document.body.clientWidth;
    if (ww >= 1000) {
      $('#side-menu').addClass('side-menu-open');
      $('#body-wrapper').addClass('nav-body-shift');
    } else if (ww < 1000) {
    	$('#side-menu').removeClass('side-menu-open');
    	$('#body-wrapper').removeClass('nav-body-shift');
    }
  };
  alterClass();
});

function toggleSideMenu() {
	document.getElementById('side-menu').classList.toggle('side-menu-open');
	document.getElementById('side-menu').classList.toggle('shadow');

	if (document.body.clientWidth >= 1000) {
		document.getElementById('body-wrapper').classList.toggle('nav-body-shift');		
	}
}