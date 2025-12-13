# HMS Consolidated Billing System - Complete Documentation

## Overview

The Hospital Management System (HMS) now uses a **patient-based consolidated billing** model where **ONE consolidated bill is generated per patient** instead of multiple separate bills. All charges from different departments (consultations, lab tests, medications, room charges, procedures, etc.) are automatically accumulated into a single bill per patient.

---

## Key Features

### ✅ One Bill Per Patient
- Each patient has **one active billing record** at any time
- All charges from all departments are attached to the same bill
- Charges are automatically added when services are completed

### ✅ Comprehensive Charge Tracking
The consolidated bill includes charges from:
- **Doctor Consultations** - Automatically added when consultations are completed
- **Laboratory Tests** - Added when lab results are submitted
- **Medications** - Added when prescriptions are dispensed
- **Room/Bed Charges** - Calculated based on admission dates
- **Procedures** - Added when procedures are performed
- **Other Services** - Can be manually added by accountants

### ✅ Billing Status Flow
- **Pending** - Bill created but no payments received
- **Partially Paid** - Some payment received but balance remains
- **Fully Paid** - Complete payment received
- **Cancelled** - Bill cancelled (rare)

### ✅ Payment Handling
- **Partial Payments** - Multiple payments can be made against one bill
- **Automatic Balance Calculation** - Balance updates automatically after each payment
- **Payment Receipts** - Official receipt generated per payment
- **Payment History** - Complete audit trail of all payments

---

## System Architecture

### Database Structure

#### 1. `billing` Table (Consolidated Bill)
- `id` - Primary key
- `invoice_number` - Unique bill number (e.g., BILL-20250101120000-123)
- `patient_id` - Foreign key to patients table
- `bill_date` - Date bill was created
- `due_date` - Payment due date (default: 30 days)
- `subtotal` - Sum of all item prices
- `tax_amount` - Tax amount (if applicable)
- `discount_amount` - Discount/insurance coverage
- `total_amount` - Final bill amount
- `paid_amount` - Total amount paid so far
- `balance` - Remaining balance (total_amount - paid_amount)
- `payment_status` - pending | partial | paid | overdue | cancelled
- `insurance_claim_number` - Insurance claim reference
- `notes` - Additional notes

#### 2. `billing_items` Table (Bill Line Items)
- `id` - Primary key
- `billing_id` - Foreign key to billing table
- `item_type` - consultation | medication | lab_test | room_charge | procedure | other
- `item_name` - Name of the service/item
- `description` - Additional details
- `quantity` - Quantity
- `unit_price` - Price per unit
- `total_price` - Quantity × Unit Price

#### 3. `payments` Table
- `id` - Primary key
- `billing_id` - Foreign key to billing table
- `patient_id` - Foreign key to patients table
- `amount` - Payment amount
- `paid_at` - Payment date/time
- `payment_method` - cash | card | cheque | bank_transfer | insurance | other
- `transaction_id` - Transaction reference
- `notes` - Payment notes
- `processed_by` - User who processed the payment

---

## Workflow

### 1. Automatic Bill Creation

When a patient receives any service:
1. System checks if patient has an active (unpaid) bill
2. If no active bill exists, creates a new consolidated bill
3. Adds the service charge as a billing item
4. Recalculates bill totals automatically

**Example Flow:**
```
Patient visits doctor → Consultation charge added → Bill created/updated
Patient gets lab test → Lab test charge added → Same bill updated
Patient gets medication → Medication charge added → Same bill updated
```

### 2. Charge Addition (Automatic)

Charges are automatically added when:
- **Doctor completes consultation** → Consultation fee added
- **Lab staff submits results** → Lab test cost added
- **Pharmacy dispenses medication** → Medication cost added
- **Patient is admitted** → Room charges calculated daily

**Code Location:**
- Doctor consultations: `app/Controllers/Doctor.php` (around line 1058)
- Lab tests: `app/Controllers/Labstaff.php` (around line 140)
- Medications: `app/Controllers/Pharmacy.php` (around line 457)

### 3. Manual Charge Management

Accountants can manually:
- **Add Charges** - `/accountant/add-charge/{patient_id}`
- **Remove Charges** - `/accountant/remove-charge/{item_id}`
- **Apply Insurance** - `/accountant/apply-insurance/{patient_id}`
- **Accept Payments** - `/accountant/accept-payment/{patient_id}`
- **Print/Export** - `/accountant/print-bill/{patient_id}`

---

## Accountant Dashboard

### Main Interface: Patient Billing List

**URL:** `/accountant/consolidated-bills`

**Features:**
- Lists all patients with their consolidated bills
- Shows key information:
  - Patient ID
  - Patient Name
  - Total Amount
  - Paid Amount
  - Balance
  - Billing Status
- Status filters: All | Pending | Partially Paid | Fully Paid
- Action buttons for each patient:
  - **View Consolidated Bill** - See full bill details
  - **Accept Payment** - Record payment
  - **Print/Export** - Generate invoice PDF

### Consolidated Bill View

**URL:** `/accountant/consolidated-bill/{patient_id}`

**Shows:**
1. **Patient Information** - Name, ID, contact, type (inpatient/outpatient)
2. **Invoice Summary** - Bill number, dates, status, amounts
3. **Services & Products** - Complete list of all charges with:
   - Item type
   - Description
   - Date
   - Amount
   - Remove button (for manual charges)
4. **Payment History** - All payments made with:
   - Payment date
   - Amount
   - Payment method
   - Notes

