<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bus Ticket - {{ $booking->booking_reference }}</title>
    <style>
        @page {
            margin: 15mm;
            size: A4 portrait;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 11px;
            line-height: 1.4;
            color: #333;
        }
        
        .ticket-container {
            width: 100%;
            border: 2px solid #10b981;
        }
        
        .ticket-header {
            background: #10b981;
            color: white;
            padding: 15px;
            text-align: center;
        }
        
        .ticket-header h1 {
            font-size: 22px;
            margin-bottom: 5px;
        }
        
        .ticket-header p {
            font-size: 12px;
        }
        
        .status-badge {
            display: inline-block;
            background: white;
            color: #10b981;
            padding: 5px 15px;
            border-radius: 15px;
            font-weight: bold;
            margin-top: 5px;
            font-size: 11px;
        }
        
        .ticket-body {
            padding: 15px;
        }
        
        .booking-ref {
            text-align: center;
            background: #f0fdf4;
            padding: 10px;
            margin-bottom: 15px;
            border: 2px dashed #10b981;
        }
        
        .booking-ref h2 {
            font-size: 18px;
            color: #10b981;
            margin-bottom: 3px;
        }
        
        .booking-ref p {
            color: #666;
            font-size: 10px;
        }
        
        .section {
            margin-bottom: 15px;
        }
        
        .section-title {
            font-size: 13px;
            font-weight: bold;
            color: #10b981;
            margin-bottom: 8px;
            padding-bottom: 5px;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .info-grid {
            width: 100%;
            margin-bottom: 8px;
        }
        
        .info-row {
            width: 100%;
            margin-bottom: 5px;
            overflow: hidden;
        }
        
        .info-item {
            display: inline-block;
            width: 48%;
            padding: 6px;
            background: #f9fafb;
            vertical-align: top;
            margin-right: 1%;
        }
        
        .info-label {
            font-size: 9px;
            color: #6b7280;
            margin-bottom: 2px;
            text-transform: uppercase;
        }
        
        .info-value {
            font-size: 11px;
            font-weight: bold;
            color: #111827;
        }
        
        .journey-info {
            background: #f0fdf4;
            padding: 10px;
            margin-bottom: 10px;
            border-left: 3px solid #10b981;
        }
        
        .route {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            color: #111827;
            margin-bottom: 8px;
        }
        
        .route-arrow {
            margin: 0 8px;
            color: #10b981;
        }
        
        .seats-container {
            margin-top: 5px;
        }
        
        .seat-badge {
            display: inline-block;
            background: #10b981;
            color: white;
            padding: 4px 10px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 11px;
            margin-right: 5px;
            margin-bottom: 3px;
        }
        
        .payment-summary {
            background: #f9fafb;
            padding: 10px;
            margin-top: 10px;
        }
        
        .payment-row {
            width: 100%;
            padding: 5px 0;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .payment-row:last-child {
            border-bottom: none;
            border-top: 2px solid #10b981;
            margin-top: 5px;
            padding-top: 8px;
        }
        
        .payment-label {
            display: inline-block;
            width: 60%;
            font-size: 11px;
        }
        
        .payment-value {
            display: inline-block;
            width: 38%;
            text-align: right;
            font-size: 11px;
        }
        
        .payment-total .payment-label,
        .payment-total .payment-value {
            font-size: 14px;
            font-weight: bold;
            color: #10b981;
        }
        
        .payment-method {
            background: #eff6ff;
            padding: 6px;
            margin-top: 8px;
            text-align: center;
            color: #1e40af;
            font-weight: 600;
            font-size: 10px;
        }
        
        .important-notes {
            background: #fef3c7;
            border: 1px solid #f59e0b;
            padding: 10px;
            margin-top: 15px;
        }
        
        .important-notes h3 {
            color: #92400e;
            margin-bottom: 8px;
            font-size: 12px;
        }
        
        .important-notes ul {
            list-style: none;
            color: #78350f;
        }
        
        .important-notes li {
            margin-bottom: 4px;
            padding-left: 12px;
            position: relative;
            font-size: 10px;
        }
        
        .important-notes li::before {
            content: '•';
            position: absolute;
            left: 0;
            font-weight: bold;
        }
        
        .ticket-footer {
            background: #f9fafb;
            padding: 10px;
            text-align: center;
            border-top: 1px solid #e5e7eb;
        }
        
        .ticket-footer p {
            color: #6b7280;
            font-size: 10px;
            margin-bottom: 3px;
        }
        
        .company-info {
            margin-top: 8px;
            padding-top: 8px;
            border-top: 1px solid #e5e7eb;
        }
    </style>
</head>
<body>
    <div class="ticket-container">
        <!-- Header -->
        <div class="ticket-header">
            <h1>🚌 BD Bus Tickets</h1>
            <p>Your Digital Bus Ticket</p>
            <div class="status-badge">✅ CONFIRMED & PAID</div>
        </div>

        <!-- Body -->
        <div class="ticket-body">
            <!-- Booking Reference -->
            <div class="booking-ref">
                <h2>{{ $booking->booking_reference }}</h2>
                <p>Booking Reference Number</p>
            </div>

            <!-- Journey Information -->
            <div class="section">
                <div class="section-title">Journey Details</div>
                <div class="journey-info">
                    <div class="route">
                        <span>{{ $booking->busSchedule->bus->route->sourceDistrict->name }}</span>
                        <span class="route-arrow">→</span>
                        <span>{{ $booking->busSchedule->bus->route->destinationDistrict->name }}</span>
                    </div>
                    <div class="info-grid">
                        <div class="info-row">
                            <div class="info-item">
                                <div class="info-label">Journey Date</div>
                                <div class="info-value">{{ \Carbon\Carbon::parse($booking->busSchedule->journey_date)->format('d M Y') }}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Departure Time</div>
                                <div class="info-value">{{ \Carbon\Carbon::parse($booking->busSchedule->departure_time)->format('h:i A') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bus & Company Information -->
            <div class="section">
                <div class="section-title">Bus Details</div>
                <div class="info-grid">
                    <div class="info-row">
                        <div class="info-item">
                            <div class="info-label">Bus Company</div>
                            <div class="info-value">{{ $booking->busSchedule->bus->company->name }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Bus Number</div>
                            <div class="info-value">{{ $booking->busSchedule->bus->bus_number }}</div>
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-item">
                            <div class="info-label">Bus Type</div>
                            <div class="info-value">{{ ucfirst($booking->busSchedule->bus->bus_type) }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Seats</div>
                            <div class="info-value">{{ $booking->seats->pluck('seat_number')->implode(', ') }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Passenger Information -->
            <div class="section">
                <div class="section-title">Passenger Details</div>
                <div class="info-grid">
                    <div class="info-row">
                        <div class="info-item">
                            <div class="info-label">Full Name</div>
                            <div class="info-value">{{ $booking->passenger_details['name'] ?? 'N/A' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Phone Number</div>
                            <div class="info-value">{{ $booking->passenger_details['phone'] ?? 'N/A' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Summary -->
            <div class="section">
                <div class="section-title">Payment Details</div>
                <div class="payment-summary">
                    <div class="payment-row">
                        <span class="payment-label">Fare per Seat:</span>
                        <span class="payment-value">৳{{ number_format($booking->busSchedule->base_fare, 2) }}</span>
                    </div>
                    <div class="payment-row">
                        <span class="payment-label">Number of Seats:</span>
                        <span class="payment-value">{{ $booking->total_seats }}</span>
                    </div>
                    <div class="payment-row payment-total">
                        <span class="payment-label">Total Amount:</span>
                        <span class="payment-value">৳{{ number_format($booking->total_amount, 2) }}</span>
                    </div>
                </div>

                @if($booking->payment)
                <div class="payment-method">
                    Paid via {{ ucfirst($booking->payment->payment_method) }} on {{ $booking->payment->paid_at->format('d M Y') }}
                </div>
                @endif
            </div>

            <!-- Important Notes -->
            <div class="important-notes">
                <h3>Important Information</h3>
                <ul>
                    <li>Arrive <strong>30 minutes before</strong> departure.</li>
                    <li>Carry valid <strong>government ID</strong> for verification.</li>
                    <li>Booking Ref: <strong>{{ $booking->booking_reference }}</strong></li>
                    <li>Contact: {{ $booking->busSchedule->bus->company->phone }}</li>
                </ul>
            </div>
        </div>

        <!-- Footer -->
        <div class="ticket-footer">
            <p><strong>{{ $booking->busSchedule->bus->company->name }}</strong> | {{ $booking->busSchedule->bus->company->phone }}</p>
            <p>Booked: {{ $booking->created_at->format('d M Y, h:i A') }} | Thank you for choosing BD Bus Tickets!</p>
        </div>
    </div>
</body>
</html>
