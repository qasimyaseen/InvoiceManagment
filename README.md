# Invoice Management System

A clean and efficient Invoice Management System built with PHP and MySQL. This application allows businesses to manage customers, generate invoices, and track their billing history with a user-friendly interface.

## Features

-   **Dashboard**: Overview of recent activity, total invoices, and pending payments.
-   **Customer Management**:
    -   Add, Edit, and Delete customer details.
    -   View customer lists.
-   **Invoice Management**:
    -   Create dynamic invoices with multiple items.
    -   Automatic calculation of totals.
    -   View, Print, and Delete invoices.
-   **Authentication**: Secure login system to protect data.
-   **Responsive Design**: Built with Bootstrap 5 for compatibility across devices.

## Technology Stack

-   **Backend**: PHP (Vanilla)
-   **Database**: MySQL
-   **Frontend**: HTML5, CSS3, Bootstrap 5, JavaScript
-   **Server**: Apache (via XAMPP/WAMP/MAMP)

## Installation & Setup

1.  **Clone the Repository**
    ```bash
    git clone https://github.com/qasimyaseen/InvoiceManagment.git
    cd InvoiceManagment
    ```

2.  **Database Configuration**
    -   Create a new MySQL database named `invoices_db` (or your preferred name).
    -   Import the `database/schema.sql` file into your database to set up tables and default users.

3.  **Connect Application to Database**
    -   Open `config/database.php`.
    -   Update the database credentials if necessary:
        ```php
        $host = 'localhost';
        $db   = 'invoices_db';
        $user = 'root'; // Update if different
        $pass = '';     // Update if different
        ```

4.  **Run the Application**
    -   Place the project folder in your server's root directory (e.g., `htdocs` for XAMPP).
    -   Open your browser and navigate to:
        `http://localhost/InvoiceManagment`

## Default Login

After importing the schema, you can access the system with the default admin account:
*   **Username**: `admin`
*   **Password**: `admin123` *(Please change this after first login)*

## Project Structure

*   `assets/` - CSS, JS, and Images.
*   `config/` - Database connection settings.
*   `customers/` - Customer CRUD operations.
*   `database/` - SQL schema file.
*   `includes/` - Header, footer, and auth helper files.
*   `invoices/` - Invoice generation and management.
*   `users/` - User authentication logic.
