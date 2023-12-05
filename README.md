# News Aggregator API

## Introduction

This repository contains the codebase for a News Aggregator API. The API aggregates news articles from various sources using third-party APIs and stores them in a local database for efficient retrieval.

## Getting Started

### 1. Clone the Repository

```bash
git clone https://github.com/MuhammadAhsanAli/news_aggregator.git
```
### 2. Copy Environment Configuration

```bash
# Navigate to the project directory
cd news_aggregator

# Make a copy of the provided env.example file and rename it to .env
cp env.example .env
```
### 3. Set Database Configuration

To configure the database for the News Aggregator API, follow these steps:

Open the `.env` file using your preferred text editor.

    ```env
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=your_database_name
    DB_USERNAME=your_database_user
    DB_PASSWORD=your_database_password
    ```
### 4. Create Database

Run the following command to create the database:

```bash
php artisan migrate
```

### 5. Set API Keys
To set API keys for the News Aggregator API, update the .env file as follows:

```bash
GUARDIAN_API_KEY="your_guardian_api_key"
NEWS_API_KEY="your_news_api_key"
NY_TIMES_API_KEY="your_ny_times_api_key"
```

### 6. Install Dependencies
Run the following command to install project dependencies:

```bash
composer update
```

### 7. Set Permissions
Set the appropriate permissions for the storage folder:

```bash
chmod -R 775 storage
```

### 8. Generate Application Key
Generate the Laravel application key:

```bash
php artisan key:generate
```

### 9. Seed Database
Seed the database with initial data using the following command:

```bash
php artisan db:seed --class=SourceSeeder
```

### 10. Optimize Application
Optimize the application for better performance:

```bash
php artisan optimize
```

### 11. Fetch Data from News Live Site
Run the command to fetch data from the live news site:

```bash
php artisan news:update
```

## API Documentation
Use the provided Postman collection file attached to the email for API requests and documentation. Alternatively, you can use the following link to fetch API data from the local database:
```bash
http://yourhost.com/api/articles?search=Oxford University&fromDate=2023-12-03&toDate=2023-12-03&category=Arts&source=ny_times&author=John
```
