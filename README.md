# YIC IT Support Portal

A web-based IT support ticket system for Yanbu Industrial College.

## Technologies Used
- HTML
- CSS
- JavaScript
- PHP
- MySQL
- PDO

## Setup Instructions
1. Install Laragon.
2. Place the project folder inside `C:\laragon\www`
3. Import `yic_support.sql` into MySQL using HeidiSQL.
4. Start Apache and MySQL.
5. Open the project in the browser:
   `http://localhost/IT-Support-Tickets-PROJECT-main/`



## Features
- User registration
- User login/logout
- Submit support tickets
- View personal tickets
- Admin dashboard
- Update ticket status
- Delete tickets
- Role-based access
- Responsive design
- Security implementation

## Security
- Database queries use PHP PDO to reduce SQL injection risk
- Role-based access control: only admins can update or delete tickets
- Session-based login with logout to end user sessions
- Demo accounts are for local testing only; change passwords before any real deployment
