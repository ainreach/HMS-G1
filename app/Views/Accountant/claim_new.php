<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>New Insurance Claim</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head>
<body>
<header class="dash-topbar" role="banner"><div class="topbar-inner">
  <a href="<?= site_url('/') ?>" class="menu-btn" aria-label="Home"><i class="fa-solid fa-grip"></i></a>
  <div class="brand-text"><h1 style="font-size:1.25rem;margin:0">New Insurance Claim</h1></div>
</div></header>
<div class="layout">
  <aside class="simple-sidebar" role="navigation"><nav class="side-nav">
    <a href="<?= site_url('dashboard/accountant') ?>">Overview</a>
    <a href="<?= site_url('accountant/billing') ?>">Billing & Payments</a>
    <a href="<?= site_url('accountant/insurance') ?>" class="active">Insurance</a>
    <a href="<?= site_url('accountant/reports') ?>">Financial Reports</a>
  </nav></aside>
  <main class="content">
    <section class="panel">
      <div class="panel-head"><h2 style="margin:0;font-size:1.1rem">Claim Details</h2></div>
      <div class="panel-body">
        <form method="post" action="<?= site_url('accountant/claims') ?>" style="display:grid;gap:10px;max-width:560px">
          <label>Claim #<input required name="claim_no" class="input"></label>
          <label>Invoice #<input name="invoice_no" class="input"></label>
          <label>Patient Name<input required name="patient_name" class="input"></label>
          <label>Provider<input name="provider" class="input"></label>
          <label>Policy #<input name="policy_no" class="input"></label>
          <label>Amount Claimed<input required name="amount_claimed" type="number" step="0.01" class="input"></label>
          <label>Amount Approved<input name="amount_approved" type="number" step="0.01" class="input"></label>
          <label>Status
            <select name="status" class="input">
              <option value="submitted">Submitted</option>
              <option value="in_review">In Review</option>
              <option value="approved">Approved</option>
              <option value="denied">Denied</option>
              <option value="paid">Paid</option>
            </select>
          </label>
          <label>Submitted At<input name="submitted_at" type="datetime-local" class="input"></label>
          <button class="btn" type="submit" style="width:max-content">Save Claim</button>
        </form>
      </div>
    </section>
  </main>
</div>
</body>
</html>
