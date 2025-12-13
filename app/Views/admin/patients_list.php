<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- Statistics Cards -->
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:16px;margin-bottom:24px">
  <div class="panel" style="border-left:4px solid #3b82f6">
    <div style="display:flex;justify-content:space-between;align-items:center">
      <div>
        <p style="margin:0;color:#6b7280;font-size:0.875rem;font-weight:500">Total Patients</p>
        <h3 style="margin:8px 0 0 0;font-size:1.75rem;font-weight:700;color:#111827"><?= number_format($stats['total'] ?? 0) ?></h3>
      </div>
      <div style="width:48px;height:48px;background:#dbeafe;border-radius:12px;display:flex;align-items:center;justify-content:center">
        <i class="fa-solid fa-users" style="font-size:24px;color:#3b82f6"></i>
      </div>
    </div>
  </div>
  
  <div class="panel" style="border-left:4px solid #3b82f6">
    <div style="display:flex;justify-content:space-between;align-items:center">
      <div>
        <p style="margin:0;color:#6b7280;font-size:0.875rem;font-weight:500">Admitted</p>
        <h3 style="margin:8px 0 0 0;font-size:1.75rem;font-weight:700;color:#111827"><?= number_format($stats['admitted'] ?? 0) ?></h3>
      </div>
      <div style="width:48px;height:48px;background:#dbeafe;border-radius:12px;display:flex;align-items:center;justify-content:center">
        <i class="fa-solid fa-bed" style="font-size:24px;color:#3b82f6"></i>
      </div>
    </div>
  </div>
  
  <div class="panel" style="border-left:4px solid #16a34a">
    <div style="display:flex;justify-content:space-between;align-items:center">
      <div>
        <p style="margin:0;color:#6b7280;font-size:0.875rem;font-weight:500">Discharged</p>
        <h3 style="margin:8px 0 0 0;font-size:1.75rem;font-weight:700;color:#111827"><?= number_format($stats['discharged'] ?? 0) ?></h3>
      </div>
      <div style="width:48px;height:48px;background:#dcfce7;border-radius:12px;display:flex;align-items:center;justify-content:center">
        <i class="fa-solid fa-check-circle" style="font-size:24px;color:#16a34a"></i>
      </div>
    </div>
  </div>
  
  <div class="panel" style="border-left:4px solid #6b7280">
    <div style="display:flex;justify-content:space-between;align-items:center">
      <div>
        <p style="margin:0;color:#6b7280;font-size:0.875rem;font-weight:500">Out-Patients</p>
        <h3 style="margin:8px 0 0 0;font-size:1.75rem;font-weight:700;color:#111827"><?= number_format($stats['outpatient'] ?? 0) ?></h3>
      </div>
      <div style="width:48px;height:48px;background:#f3f4f6;border-radius:12px;display:flex;align-items:center;justify-content:center">
        <i class="fa-solid fa-user" style="font-size:24px;color:#6b7280"></i>
      </div>
    </div>
  </div>
</div>

