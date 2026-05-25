@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">

    {{-- ==================== QUICK ACTIONS (ALL USERS) ==================== --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body py-2">
                    <div class="d-flex flex-wrap align-items-center gap-2">
                        <span class="text-muted me-2"><i class="bi bi-lightning-charge me-1"></i>Quick Actions:</span>
                        @can('create users')
                        <a href="{{ route('register') }}" class="btn btn-outline-primary btn-sm"><i class="bi bi-person-plus me-1"></i> Add Employee</a>
                        @endcan
                        @can('create invoices')
                        <a href="{{ route('invoices.create') }}" class="btn btn-outline-success btn-sm"><i class="bi bi-receipt me-1"></i> Invoice</a>
                        @endcan
                        @can('create expenses')
                        <a href="{{ route('expenses.create') }}" class="btn btn-outline-danger btn-sm"><i class="bi bi-cash-coin me-1"></i> Expense</a>
                        @endcan
                        @can('create products')
                        <a href="{{ route('products.create') }}" class="btn btn-outline-warning btn-sm"><i class="bi bi-box me-1"></i> Product</a>
                        @endcan
                        @can('create leave requests')
                        <a href="{{ route('leave-requests.create') }}" class="btn btn-outline-info btn-sm"><i class="bi bi-calendar-x me-1"></i> Leave</a>
                        @endcan
                        <a href="{{ route('attendance.punch') }}" class="btn btn-outline-dark btn-sm"><i class="bi bi-clock me-1"></i> Punch</a>
                        @can('create customers')
                        <a href="{{ route('customers.create') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-person-lines-fill me-1"></i> Customer</a>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ==================== ADMIN / SUPER ADMIN DASHBOARD ==================== --}}
    @if(auth()->user()->can('view employees') && auth()->user()->can('view invoices') && auth()->user()->can('view products'))
    <div class="row g-3 mb-4 animate-fadeInUp">
        <div class="col-xl-3 col-md-6">
            <a href="{{ route('employees.index') }}" class="text-decoration-none"><div class="stat-card blue" style="cursor: pointer;"><div class="icon"><i class="bi bi-people-fill"></i></div><p>Total Employees</p><h3>{{ $totalEmployees ?? 0 }}</h3></div></a>
        </div>
        <div class="col-xl-3 col-md-6">
            <a href="{{ route('invoices.index') }}" class="text-decoration-none"><div class="stat-card orange" style="cursor: pointer;"><div class="icon"><i class="bi bi-cash-stack"></i></div><p>Total Revenue</p><h3>${{ number_format($totalRevenue ?? 0, 2) }}</h3></div></a>
        </div>
        <div class="col-xl-3 col-md-6">
            <a href="{{ route('products.index') }}" class="text-decoration-none"><div class="stat-card green" style="cursor: pointer;"><div class="icon"><i class="bi bi-box-seam"></i></div><p>Total Products</p><h3>{{ $totalProducts ?? 0 }}</h3></div></a>
        </div>
        <div class="col-xl-3 col-md-6">
            <a href="{{ route('projects.index') }}" class="text-decoration-none"><div class="stat-card purple" style="cursor: pointer;"><div class="icon"><i class="bi bi-kanban"></i></div><p>Active Projects</p><h3>{{ $totalProjects ?? 0 }}</h3></div></a>
        </div>
    </div>
    <div class="row g-3 mb-4">
        <div class="col-lg-8"><div class="card"><div class="card-header"><h5 class="mb-0">Revenue vs Expenses</h5></div><div class="card-body"><canvas id="revenueChart" height="300"></canvas></div></div></div>
        <div class="col-lg-4"><div class="card"><div class="card-header"><h5 class="mb-0">Departments</h5></div><div class="card-body"><canvas id="departmentChart" height="300"></canvas></div></div></div>
    </div>
    <div class="row g-3 mb-4">
        <div class="col-lg-6"><div class="card"><div class="card-header"><h5 class="mb-0">Recent Invoices</h5></div><div class="card-body p-0"><table class="table mb-0"><thead><tr><th>#</th><th>Customer</th><th>Amount</th><th>Status</th></tr></thead><tbody>@foreach($recentInvoices ?? [] as $inv)<tr><td><a href="{{ route('invoices.show',$inv->id) }}">{{ $inv->invoice_number }}</a></td><td>{{ $inv->customer->name ?? 'N/A' }}</td><td>${{ number_format($inv->total_amount,2) }}</td><td><span class="badge bg-{{ $inv->status=='paid'?'success':'warning' }}">{{ ucfirst($inv->status) }}</span></td></tr>@endforeach</tbody></table></div></div></div>
        <div class="col-lg-6"><div class="card"><div class="card-header"><h5 class="mb-0">Low Stock</h5></div><div class="card-body p-0"><table class="table mb-0"><thead><tr><th>Product</th><th>SKU</th><th>Status</th></tr></thead><tbody>@foreach($lowStockProducts ?? [] as $p)<tr><td>{{ $p->name }}</td><td>{{ $p->sku }}</td><td><span class="badge bg-danger">Low</span></td></tr>@endforeach</tbody></table></div></div></div>
    </div>
    <div class="row"><div class="col-12"><div class="card"><div class="card-header"><h5 class="mb-0">Recent Activities</h5></div><div class="card-body p-0"><table class="table mb-0"><thead><tr><th>User</th><th>Action</th><th>Module</th><th>When</th></tr></thead><tbody>@foreach($recentActivities ?? [] as $a)<tr><td>{{ $a->user->name ?? '?' }}</td><td><span class="badge bg-info">{{ $a->action }}</span></td><td>{{ $a->module }}</td><td>{{ $a->created_at->diffForHumans() }}</td></tr>@endforeach</tbody></table></div></div></div></div>
    @endif

    {{-- ==================== HR MANAGER DASHBOARD ==================== --}}
    @if(auth()->user()->can('view employees') && !auth()->user()->can('view invoices') && !auth()->user()->can('view products'))
    <div class="row g-3 mb-4">
        <div class="col-xl-2 col-md-4 col-sm-6"><a href="{{ route('employees.index') }}" class="text-decoration-none"><div class="stat-card blue" style="cursor: pointer;"><div class="icon"><i class="bi bi-people-fill"></i></div><p>Total Employees</p><h3>{{ $totalEmployees ?? 0 }}</h3></div></a></div>
        <div class="col-xl-2 col-md-4 col-sm-6"><a href="{{ route('departments.index') }}" class="text-decoration-none"><div class="stat-card green" style="cursor: pointer;"><div class="icon"><i class="bi bi-diagram-3"></i></div><p>Departments</p><h3>{{ $totalDepartments ?? 0 }}</h3></div></a></div>
        <div class="col-xl-2 col-md-4 col-sm-6"><div class="stat-card orange"><div class="icon"><i class="bi bi-clock"></i></div><p>On Leave</p><h3>{{ $onLeaveToday ?? 0 }}</h3></div></div>
        <div class="col-xl-2 col-md-4 col-sm-6"><div class="stat-card purple"><div class="icon"><i class="bi bi-person-check"></i></div><p>New Hires</p><h3>{{ $newHiresThisMonth ?? 0 }}</h3></div></div>
        <div class="col-xl-2 col-md-4 col-sm-6"><a href="{{ route('leave-requests.index') }}" class="text-decoration-none"><div class="stat-card" style="cursor:pointer;background:linear-gradient(135deg,#f59e0b,#d97706);"><div class="icon"><i class="bi bi-calendar-x"></i></div><p>Pending Leaves</p><h3>{{ $pendingLeaveCount ?? 0 }}</h3></div></a></div>
        <div class="col-xl-2 col-md-4 col-sm-6"><a href="{{ route('hr.recruitments.index') }}" class="text-decoration-none"><div class="stat-card" style="cursor:pointer;background:linear-gradient(135deg,#06b6d4,#0891b2);"><div class="icon"><i class="bi bi-briefcase"></i></div><p>Open Jobs</p><h3>{{ $openRecruitments ?? 0 }}</h3></div></a></div>
    </div>
    <div class="row g-3">
        <div class="col-lg-4"><div class="card h-100"><div class="card-header"><h5 class="mb-0">Today's Attendance</h5></div><div class="card-body text-center"><div class="row"><div class="col-6"><div class="p-3 bg-success bg-opacity-10 rounded-3"><i class="bi bi-check-circle text-success fs-1"></i><h3 class="text-success mb-0">{{ $presentToday ?? 0 }}</h3><small>Present</small></div></div><div class="col-6"><div class="p-3 bg-danger bg-opacity-10 rounded-3"><i class="bi bi-x-circle text-danger fs-1"></i><h3 class="text-danger mb-0">{{ max(0,$absentToday ?? 0) }}</h3><small>Absent</small></div></div></div></div></div></div>
        <div class="col-lg-4"><div class="card h-100"><div class="card-header"><h5 class="mb-0">Pending Leave Requests</h5></div><div class="card-body p-0">@forelse($pendingLeaves ?? [] as $leave)<div class="p-3 border-bottom"><div class="d-flex justify-content-between"><div><strong>{{ $leave->employee->user->name ?? '?' }}</strong><br><small>{{ $leave->leaveType->name ?? '?' }} | {{ $leave->start_date->format('M d') }} - {{ $leave->end_date->format('M d') }}</small></div><div class="btn-group btn-group-sm"><form action="{{ route('leave-requests.approve',$leave->id) }}" method="POST">@csrf<button class="btn btn-success btn-sm"><i class="bi bi-check-lg"></i></button></form><form action="{{ route('leave-requests.reject',$leave->id) }}" method="POST">@csrf<button class="btn btn-danger btn-sm"><i class="bi bi-x-lg"></i></button></form></div></div></div>@empty<div class="text-center py-4 text-muted">No pending requests</div>@endforelse</div></div></div>
        <div class="col-lg-4"><div class="card h-100"><div class="card-header"><h5 class="mb-0">Upcoming Interviews</h5></div><div class="card-body p-0">@forelse($upcomingInterviews ?? [] as $iv)<div class="p-3 border-bottom"><strong>{{ $iv->candidate_name }}</strong><br><small>{{ $iv->recruitment->position ?? 'N/A' }}</small><br><small>{{ \Carbon\Carbon::parse($iv->interview_date)->format('M d, Y h:i A') }}</small></div>@empty<div class="text-center py-4 text-muted">No interviews</div>@endforelse</div></div></div>
    </div>
    <div class="row g-3 mt-3">
        <div class="col-lg-6"><div class="card"><div class="card-header"><h5 class="mb-0">Attendance Trend</h5></div><div class="card-body"><canvas id="attendanceTrendChart" height="250"></canvas></div></div></div>
        <div class="col-lg-6"><div class="card"><div class="card-header"><h5 class="mb-0">Department Distribution</h5></div><div class="card-body"><canvas id="deptChart" height="250"></canvas></div></div></div>
    </div>
    <div class="row g-3 mt-3">
        <div class="col-lg-6"><div class="card"><div class="card-header"><h5 class="mb-0">Recent Hires</h5></div><div class="card-body p-0"><table class="table table-sm mb-0"><thead><tr><th>Employee</th><th>Department</th><th>Joined</th></tr></thead><tbody>@foreach($recentHires ?? [] as $h)<tr><td>{{ $h->user->name ?? '?' }}</td><td>{{ $h->department->name ?? '?' }}</td><td>{{ $h->joining_date->format('M d') }}</td></tr>@endforeach</tbody></table></div></div></div>
        <div class="col-lg-6"><div class="card"><div class="card-header"><h5 class="mb-0">Recent HR Activities</h5></div><div class="card-body p-0"><table class="table table-sm mb-0"><thead><tr><th>User</th><th>Action</th><th>When</th></tr></thead><tbody>@foreach($recentActivities ?? [] as $a)<tr><td>{{ $a->user->name ?? '?' }}</td><td>{{ $a->description }}</td><td><small>{{ $a->created_at->diffForHumans() }}</small></td></tr>@endforeach</tbody></table></div></div></div>
    </div>
    @endif

    {{-- ==================== ACCOUNTANT DASHBOARD ==================== --}}
    @if(auth()->user()->can('view expenses') && auth()->user()->can('view invoices') && !auth()->user()->can('view employees'))
    <div class="row g-3 mb-4">
        <div class="col-md-3"><div class="stat-card green"><div class="icon"><i class="bi bi-cash-stack"></i></div><p>Total Revenue</p><h3>${{ number_format($totalRevenue ?? 0, 2) }}</h3></div></div>
        <div class="col-md-3"><div class="stat-card orange"><div class="icon"><i class="bi bi-cash-coin"></i></div><p>Total Expenses</p><h3>${{ number_format($totalExpenses ?? 0, 2) }}</h3></div></div>
        <div class="col-md-3"><div class="stat-card purple"><div class="icon"><i class="bi bi-graph-up"></i></div><p>Net Profit</p><h3>${{ number_format(($totalRevenue??0)-($totalExpenses??0), 2) }}</h3></div></div>
        <div class="col-md-3"><a href="{{ route('expenses.index') }}"><div class="stat-card" style="background:linear-gradient(135deg,#f59e0b,#d97706);cursor:pointer;"><div class="icon"><i class="bi bi-clock"></i></div><p>Pending Expenses</p><h3>{{ $pendingExpenses ?? 0 }}</h3></div></a></div>
    </div>
    <div class="row g-3">
        <div class="col-lg-6"><div class="card"><div class="card-header"><h5 class="mb-0">Pending Expense Approvals</h5></div><div class="card-body p-0"><table class="table table-sm mb-0"><thead><tr><th>#</th><th>Category</th><th>Amount</th><th>By</th><th>Action</th></tr></thead><tbody>@forelse($pendingExpensesList ?? [] as $exp)<tr><td>{{ $exp->expense_number }}</td><td>{{ $exp->category->name ?? 'N/A' }}</td><td>${{ number_format($exp->amount,2) }}</td><td>{{ $exp->createdBy->name ?? 'N/A' }}</td><td><div class="btn-group btn-group-sm"><form action="{{ route('expenses.approve',$exp->id) }}" method="POST">@csrf<button class="btn btn-success"><i class="bi bi-check-lg"></i></button></form><form action="{{ route('expenses.reject',$exp->id) }}" method="POST">@csrf<button class="btn btn-danger"><i class="bi bi-x-lg"></i></button></form></div></td></tr>@empty<tr><td colspan="5" class="text-center py-3 text-muted">No pending expenses</td></tr>@endforelse</tbody></table></div></div></div>
        <div class="col-lg-6"><div class="card"><div class="card-header"><h5 class="mb-0">Recent Invoices</h5></div><div class="card-body p-0"><table class="table table-sm mb-0"><thead><tr><th>#</th><th>Customer</th><th>Amount</th><th>Status</th></tr></thead><tbody>@foreach($recentInvoices ?? [] as $inv)<tr><td>{{ $inv->invoice_number }}</td><td>{{ $inv->customer->name ?? 'N/A' }}</td><td>${{ number_format($inv->total_amount,2) }}</td><td><span class="badge bg-{{ $inv->status=='paid'?'success':'warning' }}">{{ ucfirst($inv->status) }}</span></td></tr>@endforeach</tbody></table></div></div></div>
    </div>
    @endif

    {{-- ==================== EMPLOYEE DASHBOARD ==================== --}}
    @if(!auth()->user()->can('view employees') && !auth()->user()->can('view invoices') && !auth()->user()->can('view products'))
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-primary text-white">
                <div class="card-body py-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div><h4 class="mb-1">Welcome, {{ auth()->user()->name }}! 👋</h4><p class="mb-0 opacity-75">{{ now()->format('l, F d, Y') }}</p></div>
                        <div><a href="{{ route('attendance.punch') }}" class="btn btn-light me-2"><i class="bi bi-clock me-1"></i> Punch In/Out</a><a href="{{ route('leave-requests.create') }}" class="btn btn-outline-light"><i class="bi bi-calendar-x me-1"></i> Request Leave</a></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row g-3 mb-4">
        <div class="col-md-4"><div class="card h-100"><div class="card-body text-center py-4">
            @if($todayAttendance && !$todayAttendance->time_out)<i class="bi bi-clock text-warning display-4"></i><h5>Checked In</h5><p>Since {{ \Carbon\Carbon::parse($todayAttendance->time_in)->format('h:i A') }}</p><span class="badge bg-warning">Working</span>
            @elseif($todayAttendance && $todayAttendance->time_out)<i class="bi bi-check-circle text-success display-4"></i><h5>Completed</h5><p>{{ \Carbon\Carbon::parse($todayAttendance->time_in)->format('h:i A') }} - {{ \Carbon\Carbon::parse($todayAttendance->time_out)->format('h:i A') }}</p>
            @else<i class="bi bi-x-circle text-danger display-4"></i><h5>Not Checked In</h5><a href="{{ route('attendance.punch') }}" class="btn btn-primary btn-sm">Punch In</a>@endif
        </div></div></div>
        <div class="col-md-4"><div class="card h-100"><div class="card-body text-center py-4"><i class="bi bi-calendar-check text-primary display-4"></i><h5>Monthly Attendance</h5><div class="row mt-2"><div class="col-4"><span class="badge bg-success">Present</span><h4>{{ $monthStats['present']??0 }}</h4></div><div class="col-4"><span class="badge bg-warning">Late</span><h4>{{ $monthStats['late']??0 }}</h4></div><div class="col-4"><span class="badge bg-danger">Absent</span><h4>{{ $monthStats['absent']??0 }}</h4></div></div><a href="{{ route('attendance.index') }}" class="btn btn-outline-primary btn-sm mt-2">View All</a></div></div></div>
        <div class="col-md-4"><div class="card h-100"><div class="card-body text-center py-4"><i class="bi bi-cash-stack text-success display-4"></i><h5>Latest Payslip</h5>@if($latestPayslip)<h3 class="text-success">${{ number_format($latestPayslip->net_salary,2) }}</h3><small>{{ $latestPayslip->payroll->period_start->format('M Y') }}</small><br><a href="{{ route('payslips.my') }}" class="btn btn-outline-success btn-sm mt-2">View All</a>@else<p class="text-muted">No payslips yet</p>@endif</div></div></div>
    </div>
    <div class="row g-3">
        <div class="col-md-6"><div class="card h-100"><div class="card-header d-flex justify-content-between"><h5 class="mb-0">Leave Balance</h5><a href="{{ route('leave-requests.create') }}" class="btn btn-sm btn-primary">Request</a></div><div class="card-body p-0"><table class="table table-sm mb-0"><thead><tr><th>Type</th><th>Used</th><th>Left</th></tr></thead><tbody>@foreach($leaveBalance as $b)<tr><td>{{ $b['type'] }}</td><td>{{ $b['used'] }}</td><td><span class="badge bg-{{ $b['remaining']>0?'success':'danger' }}">{{ $b['remaining'] }}</span></td></tr>@endforeach</tbody></table></div></div></div>
        <div class="col-md-6"><div class="card h-100"><div class="card-header d-flex justify-content-between"><h5 class="mb-0">My Tasks</h5><a href="{{ route('tasks.my') }}" class="btn btn-sm btn-outline-primary">View All</a></div><div class="card-body p-0">@forelse($pendingTasks as $task)<div class="p-3 border-bottom"><div class="d-flex justify-content-between align-items-start mb-2"><div><strong><a href="{{ route('tasks.view', $task->id) }}" class="text-dark text-decoration-none">{{ $task->title }}</a></strong><br><small class="text-muted">Project: {{ $task->project->name ?? 'N/A' }}</small></div><span class="badge bg-{{ $task->priority=='urgent'?'danger':($task->priority=='high'?'warning':'info') }}">{{ ucfirst($task->priority) }}</span></div><div class="d-flex justify-content-between align-items-center mt-2"><div><span class="badge bg-{{ $task->status=='completed'?'success':($task->status=='in_progress'?'primary':($task->status=='review'?'warning':'secondary')) }}">{{ ucfirst(str_replace('_',' ',$task->status)) }}</span>@if($task->due_date)<small class="text-muted ms-2"><i class="bi bi-calendar me-1"></i>{{ $task->due_date->format('M d') }}</small>@endif</div><div class="btn-group btn-group-sm">@if($task->status=='todo')<button class="btn btn-outline-primary btn-sm" onclick="updateMyTask({{ $task->id }},'in_progress')" title="Start"><i class="bi bi-play-fill"></i></button>@endif @if($task->status=='in_progress')<button class="btn btn-outline-warning btn-sm" onclick="updateMyTask({{ $task->id }},'review')" title="Submit"><i class="bi bi-send"></i></button><button class="btn btn-outline-success btn-sm" onclick="updateMyTask({{ $task->id }},'completed')" title="Done"><i class="bi bi-check-lg"></i></button>@endif</div></div></div>@empty<div class="text-center py-4 text-muted"><i class="bi bi-check-circle" style="font-size:32px;"></i><p class="mt-2">No pending tasks</p></div>@endforelse</div></div></div>
    </div>
    <div class="row g-3 mt-3">
        <div class="col-12"><div class="card"><div class="card-header d-flex justify-content-between"><h5 class="mb-0">Recent Attendance</h5><a href="{{ route('attendance.index') }}" class="btn btn-sm btn-outline-primary">View All</a></div><div class="card-body p-0"><table class="table table-sm mb-0"><thead><tr><th>Date</th><th>In</th><th>Out</th><th>Status</th></tr></thead><tbody>@php $empRecord = \App\Models\Employee::where('user_id', auth()->id())->first(); $recentAtt = $empRecord ? \App\Models\Attendance::where('employee_id', $empRecord->id)->latest('date')->take(10)->get() : collect(); @endphp @forelse($recentAtt as $att)<tr><td>{{ $att->date->format('M d, Y') }}</td><td>{{ $att->time_in ? \Carbon\Carbon::parse($att->time_in)->format('h:i A') : '-' }}</td><td>{{ $att->time_out ? \Carbon\Carbon::parse($att->time_out)->format('h:i A') : '-' }}</td><td><span class="badge bg-{{ $att->status=='present'?'success':($att->status=='late'?'warning':'danger') }}">{{ ucfirst($att->status) }}</span></td></tr>@empty<tr><td colspan="4" class="text-center text-muted py-3">No records</td></tr>@endforelse</tbody></table></div></div></div>
    </div>
    @endif

