// Simple Registration and Login Handler
document.addEventListener('DOMContentLoaded', function() {
  // Determine app base URL exposed by PHP view or fallback
  function buildBase() {
    let b = window.APP_BASE || '/HMS/';
    // Ensure trailing slash
    if (!b.endsWith('/')) b += '/';
    // If not absolute URL, ensure it starts with a single leading slash
    const isAbsolute = /^https?:\/\//i.test(b);
    if (!isAbsolute) {
      b = '/' + b.replace(/^\/+/, '');
    }
    return b;
  }
  const BASE = buildBase();
  function goto(path) {
    const clean = path.replace(/^\/+/, '');
    if (window.SITE_URL) {
      const root = String(window.SITE_URL).replace(/\/+$/, '');
      window.location.href = root + '/' + clean;
    } else {
      window.location.href = BASE + clean;
    }
  }
  // Expose for onclick handlers
  window.goto = goto;
  // Registration Form
  const registerForm = document.getElementById('registerForm');
  if (registerForm) {
    registerForm.addEventListener('submit', function(e) {
      e.preventDefault();
      
      // Get form values
      const fullname = document.getElementById('fullname').value.trim();
      const username = document.getElementById('regUsername').value.trim();
      const password = document.getElementById('regPassword').value;
      const role = document.getElementById('role').value;
      
      // Basic validation
      if (!fullname || !username || !password || !role) {
        alert('Please fill in all fields');
        return;
      }
      
      // Password validation
      if (password.length < 6) {
        alert('Password must be at least 6 characters long');
        return;
      }
      
      // Get existing users or initialize empty array
      const users = JSON.parse(localStorage.getItem('users') || '[]');
      
      // Check if username exists
      if (users.some(u => u.username === username)) {
        alert('Username already exists');
        return;
      }
      
      // Add new user
      users.push({ fullname, username, password, role });
      localStorage.setItem('users', JSON.stringify(users));
      
      alert('Registration successful! Please login.');
      goto('login');
    });
  }
  
  // Login Form
  const loginForm = document.getElementById('loginForm');
  if (loginForm) {
    loginForm.addEventListener('submit', function(e) {
      e.preventDefault();
      
      const username = document.getElementById('username').value.trim();
      const password = document.getElementById('password').value;
      
      // Basic validation
      if (!username || !password) {
        alert('Please enter both username and password');
        return;
      }
      
      const users = JSON.parse(localStorage.getItem('users') || '[]');
      const user = users.find(u => u.username === username && u.password === password);
      
      if (user) {
        sessionStorage.setItem('currentUser', JSON.stringify(user));
        goto('dashboard');
      } else {
        alert('Invalid username or password');
      }
    });
  }
});

// Navigation function
window.createAccount = function() { window.goto('register'); };