<!-- Main Panel -->
<section class="panel">
  <div class="panel-head" style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px">
    <div>
      <h2 style="margin:0;font-size:1.25rem;font-weight:600">Patient Records</h2>
      <p style="margin:4px 0 0 0;color:#6b7280;font-size:0.875rem">
        Showing <?= number_format($total) ?> patient<?= $total != 1 ? 's' : '' ?>
        <?php if (!empty($search)): ?>
          <span style="color:#3b82f6">matching "<?= esc($search) ?>"</span>
        <?php endif; ?>
      </p>
    </div>
    <button type="button" class="btn btn-success" onclick="showPatientModal()" style="display:inline-flex;align-items:center;gap:6px">
      <i class="fa-solid fa-plus"></i> Add New Patient
    </button>
  </div>

  <!-- Search and Filter Section -->
  <div class="panel-body" style="background:#f9fafb;border-bottom:1px solid #e5e7eb;padding:16px">
    <form method="GET" action="<?= site_url('admin/patients') ?>" id="filterForm" style="display:grid;gap:12px">
      <!-- Search Bar -->
      <div style="display:grid;grid-template-columns:1fr auto;gap:8px">
        <div style="position:relative">
          <i class="fa-solid fa-magnifying-glass" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#6b7280"></i>
          <input 
            type="text" 
            name="search" 
            value="<?= esc($search ?? '') ?>" 
            placeholder="Search by name, patient ID, phone, or email..." 
            style="width:100%;padding:10px 12px 10px 40px;border:1px solid #d1d5db;border-radius:8px;font-size:0.875rem"
            aria-label="Search patients"
          >
        </div>
        <button type="submit" class="btn" style="padding:10px 20px;white-space:nowrap">
          <i class="fa-solid fa-search"></i> Search
        </button>
      </div>

      <!-- Filter Row -->
      <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:12px">
        <!-- Status Filter -->
        <div>
          <label for="status" style="display:block;margin-bottom:4px;font-size:0.75rem;font-weight:600;color:#374151">Status</label>
          <select name="status" id="status" onchange="document.getElementById('filterForm').submit()" style="width:100%;padding:8px;border:1px solid #d1d5db;border-radius:6px;font-size:0.875rem;background:white">
            <option value="all" <?= ($statusFilter ?? 'all') === 'all' ? 'selected' : '' ?>>All Status</option>
            <option value="admitted" <?= ($statusFilter ?? '') === 'admitted' ? 'selected' : '' ?>>Admitted</option>
            <option value="discharged" <?= ($statusFilter ?? '') === 'discharged' ? 'selected' : '' ?>>Discharged</option>
            <option value="outpatient" <?= ($statusFilter ?? '') === 'outpatient' ? 'selected' : '' ?>>Out-Patient</option>
          </select>
        </div>

        <!-- Gender Filter -->
        <div>
          <label for="gender" style="display:block;margin-bottom:4px;font-size:0.75rem;font-weight:600;color:#374151">Gender</label>
          <select name="gender" id="gender" onchange="document.getElementById('filterForm').submit()" style="width:100%;padding:8px;border:1px solid #d1d5db;border-radius:6px;font-size:0.875rem;background:white">
            <option value="all" <?= ($genderFilter ?? 'all') === 'all' ? 'selected' : '' ?>>All Genders</option>
            <option value="male" <?= ($genderFilter ?? '') === 'male' ? 'selected' : '' ?>>Male</option>
            <option value="female" <?= ($genderFilter ?? '') === 'female' ? 'selected' : '' ?>>Female</option>
            <option value="other" <?= ($genderFilter ?? '') === 'other' ? 'selected' : '' ?>>Other</option>
          </select>
        </div>

        <!-- Date From -->
        <div>
          <label for="date_from" style="display:block;margin-bottom:4px;font-size:0.75rem;font-weight:600;color:#374151">Date From</label>
          <input 
            type="date" 
            name="date_from" 
            id="date_from" 
            value="<?= esc($dateFrom ?? '') ?>" 
            onchange="document.getElementById('filterForm').submit()"
            style="width:100%;padding:8px;border:1px solid #d1d5db;border-radius:6px;font-size:0.875rem"
          >
        </div>

        <!-- Date To -->
        <div>
          <label for="date_to" style="display:block;margin-bottom:4px;font-size:0.75rem;font-weight:600;color:#374151">Date To</label>
          <input 
            type="date" 
            name="date_to" 
            id="date_to" 
            value="<?= esc($dateTo ?? '') ?>" 
            onchange="document.getElementById('filterForm').submit()"
            style="width:100%;padding:8px;border:1px solid #d1d5db;border-radius:6px;font-size:0.875rem"
          >
        </div>

        <!-- Per Page -->
        <div>
          <label for="per_page" style="display:block;margin-bottom:4px;font-size:0.75rem;font-weight:600;color:#374151">Per Page</label>
          <select name="per_page" id="per_page" onchange="document.getElementById('filterForm').submit()" style="width:100%;padding:8px;border:1px solid #d1d5db;border-radius:6px;font-size:0.875rem;background:white">
            <option value="10" <?= ($perPage ?? 25) == 10 ? 'selected' : '' ?>>10</option>
            <option value="25" <?= ($perPage ?? 25) == 25 ? 'selected' : '' ?>>25</option>
            <option value="50" <?= ($perPage ?? 25) == 50 ? 'selected' : '' ?>>50</option>
            <option value="100" <?= ($perPage ?? 25) == 100 ? 'selected' : '' ?>>100</option>
          </select>
        </div>

        <!-- Clear Filters -->
        <div style="display:flex;align-items:flex-end">
          <a href="<?= site_url('admin/patients') ?>" class="btn" style="width:100%;padding:8px;background:#6b7280;color:white;text-decoration:none;border-radius:6px;text-align:center;font-size:0.875rem">
            <i class="fa-solid fa-xmark"></i> Clear
          </a>
        </div>
      </div>

      <!-- Preserve other filters in hidden inputs -->
      <input type="hidden" name="sort" value="<?= esc($sortBy ?? 'created_at') ?>">
      <input type="hidden" name="order" value="<?= esc($sortOrder ?? 'DESC') ?>">
    </form>
  </div>

  <!-- Table Section -->
  <div class="panel-body" style="padding:0;overflow-x:auto">
    <?php if (!empty($patients)): ?>
      <table class="table" style="width:100%;border-collapse:collapse" role="table" aria-label="Patient records table">
        <thead>
          <tr style="background:#f9fafb;border-bottom:2px solid #e5e7eb">
            <th style="text-align:left;padding:12px;font-weight:600;font-size:0.875rem;color:#374151;white-space:nowrap">
              <a href="<?= site_url('admin/patients?' . http_build_query(array_merge($_GET, ['sort' => 'patient_id', 'order' => ($sortBy === 'patient_id' && $sortOrder === 'ASC') ? 'DESC' : 'ASC']))) ?>" 
                 style="color:inherit;text-decoration:none;display:flex;align-items:center;gap:6px">
                Patient ID
                <?php if ($sortBy === 'patient_id'): ?>
                  <i class="fa-solid fa-sort-<?= $sortOrder === 'ASC' ? 'up' : 'down' ?>" style="font-size:0.75rem"></i>
                <?php else: ?>
                  <i class="fa-solid fa-sort" style="font-size:0.75rem;opacity:0.3"></i>
                <?php endif; ?>
              </a>
            </th>
            <th style="text-align:left;padding:12px;font-weight:600;font-size:0.875rem;color:#374151;white-space:nowrap">
              <a href="<?= site_url('admin/patients?' . http_build_query(array_merge($_GET, ['sort' => 'last_name', 'order' => ($sortBy === 'last_name' && $sortOrder === 'ASC') ? 'DESC' : 'ASC']))) ?>" 
                 style="color:inherit;text-decoration:none;display:flex;align-items:center;gap:6px">
                Name
                <?php if ($sortBy === 'last_name'): ?>
                  <i class="fa-solid fa-sort-<?= $sortOrder === 'ASC' ? 'up' : 'down' ?>" style="font-size:0.75rem"></i>
                <?php else: ?>
                  <i class="fa-solid fa-sort" style="font-size:0.75rem;opacity:0.3"></i>
                <?php endif; ?>
              </a>
            </th>
            <th style="text-align:left;padding:12px;font-weight:600;font-size:0.875rem;color:#374151;white-space:nowrap">Gender</th>
            <th style="text-align:left;padding:12px;font-weight:600;font-size:0.875rem;color:#374151;white-space:nowrap">Contact</th>
            <th style="text-align:left;padding:12px;font-weight:600;font-size:0.875rem;color:#374151;white-space:nowrap">Status</th>
            <th style="text-align:left;padding:12px;font-weight:600;font-size:0.875rem;color:#374151;white-space:nowrap">Room/Bed</th>
            <th style="text-align:left;padding:12px;font-weight:600;font-size:0.875rem;color:#374151;white-space:nowrap">
              <a href="<?= site_url('admin/patients?' . http_build_query(array_merge($_GET, ['sort' => 'created_at', 'order' => ($sortBy === 'created_at' && $sortOrder === 'ASC') ? 'DESC' : 'ASC']))) ?>" 
                 style="color:inherit;text-decoration:none;display:flex;align-items:center;gap:6px">
                Registered
                <?php if ($sortBy === 'created_at'): ?>
                  <i class="fa-solid fa-sort-<?= $sortOrder === 'ASC' ? 'up' : 'down' ?>" style="font-size:0.75rem"></i>
                <?php else: ?>
                  <i class="fa-solid fa-sort" style="font-size:0.75rem;opacity:0.3"></i>
                <?php endif; ?>
              </a>
            </th>
            <th style="text-align:center;padding:12px;font-weight:600;font-size:0.875rem;color:#374151;white-space:nowrap">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($patients as $patient): ?>
            <tr style="border-bottom:1px solid #f3f4f6;transition:background 0.15s" 
                onmouseover="this.style.background='#f9fafb'" 
                onmouseout="this.style.background=''"
                role="row">
              <td style="padding:12px;font-size:0.875rem">
                <span style="font-weight:600;color:#3b82f6"><?= esc($patient['patient_id'] ?? 'N/A') ?></span>
              </td>
              <td style="padding:12px;font-size:0.875rem">
                <div style="display:flex;align-items:center;gap:8px">
                  <div style="width:36px;height:36px;background:#e5e7eb;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                    <i class="fa-solid fa-user" style="color:#6b7280;font-size:0.875rem"></i>
                  </div>
                  <div>
                    <a href="<?= site_url('admin/patients/view/' . $patient['id']) ?>" 
                       style="font-weight:600;color:#111827;text-decoration:none;display:block"
                       onmouseover="this.style.color='#3b82f6'" 
                       onmouseout="this.style.color='#111827'">
                      <?= esc(trim(($patient['first_name'] ?? '') . ' ' . ($patient['last_name'] ?? ''))) ?>
                    </a>
                    <?php if (!empty($patient['date_of_birth'])): ?>
                      <small style="color:#6b7280;font-size:0.75rem">
                        DOB: <?= esc(date('M d, Y', strtotime($patient['date_of_birth']))) ?>
                      </small>
                    <?php endif; ?>
                  </div>
                </div>
              </td>
              <td style="padding:12px;font-size:0.875rem">
                <span style="padding:4px 10px;background:#f3f4f6;color:#374151;border-radius:12px;font-size:0.75rem;font-weight:500;text-transform:uppercase">
                  <?= esc(ucfirst($patient['gender'] ?? 'N/A')) ?>
                </span>
              </td>
              <td style="padding:12px;font-size:0.875rem">
                <div style="color:#374151">
                  <?php if (!empty($patient['phone'])): ?>
                    <div style="display:flex;align-items:center;gap:6px;margin-bottom:4px">
                      <i class="fa-solid fa-phone" style="color:#6b7280;font-size:0.75rem"></i>
                      <span><?= esc($patient['phone']) ?></span>
                    </div>
                  <?php endif; ?>
                  <?php if (!empty($patient['email'])): ?>
                    <div style="display:flex;align-items:center;gap:6px">
                      <i class="fa-solid fa-envelope" style="color:#6b7280;font-size:0.75rem"></i>
                      <span style="color:#6b7280;font-size:0.8125rem"><?= esc($patient['email']) ?></span>
                    </div>
                  <?php endif; ?>
                  <?php if (empty($patient['phone']) && empty($patient['email'])): ?>
                    <span style="color:#9ca3af">N/A</span>
                  <?php endif; ?>
                </div>
              </td>
              <td style="padding:12px;font-size:0.875rem">
                <span style="padding:6px 12px;background:<?= esc($patient['status_color'] ?? '#6b7280') ?>20;color:<?= esc($patient['status_color'] ?? '#6b7280') ?>;border-radius:6px;font-size:0.75rem;font-weight:600;display:inline-flex;align-items:center;gap:6px">
                  <span style="width:8px;height:8px;background:<?= esc($patient['status_color'] ?? '#6b7280') ?>;border-radius:50%"></span>
                  <?= esc($patient['status_label'] ?? 'N/A') ?>
                </span>
              </td>
              <td style="padding:12px;font-size:0.875rem">
                <?php if (!empty($patient['room_display']) && $patient['room_display'] !== 'N/A'): ?>
                  <div style="display:flex;align-items:center;gap:6px;color:#374151">
                    <i class="fa-solid fa-bed" style="color:#3b82f6"></i>
                    <span><?= esc($patient['room_display']) ?></span>
                  </div>
                <?php else: ?>
                  <span style="color:#9ca3af">—</span>
                <?php endif; ?>
              </td>
              <td style="padding:12px;font-size:0.875rem;color:#6b7280">
                <?= esc(date('M d, Y', strtotime($patient['created_at'] ?? 'now'))) ?>
                <br>
                <small style="font-size:0.75rem"><?= esc(date('h:i A', strtotime($patient['created_at'] ?? 'now'))) ?></small>
              </td>
              <td style="padding:12px;text-align:center">
                <div style="display:flex;gap:4px;justify-content:center;flex-wrap:wrap">
                  <a href="<?= site_url('admin/patients/view/' . $patient['id']) ?>" 
                     class="btn btn-sm btn-info" 
                     style="padding:6px 10px;font-size:0.75rem;text-decoration:none;border-radius:4px"
                     title="View Patient Details"
                     aria-label="View patient <?= esc($patient['first_name'] . ' ' . $patient['last_name']) ?>">
                    <i class="fa-solid fa-eye"></i>
                  </a>
                  <a href="<?= site_url('admin/patients/edit/' . $patient['id']) ?>" 
                     class="btn btn-sm btn-warning" 
                     style="padding:6px 10px;font-size:0.75rem;text-decoration:none;border-radius:4px"
                     title="Edit Patient"
                     aria-label="Edit patient <?= esc($patient['first_name'] . ' ' . $patient['last_name']) ?>">
                    <i class="fa-solid fa-pencil"></i>
                  </a>
                  <?php if ($patient['status'] === 'admitted'): ?>
                    <a href="<?= site_url('doctor/discharge-patients') ?>" 
                       class="btn btn-sm" 
                       style="padding:6px 10px;font-size:0.75rem;text-decoration:none;border-radius:4px;background:#16a34a;color:white"
                       title="Discharge Patient"
                       aria-label="Discharge patient <?= esc($patient['first_name'] . ' ' . $patient['last_name']) ?>">
                      <i class="fa-solid fa-sign-out-alt"></i>
                    </a>
                  <?php endif; ?>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php else: ?>
      <div style="text-align:center;padding:60px 20px;color:#6b7280">
        <i class="fa-solid fa-user-injured" style="font-size:4rem;margin-bottom:16px;opacity:0.3"></i>
        <h3 style="margin:0 0 8px 0;font-size:1.125rem;font-weight:600;color:#374151">No patients found</h3>
        <p style="margin:0 0 24px 0;font-size:0.875rem">
          <?php if (!empty($search) || $statusFilter !== 'all'): ?>
            Try adjusting your search or filters.
          <?php else: ?>
            Start by adding your first patient to the system.
          <?php endif; ?>
        </p>
        <?php if (empty($search) && $statusFilter === 'all'): ?>
          <button type="button" class="btn btn-success" onclick="showPatientModal()" style="display:inline-flex;align-items:center;gap:6px">
            <i class="fa-solid fa-plus"></i> Add First Patient
          </button>
        <?php else: ?>
          <a href="<?= site_url('admin/patients') ?>" class="btn" style="display:inline-flex;align-items:center;gap:6px;text-decoration:none">
            <i class="fa-solid fa-xmark"></i> Clear Filters
          </a>
        <?php endif; ?>
      </div>
    <?php endif; ?>
  </div>

  <!-- Pagination -->
  <?php if ($totalPages > 1): ?>
    <div class="panel-body" style="border-top:1px solid #e5e7eb;padding:16px;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;background:#f9fafb">
      <div style="color:#6b7280;font-size:0.875rem">
        Showing <strong><?= number_format($offset + 1) ?></strong> to <strong><?= number_format(min($offset + $perPage, $total)) ?></strong> of <strong><?= number_format($total) ?></strong> patients
      </div>
      <div style="display:flex;gap:8px;align-items:center">
        <?php if ($hasPrev): ?>
          <a href="<?= site_url('admin/patients?' . http_build_query(array_merge($_GET, ['page' => $page - 1]))) ?>" 
             class="btn" 
             style="padding:8px 12px;text-decoration:none;border-radius:6px;display:inline-flex;align-items:center;gap:6px">
            <i class="fa-solid fa-chevron-left"></i> Previous
          </a>
        <?php endif; ?>
        
        <div style="display:flex;gap:4px">
          <?php
            $startPage = max(1, $page - 2);
            $endPage = min($totalPages, $page + 2);
            
            if ($startPage > 1): ?>
              <a href="<?= site_url('admin/patients?' . http_build_query(array_merge($_GET, ['page' => 1]))) ?>" 
                 class="btn" 
                 style="padding:8px 12px;text-decoration:none;border-radius:6px;min-width:40px;text-align:center">1</a>
              <?php if ($startPage > 2): ?>
                <span style="padding:8px 4px;color:#6b7280">...</span>
              <?php endif; ?>
            <?php endif; ?>
            
            <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
              <a href="<?= site_url('admin/patients?' . http_build_query(array_merge($_GET, ['page' => $i]))) ?>" 
                 class="btn" 
                 style="padding:8px 12px;text-decoration:none;border-radius:6px;min-width:40px;text-align:center;<?= $i == $page ? 'background:#3b82f6;color:white;font-weight:600' : '' ?>">
                <?= $i ?>
              </a>
            <?php endfor; ?>
            
            <?php if ($endPage < $totalPages): ?>
              <?php if ($endPage < $totalPages - 1): ?>
                <span style="padding:8px 4px;color:#6b7280">...</span>
              <?php endif; ?>
              <a href="<?= site_url('admin/patients?' . http_build_query(array_merge($_GET, ['page' => $totalPages]))) ?>" 
                 class="btn" 
                 style="padding:8px 12px;text-decoration:none;border-radius:6px;min-width:40px;text-align:center"><?= $totalPages ?></a>
            <?php endif; ?>
        </div>
        
        <?php if ($hasNext): ?>
          <a href="<?= site_url('admin/patients?' . http_build_query(array_merge($_GET, ['page' => $page + 1]))) ?>" 
             class="btn" 
             style="padding:8px 12px;text-decoration:none;border-radius:6px;display:inline-flex;align-items:center;gap:6px">
            Next <i class="fa-solid fa-chevron-right"></i>
          </a>
        <?php endif; ?>
      </div>
    </div>
  <?php endif; ?>