</div>

@push('scripts')
<script>
    // Update task status from employee dashboard
    function updateMyTask(taskId, status) {
        const messages = {
            'in_progress': 'Task started! 🚀',
            'review': 'Task submitted for review! 📤',
            'completed': 'Task completed! ✅'
        };
        fetch(`/tasks/${taskId}/update-status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ status: status })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast(messages[status] || 'Task updated!', 'success');
                setTimeout(() => location.reload(), 600);
            }
        });
    }

    // Admin Charts
    @if(isset($months))
    const rc=document.getElementById('revenueChart')?.getContext('2d');
    if(rc)new Chart(rc,{type:'line',data:{labels:{!! json_encode($months) !!},datasets:[{label:'Revenue',data:{!! json_encode($monthlyRevenue??[]) !!},borderColor:'#3b82f6',backgroundColor:'rgba(59,130,246,0.1)',tension:0.4,fill:!0},{label:'Expenses',data:{!! json_encode($monthlyExpenses??[]) !!},borderColor:'#f97316',backgroundColor:'rgba(249,115,22,0.1)',tension:0.4,fill:!0}]},options:{responsive:!0,maintainAspectRatio:!1}});
    const dc=document.getElementById('departmentChart')?.getContext('2d');
    if(dc)new Chart(dc,{type:'doughnut',data:{labels:{!! json_encode(($employeesByDepartment??collect())->pluck('name')) !!},datasets:[{data:{!! json_encode(($employeesByDepartment??collect())->pluck('total')) !!},backgroundColor:['#3b82f6','#f97316','#10b981','#8b5cf6','#f59e0b','#ec4899','#06b6d4']}]},options:{responsive:!0,maintainAspectRatio:!1}});
    @endif

    // HR Charts
    @if(isset($trendMonths) && !isset($months))
    const tc=document.getElementById('attendanceTrendChart')?.getContext('2d');
    if(tc)new Chart(tc,{type:'line',data:{labels:{!! json_encode($trendMonths) !!},datasets:[{label:'Attendance',data:{!! json_encode($attendanceTrend??[]) !!},borderColor:'#3b82f6',backgroundColor:'rgba(59,130,246,0.1)',tension:0.4,fill:!0}]},options:{responsive:!0,maintainAspectRatio:!1,plugins:{legend:{display:!1}}}});
    const dcc=document.getElementById('deptChart')?.getContext('2d');
    if(dcc)new Chart(dcc,{type:'doughnut',data:{labels:{!! json_encode(($deptDistribution??collect())->pluck('name')) !!},datasets:[{data:{!! json_encode(($deptDistribution??collect())->pluck('total')) !!},backgroundColor:['#3b82f6','#f97316','#10b981','#8b5cf6','#f59e0b','#ec4899','#06b6d4']}]},options:{responsive:!0,maintainAspectRatio:!1}});
    @endif
</script>
@endpush
@endsection