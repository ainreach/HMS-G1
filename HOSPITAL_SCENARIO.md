# 🏥 HOSPITAL MANAGEMENT SYSTEM - REALISTIC SCENARIOS

## 📋 **SCENARIO 1: EMERGENCY ADMISSION - PEDIATRIC PATIENT**

### **Situation:**
**Patient:** Maria Santos, 5 years old, Female  
**Complaint:** High fever (39.5°C), severe cough, difficulty breathing  
**Arrival:** Emergency Department, 2:00 PM  
**Condition:** Critical - needs immediate admission

---

### **COMPLETE FLOW:**

#### **STEP 1: RECEPTIONIST - Patient Registration**
- **Actor:** Receptionist (Jane)
- **Action:**
  - Receives patient at Emergency Department
  - Opens HMS → "Register New Patient"
  - Enters patient details:
    - Name: Maria Santos
    - DOB: 2019-03-15
    - Gender: Female
    - Contact: 0917-123-4567
    - Address: 123 Main St, Quezon City
    - Emergency Contact: Juan Santos (Father) - 0918-765-4321
  - **Admission Type:** "Admission" (In-Patient)
  - **Attending Physician:** Dr. Sarah Cruz (Pediatrician - Pediatrics Department)
  - **Room Assignment:** Room 201, Bed 1 (Pediatric Ward)
  - **Priority:** Emergency
  - Saves patient record

**System Records:**
- ✅ Patient created with ID #1234
- ✅ `attending_physician_id` = Dr. Sarah Cruz
- ✅ `assigned_room_id` = Room 201
- ✅ `status` = "admitted"
- ✅ `admission_type` = "admission"

---

#### **STEP 2: DOCTOR - Initial Assessment**
- **Actor:** Dr. Sarah Cruz (Pediatrician)
- **Action:**
  - Logs into HMS → Doctor Dashboard
  - Sees notification: "New Patient: Maria Santos"
  - Opens "My Assigned Patients" → Finds Maria Santos
  - Clicks "Start Consultation"
  - Performs initial assessment:
    - **Vital Signs:**
      - Temperature: 39.5°C
      - Blood Pressure: 110/70 mmHg
      - Heart Rate: 120 bpm
      - Respiratory Rate: 28/min
      - Oxygen Saturation: 92%
      - Weight: 18 kg
      - Height: 105 cm
      - BMI: 16.3
    - **Chief Complaint:** High fever, severe cough, difficulty breathing
    - **History:** Started 2 days ago, getting worse
    - **Physical Examination:**
      - Chest: Bilateral rales
      - Throat: Inflamed
      - Ears: Normal
    - **Initial Diagnosis:** Severe Pneumonia (suspected)
  - Creates Medical Record #MR-001
  - **Orders:**
    - Lab Tests: Complete Blood Count (CBC), Chest X-Ray, Blood Culture
    - Medications: IV Antibiotics (pending lab results)

**System Records:**
- ✅ Medical Record created
- ✅ Lab Requests created (pending)
- ✅ Doctor Orders created (pending)

---

#### **STEP 3: NURSE - Patient Care & Vital Monitoring**
- **Actor:** Nurse (Anna)
- **Action:**
  - Logs into HMS → Nurse Dashboard
  - Sees assigned patient: Maria Santos (Room 201, Bed 1)
  - Updates patient status:
    - Administered IV fluids
    - Started oxygen therapy (2L/min)
    - Monitored vital signs every 2 hours
  - Records nursing notes:
    - "Patient restless, parents present"
    - "Oxygen saturation improved to 95%"
    - "Temperature: 38.8°C (slightly decreased)"
  - Updates patient chart

**System Records:**
- ✅ Nursing notes updated
- ✅ Vital signs logged
- ✅ Patient status updated

---

#### **STEP 4: LAB STAFF - Laboratory Tests**
- **Actor:** Lab Technician (Mark)
- **Action:**
  - Logs into HMS → Lab Staff Dashboard
  - Sees pending lab requests:
    - Maria Santos - CBC
    - Maria Santos - Chest X-Ray
    - Maria Santos - Blood Culture
  - Processes tests:
    - **CBC Results:**
      - WBC: 15,000/μL (High - indicates infection)
      - RBC: 4.2 million/μL (Normal)
      - Hemoglobin: 12.5 g/dL (Normal)
      - Platelets: 250,000/μL (Normal)
    - **Chest X-Ray:** Bilateral infiltrates, consistent with pneumonia
    - **Blood Culture:** Pending (48 hours)
  - Enters results into HMS
  - Marks tests as "Completed"
  - System notifies Dr. Sarah Cruz