</section>

<!-- Add Patient Modal (keeping existing modal code) -->
<div id="addPatientModal" style="position:fixed;inset:0;display:none;align-items:center;justify-content:center;background:rgba(15,23,42,0.35);z-index:50;">
  <div style="width:95%;max-width:900px;background:white;border-radius:8px;box-shadow:0 20px 40px rgba(15,23,42,0.35);max-height:90vh;overflow-y:auto;">
    <div style="background:#2563eb;color:white;display:flex;justify-content:space-between;align-items:center;padding:16px 20px;border-bottom:1px solid #1d4ed8;">
      <h3 style="margin:0;font-size:1.1rem;">Add New Patient</h3>
      <button type="button" onclick="closePatientModal()" style="background:none;border:none;font-size:20px;color:white;cursor:pointer;" aria-label="Close modal">&times;</button>
    </div>
    <div style="padding:16px 20px;">
      <?php if(session()->getFlashdata('error')): ?>
        <div class="alert alert-error" style="background:#fef2f2;color:#dc2626;padding:12px;border-radius:8px;margin-bottom:16px;border:1px solid #fecaca;">
          <?= esc(session()->getFlashdata('error')) ?>
        </div>
      <?php endif; ?>

      <form id="addPatientForm" method="POST" action="<?= site_url('admin/patients') ?>" style="display:grid;gap:12px;">
        <?= csrf_field() ?>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
          <div>
            <label for="first_name" style="display:block;margin-bottom:4px;font-weight:600;">First Name <span style="color:#dc2626">*</span></label>
            <input type="text" id="first_name" name="first_name" value="<?= old('first_name') ?>" required style="width:100%;padding:8px;border:1px solid #d1d5db;border-radius:6px;">
          </div>
          <div>
            <label for="last_name" style="display:block;margin-bottom:4px;font-weight:600;">Last Name <span style="color:#dc2626">*</span></label>
            <input type="text" id="last_name" name="last_name" value="<?= old('last_name') ?>" required style="width:100%;padding:8px;border:1px solid #d1d5db;border-radius:6px;">
          </div>
        </div>

        <div>
          <label for="middle_name" style="display:block;margin-bottom:4px;font-weight:600;">Middle Name</label>
          <input type="text" id="middle_name" name="middle_name" value="<?= old('middle_name') ?>" style="width:100%;padding:8px;border:1px solid #d1d5db;border-radius:6px;">
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
          <div>
            <label for="date_of_birth" style="display:block;margin-bottom:4px;font-weight:600;">Date of Birth <span style="color:#dc2626">*</span></label>
            <input type="date" id="date_of_birth" name="date_of_birth" value="<?= old('date_of_birth') ?>" required style="width:100%;padding:8px;border:1px solid #d1d5db;border-radius:6px;">
          </div>
          <div>
            <label for="gender" style="display:block;margin-bottom:4px;font-weight:600;">Gender <span style="color:#dc2626">*</span></label>
            <select id="gender" name="gender" required style="width:100%;padding:8px;border:1px solid #d1d5db;border-radius:6px;">
              <option value="">Select Gender</option>
              <option value="male" <?= old('gender') == 'male' ? 'selected' : '' ?>>Male</option>
              <option value="female" <?= old('gender') == 'female' ? 'selected' : '' ?>>Female</option>
              <option value="other" <?= old('gender') == 'other' ? 'selected' : '' ?>>Other</option>
            </select>
          </div>
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
          <div>
            <label for="blood_type" style="display:block;margin-bottom:4px;font-weight:600;">Blood Type</label>
            <select id="blood_type" name="blood_type" style="width:100%;padding:8px;border:1px solid #d1d5db;border-radius:6px;">
              <option value="">Select Blood Type</option>
              <option value="A+" <?= old('blood_type') == 'A+' ? 'selected' : '' ?>>A+</option>
              <option value="A-" <?= old('blood_type') == 'A-' ? 'selected' : '' ?>>A-</option>
              <option value="B+" <?= old('blood_type') == 'B+' ? 'selected' : '' ?>>B+</option>
              <option value="B-" <?= old('blood_type') == 'B-' ? 'selected' : '' ?>>B-</option>
              <option value="AB+" <?= old('blood_type') == 'AB+' ? 'selected' : '' ?>>AB+</option>
              <option value="AB-" <?= old('blood_type') == 'AB-' ? 'selected' : '' ?>>AB-</option>
              <option value="O+" <?= old('blood_type') == 'O+' ? 'selected' : '' ?>>O+</option>
              <option value="O-" <?= old('blood_type') == 'O-' ? 'selected' : '' ?>>O-</option>
            </select>
          </div>
          <div>
            <label for="phone" style="display:block;margin-bottom:4px;font-weight:600;">Phone Number</label>
            <input type="tel" id="phone" name="phone" value="<?= old('phone') ?>" style="width:100%;padding:8px;border:1px solid #d1d5db;border-radius:6px;">
          </div>
        </div>

        <div>
          <label for="email" style="display:block;margin-bottom:4px;font-weight:600;">Email Address</label>
          <input type="email" id="email" name="email" value="<?= old('email') ?>" style="width:100%;padding:8px;border:1px solid #d1d5db;border-radius:6px;">
        </div>

        <div>
          <label for="address" style="display:block;margin-bottom:4px;font-weight:600;">Address</label>
          <textarea id="address" name="address" rows="3" style="width:100%;padding:8px;border:1px solid #d1d5db;border-radius:6px;resize:vertical;"><?= old('address') ?></textarea>
        </div>

        <div>
          <label for="city" style="display:block;margin-bottom:4px;font-weight:600;">City</label>
          <input type="text" id="city" name="city" value="<?= old('city') ?>" style="width:100%;padding:8px;border:1px solid #d1d5db;border-radius:6px;">
        </div>

        <h3 style="margin: 1.5rem 0 0.75rem 0; color: #374151; font-size: 1rem;">Emergency Contact</h3>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
          <div>
            <label for="emergency_contact_name" style="display:block;margin-bottom:4px;font-weight:600;">Emergency Contact Name</label>
            <input type="text" id="emergency_contact_name" name="emergency_contact_name" value="<?= old('emergency_contact_name') ?>" style="width:100%;padding:8px;border:1px solid #d1d5db;border-radius:6px;">
          </div>
          <div>
            <label for="emergency_contact_phone" style="display:block;margin-bottom:4px;font-weight:600;">Emergency Contact Phone</label>
            <input type="tel" id="emergency_contact_phone" name="emergency_contact_phone" value="<?= old('emergency_contact_phone') ?>" style="width:100%;padding:8px;border:1px solid #d1d5db;border-radius:6px;">
          </div>
        </div>

        <div>
          <label for="emergency_contact_relation" style="display:block;margin-bottom:4px;font-weight:600;">Relationship</label>
          <input type="text" id="emergency_contact_relation" name="emergency_contact_relation" value="<?= old('emergency_contact_relation') ?>" placeholder="e.g., Spouse, Parent, Sibling" style="width:100%;padding:8px;border:1px solid #d1d5db;border-radius:6px;">
        </div>

        <h3 style="margin: 1.5rem 0 0.75rem 0; color: #374151; font-size: 1rem;">Insurance Information</h3>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
          <div>
            <label for="insurance_provider" style="display:block;margin-bottom:4px;font-weight:600;">Insurance Provider</label>
            <input type="text" id="insurance_provider" name="insurance_provider" value="<?= old('insurance_provider') ?>" style="width:100%;padding:8px;border:1px solid #d1d5db;border-radius:6px;">
          </div>
          <div>
            <label for="insurance_number" style="display:block;margin-bottom:4px;font-weight:600;">Insurance Number</label>
            <input type="text" id="insurance_number" name="insurance_number" value="<?= old('insurance_number') ?>" style="width:100%;padding:8px;border:1px solid #d1d5db;border-radius:6px;">
          </div>
        </div>

        <h3 style="margin: 1.5rem 0 0.75rem 0; color: #374151; font-size: 1rem;">Medical Information</h3>

        <div>
          <label for="allergies" style="display:block;margin-bottom:4px;font-weight:600;">Allergies</label>
          <textarea id="allergies" name="allergies" rows="3" placeholder="List any known allergies" style="width:100%;padding:8px;border:1px solid #d1d5db;border-radius:6px;resize:vertical;"><?= old('allergies') ?></textarea>
        </div>

        <div>
          <label for="medical_history" style="display:block;margin-bottom:4px;font-weight:600;">Medical History</label>
          <textarea id="medical_history" name="medical_history" rows="3" placeholder="Previous medical conditions, surgeries, etc." style="width:100%;padding:8px;border:1px solid #d1d5db;border-radius:6px;resize:vertical;"><?= old('medical_history') ?></textarea>
        </div>

        <div style="display:flex;justify-content:flex-end;gap:8px;margin-top:12px;">
          <button type="button" class="btn btn-secondary" onclick="closePatientModal()">Cancel</button>
          <button type="submit" class="btn">
            <i class="fas fa-save"></i> Create Patient
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('assets/js/rbac.js') ?>"></script>
<script>
  function showPatientModal() {
    var modal = document.getElementById('addPatientModal');
    if (modal) {
      modal.style.display = 'flex';
      document.body.style.overflow = 'hidden';
    }
  }

  function closePatientModal() {
    var modal = document.getElementById('addPatientModal');
    if (modal) {
      modal.style.display = 'none';
      document.body.style.overflow = 'auto';
      var form = document.getElementById('addPatientForm');
      if (form) form.reset();
    }
  }

  // Close modal when clicking outside
  document.addEventListener('click', function(e) {
    var modal = document.getElementById('addPatientModal');
    if (modal && e.target === modal) {
      closePatientModal();
    }
  });

  // Close modal with Escape key
  document.addEventListener('keydown', function(e) {
    var modal = document.getElementById('addPatientModal');
    if (modal && modal.style.display === 'flex' && e.key === 'Escape') {
      closePatientModal();
    }
  });

  // Keyboard navigation for table rows
  document.addEventListener('keydown', function(e) {
    if (e.target.tagName === 'A' && e.key === 'Enter') {
      e.target.click();
    }
  });
</script>
<?= $this->endSection() ?>
