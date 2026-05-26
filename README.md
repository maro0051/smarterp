# SmartERP - Enterprise Resource Planning System

![SmartERP](https://img.shields.io/badge/SmartERP-v1.0-blue)
![Laravel](https://img.shields.io/badge/Laravel-13.9-red)
![PHP](https://img.shields.io/badge/PHP-8.3-purple)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-blue)
![License](https://img.shields.io/badge/License-MIT-green)

SmartERP is a comprehensive, modern Enterprise Resource Planning system built with Laravel. It manages all aspects of your business from HR to Accounting in one unified platform.

## 📸 Screenshots

### Dashboard
- **Super Admin** - Full analytics, charts, revenue tracking
- **HR Manager** - Employee stats, attendance, leave management
- **Accountant** - Financial dashboard, expense approvals
- **Employee** - Personal attendance, payslips, tasks

### Modules
- Employee Management with ID Cards
- Attendance with Punch In/Out system
- Leave Management with approval workflow
- Project Management with Kanban Board
- Inventory & Stock Management
- Sales & Invoice Management
- Accounting & Expense Tracking
- CRM & Lead Management
- Payroll & Payslip Generation

## 🚀 Features

### Core Modules
| Module | Description |
|--------|-------------|
| **Dashboard** | Role-based dashboards with charts and analytics |
| **Employee Management** | CRUD operations, departments, positions |
| **Attendance** | Punch in/out system, attendance history |
| **Leave Management** | Request, approve, reject workflow |
| **HR Module** | Recruitment, interviews, performance reviews |
| **ID Cards** | Generate and print employee ID cards |
| **Inventory** | Products, categories, suppliers, warehouses |
| **Stock Movement** | Track stock in/out across warehouses |
| **Sales** | Customers, invoices, payments |
| **Accounting** | Expenses with approval workflow |
| **CRM** | Lead management and conversion |
| **Projects** | Kanban board, task assignment |
| **Payroll** | Generate and process payroll |
| **Payslips** | Employee payslip viewing and PDF download |
| **Reports** | Sales, inventory, financial reports |
| **Settings** | Company configuration |
| **User Management** | Role-based access control |

### Technical Features
- ✅ **Role-Based Access Control** (7 roles)
- ✅ **Permission System** (50+ permissions)
- ✅ **Responsive Design** (Bootstrap 5)
- ✅ **Charts & Analytics** (Chart.js)
- ✅ **PDF Export** (DomPDF)
- ✅ **SweetAlert Notifications**
- ✅ **Activity Logging**
- ✅ **REST API Ready**
- ✅ **Two-Factor Authentication**
- ✅ **Email Notifications**
- ✅ **CSV Export**
- ✅ **Mobile Responsive**

## 👥 User Roles

| Role | Description | Access Level |
|------|-------------|--------------|
| **Super Admin** | Full system access | Everything |
| **HR Manager** | Employee management | HR modules, reports |
| **Accountant** | Financial management | Expenses, invoices, reports |
| **Inventory Manager** | Stock management | Products, suppliers, warehouses |
| **Sales Manager** | Sales and CRM | Customers, leads, invoices |
| **Employee** | Self-service | Own attendance, leave, tasks, payslips |

## 🔐 Default Login Credentials

| Role | Email | Password |
|------|-------|----------|
| Super Admin | admin@smarterp.com | password123 |
| HR Manager | sarah.johnson@smarterp.com | password123 |
| Accountant | michael.chen@smarterp.com | password123 |
| Inventory Manager | emily.rodriguez@smarterp.com | password123 |
| Sales Manager | david.thompson@smarterp.com | password123 |
| Employee | lisa.anderson@smarterp.com | password123 |

## 📋 Requirements

- PHP 8.2+
- MySQL 5.7+ or SQLite
- Composer
- Node.js & NPM
- Laravel 13.x

## 🛠️ Installation

### Step 1: Clone the Repository
```bash
git clone https://github.com/yourusername/smarterp.git
cd smarterp
Step 2: Install Dependencies
bash
composer install
npm install
Step 3: Configure Environment
bash
cp .env.example .env
php artisan key:generate
Step 4: Configure Database
Edit .env file:

env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=smarterp
DB_USERNAME=root
DB_PASSWORD=
Step 5: Run Migrations & Seed
bash
php artisan migrate:fresh --seed
Step 6: Build Frontend Assets
bash
npm run build
Step 7: Create Storage Link
bash
php artisan storage:link
Step 8: Start Server
bash
php artisan serve
Visit: http://localhost:8000

📁 Project Structure
text
smarterp/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/           # Authentication controllers
│   │   │   ├── HR/             # HR module controllers
│   │   │   └── Api/            # API controllers
│   │   ├── Middleware/
│   │   └── Requests/
│   ├── Models/                 # Eloquent models
│   └── Mail/                   # Email mailable classes
├── database/
│   ├── migrations/             # Database migrations
│   └── seeders/                # Database seeders
├── resources/
│   └── views/
│       ├── layouts/            # Main layout
│       ├── auth/               # Authentication pages
│       ├── employees/          # Employee management
│       ├── hr/                 # HR module views
│       ├── projects/           # Project management
│       ├── tasks/              # Task views
│       └── ...                 # Other module views
├── routes/
│   ├── web.php                 # Web routes
│   ├── api.php                 # API routes
│   └── auth.php                # Auth routes
└── public/                     # Public assets
🔄 Workflow Examples
Employee Leave Request Flow
Employee creates leave request

HR Manager receives notification

HR Manager approves or rejects

Employee receives notification of decision

Task Assignment Flow
Admin/HR creates project and tasks

Assigns tasks to employees

Employee receives notification

Employee starts working (Status: In Progress)

Employee submits for review (Status: Review)

Manager approves (Status: Completed)

Punch In/Out Flow
Employee enters employee code

System records check-in time

If after 9:00 AM, marked as "Late"

Employee enters code again to check out

Attendance record complete

🎨 Color Theme
Primary Blue: #1e3a8a

Accent Orange: #f97316

Success Green: #10b981

Warning Yellow: #f59e0b

Danger Red: #ef4444

🔌 API Endpoints
Method	Endpoint	Description
GET	/api/v1/employees	List employees
GET	/api/v1/products	List products
GET	/api/v1/customers	List customers
GET	/api/v1/invoices	List invoices
GET	/api/v1/dashboard/stats	Dashboard statistics
📦 Packages Used
Spatie Permission - Role & permission management

Laravel Breeze - Authentication scaffold

Chart.js - Charts and graphs

SweetAlert2 - Alert dialogs

Bootstrap 5 - UI framework

Bootstrap Icons - Icon library

DomPDF - PDF generation

Laravel Sanctum - API authentication

🧪 Testing
bash
# Run all tests
php artisan test

# Run specific test
php artisan test --filter=EmployeeTest
📝 License
This project is licensed under the MIT License.

👨‍💻 Author
SmartERP Development Team

🙏 Support
For support, email: admin@smarterp.com

Made with ❤️ using Laravel

text

```bash
# Save the README
echo "README.md created successfully!"
The README includes:

✅ Project overview and badges

✅ Feature list with all modules

✅ User roles and permissions

✅ Default login credentials

✅ Installation steps

✅ Project structure

✅ Workflow examples

✅ API endpoints

✅ Color theme reference

✅ Packages used

✅ License information