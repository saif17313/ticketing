# 📋 COMMIT 13 PLAN: Payment Integration & Booking Expiry

**Date:** October 21, 2025  
**Status:** 🚧 PLANNING  
**Dependencies:** Commit 12 (Booking System) ✅ Complete

---

## 🎯 Objectives

Implement payment processing system with multiple payment methods and automatic booking expiry management to complete the booking lifecycle.

---

## 📦 What Will Be Built

### **1. Payment Processing System**

#### **A. Payment Gateway Simulation**
Since real payment gateway integration requires API keys and testing environments, we'll create:
- **Simulated payment flow** for bKash, Nagad, Rocket, Card
- **Real cash payment** tracking at counter
- Payment confirmation mechanism
- Transaction logging

#### **B. Payment Methods:**
1. **bKash** - Mobile banking (simulated)
2. **Nagad** - Mobile banking (simulated)
3. **Rocket** - Mobile banking (simulated)
4. **Card** - Credit/Debit card (simulated)
5. **Cash** - Pay at counter (real tracking)

---

### **2. Files to Create**

#### **Controllers (2 files):**

**A. `app/Http/Controllers/Passenger/PaymentController.php`**
- **Purpose:** Handle payment processing
- **Methods:**
  - `showPaymentOptions($booking)` - Display payment methods
  - `processMobilePayment(Request $request, $booking)` - Handle bKash/Nagad/Rocket
  - `processCardPayment(Request $request, $booking)` - Handle card payment
  - `confirmPayment($booking, $transactionId)` - Mark payment as completed
  - `paymentCallback(Request $request)` - Handle payment gateway callbacks (future)
  - `downloadInvoice($booking)` - Generate and download invoice PDF

**B. `app/Console/Commands/ExpireBookings.php`**
- **Purpose:** Scheduled command to expire unpaid bookings
- **Functionality:**
  - Runs every hour
  - Finds bookings with status='pending' and expires_at < now()
  - Marks as 'expired'
  - Releases all seats
  - Sends notification (future: email/SMS)

---

#### **Views (3 files):**

**A. `resources/views/passenger/payment/options.blade.php`**
- **Purpose:** Payment method selection page
- **Features:**
  - Booking summary card
  - Payment method cards (bKash, Nagad, Rocket, Card, Cash)
  - Amount display
  - Countdown timer (if booking has expiry)
  - Payment method icons
- **Design:** Professional cards with hover effects

**B. `resources/views/passenger/payment/mobile.blade.php`**
- **Purpose:** Mobile payment form (bKash/Nagad/Rocket)
- **Features:**
  - Mobile number input
  - PIN/OTP simulation
  - Transaction processing animation
  - Success/failure messages
- **Design:** Simulated payment gateway interface

**C. `resources/views/passenger/payment/success.blade.php`**
- **Purpose:** Payment success confirmation
- **Features:**
  - Success animation/icon
  - Transaction details
  - Booking confirmation
  - Download invoice button
  - View booking button
- **Design:** Celebration page with confetti effect (CSS)

---

#### **Migrations (0 files - Use Existing)**
- ✅ `payments` table already exists from Commit 12
- Will use existing columns:
  - `booking_id`, `transaction_id`, `amount`
  - `payment_method`, `status`
  - `invoice_number`, `invoice_path`
  - `paid_at`, `payment_details`

---

#### **Routes (5 routes):**
```php
// Payment Routes (auth + role:passenger)
GET  /passenger/booking/{booking}/payment          - Payment options
POST /passenger/payment/mobile/{booking}           - Process mobile payment
POST /passenger/payment/card/{booking}             - Process card payment
GET  /passenger/payment/success/{booking}          - Payment success page
GET  /passenger/booking/{booking}/invoice          - Download invoice PDF
```

---

### **3. Key Features**

#### **A. Payment Flow:**
1. User completes booking → Status: 'pending'
2. Redirected to payment options page
3. Selects payment method
4. Completes payment (simulated)
5. Payment record created
6. Booking status updated to 'confirmed'
7. Success page with invoice download

#### **B. Booking Expiry System:**
- **Scheduled Command:** Runs every hour via Laravel scheduler
- **Logic:**
  ```php
  Find bookings where:
    - status = 'pending'
    - booking_type = 'book' (pay later)
    - expires_at < now()
  
  For each expired booking:
    - Update status to 'expired'
    - Release all seats (status → 'available')
    - Increment schedule available_seats
    - Log expiry event
  ```