**System Records:**
- ✅ Lab results entered
- ✅ Lab requests marked as completed
- ✅ Doctor notified

---

#### **STEP 5: DOCTOR - Review Results & Prescribe Treatment**
- **Actor:** Dr. Sarah Cruz
- **Action:**
  - Receives notification: "Lab Results Available"
  - Opens "Lab Requests" → Reviews Maria Santos results
  - Confirms diagnosis: **Severe Bacterial Pneumonia**
  - Updates Medical Record:
    - **Final Diagnosis:** Severe Bacterial Pneumonia
    - **Treatment Plan:**
      - IV Antibiotics: Ceftriaxone 500mg IV every 12 hours (7 days)
      - Antipyretics: Paracetamol 250mg every 6 hours (as needed)
      - Oxygen therapy: Continue 2L/min
      - IV Fluids: Continue maintenance
  - Creates Doctor Orders:
    - **Medication Orders:**
      - Ceftriaxone 500mg IV - 7 days
      - Paracetamol 250mg - PRN
    - **Treatment Orders:**
      - Continue oxygen therapy
      - Monitor vital signs every 4 hours
      - Follow-up chest X-Ray in 3 days

**System Records:**
- ✅ Medical Record updated
- ✅ Doctor Orders created
- ✅ Pharmacy notified (medication orders)
- ✅ Nurse notified (treatment orders)

---

#### **STEP 6: PHARMACIST - Medication Dispensing**
- **Actor:** Pharmacist (Lisa)
- **Action:**
  - Logs into HMS → Pharmacist Dashboard
  - Sees medication orders:
    - Maria Santos - Ceftriaxone 500mg IV (7 days supply)
    - Maria Santos - Paracetamol 250mg (PRN)
  - Checks inventory:
    - Ceftriaxone: Available (Stock: 50 vials)
    - Paracetamol: Available (Stock: 200 tablets)
  - Dispenses medications:
    - Ceftriaxone: 14 vials (7 days × 2 doses/day)
    - Paracetamol: 10 tablets (PRN)
  - Updates inventory:
    - Ceftriaxone: 50 → 36 vials
    - Paracetamol: 200 → 190 tablets
  - Marks orders as "Dispensed"
  - System notifies Nurse

**System Records:**
- ✅ Medications dispensed
- ✅ Inventory updated
- ✅ Orders marked as dispensed
- ✅ Nurse notified

---

#### **STEP 7: NURSE - Medication Administration**
- **Actor:** Nurse (Anna)
- **Action:**
  - Receives notification: "Medications Ready"
  - Picks up medications from Pharmacy
  - Administers first dose:
    - Ceftriaxone 500mg IV - 2:00 PM
    - Records administration time
  - Updates patient chart
  - Continues monitoring

**System Records:**
- ✅ Medication administration logged
- ✅ Patient chart updated

---

#### **STEP 8: DOCTOR - Daily Rounds & Progress**
- **Actor:** Dr. Sarah Cruz
- **Action:**
  - **Day 2:** Patient improving
    - Temperature: 37.8°C (decreased)
    - Oxygen saturation: 96% (improved)
    - Cough: Less severe
    - Updates Medical Record: "Patient responding well to treatment"
  
  - **Day 3:** Significant improvement
    - Temperature: 37.2°C (normal)
    - Breathing: Normal
    - Orders follow-up Chest X-Ray
  
  - **Day 4:** Lab Staff processes X-Ray
    - Results: Significant improvement, infiltrates clearing
  
  - **Day 5:** Patient stable
    - Plans for discharge preparation

---

#### **STEP 9: ACCOUNTANT - Billing Preparation**
- **Actor:** Accountant (Robert)
- **Action:**
  - Logs into HMS → Accountant Dashboard
  - Reviews patient charges:
    - Room charges: 5 days × ₱2,000/day = ₱10,000
    - Doctor fees: ₱5,000
    - Lab tests: CBC (₱500) + X-Ray (₱1,500) + Blood Culture (₱800) = ₱2,800
    - Medications: Ceftriaxone (₱3,500) + Paracetamol (₱200) = ₱3,700
    - IV fluids: ₱1,500
    - Oxygen therapy: ₱2,000
    - **Total: ₱25,500**
  - Prepares consolidated bill
  - Checks insurance coverage (if applicable)
  - Generates invoice

**System Records:**
- ✅ Charges calculated
- ✅ Consolidated bill created
- ✅ Invoice generated

---

