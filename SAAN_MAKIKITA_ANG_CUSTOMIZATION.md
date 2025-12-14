# 📍 SAAN MAKIKITA ANG CUSTOMIZATION

## 🗂️ **FILE LOCATIONS (Saan naka-save):**

### **1. Configuration File (Settings)**
```
📁 app/Config/DoctorDashboardCustomization.php
```
**Ano ito:** Dito naka-define ang lahat ng customization settings per specialization

---

### **2. Controller (Logic)**
```
📁 app/Controllers/Doctor.php
```
**Ano ito:** Dito naglo-load ang customization config at nagpapakita sa dashboard

---

### **3. Dashboard View (UI)**
```
📁 app/Views/doctor/dashboard.php
```
**Ano ito:** Dito ang actual dashboard na nakikita ng doctors

---

### **4. Documentation**
```
📁 DOCTOR_DASHBOARD_CUSTOMIZATION_GUIDE.md
```
**Ano ito:** Complete guide kung paano i-customize

---

## 👀 **PAANO MAKIKITA SA ACTUAL SYSTEM:**

### **STEP 1: Create/Edit Doctor Account**

**Path:**
```
Admin Panel → Users → Create New User (or Edit Existing)
```

**Steps:**
1. Login as **Admin**
2. Go to **Users** → Click **"Create New User"** or **Edit** existing doctor
3. Set **Role:** `doctor`
4. Set **Specialization:** (e.g., "Pediatrician (Pedia)", "Cardiologist", "General Surgeon")
5. Set **Department:** (optional, pero recommended)
6. Save

---

### **STEP 2: Login as Doctor**

**Path:**
```
Login Page → Select "Doctor" → Enter credentials
```

**Steps:**
1. Logout from Admin
2. Go to Login page
3. Select **"Doctor"** role
4. Login using doctor credentials
5. You'll be redirected to: `/dashboard/doctor`

---

### **STEP 3: View Customized Dashboard**

**Path:**
```
Doctor Dashboard: http://localhost:8080/dashboard/doctor
```

**Ano ang makikita:**

#### **A. Header Section:**
- **Title:** Shows specialization (e.g., "Pediatrician (Pedia)")
- **Subtitle:** Shows department (e.g., "Pediatrics Department")
- **Badge:** Shows specialization badge

#### **B. KPI Cards (Top Section):**
**Default (General Practitioner):**
- ✅ Today Appointments
- ✅ Pending Results
- ✅ Assigned Patients

**Pediatrician:**
- ✅ Today Appointments
- ✅ Pending Results
- ✅ Assigned Patients
- ✅ **Pediatric Patients** (Custom - yellow card)

**Cardiologist:**
- ✅ Today Appointments
- ✅ Pending Results
- ✅ Assigned Patients
- ✅ **Pending ECG** (Custom - red card)

**Surgeon:**
- ✅ Pending Results
- ✅ Assigned Patients
- ✅ **Scheduled Surgeries** (Custom)
- ❌ Today Appointments (hidden)

**Emergency Doctor:**
- ✅ Pending Results
- ✅ Assigned Patients
- ✅ **Emergency Cases** (Custom - red card)
- ❌ Today Appointments (hidden)

#### **C. Quick Actions:**
**Default:**
- New Medical Record
- Request Lab Test
- View Patients
- Admit Patient
- Prescriptions
- Lab Results

**Pediatrician:**
- All default actions
- ✅ **Vaccination Record** (Custom - yellow button)

**Surgeon:**
- All default actions
- ✅ **Schedule Surgery** (Custom - red button)

**Cardiologist:**
- All default actions
- ✅ **Request ECG** (Custom - red button)

**Emergency Doctor:**
- All default actions
- ✅ **Emergency Admit** (Custom - red button)

#### **D. Main Sections:**
**Default:**
- ✅ Today's Appointments
- ✅ My Assigned Patients
- ✅ My Weekly Schedule
- ✅ Recent Medical Records
- ✅ Pending Lab Tests

**Surgeon:**
- ❌ Today's Appointments (hidden)
- ✅ My Assigned Patients
- ✅ **Surgery Schedule** (Custom section)
- ✅ **Pre-Op Patients** (Custom section)
- ✅ My Weekly Schedule
- ✅ Recent Medical Records
- ✅ Pending Lab Tests

**Emergency Doctor:**
- ❌ Today's Appointments (hidden)
- ✅ My Assigned Patients
- ✅ **Active Emergencies** (Custom section)
- ❌ My Weekly Schedule (hidden)
- ✅ Recent Medical Records
- ✅ Pending Lab Tests

**Radiologist:**
- ❌ Today's Appointments (hidden)
- ❌ My Assigned Patients (hidden)
- ✅ **Imaging Requests** (Custom section)
- ✅ **Completed Reports** (Custom section)
- ✅ My Weekly Schedule
- ❌ Recent Medical Records (hidden)
- ❌ Pending Lab Tests (hidden)

