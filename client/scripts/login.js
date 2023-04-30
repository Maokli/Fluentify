function login(email, password) {
  var myHeaders = new Headers();
  myHeaders.append("Content-Type", "application/json");
  myHeaders.append("Access-Control-Allow-Origin", "*");
  myHeaders.append("Access-Control-Allow-Methods", "POST, PUT, GET, DELETE");
  myHeaders.append("Access-Control-Allow-Headers", "*");
  myHeaders.append("Access-Control-Max-Age", "3600");
  var raw = JSON.stringify({
    username: email,
    password: password,
  });

  var requestOptions = {
    method: "POST",
    headers: myHeaders,
    body: raw,
    redirect: "follow",
  };

  fetch("http://127.0.0.1:8000/api/login_check", requestOptions)
    .then((response) => response.json())
    .then((result) => localStorage.setItem("token", result.token))
    .then((headers)=> {
        if (headers.status == 200) {
          // after login page redirect to welcome page 
            window.location.href = "welcome.html";
        }
        else{
          const a = document.querySelector('div.mb-3#message'); 
          a.innerHTML = "Invalid email or password";
        }
    })
    .catch((error) => console.log("error", error));
}
function submitForm(event) {
    event.preventDefault();
    const email = event.target.elements.email.value;
    const password = event.target.elements.password.value;
    login(email, password);
    console.log(email, password);
  }