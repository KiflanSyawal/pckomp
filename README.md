# PCKOMP - Database Migration & Web Application

A lightweight PC Components & Computer Store management web application built with plain PHP, completely migrated and modernized to run on **SQLite 3** via **PHP PDO**.

---

## 📌 Background & Migration Summary

Originally, this project depended on a MySQL database managed through XAMPP (`mysqli_connect('localhost', 'root', '', 'pckomp')`). Because the original MySQL database became unavailable and inaccessible, this migration replaces the database layer with a **self-contained, zero-infrastructure SQLite 3 database**.

### Why SQLite Was Chosen
1. **Zero External Infrastructure Required:** SQLite does not rely on a running MySQL/MariaDB server daemon, socket, or port configuration (e.g., port 3306 conflicts).
2. **Built-in PHP PDO Support:** Supported natively out of the box through PHP's `pdo_sqlite` extension (already installed and active in standard PHP / XAMPP).
3. **Portability:** The database is preserved in a single file (`database/pckomp.sqlite`), making the entire project instantly runnable on any machine or hosting environment without database import hassles.
4. **Reliability & Relational Integrity:** Full ACID compliance, foreign key constraints (`PRAGMA foreign_keys = ON`), and indexing.
5. **Modernized & Secure:** All queries have been upgraded to **PDO Prepared Statements**, eliminating SQL injection risks while fully preserving the plain PHP architecture and original frontend UI.
6. **Automatic Bootstrapping:** If the database file is not yet created, `db.php` automatically provisions the tables and seeds them on first run.

---

## 🏗️ Reconstructed Database Schema

The database consists of three relational tables: `admin`, `category`, and `product`.

### 1. `admin` (Administrator Accounts)
| Column | Type | Constraints | Description |
|---|---|---|---|
| `admin_id` | `INTEGER` | `PRIMARY KEY AUTOINCREMENT` | Unique identifier for admin |
| `admin_name` | `TEXT` | `NOT NULL` | Admin full name |
| `username` | `TEXT` | `NOT NULL UNIQUE` | Login username |
| `password` | `TEXT` | `NOT NULL` | MD5 hashed password |
| `admin_telp` | `TEXT` | `NOT NULL` | Phone number |
| `admin_email` | `TEXT` | `NOT NULL` | Email address |
| `admin_address` | `TEXT` | `NOT NULL` | Physical address |

### 2. `category` (Product Categories)
| Column | Type | Constraints | Description |
|---|---|---|---|
| `kategori_id` | `INTEGER` | `PRIMARY KEY AUTOINCREMENT` | Unique identifier for category |
| `kategori_nama` | `TEXT` | `NOT NULL` | Name of category (e.g., Processor, VGA) |

### 3. `product` (Computer Components & Products)
| Column | Type | Constraints | Description |
|---|---|---|---|
| `product_id` | `INTEGER` | `PRIMARY KEY AUTOINCREMENT` | Unique identifier for product |
| `kategori_id` | `INTEGER` | `NOT NULL`, `FOREIGN KEY` | Category reference (`ON DELETE CASCADE`) |
| `product_name` | `TEXT` | `NOT NULL` | Name of component / product |
| `product_price` | `INTEGER` | `NOT NULL` | Price in IDR |
| `product_description` | `TEXT` | Nullable | Detailed product specifications |
| `product_image` | `TEXT` | Nullable | Image filename |
| `product_status` | `INTEGER` | `NOT NULL DEFAULT 1` | 1 = Active, 0 = Inactive |
| `data_created` | `DATETIME` | `DEFAULT CURRENT_TIMESTAMP` | Creation timestamp |

---

## 🔑 Default Credentials

- **Username:** `admin`
- **Password:** `admin`

*(You can update your username, password, email, and contact info at any time from the **Profile** page).*

---

## 📁 Project Structure

```
c:\xampp\htdocs\pckomp\
├── css\
│   └── style.css           # Styling for login, dashboard, tables, and forms
├── database\
│   ├── pckomp.sqlite       # Active SQLite database file
│   ├── schema.sql          # SQLite table definitions and indexes
│   ├── seed.sql            # Realistic seed data for admin, categories, and products
│   └── schema_mysql.sql    # Reference MySQL DDL for MySQL environments
├── dashboard.php           # Main admin dashboard with session guard
├── data_kategori.php       # Categories listing & management
├── data_produk.php         # Products listing & management
├── db.php                  # Centralized PDO SQLite connection & auto-bootstrapper
├── edit-kategori.php       # Category editing form
├── edit-produk.php         # Product editing form
├── index.php               # Root entry point (auto-redirect to dashboard/login)
├── keluar.php              # Logout script (destroys session)
├── login.php               # Admin authentication page
├── profile.php             # Profile management & password changer
├── proses-hapus.php        # Deletion processor for categories and products
├── setup_database.php      # Standalone CLI / Web database migration & setup script
├── tambah-kategori.php     # Category creation form
├── tambah-produk.php       # Product creation form
├── test_migration.php      # Automated unit test suite (100% pass)
└── test_web_flow.php       # Automated end-to-end web flow simulation
```

---

## 🚀 Installation & Running the Application

### Prerequisites
- PHP 8.0 or higher.
- `pdo_sqlite` PHP extension enabled (enabled by default in standard PHP / XAMPP).

### Option 1: Using PHP Built-in Server (Recommended for Fast Local Development)
Navigate to the project root directory and start PHP's built-in server:
```powershell
cd c:\xampp\htdocs\pckomp
php -S localhost:8000
```
Open your web browser and visit:
👉 **`http://localhost:8000`**

### Option 2: Using XAMPP Apache
1. Place this project inside `C:\xampp\htdocs\pckomp`.
2. Start **Apache** from the XAMPP Control Panel (MySQL is **not** required).
3. Open your web browser and visit:
👉 **`http://localhost/pckomp`**

---

## ⚙️ Manual Database Reinitialization (Optional)

The application automatically creates and seeds `database/pckomp.sqlite` on first load. If you ever want to reset or re-seed the database manually from the command line, run:
```powershell
php setup_database.php
```
Or access `http://localhost:8000/setup_database.php` in your browser.

---

## 🧪 Testing & Verification

Comprehensive test suites are included to verify all database operations, relationships, security, and CRUD functionality.

### 1. Run Core Database & Unit Test Suite:
```powershell
php test_migration.php
```
Tests:
- [x] Active PDO connection and SQLite driver validation.
- [x] Schema integrity: existence of `admin`, `category`, and `product` tables.
- [x] Authentication: valid login verification and invalid password rejection.
- [x] Category CRUD: Create, Read, Update, and Delete operations.
- [x] Product CRUD: Create, Read with category join, Update, and Delete operations.
- [x] Admin profile & password update verification.

### 2. Run Web Flow Simulation:
```powershell
php test_web_flow.php
```
Tests full lifecycle of admin login, category creation/update, product creation/update, and cascading deletions.

### 3. Syntax Verification:
```powershell
Get-ChildItem -Filter "*.php" | ForEach-Object { php -l $_.FullName }
```
All PHP files pass syntax checks without warnings.

