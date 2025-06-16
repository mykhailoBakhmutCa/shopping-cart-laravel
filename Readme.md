# Shopping Cart Laravel Project

---

## Local Development

Follow these steps to get the project up and running on your local machine:

1.  **Clone the Repository:**

    ```bash
    git clone [https://github.com/mykhailoBakhmutCa/shopping-cart-laravel.git](https://github.com/mykhailoBakhmutCa/shopping-cart-laravel.git)
    cd shopping-cart-laravel
    ```

2.  **Install Composer Dependencies:**

    ```bash
    composer install
    ```

    This command will install all the necessary PHP packages.

3.  **Create `.env` File:**
    Copy the `.env.example` file and rename it to `.env`.

    ```bash
    cp .env.example .env
    ```

4.  **Generate Application Key:**

    ```bash
    php artisan key:generate
    ```

5.  **Configure Database (SQLite):**
    Ensure your `.env` file has the following settings for SQLite:

    ```
    DB_CONNECTION=sqlite
    # DB_HOST=127.0.0.1
    # DB_PORT=3306
    # DB_DATABASE=laravel
    # DB_USERNAME=root
    # DB_PASSWORD=
    ```

    Comment out or remove any lines related to MySQL/PostgreSQL if they exist.

    Next, create an empty SQLite database file:

    ```bash
    touch database/database.sqlite
    ```

6.  **Run Database Migrations:**

    ```bash
    php artisan migrate
    ```

    This will create the necessary tables in your database.

7.  **Seed the Database:**

    ```bash
    php artisan db:seed
    ```

    This command will populate your database with initial test items.

8.  **Build Front-End Assets:**

    ```bash
    npm install && npm run build
    ```

    This will install JavaScript dependencies and compile your front-end assets.

9.  **Start Laravel Development Server:**

    ```bash
    php artisan serve
    ```

    The application will be accessible at `http://127.0.0.1:8000` (or another port if specified in the command output).

10. **Scheduler Setup (for Cart Cleanup):**
    To enable automatic cleanup of old shopping cart entries, set your `SESSION_LIFETIME` in the `.env` file to 2-3 minutes to quickly observe the results.

    Then, run the scheduler:

    ```bash
    php artisan schedule:work
    ```
