// --- REGISTER FUNCTION ---
document.getElementById("registerForm")?.addEventListener("submit", function(e) {
  e.preventDefault();

  const fullname = document.getElementById("fullname").value;
  const username = document.getElementById("regUsername").value;
  const password = document.getElementById("regPassword").value;
  const role = document.getElementById("role").value;

  // Kunin existing users sa localStorage
  let users = JSON.parse(localStorage.getItem("users")) || [];

  // Check kung may kaparehong username
  const exists = users.find(user => user.username === username);
  if(exists) {
    alert("Username already exists!");
    return;
  }

  // Add new user
  users.push({ fullname, username, password, role });
  localStorage.setItem("users", JSON.stringify(users));

  alert("Account created successfully!");
  window.location.href = "login.html"; // balik sa login
});

// --- LOGIN FUNCTION ---
document.getElementById("loginForm")?.addEventListener("submit", function(e) {
  e.preventDefault();
  const user = document.getElementById("username").value;
  const pass = document.getElementById("password").value;

  let users = JSON.parse(localStorage.getItem("users")) || [];

  const found = users.find(u => u.username === user && u.password === pass);
  if(found) {
    alert("Welcome " + found.fullname + " (" + found.role + ")");
    // 👉 redirect diretso sa dashboard
    window.location.href = "../DASHBOARD/dashboard.html";
  } else {
    alert("Invalid username or password!");
  }
});


function createAccount() {
  window.location.href = "register.html";
}