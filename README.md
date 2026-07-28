# 🛒 POS System

A modern Point of Sale (POS) system built with **PHP**, **JavaScript**, **Bootstrap**, and **SQLite**, designed for restaurants, cafés, supermarkets, and small businesses.

---

## 📖 Overview

This project is a complete offline POS solution that allows businesses to manage sales, products, customers, invoices, debts, and daily operations through a simple and responsive interface.

The system was developed with a focus on speed, usability, and reliability.

---

# ✨ Features

## 🔐 Authentication
- Secure login system
- User roles (Admin & Cashier)
- Password hashing
- Session management

---

## 🛍 Product Management

- Add/Edit/Delete products
- Product categories
- Barcode support
- Product search
- Stock quantity tracking
- Minimum stock warning
- Product images
- Multi-currency prices

---

## 💰 Sales System

- Fast checkout
- Barcode scanner support
- Shopping cart
- Quantity editing
- Discount support
- Tax support (optional)
- Multiple payment methods
- Print invoice
- Cancel invoice
- Search previous invoices

---

## 🍽 Restaurant Mode

- Table management
- Open tables
- Save table orders
- Resume table later
- Clear tables
- Merge table orders

---

## 👥 Customer Management

- Customer registration
- Customer search
- Debt management
- Unpaid invoices
- Customer purchase history

---

## 💵 Multi Currency

Supports:

- USD
- Local Currency

Features:

- Live exchange rate
- Automatic currency conversion
- Display totals in both currencies

---

## 🧾 Invoice Printing

- Thermal printer support
- Clean invoice layout
- Company logo
- Store information
- Invoice number
- Payment details

---

## 📊 Reports

- Daily sales
- Previous invoices
- Sales history
- Revenue summary
- Product sales statistics

---

## ⚙ Settings

- Company information
- Store logo
- Phone number
- Address
- Currency settings
- Exchange rate
- Printer configuration

---

## 🔒 Security

- Password hashing
- SQL Injection protection (PDO Prepared Statements)
- Session protection
- Role-based access
- Local machine licensing
- MAC Address verification

---

## 🌐 Offline First

This system works completely offline.

No internet connection is required after installation.

Perfect for:

- Restaurants
- Coffee Shops
- Grocery Stores
- Supermarkets
- Retail Shops

---

# 🛠 Technologies Used

| Technology | Purpose |
|------------|---------|
| PHP | Backend |
| JavaScript | Frontend Logic |
| HTML5 | User Interface |
| CSS3 | Styling |
| Bootstrap | Responsive Design |
| SQLite | Database |
| PDO | Database Access |

---

# 📁 Project Structure

```
POS/
│
├── assets/
│   ├── css/
│   ├── js/
│   ├── images/
│
├── includes/
│
├── database/
│
├── pages/
│
├── print/
│
├── uploads/
│
├── index.php
└── README.md
```

---

# 🚀 Installation

## Clone Repository

```bash
git clone https://github.com/MohamadAl-Zohbi/POS.git
```

---

## Move Project

Place the project inside your web server.

Example:

```
htdocs/
```

or

```
www/
```

---

## Configure Database

The project uses **SQLite**.

Create or place the SQLite database inside:

```
database/
```

Update the database path if necessary.

---

## Start Server

Using XAMPP

```
Apache
```

Then open

```
http://localhost/sys
```

---

# 🔧 Requirements

- PHP 8+
- Apache / Nginx
- SQLite
- PDO SQLite Extension

---

# 📸 Screenshots

You can add screenshots here.

Example:

```
screenshots/

login.png

dashboard.png

sales.png

products.png

reports.png
```

---

# 🎯 Future Improvements

- Online synchronization
- Cloud backup
- Mobile application
- QR Code payments
- Employee attendance
- Inventory analytics
- Email receipts
- Customer loyalty system
- Multi-store support
- Dashboard charts

---

# 🤝 Contributing

Contributions are welcome.

Feel free to fork the project and submit pull requests.

---

# 📄 License

This project is licensed under the MIT License.

---

# 👨‍💻 Author

**Mohamad Al-Zohbi**

Software Developer

GitHub:
https://github.com/MohamadAl-Zohbi/

LinkedIn:
https://www.linkedin.com/in/mohamad-al-zohbi/

Email:
mohamadalzohbi01@gmail.com