# Welcome to WebDevProject! 
Thank you for visiting our full-stack web development project page! We are building an e-commerce website that sells shoes to customers.

## <ins> Tech Stack </ins>
We are using Vue.js, Laravel (PHP) and Tailwind CSS. This is a modified version of the more popular VILT tack (Vue.js, Inertia, Laravel, Tailwind CSS). Instead of Inertia, our team is using RESTful APIs to decouple the frontend and the backend.

## <ins> Use of GitHub by Team 1 </ins>
 * Each team member works on their own <ins>***individual branch</ins> - please see individual branches for a more detailed list of contributions by each member***.

 * Moreover, there is a develoment branch, where features from different team members are combined and added.

 * Additionally, there is a main branch, to which working versions of the website are merged/ pushed from the development branch at regular intervals.

## <ins> Contents </ins>
This repository contains the following:
 * Code from both the back-end and front-end parts of the website.
   
 * A file to document any dependencies: _Dependencies.txt_.
   
 * A folder for database schema diagrams, and code that may be used to render these diagrams on dbdiagram.io.

## <ins> Deployment Guide </ins>

### 1. Deploy Laravel Backend

#### 1. Upload Laravel Project
Upload the entire Laravel project (including vendor/, app/, routes/, etc.) to:
```
/home/cs4team1/Backend/
```
If the file is too large, compress it into a zip file before uploading and then extract it.

#### 2. Modify /public_html/index.php
Copy the contents of Laravel's public/ folder to /public_html/ and modify the paths in index.php:

```php
// Before
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';

// After
require __DIR__.'/../Backend/vendor/autoload.php';
$app = require_once __DIR__.'/../Backend/bootstrap/app.php';
```

#### 3. Laravel Environment Configuration
Adjust the .env file:
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://cs4team1.cs2410-web01pvm.aston.ac.uk

DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=cs4team1_projectname
DB_USERNAME=cs4team1
DB_PASSWORD=yourpassword
```

#### 4. Database Setup
Use phpMyAdmin to create the database, or run:
```bash
cd ~/Backend
php artisan migrate
```

#### 5. Create Storage Symlink
```bash
php artisan storage:link
```
Or create manually:
```bash
ln -s /home/cs4team1/Backend/storage/app/public /home/cs4team1/public_html/storage
```

#### 6. Clear and Cache Laravel Settings
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan config:cache
php artisan route:cache
```

### 2. Deploy Vue Frontend

#### 1. Local Build
```bash
npm run build
```

#### 2. Upload Build Files
Upload all files from the dist/ folder to:
```
/home/cs4team1/public_html/
```

#### 3. API URL Configuration
Set in .env.production:
```env
VITE_API_BASE_URL=https://cs4team1.cs2410-web01pvm.aston.ac.uk/api
```

### 3. Local Development Setup (please Download Development Branch)

#### 1. Clean Up Existing Files
Delete the existing `vendor` folder and `composer.lock`:
```bash
rm -rf vendor
rm composer.lock
```

#### 2. Update Environment Configuration
In `.env` file, temporarily change:
```plaintext
CACHE_STORE=database
```
to:
```plaintext
CACHE_STORE=file
```

#### 3. Install Dependencies
If the `vendor` folder does not exist, install dependencies:
```bash
composer install --no-dev --optimize-autoloader
```

#### 4. Clear and Rebuild Cache
```bash
php artisan config:clear
php artisan cache:clear
php artisan config:cache
```

#### 5. Restore Cache Store Setting
Change the `CACHE_STORE` in `.env` back to:
```plaintext
CACHE_STORE=database
```

#### 6. Create Storage Link and Start Server
```bash
php artisan storage:link
php artisan serve --port=9000
```

### 4. Frontend Local Development Setup

#### 1. Install Dependencies
```sh
npm install
```

#### 2. Start Development Server
```sh
npm run dev
```

#### 3. Stop Development Server
Press `Control + C` to stop the development server.




