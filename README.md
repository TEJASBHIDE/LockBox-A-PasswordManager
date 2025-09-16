# 🔐 LockBox: Secure Password Management System

LockBox is a lightweight and secure web-based application designed to help users manage their digital passwords in one centralized platform. It eliminates password fatigue, encourages strong credential practices, and ensures data privacy with modern security measures.  

---

## 🚀 Features

- **User Authentication** – Secure sign-up and login with session management.  
- **Password Management (CRUD)** – Create, view, update, and delete password entries easily.  
- **Password Generator** – Built-in tool for creating strong, random passwords.  
- **Search Functionality** – Quickly locate stored credentials with a smart search bar.  
- **Password Strength Meter** – Real-time feedback on entered passwords.  
- **Two-Factor Authentication (2FA)** – Adds an extra layer of login security.  
- **Admin Dashboard** – Monitor system metrics such as total registered users.  

---

## 🛠️ Tech Stack

**Frontend**
- HTML5 – Semantic structure  
- CSS3 – Responsive styling with media queries  
- JavaScript – Client-side interactivity & validation  

**Backend**
- PHP – Server-side logic & session handling  
- MySQL – Relational database for secure storage  
- phpMyAdmin – Database management interface  

---

## 📐 System Architecture

1. User submits a form (e.g., add a new password).  
2. JavaScript validates the input and sends it to a PHP script.  
3. PHP sanitizes data and communicates with MySQL.  
4. Database stores/retrieves the data.  
5. PHP returns a response (success/error).  
6. JavaScript updates the UI dynamically.  

---

## ⚡ Installation & Setup

### Prerequisites
- Apache or any web server  
- PHP **7.0+**  
- MySQL **5.7+**  

### Steps
1. Clone the repository:  
   ```bash
   git clone https://github.com/yourusername/LockBox.git
