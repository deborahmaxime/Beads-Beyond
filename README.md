
# Beads & Beyond   
**Where Beauty Meets Craftsmanship**

Beads & Beyond is a full-stack e-commerce web application for a handcrafted jewelry brand.  
The platform allows customers to browse products, customize selections, place orders, and track their purchases, while providing administrators with tools to manage products, users, and orders.

---

##  Features

###  Customer Features
- User authentication (Sign up, Login, Logout)
- Browse products by category
- View featured and latest products
- Product detail pages
- Shopping cart management
- Secure checkout flow
- Order history & order details
- Dark mode toggle
- Responsive, elegant gold-themed UI

###  Admin Features
- Admin dashboard
- Add, edit, and delete products
- View and manage all orders
- Manage users (view & delete)
- Order status tracking
- Secure admin-only access

---

##  Project Structure

 webapp/
│
├── admin/ # Admin dashboard & management pages
      |-- admin.php
      |-- admin_product.php
      |-- edit_product.php
      |--add_product.php
      |--manage_users.php
      |-- view orders.php
      |-- index.php
├── css/ # Global stylesheets
      |-- style.php
      |-- index.php
├── js/ # JavaScript (slider, dark mode, etc.)
      |-- darkmode.js
      |-- index.php
├── php/ # Backend logic (DB, auth, sessions)
      |-- db.php
      |-- auth_session.php
      |-- login.php
      |-- signup.php
      |-- cart_actions.php
      |-- index.php
      |-- logout.php
├── image/ # Images and assets
│ └── products/ # Product images
│
├── index.php # Homepage
├── shop.php # Product listing
├── product.php # Product details
├── cart.php # Shopping cart
├── checkout.php # Checkout process
├── orders.php # User orders
├── thank_you.php # Order confirmation
├── about.php # About page
├── db.sql # Database schema
└── README.md


## Database

- **Database Engine:** MySQL (InnoDB)
- **Key Tables:**
  - `users`
  - `products`
  - `orders`
  - `order_items`
  - `cart_items`

Foreign keys are enforced, with cascading deletes where appropriate.

## Installation & Setup

### Clone the Repository
```bash
git clone https://github.com/deborahmaxime/Beads-Beyond.git

### Move to Web Root
cd Beads-Beyond

### Place the project inside:

htdocs/ (XAMPP) or

public_html/ (Live server)

### Import Database

Open phpMyAdmin

Create a database (e.g. beads_and_beyond)

Import db.sql

### Configure Database Connection

Edit:

php/db.php


### Update credentials:

$host = "localhost";
$dbname = "beads_and_beyond";
$user = "root";
$pass = "";

### Run the App

Visit in browser:

http://localhost/webapp

## Authentication & Security

Session-based authentication

Password hashing

Admin-only protected routes

Prepared SQL statements (PDO)

## Design

Custom luxury gold theme

Google Fonts (Poppins, Playfair Display)

Clean UI for both users and admins

Dark mode support

## Technologies Used

Frontend: HTML5, CSS3, JavaScript

Backend: PHP (PDO)

Database: MySQL

Version Control: Git & GitHub

##Author

Deborah Cholabomi AGOSSOU Maxime
Computer Science Student
Beads & Beyond – Founder & Developer

## License

This project is for academic and personal use.
All rights reserved © Beads & Beyond.
