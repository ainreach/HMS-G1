// Simple front-end RBAC + Branch selector using localStorage
//
// What this file does (in simple terms):
// 1) Saves your selected Role and Branch in the browser (localStorage).
// 2) Shows or hides parts of a page based on your Role (this is RBAC: Role-Based Access Control).
// 3) If you open a page you are not allowed to see, it sends you back to the Dashboard.
//
// How to explain RBAC here:
// - We have a list called ACCESS that tells which roles can see each feature.
// - Every element that has data-feature="..." will be shown only if your role is allowed in ACCESS.
// - Changing the Role from the dropdown updates the page instantly.
(function(){
  const ROLES = [
    'Administrator','Doctor','Nurse','Receptionist','Lab Staff','Pharmacist','Accountant','IT Staff','Patient'
  ];
  const BRANCHES_KEY = 'hms_branches';
  const ROLE_KEY = 'hms_role';
  const BRANCH_KEY = 'hms_branch';

  // Default branches
  if (!localStorage.getItem(BRANCHES_KEY)) {
    localStorage.setItem(BRANCHES_KEY, JSON.stringify(['Main','East Wing','West Wing']));
  }

  // Feature-to-roles map
  const ACCESS = {
    dashboard: ROLES, // everyone including Patient can see dashboard
    ehr: ['Administrator','Doctor','Nurse','Receptionist'],
    scheduling: ['Administrator','Doctor','Nurse','Receptionist','Patient'],
    billing: ['Administrator','Accountant','Receptionist','Patient'],
    insurance: ['Administrator','Accountant'],
    laboratory: ['Administrator','Doctor','Nurse','Lab Staff'],
    pharmacy: ['Administrator','Pharmacist'],
    reports: ['Administrator','Accountant','IT Staff'],
  };

  // applyRBAC: reads the saved role/branch and updates the page UI
  function applyRBAC() {
    const role = localStorage.getItem(ROLE_KEY) || 'Administrator';
    const branch = localStorage.getItem(BRANCH_KEY) || 'Main';

    // Populate selectors if present
    const roleSel = document.getElementById('roleSel');
    if (roleSel) {
      roleSel.innerHTML = ROLES.map(r => `<option ${r===role?'selected':''}>${r}</option>`).join('');
      roleSel.onchange = () => {
        localStorage.setItem(ROLE_KEY, roleSel.value);
        // Re-apply instantly without reloading to avoid delays
        applyRBAC();
        // If current page not allowed, redirect to dashboard for safety
        enforcePageAccess();
      };
    }
    const branchSel = document.getElementById('branchSel');
    if (branchSel) {
      const branches = JSON.parse(localStorage.getItem(BRANCHES_KEY)||'[]');
      branchSel.innerHTML = branches.map(b => `<option ${b===branch?'selected':''}>${b}</option>`).join('');
      branchSel.onchange = () => { localStorage.setItem(BRANCH_KEY, branchSel.value); document.body.setAttribute('data-branch', branchSel.value); };
    }

    // Apply to nav links and feature blocks
    // Any HTML element with data-feature="something" will only show
    // if the current role is listed in ACCESS[something].
    document.querySelectorAll('[data-feature]').forEach(el => {
      const f = el.getAttribute('data-feature');
      const allowed = ACCESS[f] || [];
      const ok = allowed.includes(role);
      el.style.display = ok ? '' : 'none';
    });

    // Show current role/branch text if element exists
    const roleText = document.getElementById('roleText');
    if (roleText) roleText.textContent = role;
    const branchText = document.getElementById('branchText');
    if (branchText) branchText.textContent = branch;

    document.body.setAttribute('data-role', role);
    document.body.setAttribute('data-branch', branch);
  }

  // enforcePageAccess: safety check when you are on a page you can't access
  function enforcePageAccess(){
    try {
      const role = localStorage.getItem(ROLE_KEY) || 'Administrator';
      const active = document.querySelector('.side-nav a.active');
      const feature = active ? active.getAttribute('data-feature') : null;
      if (feature && Array.isArray(ACCESS[feature]) && !ACCESS[feature].includes(role)) {
        // Redirect to dashboard if current page is not allowed for the selected role
        try {
          if (typeof goto === 'function') { goto('dashboard'); return; }
        } catch(e) { /* ignore */ }
        var base = (window.SITE_URL || window.APP_BASE || '/').replace(/\/?$/, '/');
        window.location.href = base + 'dashboard';
      }
    } catch(e) { /* no-op */ }
  }

  // Run when the page is ready
  function init(){
    applyRBAC();
    enforcePageAccess();
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