#### **STEP 10: DOCTOR - Discharge Planning**
- **Actor:** Dr. Sarah Cruz
- **Action:**
  - **Day 6:** Patient fully recovered
    - Temperature: 36.8°C (normal)
    - No respiratory distress
    - Chest X-Ray: Clear
    - Blood Culture: Negative (no bacteria)
  - Prepares discharge:
    - **Discharge Diagnosis:** Severe Bacterial Pneumonia (Resolved)
    - **Discharge Medications:**
      - Amoxicillin 250mg - 5 days (oral)
      - Paracetamol 250mg - PRN
    - **Discharge Instructions:**
      - Complete antibiotic course
      - Follow-up in 1 week
      - Rest at home
      - Monitor for fever
  - Creates discharge order
  - System notifies Receptionist and Accountant

**System Records:**
- ✅ Discharge order created
- ✅ Discharge medications prescribed
- ✅ Receptionist notified
- ✅ Accountant notified

---

#### **STEP 11: PHARMACIST - Discharge Medications**
- **Actor:** Pharmacist (Lisa)
- **Action:**
  - Receives discharge medication orders
  - Dispenses:
    - Amoxicillin 250mg: 15 tablets (5 days)
    - Paracetamol 250mg: 10 tablets (PRN)
  - Updates inventory
  - Prepares medication bag

**System Records:**
- ✅ Discharge medications dispensed
- ✅ Inventory updated

---

#### **STEP 12: ACCOUNTANT - Final Billing & Payment**
- **Actor:** Accountant (Robert)
- **Action:**
  - Finalizes bill: ₱25,500
  - Adds discharge medications: ₱500
  - **Final Total: ₱26,000**
  - Processes payment:
    - Insurance covers: ₱20,000
    - Patient pays: ₱6,000
  - Generates receipt
  - Marks bill as "Paid"

**System Records:**
- ✅ Final bill calculated
- ✅ Payment processed
- ✅ Receipt generated
- ✅ Bill marked as paid

---

#### **STEP 13: RECEPTIONIST - Discharge Processing**
- **Actor:** Receptionist (Jane)
- **Action:**
  - Receives discharge notification
  - Verifies payment status
  - Prepares discharge documents:
    - Discharge summary
    - Medication list
    - Follow-up appointment slip
  - Updates patient status: "Discharged"
  - Releases patient

**System Records:**
- ✅ Patient status: "discharged"
- ✅ Discharge date recorded
- ✅ Room 201, Bed 1: Available

---

## 📋 **SCENARIO 2: OUT-PATIENT CONSULTATION - ROUTINE CHECKUP**

### **Situation:**
**Patient:** John Dela Cruz, 45 years old, Male  
**Complaint:** Annual health checkup  
**Arrival:** Out-Patient Department, 9:00 AM  
**Type:** Routine consultation

---

### **COMPLETE FLOW:**

