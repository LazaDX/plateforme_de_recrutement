# Investigator Recruitment Platform (Plate)

<p align="center">
  <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" alt="Laravel Logo" width="200"/>
</p>

## Description

Plate is a web platform designed for managing the recruitment of investigators.  
It allows administrators to create survey offers with dynamic fields to be filled out, and investigators to apply by completing these specific fields.  
The system provides a front-office interface for investigators and a back-office interface for administrators, with integrated analytical tools to track application statistics over different periods (week, month, quarter, year).

---

## Main Features

- Secure authentication for administrators and investigators  
- Creation and management of survey offers with dynamic fields  
- Application interface allowing investigators to apply and fill out specific fields  
- Analytical dashboard in the admin panel (weekly, monthly, quarterly, yearly stats)  
- Application management (acceptance/rejection)  
- User management (under development)  

---

## Technologies Used

- Backend: **Laravel**  
- Frontend: **Vue.js 3**, **Blade**, **Tailwind CSS**, **Alpine.js**  
- Database: **MySQL**  
- Tools and Libraries: Laravel Sanctum, Livewire, Ziggy, FontAwesome, Laravel Mix, Breeze  

---

## Installation

1. **Clone the repository:**

```bash
git clone https://github.com/your-username/plate.git
cd plate
```

2. **Install PHP dependencies with Composer:**

```bash
composer install
```

3. **Install JavaScript dependencies with NPM:**

```bash
npm install
```

4. **Configure Tailwind CSS and compile assets:**

```bash
npm run dev
# or for production
npm run production
```

5. **Copy the environment file and generate the application key:**

```bash
cp .env.example .env
php artisan key:generate
```

6. **Configure the database in .env:**

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=database_name
DB_USERNAME=username
DB_PASSWORD=password
```

7. **Run migrations and seeders:**

```bash
php artisan migrate --seed
```

8. **Clear caches to avoid conflicts:**

```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

9. **Start the project:**

```bash
php artisan serve
```

The application will be accessible by default at:  
`http://localhost:8000`

---

## Contribution

Contributions are welcome!  
To contribute:

1. Fork the repository  
2. Create a branch for your feature or fix (`feature/feature-name`)  
3. Make clear and descriptive commits  
4. Open a pull request for review  

Please adhere to coding best practices and the existing project structure.

---

## Acknowledgments and Credits

This project uses several open-source libraries and is built on Laravel, Vue.js, and Tailwind CSS.  
Thanks to all contributors and sponsors who make this project possible.
