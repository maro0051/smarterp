<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add Employee - SmartERP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Inter', sans-serif;
        }
        .register-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            padding: 30px;
            width: 100%;
            max-width: 700px;
            max-height: 90vh;
            overflow-y: auto;
        }
        .register-header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #f97316;
            padding-bottom: 15px;
        }
        .register-header h2 { color: #1e3a8a; font-weight: 700; margin: 0; }
        .section-title {
            background: #f8fafc;
            padding: 8px 15px;
            border-radius: 8px;
            margin-bottom: 15px;
            font-weight: 600;
            color: #1e3a8a;
            font-size: 14px;
        }
        .form-control, .form-select {
            border-radius: 8px;
            padding: 10px 12px;
            border: 1px solid #e2e8f0;
            font-size: 14px;
        }
        .btn-create {
            background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
            border: none;
            border-radius: 8px;
            padding: 12px;
            font-weight: 600;
            width: 100%;
            color: white;
        }
        .btn-create:hover { transform: translateY(-2px); box-shadow: 0 10px 25px rgba(249, 115, 22, 0.3); }
    </style>
</head>
<body>
    <div class="register-card">
        <div class="register-header">
            <h2><i class="bi bi-person-plus me-2"></i>Add New Employee</h2>
            <p class="text-muted mb-0">Create login credentials and assign role</p>
        </div>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- Login Credentials -->
            <div class="section-title"><i class="bi bi-key me-2"></i>Login Credentials</div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Full Name *</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                           value="{{ old('name') }}" required placeholder="John Doe">
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Email Address *</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                           value="{{ old('email') }}" required placeholder="john@company.com">
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Password *</label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" 
                           required placeholder="Min 8 characters">
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Confirm Password *</label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>
            </div>

            <!-- Role & Department -->
            <div class="section-title"><i class="bi bi-shield-lock me-2"></i>Role & Department</div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Role *</label>
                    <select name="role" class="form-select @error('role') is-invalid @enderror" required>
                        <option value="">Select Role</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->name }}" {{ old('role') == $role->name ? 'selected' : '' }}>
                                {{ $role->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Department *</label>
                    <select name="department_id" class="form-select" required>
                        <option value="">Select</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>
                                {{ $dept->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Position *</label>
                    <select name="position_id" class="form-select" required>
                        <option value="">Select</option>
                        @foreach($positions as $pos)
                            <option value="{{ $pos->id }}" {{ old('position_id') == $pos->id ? 'selected' : '' }}>
                                {{ $pos->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Employment Details -->
            <div class="section-title"><i class="bi bi-briefcase me-2"></i>Employment Details</div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Employee Code</label>
                    <input type="text" name="employee_code" class="form-control" 
                           value="{{ old('employee_code', 'EMP' . str_pad(\App\Models\Employee::count() + 1, 3, '0', STR_PAD_LEFT)) }}" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Salary</label>
                    <input type="number" name="salary" class="form-control" value="{{ old('salary', 0) }}" step="0.01">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
                </div>
            </div>

            <button type="submit" class="btn-create mt-3">
                <i class="bi bi-person-check me-2"></i>Create Employee Account
            </button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>