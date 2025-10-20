# 🎫 Commit 12: Seat Selection & Booking System

**Date:** October 21, 2025  
**Status:** 🚧 In Progress  

---

## 🎯 Objective

Create a complete booking system that allows passengers to:
1. Select seats visually on an interactive seat map
2. Enter passenger details
3. Review booking summary
4. Confirm and create booking
5. View booking confirmation
6. View their booking history
7. Download tickets (PDF)
8. Cancel bookings

---

## 📋 Features to Implement

### 1. Seat Selection Interface
- **Visual Seat Map:**
  - Display seats based on layout (2x2, 2x3, 2x1)
  - Show available seats (green)
  - Show booked seats (gray, disabled)
  - Show selected seats (blue)
  - Click to select/deselect
  - Support multiple seat selection
  - Show selected seat numbers
  
- **Seat Pricing:**
  - Base fare per seat
  - Total calculation (seats × base_fare)
  - Update in real-time

### 2. Passenger Details Form
- **Required Information:**
  - Full name
  - Email
  - Phone number
  - Age
  - Gender
  - Emergency contact (optional)
  
- **Validation:**
  - All required fields
  - Valid email format
  - Valid phone format (BD)
  - Age between 1-120

### 3. Booking Summary
- **Display:**
  - Journey details (FROM → TO, Date, Time)
  - Bus information (Company, Number, Type)
  - Selected seats
  - Passenger details
  - Total fare
  - Terms & conditions checkbox
  
- **Actions:**
  - Edit button (go back)
  - Confirm booking button

### 4. Booking Confirmation
- **Success Page:**
  - Booking ID
  - Journey details
  - Seat numbers
  - Total paid
  - Download ticket button
  - View bookings button

### 5. Booking Management
- **My Bookings Page:**
  - List all user's bookings
  - Filter by status (upcoming, completed, cancelled)
  - Sort by date
  - Show booking details
  - Cancel booking option
  - Download ticket option

### 6. Ticket Generation
- **PDF Ticket:**
  - Booking ID & QR code
  - Passenger details
  - Journey information
  - Seat numbers
  - Fare details
  - Company contact
  - Terms & conditions

---

## 📁 Files to Create/Modify

### Database
- [ ] `database/migrations/XXXX_create_bookings_table.php` - Main bookings table
- [ ] `database/migrations/XXXX_create_booking_seats_table.php` - Many-to-many relationship

### Models
- [ ] `app/Models/Booking.php` - Booking model
- [ ] `app/Models/BookingSeat.php` - Pivot model for seats

### Controllers
- [ ] `app/Http/Controllers/Passenger/BookingController.php` - Handle all booking operations

### Views
- [ ] `resources/views/passenger/booking/seat-selection.blade.php` - Visual seat map
- [ ] `resources/views/passenger/booking/passenger-details.blade.php` - Form for details
- [ ] `resources/views/passenger/booking/confirmation.blade.php` - Success page
- [ ] `resources/views/passenger/bookings/index.blade.php` - Booking history
- [ ] `resources/views/passenger/bookings/show.blade.php` - Single booking details

### Routes
- [ ] Add booking routes in `routes/web.php`

### JavaScript (Optional for better UX)
- [ ] `resources/js/seat-selection.js` - Interactive seat map logic

---

## 🗄️ Database Schema

### Bookings Table
```sql
id (bigint, primary key)
user_id (foreign key → users)
schedule_id (foreign key → bus_schedules)
booking_reference (unique, string) - e.g., "BDT-2025-001234"
passenger_name (string)
passenger_email (string)
passenger_phone (string)
passenger_age (integer)
passenger_gender (enum: male, female, other)
emergency_contact (string, nullable)
total_seats (integer) - number of seats booked
total_fare (decimal) - total amount
status (enum: confirmed, cancelled) - default: confirmed
payment_status (enum: pending, paid, refunded) - default: paid (mock)
payment_method (string) - "Cash on Board" (mock payment)
booking_date (timestamp) - when booking was made
journey_date (date) - copy from schedule (for quick filtering)
timestamps
```

### Booking_Seats Table (Pivot)
```sql
id (bigint, primary key)
booking_id (foreign key → bookings)
seat_number (string) - e.g., "A1", "B2"
timestamps
```

