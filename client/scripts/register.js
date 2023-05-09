function register(email, password, firstName, lastName) {
  const config = {
    headers: {
      "Content-Type": "application/json",
      "Access-Control-Allow-Origin": "*",
      "Access-Control-Allow-Methods": "POST, PUT, GET, DELETE",
      "Access-Control-Allow-Headers": "*",
      "Access-Control-Max-Age": "3600",
    },
  };
  const data = {
    email: email,
    password: password,
    firstName: firstName,
    lastName: lastName,
  };

  axios
    .post("http://127.0.0.1:8000/api/register", data, config)
    .then((response) => {
      console.log(response.data.message);
      if (response.data.message === "Registered Successfully") {
        window.location.href = "login.html";
      }
    })
    .catch((error) => console.log("error", error));
}

function goodpass(pass) {
  var regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/;
  return regex.test(pass);
}

function goodemail(email) {
  var regex = /\S+@\S+\.\S+/;
  return regex.test(email);
}

function submitForm(event) {
  event.preventDefault();
  const password = event.target.elements.password.value;
  const a = document.querySelector("div.mb-3#message");
  const email = event.target.elements.email.value;
  if (!goodemail(email)) {
    a.innerHTML = "Email must be valid";
    return;
  } else {
    a.innerHTML = "";
  }

  if (!goodpass(password)) {
    a.innerHTML =
      "Password must contain at least 8 characters, including UPPER/lowercase and numbers";
    return;
  } else {
    a.innerHTML = "";
  }

  const firstName = event.target.elements.firstName.value;
  const lastName = event.target.elements.lastName.value;

  register(email, password, firstName, lastName);
}