---

## 🧪 **PAANO I-TEST:**

### **Test 1: Pediatrician**
1. Create doctor with specialization: **"Pediatrician (Pedia)"**
2. Login as that doctor
3. **Check:**
   - ✅ Header shows "Pediatrician (Pedia)"
   - ✅ KPI shows "Pediatric Patients" (yellow card)
   - ✅ Quick Actions shows "Vaccination Record" button

### **Test 2: Cardiologist**
1. Create doctor with specialization: **"Cardiologist"**
2. Login as that doctor
3. **Check:**
   - ✅ Header shows "Cardiologist"
   - ✅ KPI shows "Pending ECG" (red card)
   - ✅ Quick Actions shows "Request ECG" button

### **Test 3: Surgeon**
1. Create doctor with specialization: **"General Surgeon"** or **"Orthopedic Surgeon"**
2. Login as that doctor
3. **Check:**
   - ✅ Header shows specialization
   - ✅ "Today Appointments" section is HIDDEN
   - ✅ Quick Actions shows "Schedule Surgery" button

### **Test 4: Emergency Doctor**
1. Create doctor with specialization: **"Emergency Medicine Doctor"**
2. Login as that doctor
3. **Check:**
   - ✅ Header shows "Emergency Medicine Doctor"
   - ✅ KPI shows "Emergency Cases" (red card)
   - ✅ "Today Appointments" section is HIDDEN
   - ✅ "Weekly Schedule" section is HIDDEN
   - ✅ Quick Actions shows "Emergency Admit" button

---

## 📝 **PAANO I-EDIT/CUSTOMIZE:**

### **Option 1: Edit Configuration File**

**File:** `app/Config/DoctorDashboardCustomization.php`

**Example - Add new KPI:**
```php
'pediatrics' => [
    'kpis' => [
        'today_appointments' => true,
        'pending_lab_results' => true,
        'assigned_patients' => true,
        'pediatric_patients' => true,
        'vaccination_due' => true, // NEW KPI
    ],
],
```

**Example - Hide section:**
```php
'pediatrics' => [
    'sections' => [
        'today_appointments' => true,
        'assigned_patients' => true,
        'weekly_schedule' => false, // HIDE THIS
    ],
],
```

**Example - Add custom action:**
```php
'pediatrics' => [
    'quick_actions' => [
        'new_medical_record' => true,
        'vaccination_record' => true,
        'growth_chart' => true, // NEW ACTION
    ],
],
```

---

### **Option 2: Add Custom KPI Data**

**File:** `app/Config/DoctorDashboardCustomization.php`

**Method:** `getCustomKPIs()`

**Example:**
```php
case 'pediatrics':
    // Count vaccination due
    $vaccinationDue = // your logic here
    $customKPIs['vaccination_due'] = $vaccinationDue;
    break;
```

---

### **Option 3: Add Custom Section in View**

**File:** `app/Views/doctor/dashboard.php`

**Example:**
```php
<?php if (!empty($dashboardConfig['sections']['vaccination_schedule'])): ?>
    <section class="panel">
        <div class="panel-head">
            <h2>Vaccination Schedule</h2>
        </div>
        <div class="panel-body">
            <!-- Your custom content here -->
        </div>
    </section>
<?php endif; ?>
```

---

## 🎯 **QUICK REFERENCE:**

### **Where to see customization:**
1. **Browser:** `http://localhost:8080/dashboard/doctor` (login as doctor)
2. **Files:** `app/Config/DoctorDashboardCustomization.php` (edit settings)
3. **View:** `app/Views/doctor/dashboard.php` (edit UI)

### **What to check:**
- ✅ Header shows specialization
- ✅ KPI cards (top section) - may custom KPIs
- ✅ Quick Actions buttons - may custom buttons
- ✅ Main sections - may hidden/shown sections

### **How to test:**
1. Create different doctors with different specializations
2. Login as each doctor
3. Compare dashboards - dapat iba-iba ang makikita

---

## ✅ **SUMMARY:**

**Saan makikita:**
- 🌐 **Browser:** Login as doctor → Dashboard (`/dashboard/doctor`)
- 📁 **Files:** `app/Config/DoctorDashboardCustomization.php`
- 📁 **View:** `app/Views/doctor/dashboard.php`

**Paano makikita:**
1. Create doctor account with specialization
2. Login as that doctor
3. View dashboard - makikita ang customized content

**Paano i-edit:**
1. Open `app/Config/DoctorDashboardCustomization.php`
2. Edit configuration per specialization
3. Refresh dashboard to see changes

---

**Status: READY TO TEST!** 🚀

**Last Updated:** 2024-12-19

