<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>HMS - Reports & Analytics</title>
  <base href="<?= rtrim(base_url(), '/') ?>/" />
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>
<body>
  <header class="dash-topbar">
    <div class="topbar-inner">
      <button class="menu-btn" id="btnToggle" aria-label="Toggle menu"><i class="fa-solid fa-bars"></i></button>
      <div class="brand">
        <img src="<?= base_url('assets/img/logo.png') ?>" alt="FIFTY 50 MEDTECHIE" />
        <div class="brand-text">
          <h2>FIFTY 50 MEDTECHIE</h2>
          <small>"Trust Us... If You’re Feeling Lucky."</small>
        </div>
      </div>
    </div>
  </header>

  <div class="layout">
    <aside class="simple-sidebar" id="sidebar">
      <nav class="side-nav">
        <a href="javascript:void(0)" onclick="goto('dashboard')" data-feature="dashboard">Dashboard</a>
        <a href="javascript:void(0)" onclick="goto('records')" data-feature="ehr">Registration & Records</a>
        <a href="javascript:void(0)" onclick="goto('scheduling')" data-feature="scheduling">Scheduling</a>
        <a href="javascript:void(0)" onclick="goto('billing')" data-feature="billing">Billing & Payment</a>
        <a href="javascript:void(0)" onclick="goto('laboratory')" data-feature="laboratory">Laboratory & Diagnostic</a>
        <a href="javascript:void(0)" onclick="goto('pharmacy')" data-feature="pharmacy">Pharmacy</a>
        <a href="javascript:void(0)" onclick="goto('reports')" class="active" data-feature="reports">Reports & Analytics</a>
      </nav>
    </aside>

    <main class="content">
      <h1 class="page-title">Reports & Analytics</h1>

      <section class="kpi-grid">
        <div class="kpi-card">
          <div class="kpi-title">Appointments Today</div>
          <div class="kpi-value" id="kpiAppt">0</div>
        </div>
        <div class="kpi-card">
          <div class="kpi-title">Outstanding Balance</div>
          <div class="kpi-value" id="kpiOutstanding">₱0.00</div>
        </div>
        <div class="kpi-card">
          <div class="kpi-title">Lab Orders In-Process</div>
          <div class="kpi-value" id="kpiLab">0</div>
        </div>
        <div class="kpi-card">
          <div class="kpi-title">Low Stock Items</div>
          <div class="kpi-value" id="kpiStock">0</div>
        </div>
      </section>

      <section class="form-container">
        <h3><i class="fa-solid fa-download"></i> Export Data</h3>
        <div class="row">
          <div>
            <label>Dataset</label>
            <select id="dataset">
              <option value="appointments">Appointments</option>
              <option value="invoices">Invoices</option>
              <option value="payments">Payments</option>
            </select>
          </div>
          <div style="align-self:flex-end;">
            <button class="action-btn" id="btnExport"><i class="fa-solid fa-file-csv"></i> Export CSV</button>
          </div>
        </div>
      </section>

      <section class="form-container">
        <h3><i class="fa-solid fa-chart-simple"></i> Recent Activity</h3>
        <div id="activityList" class="record-list"></div>
      </section>
    </main>
  </div>

  <script>
    window.APP_BASE = '<?= rtrim(site_url('/'), '/') ?>/';
    window.SITE_URL = '<?= rtrim(site_url(), '/') ?>';
  </script>
  <script src="<?= base_url('assets/js/script.js') ?>"></script>
  <script src="<?= base_url('assets/js/rbac.js') ?>"></script>
  <script>
    const btn = document.getElementById('btnToggle');
    const sidebar = document.getElementById('sidebar');
    if (btn && sidebar) { btn.addEventListener('click', () => sidebar.classList.toggle('collapsed')); }

    const APPT_KEY = 'hms_appointments';
    const INV_KEY = 'hms_invoices';
    const PAY_KEY = 'hms_payments';

    function peso(n){ return '₱' + (Number(n||0)).toLocaleString(undefined, {minimumFractionDigits:2, maximumFractionDigits:2}); }

    function seedDemoIfEmpty(){
      // Only seed when keys are missing or empty arrays
      const ap = JSON.parse(localStorage.getItem(APPT_KEY)||'[]');
      const inv = JSON.parse(localStorage.getItem(INV_KEY)||'[]');
      const pay = JSON.parse(localStorage.getItem(PAY_KEY)||'[]');

      if (!ap.length) {
        const today = new Date();
        const tomorrow = new Date(Date.now()+24*60*60*1000);
        const sampleAppts = [
          {id: 1, patient: 'Juan Dela Cruz', doctor: 'Dr. Santos', when: today.toISOString(), duration: '30m', status: 'Confirmed', reason: 'Checkup'},
          {id: 2, patient: 'Maria Clara', doctor: 'Dr. Reyes', when: tomorrow.toISOString(), duration: '30m', status: 'Pending', reason: 'Follow-up'}
        ];
        localStorage.setItem(APPT_KEY, JSON.stringify(sampleAppts));
      }
      if (!inv.length) {
        const sampleInv = [
          {no: 'INV-1001', patient: 'Juan Dela Cruz', total: 1250, paid: 500, date: new Date().toISOString()},
          {no: 'INV-1002', patient: 'Maria Clara', total: 800, paid: 800, date: new Date().toISOString()}
        ];
        localStorage.setItem(INV_KEY, JSON.stringify(sampleInv));
      }
      if (!pay.length) {
        const samplePay = [
          {no: 'RCPT-501', patient: 'Juan Dela Cruz', amount: 500, method: 'Cash', date: new Date().toISOString()},
          {no: 'RCPT-502', patient: 'Maria Clara', amount: 800, method: 'Card', date: new Date().toISOString()}
        ];
        localStorage.setItem(PAY_KEY, JSON.stringify(samplePay));
      }
    }

    function loadKPIs(){
      const appts = JSON.parse(localStorage.getItem(APPT_KEY)||'[]');
      const today = new Date(); today.setHours(0,0,0,0);
      const countToday = appts.filter(a=>{const d=new Date(a.when); d.setHours(0,0,0,0); return d.getTime()===today.getTime();}).length;
      document.getElementById('kpiAppt').textContent = countToday;

      const inv = JSON.parse(localStorage.getItem(INV_KEY)||'[]');
      const outstanding = inv.reduce((s,i)=> s + Math.max(0,(i.total||i.amount||0) - (i.paid||0)),0);
      document.getElementById('kpiOutstanding').textContent = peso(outstanding);

      document.getElementById('kpiLab').textContent = 0;
      document.getElementById('kpiStock').textContent = 0;
    }

    function exportCSV(){
      const ds = document.getElementById('dataset').value;
      let rows = [];
      if (ds==='appointments') {
        const data = JSON.parse(localStorage.getItem(APPT_KEY)||'[]');
        rows = [['ID','Patient','Doctor','When','Duration','Status','Reason'], ...data.map(a=>[a.id,a.patient,a.doctor,a.when,a.duration||'',a.status||'',a.reason||''])];
      } else if (ds==='invoices') {
        const data = JSON.parse(localStorage.getItem(INV_KEY)||'[]');
        rows = [['Invoice #','Patient','Total','Paid','Date'], ...data.map(i=>[i.no||'',i.patient||'',i.total||i.amount||0,i.paid||0,i.date||''])];
      } else if (ds==='payments') {
        const data = JSON.parse(localStorage.getItem(PAY_KEY)||'[]');
        rows = [['Receipt #','Patient','Amount','Method','Date'], ...data.map(p=>[p.no||'',p.patient||'',p.amount||0,p.method||'',p.date||''])];
      }
      const csv = rows.map(r=>r.map(x=>`"${String(x).replaceAll('"','""')}"`).join(',')).join('\n');
      const blob = new Blob([csv], {type:'text/csv'});
      const url = URL.createObjectURL(blob);
      const a = document.createElement('a'); a.href=url; a.download=ds+".csv"; a.click();
      URL.revokeObjectURL(url);
    }

    function loadActivity(){
      const list = document.getElementById('activityList');
      const appts = JSON.parse(localStorage.getItem(APPT_KEY)||'[]').slice(-5).reverse();
      const pays = JSON.parse(localStorage.getItem(PAY_KEY)||'[]').slice(-5).reverse();
      const items = [];
      appts.forEach(a=> items.push(`<div class="record"><p><strong>Appointment</strong> · ${a.patient} with ${a.doctor}</p><span class="muted">${new Date(a.when).toLocaleString()}</span></div>`));
      pays.forEach(p=> items.push(`<div class="record"><p><strong>Payment</strong> · ${p.no||p.invoiceNumber||''}</p><span class="muted">₱${Number(p.amount||0).toLocaleString()} · ${p.method||''}</span></div>`));
      if (!items.length) { list.innerHTML = '<p class="muted">No recent activity.</p>'; return; }
      list.innerHTML = items.join('');
    }

    document.getElementById('btnExport').addEventListener('click', exportCSV);
    seedDemoIfEmpty();
    loadKPIs();
    loadActivity();
  </script>
</body>
</html>
