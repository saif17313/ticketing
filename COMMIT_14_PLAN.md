# 📋 COMMIT 14 PLAN: Admin Dashboard & Reports

**Date:** October 21, 2025  
**Status:** 🚧 PLANNING  
**Dependencies:** Commit 13 (Payment System) ✅ Expected Complete

---

## 🎯 Objectives

Create a comprehensive admin dashboard with system-wide statistics, booking reports, revenue tracking, and user management capabilities.

---

## 📦 What Will Be Built

### **1. Admin Role & Access Control**

#### **A. Add Admin Role**
- Update user seeder to create admin account
- Admin email: `admin@bdbus.com`
- Admin password: `admin123`

#### **B. Middleware Update**
- Ensure `CheckRole` middleware supports 'admin' role
- Admin routes protected with `auth` + `role:admin`

---

### **2. Files to Create**

#### **Controllers (3 files):**

**A. `app/Http/Controllers/Admin/DashboardController.php`**
- **Purpose:** Admin dashboard overview
- **Methods:**
  - `index()` - Dashboard with key metrics
  - Statistics to show:
    - Total bookings (today, this week, this month, all time)
    - Total revenue (today, this week, this month, all time)
    - Total users (passengers, owners)
    - Total companies
    - Total buses
    - Total routes
    - Active bookings (confirmed, not yet departed)
    - Expired bookings (today)
    - Recent bookings (last 10)
    - Top performing routes
    - Top performing companies

**B. `app/Http/Controllers/Admin/BookingReportController.php`**
- **Purpose:** Booking reports and analytics
- **Methods:**
  - `index()` - All bookings list with filters
  - `daily()` - Daily booking report
  - `monthly()` - Monthly booking report
  - `export()` - Export bookings to CSV/Excel (future)
- **Filters:**
  - Date range
  - Status (pending/confirmed/cancelled/expired)
  - Booking type (book/direct_pay)
  - Company
  - Route

**C. `app/Http/Controllers/Admin/UserController.php`**
- **Purpose:** User management
- **Methods:**
  - `index()` - List all users
  - `show(User $user)` - View user details
  - `toggleStatus(User $user)` - Activate/deactivate user
  - `bookingHistory(User $user)` - User's booking history
- **Features:**
  - Filter by role (passenger/owner/admin)
  - Search by name/email
  - View user statistics

---

#### **Views (7 files):**

**A. `resources/views/admin/dashboard.blade.php`**
- **Purpose:** Main admin dashboard
- **Features:**
  - Statistics cards (8-10 cards with icons)
  - Revenue chart (simple bar/line chart with Chart.js)
  - Recent bookings table
  - Quick actions (View Reports, Manage Users)
  - Top routes/companies
- **Design:** Professional dashboard with cards and charts

**B. `resources/views/admin/bookings/index.blade.php`**
- **Purpose:** All bookings list with filters
- **Features:**
  - Filter form (date range, status, company, route)
  - Bookings table with columns:
    - Booking Reference
    - Passenger Name
    - Company
    - Route
    - Journey Date
    - Seats
    - Amount
    - Status
    - Actions (View, Cancel)
  - Pagination
  - Export button (future)
- **Design:** Table with filters sidebar

**C. `resources/views/admin/bookings/show.blade.php`**
- **Purpose:** Single booking details (admin view)
- **Features:**
  - Complete booking information
  - Passenger details
  - Payment details
  - Admin actions:
    - Mark as paid (if cash payment)
    - Cancel booking
    - Resend confirmation email (future)
- **Design:** Detailed view with admin actions

**D. `resources/views/admin/reports/daily.blade.php`**
- **Purpose:** Daily booking report
- **Features:**
  - Date selector
  - Daily statistics:
    - Total bookings
    - Total revenue
    - Bookings by status
    - Bookings by payment method
  - Bookings list for selected date
  - Chart visualization
- **Design:** Report page with date filter

**E. `resources/views/admin/reports/monthly.blade.php`**
- **Purpose:** Monthly booking report
- **Features:**
  - Month/Year selector
  - Monthly statistics:
    - Total bookings
    - Total revenue
    - Daily breakdown chart
    - Top routes
    - Top companies
  - Revenue trend chart
- **Design:** Comprehensive monthly report

**F. `resources/views/admin/users/index.blade.php`**
- **Purpose:** All users list
- **Features:**
  - Filter by role (passenger/owner/admin)
  - Search by name/email
  - Users table:
    - Name, Email, Role, Status, Registered Date
    - Total Bookings (passengers)
    - Total Companies (owners)
    - Actions (View, Activate/Deactivate)
  - Pagination
- **Design:** Table with filters

**G. `resources/views/admin/users/show.blade.php`**
- **Purpose:** Single user details
- **Features:**
  - User information
  - User statistics:
    - Total bookings (passengers)
    - Total spent (passengers)
    - Total companies (owners)
    - Total buses (owners)
  - Booking history (passengers)
  - Company list (owners)
  - Admin actions:
    - Activate/Deactivate
    - View full history
- **Design:** User profile with stats

---

#### **Routes (11 routes):**
```php
// Admin Routes (auth + role:admin)
GET  /admin/dashboard                      - Admin dashboard
GET  /admin/bookings                       - All bookings list
GET  /admin/bookings/{booking}             - Single booking
POST /admin/bookings/{booking}/cancel      - Cancel booking (admin)
POST /admin/bookings/{booking}/mark-paid   - Mark as paid (cash)
GET  /admin/reports/daily                  - Daily report
GET  /admin/reports/monthly                - Monthly report
GET  /admin/users                          - All users
GET  /admin/users/{user}                   - User details
POST /admin/users/{user}/toggle-status     - Activate/Deactivate
GET  /admin/users/{user}/bookings          - User booking history
```

