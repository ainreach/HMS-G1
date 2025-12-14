# 🔄 PATIENT ASSIGNMENT FLOW

## 📋 **PAANO GUMAGANA ANG FLOW:**

### **SCENARIO 1: OUT-PATIENT (Checkup/Consultation)**

```
1. Receptionist → Register New Patient
   └─> Select "Checkup" as Admission Type
   └─> Select Doctor (e.g., Dr. Juan from Pediatrics)
   └─> Set Appointment Date & Time
   
2. System Action:
   └─> Creates Patient Record
   └─> Creates Appointment with doctor_id = selected doctor
   
3. Doctor Dashboard:
   └─> Sees patient in "Today's Appointments"
   └─> Patient appears in "My Assigned Patients" list
   └─> Assignment Type: "Appointment" (yellow badge)
```

### **SCENARIO 2: IN-PATIENT (Admission)**

```
1. Receptionist → Register New Patient
   └─> Select "Admission" as Admission Type
   └─> Select Attending Physician (e.g., Dr. Maria from Pediatrics)
   └─> Assign Room & Bed
   
2. System Action:
   └─> Creates Patient Record
   └─> Sets patient.attending_physician_id = selected doctor
   └─> Sets patient.assigned_room_id = selected room
   
3. Doctor Dashboard:
   └─> Sees patient in "My Assigned Patients" section
   └─> Assignment Type: "Attending Physician" (blue badge)
   └─> Can see room assignment
```

---

## ✅ **ANO ANG NANGYAYARI:**

### **Kapag Pumili ang Receptionist ng Doctor:**

1. **Out-Patient (Checkup):**
   - Patient → Appointment → `appointments.doctor_id` = selected doctor
   - Doctor makikita ang patient sa **"Today's Appointments"**
   - Doctor makikita ang patient sa **"My Assigned Patients"** list

2. **In-Patient (Admission):**
   - Patient → `patients.attending_physician_id` = selected doctor
   - Doctor makikita ang patient sa **"My Assigned Patients"** section
   - Doctor makikita ang room assignment

---

## 🎯 **EXAMPLE FLOW:**

### **Example: Receptionist pumili ng "Dr. Juan - Pediatrics"**

**Step 1: Receptionist Action**
```
Receptionist Dashboard
  → Click "Register New Patient"
  → Fill patient details
  → Select "Checkup" or "Admission"
  → Select Doctor: "Dr. Juan - Pediatrics"
  → Save
```

**Step 2: System Processing**
```
If Checkup:
  ✅ Creates appointment
  ✅ Sets appointment.doctor_id = Dr. Juan's ID
  
If Admission:
  ✅ Sets patient.attending_physician_id = Dr. Juan's ID
  ✅ Assigns room
```

**Step 3: Doctor Sees Patient**
```
Dr. Juan logs in
  → Opens Dashboard
  → Sees patient in:
     • "Today's Appointments" (if checkup)
     • "My Assigned Patients" (both checkup & admission)
  → Can click patient to view details
  → Can start consultation
```

---

## 📊 **VISUAL FLOW:**

```
┌─────────────────┐
│  RECEPTIONIST   │
│                 │
│ 1. Register     │
│    Patient      │
│                 │
│ 2. Select       │
│    Doctor       │
│    (Pediatrics) │
└────────┬────────┘
         │
         ▼
┌─────────────────┐
│     SYSTEM      │
│                 │
│ If Checkup:     │
│  → Appointment  │
│    (doctor_id)  │
│                 │
│ If Admission:   │
│  → Patient      │
│    (attending_  │
│     physician_  │
│     id)         │
└────────┬────────┘
         │
         ▼
┌─────────────────┐
│     DOCTOR      │
│                 │
│ Dashboard:      │
│  ✅ Sees        │
│     Patient     │
│                 │
│ Patient List:   │
│  ✅ Sees        │
│     Patient     │
│     with        │
│     Assignment  │
│     Type        │
└─────────────────┘
```

---

## 🔍 **HOW TO VERIFY:**

### **Check if Patient is Assigned:**

1. **Login as Doctor**
2. **Go to Dashboard**
   - Check "Today's Appointments" (for checkups)
   - Check "My Assigned Patients" (for both)
3. **Go to Patient List**
   - Should see patient with badge:
     - 🔵 "Attending Physician" (In-Patient)
     - 🟡 "Appointment" (Out-Patient)

---

## ✅ **SUMMARY:**

**Oo, kapag pumili ang receptionist ng doctor (like Pediatrics), mapupunta ang patient sa doctor na iyon!**

**How:**
- **Out-Patient:** Through `appointments.doctor_id`
- **In-Patient:** Through `patients.attending_physician_id`

**Doctor makikita:**
- ✅ Dashboard → "My Assigned Patients"
- ✅ Dashboard → "Today's Appointments" (for checkups)
- ✅ Patient List → All assigned patients with assignment type

**Status: WORKING ✅**

---

**Last Updated:** <?= date('Y-m-d H:i:s') ?>

