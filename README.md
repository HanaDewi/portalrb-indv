# Portal RB (Reformasi Birokrasi)

Web-based dashboard system developed to support the evaluation and monitoring of Bureaucratic Reform (RB). This application provides structured data processing, visualization, and reporting features to assist institutions in assessing their RB implementation.

---

## Features

* Dashboard for RB evaluation results
* Data visualization and scoring system
* Automated calculation of RB indicators
* Data management (input, conversion, and evaluation)
* Predikat (grading) classification
* Role-based access (admin/user)

---

## Tech Stack

* Backend: Laravel (PHP)
* Frontend: Blade, Tailwind CSS
* Database: MySQL
* Tools: Vite, Git

---

## Project Structure

```
app/
routes/
resources/views/
public/
config/
```

---

## Installation

1. Clone the repository:

   ```
   git clone https://github.com/HanaDewi/portalrb-indv.git
   ```

2. Navigate to project folder:

   ```
   cd portalrb-indv
   ```

3. Install dependencies:

   ```
   composer install
   npm install
   ```

4. Copy environment file:

   ```
   cp .env.example .env
   ```

5. Configure `.env`:

   * Set database name, username, and password

6. Generate application key:

   ```
   php artisan key:generate
   ```

7. Run migration:

   ```
   php artisan migrate
   ```

8. Run the application:

   ```
   php artisan serve
   ```

---

## Notes

* Sensitive data such as `.env` is not included in this repository.
* This project is shared for portfolio and educational purposes.

---

## Disclaimer

Some parts of this project may be adapted or simplified for public sharing. Internal or sensitive data has been removed.