---

### **3. Key Features**

#### **A. Dashboard Statistics**

**Metrics Cards:**
1. **Total Bookings Today**
   - Count of bookings created today
   - Comparison with yesterday (↑ 12%)

2. **Total Revenue Today**
   - Sum of confirmed booking amounts today
   - Comparison with yesterday

3. **Total Bookings This Week**
   - Count of bookings this week
   - Weekly trend

4. **Total Revenue This Week**
   - Sum of revenue this week
   - Weekly trend

5. **Total Users**
   - Count of all users
   - Breakdown: Passengers vs Owners

6. **Active Bookings**
   - Confirmed bookings with future departure dates
   - Upcoming journeys count

7. **Pending Payments**
   - Bookings with status='pending'
   - Total amount pending

8. **Expired Bookings (Today)**
   - Bookings expired today
   - Seats released count

#### **B. Charts & Visualizations**

**Revenue Chart (Last 7 Days):**
- Bar chart showing daily revenue
- X-axis: Dates
- Y-axis: Revenue (৳)
- Library: Chart.js

**Bookings by Status (Pie Chart):**
- Confirmed: Green
- Pending: Yellow
- Cancelled: Red
- Expired: Gray

**Top Routes (Horizontal Bar):**
- Routes with most bookings
- Top 5 routes

#### **C. User Management**

**Features:**
- View all users (passengers, owners, admins)
- Search by name/email/phone
- Filter by role
- View user details:
  - Registration date
  - Total bookings (passengers)
  - Total companies/buses (owners)
  - Last login (future)
- Activate/Deactivate users
- View user's booking history

#### **D. Booking Management**

**Admin Powers:**
- View all bookings across all users
- Filter by date, status, company, route
- View booking details
- Cancel any booking
- Mark cash payments as paid
- Resend confirmation emails (future)

#### **E. Reports**

**Daily Report:**
- Select any date
- View all bookings for that date
- Revenue breakdown
- Export to PDF/CSV (future)

**Monthly Report:**
- Select month/year
- Daily revenue trend
- Total bookings/revenue
- Top routes/companies
- Export to PDF/CSV (future)

---

### **4. Database Updates**

#### **User Seeder Update:**
```php
// Add admin user
User::create([
    'name' => 'System Admin',
    'email' => 'admin@bdbus.com',
    'password' => Hash::make('admin123'),
    'role' => 'admin',
    'phone' => '01700000000',
]);
```

#### **Migration (Optional - Add if needed):**
```php
// Add 'status' to users table if not exists
Schema::table('users', function (Blueprint $table) {
    $table->enum('status', ['active', 'inactive'])->default('active')->after('role');
});
```

---

### **5. Chart.js Integration**

**Install Chart.js:**
```bash
npm install chart.js
```

**Import in `resources/js/app.js`:**
```javascript
import Chart from 'chart.js/auto';
window.Chart = Chart;
```

**Usage in Blade:**
```html
<canvas id="revenueChart"></canvas>

<script>
const ctx = document.getElementById('revenueChart');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: @json($dates),
        datasets: [{
            label: 'Revenue (৳)',
            data: @json($revenues),
            backgroundColor: 'rgba(34, 197, 94, 0.8)',
            borderColor: 'rgba(34, 197, 94, 1)',
            borderWidth: 1
        }]
    }
});
</script>
```

---

## 🔧 Implementation Steps

### **Step 1: Add Admin Role**
- Update user seeder
- Create admin account
- Run seeder

### **Step 2: Create Admin Controllers**
- DashboardController
- BookingReportController
- UserController

### **Step 3: Create Admin Views**
- Dashboard with statistics
- Booking reports
- User management

### **Step 4: Add Admin Routes**
- Dashboard route
- Booking management routes
- User management routes
- Report routes

### **Step 5: Install Chart.js**
- Install via npm
- Import in app.js
- Create charts

### **Step 6: Implement Statistics**
- Query bookings/revenue data
- Calculate metrics
- Pass to views

### **Step 7: Testing**
- Login as admin
- View dashboard
- Test filters
- Test reports
- Test user management

---

## 📊 Expected Outcomes

### **Admin Dashboard:**
- ✅ Comprehensive overview of system
- ✅ Real-time statistics
- ✅ Visual charts
- ✅ Quick access to reports

### **User Management:**
- ✅ View all users
- ✅ Filter and search
- ✅ View user details
- ✅ Activate/deactivate users

### **Reports:**
- ✅ Daily booking reports
- ✅ Monthly reports
- ✅ Revenue tracking
- ✅ Export capabilities (future)

---

## 🎨 Design Notes

- **Dashboard Cards:** Large metric cards with icons and trends
- **Charts:** Professional charts with Chart.js (green color scheme)
- **Tables:** Clean, filterable tables with pagination
- **Admin Header:** Different header design for admin section
- **Sidebar Navigation:** Left sidebar with admin menu (future)

---

## 🚀 Future Enhancements (Not in this commit)

- Real-time dashboard (WebSockets/Pusher)
- Advanced analytics (revenue forecasting)
- User activity logs
- Email user from admin panel
- Bulk operations (cancel multiple bookings)
- Advanced filters (multiple companies, routes)
- Export to PDF/Excel
- Scheduled reports via email

---

**Status:** 🚧 Ready for Implementation  
**Estimated Time:** 2-3 hours  
**Dependencies:** Commit 13 must be complete first  
**Next Phase:** Testing & Deployment
