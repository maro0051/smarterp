<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>ID Card - {{ $employee->user->name }}</title>
    <style>
        @page {
            size: 54mm 86mm;
            margin: 0;
        }
        body {
            font-family: 'Helvetica', sans-serif;
            margin: 0;
            padding: 0;
            width: 54mm;
            height: 86mm;
        }
        .card {
            background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%);
            color: white;
            padding: 15px;
            height: 100%;
            box-sizing: border-box;
            border-radius: 8px;
        }
        .header {
            text-align: center;
            margin-bottom: 10px;
        }
        .header h4 {
            margin: 0;
            font-size: 14px;
        }
        .header small {
            font-size: 9px;
            opacity: 0.8;
        }
        .divider {
            border: none;
            border-top: 1px solid rgba(255,255,255,0.3);
            margin: 10px 0;
        }
        .photo-area {
            text-align: center;
            margin: 10px 0;
        }
        .photo {
            width: 80px;
            height: 80px;
            border-radius: 8px;
            border: 2px solid white;
            object-fit: cover;
        }
        .photo-placeholder {
            width: 80px;
            height: 80px;
            border-radius: 8px;
            border: 2px solid white;
            background: white;
            color: #1e3a8a;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            font-weight: bold;
            margin: 0 auto;
        }
        .info {
            text-align: center;
        }
        .info h3 {
            margin: 5px 0;
            font-size: 16px;
        }
        .info p {
            margin: 2px 0;
            font-size: 10px;
            opacity: 0.9;
        }
        .info .emp-code {
            font-size: 12px;
            font-weight: bold;
            margin: 5px 0;
        }
        .footer {
            text-align: center;
            font-size: 8px;
            opacity: 0.7;
            margin-top: 8px;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="header">
            <h4>{{ $companySettings->company_name ?? 'SmartERP' }}</h4>
            <small>Employee ID Card</small>
        </div>
        
        <div class="divider"></div>
        
        <div class="photo-area">
            @if($employee->user->avatar)
                <img src="{{ public_path('storage/' . $employee->user->avatar) }}" class="photo">
            @else
                <div class="photo-placeholder">
                    {{ strtoupper(substr($employee->user->name, 0, 1)) }}
                </div>
            @endif
        </div>
        
        <div class="info">
            <h3>{{ $employee->user->name }}</h3>
            <p>{{ $employee->position->name }}</p>
            <p class="emp-code">{{ $employee->employee_code }}</p>
            <p>{{ $employee->department->name }}</p>
        </div>
        
        <div class="divider"></div>
        
        <div class="footer">
            Joining: {{ $employee->joining_date->format('M d, Y') }} | {{ $companySettings->company_name ?? 'SmartERP' }}
        </div>
    </div>
</body>
</html>