**Action Buttons:**
- **Add Charge** - Manually add a charge
- **Apply Insurance** - Apply insurance coverage
- **Accept Payment** - Record a payment
- **Print/Export** - Generate printable invoice

---

## Key Methods

### BillingModel Methods

#### `getOrCreateActiveBill($patientId, $branchId, $createdBy)`
- Gets existing active (unpaid) bill for patient
- Creates new bill if none exists
- Returns bill array

#### `getConsolidatedBill($patientId, $branchId, $createdBy)`
- Gets or creates consolidated bill
- Recalculates totals
- Returns bill with all relations (items, payments, patient)

#### `recalculateBill($billingId)`
- Recalculates subtotal from all items
- Updates total_amount, paid_amount, balance
- Updates payment_status based on balance

#### `calculateBalance($billingId)`
- Calculates total paid from payments
- Updates paid_amount and balance
- Updates payment_status

---

## Payment Processing

### Partial Payments

The system fully supports partial payments:

1. **Record Payment:**
   - Accountant enters payment amount
   - System validates amount doesn't exceed balance
   - Payment is recorded in `payments` table

2. **Automatic Updates:**
   - `paid_amount` is recalculated
   - `balance` is updated
   - `payment_status` changes:
     - If balance = 0 → status = "paid"
     - If balance > 0 and paid_amount > 0 → status = "partial"
     - If balance > 0 and paid_amount = 0 → status = "pending"

3. **Multiple Payments:**
   - Patient can make multiple payments
   - Each payment is tracked separately
   - All payments sum to total paid amount

### Payment Receipt

Each payment generates a receipt with:
- Payment date/time
- Payment amount
- Payment method
- Transaction ID (if provided)
- Bill reference
- Patient information

---

## Insurance Handling

### Apply Insurance Coverage

1. **Navigate to:** `/accountant/apply-insurance/{patient_id}`
2. **Enter:**
   - Insurance Claim Number
   - Insurance Coverage Amount (applied as discount)
   - Notes
3. **System:**
   - Updates `insurance_claim_number` in billing
   - Adds coverage amount to `discount_amount`
   - Recalculates bill totals

---

## Print/Export Invoice

### Print Bill

**URL:** `/accountant/print-bill/{patient_id}`

**Features:**
- Professional invoice layout
- All bill details
- Payment history
- Print-optimized styling
- Can be saved as PDF using browser print function

---

## Status Management

### Payment Status Logic

```php
if ($balance <= 0) {
    $status = 'paid';
} elseif ($paid_amount > 0) {
    $status = 'partial';
} else {
    $status = 'pending';
}
```

### Status Badges
- **Pending** - Gray badge
- **Partial** - Orange badge
- **Paid** - Green badge
- **Overdue** - Red badge (if due date passed)

---

## Routes

### Consolidated Billing Routes

```
GET  /accountant/consolidated-bills          - List all patient bills
GET  /accountant/consolidated-bill/{id}       - View patient's consolidated bill
GET  /accountant/add-charge/{id}              - Show add charge form
POST /accountant/add-charge/{id}              - Process add charge
GET  /accountant/remove-charge/{id}            - Remove charge item
GET  /accountant/apply-insurance/{id}         - Show insurance form
POST /accountant/apply-insurance/{id}         - Process insurance
GET  /accountant/accept-payment/{id}          - Show payment form
POST /accountant/accept-payment/{id}          - Process payment
GET  /accountant/print-bill/{id}              - Print/export invoice
```

---

## Best Practices

### For Accountants

1. **Review Bills Regularly**
   - Check consolidated bills daily
   - Monitor pending and partial payments
   - Follow up on overdue bills

2. **Charge Management**
   - Only remove charges if there's an error
   - Always add descriptions for manual charges
   - Verify automatic charges are correct

3. **Payment Recording**
   - Record payments immediately after receipt
   - Use accurate payment dates
   - Include transaction IDs for traceability

4. **Insurance Processing**
   - Apply insurance before finalizing bills
   - Verify claim numbers are correct
   - Document insurance coverage amounts

### For System Administrators

1. **Monitor Bill Creation**
   - Ensure automatic charge addition is working
   - Check for duplicate charges
   - Verify bill calculations

2. **Payment Reconciliation**
   - Regularly reconcile payments with bank records
   - Review payment history for discrepancies
   - Monitor partial payment patterns

---

## Troubleshooting

### Issue: Duplicate Charges

**Solution:** System prevents duplicates by checking:
- Item type
- Item name
- Description (for date-based services)
- Billing ID

### Issue: Balance Not Updating

**Solution:**
1. Check if payment was recorded correctly
2. Verify `billing_id` in payment matches bill
3. Run `recalculateBill()` manually if needed

### Issue: Bill Not Created

**Solution:**
1. Check patient is active (`is_active = 1`)
2. Verify user has permission to create bills
3. Check database connection

---

## Summary

The consolidated billing system provides:
- ✅ **Simplified billing** - One bill per patient
- ✅ **Automatic charge tracking** - All services automatically billed
- ✅ **Flexible payment handling** - Partial payments supported
- ✅ **Complete audit trail** - All charges and payments tracked
- ✅ **Professional invoicing** - Print-ready invoices
- ✅ **Insurance integration** - Easy insurance application
- ✅ **Accountant-friendly** - Intuitive interface

This system is production-ready and suitable for real hospital operations.

---

**Last Updated:** January 2025
**Version:** 1.0
**Status:** ✅ Complete and Operational

