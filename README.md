# Smart Invoice Pro

Smart Invoice Pro is a full-stack business management application built with Laravel. The project is designed to provide businesses with a centralised system for managing clients and invoicing workflows.

The application is being developed as a production-oriented portfolio project to demonstrate practical full-stack software development, database design, authentication, business logic and responsive user interface development.

## 🚀 Project Status

**In active development**

Current development includes:

* User authentication
* Dashboard
* Client management
* Company-based application structure
* Database-driven workflows
* Responsive user interface

Additional invoicing functionality is being developed as part of the project roadmap.

---

## 🛠️ Technology Stack

### Backend

* PHP
* Laravel

### Frontend

* Livewire
* Tailwind CSS
* JavaScript
* Vite

### Database

* SQLite

### Development Tools

* Git
* GitHub
* Visual Studio Code
* Composer
* NPM

---

## ✨ Current Features

### Authentication

The application includes user authentication and protected application areas.

### Dashboard

A responsive application dashboard provides the central interface for accessing system functionality.

### Client Management

Users can manage client records through the application.

Current functionality includes:

* Create clients
* View clients
* Edit clients
* Delete clients
* View client details

### Company Structure

The application is being developed with company-based data relationships to support business users and their associated records.

---

## 🏗️ Application Architecture

The application follows Laravel's MVC architecture and uses Laravel's database and authentication functionality to manage application data and user access.

The project is structured around modular business functionality so additional modules can be added as development continues.

---

## 🗄️ Database

The current development environment uses SQLite.

The database contains application entities including users, companies and clients, with additional invoice-related entities planned as development progresses.

---

## 💻 Local Installation

### Requirements

Before installing the application, ensure you have:

* PHP
* Composer
* Node.js and NPM
* SQLite
* Git

### Clone the repository

```bash
git clone https://github.com/YOUR-USERNAME/smart-invoice-pro.git
```

Navigate into the project:

```bash
cd smart-invoice-pro
```

Install PHP dependencies:

```bash
composer install
```

Install frontend dependencies:

```bash
npm install
```

Create the environment file:

```bash
cp .env.example .env
```

Generate the Laravel application key:

```bash
php artisan key:generate
```

Run migrations:

```bash
php artisan migrate
```

Build frontend assets:

```bash
npm run build
```

Start the Laravel development server:

```bash
php artisan serve
```

---

## 🔐 Environment Configuration

Sensitive environment configuration is stored in the `.env` file.

The `.env` file is intentionally excluded from version control.

Developers should create their own `.env` file using:

```text
.env.example
```

---

## 🧪 Development Roadmap

Planned functionality includes:

* Invoice management
* Invoice items
* Invoice calculations
* Invoice status management
* PDF invoice generation
* Customer invoice history
* Dashboard statistics
* Improved validation
* Automated testing
* Production deployment

---

## 🎯 Project Goals

Smart Invoice Pro is being developed to demonstrate practical software engineering skills including:

* Full-stack web development
* Laravel application architecture
* Database design
* Authentication and authorization
* CRUD development
* Business logic
* Responsive interface development
* Git version control
* Software testing
* Production deployment

---

## 👨‍💻 Developer

**Elisha Oluwadayomi**

Full-Stack Software Developer | IT Professional

Portfolio: https://elishakhizs.github.io/

GitHub: https://github.com/elishkhizs

LinkedIn: www.linkedin.com/in/elisha-oluwadayomi-693a23336
