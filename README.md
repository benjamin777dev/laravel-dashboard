---

# Agent Commander // CRM Integration Project

## Project Overview

This Laravel project integrates with Zoho CRM to provide a dashboard for managing deals and contacts. The application authenticates users via Zoho OAuth, retrieves deal information, and displays it on a custom dashboard. It supports token refresh and allows users to set and track their sales goals, manage their contacts, emails, and marketing efforts.

### Features

- **Zoho OAuth Authentication**: Securely authenticate users with Zoho CRM using OAuth.
- **Deals Management**: View and manage deal information fetched from Zoho CRM.
- **Sales Goal Tracking**: Users can set a personal sales goal and track progress towards this goal.
- **Dashboard**: Custom dashboard displaying deal progress and other relevant metrics.
- **Contact Management**: Manage contacts and their email addresses.
- **Email Management**: Manage emails and their attachments.
- **Marketing Management**: Manage marketing efforts and their attachments.
- **Token Refresh**: Automatically refresh expired tokens.


## Prerequisites

- PHP >= 8.2
- Laravel Framework (10.3+)
- MariaDB Database (10.6+)
- Composer for dependency management (latest)
- Zoho CRM account (v2 and v4-v6 calls supported)

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

2b. **Install NodeJS Dependencies**

   ```bash
   npm install
   ```

2c. **Compile SaSS Components via Vite/Mix**

   ```bash
   npm run build
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
   php artisan serve (if on local host)
   ```

6b. **On Server: Ensure .htaccess setup properly**

```bash
   <IfModule mod_rewrite.c>
      <IfModule mod_negotiation.c>
         Options -MultiViews
      </IfModule>

      RewriteEngine On

      RewriteCond %{REQUEST_FILENAME} -d [OR]
      RewriteCond %{REQUEST_FILENAME} -f
      RewriteRule ^ ^$1 [N]

      RewriteCond %{REQUEST_URI} (\.\w+$) [NC]
      RewriteRule ^(.*)$ public/$1

      RewriteCond %{REQUEST_FILENAME} !-d
      RewriteCond %{REQUEST_FILENAME} !-f
      RewriteRule ^ index.php
   </IfModule>
```
## Configuration

- Set your Zoho CRM credentials in the `.env` file (ZOHO_CLIENT_ID, ZOHO_CLIENT_SECRET, etc.).
- Configure database settings in the `.env` file.
- Customize sales goals and other settings as per your requirement.

## Usage

- Access the application at `http://localhost:8000` (or your configured local domain).
- NEW Database: Click 'Signup With Zoho' and finish oAuth process & set password; else login with your username and password (username is email).
- Navigate through the dashboard to view and manage deals.

## Contributing

Contributions to this project are welcome. Please fork the repository and submit pull requests for any enhancements.

## License

This project is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT), however, approval to push back to the repo from your fork is required.

## Author

Phillip Rumple, Colorado Home Realty
- zackrspv on GitHub

# Changelog
- initial: Initial release on GitHub
- 0.0.1: Login/Register Process
- 0.0.2: Login/Register Updates
- 0.0.3: Login/Register OAUTH Updates
- 0.0.4: OAUTH Issues resolved for refresh token
- 0.0.5: Zoho OAUTH nuanances resolved
- 0.0.6: Register with password external set
- 0.0.7: Middelware set
- 0.0.8: Dashboard Controller created
- 0.0.9: Dashboard updated with initial deal information
- 0.1.0: Deal Management Controller created
- 0.1.1: Deal Chart Updated/Created
- 0.1.2: Contact Management Controller created
- 0.1.3: Contact Management component updated on dashboard blade
- 0.1.4: Contact Management for base stats updated on blade
- 0.1.5: Updated overall layout for use by designer

# Future Updates
- Contact Management: Add/Edit/Delete Contacts
- Contact Management: Add/Edit/Delete Emails
- Contact Management: Add/Edit/Delete Marketing Efforts
- Sales Goal Tracking: Add/Edit/Delete Sales Goals
- Email Management: Add/Edit/Delete Emails and Templates
- Marketing Management: Add/Edit/Delete Marketing Efforts and Templates
- Profile Management: Update/Integrate 3rd party apps
- Integration: 3rd party application link with Agent Commander

---
