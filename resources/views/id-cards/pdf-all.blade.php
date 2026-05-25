<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>All Employee ID Cards</title>
    <style>
        @page {
            size: a4;
            margin: 10mm;
        }
        body {
            font-family: 'Helvetica', sans-serif;
            margin: 0;
            padding: 0;
        }
        .page-break {
            page-break-after: always;
        }
        .cards-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
        }
        .card {
            background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%);
            color: white;
            padding: 15px;
            border-radius: 12px;
            text-align: center;
            height: 85mm;
            box-sizing: border-box;
        }
        .card h5 {
            margin: 0 0 5px 0;
            font-size: 12px;
            opacity: 0.8;
        }
        .divider {
            border: none;
            border-top: 1px solid rgba(255,255,255,0.3);
            margin: 8px 0;
        }
        .photo {
            width: 60px;
            height: 60px;
            border-radius: 8px;
            border: 2px solid white;
            object-fit: cover;
            margin: 5px auto;
        }
        .photo-placeholder {
            width: 60px;
            height: 60px;
            border-radius: 8px;
            border: 2px solid white;
            background: white;
            color: #1e3a8a;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            font-weight: bold;
            margin: 5px auto;
        }
        .card h4 {
            margin: 3px 0;
            font-size: 14px;
        }
        .card p {
            margin: 2px 0;
            font-size: 10px;
            opacity: 0.9;
        }
        .card .code {
            font-size: 11px;
            font-weight: bold;
        }
        .card small {
            font-size: 8px;
            opacity: 0.7;
        }
    </style>
</head>
<body>
    @foreach($employees->chunk(6) as $chunk)
    <div class="cards-grid @if(!$loop->last) page-break @endif">
        @foreach($chunk as $employee)
        <div class="card">
            <h5>{{ $companySettings->company_name ?? 'SmartERP' }}</h5>
            <div class="divider"></div>
            
            @if($employee->user->avatar)
                <img src="{{ public_path('storage/' . $employee->user->avatar) }}" class="photo">
            @else
                <div class="photo-placeholder">
                    {{ strtoupper(substr($employee->user->name, 0, 1)) }}
                </div>
            @endif
            
            <h4>{{ $employee->user->name }}</h4>
            <p>{{ $employee->position->name }}</p>
            <p class="code">{{ $employee->employee_code }}</p>
            <p>{{ $employee->department->name }}</p>
            <div class="divider"></div>
            <small>Joined: {{ $employee->joining_date->format('M Y') }}</small>
        </div>
        @endforeach
    </div>
    @endforeach
</body>
</html>