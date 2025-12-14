# 🎨 DOCTOR DASHBOARD CUSTOMIZATION GUIDE

## 📋 **ANO ANG GINAWA:**

Nag-create ng **customization system** para sa doctor dashboard na nagpapakita ng **iba't ibang content** depende sa **specialization** ng doctor.

---

## ✅ **ANO ANG GUMAGANA:**

### **1. Automatic Customization**
- Kapag nag-login ang doctor, automatic na nag-detect ang system ng specialization
- Nagpapakita ng **customized widgets** at **sections** base sa specialization

### **2. Specialization Categories:**
- **Pediatrics** - Pediatrician, Neonatologist, etc.
- **Surgery** - All surgeons
- **Cardiology** - Cardiologist, Cardiac Surgeon
- **Emergency** - Emergency Medicine, Critical Care
- **Radiology** - Radiologist
- **OB-GYN** - Obstetrician-Gynecologist
- **Psychiatry** - Psychiatrist
- **Default** - General Practitioner, Family Medicine, Internist, etc.

---

## 🎯 **EXAMPLES:**

### **Example 1: Pediatrician Dashboard**
**Makikita:**
- ✅ Today Appointments
- ✅ Pending Lab Results
- ✅ Assigned Patients
- ✅ **Pediatric Patients** (Custom KPI - count ng patients < 18 years old)
- ✅ **Vaccination Record** button (Custom Quick Action)
- ✅ **Vaccination Schedule** section (Custom Section)

**Hindi makikita:**
- ❌ Surgery Schedule (hindi relevant)

---

### **Example 2: Surgeon Dashboard**
**Makikita:**
- ✅ Pending Lab Results
- ✅ Assigned Patients
- ✅ **Scheduled Surgeries** (Custom KPI)
- ✅ **Surgery Schedule** section (Custom Section)
- ✅ **Pre-Op Patients** section (Custom Section)
- ✅ **Schedule Surgery** button (Custom Quick Action)

**Hindi makikita:**
- ❌ Today Appointments (hindi relevant para sa surgeons)

---

### **Example 3: Cardiologist Dashboard**
**Makikita:**
- ✅ Today Appointments
- ✅ Pending Lab Results
- ✅ Assigned Patients
- ✅ **Pending ECG** (Custom KPI)
- ✅ **Cardiac Patients** section (Custom Section)
- ✅ **ECG Results** section (Custom Section)
- ✅ **Request ECG** button (Custom Quick Action)

---

### **Example 4: Emergency Medicine Doctor Dashboard**
**Makikita:**
- ✅ Pending Lab Results
- ✅ Assigned Patients
- ✅ **Emergency Cases** (Custom KPI)
- ✅ **Active Emergencies** section (Custom Section)
- ✅ **Emergency Admit** button (Custom Quick Action)

**Hindi makikita:**
- ❌ Today Appointments (hindi relevant sa ER)
- ❌ Weekly Schedule (hindi relevant sa ER)

---

### **Example 5: Radiologist Dashboard**
**Makikita:**
- ✅ **Imaging Requests** (Custom KPI)
- ✅ **Completed Scans** (Custom KPI)
- ✅ **Imaging Requests** section (Custom Section)
- ✅ **Completed Reports** section (Custom Section)
- ✅ **View Imaging Requests** button (Custom Quick Action)
- ✅ **Upload Report** button (Custom Quick Action)

**Hindi makikita:**
- ❌ Today Appointments
- ❌ Assigned Patients
- ❌ Lab Tests
- ❌ Medical Records

---

### **Example 6: OB-GYN Dashboard**
**Makikita:**
- ✅ Today Appointments
- ✅ Pending Lab Results
- ✅ Assigned Patients
- ✅ **Prenatal Patients** (Custom KPI)
- ✅ **Prenatal Patients** section (Custom Section)
- ✅ **Delivery Schedule** section (Custom Section)
- ✅ **Prenatal Checkup** button (Custom Quick Action)

---

## 🔧 **PAANO I-CUSTOMIZE:**

### **File: `app/Config/DoctorDashboardCustomization.php`**

#### **1. Add New Specialization Category:**
```php
// Add sa getSpecializationCategory() method
if (stripos($specialization, 'your_specialization') !== false) {
    return 'your_category';
}
```

#### **2. Add Widget Configuration:**
```php
// Add sa getConfig() method
'your_category' => [
    'kpis' => [
        'today_appointments' => true,
        'pending_lab_results' => true,
        'your_custom_kpi' => true,
    ],
    'sections' => [
        'quick_actions' => true,
        'today_appointments' => true,
        'your_custom_section' => true,
    ],
    'quick_actions' => [
        'new_medical_record' => true,
        'your_custom_action' => true,
    ],
],
```

