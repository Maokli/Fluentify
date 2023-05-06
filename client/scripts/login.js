function login(email, password) {
  const config = {
    headers: {
      'Content-Type': 'application/json',
      'Access-Control-Allow-Origin': '*',
      'Access-Control-Allow-Methods': 'POST, PUT, GET, DELETE',
      'Access-Control-Allow-Headers': '*',
      'Access-Control-Max-Age': '3600',
    },
  };
  const data = {
    username: email,
    password: password,
  };

  function showInvalidCredentials() {
    const a = document.querySelector('div.mb-3#message');
    a.innerHTML = 'Invalid email or password';
  }

  axios.post('http://127.0.0.1:8000/api/login_check', data, config)
    .then(response => {
      localStorage.setItem('token', response.data.token);
      // after login page redirect to dashboard page
      window.location.href = 'dashboard.html';
    })
    .catch(error => showInvalidCredentials());
}


function submitForm(event) {
  event.preventDefault();
  const email = event.target.elements.email.value;
  const password = event.target.elements.password.value;
  login(email, password);
}
