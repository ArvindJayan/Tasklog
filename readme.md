# TaskLog

TaskLog is a task management application built using PHP and CodeIgniter 3. It provides role-based task management, task assignment, task tracking, filtering, and audit logging.

## Features

### Authentication & Authorization
- User authentication
- Role-based access control
- Authorization checks for protected functionality
- Role-specific access for Admin and RA users

### Task Management
- Create tasks
- Update tasks
- Delete tasks
- Assign tasks to employees
- Manage task status
- Set task due dates
- View assigned tasks
- Filter tasks

### Admin Features
- Admin dashboard
- Task management
- Employee and RA-related task assignment
- Audit log viewing

### RA Features
- View assigned tasks
- Assign tasks to employees
- Manage task assignments
- Role-specific task access

### Audit Logging
- Records important application actions
- Provides an admin-facing audit log
- Helps track changes made within the application

## Tech Stack

- **Backend:** PHP
- **Framework:** CodeIgniter 3
- **Database:** MySQL
- **Frontend:** HTML, CSS, JavaScript
- **Architecture:** MVC

## Project Structure

```text
TaskLog/
├── application/
│   ├── controllers/
│   ├── models/
│   ├── views/
│   ├── config/
│   └── ...
├── system/
├── assets/
├── index.php
└── README.md