#### **3. Add Custom KPI Data:**
```php
// Add sa getCustomKPIs() method
case 'your_category':
    // Your custom KPI logic
    $customKPIs['your_custom_kpi'] = $count;
    break;
```

---

## 📊 **CURRENT CUSTOMIZATIONS:**

### **KPIs (Key Performance Indicators):**
- ✅ Today Appointments
- ✅ Pending Lab Results
- ✅ Assigned Patients
- ✅ **Pediatric Patients** (Pediatrics)
- ✅ **Pending ECG** (Cardiology)
- ✅ **Emergency Cases** (Emergency)
- ✅ **Prenatal Patients** (OB-GYN)

### **Sections:**
- ✅ Quick Actions
- ✅ Today's Appointments
- ✅ Assigned Patients
- ✅ Weekly Schedule
- ✅ Recent Medical Records
- ✅ Pending Lab Tests
- ✅ **Vaccination Schedule** (Pediatrics)
- ✅ **Surgery Schedule** (Surgery)
- ✅ **ECG Results** (Cardiology)
- ✅ **Active Emergencies** (Emergency)
- ✅ **Delivery Schedule** (OB-GYN)

### **Quick Actions:**
- ✅ New Medical Record
- ✅ Request Lab Test
- ✅ View Patients
- ✅ Admit Patient
- ✅ Prescriptions
- ✅ Lab Results
- ✅ **Vaccination Record** (Pediatrics)
- ✅ **Schedule Surgery** (Surgery)
- ✅ **Request ECG** (Cardiology)
- ✅ **Emergency Admit** (Emergency)
- ✅ **Prenatal Checkup** (OB-GYN)

---

## 🎨 **HOW IT WORKS:**

### **Step 1: Doctor Logs In**
```
Doctor → Login → System detects specialization
```

### **Step 2: System Loads Configuration**
```php
$dashboardConfig = DoctorDashboardCustomization::getWidgets($specialization);
$customKPIs = DoctorDashboardCustomization::getCustomKPIs($specialization, $doctorId);
```

### **Step 3: Dashboard Shows Customized Content**
```php
// View checks configuration
<?php if (!empty($dashboardConfig['kpis']['today_appointments'])): ?>
    // Show Today Appointments KPI
<?php endif; ?>
```

---

## ✅ **BENEFITS:**

1. **Relevant Content** - Bawat doctor nakikita lang ang relevant sa specialization nila
2. **Cleaner Dashboard** - Hindi cluttered, focused lang sa kailangan
3. **Easy to Customize** - Puwede mag-add ng bagong customization sa config file
4. **Automatic** - Hindi kailangan ng manual setup, automatic based sa specialization

---

## 📝 **HOW TO ADD NEW CUSTOMIZATION:**

### **Example: Add "Dermatology" Customization**

**Step 1: Update `getSpecializationCategory()`:**
```php
if (stripos($specialization, 'dermatologist') !== false) {
    return 'dermatology';
}
```

**Step 2: Add Configuration:**
```php
'dermatology' => [
    'kpis' => [
        'today_appointments' => true,
        'assigned_patients' => true,
        'skin_conditions' => true, // Custom KPI
    ],
    'sections' => [
        'quick_actions' => true,
        'today_appointments' => true,
        'skin_conditions_list' => true, // Custom section
    ],
    'quick_actions' => [
        'new_medical_record' => true,
        'view_patients' => true,
        'skin_examination' => true, // Custom action
    ],
],
```

**Step 3: Add Custom KPI Data:**
```php
case 'dermatology':
    // Count patients with skin conditions
    $skinConditionsCount = // your logic here
    $customKPIs['skin_conditions'] = $skinConditionsCount;
    break;
```

**Step 4: Add Custom Section sa View:**
```php
<?php if (!empty($dashboardConfig['sections']['skin_conditions_list'])): ?>
    <section class="panel">
        <div class="panel-head"><h2>Skin Conditions</h2></div>
        <!-- Your custom content -->
    </section>
<?php endif; ?>
```

---

## 🎯 **SUMMARY:**

✅ **Working:** Automatic customization based on specialization  
✅ **Configurable:** Easy to add new customizations  
✅ **Flexible:** Puwede mag-show/hide ng widgets  
✅ **Relevant:** Bawat doctor nakikita lang ang kailangan nila  

---

**Status: COMPLETE - Dashboard customization system ready!**

**Last Updated:** 2024-12-19

