# Auth System Project

## Prerequisites

### Install XAMPP and Composer
If you do not already have XAMPP and Composer installed, you can download them from the following links:

- [XAMPP Download](https://www.apachefriends.org/index.html)
- [Composer Download](https://getcomposer.org/)

## Installation Steps

### 1. Clone the Project Repository
```bash
# Clone the repository using Git
git clone <repository-link>
```

### 2. Update Composer
After cloning the repository, navigate to the project directory and update Composer dependencies:
```bash
cd <project-folder>
composer update
```

### 3. Move the Project to XAMPP's `htdocs` Directory
Copy the entire project folder and paste it into the `htdocs` directory of your XAMPP installation.
```
Example: C:\xampp\htdocs\auth_system
```

### 4. Start XAMPP and Set Up the Database
1. Open the XAMPP Control Panel and start **Apache** and **MySQL** services.
2. Go to [phpMyAdmin](http://localhost/phpmyadmin).
3. Import the SQL file:
   - Navigate to the `sql` directory in your project folder.
   - Import the `auth_system.sql` file into phpMyAdmin to set up the database.

### 5. Run the Project
Access the project in your web browser:
```
http://localhost/auth_system
```

## Email System Configuration
This project uses PHPMailer for sending emails, and it works both locally and on live servers.

### Setting Up Email Credentials
1. Generate an **App Password** from your Google Account.
   - Follow the guide: [Google App Password Guide](https://support.google.com/accounts/answer/185833?hl=en)
2. Add your email credentials to the project's configuration file.

### PHPMailer Usage
PHPMailer is already integrated into the project. Simply configure your email and password in the respective settings, and the email system will be ready to use.

---

That's it! Your project is now set up and running successfully.
