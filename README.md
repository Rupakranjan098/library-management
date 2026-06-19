# Library Management System

A sleek, state-of-the-art Library Management System (LMS) built with **Laravel 11**, PHP, and **Tailwind CSS**. It features a modern, premium dark-themed administrative dashboard alongside an interactive public catalog system with live reservation features.

---

## 🚀 Features

### 📋 Administrative Dashboard
- **Real-time Analytics**: High-level visual statistics for books, active members, active borrowings, and upcoming events.
- **Resource Management**: Complete CRUD interfaces for:
  - **Books**: Manage titles, authors, categories, ISBNs, and total vs. available copies.
  - **Authors**: Comprehensive author biographies and information.
  - **Categories**: Organize library resources under custom genres/topics.
  - **Members**: Register and manage profiles of library cardholders.
- **Borrowing Records**: Monitor and manage lending activity, automatically tracking active, returned, and overdue books with real-time stock adjustments.
- **Reports & Exporting**: Generate administrative reports and export data seamlessly.
- **Settings**: System configuration options.

### 🌐 Public Catalog & Reservation
- **Dynamic Catalog**: Interactive catalog interface for searching library resources by title, author, or ISBN.
- **Search & Filter**: Search text filters combined with category navigation tabs.
- **Live Reservation System**: Registered members can reserve available books directly from the web interface, utilizing automated validation and copy counters.

---

## 🛠️ Tech Stack & Design System
- **Backend Framework**: Laravel 11.x (PHP 8.2+)
- **Frontend / Styling**: Tailwind CSS, Google Fonts (Inter)
- **Database**: MySQL (relational indexing for books, members, and borrowings)
- **UI/UX Design**: Sleek glassmorphism card panels, micro-animations, vibrant glowing indicators, and context-aware session toasts (Created, Updated, and Deleted).

---

## 📦 Installation & Setup

1. **Clone the Repository**:
   ```bash
   git clone https://github.com/Rupakranjan098/library-management.git
   cd library-management
   ```

2. **Install Dependencies**:
   ```bash
   composer install
   npm install
   ```

3. **Configure Environment File**:
   Copy `.env.example` to `.env` and configure your database settings.
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Run Database Migrations & Seeders**:
   ```bash
   php artisan migrate --seed
   ```

5. **Start the Local Development Server**:
   ```bash
   php artisan serve
   ```
   Access the application in your browser at `http://127.0.0.1:8000`.

---

## 🤝 Contributing
Contributions are welcome! Feel free to open issues or submit pull requests to enhance features or styling.

## 📄 License
This project is open-sourced software licensed under the [MIT license](LICENSE).
