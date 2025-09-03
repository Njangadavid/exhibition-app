<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exhibitor Bookings Report - {{ $event->title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background: white;
            color: #333;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
        }
        
        .header h1 {
            margin: 0 0 10px 0;
            color: #333;
            font-size: 24px;
        }
        
        .header p {
            margin: 5px 0;
            color: #666;
        }
        
        .summary {
            margin-bottom: 30px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
        }
        
        .summary h3 {
            color: #333;
            border-bottom: 2px solid #007bff;
            padding-bottom: 5px;
            margin-bottom: 15px;
            font-size: 18px;
        }
        
        .summary-grid {
            display: table;
            width: 100%;
            border-collapse: collapse;
        }
        
        .summary-row {
            display: table-row;
        }
        
        .summary-item {
            display: table-cell;
            background: white;
            padding: 15px;
            border: 1px solid #e9ecef;
            width: 33.33%;
            vertical-align: top;
        }
        
        .summary-label {
            font-size: 12px;
            color: #6c757d;
            margin-bottom: 5px;
            font-weight: bold;
        }
        
        .summary-value {
            font-size: 18px;
            font-weight: bold;
            color: #333;
        }
        
                 .table-container {
             margin-top: 20px;
         }
         
         .exhibitor-table {
             width: 100%;
             border-collapse: collapse;
             margin-bottom: 20px;
             background: white;
         }
         
         .exhibitor-table th {
             background: #f8f9fa;
             padding: 12px 8px;
             text-align: left;
             font-weight: bold;
             font-size: 11px;
             color: #333;
             border: 1px solid #e9ecef;
         }
         
         .exhibitor-table td {
             padding: 10px 8px;
             font-size: 10px;
             border: 1px solid #e9ecef;
             vertical-align: top;
         }
         
         .exhibitor-table tr:nth-child(even) {
             background: #f8f9fa;
         }
         
         .exhibitor-name {
             font-weight: bold;
             font-size: 11px;
         }
         
         .company-name {
             color: #6c757d;
             font-size: 10px;
         }
         
         .payment-badge {
             display: inline-block;
             padding: 2px 6px;
             border-radius: 3px;
             font-size: 9px;
             font-weight: bold;
         }
         
         .badge-success { background: #d4edda; color: #155724; }
         .badge-warning { background: #fff3cd; color: #856404; }
         .badge-secondary { background: #e2e3e5; color: #383d41; }
         
         .booth-badge {
             display: inline-block;
             padding: 1px 4px;
             border-radius: 2px;
             font-size: 8px;
             margin-right: 3px;
         }
         
         .badge-info { background: #d1ecf1; color: #0c5460; }
         .badge-warning { background: #fff3cd; color: #856404; }
         .badge-success { background: #d4edda; color: #155724; }
         .badge-danger { background: #f8d7da; color: #721c24; }
        
        .page-break {
            page-break-before: always;
        }
        
        .section-title {
            font-size: 16px;
            font-weight: bold;
            margin: 20px 0 15px 0;
            color: #333;
            border-bottom: 1px solid #e9ecef;
            padding-bottom: 5px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Exhibitor Bookings Report</h1>
        <p><strong>Event:</strong> {{ $event->title }}</p>
        <p><strong>Event Dates:</strong> {{ $event->start_date->format('F d, Y') }} - {{ $event->end_date->format('F d, Y') }}</p>
        <p><strong>Report Generated:</strong> {{ date('F d, Y') }}</p>
    </div>
    
    <div class="summary">
        <h3>Summary Statistics</h3>
        <div class="summary-grid">
            <div class="summary-row">
                <div class="summary-item">
                    <div class="summary-label">Total Exhibitors</div>
                    <div class="summary-value">{{ $stats['total_booth_owners'] }}</div>
                </div>
                <div class="summary-item">
                    <div class="summary-label">Paid Bookings</div>
                    <div class="summary-value">{{ $stats['paid_bookings'] }}</div>
                </div>
                <div class="summary-item">
                    <div class="summary-label">Pending Payments</div>
                    <div class="summary-value">{{ $stats['pending_payments'] }}</div>
                </div>
            </div>
            <div class="summary-row">
                <div class="summary-item">
                    <div class="summary-label">Total Revenue</div>
                    <div class="summary-value">${{ number_format($stats['total_revenue'], 2) }}</div>
                </div>
                <div class="summary-item">
                    <div class="summary-label">Potential Revenue</div>
                    <div class="summary-value">${{ number_format($stats['total_potential_revenue'], 2) }}</div>
                </div>
                <div class="summary-item">
                    <div class="summary-label">Collection Rate</div>
                    <div class="summary-value">{{ $stats['total_potential_revenue'] > 0 ? round(($stats['total_revenue'] / $stats['total_potential_revenue']) * 100, 1) : 0 }}%</div>
                </div>
            </div>
            <div class="summary-row">
                <div class="summary-item">
                    <div class="summary-label">Total Booth Members</div>
                    <div class="summary-value">{{ $stats['total_booth_members'] }}</div>
                </div>
                <div class="summary-item">
                    <div class="summary-label">Fully Occupied</div>
                    <div class="summary-value">{{ $stats['fully_occupied'] }}</div>
                </div>
                <div class="summary-item">
                    <div class="summary-label">Partially Filled</div>
                    <div class="summary-value">{{ $stats['partially_filled'] }}</div>
                </div>
            </div>
        </div>
    </div>
    
         <div class="page-break">
         <div class="section-title">Exhibitor Details</div>
         <div class="table-container">
             <table class="exhibitor-table">
                 <thead>
                     <tr>
                         <th>Exhibitor</th>
                         <th>Company</th>
                         <th>Contact</th>
                         <th>Booth</th>
                         <th>Size</th>
                         <th>Price</th>
                         <th>Members</th>
                         <th>Payment</th>
                     </tr>
                 </thead>
                 <tbody>
                     @foreach($boothOwners as $boothOwner)
                     @php
                     $booking = $boothOwner->booking;
                     $floorplanItem = $booking->floorplanItem ?? null;
                     $paymentStatus = $booking->payments->where('status', 'completed')->count() > 0 ? 'paid' :
                     ($booking->payments->where('status', 'pending')->count() > 0 ? 'pending' : 'unpaid');
                     $paymentStatusClass = $paymentStatus === 'paid' ? 'badge-success' :
                     ($paymentStatus === 'pending' ? 'badge-warning' : 'badge-secondary');
                     @endphp
                     <tr>
                         <td>
                             <div class="exhibitor-name">{{ $boothOwner->form_responses['name'] ?? 'N/A' }}</div>
                         </td>
                         <td>
                             <div class="company-name">{{ $boothOwner->form_responses['company_name'] ?? 'N/A' }}</div>
                         </td>
                         <td>
                             <div>{{ $boothOwner->form_responses['email'] ?? 'N/A' }}</div>
                             <div>{{ $boothOwner->form_responses['phone'] ?? 'N/A' }}</div>
                         </td>
                         <td>
                             @if($floorplanItem)
                                 @php
                                 $bookingStatus = $booking->status ?? 'reserved';
                                 $statusColors = [
                                     'reserved' => ['bg' => 'warning', 'icon' => 'clock'],
                                     'confirmed' => ['bg' => 'success', 'icon' => 'check-circle'],
                                     'cancelled' => ['bg' => 'danger', 'icon' => 'x-circle'],
                                     'completed' => ['bg' => 'info', 'icon' => 'check2-all']
                                 ];
                                 $statusConfig = $statusColors[$bookingStatus] ?? $statusColors['reserved'];
                                 @endphp
                                 <div class="booth-badge badge-info">{{ $floorplanItem->label }}</div>
                                 <div class="booth-badge badge-{{ $statusConfig['bg'] }}">{{ ucfirst($bookingStatus) }}</div>
                              @else
                                 N/A
                             @endif
                         </td>
                         <td>
                             @if($floorplanItem)
                                 {{ $floorplanItem->effective_booth_width_meters }}m x {{ $floorplanItem->effective_booth_height_meters }}m
                             @else
                                 N/A
                             @endif
                         </td>
                         <td>
                             @if($floorplanItem)
                                 ${{ number_format($floorplanItem->price, 2) }}
                             @else
                                 N/A
                             @endif
                         </td>
                         <td>
                             @if($floorplanItem)
                                 @php
                                 $memberCount = $boothOwner->boothMembers->count();
                                 $maxCapacity = $floorplanItem->max_capacity ?? 5;
                                 $capacityClass = $memberCount >= $maxCapacity ? 'badge-success' : ($memberCount > 0 ? 'badge-warning' : 'badge-secondary');
                                 @endphp
                                 <span class="booth-badge {{ $capacityClass }}">{{ $memberCount }}/{{ $maxCapacity }}</span>
                             @else
                                 N/A
                             @endif
                         </td>
                         <td>
                             <span class="payment-badge {{ $paymentStatusClass }}">{{ ucfirst($paymentStatus) }}</span>
                         </td>
                     </tr>
                     @endforeach
                 </tbody>
             </table>
         </div>
     </div>
</body>
</html>
