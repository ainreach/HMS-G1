<?php /* Converted from billing.html (streamlined) */ ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Billing & Payment - FIFTY 50 MEDTECHIE</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
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
        <a href="javascript:void(0)" onclick="goto('dashboard')">Dashboard</a>
        <a href="javascript:void(0)" onclick="goto('records')">Registration & Records</a>
        <a href="javascript:void(0)" onclick="goto('scheduling')">Scheduling</a>
        <a href="javascript:void(0)" onclick="goto('billing')" class="active">Billing & Payment</a>
        <a href="javascript:void(0)" onclick="goto('laboratory')">Laboratory & Diagnostic</a>
        <a href="javascript:void(0)" onclick="goto('pharmacy')">Pharmacy</a>
        <a href="javascript:void(0)" onclick="goto('reports')">Reports & Analytics</a>
      </nav>
    </aside>

    <main class="content">
      <h1 class="page-title">Billing & Payment</h1>

      <section class="kpi-grid" style="margin-bottom:14px;">
        <article class="kpi-card kpi-info">
          <div class="kpi-head"><span>Total Outstanding</span><i class="fa-solid fa-receipt"></i></div>
          <div class="kpi-value" id="kpiOutstanding">₱0</div>
          <div class="kpi-sub up">Updated live</div>
        </article>
        <article class="kpi-card kpi-success">
          <div class="kpi-head"><span>Paid Invoices</span><i class="fa-solid fa-circle-check"></i></div>
          <div class="kpi-value" id="kpiPaid">0</div>
          <div class="kpi-sub up">Thank you!</div>
        </article>
        <article class="kpi-card kpi-warning">
          <div class="kpi-head"><span>Unpaid Invoices</span><i class="fa-solid fa-triangle-exclamation"></i></div>
          <div class="kpi-value" id="kpiUnpaid">0</div>
          <div class="kpi-sub down">Settle soon</div>
        </article>
        <article class="kpi-card kpi-primary">
          <div class="kpi-head"><span>Last Payment</span><i class="fa-solid fa-peso-sign"></i></div>
          <div class="kpi-value" id="kpiLast">—</div>
          <div class="kpi-sub">Most recent receipt</div>
        </article>
      </section>

      <section class="form-container">
        <h3><i class="fa-solid fa-receipt"></i> Invoices</h3>
        <div class="row">
          <div>
            <label for="filterInv">Search</label>
            <input id="filterInv" type="text" placeholder="Patient or Invoice #">
          </div>
          <div>
            <label for="statusSel">Status</label>
            <select id="statusSel">
              <option value="all">All</option>
              <option value="unpaid">Unpaid</option>
              <option value="paid">Paid</option>
            </select>
          </div>
        </div>
        <div id="invoiceList" style="margin-top:10px;"></div>
      </section>

      <section class="form-container">
        <h3><i class="fa-solid fa-coins"></i> Process Payment</h3>
        <div class="row">
          <div>
            <label for="payInvoice">Invoice</label>
            <select id="payInvoice"></select>
          </div>
          <div>
            <label for="payAmount">Amount</label>
            <input id="payAmount" type="number" min="0" step="0.01" placeholder="0.00">
          </div>
        </div>
        <div class="row">
          <div>
            <label for="payMethod">Payment Method</label>
            <select id="payMethod">
              <option>Cash</option>
              <option>GCash</option>
              <option>Bank Transfer</option>
              <option>Card</option>
            </select>
          </div>
          <div>
            <label for="payRef">Reference No. (optional)</label>
            <input id="payRef" type="text" placeholder="e.g., GCash Ref, OR #">
          </div>
        </div>
        <button class="submit-btn" id="btnPay"><i class="fa-solid fa-check"></i> Submit Payment</button>
      </section>

      <section class="form-container">
        <h3><i class="fa-solid fa-clipboard-list"></i> Recent Payments</h3>
        <div id="paymentList"></div>
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

    const INVOICES_KEY = 'hms_invoices';
    const PAYMENTS_KEY = 'hms_payments';

    const db = {
      invAll: () => JSON.parse(localStorage.getItem(INVOICES_KEY) || '[]'),
      invSave: (arr) => localStorage.setItem(INVOICES_KEY, JSON.stringify(arr)),
      payAll: () => JSON.parse(localStorage.getItem(PAYMENTS_KEY) || '[]'),
      paySave: (arr) => localStorage.setItem(PAYMENTS_KEY, JSON.stringify(arr)),
    };

    const el = (id) => document.getElementById(id);
    const invoiceList = el('invoiceList');
    const payInvoice = el('payInvoice');
    const payAmount = el('payAmount');
    const payMethod = el('payMethod');
    const payRef = el('payRef');

    function money(n){ return '₱' + (Number(n||0)).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }); }

    function refreshKpis(){
      const inv = db.invAll();
      const paid = inv.filter(i => i.status === 'paid');
      const unpaid = inv.filter(i => i.status !== 'paid');
      const outstanding = unpaid.reduce((s, i) => s + Number(i.amount||0) - Number(i.paid||0), 0);
      el('kpiOutstanding').textContent = money(outstanding);
      el('kpiPaid').textContent = String(paid.length);
      el('kpiUnpaid').textContent = String(unpaid.length);
      const pays = db.payAll().sort((a,b)=> new Date(b.when) - new Date(a.when));
      el('kpiLast').textContent = pays[0] ? money(pays[0].amount) : '—';
    }

    function renderInvoices(){
      const q = (el('filterInv').value||'').toLowerCase();
      const status = el('statusSel').value;
      const inv = db.invAll().filter(i => {
        const txt = `${i.patient||''} ${i.no||''}`.toLowerCase();
        const sOk = status === 'all' || (status === 'paid' ? i.status==='paid' : i.status!=='paid');
        return txt.includes(q) && sOk;
      });
      if (!inv.length) { invoiceList.innerHTML = '<p class="muted">No invoices found.</p>'; return; }
      invoiceList.innerHTML = inv.map(i => `
        <div class="record">
          <p><strong>${i.patient}</strong><br>
          <span class="muted">Invoice #${i.no} · ${money(i.amount)} · Paid: ${money(i.paid||0)}</span></p>
          <div class="actions">
            <span class="low" style="background:${i.status==='paid'?'#dcfce7':'#fee2e2'};color:${i.status==='paid'?'#166534':'#991b1b'};">${i.status==='paid'?'Paid':'Unpaid'}</span>
            <button class="btn-view" onclick="fillPay('${i.no}', ${Math.max(0,(i.amount-(i.paid||0))).toFixed(2)})">Pay</button>
          </div>
        </div>`).join('');
      buildInvoiceOptions();
    }

    function buildInvoiceOptions(){
      const inv = db.invAll();
      payInvoice.innerHTML = inv.map(i => `<option value="${i.no}">${i.no} — ${i.patient}</option>`).join('');
    }

    function fillPay(no, amount){
      payInvoice.value = String(no);
      payAmount.value = String(amount||'');
      window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' });
    }

    el('btnPay').onclick = () => {
      const no = payInvoice.value;
      const amt = Number(payAmount.value||0);
      if (!no || !amt) { alert('Select invoice and enter amount'); return; }
      const all = db.invAll();
      const idx = all.findIndex(i => String(i.no) === String(no));
      if (idx < 0) { alert('Invoice not found'); return; }
      all[idx].paid = Number(all[idx].paid||0) + amt;
      all[idx].status = (all[idx].paid >= all[idx].amount) ? 'paid' : 'unpaid';
      db.invSave(all);
      const pays = db.payAll();
      pays.push({ no, amount: amt, method: payMethod.value, ref: payRef.value, when: new Date().toISOString() });
      db.paySave(pays);
      payAmount.value = ''; payRef.value = '';
      refreshKpis(); renderInvoices(); alert('Payment recorded');
    };

    el('statusSel').onchange = renderInvoices;
    el('filterInv').oninput = renderInvoices;

    function seedDemo(){
      if (db.invAll().length) return;
      db.invSave([
        { no: 'INV-001', patient: 'Juan Dela Cruz', amount: 1250.00, paid: 0, status: 'unpaid' },
        { no: 'INV-002', patient: 'Maria Santos', amount: 850.00, paid: 850.00, status: 'paid' },
        { no: 'INV-003', patient: 'Pedro Reyes', amount: 500.00, paid: 100.00, status: 'unpaid' },
      ]);
    }

    seedDemo();
    refreshKpis();
    renderInvoices();
  </script>
</body>
</html>
