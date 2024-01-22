---

# Laravel Zoho CRM Integration Project

## Project Overview

This Laravel project integrates with Zoho CRM to provide a dashboard for managing deals and contacts. The application authenticates users via Zoho OAuth, retrieves deal information, and displays it on a custom dashboard. It supports token refresh and allows users to set and track their sales goals.

### Features

- **Zoho OAuth Authentication**: Securely authenticate users with Zoho CRM using OAuth.
- **Deals Management**: View and manage deal information fetched from Zoho CRM.
- **Sales Goal Tracking**: Users can set a personal sales goal and track progress towards this goal.
- **Dashboard**: Custom dashboard displaying deal progress and other relevant metrics.

## Prerequisites

- PHP >= 8.1
- Laravel Framework
- MySQL/MariaDB Database
- Composer for dependency management
- Zoho CRM account

## Installation

1. **Clone the Repository**
   
   ```bash
   git clone [repository-url]
   cd [project-directory]
   ```

2. **Install Dependencies**
   
   ```bash
   composer install
   ```

3. **Environment Setup**
   
   Copy `.env.example` to `.env` and configure your database and Zoho credentials.

   ```bash
   cp .env.example .env
   ```

4. **Generate Application Key**
   
   ```bash
   php artisan key:generate
   ```

5. **Run Migrations**
   
   ```bash
   php artisan migrate
   ```

6. **Start the Server**
   
   ```bash
   php artisan serve
   ```

## Configuration

- Set your Zoho CRM credentials in the `.env` file (ZOHO_CLIENT_ID, ZOHO_CLIENT_SECRET, etc.).
- Configure database settings in the `.env` file.
- Customize sales goals and other settings as per your requirement.

## Usage

- Access the application at `http://localhost:8000` (or your configured local domain).
- Log in using your Zoho CRM credentials.
- Navigate through the dashboard to view and manage deals.

## Contributing

Contributions to this project are welcome. Please fork the repository and submit pull requests for any enhancements.

## License

This project is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).

---
