# SmartERP - Enterprise Resource Planning System

![Version](https://img.shields.io/badge/version-1.0.0-blue)
![Laravel](https://img.shields.io/badge/Laravel-13.x-red)
![PHP](https://img.shields.io/badge/PHP-8.2%2B-purple)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-blue)
![License](https://img.shields.io/badge/license-MIT-green)

A comprehensive, modern Enterprise Resource Planning system built with Laravel. Manages HR, Inventory, Sales, Accounting, CRM, and Project Management in one unified platform.

---

## 📋 Table of Contents

- [Features](#-features)
- [User Roles](#-user-roles)
- [Default Login](#-default-login)
- [Installation](#-installation)
- [Folder Structure](#-folder-structure)
- [Common Workflows](#-common-workflows)
- [API Endpoints](#-api-endpoints)
- [Deployment](#-deployment)
- [Troubleshooting](#-troubleshooting)
- [Security](#-security)
- [License](#-license)

---

## ✨ Features

### Core Modules

| Module | Description |
|--------|-------------|
| 🔐 **Authentication** | Login, Register, Password Reset, Two-Factor Auth |
| 📊 **Dashboard** | Role-based dashboards with charts and analytics |
| 👥 **Employee Management** | CRUD, Departments, Positions, ID Cards |
| ⏰ **Attendance** | Punch In/Out system, attendance tracking |
| 🏖️ **Leave Management** | Request, approve, reject leave |
| 💼 **HR Module** | Recruitment, Interviews, Performance Reviews |
| 📦 **Inventory** | Products, Categories, Suppliers, Warehouses |
| 📈 **Stock Management** | Stock in/out, low stock alerts |
| 🛒 **Sales** | Customers, Invoices, Payments |
| 💰 **Accounting** | Expenses, Financial reports |
| 🤝 **CRM** | Leads, Pipeline management |
| 📁 **Projects** | Kanban board, Task assignment |
| 💵 **Payroll** | Payroll generation, Payslips |
| 📑 **Reports** | Sales, Inventory, Financial reports |
| ⚙️ **Settings** | Company configuration |
| 🔒 **User Management** | Roles & Permissions |
| 📝 **Activity Logs** | Complete audit trail |

### Technical Features

- ✅ Role-Based Access Control (7 roles)
- ✅ 50+ Granular Permissions
- ✅ Responsive Design (Mobile-friendly)
- ✅ Charts & Analytics (Chart.js)
- ✅ PDF Export (DomPDF)
- ✅ Email Notifications
- ✅ Two-Factor Authentication
- ✅ REST API
- ✅ Activity Logging
- ✅ CSV Export

---

## 👥 User Roles

| Role | Access Level | Description |
|------|-------------|-------------|
| **Super Admin** | Full System | Complete access to everything |
| **HR Manager** | HR Module | Employees, attendance, leaves, recruitment |
| **Accountant** | Financial | Expenses, invoices, payments, reports |
| **Inventory Manager** | Inventory | Products, stock, suppliers, warehouses |
| **Sales Manager** | Sales & CRM | Customers, leads, invoices, payments |
| **Employee** | Self-Service | Own attendance, leave, tasks, payslips |

---

## 🔐 Default Login

| Role | Email | Password |
|------|-------|----------|
| **Super Admin** | admin@smarterp.com | password123 |
| **HR Manager** | sarah.johnson@smarterp.com | password123 |
| **Accountant** | michael.chen@smarterp.com | password123 |
| **Inventory Manager** | emily.rodriguez@smarterp.com | password123 |
| **Sales Manager** | david.thompson@smarterp.com | password123 |
| **Employee** | lisa.anderson@smarterp.com | password123 |

> ⚠️ **Important:** Change all default passwords immediately after first login in production!

---

## 🚀 Installation

### Prerequisites

- PHP 8.2 or higher
- Composer
- Node.js & NPM
- MySQL 5.7+ or SQLite
- Git

### Step-by-Step Installation

#### 1. Clone the Repository

```bash
git clone https://github.com/yourusername/smarterp.git
cd smarterp
```

#### 2. Install Dependencies

```bash
composer install
npm install
```

#### 3. Environment Configuration

```bash
cp .env.example .env
php artisan key:generate
```

#### 4. Configure Database

Edit `.env` file:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=smarterp
DB_USERNAME=root
DB_PASSWORD=
```

Or use SQLite:

```env
DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/database.sqlite
```

#### 5. Run Migrations & Seeders

```bash
php artisan migrate:fresh --seed
```

#### 6. Create Storage Link

```bash
php artisan storage:link
```

#### 7. Build Frontend Assets

```bash
npm run build
```

#### 8. Start the Server

```bash
php artisan serve
```

Access at: **http://localhost:8000**

---

## 📁 Folder Structure

```
smarterp/
│
├── app/
│   ├── Helpers/
│   │   ├── ActivityHelper.php          # Activity logging helper
│   │   └── helpers.php                 # Global helper functions
│   │
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/
│   │   │   │   ├── AuthenticatedSessionController.php
│   │   │   │   ├── ConfirmablePasswordController.php
│   │   │   │   ├── EmailVerificationNotificationController.php
│   │   │   │   ├── EmailVerificationPromptController.php
│   │   │   │   ├── NewPasswordController.php
│   │   │   │   ├── PasswordController.php
│   │   │   │   ├── PasswordResetLinkController.php
│   │   │   │   ├── RegisteredUserController.php
│   │   │   │   └── VerifyEmailController.php
│   │   │   │
│   │   │   ├── Api/
│   │   │   │   ├── CustomerApiController.php
│   │   │   │   ├── EmployeeApiController.php
│   │   │   │   ├── ExpenseApiController.php
│   │   │   │   ├── InvoiceApiController.php
│   │   │   │   ├── LeadApiController.php
│   │   │   │   ├── ProductApiController.php
│   │   │   │   └── ProjectApiController.php
│   │   │   │
│   │   │   ├── HR/
│   │   │   │   ├── InterviewController.php
│   │   │   │   ├── PerformanceReviewController.php
│   │   │   │   └── RecruitmentController.php
│   │   │   │
│   │   │   ├── Controller.php
│   │   │   ├── ActivityLogController.php
│   │   │   ├── AttendanceController.php
│   │   │   ├── CategoryController.php
│   │   │   ├── CustomerController.php
│   │   │   ├── DashboardController.php
│   │   │   ├── DepartmentController.php
│   │   │   ├── EmployeeController.php
│   │   │   ├── ExpenseController.php
│   │   │   ├── IDCardController.php
│   │   │   ├── InvoiceController.php
│   │   │   ├── LeadController.php
│   │   │   ├── LeaveRequestController.php
│   │   │   ├── PaymentController.php
│   │   │   ├── PayslipController.php
│   │   │   ├── PositionController.php
│   │   │   ├── ProductController.php
│   │   │   ├── ProfileController.php
│   │   │   ├── ProjectController.php
│   │   │   ├── ReportController.php
│   │   │   ├── SettingController.php
│   │   │   ├── SupplierController.php
│   │   │   ├── TaskController.php
│   │   │   ├── TwoFactorController.php
│   │   │   ├── UserController.php
│   │   │   └── WarehouseController.php
│   │   │
│   │   ├── Middleware/
│   │   │   └── CheckPermission.php
│   │   │
│   │   └── Requests/
│   │       ├── Auth/
│   │       │   └── LoginRequest.php
│   │       └── ProfileUpdateRequest.php
│   │
│   ├── Mail/
│   │   └── TwoFactorCodeMail.php        # 2FA email
│   │
│   └── Models/
│       ├── ActivityLog.php
│       ├── Attendance.php
│       ├── Category.php
│       ├── CompanySetting.php
│       ├── Customer.php
│       ├── Department.php
│       ├── Document.php
│       ├── Employee.php
│       ├── Expense.php
│       ├── Interview.php
│       ├── Invoice.php
│       ├── InvoiceItem.php
│       ├── Lead.php
│       ├── LeaveRequest.php
│       ├── LeaveType.php
│       ├── Notification.php
│       ├── Payment.php
│       ├── Payroll.php
│       ├── Payslip.php
│       ├── PerformanceReview.php
│       ├── Position.php
│       ├── Product.php
│       ├── Project.php
│       ├── Recruitment.php
│       ├── StockMovement.php
│       ├── Supplier.php
│       ├── Task.php
│       ├── Training.php
│       ├── User.php
│       └── Warehouse.php
│
├── bootstrap/
│   ├── app.php
│   ├── cache/
│   └── providers.php
│
├── config/
│   ├── app.php
│   ├── auth.php
│   ├── cache.php
│   ├── database.php
│   ├── filesystems.php
│   ├── logging.php
│   ├── mail.php
│   ├── permission.php
│   ├── queue.php
│   ├── sanctum.php
│   ├── services.php
│   └── session.php
│
├── database/
│   ├── database.sqlite
│   ├── factories/
│   │   └── UserFactory.php
│   │
│   ├── migrations/
│   │   ├── 0001_01_01_000000_create_users_table.php
│   │   ├── 0001_01_01_000001_create_cache_table.php
│   │   ├── 0001_01_01_000002_create_jobs_table.php
│   │   ├── 2024_01_01_000001_create_departments_table.php
│   │   ├── 2024_01_01_000002_create_positions_table.php
│   │   ├── 2024_01_01_000003_create_employees_table.php
│   │   ├── 2024_01_01_000004_create_attendances_table.php
│   │   ├── 2024_01_01_000005_create_leave_types_table.php
│   │   ├── 2024_01_01_000006_create_leave_requests_table.php
│   │   ├── 2024_01_01_000007_create_categories_table.php
│   │   ├── 2024_01_01_000008_create_suppliers_table.php
│   │   ├── 2024_01_01_000009_create_warehouses_table.php
│   │   ├── 2024_01_01_000010_create_products_table.php
│   │   ├── 2024_01_01_000011_create_stock_movements_table.php
│   │   ├── 2024_01_01_000012_create_customers_table.php
│   │   ├── 2024_01_01_000013_create_invoices_table.php
│   │   ├── 2024_01_01_000014_create_invoice_items_table.php
│   │   ├── 2024_01_01_000015_create_payments_table.php
│   │   ├── 2024_01_01_000016_create_expenses_table.php
│   │   ├── 2024_01_01_000017_create_projects_table.php
│   │   ├── 2024_01_01_000018_create_tasks_table.php
│   │   ├── 2024_01_01_000019_create_leads_table.php
│   │   ├── 2024_01_01_000020_create_notifications_table.php
│   │   ├── 2024_01_01_000021_create_activity_logs_table.php
│   │   ├── 2024_01_01_000022_create_company_settings_table.php
│   │   ├── 2026_05_19_162330_create_interviews_table.php
│   │   ├── 2026_05_19_162331_create_recruitments_table.php
│   │   ├── 2026_05_19_162332_create_performance_reviews_table.php
│   │   ├── 2026_05_19_162333_create_trainings_table.php
│   │   ├── 2026_05_19_162334_create_payrolls_table.php
│   │   ├── 2026_05_19_162335_create_payslips_table.php
│   │   ├── 2026_05_19_162336_create_documents_table.php
│   │   └── 2026_05_20_000000_add_two_factor_to_users_table.php
│   │
│   └── seeders/
│       ├── DatabaseSeeder.php
│       ├── RolesAndPermissionsSeeder.php
│       ├── UserSeeder.php
│       ├── DepartmentSeeder.php
│       ├── PositionSeeder.php
│       ├── CategorySeeder.php
│       ├── LeaveTypeSeeder.php
│       └── CompanySettingSeeder.php
│
├── public/
│   ├── build/
│   ├── css/
│   ├── images/
│   ├── js/
│   ├── storage/
│   ├── favicon.ico
│   ├── index.php
│   └── robots.txt
│
├── resources/
│   ├── css/
│   │   └── app.css
│   │
│   ├── js/
│   │   ├── app.js
│   │   └── bootstrap.js
│   │
│   └── views/
│       ├── layouts/
│       │   └── app.blade.php
│       │
│       ├── auth/
│       │   ├── confirm-password.blade.php
│       │   ├── forgot-password.blade.php
│       │   ├── login.blade.php
│       │   ├── register.blade.php
│       │   ├── reset-password.blade.php
│       │   ├── two-factor-challenge.blade.php
│       │   └── verify-email.blade.php
│       │
│       ├── profile/
│       │   ├── edit.blade.php
│       │   └── partials/
│       │
│       ├── employees/
│       │   ├── index.blade.php
│       │   ├── create.blade.php
│       │   ├── edit.blade.php
│       │   └── show.blade.php
│       │
│       ├── departments/
│       │   ├── index.blade.php
│       │   ├── create.blade.php
│       │   ├── edit.blade.php
│       │   └── show.blade.php
│       │
│       ├── positions/
│       │   ├── index.blade.php
│       │   ├── create.blade.php
│       │   └── edit.blade.php
│       │
│       ├── attendance/
│       │   ├── index.blade.php
│       │   ├── punch.blade.php
│       │   └── edit.blade.php
│       │
│       ├── leave-requests/
│       │   ├── index.blade.php
│       │   ├── create.blade.php
│       │   └── show.blade.php
│       │
│       ├── hr/
│       │   ├── recruitments/
│       │   │   ├── index.blade.php
│       │   │   ├── create.blade.php
│       │   │   ├── edit.blade.php
│       │   │   └── show.blade.php
│       │   │
│       │   └── performance/
│       │       ├── index.blade.php
│       │       ├── create.blade.php
│       │       └── show.blade.php
│       │
│       ├── products/
│       │   ├── index.blade.php
│       │   ├── create.blade.php
│       │   ├── edit.blade.php
│       │   ├── show.blade.php
│       │   └── stock-movements.blade.php
│       │
│       ├── categories/
│       │   ├── index.blade.php
│       │   ├── create.blade.php
│       │   ├── edit.blade.php
│       │   └── show.blade.php
│       │
│       ├── suppliers/
│       │   ├── index.blade.php
│       │   ├── create.blade.php
│       │   ├── edit.blade.php
│       │   └── show.blade.php
│       │
│       ├── warehouses/
│       │   ├── index.blade.php
│       │   ├── create.blade.php
│       │   ├── edit.blade.php
│       │   └── show.blade.php
│       │
│       ├── customers/
│       │   ├── index.blade.php
│       │   ├── create.blade.php
│       │   ├── edit.blade.php
│       │   └── show.blade.php
│       │
│       ├── invoices/
│       │   ├── index.blade.php
│       │   ├── create.blade.php
│       │   ├── edit.blade.php
│       │   ├── show.blade.php
│       │   └── pdf.blade.php
│       │
│       ├── payments/
│       │   └── index.blade.php
│       │
│       ├── expenses/
│       │   ├── index.blade.php
│       │   ├── create.blade.php
│       │   ├── edit.blade.php
│       │   └── show.blade.php
│       │
│       ├── leads/
│       │   ├── index.blade.php
│       │   ├── create.blade.php
│       │   ├── edit.blade.php
│       │   └── show.blade.php
│       │
│       ├── projects/
│       │   ├── index.blade.php
│       │   ├── create.blade.php
│       │   ├── edit.blade.php
│       │   └── show.blade.php
│       │
│       ├── tasks/
│       │   ├── my.blade.php
│       │   └── view.blade.php
│       │
│       ├── payslips/
│       │   ├── index.blade.php
│       │   ├── my.blade.php
│       │   ├── show.blade.php
│       │   ├── print.blade.php
│       │   └── pdf.blade.php
│       │
│       ├── id-cards/
│       │   ├── index.blade.php
│       │   ├── single.blade.php
│       │   ├── all.blade.php
│       │   ├── print.blade.php
│       │   ├── pdf.blade.php
│       │   └── pdf-all.blade.php
│       │
│       ├── reports/
│       │   ├── index.blade.php
│       │   ├── sales.blade.php
│       │   ├── inventory.blade.php
│       │   ├── financial.blade.php
│       │   └── exports/
│       │
│       ├── settings/
│       │   └── index.blade.php
│       │
│       ├── users/
│       │   ├── index.blade.php
│       │   ├── create.blade.php
│       │   └── edit.blade.php
│       │
│       ├── activity-logs/
│       │   └── index.blade.php
│       │
│       ├── notifications/
│       │   └── index.blade.php
│       │
│       ├── emails/
│       │   └── two-factor-code.blade.php
│       │
│       ├── errors/
│       │   └── 403.blade.php
│       │
│       └── dashboard.blade.php
│
├── routes/
│   ├── api.php
│   ├── auth.php
│   ├── console.php
│   └── web.php
│
├── storage/
│   ├── app/
│   │   ├── public/
│   │   │   ├── avatars/
│   │   │   ├── products/
│   │   │   ├── receipts/
│   │   │   └── company/
│   │   └── private/
│   │
│   ├── framework/
│   │   ├── cache/
│   │   ├── sessions/
│   │   └── views/
│   │
│   └── logs/
│       └── laravel.log
│
├── tests/
│   ├── Feature/
│   ├── Unit/
│   ├── Pest.php
│   └── TestCase.php
│
├── .env
├── .env.example
├── .gitignore
├── artisan
├── composer.json
├── composer.lock
├── package.json
├── package-lock.json
├── phpunit.xml
├── vite.config.js
├── README.md
└── LICENSE
```

---

## 🔄 Common Workflows

### Employee Leave Request
1. Employee logs in
2. Goes to **My Leave** → **Request Leave**
3. Fills in dates and reason
4. Submits → HR gets notified
5. HR approves/rejects → Employee gets notified

### Task Assignment
1. Manager creates project
2. Adds tasks and assigns to employees
3. Employee gets notification
4. Employee: **Start** → **Submit** → Manager **Approves**
5. Project progress auto-updates

### Attendance Punch In/Out
1. Employee enters employee code at **Punch In/Out**
2. System records check-in time
3. If late (> 9:00 AM), marked as "Late"
4. Employee checks out at end of day

### Payroll Processing
1. HR goes to **Payroll** → **Generate New Payroll**
2. Sets period and payment date
3. System creates payslips for all active employees
4. HR clicks **Mark as Paid**
5. Employees get notified and can download payslips

---

## 🔌 API Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/v1/dashboard/stats` | Dashboard statistics |
| GET | `/api/v1/employees` | List all employees |
| GET | `/api/v1/employees/{id}` | Get employee details |
| GET | `/api/v1/products` | List all products |
| GET | `/api/v1/products/{id}` | Get product details |
| GET | `/api/v1/customers` | List all customers |
| GET | `/api/v1/customers/{id}` | Get customer details |
| GET | `/api/v1/invoices` | List all invoices |
| GET | `/api/v1/invoices/{id}` | Get invoice details |
| GET | `/api/v1/expenses` | List all expenses |
| GET | `/api/v1/projects` | List all projects |
| GET | `/api/v1/leads` | List all leads |

### API Authentication

```bash
# Include token in header
Authorization: Bearer YOUR_API_TOKEN
```

---

## 🌐 Deployment

### Deploy with ngrok (Instant Public URL)

```bash
# Terminal 1: Start Laravel
php artisan serve

# Terminal 2: Expose to internet
ngrok http 8000
```

### Deploy with Laravel Herd

```bash
cd ~/Herd/smarterp
herd share
```

### Production Deployment

```bash
# Optimize for production
php artisan optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Set in `.env`:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com
```

---

## 🎨 Color Palette

| Color | Hex Code | Usage |
|-------|----------|-------|
| Primary Blue | `#1e3a8a` | Main brand color |
| Accent Orange | `#f97316` | Highlights, CTAs |
| Success Green | `#10b981` | Positive actions |
| Warning Yellow | `#f59e0b` | Warnings |
| Danger Red | `#ef4444` | Errors, delete |

---

## 🛠️ Troubleshooting

### Common Issues

**Issue: "sessions table doesn't exist"**

```bash
# Change in .env:
SESSION_DRIVER=file
```

**Issue: "SQLite driver not found"**

```bash
# Use MySQL instead:
DB_CONNECTION=mysql
```

**Issue: Storage images not showing**

```bash
php artisan storage:link
```

**Issue: Permissions errors**

```bash
php artisan optimize:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

**Issue: "Class not found"**

```bash
composer dump-autoload
```

---

## 📦 Packages Used

| Package | Purpose |
|---------|---------|
| `laravel/breeze` | Authentication scaffolding |
| `spatie/laravel-permission` | Roles & permissions |
| `barryvdh/laravel-dompdf` | PDF generation |
| `maatwebsite/excel` | Excel exports |
| `laravel/sanctum` | API authentication |
| `intervention/image` | Image manipulation |
| `bootstrap 5` | CSS framework |
| `chart.js` | Charts & graphs |
| `sweetalert2` | Alert dialogs |
| `bootstrap-icons` | Icon library |

---

## 🔐 Security Features

- ✅ CSRF Protection
- ✅ XSS Protection
- ✅ SQL Injection Prevention (Eloquent ORM)
- ✅ Password Hashing (bcrypt)
- ✅ Two-Factor Authentication
- ✅ Rate Limiting
- ✅ Secure File Uploads
- ✅ Activity Logging
- ✅ Session Security
- ✅ Role-Based Access Control

---

## 📝 License

This project is licensed under the **MIT License**.

---

## 👥 Support

For support, email: **admin@smarterp.com**

---

## 🙏 Acknowledgments

- Laravel Framework
- Bootstrap Team
- Spatie Packages
- Chart.js
- SweetAlert2
- All contributors

---

**Made with ❤️ using Laravel**