# Easy Bell Checkout System

### Overview
The Easy Bell Checkout System is a command-line application built with Laravel for managing a checkout process. It allows users to scan items and apply pricing rules, and calculates the total cost based on the scanned items and rules.

### Features
- Scan items and apply pricing rules.
- Display current status of scanned items and their subtotals.
- Print a summary with total and applied rules.

### Prerequisites
- Composer
- Docker and Docker Compose for containerization (Laravel Sail)
- PHP (8.2 or later) and Composer (for local development if not using Docker).

### Setup and Installation
1. Clone the Repository
```
git clone https://github.com/yourusername/easy_bell_checkout_system.git
```

2. Open the project directory
```
cd easy_bell_checkout_system
```
3. Install packages/dependencies
```
composer install
```
3. Build and Start Docker Containers
```
./vendor/bin/sail up -d
```
4. Enter the app the container
```
docker exec -it easy_bell_checkout_system-app-1 /bin/bash
```
5. Run the dabase migrations and seeders
```
php artisan migrate:fresh --seed
```
6. Run the Test
* Please make sure you have Unit directory in side the tests directory
* Please be noted that the test is running on the actual database, so make sure to run the database seeding.
```
./vendor/bin/phpunit
```

6. Enter the checkout cli
```
php artisan checkout:run
```

![Screenshot from 2024-08-05 06-22-58](https://github.com/user-attachments/assets/a038aa18-b547-4a1f-887f-f10b429446af)

![Screenshot from 2024-08-05 06-25-05](https://github.com/user-attachments/assets/55a7f222-5aab-4fb1-9077-adaad9edc96d)

