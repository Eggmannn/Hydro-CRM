# 🚀 Hydro CRM

Hydro CRM is a multi-tenant Customer Relationship Management (CRM) system built with Laravel.  
It supports role-based access control, ticket management, company-level isolation, and secure authorization workflows.

---

## ✨ Features

### 🔐 Role-Based Access Control
- **CRD Admin (Super Admin)**
- **Customer Admin**
- **Agent**
- **Client (External User)**

Each role has strict access boundaries and company isolation.

---

### 🏢 Multi-Company Architecture
- Each company has isolated users, tickets, and contacts
- Prevents cross-company access (IDOR protection implemented)
- Company authorization must be explicitly assumed by CRD Admin

---

### 🎫 Ticket Management
- Create, update, and filter tickets
- Assign tickets to agents
- Track ticket status (Open, Pending, Closed)
- Priority levels (Low, Normal, High)
- Comment system for conversations

---

### 👥 User Management
- Create company users
- Assign roles
- Prevent unauthorized role modification
- Secure URL manipulation protection

---

### 🔐 Security Enhancements
- IDOR protection
- Company ownership validation
- Role validation enforcement
- Assume authorization system with expiration
- Cross-company access prevention

---

### 🌙 Dark Mode Support
- Fully styled UI with dark mode compatibility
- Optimized readability across roles

---

## 🛠 Tech Stack

- **Backend:** Laravel
- **Frontend:** Blade + Tailwind CSS
- **Database:** MySQL
- **Authentication:** Laravel Auth Guards
- **Version Control:** Git

---

## 📦 Installation

### 1️⃣ Clone the repository

```bash
git clone https://github.com/Eggmannn/Hydro-CRM.git
cd Hydro-CRM