**Relationship:**
- Booking hasMany BookingSeats
- BusSchedule hasMany Bookings
- User hasMany Bookings

---

## 🔄 Complete Booking Flow

```
1. User searches buses → Sees results
   ↓
2. Clicks "Book Now" on a schedule
   ↓
3. GET /booking/{schedule}/seats
   ↓
4. BookingController@showSeatSelection($schedule)
   - Check if user is authenticated → redirect to login if not
   - Load schedule with bus, route, company
   - Get already booked seats from bookings table
   - Calculate available seat numbers
   - Generate visual seat map based on layout
   - Return seat selection view
   ↓
5. User sees visual seat map
   - Available seats: Green
   - Booked seats: Gray (disabled)
   - User clicks seats → Selected: Blue
   - Selected seats: A1, B2, C3
   - Total fare updates: 3 × ৳1,200 = ৳3,600
   ↓
6. User clicks "Continue"
   ↓
7. POST /booking/{schedule}/seats
   - Validate: at least 1 seat selected
   - Validate: seats are available (not booked by others)
   - Store selected seats in session
   - Redirect to passenger details form
   ↓
8. GET /booking/{schedule}/details
   ↓
9. BookingController@showPassengerDetails($schedule)
   - Load schedule and selected seats from session
   - Pre-fill form with user data (if authenticated)
   - Return passenger details view
   ↓
10. User fills form:
    - Name: Ahmed Hassan
    - Email: ahmed@example.com
    - Phone: 01712345678
    - Age: 28
    - Gender: Male
    - Emergency Contact: 01798765432
    ↓
11. User clicks "Review Booking"
    ↓
12. POST /booking/{schedule}/details
    - Validate all passenger details
    - Store in session
    - Redirect to confirmation page
    ↓
13. GET /booking/{schedule}/confirm
    ↓
14. BookingController@showConfirmation($schedule)
    - Load schedule, seats, passenger details from session
    - Calculate total
    - Show summary with "Confirm Booking" button
    - Return confirmation view
    ↓
15. User reviews and clicks "Confirm Booking"
    ↓
16. POST /booking/{schedule}/confirm
    ↓
17. BookingController@confirmBooking($schedule)
    - START DATABASE TRANSACTION
    - Re-validate seats are still available
    - Generate unique booking reference
    - Create booking record
    - Create booking_seats records
    - Update schedule available_seats (decrease)
    - COMMIT TRANSACTION
    - Clear session data
    - Redirect to success page with booking ID
    ↓
18. GET /booking/success/{booking}
    ↓
19. BookingController@success($booking)
    - Show booking confirmation
    - Display booking ID, seats, total
    - "Download Ticket" button
    - "View My Bookings" button
```

---

## 🎨 Seat Map Visualization

### Layout: 2x2 (40 seats example)
```
    Driver
    ┌─────┐
    │  🚗 │
    └─────┘
    
Row 1:  [A1] [A2]    [B1] [B2]
Row 2:  [A3] [A4]    [B3] [B4]
Row 3:  [A5] [A6]    [B5] [B6]
...
Row 10: [A19][A20]   [B19][B20]

Colors:
🟢 Green = Available
🔵 Blue = Selected (your choice)
⚫ Gray = Already booked
```

### Seat Numbering Logic:
- **2x2 Layout:** A1, A2, B1, B2, A3, A4, B3, B4...
- **2x3 Layout:** A1, A2, B1, B2, B3, A3, A4, B4, B5, B6...
- **2x1 Layout:** A1, B1, A2, B2, A3, B3...

---

## 🔐 Security & Validation

### Seat Selection:
- Check user is authenticated
- Validate schedule exists and is scheduled
- Validate journey date is today or future
- Validate seats are available
- Prevent double booking (race condition)

### Booking Confirmation:
- Use database transactions
- Lock rows during booking
- Re-check seat availability before confirming
- Handle concurrent booking attempts

### Authorization:
- Only booking owner can view their bookings
- Only booking owner can cancel their booking
- Can't cancel past journey bookings

---

## 💳 Payment (Mock Implementation)

For now, we'll implement a **mock payment system**:
- Payment method: "Cash on Board"
- Payment status: automatically set to "paid"
- No actual payment gateway integration
- Can add real payment gateway later (bKash, Nagad, SSL Commerz)