#### **STEP 1: RECEPTIONIST - Appointment Booking**
- **Actor:** Receptionist (Jane)
- **Action:**
  - Patient calls for appointment
  - Opens HMS → "New Appointment"
  - Checks if existing patient:
    - Yes: John Dela Cruz (ID #5678)
  - Creates appointment:
    - **Date:** Today
    - **Time:** 9:00 AM
    - **Doctor:** Dr. Michael Tan (General Medicine)
    - **Type:** Checkup
  - Confirms appointment

**System Records:**
- ✅ Appointment created
- ✅ `appointment.doctor_id` = Dr. Michael Tan
- ✅ `appointment.status` = "scheduled"

---

#### **STEP 2: DOCTOR - Consultation**
- **Actor:** Dr. Michael Tan
- **Action:**
  - Logs into HMS → Doctor Dashboard
  - Sees "Today's Appointments" → John Dela Cruz (9:00 AM)
  - Patient arrives → Starts consultation
  - **Vital Signs:**
    - BP: 130/85 mmHg (slightly elevated)
    - Heart Rate: 72 bpm
    - Temperature: 36.5°C
    - Weight: 80 kg
    - Height: 175 cm
    - BMI: 26.1 (Overweight)
  - **Assessment:**
    - General health: Good
    - Blood pressure: Slightly elevated
    - Weight: Overweight
  - **Recommendations:**
    - Lifestyle modifications
    - Regular exercise
    - Diet counseling
  - Creates Medical Record
  - **Orders:**
    - Lab Tests: Complete Blood Count, Lipid Profile, Blood Sugar
    - No medications needed

**System Records:**
- ✅ Medical Record created
- ✅ Lab requests created
- ✅ Appointment marked as "completed"

---

#### **STEP 3: LAB STAFF - Lab Tests**
- **Actor:** Lab Technician (Mark)
- **Action:**
  - Processes lab tests
  - **Results:**
    - CBC: Normal
    - Lipid Profile: Cholesterol slightly elevated (220 mg/dL)
    - Blood Sugar: Normal (95 mg/dL)
  - Enters results
  - Notifies doctor

**System Records:**
- ✅ Lab results entered
- ✅ Doctor notified

---

#### **STEP 4: DOCTOR - Review & Follow-up**
- **Actor:** Dr. Michael Tan
- **Action:**
  - Reviews lab results
  - Calls patient for follow-up
  - Provides recommendations:
    - Monitor cholesterol
    - Continue lifestyle modifications
    - Follow-up in 3 months
  - Updates Medical Record

**System Records:**
- ✅ Medical Record updated
- ✅ Follow-up scheduled

---

#### **STEP 5: ACCOUNTANT - Billing**
- **Actor:** Accountant (Robert)
- **Action:**
  - Calculates charges:
    - Consultation fee: ₱1,500
    - Lab tests: ₱2,000
    - **Total: ₱3,500**
  - Processes payment
  - Generates receipt

**System Records:**
- ✅ Bill created
- ✅ Payment processed
- ✅ Receipt generated

---

## 📋 **SCENARIO 3: MULTIPLE DEPARTMENTS - COMPLEX CASE**

### **Situation:**
**Patient:** Carlos Reyes, 60 years old, Male  
**Complaint:** Chest pain, shortness of breath  
**Arrival:** Emergency Department  
**Condition:** Cardiac emergency - needs multiple specialists

---

### **COMPLETE FLOW:**

#### **STEP 1: RECEPTIONIST - Emergency Registration**
- Registers patient
- **Admission Type:** Admission
- **Attending Physician:** Dr. Patricia Lim (Cardiologist - Cardiology Department)
- **Room:** ICU - Bed 3

---

#### **STEP 2: CARDIOLOGIST - Initial Assessment**
- **Actor:** Dr. Patricia Lim
- **Action:**
  - Performs cardiac assessment
  - **Diagnosis:** Suspected Myocardial Infarction (Heart Attack)
  - **Orders:**
    - ECG (Electrocardiogram)
    - Cardiac Enzymes (Troponin, CK-MB)
    - Chest X-Ray
    - Echo (Echocardiogram)
  - Requests consultation from:
    - **Pulmonologist** (for breathing issues)
    - **Endocrinologist** (patient is diabetic)

---

#### **STEP 3: MULTIPLE DOCTORS - CONSULTATIONS**
- **Pulmonologist:** Reviews breathing, orders pulmonary function tests
- **Endocrinologist:** Reviews diabetes management, adjusts insulin
- **Cardiologist:** Coordinates care, monitors cardiac status

---

#### **STEP 4: LAB STAFF - Multiple Tests**
- Processes all lab tests from multiple departments
- Enters results
- Notifies all consulting doctors

---

#### **STEP 5: PHARMACIST - Complex Medication Management**
- Dispenses multiple medications:
  - Cardiac medications (from Cardiologist)
  - Respiratory medications (from Pulmonologist)
  - Diabetes medications (from Endocrinologist)
- Manages drug interactions
- Updates inventory

---

#### **STEP 6: NURSE - Coordinated Care**
- Administers medications from multiple doctors
- Monitors patient closely
- Updates all doctors on patient status
- Coordinates between departments

---

#### **STEP 7: ACCOUNTANT - Complex Billing**
- Calculates charges from:
  - Multiple doctor consultations
  - Multiple lab tests
  - Multiple medications
  - ICU room charges
  - Specialized procedures
- Creates consolidated bill

---

## 🎯 **KEY POINTS FOR FLOW DOCUMENTATION:**

### **1. Patient Journey:**
- Registration → Assessment → Treatment → Monitoring → Discharge

### **2. Role Interactions:**
- Receptionist → Doctor → Nurse → Lab Staff → Pharmacist → Accountant

### **3. Data Flow:**
- Patient Record → Medical Record → Lab Results → Doctor Orders → Medications → Billing

### **4. System Modules Used:**
- Patient Management
- Appointment Scheduling
- Medical Records
- Lab Management
- Pharmacy/Inventory
- Billing/Accounting
- Room Management

### **5. Decision Points:**
- Admission vs. Out-Patient
- Which doctor/specialist
- Which tests needed
- Which medications
- When to discharge

---

## 📊 **USE THESE SCENARIOS TO CREATE:**

1. **Flowcharts** - Visual representation of patient journey
2. **DFD (Data Flow Diagrams)** - How data moves between modules
3. **User Manuals** - Step-by-step guides for each role
4. **System Documentation** - Technical flow documentation

---

**Last Updated:** 2024-12-19

