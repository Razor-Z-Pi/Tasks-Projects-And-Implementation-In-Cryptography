document.addEventListener('DOMContentLoaded', function() {
    const registerForm = document.getElementById('registerForm');
    const loginForm = document.getElementById('loginForm');
    
    if (registerForm) {
        registerForm.addEventListener('submit', function(e) {
            const password = document.getElementById('reg-password').value;
            const username = document.getElementById('reg-username').value;
            
            if (password.length < 6) {
                e.preventDefault();
                alert('Пароль должен содержать не менее 6 символов');
                return;
            }
            
            if (username.length < 2) {
                e.preventDefault();
                alert('Имя пользователя должно содержать не менее 2 символов');
                return;
            }
        });
    }
    
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            const password = document.getElementById('login-password').value;
            
            if (password.length < 6) {
                e.preventDefault();
                alert('Пароль должен содержать не менее 6 символов');
                return;
            }
        });
    }
});

function show_hide_register(target) {
    let input_register = document.getElementById('reg-password');

    if (input_register.getAttribute('type') == 'password') {
		target.classList.add('view');
		input_register.setAttribute('type', 'text');
	} else {
		target.classList.remove('view');
		input_register.setAttribute('type', 'password');
	}

    return false;
}

function show_hide_password(target) {
	let input = document.getElementById('login-password');
    
	if (input.getAttribute('type') == 'password') {
		target.classList.add('view');
		input.setAttribute('type', 'text');
	} else {
		target.classList.remove('view');
		input.setAttribute('type', 'password');
	}

	return false;
}