---

## 📊 Key Queries

### Get Booked Seats for Schedule:
```php
$bookedSeats = BookingSeat::whereHas('booking', function($query) use ($scheduleId) {
    $query->where('schedule_id', $scheduleId)
          ->where('status', 'confirmed');
})->pluck('seat_number')->toArray();
```

### Generate Available Seats:
```php
$totalSeats = $bus->total_seats;
$layout = $bus->seat_layout; // "2x2", "2x3", "2x1"

// Generate all possible seat numbers
$allSeats = [];
// Logic based on layout...

// Filter out booked seats
$availableSeats = array_diff($allSeats, $bookedSeats);
```

### User's Bookings:
```php
$bookings = Booking::where('user_id', auth()->id())
    ->with(['schedule.bus.company', 'schedule.bus.route', 'seats'])
    ->latest('booking_date')
    ->paginate(10);
```

---

## 🧪 Testing Scenarios

### Test Case 1: Successful Booking
1. Login as passenger
2. Search buses
3. Click "Book Now"
4. Select 2 seats (A1, A2)
5. Fill passenger details
6. Review and confirm
7. Verify booking created
8. Check available_seats decreased

### Test Case 2: Concurrent Booking (Race Condition)
1. Two users try to book same seats
2. First one should succeed
3. Second one should get error "Seats no longer available"

### Test Case 3: Booking Validation
1. Try booking without selecting seats → Error
2. Try booking already booked seats → Error
3. Try booking past journey → Error

### Test Case 4: View Bookings
1. User views their bookings
2. See all bookings with status
3. Can download ticket
4. Can cancel upcoming booking

### Test Case 5: Cancel Booking
1. User cancels upcoming booking
2. Booking status changes to "cancelled"
3. Seats become available again
4. available_seats increases

---

## 📱 Responsive Design

### Mobile:
- Smaller seat buttons
- Touch-friendly
- Scrollable seat map
- Stacked form fields

### Tablet:
- Medium seat buttons
- 2-column forms
- Readable ticket

### Desktop:
- Large seat buttons
- 3-column forms
- Full-width seat map

---

## 🎨 Design System

### Colors:
- **Available Seat:** bg-green-100 border-green-300 hover:bg-green-200
- **Selected Seat:** bg-blue-500 border-blue-600 text-white
- **Booked Seat:** bg-gray-200 border-gray-300 cursor-not-allowed
- **Seat Number:** text-sm font-medium

### Buttons:
- **Select Seats:** Continue (green-600)
- **Submit Details:** Review Booking (green-600)
- **Confirm Booking:** Confirm & Pay (green-600)
- **Cancel Booking:** Cancel (red-600)

---

## 📝 Implementation Steps

### Step 1: Database Setup
1. Create migrations (bookings, booking_seats)
2. Create models with relationships
3. Run migrations

### Step 2: Booking Controller
1. Seat selection method
2. Passenger details method
3. Confirmation method
4. Store booking method
5. View bookings method
6. Cancel booking method

### Step 3: Views
1. Seat selection page with visual map
2. Passenger details form
3. Confirmation page
4. Success page
5. Bookings list
6. Single booking details

### Step 4: Routes
1. Booking flow routes
2. View bookings routes
3. Cancel booking route
4. Download ticket route

### Step 5: JavaScript (Optional)
1. Interactive seat selection
2. Real-time total calculation
3. Form validation

### Step 6: Testing
1. Test full booking flow
2. Test concurrent bookings
3. Test cancellation
4. Test all validations

---

## 🚀 Next Steps (After Commit 12)

**Commit 13:** Advanced Features
- PDF ticket generation with QR code
- Email notifications
- SMS notifications
- Payment gateway integration (bKash, Nagad)
- Refund processing
- Booking reports for owners
- Revenue analytics

**Commit 14:** Admin Panel
- Approve companies
- Manage users
- System settings
- Reports and analytics

---

## ✅ Ready to Implement!

This is the biggest commit so far. Let's build the complete booking system!

**Estimated time:** 4-5 hours  
**Complexity:** High  
**Dependencies:** Commits 6-11 (completed)

---

**Status:** Ready to start implementation 🚀
