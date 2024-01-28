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

3. **Install NodeJS Dependencies**

   ```bash
   npm install
   ```

4. **Compile SaSS Components via Vite/Mix**

   ```bash
   npm run build
   ```

5. **Environment Setup**
   
   Copy `.env.example` to `.env` and configure your database and Zoho credentials.

   ```bash
   cp .env.example .env
   ```

6. **Generate Application Key**
   
   ```bash
   php artisan key:generate
   ```

7. **Run Migrations**
   
   ```bash
   php artisan migrate
   ```

8. **Start the Server**
   
   ```bash
   php artisan serve (if on local host)
   ```

9. **On Server: Ensure .htaccess setup properly**

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

10. Update SH Files

Update your clearCache.sh file to point to the proper directories where your files are located - this will allow you to properly set your cache levels once cleared on the webhost. Most webhosts won't have an issue, and this file isn't needed, and can be commented out within the CI/CD infrastructure; however, some LAMP stacks, most notably Litespeed Servers, LAMP Stacks and some Docker Stacks, reset permissions on a regular basis, and can cause issues w/ your deployments. So running this as part of your deployment process will ensure those permissions don't get changed. 

- **Check Group** Make sure the group and user that is running the command makese sense - on bitnami LAMP for example, it is `bitnami:daemon``, but on most, it may be www-data:www-data; you can always check that by looking at your `ps aux` command.

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

## CI/CD Process

By default this is meant to run in a container or a LAMP stack - for simplicity, we built it against the bitnami LAMP stack that can run on most servers, including lightsail. The CI/CD process is located in the .github/workflows folder, and should be updated to your desired handling of publishing of your application. By default it will: 

- Checkout
- Run necessary commands for composer and npm
- Send data over to your web host directory (configure rsync for your use case)
- Set proper end commands (we have included several .sh scripts for you)
- Clear and Update Laravel Caches and Config Caches

You should take the chance after checkout to update to your needs, so that when you are checking in code, you can quickly deploy it to the server of your choice.

## Author

Phillip Rumple, Colorado Home Realty
- zackrspv on GitHub

# Changelog
- initial: Initial release on GitHub
- *0.0.1.0*: Login/Register Process
- *0.0.1.1*: Login/Register Updates
- *0.0.1.2*: Login/Register OAUTH Updates
- *0.0.1.3*: OAUTH Issues resolved for refresh token
- *0.0.1.4*: Zoho OAUTH nuanances resolved
- *0.0.1.5*: Register with password external set
- *0.0.1.6*: Middelware set
- *0.0.2.0*: Dashboard Controller created
- *0.0.2.1*: Dashboard updated with initial deal information
- *0.0.3.0*: Deal Management Controller created
- *0.0.3.1*: Deal Chart Updated/Created
- *0.0.4.0*: Contact Management Controller created
- *0.0.4.1*: Contact Management component updated on dashboard blade
- *0.0.4.2*: Contact Management for base stats updated on blade
- *0.0.4.3*: Updated overall layout for use by designer

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