#### **C. Invoice Generation:**
- **Format:** HTML/PDF (using Laravel DOMPDF or similar)
- **Contents:**
  - Company logo area
  - Invoice number
  - Booking reference
  - Passenger details
  - Journey details
  - Seat numbers
  - Fare breakdown
  - Payment details
  - QR code (future)
  - Terms & conditions

---

### **4. Payment Method Simulation**

Since we're simulating payment gateways:

#### **bKash/Nagad/Rocket Simulation:**
```
1. User enters mobile number
2. Shows "Enter PIN" screen (any 4 digits accepted)
3. Shows "Processing..." animation (2 seconds)
4. Success (90% chance) or Failure (10% chance) randomly
5. Creates payment record with transaction_id
6. Updates booking status
```

#### **Card Payment Simulation:**
```
1. User enters card details (any format accepted)
2. Shows "Processing..." animation (3 seconds)
3. Success message
4. Creates payment record
```

#### **Cash Payment:**
```
- No immediate payment processing
- Booking remains 'pending'
- Owner/Admin can mark as paid from dashboard (future)
- Passenger pays at counter before departure
```

---

### **5. Scheduler Setup**

**File: `app/Console/Kernel.php`**
```php
protected function schedule(Schedule $schedule)
{
    // Run booking expiry check every hour
    $schedule->command('bookings:expire')->hourly();
}
```

**To Run Scheduler:**
```bash
# In production (cron job)
* * * * * cd /path/to/project && php artisan schedule:run >> /dev/null 2>&1

# In development (manual)
php artisan schedule:work
```

---

### **6. Updated Booking Flow**

**Current (Commit 12):**
```
Search → Select Seats → Details → Confirm → Booking Created (Pending/Confirmed)
```

**After Commit 13:**
```
Search → Select Seats → Details → Confirm → Booking Created → 
  └─> If booking_type = 'direct_pay':
        └─> Redirect to Payment → Process Payment → Success
  └─> If booking_type = 'book':
        └─> Booking complete (Pay later at counter)
        └─> Expires in 24 hours if unpaid
```

---

## 🔧 Implementation Steps

### **Step 1: Create Payment Controller**
- Handle payment method selection
- Simulate payment processing
- Create payment records
- Update booking status

### **Step 2: Create Payment Views**
- Payment options page (method selection)
- Mobile payment form (bKash/Nagad/Rocket)
- Success confirmation page

### **Step 3: Add Payment Routes**
- Payment options route
- Payment processing routes
- Success/failure routes
- Invoice download route

### **Step 4: Create Expiry Command**
- Scheduled command to expire bookings
- Release seats logic
- Logging

### **Step 5: Update Booking Flow**
- Redirect to payment after booking creation (if direct_pay)
- Add "Make Payment" button on pending bookings

### **Step 6: Invoice Generation**
- Create invoice view (HTML)
- PDF generation (future: use DOMPDF)
- Download functionality

### **Step 7: Register Scheduler**
- Update `app/Console/Kernel.php`
- Test scheduler

### **Step 8: Testing**
- Test each payment method
- Test booking expiry
- Test invoice generation
- Test payment confirmation

---

## 📊 Expected Outcomes

### **Payment System:**
- ✅ Multiple payment methods available
- ✅ Simulated payment processing
- ✅ Payment records created
- ✅ Transaction tracking

### **Booking Expiry:**
- ✅ Automatic expiry after 24 hours
- ✅ Seats released automatically
- ✅ Booking status updated

### **Invoice System:**
- ✅ Invoice generation
- ✅ Download functionality
- ✅ Professional invoice design

---

## 🎨 Design Notes

- **Payment Method Cards:** Large, clickable cards with icons
- **Processing Animation:** Spinner with "Processing payment..." message
- **Success Page:** Confetti animation (CSS), green checkmark, celebration message
- **Invoice:** Professional business invoice format

---

## 🚀 Future Enhancements (Not in this commit)

- Real payment gateway integration (bKash API, Nagad API, etc.)
- Email notifications on payment success
- SMS notifications
- Refund processing
- Payment history page
- Payment receipts via email

---

**Status:** 🚧 Ready for Implementation  
**Estimated Time:** 2-3 hours  
**Next Commit:** Commit 14 (Admin Dashboard & Reports)
