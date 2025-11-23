document.addEventListener('DOMContentLoaded', () => {
  const signUpButton = document.getElementById('signUp');
  const signInButton = document.getElementById('signIn');
  const container = document.getElementById('container');

  signUpButton.addEventListener('click', () => {
      container.classList.add("right-panel-active");
  });

  signInButton.addEventListener('click', () => {
      container.classList.remove("right-panel-active");
  });
});

function togglePasswordVisibility() {
    const passwordInput = document.getElementById('passwordInput');
    const toggleIcon = document.getElementById('togglePassword');
    
    if (passwordInput.type === 'password') {
      passwordInput.type = 'text';
      toggleIcon.textContent = '🙈'; // เปลี่ยนเป็นรูปปิดตา
    } else {
      passwordInput.type = 'password';
      toggleIcon.textContent = '👁️'; // เปลี่ยนเป็นรูปเปิดตา
    }
  }