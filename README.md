<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About This Laravel Project

# 🏫 School Management System

> A comprehensive School Management System built with **Laravel 13**, featuring User Authentication, Role-Based Access Control (RBAC), Student/Staff Management, Fee Collection, and Exam Management.

---

## 📚 Table of Contents

- [Tech Stack](#-tech-stack)
- [Role-Based Access Control](#-role-based-access-control-rbac)
- [Features & Modules](#-features--modules)
- [Installation & Setup](#-installation--setup)
- [API Documentation](#-api-documentation)
- [License](#-license)

---

## 🛠 Tech Stack

| Layer          | Technology                             |
| -------------- | -------------------------------------- |
| Framework      | Laravel 13                             |
| Authentication | Laravel Breeze                         |
| DataTables     | Laravel Yajra DataTables (Server-side) |
| PDF Generation | Barryvdh DomPDF                        |
| API Security   | Laravel Sanctum                        |
| Frontend       | Blade, Tailwind CSS / Bootstrap        |
| Admin Theme    | AdminLTE 3.1.0 (Free)                  |

---

## 🔐 Role-Based Access Control (RBAC)

This project implements a robust Role & Permission system to ensure secure access.

- **Roles** — Define system roles such as `Admin`, `Teacher`, `Student`, and `Accountant`.
- **Permissions** — Granular permissions (e.g., `view.student.admission`, `create.homework`) are assigned to roles.
- **Middleware Protection** — Routes and API endpoints are protected using Laravel's gate/permission middleware, ensuring only authorized users can perform specific actions.

---

## 📋 Features & Modules

### 1. Dashboard

- Real-time statistics of students, staff, and financial overviews.

### 2. User & Access Control

- **User Management** — Complete CRUD for system users.
- **Role Management** — Create and manage roles.
- **Permission Management** — Assign specific permissions to roles for fine-grained access control.

### 3. Academic Management

- **Student Admission** — Manage student enrollments and detailed listings.
- **Academic Setup:**
    - Class & Section Management
    - Subject Management
    - Academic Year Setup (e.g., 2026–2027)

### 4. Attendance & Staff Management

- **Attendance** — Daily tracking for both Students and Staff.
- **Staff Management** — Manage teacher and administrative staff records with listing and add features.

### 5. Fee Collection System

- **Fee Categories** — Define types like Monthly, 6-Month, and Yearly fees.
- **Assign Fee** — Assign specific fee structures to students.
- **Collect Payment** — Process fee payments from assigned students.
- **Payment History** — Track all past financial transactions.

### 6. Examination & Homework

- **Exam Management** — Create and list exams.
- **Marks Entry** — Student-wise marks entry for specific exams.
- **Results** — View and manage examination results.
- **Homework** — Class-wise homework management with file uploads and study material sharing.

### 7. Basic Reports

- **Attendance Report** — Student and Staff attendance logs.
- **Fee Report** — Detailed financial collection reports.
- **Student List** — Filterable student database.
- **Exam Result Report** — Consolidated academic performance reports.

---

## 🚀 Installation & Setup

Follow these steps to get the project running locally.

### 1. Clone the Repository

```bash
git clone https://github.com/your-username/your-repo-name.git
cd your-repo-name
```

### 2. Install Dependencies

```bash
composer update
npm install
```

### 3. Environment Configuration

Copy the example environment file and update your database credentials:

```bash
cp .env.example .env
```

Open `.env` and configure the following:

```env
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 4. Generate Application Key

```bash
php artisan key:generate
```

### 5. Database Migration & Seeding

This will create all tables and populate the roles/permissions required for the system:

```bash
php artisan migrate
php artisan db:seed
```

### 6. Build Assets & Start Server

```bash
npm run build
php artisan serve
```

### 7. Default Super Admin Login

After seeding, use the following credentials to log in as Super Admin:

| Field        | Value                  |
| ------------ | ---------------------- |
| **Email**    | `superadmin@gmail.com` |
| **Password** | `12345678`             |

> ⚠️ Please change the default password after your first login.

---

## 🔌 API Documentation

All API endpoints are located in `routes/api.php` and are secured using **Laravel Sanctum**.

| Method | Endpoint               | Description                   | Permission Required      |
| ------ | ---------------------- | ----------------------------- | ------------------------ |
| `POST` | `/api/login`           | User login & token generation | None                     |
| `POST` | `/api/logout`          | Revoke token                  | Auth Required            |
| `POST` | `/api/student-list`    | Get list of students          | `view.student.admission` |
| `POST` | `/api/add-homework`    | Create new homework           | `create.homework`        |
| `POST` | `/api/get-homework`    | Fetch class-wise homework     | `view.homework`          |
| `POST` | `/api/get-fee-details` | Get student-wise fee history  | `view.fee.history`       |

### Sample API Request Header

```http
Authorization: Bearer {your_sanctum_token}
Accept: application/json
```

---

### 📬 API Response Examples

---

#### `POST /api/login`

**Request Body:**

```json
{
    "login_input": "admin@example.com",
    "password": "password123"
}
```

> `login_input` accepts **email**, **phone number**, or **username**.

**Success Response `200`:**

```json
{
    "status": true,
    "message": "Login successfully",
    "access_token": "1|abc123tokenxyz...",
    "user": {
        "id": 1,
        "name": "Admin User",
        "email": "admin@example.com",
        "role": "Admin"
    }
}
```

**Failed Response `401`:**

```json
{
    "status": false,
    "message": "Invalid email or password"
}
```

---

#### `POST /api/logout`

**Success Response `200`:**

```json
{
    "message": "Logged out successfully"
}
```

---

#### `POST /api/student-list`

> Requires: `Authorization: Bearer {token}` | Permission: `view.student.admission`

**Success Response:**

```json
{
    "status": true,
    "message": "Student list fetched successfully",
    "data": [
        {
            "id": 5,
            "name": "John Doe",
            "email": "john@example.com",
            "phone": "9876543210",
            "class": "10th A",
            "status": 1
        }
    ]
}
```

---

#### `POST /api/add-homework`

> Requires: `Authorization: Bearer {token}` | Permission: `create.homework`

**Request Body (`multipart/form-data`):**

| Field                   | Type    | Required | Notes                           |
| ----------------------- | ------- | -------- | ------------------------------- |
| `class_id`              | integer | ✅       | Must exist in `student_classes` |
| `subject_id`            | integer | ✅       | Must exist in `subjects`        |
| `title`                 | string  | ✅       | Max 255 characters              |
| `description`           | string  | ✅       |                                 |
| `date`                  | date    | ✅       |                                 |
| `submission_date`       | date    | ✅       | Must be on or after `date`      |
| `file_upload`           | file    | ❌       | jpeg/png/jpg, max 5MB           |
| `study_material_upload` | file    | ❌       | pdf/doc/docx, max 5MB           |

**Success Response:**

```json
{
    "status": true,
    "message": "Homework added successfully",
    "data": {
        "id": 12,
        "class_id": 3,
        "subject_id": 7,
        "title": "Math Chapter 5 Exercise",
        "description": "Complete all questions from exercise 5.3",
        "date": "13-05-2026",
        "submission_date": "16-05-2026",
        "file_upload": "homeworks/filename.jpg",
        "study_material_upload": "study_materials/notes.pdf"
    }
}
```

**Validation Error Response:**

```json
{
    "status": false,
    "message": "The submission date field must be a date after or equal to date.",
    "data": {
        "submission_date": [
            "The submission date field must be a date after or equal to date."
        ]
    }
}
```

---

#### `POST /api/get-homework`

> Requires: `Authorization: Bearer {token}` | Permission: `view.homework`

**Request Body:**

```json
{
    "class_id": 3,
    "subject_id": 7
}
```

> `subject_id` is optional — omit to fetch homework for all subjects in the class.

**Success Response:**

```json
{
    "status": true,
    "message": "Homework list fetched successfully",
    "data": [
        {
            "id": 12,
            "class_id": 3,
            "class_name": "10th A",
            "subject_id": 7,
            "subject_name": "Mathematics",
            "title": "Math Chapter 5 Exercise",
            "description": "Complete all questions from exercise 5.3",
            "date": "13-05-2026",
            "submission_date": "16-05-2026",
            "file_upload": "https://yourapp.com/storage/homeworks/filename.jpg",
            "study_material_upload": "https://yourapp.com/storage/study_materials/notes.pdf"
        }
    ]
}
```

**No Data Response:**

```json
{
    "status": false,
    "message": "No homework found",
    "data": []
}
```

---

#### `POST /api/get-fee-details`

> Requires: `Authorization: Bearer {token}` | Permission: `view.fee.history`

**Request Body:**

```json
{
    "student_id": 5
}
```

**Success Response:**

```json
{
    "status": true,
    "message": "Fee details fetched successfully",
    "data": [
        {
            "id": 8,
            "student_id": 5,
            "student_name": "John Doe",
            "fee_category": "Monthly Fee",
            "total_fee": "1,500.00",
            "amount_paid": "1,000.00",
            "remaining_amount": "500.00",
            "due_date": "31-05-2026",
            "status": "Pending",
            "payment_details": [
                {
                    "id": 21,
                    "receipt_number": "REC-2026-001",
                    "amount_paid": "1,000.00",
                    "payment_date": "10-05-2026",
                    "payment_method": "Cash"
                }
            ]
        }
    ]
}
```

---

## 📄 License

The Laravel framework is open-source software licensed under the [MIT license](https://opensource.org/licenses/MIT).
