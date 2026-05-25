<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Print ID Card - {{ $employee->user->name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: #f0f0f0;
        }
        .id-card {
            background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%);
            color: white;
            padding: 30px;
            border-radius: 20px;
            width: 350px;
            text-align: center;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        .id-card h4 {
            margin-bottom: 5px;
        }
        .divider {
            border: none;
            border-top: 1px solid rgba(255,255,255,0.3);
            margin: 15px 0;
        }
        .photo {
            width: 120px;
            height: 120px;
            border-radius: 12px;
            border: 3px solid white;
            object-fit: cover;
        }
        .photo-placeholder {
            width: 120px;
            height: 120px;
            border-radius: 12px;
            border: 3px solid white;
            background: white;
            color: #1e3a8a;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 48px;
            font-weight: bold;
            margin: 0 auto;
        }
        .info p {
            margin: 3px 0;
            font-size: 14px;
        }
        .info .code {
            font-size: 18px;
            font-weight: bold;
        }
        @media print {
            body {
                background: white;
            }
            .id-card {
                box-shadow: none;
            }
        }
    </style>
</head>
<body>
    <div class="id-card">
        <h4>{{ \App\Models\CompanySetting::first()->company_name ?? 'SmartERP' }}</h4>
        <small class="opacity-75">Employee ID Card</small>
        
        <div class="divider"></div>
        
        @if($employee->user->avatar)
            <img src="{{ asset('storage/' . $employee->user->avatar) }}" class="photo mb-3">
        @else
            <div class="photo-placeholder mb-3">
                {{ strtoupper(substr($employee->user->name, 0, 1)) }}
            </div>
        @endif
        
        <div class="info">
            <h5>{{ $employee->user->name }}</h5>
            <p>{{ $employee->position->name }}</p>
            <p class="code">{{ $employee->employee_code }}</p>
            <p>{{ $employee->department->name }}</p>
        </div>
        
        <div class="divider"></div>
        
        <small>Joining: {{ $employee->joining_date->format('M d, Y') }}</small>
    </div>
    
    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>