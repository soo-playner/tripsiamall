var users = document.querySelectorAll('.user')

users.forEach(function(user) {
	user.addEventListener('click', function() {

		for(var i = 0; i < users.length; i++) {
			users[i].classList.remove('selected')
		}

		this.classList.add('selected')
	})
})

const links = document.querySelectorAll('#handle, #top-link, #about-link, #start-link, #package-link, #benefit-link, #successLink');

for (let i = 0; i < links.length; i++) {
  links[i].addEventListener('click', function(e) {
    const navUl = document.getElementById('nav-list')
    navUl.classList.toggle('showing')
  })
}