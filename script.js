document.addEventListener('DOMContentLoaded', function() {
	const registerBtn = document.querySelector('.register'); // Select the Register button
  
	if (registerBtn) { // Check if the button is found
	  registerBtn.addEventListener('click', function(event) {
		event.preventDefault(); // Prevent default link behavior
  
		// Redirect to login.html
		window.location.href = 'user.html';
	  });
	} else {
	  console.error('Register button not found.'); // Log an error if the button is not found
	}
  });
  