# CampusSwap - Peer-to-Peer Campus Thrift & Item Exchange

**Course:** ICT 2209 – Web Technologies  
**Assignment:** Mini Project – Individual Web Application Submission  
**Institution:** Rajarata University of Sri Lanka (RUSL), Faculty of Technology, Department of ICT  

---

## 1. Project Overview

**CampusSwap** is an interactive, full-stack student-centric web platform engineered using PHP, MySQL, Bootstrap 5, and Vanilla JavaScript. The platform provides a circular campus economy where undergraduates can list, exchange, or purchase second-hand textbooks, engineering stationery, hostel appliances, and lab hardware at affordable rates.

### Core Objectives:
- **Sustainability:** Encourages reuse of academic resources to eliminate campus waste.
- **Affordability:** Connects students directly to avoid commercial retail markups and middleman fees.
- **Security:** Verified student network enabling safe on-campus physical handovers.

---

## 2. Key Features

### Frontend (HTML5, CSS3, Bootstrap 5, JavaScript)
- **Responsive Layout:** Mobile-first, fully responsive design powered by Bootstrap 5 and custom media queries.
- **Custom Eco-Friendly Theme:** Tailored Emerald Green (`#059669`) and Deep Teal styling using CSS custom properties (`:root`).
- **Interactive Marketplace:**
  - Real-time client-side keyword search (filters without page reloads).
  - Category pill filter tabs (Books, Electronics, Stationery, Dorm Gear, Appliances, Accessories, Furniture).
  - Dynamic price sorting (Low-to-High, High-to-Low, A-Z).
  - Interactive item details modal dialog.
- **Dynamic Form Validation:** Real-time client-side Bootstrap validation feedback and live character counters.
- **Password Visibility Toggle:** Interactive show/hide toggle for password credentials.

### Backend (PHP 8 & MySQL PDO)
- **Prepared Statements (PDO):** Comprehensive protection against SQL Injection vulnerabilities.
- **User Authentication:**
  - Secure registration (`auth/register.php`) with password hashing via `password_hash()`.
  - Secure credential verification (`auth/login.php`) using `password_verify()`.
  - Robust session management (`$_SESSION`) and clean session termination (`auth/logout.php`).
- **User Dashboard (`dashboard.php`):**
  - Displays user profile and active listing metrics.
  - Form to publish new items to the marketplace with condition and category tagging.
  - Option to delete listings.
- **Contact Support System (`contact.php`):**
  - Processes student inquiries and stores them securely in the `messages` table.
- **Graceful Offline Fallback:** If the MySQL server is offline during initial preview, the platform falls back to demo data without breaking execution.

---

## 3. Project Directory Structure

```text
campusswap/
│── css/
│   └── style.css            # Custom CSS styles, color palette, and component design
│── js/
│   └── main.js              # Live search, filters, modal handlers, and form validation
│── images/                  # Static image assets
│── includes/
│   ├── db.php               # PDO database connection configuration
│   ├── functions.php        # Input sanitization, auth guards, and helper utilities
│   ├── header.php           # Global responsive navigation bar and head metadata
│   └── footer.php           # Footer links, project copyright, and script includes
│── auth/
│   ├── register.php         # Student registration logic and secure password hashing
│   ├── login.php            # Student sign-in verification and session startup
│   └── logout.php           # Session termination and redirect
│── contact.php              # Inquiry contact form with FAQ accordion
│── index.php                # Homepage with hero section, statistics, and category links
│── marketplace.php          # Full catalog with dynamic search, filter, and modal preview
│── dashboard.php            # User dashboard for posting and managing listings
│── README.md                # Comprehensive documentation and installation guide
│── database.sql             # Full MySQL database dump with sample seed data
└── campusswap_db.sql        # Database export file for phpMyAdmin import
```

---

## 4. Local Installation & Setup Guide (XAMPP / WAMP)

### Prerequisites:
- **XAMPP** or **WAMP** with Apache and MySQL services installed.
- PHP 8.0 or newer.
- Modern web browser (Chrome, Firefox, Edge, Safari).

### Step 1: Copy Project Folder to Web Server Root
1. Copy the `campusswap` directory into your local web root:
   - For **XAMPP**: `C:\xampp\htdocs\campusswap\`
   - For **WAMP**: `C:\wamp64\www\campusswap\`

### Step 2: Import the Database
1. Launch **XAMPP Control Panel** and start both **Apache** and **MySQL**.
2. Open your web browser and navigate to **phpMyAdmin**:
   ```
   http://localhost/phpmyadmin/
   ```
3. Click on the **Databases** tab and create a new database named `campusswap_db` with `utf8mb4_unicode_ci` collation.
4. Select the newly created `campusswap_db` database, go to the **Import** tab.
5. Click **Choose File**, select `database.sql` (or `campusswap_db.sql`) from the project root, and click **Import** (or **Go**).

### Step 3: Run the Application
1. In your browser, open the application URL:
   ```
   http://localhost/campusswap/
   ```
2. You can browse listings freely on the homepage and marketplace.

---

## 5. Demo Test Credentials

To test user-authenticated features (posting items, student dashboard, login):

| Role | Email Address | Password |
|---|---|---|
| **Demo Student** | `student@rusl.ac.lk` | `Student123!` |

*(Alternatively, you can register a new student account at `/auth/register.php`)*

---

## 6. Author Information

- **Course:** ICT 2209 - Web Technologies
- **Department:** Department of ICT
- **Faculty:** Faculty of Technology
- **Institution:** Rajarata University of Sri Lanka
