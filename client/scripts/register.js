function register(email, password, firstName, lastName, preferredLanguages) {
  var myHeaders = new Headers();
  myHeaders.append("Content-Type", "application/json");
  myHeaders.append("Access-Control-Allow-Origin", "*");
  myHeaders.append("Access-Control-Allow-Methods", "POST, PUT, GET, DELETE");
  myHeaders.append("Access-Control-Allow-Headers", "*");
  myHeaders.append("Access-Control-Max-Age", "3600");
  var raw = JSON.stringify({
    email : email,
    password: password,
    firstName: firstName,
    lastName: lastName,
    preferredLanguages: preferredLanguages,
  });

  var requestOptions = {
    method: "POST",
    headers: myHeaders,
    body: raw,
    redirect: "follow",
  };

  fetch("http://127.0.0.1:8000/api/register", requestOptions)
    .then((response) => response.json())
    .catch((error) => console.log("error", error));
}
function goodpass(pass){
  var regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/;
  return regex.test(pass);
}

function submitForm(event) {
  event.preventDefault();
  const password = event.target.elements.password.value;
  const a = document.querySelector('div.mb-3#message');  
  console.log(a);
  if(!goodpass(password))
  { 
    a.innerHTML = "Password must contain at least 8 characters, including UPPER/lowercase and numbers";
    return;
  }
  const firstName = event.target.elements.firstName.value;
  const lastName = event.target.elements.lastName.value;
  const email = event.target.elements.email.value;
  const par = event.target.elements.preferredLanguages.selectedOptions;
    // check the validity of the email 

  var S = "";
  for (var i = 0; i < par.length; i++) {
    S += par[i].value + "/";
  }

  register(email, password, firstName, lastName, S);
}
